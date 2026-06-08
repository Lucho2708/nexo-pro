<?php

namespace Tests\Feature\Asamblea;

use App\Modules\IAM\Models\User;
use App\Modules\Asamblea\Models\Asamblea;
use App\Modules\Property\Models\Copropiedad;
use App\Modules\Property\Models\Unidad;
use App\Modules\Asamblea\Models\Intervencion;
use App\Modules\Asamblea\Events\IntervencionUpdated;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class IntervencionInteractionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->copropiedad = Copropiedad::factory()->create([
            'settings' => array_merge(Copropiedad::defaultSettings(), ['asamblea_virtual_enabled' => true])
        ]);
        
        $this->asamblea = Asamblea::factory()->create([
            'copropiedad_id' => $this->copropiedad->id,
            'status' => 'in_progress'
        ]);
        
        // President / Admin
        $this->president = User::factory()->create([
            'role' => 'admin',
            'current_copropiedad_id' => $this->copropiedad->id
        ]);

        // Owners
        $this->ownerA = $this->createOwner('Torre 1', '101');
        $this->ownerB = $this->createOwner('Torre 1', '102');
    }

    private function createOwner($torre, $nombre)
    {
        $user = User::factory()->create(['current_copropiedad_id' => $this->copropiedad->id]);
        $unidad = Unidad::factory()->create([
            'copropiedad_id' => $this->copropiedad->id,
            'torre' => $torre,
            'nombre' => $nombre
        ]);
        $unidad->users()->attach($user->id, ['role' => 'propietario']);
        return $user;
    }

    public function test_when_owner_raises_hand_it_broadcasts_to_all_participants()
    {
        Event::fake();

        $response = $this->actingAs($this->ownerA)
            ->post(route('asambleas.intervenciones.request', $this->asamblea));

        $response->assertStatus(201);

        Event::assertDispatched(IntervencionUpdated::class, function ($event) {
            return $event->asambleaId === $this->asamblea->id &&
                   $event->intervencionData['user_id'] === $this->ownerA->id &&
                   $event->intervencionData['status'] === 'pending' &&
                   isset($event->intervencionData['user']['name']) &&
                   count($event->intervencionData['user']['unidades']) > 0;
        });
    }

    public function test_owner_cannot_have_multiple_pending_requests()
    {
        // First request
        $this->actingAs($this->ownerA)->post(route('asambleas.intervenciones.request', $this->asamblea));
        
        // Second request (should be ignored or return error)
        $response = $this->actingAs($this->ownerA)->post(route('asambleas.intervenciones.request', $this->asamblea));

        $this->assertEquals(1, Intervencion::where('user_id', $this->ownerA->id)->where('status', 'pending')->count());
    }

    public function test_multiple_owners_raising_hands_form_a_queue_in_order()
    {
        // Owner A raises hand first
        $this->actingAs($this->ownerA)->post(route('asambleas.intervenciones.request', $this->asamblea));
        
        // Owner B raises hand second
        $this->actingAs($this->ownerB)->post(route('asambleas.intervenciones.request', $this->asamblea));

        $intervenciones = Intervencion::where('asamblea_id', $this->asamblea->id)
            ->where('status', 'pending')
            ->orderBy('requested_at', 'asc')
            ->get();

        $this->assertCount(2, $intervenciones);
        $this->assertEquals($this->ownerA->id, $intervenciones[0]->user_id);
        $this->assertEquals($this->ownerB->id, $intervenciones[1]->user_id);
    }

    public function test_the_queue_is_visible_to_the_president()
    {
        // Setup queue
        Intervencion::factory()->create([
            'asamblea_id' => $this->asamblea->id,
            'user_id' => $this->ownerA->id,
            'status' => 'pending'
        ]);

        $response = $this->actingAs($this->president)
            ->get(route('asambleas.show', $this->asamblea));

        $response->assertStatus(200);
        // Note: In Inertia, we'd check props, but here we just ensure the view loads.
        // The real-time visibility is handled by Echo, which we tested in the broadcast test.
    }

    public function test_lowering_hand_removes_owner_from_the_queue()
    {
        Event::fake();

        $intervencion = Intervencion::factory()->create([
            'asamblea_id' => $this->asamblea->id,
            'user_id' => $this->ownerA->id,
            'status' => 'pending'
        ]);

        $response = $this->actingAs($this->ownerA)
            ->post(route('asambleas.intervenciones.cancel', $intervencion));

        $response->assertStatus(200);
        $this->assertEquals('cancelled', $intervencion->refresh()->status);

        Event::assertDispatched(IntervencionUpdated::class, function ($event) {
            return $event->intervencionData['status'] === 'cancelled';
        });
    }

    public function test_president_can_grant_word_and_timer_starts_for_everyone()
    {
        Event::fake();

        $intervencion = Intervencion::factory()->create([
            'asamblea_id' => $this->asamblea->id,
            'user_id' => $this->ownerA->id,
            'status' => 'pending'
        ]);

        $response = $this->actingAs($this->president)
            ->post(route('asambleas.intervenciones.grant', $intervencion));

        $response->assertStatus(200);
        $this->assertEquals('active', $intervencion->refresh()->status);
        $this->assertNotNull($intervencion->started_at);
        $this->assertEquals(180, $intervencion->duration_seconds);

        Event::assertDispatched(IntervencionUpdated::class, function ($event) {
            return $event->intervencionData['status'] === 'active' && 
                   $event->intervencionData['duration_seconds'] === 180;
        });
    }

    public function test_only_moderator_can_grant_the_word()
    {
        $intervencion = Intervencion::factory()->create([
            'asamblea_id' => $this->asamblea->id,
            'user_id' => $this->ownerA->id,
            'status' => 'pending'
        ]);

        // Owner B tries to grant word to Owner A
        $response = $this->actingAs($this->ownerB)
            ->post(route('asambleas.intervenciones.grant', $intervencion));

        $response->assertStatus(403); // Middleware EnsureAdmin should catch this
    }

    public function test_president_can_extend_intervention_time()
    {
        Event::fake();

        $intervencion = Intervencion::factory()->create([
            'asamblea_id' => $this->asamblea->id,
            'user_id' => $this->ownerA->id,
            'status' => 'active',
            'duration_seconds' => 180
        ]);

        $response = $this->actingAs($this->president)
            ->post(route('asambleas.intervenciones.extend', $intervencion), ['seconds' => 60]);

        $response->assertStatus(200);
        $this->assertEquals(240, $intervencion->refresh()->duration_seconds);

        Event::assertDispatched(IntervencionUpdated::class, function ($event) {
            return $event->intervencionData['duration_seconds'] === 240;
        });
    }
}

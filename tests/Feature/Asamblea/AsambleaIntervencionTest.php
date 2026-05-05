<?php

namespace Tests\Feature\Asamblea;

use App\Models\Asamblea;
use App\Models\Copropiedad;
use App\Models\Unidad;
use App\Modules\IAM\Models\User;
use App\Models\Intervencion;
use App\Events\IntervencionUpdated;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class AsambleaIntervencionTest extends TestCase
{
    use RefreshDatabase;

    protected Copropiedad $copropiedad;
    protected Asamblea $asamblea;
    protected User $user;
    protected Unidad $unidad;

    protected function setUp(): void
    {
        parent::setUp();
        Event::fake([IntervencionUpdated::class]);

        $this->copropiedad = Copropiedad::factory()->create([
            'settings' => array_merge(Copropiedad::defaultSettings(), ['asamblea_virtual_enabled' => true])
        ]);
        $this->asamblea = Asamblea::factory()->create([
            'copropiedad_id' => $this->copropiedad->id,
            'status' => 'in_progress'
        ]);
        
        $this->user = User::factory()->create([
            'role' => 'owner',
            'current_copropiedad_id' => $this->copropiedad->id
        ]);
        $this->unidad = Unidad::factory()->create(['copropiedad_id' => $this->copropiedad->id]);
        $this->user->unidades()->attach($this->unidad->id, ['role' => 'propietario']);
    }

    public function test_user_can_request_the_word(): void
    {
        $response = $this->actingAs($this->user)->post(route('asambleas.intervenciones.request', $this->asamblea->id));

        $response->assertStatus(201);
        $this->assertDatabaseHas('intervenciones', [
            'asamblea_id' => $this->asamblea->id,
            'user_id' => $this->user->id,
            'status' => 'pending'
        ]);

        Event::assertDispatched(IntervencionUpdated::class);
    }

    public function test_user_cannot_request_word_twice_if_pending(): void
    {
        // Primera petición
        $this->actingAs($this->user)->post(route('asambleas.intervenciones.request', $this->asamblea->id));
        
        // Segunda petición
        $response = $this->actingAs($this->user)->post(route('asambleas.intervenciones.request', $this->asamblea->id));

        $response->assertStatus(201); // Retorna la existente
        $this->assertEquals(1, Intervencion::count());
    }

    public function test_admin_can_grant_the_word(): void
    {
        $intervencion = Intervencion::factory()->create([
            'asamblea_id' => $this->asamblea->id,
            'user_id' => $this->user->id,
            'status' => 'pending'
        ]);

        $admin = User::factory()->create([
            'role' => 'admin',
            'current_copropiedad_id' => $this->copropiedad->id
        ]);
        $admin->managedCopropiedades()->attach($this->copropiedad->id);

        $response = $this->actingAs($admin)->post("/asambleas/intervenciones/{$intervencion->id}/grant");

        $response->assertStatus(200);
        $this->assertDatabaseHas('intervenciones', [
            'id' => $intervencion->id,
            'status' => 'active'
        ]);

        Event::assertDispatched(IntervencionUpdated::class);
    }

    public function test_admin_can_close_intervention(): void
    {
        $intervencion = Intervencion::factory()->create([
            'asamblea_id' => $this->asamblea->id,
            'user_id' => $this->user->id,
            'status' => 'active',
            'started_at' => now()->subMinutes(2)
        ]);

        $admin = User::factory()->create([
            'role' => 'admin',
            'current_copropiedad_id' => $this->copropiedad->id
        ]);
        $admin->managedCopropiedades()->attach($this->copropiedad->id);

        $response = $this->actingAs($admin)->post("/asambleas/intervenciones/{$intervencion->id}/close", [
            'force' => false
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('intervenciones', [
            'id' => $intervencion->id,
            'status' => 'completed'
        ]);

        Event::assertDispatched(IntervencionUpdated::class);
    }

    public function test_user_can_close_their_own_active_intervention(): void
    {
        $intervencion = Intervencion::factory()->create([
            'asamblea_id' => $this->asamblea->id,
            'user_id' => $this->user->id,
            'status' => 'active',
            'started_at' => now()
        ]);

        $response = $this->actingAs($this->user)->post("/asambleas/intervenciones/{$intervencion->id}/close", [
            'force' => false
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('intervenciones', [
            'id' => $intervencion->id,
            'status' => 'completed'
        ]);
    }
}

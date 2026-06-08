<?php

namespace Tests\Feature;

use App\Modules\IAM\Models\User;
use App\Modules\Asamblea\Models\Asamblea;
use App\Modules\Property\Models\Copropiedad;
use App\Modules\Property\Models\Unidad;
use App\Modules\Asamblea\Models\Intervencion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class IntervencionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        \Illuminate\Support\Facades\Event::fake([
            \App\Modules\Asamblea\Events\IntervencionUpdated::class,
            \App\Modules\Asamblea\Events\HandRaised::class,
        ]);
        
        $this->copropiedad = Copropiedad::factory()->create([
            'settings' => array_merge(Copropiedad::defaultSettings(), ['asamblea_virtual_enabled' => true])
        ]);
        
        $this->asamblea = Asamblea::factory()->create([
            'copropiedad_id' => $this->copropiedad->id,
            'status' => 'in_progress'
        ]);
        
        $this->user = User::factory()->create(['current_copropiedad_id' => $this->copropiedad->id]);
        $this->unidad = Unidad::factory()->create(['copropiedad_id' => $this->copropiedad->id]);
        $this->unidad->users()->attach($this->user->id, ['role' => 'propietario']);
    }

    public function test_a_resident_can_request_the_word_only_if_assembly_is_in_progress()
    {
        // Escenario: Asamblea pausada
        $this->asamblea->update(['status' => 'scheduled']);
        
        $response = $this->actingAs($this->user)
            ->post(route('asambleas.intervenciones.request', $this->asamblea));
            
        $response->assertStatus(403);
        $response->assertJsonPath('message', 'La asamblea debe estar en curso para solicitar la palabra.');

        // Escenario: Asamblea activa
        $this->asamblea->update(['status' => 'in_progress']);
        $response = $this->actingAs($this->user)
            ->post(route('asambleas.intervenciones.request', $this->asamblea));
            
        $response->assertStatus(201);
        $this->assertDatabaseHas('intervenciones', [
            'user_id' => $this->user->id,
            'status' => 'pending'
        ]);
    }

    public function test_a_moderator_cannot_grant_the_word_if_someone_else_is_speaking()
    {
        // Ya hay alguien hablando
        Intervencion::factory()->create([
            'asamblea_id' => $this->asamblea->id,
            'user_id' => User::factory()->create()->id,
            'status' => 'active'
        ]);

        $nuevaSolicitud = Intervencion::factory()->create([
            'asamblea_id' => $this->asamblea->id,
            'user_id' => $this->user->id,
            'status' => 'pending'
        ]);

        $admin = User::factory()->create([
            'role' => 'admin',
            'current_copropiedad_id' => $this->copropiedad->id
        ]);

        $response = $this->actingAs($admin)
            ->post(route('asambleas.intervenciones.grant', $nuevaSolicitud));

        $response->assertStatus(422);
        $response->assertJsonPath('message', 'Ya hay una intervención activa. Debe finalizarla primero.');
    }

    public function test_it_calculates_and_logs_the_exact_duration_of_the_intervention()
    {
        $intervencion = Intervencion::factory()->create([
            'asamblea_id' => $this->asamblea->id,
            'user_id' => $this->user->id,
            'status' => 'active',
            'started_at' => now()->subSeconds(134) // 2m 14s atrás
        ]);

        $admin = User::factory()->create([
            'role' => 'admin',
            'current_copropiedad_id' => $this->copropiedad->id
        ]);

        $response = $this->actingAs($admin)
            ->post(route('asambleas.intervenciones.close', $intervencion));

        $response->assertStatus(200);
        
        $intervencion->refresh();
        $this->assertEquals('completed', $intervencion->status);
        $this->assertGreaterThanOrEqual(134, $intervencion->duration_seconds);
        $this->assertLessThanOrEqual(136, $intervencion->duration_seconds);
    }

    public function test_a_resident_can_cancel_their_own_pending_request()
    {
        $intervencion = Intervencion::factory()->create([
            'asamblea_id' => $this->asamblea->id,
            'user_id' => $this->user->id,
            'status' => 'pending'
        ]);

        $response = $this->actingAs($this->user)
            ->post(route('asambleas.intervenciones.cancel', $intervencion));

        $response->assertStatus(200);
        $this->assertDatabaseHas('intervenciones', [
            'id' => $intervencion->id,
            'status' => 'cancelled'
        ]);
    }

    public function test_a_moderator_can_force_close_an_intervention()
    {
        $intervencion = Intervencion::factory()->create([
            'asamblea_id' => $this->asamblea->id,
            'user_id' => $this->user->id,
            'status' => 'active',
            'started_at' => now()
        ]);

        $admin = User::factory()->create([
            'role' => 'admin',
            'current_copropiedad_id' => $this->copropiedad->id
        ]);

        $response = $this->actingAs($admin)
            ->post(route('asambleas.intervenciones.close', $intervencion), [
                'force' => true,
                'notes' => 'Incumplimiento de normas'
            ]);

        $response->assertStatus(200);
        $intervencion->refresh();
        $this->assertEquals('forced_close', $intervencion->status);
        $this->assertEquals('Incumplimiento de normas', $intervencion->notes);
    }

    public function test_a_moderator_can_extend_an_intervention_time()
    {
        $intervencion = Intervencion::factory()->create([
            'asamblea_id' => $this->asamblea->id,
            'user_id' => $this->user->id,
            'status' => 'active',
            'duration_seconds' => 180 // 3 min iniciales
        ]);

        $admin = User::factory()->create([
            'role' => 'admin',
            'current_copropiedad_id' => $this->copropiedad->id
        ]);

        $response = $this->actingAs($admin)
            ->post(route('asambleas.intervenciones.extend', $intervencion), [
                'seconds' => 60
            ]);

        $response->assertStatus(200);
        $intervencion->refresh();
        // Nota: duration_seconds aquí lo estamos usando como el "Límite" en el frontend
        $this->assertEquals(240, $intervencion->duration_seconds);
    }
}

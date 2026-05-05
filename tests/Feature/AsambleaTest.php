<?php

namespace Tests\Feature;

use App\Modules\IAM\Models\User;
use App\Models\Asamblea;
use App\Models\Copropiedad;
use App\Models\Unidad;
use App\Models\Pregunta;
use App\Models\Opcion;
use App\Events\HandRaised;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class AsambleaTest extends TestCase
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
            'fecha' => now()->format('Y-m-d'),
            'status' => 'in_progress'
        ]);
        
        $this->user = User::factory()->create([
            'current_copropiedad_id' => $this->copropiedad->id
        ]);
        $this->unidad = Unidad::factory()->create([
            'copropiedad_id' => $this->copropiedad->id
        ]);
        
        $this->unidad->users()->attach($this->user->id, ['role' => 'propietario']);

        // Inicializar ecosistema dinámico para que las tablas existan durante el test
        app(\App\Services\AsambleaService::class)->initializeDynamicEcosystem($this->asamblea);
    }

    public function test_an_owner_can_access_the_assembly_room()
    {
        $response = $this->actingAs($this->user)
            ->get(route('asambleas.show', $this->asamblea));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('Asamblea/Show')
            ->has('asamblea')
            ->has('token')
        );
    }

    public function test_it_blocks_access_if_another_device_is_connected()
    {
        // Simulamos que ya hay una conexión
        Cache::put("asamblea_conn_unidad_{$this->unidad->id}", 'another-user-id');

        $response = $this->actingAs($this->user)
            ->get(route('asambleas.show', $this->asamblea));

        // Debe renderizar la vista de AccessDenied
        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('Asamblea/AccessDenied')
            ->where('can_reset', true)
        );
    }

    public function test_it_allows_the_same_user_to_reconnect()
    {
        // Simulamos que el MISMO usuario ya estaba conectado
        Cache::put("asamblea_conn_unidad_{$this->unidad->id}", $this->user->id);

        $response = $this->actingAs($this->user)
            ->get(route('asambleas.show', $this->asamblea));

        // Debe dejarlo pasar directamente (traspaso de sesión)
        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('Asamblea/Show'));
    }

    public function test_it_can_reset_a_connection()
    {
        Cache::put("asamblea_conn_unidad_{$this->unidad->id}", 'some-user');

        $response = $this->actingAs($this->user)
            ->post(route('asambleas.reset-connection', $this->asamblea));

        $this->assertFalse(Cache::has("asamblea_conn_unidad_{$this->unidad->id}"));
        $response->assertRedirect();
    }

    public function test_it_broadcasts_intervencion_updated_event()
    {
        Event::fake();

        $response = $this->actingAs($this->user)
            ->post(route('asambleas.intervenciones.request', $this->asamblea));

        $response->assertStatus(201);
        
        Event::assertDispatched(\App\Events\IntervencionUpdated::class);
    }

    public function test_an_owner_can_cast_a_vote()
    {
        $pregunta = new Pregunta();
        $pregunta->asamblea_id = $this->asamblea->id;
        $pregunta->titulo = '¿Acepta el presupuesto?';
        $pregunta->status = 'open';
        $pregunta->save();

        $opcion = new Opcion();
        $opcion->pregunta_id = $pregunta->id;
        $opcion->titulo = 'Sí';
        $opcion->save();

        $response = $this->actingAs($this->user)
            ->post(route('asambleas.votar', $pregunta), [
                'opcion_id' => $opcion->id
            ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas($this->asamblea->getLogTableName(), [
            'user_id' => $this->user->id,
            'event_type' => 'vote'
        ]);
    }
}

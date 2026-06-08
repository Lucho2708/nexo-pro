<?php

namespace Tests\Feature;

use App\Modules\Asamblea\Models\Asamblea;
use App\Modules\Property\Models\Copropiedad;
use App\Modules\IAM\Models\User;
use App\Modules\Asamblea\Models\Pregunta;
use App\Modules\Asamblea\Models\Opcion;
use App\Services\Tenant\StandaloneOnboardingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class StandaloneFullFlowTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_full_standalone_assembly_flow_from_onboarding_to_voting()
    {
        $onboardingService = app(StandaloneOnboardingService::class);

        // 1. CONFIGURACIÓN INICIAL (Admin crea asamblea externa)
        $copropiedad = Copropiedad::factory()->create([
            'settings' => array_merge(Copropiedad::defaultSettings(), ['asamblea_virtual_enabled' => true])
        ]);
        $asamblea = Asamblea::factory()->create([
            'copropiedad_id' => $copropiedad->id,
            'status' => 'in_progress'
        ]);

        $externalUnits = [
            ['torre' => 'A', 'apto' => '101', 'nombre' => 'Juan', 'coeficiente' => 30.0, 'documento' => '12345678'],
            ['torre' => 'A', 'apto' => '102', 'nombre' => 'Maria', 'coeficiente' => 70.0, 'documento' => '87654321'],
        ];

        // 2. IMPORTACIÓN DE DATOS
        $onboardingService->importExternalData($asamblea, $externalUnits);
        $shortId = substr($asamblea->id, 0, 8);

        // 3. ACCESO DE PROPIETARIO (Login Opción B)
        $response = $this->post(route('asambleas.guest.attempt', $asamblea), [
            'nombre_unidad' => 'A - 101',
            'documento_ultimos_4' => '5678'
        ]);

        $response->assertRedirect(route('asambleas.show', $asamblea));
        $this->assertAuthenticated();
        
        $user = auth()->user()->refresh();
        $this->assertEquals('invitado', $user->role);

        // 4. CREACIÓN DE PREGUNTA
        $pregunta = Pregunta::create([
            'asamblea_id' => $asamblea->id,
            'titulo' => '¿Aprueba el presupuesto?',
            'status' => 'open'
        ]);
        $opcion = Opcion::create([
            'pregunta_id' => $pregunta->id,
            'titulo' => 'SÍ'
        ]);

        // 5. VOTACIÓN DEL INVITADO
        $voteResponse = $this->actingAs($user)
            ->post(route('asambleas.votar', $pregunta), [
                'opcion_id' => $opcion->id
            ]);

        $voteResponse->assertStatus(200);

        // 6. VERIFICACIÓN DE RESULTADOS
        $this->assertDatabaseHas("asvotos_{$shortId}", [
            'pregunta_id' => $pregunta->id,
            'opcion_id' => $opcion->id,
            'peso' => 30.0
        ]);

        // 7. RESULTADOS TOTALES
        $resultsResponse = $this->get(route('asambleas.preguntas.results', $pregunta));
        $resultsResponse->assertJsonFragment([
            'total_participating_weight' => "30.00000"
        ]);
    }
}

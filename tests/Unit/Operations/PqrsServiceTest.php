<?php

namespace Tests\Unit\Operations;

use App\Modules\IAM\Models\User;
use App\Modules\Operations\Models\Pqrs;
use App\Modules\Property\Models\Copropiedad;
use App\Modules\Property\Models\Unidad;
use App\Services\Pqrs\PqrsService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PqrsServiceTest extends TestCase
{
    use RefreshDatabase;

    private PqrsService $service;
    private Copropiedad $copropiedad;
    private Unidad $unidad;
    private User $resident;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = app(PqrsService::class);

        $this->copropiedad = Copropiedad::create([
            'nombre' => 'Torres del Parque Unit',
            'nit' => '900123456-1',
            'direccion' => 'Calle 100 # 15-20',
            'ciudad' => 'Bogotá',
            'license_status' => 'active',
            'license_expires_at' => now()->addYear(),
        ]);

        $this->unidad = Unidad::create([
            'copropiedad_id' => $this->copropiedad->id,
            'nombre' => 'Apto 402',
            'torre' => '1',
            'tipo' => 'apartamento',
            'coeficiente' => 0.025,
            'area_m2' => 85.5,
        ]);

        $this->resident = User::factory()->create([
            'role' => 'owner',
            'current_copropiedad_id' => $this->copropiedad->id,
        ]);
    }

    public function test_computes_tactical_metrics_accurately(): void
    {
        // 2 Abiertos (1 de ellos urgente)
        Pqrs::create([
            'copropiedad_id' => $this->copropiedad->id,
            'unidad_id' => $this->unidad->id,
            'user_id' => $this->resident->id,
            'tipo' => 'peticion',
            'asunto' => 'Copia de reglamento',
            'mensaje' => 'Solicito copia',
            'prioridad' => 'baja',
            'estado' => 'abierto',
        ]);

        Pqrs::create([
            'copropiedad_id' => $this->copropiedad->id,
            'unidad_id' => $this->unidad->id,
            'user_id' => $this->resident->id,
            'tipo' => 'reclamo',
            'asunto' => 'Fuga de agua en pasillo',
            'mensaje' => 'Urgente fuga',
            'prioridad' => 'alta',
            'estado' => 'abierto',
        ]);

        // 1 En Proceso
        Pqrs::create([
            'copropiedad_id' => $this->copropiedad->id,
            'unidad_id' => $this->unidad->id,
            'user_id' => $this->resident->id,
            'tipo' => 'queja',
            'asunto' => 'Ruido nocturno',
            'mensaje' => 'Vecino piso 5',
            'prioridad' => 'media',
            'estado' => 'en_proceso',
        ]);

        // 1 Reabierto (alta prioridad)
        Pqrs::create([
            'copropiedad_id' => $this->copropiedad->id,
            'unidad_id' => $this->unidad->id,
            'user_id' => $this->resident->id,
            'tipo' => 'reclamo',
            'asunto' => 'Ascensor averiado persistente',
            'mensaje' => 'Sigue fallando',
            'prioridad' => 'alta',
            'estado' => 'reabierto',
        ]);

        // 2 Cerrados
        Pqrs::create([
            'copropiedad_id' => $this->copropiedad->id,
            'unidad_id' => $this->unidad->id,
            'user_id' => $this->resident->id,
            'tipo' => 'sugerencia',
            'asunto' => 'Iluminación parqueadero',
            'mensaje' => 'Mejor bombillos LED',
            'prioridad' => 'baja',
            'estado' => 'cerrado',
            'respuesta' => 'Se cambiaron luminarias.',
            'fecha_respuesta' => now(),
        ]);

        Pqrs::create([
            'copropiedad_id' => $this->copropiedad->id,
            'unidad_id' => $this->unidad->id,
            'user_id' => $this->resident->id,
            'tipo' => 'peticion',
            'asunto' => 'Paz y salvo',
            'mensaje' => 'Certificado al día',
            'prioridad' => 'media',
            'estado' => 'cerrado',
            'respuesta' => 'Adjunto paz y salvo.',
            'fecha_respuesta' => now(),
        ]);

        $metrics = $this->service->getDashboardMetrics($this->copropiedad->id);

        $this->assertEquals(2, $metrics['abierto'] ?? 0);
        $this->assertEquals(1, $metrics['en_proceso'] ?? 0);
        $this->assertEquals(1, $metrics['reabierto'] ?? 0);
        $this->assertEquals(2, $metrics['cerrado'] ?? 0);
        $this->assertEquals(6, $metrics['total'] ?? 0);
        // Tasa de resolución: 2 cerrados de 6 totales = 33.3%
        $this->assertEquals(33.3, $metrics['tasa_resolucion'] ?? 0);
        // Urgentes / críticas activas (abiertas o reabiertas con prioridad alta/urgente): 1 urgente + 1 alta = 2
        $this->assertEquals(2, $metrics['urgentes_activas'] ?? 0);
    }

    public function test_respond_pqrs_transitions_status_and_records_timestamp(): void
    {
        $pqrs = Pqrs::create([
            'copropiedad_id' => $this->copropiedad->id,
            'unidad_id' => $this->unidad->id,
            'user_id' => $this->resident->id,
            'tipo' => 'peticion',
            'asunto' => 'Consulta de saldo',
            'mensaje' => 'Deseo conocer mi saldo',
            'prioridad' => 'baja',
            'estado' => 'abierto',
        ]);

        $result = $this->service->respondPqrs($pqrs->id, 'Su saldo al corte es de $0.', true);

        $this->assertTrue($result);

        $fresh = $pqrs->fresh();
        $this->assertEquals('cerrado', $fresh->estado);
        $this->assertEquals('Su saldo al corte es de $0.', $fresh->respuesta);
        $this->assertNotNull($fresh->fecha_respuesta);
    }

    public function test_reopen_pqrs_changes_state_to_reabierto(): void
    {
        $pqrs = Pqrs::create([
            'copropiedad_id' => $this->copropiedad->id,
            'unidad_id' => $this->unidad->id,
            'user_id' => $this->resident->id,
            'tipo' => 'reclamo',
            'asunto' => 'Falla de citofonía',
            'mensaje' => 'No suena el citófono',
            'prioridad' => 'media',
            'estado' => 'cerrado',
        ]);

        $result = $this->service->reopenPqrs($pqrs->id);

        $this->assertTrue($result);
        $this->assertEquals('reabierto', $pqrs->fresh()->estado);
    }
}

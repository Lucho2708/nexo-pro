<?php

namespace Tests\Unit\Operations;

use App\Modules\IAM\Models\User;
use App\Modules\Operations\Models\Reserva;
use App\Modules\Property\Models\Copropiedad;
use App\Modules\Property\Models\Unidad;
use App\Modules\Property\Models\ZonaComun;
use App\Services\Operations\ReservationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class ReservationServiceTest extends TestCase
{
    use RefreshDatabase;

    private ReservationService $service;
    private Copropiedad $copropiedad;
    private Unidad $unidad;
    private User $resident;
    private ZonaComun $zona;

    protected function setUp(): void
    {
        parent::setUp();

        Notification::fake();

        $this->service = app(ReservationService::class);

        $this->copropiedad = Copropiedad::create([
            'nombre' => 'Residencial El Bosque Reservas Unit',
            'nit' => '900555444-1',
            'direccion' => 'Transversal 45 # 120-10',
            'ciudad' => 'Bogotá',
            'license_status' => 'active',
            'license_expires_at' => now()->addYear(),
        ]);

        $this->unidad = Unidad::create([
            'copropiedad_id' => $this->copropiedad->id,
            'nombre' => '501',
            'torre' => 'A',
            'tipo' => 'apartamento',
            'coeficiente' => 0.02,
            'area_m2' => 75.0,
        ]);

        $this->resident = User::factory()->create([
            'role' => 'owner',
            'current_copropiedad_id' => $this->copropiedad->id,
        ]);

        $this->zona = ZonaComun::create([
            'copropiedad_id' => $this->copropiedad->id,
            'nombre' => 'Kiosco BBQ Principal',
            'capacidad_maxima' => 30,
            'costo' => 50000,
            'activa' => true,
            'settings' => [
                'tipo' => 'bbq',
                'min_dias_anticipacion' => 1,
                'max_reservas_mes' => 4,
                'bloquear_si_mora' => false,
            ],
        ]);
    }

    public function test_computes_tactical_metrics_accurately(): void
    {
        // 1 Pendiente
        Reserva::create([
            'copropiedad_id' => $this->copropiedad->id,
            'zona_id' => $this->zona->id,
            'unidad_id' => $this->unidad->id,
            'user_id' => $this->resident->id,
            'fecha' => now()->addDays(2)->toDateString(),
            'hora_inicio' => '10:00:00',
            'hora_fin' => '13:00:00',
            'cantidad_personas' => 10,
            'monto_pagado' => 0,
            'estado' => 'pendiente',
        ]);

        // 1 Aprobada (con costo 50,000)
        Reserva::create([
            'copropiedad_id' => $this->copropiedad->id,
            'zona_id' => $this->zona->id,
            'unidad_id' => $this->unidad->id,
            'user_id' => $this->resident->id,
            'fecha' => now()->addDays(3)->toDateString(),
            'hora_inicio' => '14:00:00',
            'hora_fin' => '17:00:00',
            'cantidad_personas' => 15,
            'monto_pagado' => 50000,
            'estado' => 'aprobada',
        ]);

        // 2 Pagadas (con costo 50,000 c/u = 100,000)
        Reserva::create([
            'copropiedad_id' => $this->copropiedad->id,
            'zona_id' => $this->zona->id,
            'unidad_id' => $this->unidad->id,
            'user_id' => $this->resident->id,
            'fecha' => now()->addDays(4)->toDateString(),
            'hora_inicio' => '18:00:00',
            'hora_fin' => '21:00:00',
            'cantidad_personas' => 20,
            'monto_pagado' => 50000,
            'estado' => 'pagada',
        ]);

        Reserva::create([
            'copropiedad_id' => $this->copropiedad->id,
            'zona_id' => $this->zona->id,
            'unidad_id' => $this->unidad->id,
            'user_id' => $this->resident->id,
            'fecha' => now()->addDays(5)->toDateString(),
            'hora_inicio' => '12:00:00',
            'hora_fin' => '15:00:00',
            'cantidad_personas' => 12,
            'monto_pagado' => 50000,
            'estado' => 'pagada',
        ]);

        // 1 Rechazada
        Reserva::create([
            'copropiedad_id' => $this->copropiedad->id,
            'zona_id' => $this->zona->id,
            'unidad_id' => $this->unidad->id,
            'user_id' => $this->resident->id,
            'fecha' => now()->addDays(6)->toDateString(),
            'hora_inicio' => '10:00:00',
            'hora_fin' => '12:00:00',
            'cantidad_personas' => 5,
            'monto_pagado' => 0,
            'estado' => 'rechazada',
        ]);

        // 1 Cancelada
        Reserva::create([
            'copropiedad_id' => $this->copropiedad->id,
            'zona_id' => $this->zona->id,
            'unidad_id' => $this->unidad->id,
            'user_id' => $this->resident->id,
            'fecha' => now()->addDays(7)->toDateString(),
            'hora_inicio' => '16:00:00',
            'hora_fin' => '19:00:00',
            'cantidad_personas' => 8,
            'monto_pagado' => 0,
            'estado' => 'cancelada',
        ]);

        $metrics = $this->service->getDashboardMetrics($this->copropiedad->id);

        $this->assertEquals(1, $metrics['pendiente'] ?? 0);
        $this->assertEquals(1, $metrics['aprobada'] ?? 0);
        $this->assertEquals(2, $metrics['pagada'] ?? 0);
        $this->assertEquals(1, $metrics['rechazada'] ?? 0);
        $this->assertEquals(1, $metrics['cancelada'] ?? 0);
        $this->assertEquals(6, $metrics['total'] ?? 0);
        // Recaudo total = 50,000 (aprobada) + 50,000 (pagada) + 50,000 (pagada) = 150,000
        $this->assertEquals(150000, (float) ($metrics['recaudo_total'] ?? 0));
        // Aprobadas o Pagadas = 3 de 6 totales = 50.0%
        $this->assertEquals(50.0, (float) ($metrics['tasa_aprobacion'] ?? 0));
    }

    public function test_update_status_updates_record_and_notes(): void
    {
        $reserva = Reserva::create([
            'copropiedad_id' => $this->copropiedad->id,
            'zona_id' => $this->zona->id,
            'unidad_id' => $this->unidad->id,
            'user_id' => $this->resident->id,
            'fecha' => now()->addDays(3)->toDateString(),
            'hora_inicio' => '10:00:00',
            'hora_fin' => '12:00:00',
            'cantidad_personas' => 6,
            'monto_pagado' => 0,
            'estado' => 'pendiente',
        ]);

        $updated = $this->service->updateStatus($reserva->id, 'aprobada', 'Pago verificado en portería.');

        $this->assertTrue($updated);

        $fresh = $reserva->fresh();
        $this->assertEquals('aprobada', $fresh->estado);
        $this->assertEquals('Pago verificado en portería.', $fresh->notas_admin);
    }

    public function test_cancel_reservation_sets_state_to_cancelada(): void
    {
        $reserva = Reserva::create([
            'copropiedad_id' => $this->copropiedad->id,
            'zona_id' => $this->zona->id,
            'unidad_id' => $this->unidad->id,
            'user_id' => $this->resident->id,
            'fecha' => now()->addDays(3)->toDateString(),
            'hora_inicio' => '10:00:00',
            'hora_fin' => '12:00:00',
            'cantidad_personas' => 6,
            'monto_pagado' => 0,
            'estado' => 'aprobada',
        ]);

        $cancelled = $this->service->cancelReservation($reserva->id);

        $this->assertTrue($cancelled);
        $this->assertEquals('cancelada', $reserva->fresh()->estado);
    }
}

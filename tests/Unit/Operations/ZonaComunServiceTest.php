<?php

namespace Tests\Unit\Operations;

use App\Modules\Operations\Models\Reserva;
use App\Modules\Property\Models\Copropiedad;
use App\Modules\Property\Models\ZonaComun;
use App\Repositories\Interfaces\ZonaComunRepositoryInterface;
use App\Services\Operations\ZonaComunService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ZonaComunServiceTest extends TestCase
{
    use RefreshDatabase;

    private ZonaComunService $service;
    private Copropiedad $copropiedad;

    protected function setUp(): void
    {
        parent::setUp();

        $this->copropiedad = Copropiedad::create([
            'nombre' => 'Conjunto Residencial Los Álamos',
            'nit' => '900999888-1',
            'direccion' => 'Calle 100 # 20-30',
            'ciudad' => 'Bogotá',
        ]);

        $this->service = app(ZonaComunService::class);
    }

    public function test_it_computes_tactical_metrics_accurately(): void
    {
        // Setup: 2 zonas activas y 1 en mantenimiento
        ZonaComun::create([
            'copropiedad_id' => $this->copropiedad->id,
            'nombre' => 'Salón Social',
            'capacidad_maxima' => 60,
            'costo' => 150000,
            'activa' => true,
        ]);

        ZonaComun::create([
            'copropiedad_id' => $this->copropiedad->id,
            'nombre' => 'Zona BBQ',
            'capacidad_maxima' => 20,
            'costo' => 50000,
            'activa' => true,
        ]);

        ZonaComun::create([
            'copropiedad_id' => $this->copropiedad->id,
            'nombre' => 'Piscina Climatizada',
            'capacidad_maxima' => 30,
            'costo' => 0,
            'activa' => false,
        ]);

        $metrics = $this->service->getMetrics($this->copropiedad->id);

        $this->assertEquals(3, $metrics['total_zonas']);
        $this->assertEquals(2, $metrics['zonas_activas']);
        $this->assertEquals(1, $metrics['zonas_inactivas']);
        $this->assertEquals(110, $metrics['aforo_total_pax']);
        $this->assertEquals(66.7, $metrics['porcentaje_operatividad']);
    }

    public function test_it_creates_zona_with_extended_settings(): void
    {
        $payload = [
            'nombre' => 'Gimnasio Fitness Pro',
            'descripcion' => 'Equipamiento de cardio y pesas libres.',
            'capacidad_maxima' => 15,
            'costo' => 0,
            'settings' => [
                'tipo' => 'gym',
                'horario_apertura' => '05:00',
                'horario_cierre' => '22:00',
                'tiempo_maximo_horas' => 2,
                'deposito_garantia' => 0,
                'requiere_aprobacion' => false,
            ],
        ];

        $zona = $this->service->createZona($payload, $this->copropiedad->id);

        $this->assertInstanceOf(ZonaComun::class, $zona);
        $this->assertEquals('Gimnasio Fitness Pro', $zona->nombre);
        $this->assertTrue($zona->activa);
        $this->assertEquals('gym', $zona->settings['tipo']);
        $this->assertEquals('05:00', $zona->settings['horario_apertura']);
    }

    public function test_it_prevents_deleting_zona_when_future_reservations_exist(): void
    {
        $zona = ZonaComun::create([
            'copropiedad_id' => $this->copropiedad->id,
            'nombre' => 'Cancha Sintética',
            'capacidad_maxima' => 14,
            'costo' => 80000,
            'activa' => true,
        ]);

        $user = \App\Modules\IAM\Models\User::factory()->create([
            'current_copropiedad_id' => $this->copropiedad->id,
        ]);

        $unidad = \App\Modules\Property\Models\Unidad::create([
            'copropiedad_id' => $this->copropiedad->id,
            'nombre' => 'Apto 101',
            'torre' => 'A',
            'saldo_actual' => 0,
        ]);

        // Crear una reserva futura en estado confirmada
        Reserva::create([
            'copropiedad_id' => $this->copropiedad->id,
            'zona_id' => $zona->id,
            'user_id' => $user->id,
            'unidad_id' => $unidad->id,
            'fecha' => now()->addDays(5)->toDateString(),
            'hora_inicio' => '18:00',
            'hora_fin' => '20:00',
            'cantidad_personas' => 10,
            'monto_pagado' => 80000,
            'estado' => 'aprobada',
        ]);

        $result = $this->service->deleteZonaSafely($zona->id);

        $this->assertFalse($result['success']);
        $this->assertStringContainsString('reservas activas', $result['message']);
        $this->assertDatabaseHas('property.zonas_comunes', ['id' => $zona->id]);
    }

    public function test_it_deletes_zona_when_no_active_reservations_exist(): void
    {
        $zona = ZonaComun::create([
            'copropiedad_id' => $this->copropiedad->id,
            'nombre' => 'Sala de Coworking',
            'capacidad_maxima' => 12,
            'costo' => 0,
            'activa' => true,
        ]);

        $result = $this->service->deleteZonaSafely($zona->id);

        $this->assertTrue($result['success']);
        $this->assertDatabaseMissing('property.zonas_comunes', ['id' => $zona->id]);
    }
}

<?php

namespace Tests\Feature\Admin;

use App\Modules\IAM\Models\User;
use App\Modules\Operations\Models\Reserva;
use App\Modules\Property\Models\Copropiedad;
use App\Modules\Property\Models\ZonaComun;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class ZonaComunManagementE2ETest extends TestCase
{
    use RefreshDatabase;

    private Copropiedad $copropiedad;
    private User $adminUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->copropiedad = Copropiedad::create([
            'nombre' => 'Residencial San Carlos E2E',
            'nit' => '900888777-2',
            'direccion' => 'Avenida Principal # 12-34',
            'ciudad' => 'Bogotá',
            'license_status' => 'active',
            'license_expires_at' => now()->addYear(),
        ]);

        $this->adminUser = User::factory()->create([
            'current_copropiedad_id' => $this->copropiedad->id,
            'role' => 'admin',
            'email_verified_at' => now(),
            'terms_accepted_at' => now(),
        ]);
    }

    public function test_admin_can_access_zonas_index_with_live_stats(): void
    {
        ZonaComun::create([
            'copropiedad_id' => $this->copropiedad->id,
            'nombre' => 'Piscina Olímpica',
            'capacidad_maxima' => 40,
            'costo' => 0,
            'activa' => true,
            'settings' => ['tipo' => 'piscina', 'horario_apertura' => '06:00', 'horario_cierre' => '20:00'],
        ]);

        ZonaComun::create([
            'copropiedad_id' => $this->copropiedad->id,
            'nombre' => 'Kiosco BBQ',
            'capacidad_maxima' => 25,
            'costo' => 70000,
            'activa' => false,
            'settings' => ['tipo' => 'bbq', 'horario_apertura' => '10:00', 'horario_cierre' => '22:00'],
        ]);

        $response = $this->actingAs($this->adminUser)
            ->get('/admin/zonas');

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->component('Admin/Zonas/Index')
            ->has('zonas', 2)
            ->has('stats', fn (Assert $stats) => $stats
                ->where('total_zonas', 2)
                ->where('zonas_activas', 1)
                ->where('zonas_inactivas', 1)
                ->where('aforo_total_pax', 65)
                ->etc()
            )
        );
    }

    public function test_admin_can_create_a_common_area_with_settings(): void
    {
        $payload = [
            'nombre' => 'Salón Comunal Principal',
            'descripcion' => 'Espacio para asambleas y eventos sociales privados.',
            'capacidad_maxima' => 80,
            'costo' => 200000,
            'settings' => [
                'tipo' => 'salon',
                'horario_apertura' => '08:00',
                'horario_cierre' => '23:00',
                'deposito_garantia' => 100000,
                'tiempo_maximo_horas' => 6,
                'requiere_aprobacion' => true,
            ],
        ];

        $response = $this->actingAs($this->adminUser)
            ->post('/admin/zonas', $payload);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('property.zonas_comunes', [
            'copropiedad_id' => $this->copropiedad->id,
            'nombre' => 'Salón Comunal Principal',
            'capacidad_maxima' => 80,
            'costo' => 200000,
            'activa' => true,
        ]);
    }

    public function test_admin_can_update_a_common_area(): void
    {
        $zona = ZonaComun::create([
            'copropiedad_id' => $this->copropiedad->id,
            'nombre' => 'Cancha de Squash',
            'capacidad_maxima' => 4,
            'costo' => 30000,
            'activa' => true,
        ]);

        $response = $this->actingAs($this->adminUser)
            ->patch("/admin/zonas/{$zona->id}", [
                'nombre' => 'Cancha de Squash Pro',
                'descripcion' => 'Piso de madera certificado y aire acondicionado.',
                'capacidad_maxima' => 6,
                'costo' => 40000,
                'settings' => [
                    'tipo' => 'cancha',
                    'horario_apertura' => '07:00',
                    'horario_cierre' => '21:00',
                ],
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('property.zonas_comunes', [
            'id' => $zona->id,
            'nombre' => 'Cancha de Squash Pro',
            'capacidad_maxima' => 6,
            'costo' => 40000,
        ]);
    }

    public function test_admin_can_toggle_zona_operational_status(): void
    {
        $zona = ZonaComun::create([
            'copropiedad_id' => $this->copropiedad->id,
            'nombre' => 'Zona BBQ 2',
            'capacidad_maxima' => 15,
            'costo' => 45000,
            'activa' => true,
        ]);

        // Toggle a falso
        $response = $this->actingAs($this->adminUser)
            ->patch("/admin/zonas/{$zona->id}/toggle");

        $response->assertRedirect();
        $this->assertFalse($zona->fresh()->activa);

        // Toggle a verdadero
        $this->actingAs($this->adminUser)
            ->patch("/admin/zonas/{$zona->id}/toggle");

        $this->assertTrue($zona->fresh()->activa);
    }

    public function test_admin_can_delete_a_common_area_safely(): void
    {
        $zona = ZonaComun::create([
            'copropiedad_id' => $this->copropiedad->id,
            'nombre' => 'Espacio a Desmantelar',
            'capacidad_maxima' => 10,
            'costo' => 0,
            'activa' => false,
        ]);

        $response = $this->actingAs($this->adminUser)
            ->delete("/admin/zonas/{$zona->id}");

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('property.zonas_comunes', ['id' => $zona->id]);
    }

    public function test_admin_cannot_delete_zona_with_active_reservations(): void
    {
        $zona = ZonaComun::create([
            'copropiedad_id' => $this->copropiedad->id,
            'nombre' => 'Gimnasio Ocupado',
            'capacidad_maxima' => 20,
            'costo' => 0,
            'activa' => true,
        ]);

        $unidad = \App\Modules\Property\Models\Unidad::create([
            'copropiedad_id' => $this->copropiedad->id,
            'nombre' => 'Apto 202',
            'torre' => 'B',
            'saldo_actual' => 0,
        ]);

        Reserva::create([
            'copropiedad_id' => $this->copropiedad->id,
            'zona_id' => $zona->id,
            'user_id' => $this->adminUser->id,
            'unidad_id' => $unidad->id,
            'fecha' => now()->addDays(2)->toDateString(),
            'hora_inicio' => '10:00',
            'hora_fin' => '12:00',
            'cantidad_personas' => 5,
            'monto_pagado' => 0,
            'estado' => 'aprobada',
        ]);

        $response = $this->actingAs($this->adminUser)
            ->delete("/admin/zonas/{$zona->id}");

        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('property.zonas_comunes', ['id' => $zona->id]);
    }

    public function test_unauthenticated_user_is_redirected_to_login(): void
    {
        $response = $this->get('/admin/zonas');
        $response->assertRedirect('/login');
    }
}

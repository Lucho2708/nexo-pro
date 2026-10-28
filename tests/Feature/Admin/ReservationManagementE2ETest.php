<?php

namespace Tests\Feature\Admin;

use App\Modules\IAM\Models\User;
use App\Modules\Operations\Models\Reserva;
use App\Modules\Property\Models\Copropiedad;
use App\Modules\Property\Models\Unidad;
use App\Modules\Property\Models\ZonaComun;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class ReservationManagementE2ETest extends TestCase
{
    use RefreshDatabase;

    private Copropiedad $copropiedad;
    private User $adminUser;
    private User $residentUser;
    private Unidad $unidad;
    private ZonaComun $zona;
    private Reserva $reserva;

    protected function setUp(): void
    {
        parent::setUp();

        Notification::fake();

        $this->copropiedad = Copropiedad::create([
            'nombre' => 'Residencial San Carlos E2E Reservas',
            'nit' => '900999111-4',
            'direccion' => 'Calle 140 # 19-30',
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
        $this->adminUser->managedCopropiedades()->attach($this->copropiedad->id);

        $this->residentUser = User::factory()->create([
            'current_copropiedad_id' => $this->copropiedad->id,
            'role' => 'owner',
            'email_verified_at' => now(),
            'terms_accepted_at' => now(),
        ]);

        $this->unidad = Unidad::create([
            'copropiedad_id' => $this->copropiedad->id,
            'nombre' => '201',
            'torre' => 'A',
            'tipo' => 'apartamento',
            'coeficiente' => 0.025,
            'area_m2' => 80.0,
        ]);

        $this->zona = ZonaComun::create([
            'copropiedad_id' => $this->copropiedad->id,
            'nombre' => 'Salón Social VIP',
            'capacidad_maxima' => 60,
            'costo' => 80000,
            'activa' => true,
            'settings' => [
                'tipo' => 'salon_social',
                'min_dias_anticipacion' => 1,
                'max_reservas_mes' => 2,
                'bloquear_si_mora' => false,
            ],
        ]);

        $this->reserva = Reserva::create([
            'copropiedad_id' => $this->copropiedad->id,
            'zona_id' => $this->zona->id,
            'unidad_id' => $this->unidad->id,
            'user_id' => $this->residentUser->id,
            'fecha' => now()->addDays(3)->toDateString(),
            'hora_inicio' => '15:00:00',
            'hora_fin' => '20:00:00',
            'cantidad_personas' => 25,
            'monto_pagado' => 80000,
            'estado' => 'pendiente',
        ]);
    }

    public function test_admin_can_access_canonical_reservas_with_live_metrics_and_zonas(): void
    {
        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.reservas.index'));

        $response->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Admin/Reservas/Manager')
                ->has('reservas', 1)
                ->has('zonas')
                ->has('metrics')
                ->where('metrics.pendiente', 1)
                ->where('metrics.total', 1)
                ->where('reservas.0.id', $this->reserva->id)
                ->where('reservas.0.estado', 'pendiente')
            );
    }

    public function test_legacy_portal_reservas_route_redirects_admin_to_canonical_admin_reservas(): void
    {
        $response = $this->actingAs($this->adminUser)
            ->get('/reservas');

        $response->assertRedirect(route('admin.reservas.index'));
    }

    public function test_admin_can_approve_reservation_via_status_endpoint(): void
    {
        $response = $this->actingAs($this->adminUser)
            ->patch(route('admin.reservas.status', $this->reserva->id), [
                'estado' => 'aprobada',
                'notas' => 'Reserva autorizada. Favor reclamar llaves antes de las 14:00.',
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('operations.reservas', [
            'id' => $this->reserva->id,
            'estado' => 'aprobada',
            'notas_admin' => 'Reserva autorizada. Favor reclamar llaves antes de las 14:00.',
        ]);
    }

    public function test_admin_can_mark_reservation_as_paid(): void
    {
        $response = $this->actingAs($this->adminUser)
            ->patch(route('admin.reservas.status', $this->reserva->id), [
                'estado' => 'pagada',
                'notas' => 'Comprobante de transferencia bancaria verificado.',
            ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('operations.reservas', [
            'id' => $this->reserva->id,
            'estado' => 'pagada',
            'notas_admin' => 'Comprobante de transferencia bancaria verificado.',
        ]);
    }

    public function test_admin_can_reject_reservation_with_reason(): void
    {
        $response = $this->actingAs($this->adminUser)
            ->patch(route('admin.reservas.status', $this->reserva->id), [
                'estado' => 'rechazada',
                'notas' => 'Mantenimiento preventivo de luminarias en el salón durante esa fecha.',
            ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('operations.reservas', [
            'id' => $this->reserva->id,
            'estado' => 'rechazada',
            'notas_admin' => 'Mantenimiento preventivo de luminarias en el salón durante esa fecha.',
        ]);
    }

    public function test_resident_cannot_update_reservation_status(): void
    {
        $response = $this->actingAs($this->residentUser)
            ->patch(route('admin.reservas.status', $this->reserva->id), [
                'estado' => 'aprobada',
            ]);

        $response->assertStatus(403);
    }

    public function test_unauthenticated_user_cannot_access_admin_reservas(): void
    {
        $response = $this->get(route('admin.reservas.index'));
        $response->assertRedirect(route('login'));
    }
}

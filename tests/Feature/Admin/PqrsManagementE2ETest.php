<?php

namespace Tests\Feature\Admin;

use App\Modules\IAM\Models\User;
use App\Modules\Operations\Models\Pqrs;
use App\Modules\Property\Models\Copropiedad;
use App\Modules\Property\Models\Unidad;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class PqrsManagementE2ETest extends TestCase
{
    use RefreshDatabase;

    private Copropiedad $copropiedad;
    private User $adminUser;
    private User $residentUser;
    private Unidad $unidad;
    private Pqrs $ticket;

    protected function setUp(): void
    {
        parent::setUp();

        $this->copropiedad = Copropiedad::create([
            'nombre' => 'Residencial San Carlos E2E PQRS',
            'nit' => '900777666-3',
            'direccion' => 'Carrera 7 # 80-20',
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
            'nombre' => '301',
            'torre' => 'B',
            'tipo' => 'apartamento',
            'coeficiente' => 0.018,
            'area_m2' => 72.0,
        ]);

        $this->ticket = Pqrs::create([
            'copropiedad_id' => $this->copropiedad->id,
            'unidad_id' => $this->unidad->id,
            'user_id' => $this->residentUser->id,
            'tipo' => 'reclamo',
            'asunto' => 'Humedad en pared lateral',
            'mensaje' => 'Se está filtrando humedad desde la cubierta.',
            'prioridad' => 'alta',
            'estado' => 'abierto',
            'adjuntos' => ['pqrs/attachments/foto1.jpg'],
        ]);
    }

    public function test_admin_can_access_canonical_pqrs_index_with_live_metrics(): void
    {
        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.pqrs.index'));

        $response->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Admin/Pqrs/Index')
                ->has('pqrs', 1)
                ->has('metrics')
                ->where('metrics.abierto', 1)
                ->where('metrics.total', 1)
                ->where('metrics.tasa_resolucion', 0)
                ->where('pqrs.0.asunto', 'Humedad en pared lateral')
                ->where('pqrs.0.prioridad', 'alta')
            );
    }

    public function test_legacy_pqrs_route_redirects_admin_to_canonical_admin_pqrs(): void
    {
        $response = $this->actingAs($this->adminUser)
            ->get('/pqrs');

        $response->assertRedirect(route('admin.pqrs.index'));
    }

    public function test_admin_can_respond_and_resolve_pqrs_via_canonical_endpoint(): void
    {
        $response = $this->actingAs($this->adminUser)
            ->patch(route('admin.pqrs.update', $this->ticket->id), [
                'respuesta' => 'Se programó visita técnica para el jueves a las 9:00 AM.',
                'cerrar' => true,
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('operations.pqrs', [
            'id' => $this->ticket->id,
            'estado' => 'cerrado',
            'respuesta' => 'Se programó visita técnica para el jueves a las 9:00 AM.',
        ]);
        $this->assertNotNull($this->ticket->fresh()->fecha_respuesta);
    }

    public function test_admin_can_respond_keeping_ticket_in_progress(): void
    {
        $response = $this->actingAs($this->adminUser)
            ->patch(route('admin.pqrs.update', $this->ticket->id), [
                'respuesta' => 'Hemos solicitado cotización a 3 contratistas.',
                'cerrar' => false,
            ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('operations.pqrs', [
            'id' => $this->ticket->id,
            'estado' => 'en_proceso',
            'respuesta' => 'Hemos solicitado cotización a 3 contratistas.',
        ]);
    }

    public function test_admin_can_download_pqrs_pdf_report(): void
    {
        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.pqrs.download', $this->ticket->id));

        $response->assertOk();
        $this->assertEquals('application/pdf', $response->headers->get('content-type'));
    }

    public function test_unauthenticated_user_cannot_access_admin_pqrs(): void
    {
        $response = $this->get(route('admin.pqrs.index'));
        $response->assertRedirect(route('login'));
    }
}

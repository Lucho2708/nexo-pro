<?php

namespace Tests\Feature\Cartera;

use App\Modules\Finance\Models\ConceptoCobro;
use App\Modules\Finance\Models\Transaccion;
use App\Modules\Property\Models\Copropiedad;
use App\Modules\Property\Models\Unidad;
use App\Modules\IAM\Models\User;
use App\Repositories\Interfaces\FinancialRepositoryInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class CarteraAdminCanonicalE2ETest extends TestCase
{
    use RefreshDatabase;

    protected Copropiedad $copropiedad;
    protected User $adminUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->copropiedad = Copropiedad::create([
            'nombre' => 'Residencial San Carlos Test',
            'direccion' => 'Carrera 15 # 45-67',
            'nit' => '900555666-1',
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

    public function test_admin_can_access_canonical_admin_cartera_index_with_live_stats(): void
    {
        // Crear 2 unidades: una al día y otra en mora
        $unidadAlDia = Unidad::create([
            'copropiedad_id' => $this->copropiedad->id,
            'nombre' => 'Apto 101',
            'torre' => '1',
            'coeficiente' => 0.05,
            'saldo_actual' => 0,
        ]);

        $unidadMora = Unidad::create([
            'copropiedad_id' => $this->copropiedad->id,
            'nombre' => 'Apto 102',
            'torre' => '1',
            'coeficiente' => 0.05,
            'saldo_actual' => 450000,
        ]);

        $concepto = ConceptoCobro::create([
            'copropiedad_id' => $this->copropiedad->id,
            'nombre' => 'Cuota de Administración',
            'codigo' => 'ADM',
        ]);

        // Registrar un recaudo real en la base de datos
        Transaccion::create([
            'copropiedad_id' => $this->copropiedad->id,
            'unidad_id' => $unidadAlDia->id,
            'concepto_id' => $concepto->id,
            'tipo' => 'abono',
            'monto' => 350000,
            'fecha' => now()->toDateString(),
            'referencia' => 'TEST-LIVE-RECAUDO-1',
            'user_id' => $this->adminUser->id,
        ]);

        $response = $this->actingAs($this->adminUser)
            ->get('/admin/cartera');

        $response->assertStatus(200);
        $response->assertInertia(fn (Assert $page) => $page
            ->component('Cartera/Index')
            ->has('unidades.data', 2)
            ->has('stats', fn (Assert $stats) => $stats
                ->where('cartera_vencida', 450000)
                ->where('unidades_mora', 1)
                ->where('total_recaudado', 350000)
                ->where('total_unidades', 2)
                ->etc()
            )
        );
    }

    public function test_legacy_cartera_route_redirects_to_canonical_admin_cartera(): void
    {
        $response = $this->actingAs($this->adminUser)
            ->get('/cartera');

        $response->assertRedirect('/admin/cartera');
    }

    public function test_admin_can_register_payment_via_canonical_admin_cartera_endpoint(): void
    {
        $unidad = Unidad::create([
            'copropiedad_id' => $this->copropiedad->id,
            'nombre' => 'Apto 301',
            'torre' => 'Torre Norte',
            'saldo_actual' => 800000,
        ]);

        $concepto = ConceptoCobro::create([
            'copropiedad_id' => $this->copropiedad->id,
            'nombre' => 'Administración Ordinaria',
            'codigo' => 'ADM',
        ]);

        $response = $this->actingAs($this->adminUser)
            ->post('/admin/cartera/payment', [
                'unidad_id' => $unidad->id,
                'concepto_id' => $concepto->id,
                'monto' => 300000,
                'fecha' => now()->toDateString(),
                'referencia' => 'CANONICAL-TEST-REF-99',
            ]);

        $response->assertRedirect();

        $this->assertEquals(500000, $unidad->fresh()->saldo_actual);
        $this->assertDatabaseHas('finance.transacciones', [
            'unidad_id' => $unidad->id,
            'tipo' => 'abono',
            'monto' => 300000,
            'referencia' => 'CANONICAL-TEST-REF-99',
        ]);
    }

    public function test_admin_can_trigger_monthly_billing_via_canonical_admin_cartera_endpoint(): void
    {
        $unidad = Unidad::create([
            'copropiedad_id' => $this->copropiedad->id,
            'nombre' => 'Apto 201',
            'torre' => 'Torre A',
            'saldo_actual' => 0,
        ]);

        $concepto = ConceptoCobro::create([
            'copropiedad_id' => $this->copropiedad->id,
            'nombre' => 'Administración',
            'codigo' => 'ADM',
        ]);

        $response = $this->actingAs($this->adminUser)
            ->post('/admin/cartera/trigger-billing', [
                'monto' => 200000,
            ]);

        $response->assertRedirect();

        $this->assertEquals(200000, $unidad->fresh()->saldo_actual);
        $this->assertDatabaseHas('finance.transacciones', [
            'unidad_id' => $unidad->id,
            'tipo' => 'cargo',
            'monto' => 200000,
        ]);
    }

    public function test_unauthenticated_user_cannot_access_admin_cartera(): void
    {
        $response = $this->get('/admin/cartera');
        $response->assertRedirect('/login');
    }

    public function test_financial_repository_computes_live_metrics_accurately(): void
    {
        $repo = app(FinancialRepositoryInterface::class);

        // Pre-condición: sin unidades
        $statsVacio = $repo->getCarteraStats($this->copropiedad->id);
        $this->assertEquals(0, $statsVacio['cartera_vencida']);
        $this->assertEquals(0, $statsVacio['unidades_mora']);
        $this->assertEquals(100.0, $statsVacio['porcentaje_recaudo']);

        // Crear unidades
        $u1 = Unidad::create(['copropiedad_id' => $this->copropiedad->id, 'nombre' => 'U1', 'saldo_actual' => 0]);
        $u2 = Unidad::create(['copropiedad_id' => $this->copropiedad->id, 'nombre' => 'U2', 'saldo_actual' => 200000]);

        $statsConMora = $repo->getCarteraStats($this->copropiedad->id);
        $this->assertEquals(200000, $statsConMora['cartera_vencida']);
        $this->assertEquals(1, $statsConMora['unidades_mora']);
        $this->assertEquals(2, $statsConMora['total_unidades']);
        $this->assertEquals(50.0, $statsConMora['porcentaje_recaudo']);
    }
}

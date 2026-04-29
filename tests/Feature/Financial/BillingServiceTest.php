<?php

namespace Tests\Feature\Financial;

use App\Models\ConceptoCobro;
use App\Models\Copropiedad;
use App\Models\Unidad;
use App\Services\Financial\BillingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BillingServiceTest extends TestCase
{
    use RefreshDatabase;

    private BillingService $billingService;
    private Copropiedad $copropiedad;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->billingService = app(BillingService::class);

        $this->copropiedad = Copropiedad::create([
            'nombre' => 'Villa Prueba',
            'nit' => '123456',
            'direccion' => 'Calle 1',
            'ciudad' => 'Cali',
            'settings' => [
                'interes_mora' => 2.0 // 2% mensual
            ]
        ]);

        // Crear conceptos necesarios
        ConceptoCobro::create([
            'copropiedad_id' => $this->copropiedad->id,
            'nombre' => 'Cuota Administración',
            'codigo' => 'ADM'
        ]);

        ConceptoCobro::create([
            'copropiedad_id' => $this->copropiedad->id,
            'nombre' => 'Intereses Mora',
            'codigo' => 'INT'
        ]);
    }

    public function test_it_can_generate_mass_billing_for_all_units()
    {
        // Crear 3 unidades con saldos diferentes
        Unidad::create(['copropiedad_id' => $this->copropiedad->id, 'nombre' => '101', 'torre' => 'A', 'saldo_actual' => 0]);
        Unidad::create(['copropiedad_id' => $this->copropiedad->id, 'nombre' => '102', 'torre' => 'A', 'saldo_actual' => 0]);

        $amount = 150000; // Valor de la administración
        
        $this->billingService->generateMonthlyBilling($this->copropiedad->id, $amount);

        // Validar que ambas unidades ahora tienen saldo de 150,000
        $this->assertEquals(150000, Unidad::where('nombre', '101')->first()->saldo_actual);
        $this->assertEquals(150000, Unidad::where('nombre', '102')->first()->saldo_actual);
        
        $this->assertDatabaseCount('transacciones', 2);
    }

    public function test_it_calculates_and_charges_interest_on_overdue_balances()
    {
        // Unidad con deuda previa de $500,000
        $unidad = Unidad::create([
            'copropiedad_id' => $this->copropiedad->id, 
            'nombre' => '201', 
            'torre' => 'B', 
            'saldo_actual' => 500000
        ]);

        // Ejecutar cálculo de intereses (2% de 500,000 = $10,000)
        $this->billingService->applyLateInterests($this->copropiedad->id);

        // El nuevo saldo debe ser 510,000
        $this->assertEquals(510000, $unidad->fresh()->saldo_actual);
        
        $this->assertDatabaseHas('transacciones', [
            'unidad_id' => $unidad->id,
            'tipo' => 'cargo',
            'monto' => 10000,
            'referencia' => 'INTERES_MORA'
        ]);
    }
}

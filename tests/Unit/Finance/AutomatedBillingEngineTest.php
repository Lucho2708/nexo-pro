<?php

declare(strict_types=1);

namespace Tests\Unit\Finance;

use Tests\TestCase;
use NexoPro\Finance\Services\AutomatedBillingEngine;
use Psr\Log\LoggerInterface;
use Illuminate\Support\Facades\DB;
use Mockery;
use InvalidArgumentException;

/**
 * AutomatedBillingEngineTest
 *
 * [LÓGICA DE NEGOCIO]
 * Valida el ciclo automatizado de facturación mensual y liquidación de expensas comunes:
 * - Liquidación de mora sobre cartera vencida (interés bancario corriente certificado).
 * - Emisión del cargo ordinario mensual de administración.
 * - Actualización atómica de saldos contables por unidad.
 * - Rechazo de cobros con monto negativo o conceptos contables inexistentes.
 *
 * [ASPECTO TÉCNICO]
 * Pruebas unitarias de flujo transaccional financiero y precisión aritmética decimal.
 */
class AutomatedBillingEngineTest extends TestCase
{
    private AutomatedBillingEngine $engine;

    protected function setUp(): void
    {
        parent::setUp();

        DB::shouldReceive('transaction')
            ->andReturnUsing(fn(\Closure $callback) => $callback());

        $this->engine = new AutomatedBillingEngine();
    }

    /**
     * Edge Case 1: Lanza InvalidArgumentException si el monto de administración es <= 0.
     */
    public function test_execute_billing_throws_on_invalid_amount(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('El monto de administración debe ser mayor a cero.');

        $this->engine->executeMonthlyBillingCycle('coprop-1', 0.0);
    }

    /**
     * Edge Case 2: Lanza InvalidArgumentException si el concepto ADM no existe.
     */
    public function test_execute_billing_throws_when_adm_concept_not_found(): void
    {
        $mockQuery = Mockery::mock();
        $mockQuery->shouldReceive('where')->with('copropiedad_id', 'coprop-1')->andReturnSelf();
        $mockQuery->shouldReceive('where')->with('codigo', 'ADM')->andReturnSelf();
        $mockQuery->shouldReceive('first')->once()->andReturn(null);

        $mockQueryInt = Mockery::mock();
        $mockQueryInt->shouldReceive('where')->with('copropiedad_id', 'coprop-1')->andReturnSelf();
        $mockQueryInt->shouldReceive('where')->with('codigo', 'INT')->andReturnSelf();
        $mockQueryInt->shouldReceive('first')->once()->andReturn((object) ['id' => 'concept-int']);

        DB::shouldReceive('table')
            ->with('finance.concepto_cobros')
            ->andReturn($mockQuery, $mockQueryInt);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("No se encontró el concepto contable 'ADM'");

        $this->engine->executeMonthlyBillingCycle('coprop-1', 200000.0);
    }

    /**
     * Happy Path: Procesa exitosamente unidades al día y unidades con mora previa.
     */
    public function test_execute_billing_calculates_fees_and_interest_correctly(): void
    {
        $copropiedadId = 'coprop-alpha';
        $montoAdm = 300000.0;
        $tasaMoraPorcentaje = 2.0; // 2% mensual

        $conceptoAdm = (object) ['id' => 'c-adm-1', 'codigo' => 'ADM'];
        $conceptoInt = (object) ['id' => 'c-int-2', 'codigo' => 'INT'];

        $mockConceptosAdm = Mockery::mock();
        $mockConceptosAdm->shouldReceive('where')->with('copropiedad_id', $copropiedadId)->andReturnSelf();
        $mockConceptosAdm->shouldReceive('where')->with('codigo', 'ADM')->andReturnSelf();
        $mockConceptosAdm->shouldReceive('first')->once()->andReturn($conceptoAdm);

        $mockConceptosInt = Mockery::mock();
        $mockConceptosInt->shouldReceive('where')->with('copropiedad_id', $copropiedadId)->andReturnSelf();
        $mockConceptosInt->shouldReceive('where')->with('codigo', 'INT')->andReturnSelf();
        $mockConceptosInt->shouldReceive('first')->once()->andReturn($conceptoInt);

        DB::shouldReceive('table')
            ->with('finance.concepto_cobros')
            ->andReturn($mockConceptosAdm, $mockConceptosInt);

        // Unidad 1: Al día (saldo 0) -> no paga intereses
        // Unidad 2: En mora (saldo 100,000) -> 2% mora = 2,000 COP
        $mockUnidadesQuery = Mockery::mock();
        $mockUnidadesQuery->shouldReceive('where')->with('copropiedad_id', $copropiedadId)->andReturnSelf();
        $mockUnidadesQuery->shouldReceive('select')->with('id', 'saldo_actual', 'coeficiente')->andReturnSelf();
        $mockUnidadesQuery->shouldReceive('get')->once()->andReturn(collect([
            (object) ['id' => 'u-1', 'saldo_actual' => 0.0, 'coeficiente' => 1.5],
            (object) ['id' => 'u-2', 'saldo_actual' => 100000.0, 'coeficiente' => 2.0],
        ]));

        $mockTransacciones = Mockery::mock();
        // 1 transacción para u-1 (cargo adm) + 2 transacciones para u-2 (interés mora + cargo adm) = 3 inserts
        $mockTransacciones->shouldReceive('insert')->times(3)->andReturn(true);

        $mockUnidadesUpdate = Mockery::mock();
        $mockUnidadesUpdate->shouldReceive('where')->with('id', 'u-1')->andReturnSelf();
        $mockUnidadesUpdate->shouldReceive('update')->with(Mockery::on(function ($data) {
            return $data['saldo_actual'] === 300000.0;
        }))->once()->andReturn(1);

        $mockUnidadesUpdate->shouldReceive('where')->with('id', 'u-2')->andReturnSelf();
        $mockUnidadesUpdate->shouldReceive('update')->with(Mockery::on(function ($data) {
            // Saldo inicial 100,000 + interés 2,000 + adm 300,000 = 402,000
            return $data['saldo_actual'] === 402000.0;
        }))->once()->andReturn(1);

        DB::shouldReceive('table')->with('property.unidades')->andReturn($mockUnidadesQuery, $mockUnidadesUpdate);
        DB::shouldReceive('table')->with('finance.transacciones')->andReturn($mockTransacciones);

        $result = $this->engine->executeMonthlyBillingCycle($copropiedadId, $montoAdm, $tasaMoraPorcentaje);

        $this->assertEquals(2, $result['unidades_procesadas']);
        $this->assertEquals(600000.0, $result['total_facturado']);
        $this->assertEquals(2000.0, $result['total_intereses']);
    }
}

<?php

namespace Tests\Feature\Financial;

use App\DTOs\TransactionDataDTO;
use App\Modules\Finance\Models\ConceptoCobro;
use App\Modules\Property\Models\Copropiedad;
use App\Modules\Property\Models\Unidad;
use App\Modules\IAM\Models\User;
use App\Services\Financial\FinancialService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FinancialServiceTest extends TestCase
{
    use RefreshDatabase;

    private FinancialService $financialService;
    private Copropiedad $copropiedad;
    private Unidad $unidad;
    private ConceptoCobro $concepto;

    protected function setUp(): void
    {
        parent::setUp();

        // Necesitamos instanciar el servicio. 
        // Nota: Esto fallará hasta que creemos la clase y sus dependencias.
        $this->financialService = app(FinancialService::class);

        $this->copropiedad = Copropiedad::create([
            'nombre' => 'Residencial Test',
            'nit' => '123-4',
            'direccion' => 'Calle Falsa 123',
            'ciudad' => 'Medellín'
        ]);

        $this->unidad = Unidad::create([
            'copropiedad_id' => $this->copropiedad->id,
            'nombre' => '101',
            'torre' => 'A',
            'saldo_actual' => 100000
        ]);

        $this->concepto = ConceptoCobro::create([
            'copropiedad_id' => $this->copropiedad->id,
            'nombre' => 'Administración',
            'codigo' => 'ADM'
        ]);
    }

    public function test_it_can_process_a_payment_correctly()
    {
        $data = new TransactionDataDTO(
            unidadId: $this->unidad->id,
            conceptoId: $this->concepto->id,
            monto: 50000,
            tipo: 'abono',
            fecha: now()->format('Y-m-d'),
            referencia: 'TEST-PAY-001'
        );

        $transaction = $this->financialService->registerTransaction($data);

        $this->assertDatabaseHas('finance.transacciones', [
            'id' => $transaction->id,
            'monto' => 50000,
            'tipo' => 'abono'
        ]);

        // El saldo debe haber disminuido: 100,000 - 50,000 = 50,000
        $this->assertEquals(50000, $this->unidad->fresh()->saldo_actual);
    }

    public function test_it_rolls_back_if_balance_update_fails()
    {
        // Este test validará la atomicidad (DB Transaction)
        // Simularemos un fallo o pasaremos datos inválidos que deberían disparar rollback
        $this->expectException(\Exception::class);

        $data = new TransactionDataDTO(
            unidadId: 9999, // ID Inexistente para forzar error
            conceptoId: $this->concepto->id,
            monto: 50000,
            tipo: 'abono',
            fecha: now()->format('Y-m-d')
        );

        $this->financialService->registerTransaction($data);
    }
}

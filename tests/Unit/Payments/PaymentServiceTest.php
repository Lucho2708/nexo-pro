<?php

namespace Tests\Unit\Payments;

use App\Models\Copropiedad;
use App\Models\Unidad;
use App\Models\Transaccion;
use App\Services\Payments\PaymentService;
use App\Services\Payments\Providers\WompiProvider;
use App\Services\Payments\Providers\PayUProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentServiceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->copropiedad = Copropiedad::create([
            'nombre' => 'Test', 
            'nit' => '1', 
            'direccion' => 'D', 
            'ciudad' => 'C',
            'settings' => ['payments_enabled' => true, 'payment_gateway' => 'wompi']
        ]);
        
        $this->unidad = Unidad::create([
            'copropiedad_id' => $this->copropiedad->id,
            'nombre' => '101',
            'saldo_actual' => 1000000
        ]);
        
        $this->concepto = \App\Models\ConceptoCobro::create([
            'copropiedad_id' => $this->copropiedad->id,
            'nombre' => 'Administración',
            'codigo' => 'ADM'
        ]);

        $this->transaction = Transaccion::create([
            'unidad_id' => $this->unidad->id,
            'concepto_id' => $this->concepto->id,
            'monto' => 100000,
            'tipo' => 'abono',
            'fecha' => date('Y-m-d')
        ]);
    }

    public function test_it_calculates_wompi_commission_correctly()
    {
        $service = new PaymentService();
        $data = $service->preparePayment($this->transaction);

        // Wompi: 100.000 * 2.85% = 2.850 + 800 = 3.650. IVA (19%) = 693.5. Total = 4343.5
        $this->assertEquals(4343.5, $data['commission']);
        $this->assertEquals(104343.5, $data['total_amount']);
    }

    public function test_it_returns_null_if_payments_are_disabled()
    {
        $this->copropiedad->update(['settings' => ['payments_enabled' => false]]);
        
        $service = new PaymentService();
        $this->assertNull($service->preparePayment($this->transaction));
    }

    public function test_it_switches_to_payu_correctly()
    {
        $this->copropiedad->update(['settings' => [
            'payments_enabled' => true, 
            'payment_gateway' => 'payu'
        ]]);
        
        $service = new PaymentService();
        $data = $service->preparePayment($this->transaction);

        $this->assertEquals('payu', $data['provider']);
        // PayU: 100.000 * 3.49% = 3.490 + 900 = 4.390. IVA (19%) = 834.1. Total = 5224.1
        $this->assertEquals(5224.1, $data['commission']);
    }
}

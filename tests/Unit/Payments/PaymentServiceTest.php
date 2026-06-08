<?php

namespace Tests\Unit\Payments;

use App\Services\Payments\PaymentService;
use App\Modules\Property\Models\Copropiedad;
use Tests\TestCase;

class PaymentServiceTest extends TestCase
{
    private PaymentService $paymentService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->paymentService = new PaymentService();
    }

    public function test_it_calculates_wompi_commission_correctly()
    {
        $amount = 100000;
        // 100,000 * 0.0285 = 2,850
        // 2,850 + 800 = 3,650
        // 3,650 * 1.19 = 4,343.5
        $expected = 4343.5;
        
        $result = $this->paymentService->calculateCommission($amount, 'wompi');
        $this->assertEquals($expected, $result);
    }

    public function test_it_returns_null_if_payments_are_disabled()
    {
        $copropiedad = new Copropiedad([
            'settings' => ['payments' => ['enabled' => false]]
        ]);
        
        $this->assertFalse($this->paymentService->arePaymentsEnabled($copropiedad));
    }

    public function test_it_switches_to_payu_correctly()
    {
        $copropiedad = new Copropiedad([
            'settings' => ['payments' => ['active_gateway' => 'payu']]
        ]);
        
        $this->assertEquals('payu', $this->paymentService->getActiveGateway($copropiedad));
    }
}

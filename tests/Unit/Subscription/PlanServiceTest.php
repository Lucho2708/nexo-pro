<?php

namespace Tests\Unit\Subscription;

use App\Modules\Property\Models\Copropiedad;
use App\Modules\Property\Models\Unidad;
use App\Services\Subscription\PlanService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PlanServiceTest extends TestCase
{
    use RefreshDatabase;

    private PlanService $planService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->planService = new PlanService();
    }

    public function test_returns_all_official_plans_with_consistent_limits(): void
    {
        $plans = $this->planService->getPlans();

        $this->assertArrayHasKey('boutique', $plans);
        $this->assertArrayHasKey('condominio_pro', $plans);
        $this->assertArrayHasKey('macro_conjunto', $plans);

        $this->assertEquals(60, $plans['boutique']['max_units']);
        $this->assertEquals(150, $plans['condominio_pro']['max_units']);
        $this->assertEquals(350, $plans['macro_conjunto']['max_units']);

        $this->assertEquals(189000, $plans['boutique']['price_monthly']);
        $this->assertEquals(289000, $plans['condominio_pro']['price_monthly']);
        $this->assertEquals(420000, $plans['macro_conjunto']['price_monthly']);
    }

    public function test_allows_unit_creation_within_plan_limit(): void
    {
        $copropiedad = Copropiedad::create([
            'nombre' => 'Edificio Los Rosales',
            'nit' => '900111222-1',
            'direccion' => 'Calle 10 # 20-30',
            'ciudad' => 'Bogotá',
            'plan' => 'boutique',
        ]);

        $check = $this->planService->validateUnitCapacity($copropiedad, 50);

        $this->assertTrue($check['allowed']);
        $this->assertEquals(60, $check['max_units']);
        $this->assertEquals(0, $check['current_units']);
        $this->assertEquals(50, $check['attempted_total']);
        $this->assertNull($check['message']);
    }

    public function test_blocks_unit_creation_exceeding_plan_limit_with_informative_message(): void
    {
        $copropiedad = Copropiedad::create([
            'nombre' => 'Condominio La Floresta',
            'nit' => '900222333-2',
            'direccion' => 'Carrera 15 # 40-50',
            'ciudad' => 'Medellín',
            'plan' => 'boutique', // max 60 units
        ]);

        // Attempting to generate 70 units
        $check = $this->planService->validateUnitCapacity($copropiedad, 70);

        $this->assertFalse($check['allowed']);
        $this->assertEquals(60, $check['max_units']);
        $this->assertEquals(70, $check['attempted_total']);
        $this->assertNotNull($check['message']);
        $this->assertStringContainsString('BOUTIQUE', $check['message']);
        $this->assertStringContainsString('60', $check['message']);
        $this->assertStringContainsString('70', $check['message']);
        $this->assertStringContainsString('actualizado', strtolower($check['message']));
    }

    public function test_blocks_additional_units_when_accumulated_units_exceed_limit(): void
    {
        $copropiedad = Copropiedad::create([
            'nombre' => 'Torres de San Juan',
            'nit' => '900333444-3',
            'direccion' => 'Avenida 68 # 80-90',
            'ciudad' => 'Cali',
            'plan' => 'condominio_pro', // max 150 units
        ]);

        // Pre-create 140 units
        for ($i = 1; $i <= 140; $i++) {
            Unidad::create([
                'copropiedad_id' => $copropiedad->id,
                'nombre' => (string) $i,
                'torre' => '1',
            ]);
        }

        // Attempting to add 15 more units (140 + 15 = 155 > 150)
        $check = $this->planService->validateUnitCapacity($copropiedad, 15);

        $this->assertFalse($check['allowed']);
        $this->assertEquals(140, $check['current_units']);
        $this->assertEquals(155, $check['attempted_total']);
        $this->assertEquals(150, $check['max_units']);
    }

    public function test_enterprise_plan_allows_unlimited_units(): void
    {
        $copropiedad = Copropiedad::create([
            'nombre' => 'Macro Complejo Titán',
            'nit' => '900444555-4',
            'direccion' => 'Autopista Norte # 200',
            'ciudad' => 'Bogotá',
            'plan' => 'enterprise',
        ]);

        $check = $this->planService->validateUnitCapacity($copropiedad, 1200);

        $this->assertTrue($check['allowed']);
        $this->assertNull($check['max_units']);
    }

    public function test_maps_legacy_plan_slugs_gracefully(): void
    {
        $legacyBasic = $this->planService->getPlan('basic');
        $this->assertEquals('BOUTIQUE', $legacyBasic['name']);
        $this->assertEquals(60, $legacyBasic['max_units']);

        $legacyPro = $this->planService->getPlan('pro');
        $this->assertEquals('CONDOMINIO PRO', $legacyPro['name']);
        $this->assertEquals(150, $legacyPro['max_units']);
    }
}

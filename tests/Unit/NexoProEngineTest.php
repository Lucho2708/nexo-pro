<?php

declare(strict_types=1);

namespace Tests\Unit;

use Tests\TestCase;
use NexoPro\Property\Services\DeterministicCoefficientEngine;
use NexoPro\Property\ValueObjects\AreaSquareMeters;
use NexoPro\Property\ValueObjects\CoefficientPercentage;
use NexoPro\Assembly\Strategies\CoefficientVotingStrategy;
use NexoPro\Assembly\Strategies\NominalVotingStrategy;
use NexoPro\Assembly\DTOs\CastVoteDTO;
use NexoPro\Assembly\DTOs\QuorumStateDTO;
use NexoPro\Finance\DTOs\PaymentOrderDTO;
use NexoPro\Finance\Gateways\WompiPaymentGateway;
use NexoPro\Tenancy\Contracts\TenantContextInterface;
use NexoPro\Tenancy\Services\FailClosedTenancyGuard;
use RuntimeException;
use InvalidArgumentException;

class NexoProEngineTest extends TestCase
{
    /**
     * Test DeterministicCoefficientEngine guarantees 100.0000% sum even with irregular decimals.
     */
    public function test_deterministic_coefficient_engine_guarantees_100_percent(): void
    {
        $engine = new DeterministicCoefficientEngine();

        // 3 unidades con áreas que producen división periódica: 33.33333333...
        $areaTotal = AreaSquareMeters::fromFloat(100.0);
        $unidades = [
            ['id' => 'u1', 'area' => 33.3333],
            ['id' => 'u2', 'area' => 33.3333],
            ['id' => 'u3', 'area' => 33.3334],
        ];

        $resultados = $engine->compute($areaTotal, $unidades);

        $this->assertCount(3, $resultados);

        $suma = array_sum(array_column($resultados, 'coeficiente'));
        $this->assertEquals(100.0000, round($suma, 4), 'La suma de coeficientes calculados debe ser exactamente 100.0000%');
    }

    /**
     * Test AreaSquareMeters rejects non-positive values.
     */
    public function test_area_square_meters_rejects_zero_or_negative(): void
    {
        $this->expectException(InvalidArgumentException::class);
        AreaSquareMeters::fromFloat(0.0);
    }

    /**
     * Test CoefficientPercentage value object.
     */
    public function test_coefficient_percentage_validates_range(): void
    {
        $coef = CoefficientPercentage::fromFloat(12.34567);
        $this->assertEquals(12.34567, $coef->value);

        $this->expectException(InvalidArgumentException::class);
        CoefficientPercentage::fromFloat(100.00001);
    }

    /**
     * Test Voting Strategies (Coefficient vs Nominal).
     */
    public function test_voting_strategies_calculate_correctly(): void
    {
        $coefStrategy = new CoefficientVotingStrategy();
        $nominalStrategy = new NominalVotingStrategy();

        $votos = [
            ['opcion_id' => 'op_si', 'peso' => 25.5000, 'unidad_id' => 'u1'],
            ['opcion_id' => 'op_si', 'peso' => 30.0000, 'unidad_id' => 'u2'],
            ['opcion_id' => 'op_no', 'peso' => 20.0000, 'unidad_id' => 'u3'],
        ];

        $resCoef = $coefStrategy->tally($votos);
        $this->assertEquals(55.5000, $resCoef['op_si']['peso_acumulado']);
        $this->assertEquals(20.0000, $resCoef['op_no']['peso_acumulado']);
        $this->assertEquals(2, $resCoef['op_si']['conteo']);
        $this->assertEquals(1, $resCoef['op_no']['conteo']);

        $resNominal = $nominalStrategy->tally($votos);
        $this->assertEquals(2.0, $resNominal['op_si']['peso_acumulado']);
        $this->assertEquals(1.0, $resNominal['op_no']['peso_acumulado']);
    }

    /**
     * Test QuorumStateDTO calculates quorum thresholds accurately.
     */
    public function test_quorum_state_dto_evaluates_thresholds(): void
    {
        // 55% de presencia -> Deliberatorio SÍ, Calificado NO
        $quorum = QuorumStateDTO::fromCalculations(
            asambleaId: 'as-123',
            totalCoeficiente: 100.0,
            presenteCoeficiente: 55.0,
            totalUnidades: 100,
            presenteUnidades: 55
        );

        $this->assertTrue($quorum->tieneQuorumDeliberatorio);
        $this->assertFalse($quorum->tieneQuorumCalificado);
        $this->assertEquals(55.0, $quorum->porcentajeQuorum);

        // 72% de presencia -> Deliberatorio SÍ, Calificado SÍ
        $quorumCalificado = QuorumStateDTO::fromCalculations(
            asambleaId: 'as-123',
            totalCoeficiente: 100.0,
            presenteCoeficiente: 72.0,
            totalUnidades: 100,
            presenteUnidades: 72
        );

        $this->assertTrue($quorumCalificado->tieneQuorumDeliberatorio);
        $this->assertTrue($quorumCalificado->tieneQuorumCalificado);
    }

    /**
     * Test WompiPaymentGateway signature generation and fee calculation.
     */
    public function test_wompi_payment_gateway_signature_and_fee(): void
    {
        $gateway = new WompiPaymentGateway();

        $order = new PaymentOrderDTO(
            referencia: 'NEXO-TEST-123',
            monto: 250000.0,
            moneda: 'COP',
            unidadId: 'unit-1',
            userId: 'user-1',
            correoTitular: 'residente@nexo.pro',
            urlRetorno: 'https://nexo.pro/owner/payments'
        );

        $checkout = $gateway->prepareCheckout($order, [
            'public_key' => 'pub_test_123',
            'integrity_key' => 'integ_secret_xyz'
        ]);

        $this->assertEquals(25000000, $checkout['amount_in_cents']);
        $this->assertNotEmpty($checkout['signature']);

        // Comisión: 2.85% de 250,000 = 7,125 + 800 = 7,925
        $fee = $gateway->calculateFee(250000.0);
        $this->assertEquals(7925.0, $fee);
    }

    /**
     * Test FailClosedTenancyGuard blocks requests without active tenant.
     */
    public function test_fail_closed_tenancy_guard_blocks_without_context(): void
    {
        $mockContext = new class implements TenantContextInterface {
            public function getActiveCopropiedadId(): ?string { return null; }
            public function hasActiveContext(): bool { return false; }
            public function isSuperAdmin(): bool { return false; }
        };

        $guard = new FailClosedTenancyGuard($mockContext);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Aislamiento de seguridad activado');

        $guard->requireActiveTenantId();
    }
}

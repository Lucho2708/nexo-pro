<?php

declare(strict_types=1);

namespace Tests\Unit\Property;

use Tests\TestCase;
use NexoPro\Property\Services\DeterministicCoefficientEngine;
use NexoPro\Property\Contracts\ResidueCompensationStrategyInterface;
use NexoPro\Property\ValueObjects\AreaSquareMeters;
use Psr\Log\LoggerInterface;
use Mockery;
use InvalidArgumentException;

/**
 * DeterministicCoefficientEngineTest
 *
 * [LÓGICA DE NEGOCIO]
 * Valida que el motor algorítmico garantice incondicionalmente el 100.0000% en la sumatoria de coeficientes
 * para cualquier copropiedad (Ley 675 de Colombia, Régimen de Propiedad Horizontal).
 * Asegura que ningún microajuste decimal quede sin bitácora probatoria y que los residuos
 * se asignen a la unidad jurídica más adecuada.
 *
 * [ASPECTO TÉCNICO]
 * Pruebas unitarias de precisión matemática, algoritmos O(N) y validación de inyección de estrategias (Strategy Pattern).
 */
class DeterministicCoefficientEngineTest extends TestCase
{
    /**
     * Happy Path: Suma exacta del 100.0000% con áreas proporcionales estándar.
     */
    public function test_compute_calculates_exact_proportions_with_no_residue(): void
    {
        $engine = new DeterministicCoefficientEngine();

        $areaTotal = AreaSquareMeters::fromFloat(1000.0);
        $unidades = [
            ['id' => 'u-1', 'area' => 500.0],
            ['id' => 'u-2', 'area' => 300.0],
            ['id' => 'u-3', 'area' => 200.0],
        ];

        $resultados = $engine->compute($areaTotal, $unidades);

        $this->assertCount(3, $resultados);
        $this->assertEquals(50.0000, $resultados[0]['coeficiente']);
        $this->assertEquals(30.0000, $resultados[1]['coeficiente']);
        $this->assertEquals(20.0000, $resultados[2]['coeficiente']);

        $suma = array_sum(array_column($resultados, 'coeficiente'));
        $this->assertEquals(100.0000, $suma);
    }

    /**
     * Edge Case 1: División periódica infinita (7 unidades iguales de 10 m2 en 70 m2 totales = 14.285714...%).
     * Debe aplicar compensación de residuo y dar exactamente 100.0000%.
     */
    public function test_compute_handles_infinite_periodic_fractions_and_guarantees_exact_100_percent(): void
    {
        $engine = new DeterministicCoefficientEngine();

        $areaTotal = AreaSquareMeters::fromFloat(70.0);
        $unidades = [];
        for ($i = 1; $i <= 7; $i++) {
            $unidades[] = ['id' => "u-{$i}", 'area' => 10.0];
        }

        $resultados = $engine->compute($areaTotal, $unidades);

        $this->assertCount(7, $resultados);
        $suma = round(array_sum(array_column($resultados, 'coeficiente')), 4);
        $this->assertEquals(100.0000, $suma, 'La suma debe ser exactamente 100.0000% tras la compensación determinista de residuos.');
    }

    /**
     * Edge Case 2: Inyección de estrategia personalizada de compensación de residuo (OCP / DIP).
     */
    public function test_compute_uses_custom_compensation_strategy_when_injected(): void
    {
        $mockStrategy = Mockery::mock(ResidueCompensationStrategyInterface::class);

        // La estrategia personalizada elige forzadamente el índice 1
        $mockStrategy->shouldReceive('selectAdjustmentIndex')
            ->once()
            ->andReturn(1);

        $engine = new DeterministicCoefficientEngine($mockStrategy);

        $areaTotal = AreaSquareMeters::fromFloat(30.0);
        $unidades = [
            ['id' => 'u-1', 'area' => 10.0],
            ['id' => 'u-2', 'area' => 10.0],
            ['id' => 'u-3', 'area' => 10.0],
        ];

        $resultados = $engine->compute($areaTotal, $unidades);

        $suma = round(array_sum(array_column($resultados, 'coeficiente')), 4);
        $this->assertEquals(100.0000, $suma);
        $this->assertCount(3, $resultados);
    }

    /**
     * Edge Case 3: Verifica que el Logger reciba el evento de auditoría cuando existe microajuste.
     */
    public function test_compute_logs_audit_when_microadjustment_is_applied(): void
    {
        $mockLogger = Mockery::mock(LoggerInterface::class);
        $mockLogger->shouldReceive('info')
            ->once()
            ->with(
                Mockery::pattern('/Microajuste de coeficiente aplicado/'),
                Mockery::on(function (array $context) {
                    return isset($context['residuo_delta']) &&
                           isset($context['unidad_id']) &&
                           isset($context['coef_anterior']) &&
                           isset($context['coef_nuevo']);
                })
            );

        $engine = new DeterministicCoefficientEngine(null, $mockLogger);

        $areaTotal = AreaSquareMeters::fromFloat(30.0);
        $unidades = [
            ['id' => 'u-1', 'area' => 10.0],
            ['id' => 'u-2', 'area' => 10.0],
            ['id' => 'u-3', 'area' => 10.0],
        ];

        $engine->compute($areaTotal, $unidades);
    }

    /**
     * Edge Case 4: Lanza InvalidArgumentException si no se pasan unidades.
     */
    public function test_compute_throws_on_empty_units(): void
    {
        $engine = new DeterministicCoefficientEngine();
        $areaTotal = AreaSquareMeters::fromFloat(100.0);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('No se proporcionaron unidades para el cálculo de coeficientes.');

        $engine->compute($areaTotal, []);
    }

    /**
     * Edge Case 5: Unidades con área negativa o cero son omitidas de forma segura.
     */
    public function test_compute_skips_zero_or_negative_area_units(): void
    {
        $engine = new DeterministicCoefficientEngine();
        $areaTotal = AreaSquareMeters::fromFloat(100.0);

        $unidades = [
            ['id' => 'u-valida', 'area' => 100.0],
            ['id' => 'u-cero', 'area' => 0.0],
            ['id' => 'u-negativa', 'area' => -15.5],
        ];

        $resultados = $engine->compute($areaTotal, $unidades);

        $this->assertCount(1, $resultados);
        $this->assertEquals('u-valida', $resultados[0]['id']);
        $this->assertEquals(100.0000, $resultados[0]['coeficiente']);
    }
}

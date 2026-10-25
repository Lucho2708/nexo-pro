<?php

declare(strict_types=1);

namespace NexoPro\Property\Services;

use NexoPro\Property\Contracts\ResidueCompensationStrategyInterface;
use NexoPro\Property\ValueObjects\AreaSquareMeters;
use InvalidArgumentException;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 * DeterministicCoefficientEngine
 *
 * [LÓGICA DE NEGOCIO]
 * Motor de cálculo algorítmico de coeficientes de copropiedad con compensación determinista de residuos.
 * Garantiza de forma estricta e inimpugnable que la suma de los coeficientes de todas las unidades
 * alcance exactamente el 100.0000% requerido por el Régimen de Propiedad Horizontal colombiano (Ley 675).
 * Deja registro detallado de cualquier microajuste decimal aplicado.
 *
 * [ASPECTO TÉCNICO]
 * Cumple con SRP (solo calcula y compensa coeficientes) y DIP (recibe la estrategia de compensación y logger).
 * Complejidad algorítmica: O(N) lineal en dos pases (Pase 1: cálculo base; Pase 2: compensación de residuo).
 * Consumo de memoria: O(N) para la estructura de retorno sin mutar entidades en frío.
 */
final class DeterministicCoefficientEngine
{
    private const TARGET_TOTAL = 100.0000;
    private const PRECISION = 4;

    public function __construct(
        private readonly ?ResidueCompensationStrategyInterface $compensationStrategy = null,
        private readonly LoggerInterface $logger = new NullLogger()
    ) {}

    /**
     * [LÓGICA DE NEGOCIO]
     * Calcula los coeficientes de un conjunto de unidades asegurando que la sumatoria sea 100.0000%.
     *
     * [ASPECTO TÉCNICO]
     * Algoritmo de dos pases determinista con compensación de residuo flotante.
     *
     * @param AreaSquareMeters $areaTotal Área construida total del condominio/edificio.
     * @param array<int, array{id: string, area: float}> $unidades Lista de unidades con su área privada.
     * @return array<int, array{id: string, area: float, coeficiente: float}> Unidades con coeficientes calculados.
     */
    public function compute(AreaSquareMeters $areaTotal, array $unidades): array
    {
        if (empty($unidades)) {
            throw new InvalidArgumentException('No se proporcionaron unidades para el cálculo de coeficientes.');
        }

        $areaTotalFloat = $areaTotal->value;
        $sumaCalculada = 0.0;
        $unidadesCalculadas = [];

        // 1. Primer pase: Cálculo proporcional base en O(N)
        foreach ($unidades as $u) {
            $areaUnidad = (float) $u['area'];
            if ($areaUnidad <= 0) {
                continue;
            }

            $coef = round(($areaUnidad / $areaTotalFloat) * 100, self::PRECISION);
            $sumaCalculada += $coef;

            $unidadesCalculadas[] = [
                'id' => $u['id'],
                'area' => $areaUnidad,
                'coeficiente' => $coef,
            ];
        }

        if (empty($unidadesCalculadas)) {
            return [];
        }

        // 2. Cálculo del residuo decimal
        $residuo = round(self::TARGET_TOTAL - $sumaCalculada, self::PRECISION);

        // 3. Compensación determinista si existe discrepancia de redondeo
        if ($residuo != 0.0) {
            $indiceAjuste = $this->determineAdjustmentIndex($unidadesCalculadas);
            $coefPrevio = $unidadesCalculadas[$indiceAjuste]['coeficiente'];
            $unidadesCalculadas[$indiceAjuste]['coeficiente'] = round($coefPrevio + $residuo, self::PRECISION);

            $this->logger->info("Microajuste de coeficiente aplicado para alcanzar 100.0000%", [
                'residuo_delta' => $residuo,
                'unidad_id'     => $unidadesCalculadas[$indiceAjuste]['id'],
                'coef_anterior' => $coefPrevio,
                'coef_nuevo'    => $unidadesCalculadas[$indiceAjuste]['coeficiente'],
            ]);
        }

        return $unidadesCalculadas;
    }

    /**
     * [LÓGICA DE NEGOCIO]
     * Selecciona la unidad que absorberá el residuo decimal. Por regla jurídica habitual,
     * se asigna a la unidad con la mayor área privada construida para minimizar el impacto relativo.
     *
     * [ASPECTO TÉCNICO]
     * Búsqueda del valor máximo en O(N) sin ordenar el array (evitando O(N log N)).
     */
    private function determineAdjustmentIndex(array $unidadesData): int
    {
        if ($this->compensationStrategy !== null) {
            return $this->compensationStrategy->selectAdjustmentIndex($unidadesData);
        }

        $maxArea = -1.0;
        $maxIndex = 0;

        foreach ($unidadesData as $index => $item) {
            if ($item['area'] > $maxArea) {
                $maxArea = $item['area'];
                $maxIndex = $index;
            }
        }

        return $maxIndex;
    }
}

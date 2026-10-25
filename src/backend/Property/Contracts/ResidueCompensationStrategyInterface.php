<?php

declare(strict_types=1);

namespace NexoPro\Property\Contracts;

/**
 * ResidueCompensationStrategyInterface
 *
 * [LÓGICA DE NEGOCIO]
 * Contrato de compensación matemática de residuos decimales.
 * Al dividir áreas privadas sobre el área total de la copropiedad, la suma puede arrojar 99.9998% o 100.0002%.
 * La ley exige un total exacto del 100.0000%. Esta estrategia define a qué unidad(es) se le atribuye
 * la diferencia decimal de forma transparente, equitativa y auditable.
 *
 * [ASPECTO TÉCNICO]
 * Aplica Strategy Pattern (OCP). Permite alternar entre asignación a la unidad mayor,
 * prorrateo por mayor residuo (Hare-Niemeyer) o distribución secuencial.
 */
interface ResidueCompensationStrategyInterface
{
    /**
     * [LÓGICA DE NEGOCIO]
     * Determina el índice de la unidad que recibirá el ajuste del residuo acumulado.
     *
     * [ASPECTO TÉCNICO]
     * @param array<int, array{id: string, area: float, coeficiente: float}> $unidadesData
     * @return int Índice de la unidad seleccionada en el array.
     */
    public function selectAdjustmentIndex(array $unidadesData): int;
}

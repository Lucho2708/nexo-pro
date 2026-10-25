<?php

namespace NexoPro\Operations\Services;

use NexoPro\Operations\Contracts\LeadScoringServiceInterface;
use NexoPro\Operations\DTOs\LeadCalculationDTO;

/**
 * [LÓGICA DE NEGOCIO / UX]
 * Motor determinista de cálculo financiero y calificación de prospectos para propiedad horizontal.
 * Cuantifica el retorno de inversión (ROI) proyectando el valor rescatado de cartera en mora
 * y las horas hombre ahorradas en conciliación y gestión operativa mensual.
 *
 * [ASPECTO TÉCNICO / UI]
 * Implementa LeadScoringServiceInterface respetando Single Responsibility Principle (SRP).
 * Sin efectos colaterales de base de datos ni dependencias directas con el framework web,
 * facilitando pruebas unitarias de velocidad ultra-rápida en Pest y PHPUnit.
 */
class LeadScoringService implements LeadScoringServiceInterface
{
    /** Costo promedio de hora administrativa operativa en Colombia (COP) */
    private const VALOR_HORA_ADMIN_COP = 25000.0;

    /** Coeficiente de horas ahorradas por unidad al mes (0.22 horas/unidad/mes) */
    private const FACTOR_HORAS_UNIDAD_MES = 0.22;

    /** Tasa anual estimada de recuperación de cartera mediante pasarela Wompi y cobro automatizado (4.5%) */
    private const TASA_RECUPERACION_CARTERA_ANUAL = 0.045;

    public function calculateAndScore(int $unidades, float $cuotaPromedioMensual, string $rol = 'administrador'): LeadCalculationDTO
    {
        $unidadesSanitizadas = max(10, $unidades);
        $cuotaSanitizada = max(50000.0, $cuotaPromedioMensual);

        // 1. Cálculo de horas mensuales ahorradas
        $horasMes = (int) round($unidadesSanitizadas * self::FACTOR_HORAS_UNIDAD_MES);
        $horasMes = max(6, $horasMes); // Mínimo garantizado de 6 horas/mes

        // 2. Facturación anual total proyectada
        $facturacionAnualTotal = $unidadesSanitizadas * $cuotaSanitizada * 12.0;

        // 3. Cartera morosa recuperable estimada
        $carteraRecuperable = round($facturacionAnualTotal * self::TASA_RECUPERACION_CARTERA_ANUAL, 2);

        // 4. Ahorro administrativo por automatización
        $ahorroAdminAnual = $horasMes * 12.0 * self::VALOR_HORA_ADMIN_COP;

        // 5. Ahorro económico anual neto consolidado
        $ahorroNetoAnual = round($ahorroAdminAnual + $carteraRecuperable, 2);

        // 6. Segmento y Scoring comercial
        $segmento = $this->determineSegment($unidadesSanitizadas);
        $score = $this->computeScore($unidadesSanitizadas, $rol);

        $prioridad = match (true) {
            $segmento === 'enterprise' || $score >= 80 => 'urgente',
            $segmento === 'standard' || $score >= 60 => 'alta',
            default => 'estandar',
        };

        return new LeadCalculationDTO(
            unidadesTotales: $unidadesSanitizadas,
            cuotaPromedioMensual: $cuotaSanitizada,
            horasAhorradasMes: $horasMes,
            carteraRecuperableEstimada: $carteraRecuperable,
            ahorroEstimadoAnual: $ahorroNetoAnual,
            segmento: $segmento,
            score: $score,
            prioridadContacto: $prioridad
        );
    }

    public function determineSegment(int $unidades): string
    {
        return match (true) {
            $unidades >= 300 => 'enterprise',
            $unidades >= 100 => 'standard',
            default => 'boutique',
        };
    }

    public function computeScore(int $unidades, string $rol): int
    {
        // Puntos base por volumen de unidades (0 a 50 puntos)
        $puntosUnidades = match (true) {
            $unidades >= 500 => 50,
            $unidades >= 300 => 45,
            $unidades >= 200 => 38,
            $unidades >= 100 => 30,
            $unidades >= 50 => 20,
            default => 12,
        };

        // Puntos por rol decisor (0 a 50 puntos)
        $rolNormalizado = strtolower(trim($rol));
        $puntosRol = match ($rolNormalizado) {
            'constructor', 'constructora' => 50,
            'administrador', 'empresa_administradora' => 45,
            'miembro_consejo', 'consejo', 'consejero' => 38,
            'propietario' => 25,
            default => 20,
        };

        $totalScore = $puntosUnidades + $puntosRol;

        return max(1, min(100, $totalScore));
    }
}

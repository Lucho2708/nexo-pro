<?php

declare(strict_types=1);

namespace NexoPro\Assembly\DTOs;

/**
 * QuorumStateDTO
 *
 * [LÓGICA DE NEGOCIO]
 * Estructura de estado consolidado del quórum de una asamblea de copropietarios en un instante dado.
 * Según el Artículo 45 de la Ley 675 de 2001, la asamblea deliberará válidamente con la presencia
 * de un número plural de propietarios que represente más del 50% de los coeficientes de la copropiedad,
 * salvo excepciones de decisiones calificadas (70%).
 *
 * [ASPECTO TÉCNICO]
 * DTO inmutable serializable a JSON con evaluación computada de umbrales. Complejidad de lectura O(1).
 * Diseñado para ser transmitido por WebSockets binarios/texto mediante Laravel Reverb con consumo
 * mínimo de ancho de banda por trama (< 256 bytes).
 */
final readonly class QuorumStateDTO
{
    /**
     * @param string $asambleaId UUID de la asamblea activa.
     * @param float $totalCoeficiente Suma de coeficientes de toda la copropiedad (100.0000%).
     * @param float $presenteCoeficiente Suma de coeficientes con presencia verificada en sala.
     * @param int $totalUnidades Total de inmuebles matriculados en la copropiedad.
     * @param int $presenteUnidades Total de inmuebles con representación activa en la sesión.
     * @param float $porcentajeQuorum Porcentaje de asistencia respecto al total (ej: 64.2500%).
     * @param bool $tieneQuorumDeliberatorio Indica si se superó el 50.01% legal para sesionar.
     * @param bool $tieneQuorumCalificado Indica si se superó el 70.00% legal para decisiones especiales.
     */
    public function __construct(
        public string $asambleaId,
        public float $totalCoeficiente,
        public float $presenteCoeficiente,
        public int $totalUnidades,
        public int $presenteUnidades,
        public float $porcentajeQuorum,
        public bool $tieneQuorumDeliberatorio,
        public bool $tieneQuorumCalificado
    ) {}

    /**
     * [LÓGICA DE NEGOCIO]
     * Fábrica estática para calcular el estado del quórum a partir de totales y presencias registradas.
     *
     * [ASPECTO TÉCNICO]
     * Realiza operaciones aritméticas O(1) con protección contra división por cero.
     */
    public static function fromCalculations(
        string $asambleaId,
        float $totalCoeficiente,
        float $presenteCoeficiente,
        int $totalUnidades,
        int $presenteUnidades
    ): self {
        $porcentaje = $totalCoeficiente > 0
            ? round(($presenteCoeficiente / $totalCoeficiente) * 100, 4)
            : 0.0;

        return new self(
            asambleaId: $asambleaId,
            totalCoeficiente: round($totalCoeficiente, 4),
            presenteCoeficiente: round($presenteCoeficiente, 4),
            totalUnidades: $totalUnidades,
            presenteUnidades: $presenteUnidades,
            porcentajeQuorum: $porcentaje,
            tieneQuorumDeliberatorio: $porcentaje >= 50.01,
            tieneQuorumCalificado: $porcentaje >= 70.00
        );
    }
}

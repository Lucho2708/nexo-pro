<?php

namespace NexoPro\Operations\DTOs;

/**
 * [LÓGICA DE NEGOCIO / UX]
 * Objeto inmutable de transferencia que condensa las métricas financieras proyectadas
 * (ahorro en nómina/tiempo de administración, morosidad rescatada y retorno económico anual).
 *
 * [ASPECTO TÉCNICO / UI]
 * DTO tipado estricto (PHP 8.2+) para eliminar arrays asociativos opacos y garantizar
 * inmutabilidad en los cálculos matemáticos de conversión.
 */
final class LeadCalculationDTO
{
    public function __construct(
        public readonly int $unidadesTotales,
        public readonly float $cuotaPromedioMensual,
        public readonly int $horasAhorradasMes,
        public readonly float $carteraRecuperableEstimada,
        public readonly float $ahorroEstimadoAnual,
        public readonly string $segmento,
        public readonly int $score,
        public readonly string $prioridadContacto
    ) {}

    public function toArray(): array
    {
        return [
            'unidades_totales' => $this->unidadesTotales,
            'cuota_promedio_mensual' => $this->cuotaPromedioMensual,
            'horas_ahorradas_mes' => $this->horasAhorradasMes,
            'cartera_recuperable_estimada' => $this->carteraRecuperableEstimada,
            'ahorro_estimado_anual' => $this->ahorroEstimadoAnual,
            'segmento' => $this->segmento,
            'score' => $this->score,
            'prioridad_contacto' => $this->prioridadContacto,
        ];
    }
}

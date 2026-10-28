<?php

declare(strict_types=1);

namespace NexoPro\Property\DTOs;

/**
 * ImportValidationResultDTO
 *
 * [LÓGICA DE NEGOCIO]
 * Contiene el resultado integral de la auditoría y pre-visualización del archivo de unidades importado.
 * Entrega al frontend estadísticas exactas (total unidades, torres, desglose de modelos, sumatoria de coeficientes).
 *
 * [PRINCIPIOS SOLID]
 * - SRP: Encapsula el estado de validación y la telemetría previa a la persistencia.
 */
final class ImportValidationResultDTO
{
    /**
     * @param bool $isValid Indica si el archivo superó todas las reglas de negocio y está listo para importar.
     * @param array<int, string> $errors Lista de errores bloqueantes (ej: modelos no encontrados, nombres vacíos).
     * @param array<int, string> $warnings Lista de advertencias no bloqueantes.
     * @param array{
     *     total_units: int,
     *     towers_count: int,
     *     models_breakdown: array<string, int>,
     *     sum_coeficientes: float,
     *     is_100_percent: bool
     * } $stats
     * @param array<int, ImportUnitRowDTO> $validRows Lista de filas procesadas y normalizadas.
     */
    public function __construct(
        public readonly bool $isValid,
        public readonly array $errors,
        public readonly array $warnings,
        public readonly array $stats,
        public readonly array $validRows
    ) {}

    /**
     * Serializa para respuesta JSON de Inertia / Axios.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'is_valid' => $this->isValid,
            'errors' => $this->errors,
            'warnings' => $this->warnings,
            'stats' => $this->stats,
            'preview_rows' => array_map(function (ImportUnitRowDTO $row) {
                return [
                    'torre' => $row->torre,
                    'nombre' => $row->nombre,
                    'piso' => $row->piso,
                    'modelo' => $row->modelo,
                    'propietario' => $row->propietarioNombre,
                    'identificacion' => $row->propietarioIdentificacion,
                    'telefono' => $row->telefonoContacto,
                    'email' => $row->emailContacto,
                    'saldo' => $row->saldoActual,
                    'coeficiente' => $row->coeficiente,
                ];
            }, array_slice($this->validRows, 0, 10)), // Muestra las primeras 10 como muestra rápida
        ];
    }
}

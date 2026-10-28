<?php

declare(strict_types=1);

namespace NexoPro\Property\Services;

use NexoPro\Property\DTOs\ImportUnitRowDTO;
use NexoPro\Property\DTOs\ImportValidationResultDTO;
use NexoPro\Property\ValueObjects\AreaSquareMeters;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Throwable;

/**
 * SpreadsheetUnitImportService
 *
 * [LÓGICA DE NEGOCIO]
 * Valida, audita y procesa hojas de cálculo (XLSX, CSV) cargadas por el administrador.
 * Asocia automáticamente cada unidad con su modelo arquitectónico certificado y ejecuta
 * el DeterministicCoefficientEngine para garantizar el 100.0000% de la Ley 675 si no se suministran coeficientes.
 *
 * [PRINCIPIOS SOLID]
 * - SRP: Valida y transforma la entrada cruda de hojas de cálculo a objetos de dominio auditados.
 * - DIP: Depende del motor algorítmico inyectado mediante su abstracción de cálculo.
 */
class SpreadsheetUnitImportService
{
    public function __construct(
        private readonly ?DeterministicCoefficientEngine $coefficientEngine = null
    ) {}

    /**
     * Parsea un archivo de hoja de cálculo desde el disco temporal del servidor.
     *
     * @param string $filePath Ruta absoluta del archivo subido.
     * @param array<string, array{id: string, area_m2: float}> $modelosCatalog Catálogo indexado por nombre de modelo.
     * @return ImportValidationResultDTO
     */
    public function parseSpreadsheetFile(string $filePath, array $modelosCatalog): ImportValidationResultDTO
    {
        try {
            $spreadsheet = IOFactory::load($filePath);
            $sheet = $spreadsheet->getSheetByName('Censo de Unidades') ?? $spreadsheet->getSheet(0);
            $data = $sheet->toArray(null, true, true, false);

            if (empty($data) || count($data) < 2) {
                return new ImportValidationResultDTO(
                    isValid: false,
                    errors: ['El archivo cargado está vacío o no contiene filas de datos.'],
                    warnings: [],
                    stats: [
                        'total_units' => 0,
                        'towers_count' => 0,
                        'models_breakdown' => [],
                        'sum_coeficientes' => 0.0,
                        'is_100_percent' => false,
                    ],
                    validRows: []
                );
            }

            // Mapear encabezados de la fila 1
            $headerRow = array_map(fn($col) => mb_strtolower(trim((string)$col)), $data[0]);
            $rows = [];

            for ($i = 1; $i < count($data); $i++) {
                $rawRow = $data[$i];

                // Saltar filas totalmente vacías
                $nonEmpty = array_filter($rawRow, fn($val) => !is_null($val) && trim((string)$val) !== '');
                if (empty($nonEmpty)) {
                    continue;
                }

                $mapped = [
                    'torre' => $this->extractColumnValue($rawRow, $headerRow, ['torre', 'bloque']),
                    'nombre' => $this->extractColumnValue($rawRow, $headerRow, ['número unidad', 'numero unidad', 'apto', 'unidad', 'nombre']),
                    'piso' => $this->extractColumnValue($rawRow, $headerRow, ['piso']),
                    'modelo' => $this->extractColumnValue($rawRow, $headerRow, ['modelo arquitectónico', 'modelo', 'tipo']),
                    'propietario_nombre' => $this->extractColumnValue($rawRow, $headerRow, ['nombre propietario', 'propietario', 'residente']),
                    'propietario_identificacion' => $this->extractColumnValue($rawRow, $headerRow, ['cédula', 'cedula', 'nit', 'identificacion']),
                    'celular' => $this->extractColumnValue($rawRow, $headerRow, ['celular', 'whatsapp', 'telefono', 'teléfono']),
                    'email' => $this->extractColumnValue($rawRow, $headerRow, ['correo electrónico', 'correo', 'email']),
                    'saldo' => $this->extractColumnValue($rawRow, $headerRow, ['saldo inicial', 'saldo', 'deuda']),
                    'coeficiente' => $this->extractColumnValue($rawRow, $headerRow, ['coeficiente', 'coef']),
                ];

                $rows[] = $mapped;
            }

            return $this->validateAndProcessRows($rows, $modelosCatalog);
        } catch (Throwable $e) {
            return new ImportValidationResultDTO(
                isValid: false,
                errors: ['Error al leer la hoja de cálculo: ' . $e->getMessage()],
                warnings: [],
                stats: [
                    'total_units' => 0,
                    'towers_count' => 0,
                    'models_breakdown' => [],
                    'sum_coeficientes' => 0.0,
                    'is_100_percent' => false,
                ],
                validRows: []
            );
        }
    }

    /**
     * Valida y procesa una matriz de filas crudas contra el catálogo de modelos de la copropiedad.
     *
     * @param array<int, array<string, mixed>> $rows
     * @param array<string, array{id: string, area_m2: float}> $modelosCatalog
     * @return ImportValidationResultDTO
     */
    public function validateAndProcessRows(array $rows, array $modelosCatalog): ImportValidationResultDTO
    {
        $errors = [];
        $warnings = [];
        $validRows = [];
        $towers = [];
        $modelsBreakdown = [];
        $needsCoefficientCalculation = false;

        // Normalizar catálogo de modelos (claves en minúscula para comparación tolerante)
        $normalizedCatalog = [];
        foreach ($modelosCatalog as $key => $val) {
            $normalizedCatalog[mb_strtolower(trim((string)$key))] = [
                'original_name' => (string)$key,
                'id' => (string)($val['id'] ?? ''),
                'area_m2' => (float)($val['area_m2'] ?? 0.0),
            ];
        }

        $rowNumber = 1; // Para mensajes de error humanos (1-based + 1 por encabezado)
        foreach ($rows as $r) {
            $rowNumber++;
            $nombre = trim((string)($r['nombre'] ?? ''));
            $modeloRaw = trim((string)($r['modelo'] ?? ''));

            if ($nombre === '') {
                $errors[] = "Fila {$rowNumber}: El número de apartamento o unidad es obligatorio.";
                continue;
            }

            if ($modeloRaw === '') {
                $errors[] = "Fila {$rowNumber} (Unidad '{$nombre}'): Debe especificar un modelo arquitectónico.";
                continue;
            }

            $normalizedModeloKey = mb_strtolower($modeloRaw);
            if (!isset($normalizedCatalog[$normalizedModeloKey])) {
                $errors[] = "Fila {$rowNumber} (Unidad '{$nombre}'): El modelo '{$modeloRaw}' no existe en el catálogo certificado de la copropiedad.";
                continue;
            }

            $modeloInfo = $normalizedCatalog[$normalizedModeloKey];
            $modeloCanonical = $modeloInfo['original_name'];

            // Conteo para estadísticas
            $modelsBreakdown[$modeloCanonical] = ($modelsBreakdown[$modeloCanonical] ?? 0) + 1;

            $torre = !empty($r['torre']) ? trim((string)$r['torre']) : null;
            if ($torre !== null && !in_array($torre, $towers, true)) {
                $towers[] = $torre;
            }

            $coeficienteRaw = $r['coeficiente'] ?? null;
            $coeficiente = null;
            if ($coeficienteRaw !== null && trim((string)$coeficienteRaw) !== '') {
                $coeficiente = (float)$coeficienteRaw;
            } else {
                $needsCoefficientCalculation = true;
            }

            $saldoRaw = $r['saldo'] ?? 0;
            $saldoActual = is_numeric($saldoRaw) ? (float)$saldoRaw : 0.0;

            $dto = new ImportUnitRowDTO(
                torre: $torre,
                nombre: $nombre,
                piso: !empty($r['piso']) ? trim((string)$r['piso']) : null,
                modelo: $modeloCanonical,
                propietarioNombre: !empty($r['propietario_nombre']) ? trim((string)$r['propietario_nombre']) : null,
                propietarioIdentificacion: !empty($r['propietario_identificacion']) ? trim((string)$r['propietario_identificacion']) : null,
                emailContacto: !empty($r['email']) ? trim((string)$r['email']) : null,
                telefonoContacto: !empty($r['celular']) ? trim((string)$r['celular']) : null,
                saldoActual: $saldoActual,
                coeficiente: $coeficiente
            );

            $validRows[] = $dto;
        }

        // Si hay errores de validación, retornamos inmediatamente el resultado inválido
        if (!empty($errors)) {
            return new ImportValidationResultDTO(
                isValid: false,
                errors: $errors,
                warnings: $warnings,
                stats: [
                    'total_units' => count($validRows),
                    'towers_count' => count($towers),
                    'models_breakdown' => $modelsBreakdown,
                    'sum_coeficientes' => 0.0,
                    'is_100_percent' => false,
                ],
                validRows: []
            );
        }

        // Proceso de Cálculo / Normalización de Coeficientes (Ley 675)
        if ($needsCoefficientCalculation && !empty($validRows)) {
            $validRows = $this->calculateDeterministicCoefficients($validRows, $normalizedCatalog);
            $warnings[] = 'Los coeficientes de votación fueron calculados automáticamente de forma determinista basándose en el área de cada modelo (Ley 675).';
        }

        $sumCoeficientes = round(array_sum(array_map(fn(ImportUnitRowDTO $r) => $r->coeficiente ?? 0.0, $validRows)), 4);
        $is100Percent = abs($sumCoeficientes - 100.0000) < 0.0001;

        if (!$is100Percent && !empty($validRows)) {
            $warnings[] = "La sumatoria actual de coeficientes es {$sumCoeficientes}%. Verifique si concuerda con la escritura de propiedad horizontal.";
        }

        return new ImportValidationResultDTO(
            isValid: true,
            errors: [],
            warnings: $warnings,
            stats: [
                'total_units' => count($validRows),
                'towers_count' => count($towers),
                'models_breakdown' => $modelsBreakdown,
                'sum_coeficientes' => $sumCoeficientes,
                'is_100_percent' => $is100Percent,
            ],
            validRows: $validRows
        );
    }

    /**
     * Utiliza el DeterministicCoefficientEngine para asignar coeficientes exactos que suman 100.0000%.
     *
     * @param array<int, ImportUnitRowDTO> $rows
     * @param array<string, array{id: string, original_name: string, area_m2: float}> $normalizedCatalog
     * @return array<int, ImportUnitRowDTO>
     */
    private function calculateDeterministicCoefficients(array $rows, array $normalizedCatalog): array
    {
        $engine = $this->coefficientEngine ?? new DeterministicCoefficientEngine();

        // 1. Calcular área privada total del conjunto a partir de los modelos de las unidades
        $unitsPayload = [];
        $areaTotalM2 = 0.0;

        foreach ($rows as $index => $row) {
            $key = mb_strtolower($row->modelo);
            $area = $normalizedCatalog[$key]['area_m2'] ?? 50.0;
            if ($area <= 0) {
                $area = 50.0; // Fallback razonable si el modelo se guardó sin área
            }

            $areaTotalM2 += $area;
            $unitsPayload[] = [
                'id' => (string) $index,
                'area' => $area,
            ];
        }

        if ($areaTotalM2 <= 0) {
            return $rows;
        }

        $computed = $engine->compute(AreaSquareMeters::fromFloat($areaTotalM2), $unitsPayload);
        $computedMap = [];
        foreach ($computed as $c) {
            $computedMap[(int)$c['id']] = $c['coeficiente'];
        }

        // Reconstruir los DTOs con los coeficientes exactos
        $rebuiltRows = [];
        foreach ($rows as $index => $row) {
            $rebuiltRows[] = new ImportUnitRowDTO(
                torre: $row->torre,
                nombre: $row->nombre,
                piso: $row->piso,
                modelo: $row->modelo,
                propietarioNombre: $row->propietarioNombre,
                propietarioIdentificacion: $row->propietarioIdentificacion,
                emailContacto: $row->emailContacto,
                telefonoContacto: $row->telefonoContacto,
                saldoActual: $row->saldoActual,
                coeficiente: $computedMap[$index] ?? 0.0
            );
        }

        return $rebuiltRows;
    }

    /**
     * Extrae el valor de una columna buscando coincidencias en la lista de encabezados.
     */
    private function extractColumnValue(array $rawRow, array $headerRow, array $possibleNames): ?string
    {
        foreach ($possibleNames as $name) {
            $index = -1;
            foreach ($headerRow as $hIndex => $hName) {
                if (str_contains($hName, $name)) {
                    $index = $hIndex;
                    break;
                }
            }

            if ($index !== -1 && isset($rawRow[$index])) {
                $val = trim((string)$rawRow[$index]);
                return $val !== '' ? $val : null;
            }
        }

        return null;
    }
}

<?php

declare(strict_types=1);

namespace NexoPro\Property\Services;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

/**
 * SpreadsheetUnitTemplateService
 *
 * [LÓGICA DE NEGOCIO]
 * Generador de la plantilla oficial de Excel para el censo de unidades en copropiedades.
 * Incorpora los modelos arquitectónicos registrados por el administrador como listas desplegables (dropdowns),
 * eliminando errores humanos de tipeo y asegurando integridad referencial en la base de datos.
 *
 * [PRINCIPIOS SOLID]
 * - SRP: Su única responsabilidad es componer y formatear la hoja de cálculo de onboarding.
 * - OCP: El diseño de columnas y estilos se puede extender sin romper el contrato de generación.
 */
class SpreadsheetUnitTemplateService
{
    /**
     * Genera el archivo Excel en memoria y devuelve el contenido binario para descarga HTTP.
     *
     * @param string $copropiedadNombre
     * @param array<int, array{id: string, nombre: string, area_m2: float}> $modelos
     * @return string
     */
    public function generateBinary(string $copropiedadNombre, array $modelos = []): string
    {
        $spreadsheet = new Spreadsheet();
        $spreadsheet->getProperties()
            ->setCreator('NEXO-PRO Cloud Suite')
            ->setTitle("Plantilla Censo de Unidades - {$copropiedadNombre}")
            ->setSubject('Onboarding de Unidades y Residentes');

        // PESTAÑA 1: Censo de Unidades
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Censo de Unidades');

        // Encabezados
        $headers = [
            'A1' => 'Torre / Bloque',
            'B1' => 'Número Unidad / Apto *',
            'C1' => 'Piso',
            'D1' => 'Modelo Arquitectónico *',
            'E1' => 'Nombre Propietario',
            'F1' => 'Cédula / NIT Propietario',
            'G1' => 'Celular / WhatsApp (Cobro)',
            'H1' => 'Correo Electrónico',
            'I1' => 'Saldo Inicial ($)',
            'J1' => 'Coeficiente (%) (Opcional)',
        ];

        foreach ($headers as $cell => $text) {
            $sheet->setCellValue($cell, $text);
        }

        // Estilo del encabezado (Brand Nexo-Pro: Verde esmeralda oscuro / Slate)
        $headerRange = 'A1:J1';
        $sheet->getStyle($headerRange)->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 11,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '0F766E'], // Emerald 700
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '0D9488'],
                ],
            ],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(32);

        // Si existen modelos, poblar la pestaña auxiliar de catálogos y crear el desplegable
        $modelNames = array_map(fn($m) => trim((string)$m['nombre']), $modelos);
        $modelNames = array_filter($modelNames, fn($n) => $n !== '');

        if (!empty($modelNames)) {
            // PESTAÑA 2: Catálogo de Modelos (Referencia para desplegable)
            $catSheet = $spreadsheet->createSheet();
            $catSheet->setTitle('_Modelos');

            $catSheet->setCellValue('A1', 'MODELOS DISPONIBLES');
            $catSheet->setCellValue('B1', 'ÁREA M2');
            $catSheet->getStyle('A1:B1')->getFont()->setBold(true);

            $rowIndex = 2;
            foreach ($modelos as $m) {
                $catSheet->setCellValue("A{$rowIndex}", trim((string)$m['nombre']));
                $catSheet->setCellValue("B{$rowIndex}", (float)($m['area_m2'] ?? 0));
                $rowIndex++;
            }

            // Ocultar la pestaña de catálogo para que no distraiga al usuario
            $catSheet->setSheetState(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::SHEETSTATE_HIDDEN);

            // Regla de Validación de Datos para las celdas D2:D500
            $validation = new DataValidation();
            $validation->setType(DataValidation::TYPE_LIST);
            $validation->setErrorStyle(DataValidation::STYLE_STOP);
            $validation->setAllowBlank(false);
            $validation->setShowInputMessage(true);
            $validation->setShowErrorMessage(true);
            $validation->setShowDropDown(true);
            $validation->setErrorTitle('Modelo No Válido');
            $validation->setError('Por favor selecciona un modelo arquitectónico de la lista.');
            $validation->setPromptTitle('Seleccionar Modelo');
            $validation->setPrompt('Elige el modelo registrado para esta unidad.');
            $validation->setFormula1("'_Modelos'!\$A\$2:\$A\$" . ($rowIndex - 1));

            // Aplicar validación a las filas
            for ($r = 2; $r <= 250; $r++) {
                $sheet->getCell("D{$r}")->setDataValidation(clone $validation);
            }
        }

        // Filas de Ejemplo reales para guiar al usuario
        $primerModelo = !empty($modelNames) ? reset($modelNames) : 'Apartamento Estándar';
        $segundoModelo = count($modelNames) > 1 ? $modelNames[1] : $primerModelo;

        $sampleData = [
            ['Torre 1', '101', '1', $primerModelo, 'Carlos Mario Restrepo', '71234567', '3001234567', 'carlos@ejemplo.com', 0, ''],
            ['Torre 1', '102', '1', $segundoModelo, 'Ana María Gómez', '1020456789', '3159876543', 'ana@ejemplo.com', 150000, ''],
        ];

        $sampleRow = 2;
        foreach ($sampleData as $row) {
            $colLetter = 'A';
            foreach ($row as $val) {
                $sheet->setCellValue("{$colLetter}{$sampleRow}", $val);
                $colLetter++;
            }
            $sampleRow++;
        }

        // Ajuste automático de ancho de columnas
        foreach (range('A', 'J') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Asegurar que la pestaña activa sea la principal de Unidades
        $spreadsheet->setActiveSheetIndex(0);

        // Devolver stream binario
        $writer = new Xlsx($spreadsheet);
        ob_start();
        $writer->save('php://output');
        return (string) ob_get_clean();
    }
}

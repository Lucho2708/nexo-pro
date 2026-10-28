<?php

declare(strict_types=1);

namespace Tests\Unit\Property;

use Tests\TestCase;
use NexoPro\Property\DTOs\ImportUnitRowDTO;
use NexoPro\Property\DTOs\ImportValidationResultDTO;
use NexoPro\Property\Services\SpreadsheetUnitImportService;
use NexoPro\Property\Services\SpreadsheetUnitTemplateService;
use NexoPro\Property\Services\DeterministicCoefficientEngine;

class SpreadsheetUnitImportServiceTest extends TestCase
{
    public function test_import_unit_row_dto_normalizes_data_correctly(): void
    {
        $dto = new ImportUnitRowDTO(
            torre: ' Torre 1 ',
            nombre: ' 101 ',
            piso: ' 1 ',
            modelo: ' Apartamento Tipo A ',
            propietarioNombre: ' Juan Pérez ',
            propietarioIdentificacion: ' 12345678 ',
            emailContacto: ' JUAN@ejemplo.com ',
            telefonoContacto: ' 300 123 4567 ',
            saldoActual: 150000.50,
            coeficiente: 12.5000
        );

        $this->assertEquals('Torre 1', $dto->torre);
        $this->assertEquals('101', $dto->nombre);
        $this->assertEquals('1', $dto->piso);
        $this->assertEquals('Apartamento Tipo A', $dto->modelo);
        $this->assertEquals('Juan Pérez', $dto->propietarioNombre);
        $this->assertEquals('12345678', $dto->propietarioIdentificacion);
        $this->assertEquals('juan@ejemplo.com', $dto->emailContacto);
        $this->assertEquals('+573001234567', $dto->telefonoContacto);
        $this->assertEquals(150000.50, $dto->saldoActual);
        $this->assertEquals(12.5000, $dto->coeficiente);
    }

    public function test_template_generator_creates_valid_spreadsheet_with_model_dropdown(): void
    {
        $generator = new SpreadsheetUnitTemplateService();
        $modelos = [
            ['id' => 'm-1', 'nombre' => 'Tipo A', 'area_m2' => 65.0],
            ['id' => 'm-2', 'nombre' => 'Tipo B', 'area_m2' => 85.0],
            ['id' => 'm-3', 'nombre' => 'Penthouse', 'area_m2' => 150.0],
        ];

        $content = $generator->generateBinary('Conjunto Residencial Mirador', $modelos);

        $this->assertNotEmpty($content);
        $this->assertStringStartsWith('PK', $content); // Valid zip / xlsx header
    }

    public function test_import_service_validates_and_parses_matrix_with_deterministic_coefficients(): void
    {
        $modelosCatalog = [
            'Tipo A' => ['id' => 'm-1', 'area_m2' => 50.0],
            'Penthouse' => ['id' => 'm-2', 'area_m2' => 150.0],
        ];

        $rows = [
            [
                'torre' => 'Torre 1',
                'nombre' => '101',
                'piso' => '1',
                'modelo' => 'Tipo A',
                'propietario_nombre' => 'Carlos López',
                'propietario_identificacion' => '71000111',
                'celular' => '3009998877',
                'email' => 'carlos@test.com',
                'saldo' => '0',
                'coeficiente' => null, // Dejado vacío para autocalcular
            ],
            [
                'torre' => 'Torre 1',
                'nombre' => 'PH-01',
                'piso' => '2',
                'modelo' => 'Penthouse',
                'propietario_nombre' => 'María Torres',
                'propietario_identificacion' => '52000222',
                'celular' => '3151112233',
                'email' => 'maria@test.com',
                'saldo' => '50000',
                'coeficiente' => null, // Dejado vacío para autocalcular
            ],
        ];

        $engine = new DeterministicCoefficientEngine();
        $service = new SpreadsheetUnitImportService($engine);

        $result = $service->validateAndProcessRows($rows, $modelosCatalog);

        $this->assertTrue($result->isValid);
        $this->assertCount(2, $result->validRows);
        $this->assertEquals(2, $result->stats['total_units']);
        $this->assertEquals(1, $result->stats['towers_count']);
        $this->assertEquals(100.0000, $result->stats['sum_coeficientes']);
        $this->assertTrue($result->stats['is_100_percent']);

        // 50m2 out of 200m2 total = 25.0000%
        // 150m2 out of 200m2 total = 75.0000%
        $this->assertEquals(25.0000, $result->validRows[0]->coeficiente);
        $this->assertEquals(75.0000, $result->validRows[1]->coeficiente);
    }

    public function test_import_service_rejects_unknown_model_gracefully(): void
    {
        $modelosCatalog = [
            'Tipo A' => ['id' => 'm-1', 'area_m2' => 50.0],
        ];

        $rows = [
            [
                'torre' => 'Torre 1',
                'nombre' => '101',
                'piso' => '1',
                'modelo' => 'Tipo Fantasma Inexistente',
                'propietario_nombre' => 'Carlos',
                'propietario_identificacion' => '71000111',
                'celular' => '3009998877',
                'email' => 'carlos@test.com',
                'saldo' => '0',
                'coeficiente' => null,
            ],
        ];

        $service = new SpreadsheetUnitImportService(new DeterministicCoefficientEngine());
        $result = $service->validateAndProcessRows($rows, $modelosCatalog);

        $this->assertFalse($result->isValid);
        $this->assertNotEmpty($result->errors);
        $this->assertStringContainsString('Tipo Fantasma Inexistente', $result->errors[0]);
    }

    public function test_template_to_import_roundtrip_succeeds(): void
    {
        $generator = new SpreadsheetUnitTemplateService();
        $modelos = [
            ['id' => 'm-1', 'nombre' => 'Apartamento Estándar', 'area_m2' => 60.0],
            ['id' => 'm-2', 'nombre' => 'Apartamento Tipo B', 'area_m2' => 90.0],
        ];

        $binary = $generator->generateBinary('Torres del Prado', $modelos);
        
        $tempFile = tempnam(sys_get_temp_dir(), 'nexo_test_') . '.xlsx';
        file_put_contents($tempFile, $binary);

        $modelosCatalog = [
            'Apartamento Estándar' => ['id' => 'm-1', 'area_m2' => 60.0],
            'Apartamento Tipo B' => ['id' => 'm-2', 'area_m2' => 90.0],
        ];

        $service = new SpreadsheetUnitImportService(new DeterministicCoefficientEngine());
        $result = $service->parseSpreadsheetFile($tempFile, $modelosCatalog);

        @unlink($tempFile);

        $this->assertTrue($result->isValid);
        $this->assertCount(2, $result->validRows); // The two sample rows
        $this->assertEquals('Carlos Mario Restrepo', $result->validRows[0]->propietarioNombre);
        $this->assertEquals('+573001234567', $result->validRows[0]->telefonoContacto);
        $this->assertEquals(100.0000, $result->stats['sum_coeficientes']);
    }
}

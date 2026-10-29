<?php

namespace Tests\Unit\Porteria;

use Tests\TestCase;
use NexoPro\Porteria\Services\ColombianIdBarcodeParser;

/**
 * [TDD - RED -> GREEN -> REFACTOR]
 * Pruebas unitarias para el analizador de código de barras PDF417 de cédulas colombianas:
 * - HU-SEC-02: Escaneo rápido de cédulas colombianas para autocompletar formularios en 0.2 segundos.
 */
class ColombianIdBarcodeParserTest extends TestCase
{
    private ColombianIdBarcodeParser $parser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->parser = new ColombianIdBarcodeParser();
    }

    /**
     * [HU-SEC-02: Happy Path Cédula Holográfica / Digital Estándar]
     * Parsea un payload con dos apellidos y dos nombres.
     */
    public function test_parsear_codigo_barras_cedula_colombiana_formato_pdf417_completo(): void
    {
        // Simulación de payload decodificado de código PDF417 de cédula colombiana
        $rawPayload = "00001020304050GOMEZ         RESTREPO      CARLOS ALBERTO                M19880415050A+";

        $result = $this->parser->parse($rawPayload);

        $this->assertNotNull($result);
        $this->assertEquals('1020304050', $result['documento']);
        $this->assertEquals('GOMEZ', $result['primer_apellido']);
        $this->assertEquals('RESTREPO', $result['segundo_apellido']);
        $this->assertEquals('CARLOS ALBERTO', $result['nombres']);
        $this->assertEquals('CARLOS ALBERTO GOMEZ RESTREPO', $result['nombre_completo']);
        $this->assertEquals('M', $result['genero']);
        $this->assertEquals('1988-04-15', $result['fecha_nacimiento']);
    }

    /**
     * [HU-SEC-02: Edge Case Cédula con Un Solo Apellido]
     * En Colombia algunas personas solo tienen un apellido registrado legalmente.
     */
    public function test_parsear_codigo_con_un_solo_apellido(): void
    {
        $rawPayload = "0000079845123RODRIGUEZ                   JUAN CARLOS                   M19751020050O+";

        $result = $this->parser->parse($rawPayload);

        $this->assertNotNull($result);
        $this->assertEquals('79845123', $result['documento']);
        $this->assertEquals('RODRIGUEZ', $result['primer_apellido']);
        $this->assertNull($result['segundo_apellido']);
        $this->assertEquals('JUAN CARLOS', $result['nombres']);
        $this->assertEquals('JUAN CARLOS RODRIGUEZ', $result['nombre_completo']);
    }

    /**
     * [HU-SEC-02: Formato Delimitado o Limpio por Lector USB]
     * Muchos lectores USB configuran delimitador de tabulación o salto de línea.
     */
    public function test_parsear_codigo_con_delimitadores_de_espacio_o_control(): void
    {
        $rawPayload = "1015482931|CARDONA|SALAZAR|ANDREA SOFIA|F|19950812|O+";

        $result = $this->parser->parse($rawPayload);

        $this->assertNotNull($result);
        $this->assertEquals('1015482931', $result['documento']);
        $this->assertEquals('CARDONA', $result['primer_apellido']);
        $this->assertEquals('SALAZAR', $result['segundo_apellido']);
        $this->assertEquals('ANDREA SOFIA', $result['nombres']);
        $this->assertEquals('ANDREA SOFIA CARDONA SALAZAR', $result['nombre_completo']);
        $this->assertEquals('F', $result['genero']);
        $this->assertEquals('1995-08-12', $result['fecha_nacimiento']);
    }

    /**
     * [HU-SEC-02: Edge Case Payload Malformado]
     * Si el escáner lee un código de barras de un producto o basura aleatoria,
     * el parser no debe romper la aplicación; debe retornar null o resultado no reconocido.
     */
    public function test_parsear_codigo_invalido_retorna_null(): void
    {
        $result = $this->parser->parse("7702001002345"); // Código de barras EAN-13 de supermercado
        $this->assertNull($result);

        $resultEmpty = $this->parser->parse("");
        $this->assertNull($resultEmpty);
    }
}

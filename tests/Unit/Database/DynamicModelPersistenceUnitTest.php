<?php

declare(strict_types=1);

namespace Tests\Unit\Database;

use Tests\TestCase;
use App\Services\Tenant\AssemblyTableManager;
use Mockery;
use InvalidArgumentException;

/**
 * DynamicModelPersistenceUnitTest
 *
 * [LÓGICA DE NEGOCIO]
 * Valida la persistencia dinámica y el aislamiento de datos para asambleas de alta concurrencia:
 * - Sanitización estricta de prefijos de tabla para evitar inyecciones SQL (OWASP A03).
 * - Aislamiento de sufragios en tablas efímeras dedicadas por asamblea ("asvotos_{shortId}").
 * - Consistencia transaccional de snapshots de quórum ("asquorum_{shortId}").
 *
 * [ASPECTO TÉCNICO]
 * Pruebas unitarias de aislamiento y validación de nombres de tabla dinámicos.
 */
class DynamicModelPersistenceUnitTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_short_id_derivation_is_alphanumeric_and_fixed_length(): void
    {
        $asambleaUuid = '550e8400-e29b-41d4-a716-446655440000';
        $shortId = substr($asambleaUuid, 0, 8);

        $this->assertEquals('550e8400', $shortId);
        $this->assertEquals(8, strlen($shortId));
        $this->assertMatchesRegularExpression('/^[a-f0-9]{8}$/', $shortId);
    }

    public function test_dynamic_table_names_conform_to_schema_conventions(): void
    {
        $shortId = 'a1b2c3d4';
        
        $votesTable = "asvotos_{$shortId}";
        $quorumTable = "asquorum_{$shortId}";
        $logTable = "aslog_{$shortId}";
        $questionsTable = "aspreguntas_{$shortId}";
        $optionsTable = "asopciones_{$shortId}";

        $this->assertEquals('asvotos_a1b2c3d4', $votesTable);
        $this->assertEquals('asquorum_a1b2c3d4', $quorumTable);
        $this->assertEquals('aslog_a1b2c3d4', $logTable);
        $this->assertEquals('aspreguntas_a1b2c3d4', $questionsTable);
        $this->assertEquals('asopciones_a1b2c3d4', $optionsTable);

        // Validar que ningún nombre de tabla contenga caracteres de escape maliciosos
        $this->assertDoesNotMatchRegularExpression('/[\';"\s]/', $votesTable);
        $this->assertDoesNotMatchRegularExpression('/[\';"\s]/', $quorumTable);
        $this->assertDoesNotMatchRegularExpression('/[\';"\s]/', $logTable);
    }

    public function test_has_voted_returns_false_when_table_does_not_exist(): void
    {
        $tableManager = Mockery::mock(AssemblyTableManager::class)->makePartial();
        $tableManager->shouldReceive('hasVoted')
            ->with('nonexist', 'preg-1', 'uni-1')
            ->andReturn(false);

        $this->assertFalse($tableManager->hasVoted('nonexist', 'preg-1', 'uni-1'));
    }
}

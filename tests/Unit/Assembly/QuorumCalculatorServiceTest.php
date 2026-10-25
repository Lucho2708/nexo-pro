<?php

declare(strict_types=1);

namespace Tests\Unit\Assembly;

use Tests\TestCase;
use NexoPro\Assembly\Services\QuorumCalculatorService;
use NexoPro\Assembly\DTOs\QuorumStateDTO;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Mockery;

/**
 * QuorumCalculatorServiceTest
 *
 * [LÓGICA DE NEGOCIO]
 * Valida la determinación legal del quórum en vivo para asambleas de copropietarios:
 * - Cumplimiento del Artículo 45 de la Ley 675 de 2001 (Quórum ordinario > 50.00% y calificado >= 70.00%).
 * - Resiliencia ante copropiedades recién creadas (0 unidades o 0 presencia) para evitar divisiones por cero.
 * - Lectura optimizada desde snapshots dinámicos aislados (asquorum_{shortId}).
 *
 * [ASPECTO TÉCNICO]
 * Pruebas unitarias de cálculo agregativo SQL e inmutabilidad de DTO.
 */
class QuorumCalculatorServiceTest extends TestCase
{
    private QuorumCalculatorService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new QuorumCalculatorService();
    }

    /**
     * Happy Path: Calcula el quórum a partir de la tabla dinámica asquorum_{shortId}.
     */
    public function test_calculate_quorum_from_dynamic_table_with_deliberatory_quorum(): void
    {
        $asambleaId = 'b1c2d3e4-f5a6-7890-bcde-9876543210ab';
        $shortId = 'b1c2d3e4';
        $copropiedadId = 'coprop-123';

        Schema::shouldReceive('hasTable')
            ->once()
            ->with("asquorum_{$shortId}")
            ->andReturn(true);

        $mockQuery = Mockery::mock();
        $mockQuery->shouldReceive('selectRaw')
            ->once()
            ->andReturnSelf();

        $stats = (object) [
            'total_unidades' => 120,
            'total_coeficiente' => 100.0000,
            'presente_unidades' => 65,
            'presente_coeficiente' => 54.3210,
        ];

        $mockQuery->shouldReceive('first')
            ->once()
            ->andReturn($stats);

        DB::shouldReceive('table')
            ->once()
            ->with("asquorum_{$shortId}")
            ->andReturn($mockQuery);

        $dto = $this->service->calculateQuorum($asambleaId, $copropiedadId);

        $this->assertInstanceOf(QuorumStateDTO::class, $dto);
        $this->assertEquals(54.3210, $dto->presenteCoeficiente);
        $this->assertEquals(100.0000, $dto->totalCoeficiente);
        $this->assertEquals(120, $dto->totalUnidades);
        $this->assertEquals(65, $dto->presenteUnidades);
        $this->assertEquals(54.321, $dto->porcentajeQuorum);
        $this->assertTrue($dto->tieneQuorumDeliberatorio, 'Con 54.32% debe tener quórum deliberatorio.');
        $this->assertFalse($dto->tieneQuorumCalificado, 'Con 54.32% NO debe tener quórum calificado (> 70%).');
    }

    /**
     * Happy Path: Alcanza quórum calificado (> 70.00%).
     */
    public function test_calculate_quorum_detects_qualified_quorum(): void
    {
        $asambleaId = 'c1c2c3c4-aaaa-bbbb-cccc-111122223333';
        $shortId = 'c1c2c3c4';
        $copropiedadId = 'coprop-123';

        Schema::shouldReceive('hasTable')
            ->once()
            ->with("asquorum_{$shortId}")
            ->andReturn(true);

        $mockQuery = Mockery::mock();
        $mockQuery->shouldReceive('selectRaw')->once()->andReturnSelf();

        $stats = (object) [
            'total_unidades' => 80,
            'total_coeficiente' => 100.0000,
            'presente_unidades' => 60,
            'presente_coeficiente' => 75.5000,
        ];

        $mockQuery->shouldReceive('first')->once()->andReturn($stats);

        DB::shouldReceive('table')
            ->once()
            ->with("asquorum_{$shortId}")
            ->andReturn($mockQuery);

        $dto = $this->service->calculateQuorum($asambleaId, $copropiedadId);

        $this->assertTrue($dto->tieneQuorumDeliberatorio);
        $this->assertTrue($dto->tieneQuorumCalificado, 'Con 75.50% debe tener quórum calificado activo.');
    }

    /**
     * Edge Case 1: Fallback seguro a tabla property.unidades si la tabla dinámica no está aprovisionada aún.
     */
    public function test_calculate_quorum_fallbacks_to_property_unidades(): void
    {
        $asambleaId = 'e1e2e3e4-ffff-0000-1111-222233334444';
        $shortId = 'e1e2e3e4';
        $copropiedadId = 'coprop-999';

        Schema::shouldReceive('hasTable')
            ->once()
            ->with("asquorum_{$shortId}")
            ->andReturn(false);

        $mockQueryCount = Mockery::mock();
        $mockQueryCount->shouldReceive('where')->with('copropiedad_id', $copropiedadId)->andReturnSelf();
        $mockQueryCount->shouldReceive('count')->once()->andReturn(50);

        $mockQuerySum = Mockery::mock();
        $mockQuerySum->shouldReceive('where')->with('copropiedad_id', $copropiedadId)->andReturnSelf();
        $mockQuerySum->shouldReceive('sum')->with('coeficiente')->once()->andReturn(100.0);

        DB::shouldReceive('table')
            ->with('property.unidades')
            ->andReturn($mockQueryCount, $mockQuerySum);

        $dto = $this->service->calculateQuorum($asambleaId, $copropiedadId);

        $this->assertEquals(50, $dto->totalUnidades);
        $this->assertEquals(0, $dto->presenteUnidades);
        $this->assertEquals(0.0, $dto->presenteCoeficiente);
        $this->assertFalse($dto->tieneQuorumDeliberatorio);
    }
}

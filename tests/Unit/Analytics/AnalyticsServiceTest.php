<?php

namespace Tests\Unit\Analytics;

use Tests\TestCase;
use App\Services\Analytics\AnalyticsService;
use App\Repositories\Interfaces\AnalyticsRepositoryInterface;
use Mockery;

class AnalyticsServiceTest extends TestCase
{
    private $repositoryMock;
    private AnalyticsService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repositoryMock = Mockery::mock(AnalyticsRepositoryInterface::class);
        $this->service = new AnalyticsService($this->repositoryMock);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_get_admin_dashboard_stats_computes_proportions_and_includes_navigation_links(): void
    {
        $copropiedadId = 'test-copropiedad-uuid-001';

        $this->repositoryMock->shouldReceive('getBilledAmount')
            ->once()
            ->andReturn(10000000.0);

        $this->repositoryMock->shouldReceive('getCollectedAmount')
            ->once()
            ->andReturn(8500000.0);

        $this->repositoryMock->shouldReceive('getTotalUnitsCount')
            ->once()
            ->with($copropiedadId)
            ->andReturn(100);

        $this->repositoryMock->shouldReceive('getUnitsInDebtCount')
            ->once()
            ->with($copropiedadId)
            ->andReturn(15);

        $this->repositoryMock->shouldReceive('getOpenPqrsCount')
            ->once()
            ->with($copropiedadId)
            ->andReturn(3);

        $this->repositoryMock->shouldReceive('getUpcomingReservationsCount')
            ->once()
            ->andReturn(7);

        $stats = $this->service->getAdminDashboardStats($copropiedadId);

        $this->assertCount(4, $stats);

        // 1. Recaudo
        $this->assertEquals('% Recaudo del mes', $stats[0]['label']);
        $this->assertEquals('85%', $stats[0]['value']);
        $this->assertTrue($stats[0]['trend_up']);
        $this->assertStringContainsString('cartera', $stats[0]['href']);

        // 2. Morosidad
        $this->assertEquals('Morosidad', $stats[1]['label']);
        $this->assertEquals('15%', $stats[1]['value']);
        $this->assertFalse($stats[1]['trend_up']);
        $this->assertEquals('15 unidades en mora', $stats[1]['subtext']);

        // 3. PQRS
        $this->assertEquals('PQRS abiertas', $stats[2]['label']);
        $this->assertEquals('3', $stats[2]['value']);
        $this->assertStringContainsString('pqrs', $stats[2]['href']);

        // 4. Reservas
        $this->assertEquals('Reservas 7d', $stats[3]['label']);
        $this->assertEquals('7', $stats[3]['value']);
        $this->assertStringContainsString('reservas', $stats[3]['href']);
    }

    public function test_clear_cache_delegates_to_repository(): void
    {
        $copropiedadId = 'test-copropiedad-uuid-002';

        $this->repositoryMock->shouldReceive('clearCache')
            ->once()
            ->with($copropiedadId);

        $this->service->clearCache($copropiedadId);
        $this->assertTrue(true);
    }

    public function test_get_chart_data_formats_six_months_history(): void
    {
        $copropiedadId = 'test-copropiedad-uuid-003';

        $this->repositoryMock->shouldReceive('getCollectedAmount')
            ->times(6)
            ->andReturn(5000000.0);

        $this->repositoryMock->shouldReceive('getBilledAmount')
            ->times(6)
            ->andReturn(6000000.0);

        $chart = $this->service->getChartData($copropiedadId);

        $this->assertArrayHasKey('categories', $chart);
        $this->assertArrayHasKey('series', $chart);
        $this->assertCount(6, $chart['categories']);
        $this->assertCount(2, $chart['series']);
        $this->assertEquals(5000, $chart['series'][0]['data'][0]);
        $this->assertEquals(6000, $chart['series'][1]['data'][0]);
    }
}

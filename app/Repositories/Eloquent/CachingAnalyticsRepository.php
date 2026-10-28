<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Interfaces\AnalyticsRepositoryInterface;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class CachingAnalyticsRepository implements AnalyticsRepositoryInterface
{
    private const CACHE_TTL = 120; // 2 minutos para datos vivos y frescos

    public function __construct(
        private AnalyticsRepositoryInterface $baseRepository
    ) {}

    public function getBilledAmount(string $copropiedadId, int $month, int $year): float
    {
        return Cache::remember(
            "billing_{$copropiedadId}_{$month}_{$year}",
            self::CACHE_TTL,
            fn() => $this->baseRepository->getBilledAmount($copropiedadId, $month, $year)
        );
    }

    public function getCollectedAmount(string $copropiedadId, int $month, int $year): float
    {
        return Cache::remember(
            "collected_{$copropiedadId}_{$month}_{$year}",
            self::CACHE_TTL,
            fn() => $this->baseRepository->getCollectedAmount($copropiedadId, $month, $year)
        );
    }

    public function getTotalUnitsCount(string $copropiedadId): int
    {
        return Cache::remember(
            "total_units_{$copropiedadId}",
            self::CACHE_TTL,
            fn() => $this->baseRepository->getTotalUnitsCount($copropiedadId)
        );
    }

    public function getUnitsInDebtCount(string $copropiedadId): int
    {
        return Cache::remember(
            "units_in_debt_{$copropiedadId}",
            60, // 60 segundos para reflejar pagos
            fn() => $this->baseRepository->getUnitsInDebtCount($copropiedadId)
        );
    }

    public function getOpenPqrsCount(string $copropiedadId): int
    {
        return Cache::remember(
            "open_pqrs_{$copropiedadId}",
            30, // 30 segundos para requerimientos
            fn() => $this->baseRepository->getOpenPqrsCount($copropiedadId)
        );
    }

    public function getUpcomingReservationsCount(string $copropiedadId, string $startDate, string $endDate): int
    {
        return Cache::remember(
            "upcoming_res_{$copropiedadId}_{$startDate}_{$endDate}",
            60,
            fn() => $this->baseRepository->getUpcomingReservationsCount($copropiedadId, $startDate, $endDate)
        );
    }

    public function getTopOverdueUnits(string $copropiedadId, int $limit = 5): array
    {
        return Cache::remember(
            "top_overdue_{$copropiedadId}_{$limit}",
            60,
            fn() => $this->baseRepository->getTopOverdueUnits($copropiedadId, $limit)
        );
    }

    public function clearCache(string $copropiedadId): void
    {
        $now = Carbon::now();
        Cache::forget("billing_{$copropiedadId}_{$now->month}_{$now->year}");
        Cache::forget("collected_{$copropiedadId}_{$now->month}_{$now->year}");
        Cache::forget("total_units_{$copropiedadId}");
        Cache::forget("units_in_debt_{$copropiedadId}");
        Cache::forget("open_pqrs_{$copropiedadId}");
        Cache::forget("top_overdue_{$copropiedadId}_5");

        for ($i = 0; $i <= 5; $i++) {
            $d = Carbon::now()->subMonths($i);
            Cache::forget("billing_{$copropiedadId}_{$d->month}_{$d->year}");
            Cache::forget("collected_{$copropiedadId}_{$d->month}_{$d->year}");
        }

        Cache::forget("upcoming_res_{$copropiedadId}_{$now->toDateString()}_" . $now->copy()->addDays(7)->toDateString());

        $this->baseRepository->clearCache($copropiedadId);
    }
}

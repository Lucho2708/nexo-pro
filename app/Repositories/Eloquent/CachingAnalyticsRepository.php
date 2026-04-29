<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Interfaces\AnalyticsRepositoryInterface;
use Illuminate\Support\Facades\Cache;

class CachingAnalyticsRepository implements AnalyticsRepositoryInterface
{
    private const CACHE_TTL = 3600; // 1 hora de caché por defecto

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
            600, // 10 minutos para datos más volátiles
            fn() => $this->baseRepository->getUnitsInDebtCount($copropiedadId)
        );
    }

    public function getOpenPqrsCount(string $copropiedadId): int
    {
        return Cache::remember(
            "open_pqrs_{$copropiedadId}",
            300, // 5 minutos
            fn() => $this->baseRepository->getOpenPqrsCount($copropiedadId)
        );
    }

    public function getUpcomingReservationsCount(string $copropiedadId, string $startDate, string $endDate): int
    {
        return Cache::remember(
            "upcoming_res_{$copropiedadId}_{$startDate}_{$endDate}",
            600,
            fn() => $this->baseRepository->getUpcomingReservationsCount($copropiedadId, $startDate, $endDate)
        );
    }

    public function getTopOverdueUnits(string $copropiedadId, int $limit = 5): array
    {
        return Cache::remember(
            "top_overdue_{$copropiedadId}_{$limit}",
            600,
            fn() => $this->baseRepository->getTopOverdueUnits($copropiedadId, $limit)
        );
    }
}

<?php

namespace App\Repositories\Interfaces;

interface AnalyticsRepositoryInterface
{
    public function getBilledAmount(string $copropiedadId, int $month, int $year): float;
    
    public function getCollectedAmount(string $copropiedadId, int $month, int $year): float;
    
    public function getTotalUnitsCount(string $copropiedadId): int;
    
    public function getUnitsInDebtCount(string $copropiedadId): int;
    
    public function getOpenPqrsCount(string $copropiedadId): int;
    
    public function getUpcomingReservationsCount(string $copropiedadId, string $startDate, string $endDate): int;
    
    public function getTopOverdueUnits(string $copropiedadId, int $limit = 5): array;
}

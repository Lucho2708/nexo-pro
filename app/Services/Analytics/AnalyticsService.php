<?php

namespace App\Services\Analytics;

use App\Repositories\Interfaces\AnalyticsRepositoryInterface;
use Carbon\Carbon;

class AnalyticsService
{
    public function __construct(
        private AnalyticsRepositoryInterface $repository
    ) {}

    public function getAdminDashboardStats(string $copropiedadId): array
    {
        $now = Carbon::now();
        
        // 1. % Recaudo
        $billed = $this->repository->getBilledAmount($copropiedadId, $now->month, $now->year);
        $collected = $this->repository->getCollectedAmount($copropiedadId, $now->month, $now->year);
        $recaudoPercentage = $billed > 0 ? round(($collected / $billed) * 100) : 0;

        // 2. Morosidad
        $totalUnits = $this->repository->getTotalUnitsCount($copropiedadId);
        $unitsInDebt = $this->repository->getUnitsInDebtCount($copropiedadId);
        $morosidadPercentage = $totalUnits > 0 ? round(($unitsInDebt / $totalUnits) * 100) : 0;

        // 3. PQRS
        $openPqrs = $this->repository->getOpenPqrsCount($copropiedadId);

        // 4. Reservas
        $upcomingReservas = $this->repository->getUpcomingReservationsCount(
            $copropiedadId, 
            $now->toDateString(), 
            $now->copy()->addDays(7)->toDateString()
        );

        return [
            [
                'label' => '% Recaudo del mes',
                'value' => $recaudoPercentage . '%',
                'trend' => 'Este mes',
                'trend_up' => true,
                'progress' => $recaudoPercentage,
                'color' => 'bg-secondary',
                'accent_color' => '#0F7B6E',
            ],
            [
                'label' => 'Morosidad',
                'value' => $morosidadPercentage . '%',
                'trend' => 'Global',
                'trend_up' => false,
                'subtext' => $unitsInDebt . ' unidades en mora',
                'color' => 'bg-[#FF6B4A]',
                'accent_color' => '#FF6B4A',
            ],
            [
                'label' => 'PQRS abiertas',
                'value' => (string)$openPqrs,
                'subtext' => 'Pendientes por responder',
                'color' => 'bg-primary-container',
                'accent_color' => '#0A2B5E',
            ],
            [
                'label' => 'Reservas 7d',
                'value' => (string)$upcomingReservas,
                'subtext' => 'En áreas comunes',
                'color' => 'bg-secondary-fixed-dim',
                'accent_color' => '#7cd6c7',
            ],
        ];
    }

    public function getChartData(string $copropiedadId): array
    {
        $categories = [];
        $incomeData = [];
        $expenseData = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $categories[] = $date->shortMonthName;

            $income = $this->repository->getCollectedAmount($copropiedadId, $date->month, $date->year);
            $billing = $this->repository->getBilledAmount($copropiedadId, $date->month, $date->year);

            $incomeData[] = (int)($income / 1000);
            $expenseData[] = (int)($billing / 1000);
        }

        return [
            'categories' => $categories,
            'series' => [
                ['name' => 'Recaudado (k)', 'data' => $incomeData, 'color' => '#0F7B6E'],
                ['name' => 'Facturado (k)', 'data' => $expenseData, 'color' => '#FF6B4A'],
            ],
        ];
    }

    public function getOverduePayments(string $copropiedadId): array
    {
        $units = $this->repository->getTopOverdueUnits($copropiedadId);

        return array_map(function($unit) {
            return [
                'unit' => ($unit['torre'] ? $unit['torre'] . '-' : '') . $unit['nombre'],
                'owner' => $unit['propietario_nombre'] ?? 'Sin asignar',
                'initials' => collect(explode(' ', $unit['propietario_nombre'] ?? 'U'))->map(fn($n) => mb_substr($n, 0, 1))->take(2)->join(''),
                'amount' => (float)$unit['saldo_actual'],
                'last_payment' => 'Consultar', // Simplificado para el ejemplo
                'status' => $unit['saldo_actual'] > 500000 ? 'Mora > 30 días' : 'Pendiente',
                'status_type' => $unit['saldo_actual'] > 500000 ? 'error' : 'warning',
            ];
        }, $units);
    }
}

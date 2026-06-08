<?php

namespace App\Repositories\Eloquent;

use App\Modules\Operations\Models\Pqrs;
use App\Modules\Operations\Models\Reserva;
use App\Modules\Finance\Models\Transaccion;
use App\Modules\Property\Models\Unidad;
use App\Repositories\Interfaces\AnalyticsRepositoryInterface;

class EloquentAnalyticsRepository implements AnalyticsRepositoryInterface
{
    public function getBilledAmount(string $copropiedadId, int $month, int $year): float
    {
        return (float) Transaccion::whereHas('unidad', function ($query) use ($copropiedadId) {
                $query->where('copropiedad_id', $copropiedadId);
            })
            ->where('tipo', 'cargo')
            ->whereMonth('fecha', $month)
            ->whereYear('fecha', $year)
            ->sum('monto');
    }

    public function getCollectedAmount(string $copropiedadId, int $month, int $year): float
    {
        return (float) Transaccion::whereHas('unidad', function ($query) use ($copropiedadId) {
                $query->where('copropiedad_id', $copropiedadId);
            })
            ->where('tipo', 'abono')
            ->whereMonth('fecha', $month)
            ->whereYear('fecha', $year)
            ->sum('monto');
    }

    public function getTotalUnitsCount(string $copropiedadId): int
    {
        return Unidad::where('copropiedad_id', $copropiedadId)->count();
    }

    public function getUnitsInDebtCount(string $copropiedadId): int
    {
        return Unidad::where('copropiedad_id', $copropiedadId)->where('saldo_actual', '>', 0)->count();
    }

    public function getOpenPqrsCount(string $copropiedadId): int
    {
        return Pqrs::whereHas('unidad', function($q) use ($copropiedadId) {
                $q->where('copropiedad_id', $copropiedadId);
            })
            ->where('estado', '!=', 'cerrado')
            ->count();
    }

    public function getUpcomingReservationsCount(string $copropiedadId, string $startDate, string $endDate): int
    {
        return Reserva::whereHas('unidad', function($q) use ($copropiedadId) {
                $q->where('copropiedad_id', $copropiedadId);
            })
            ->where('fecha', '>=', $startDate)
            ->where('fecha', '<=', $endDate)
            ->count();
    }

    public function getTopOverdueUnits(string $copropiedadId, int $limit = 5): array
    {
        return Unidad::where('copropiedad_id', $copropiedadId)
            ->where('saldo_actual', '>', 0)
            ->orderByDesc('saldo_actual')
            ->take($limit)
            ->get()
            ->toArray();
    }
}

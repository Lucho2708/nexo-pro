<?php

namespace App\Repositories\Eloquent;

use App\Models\Reserva;
use App\Repositories\Interfaces\ReservationRepositoryInterface;
use Illuminate\Support\Collection;

class EloquentReservationRepository implements ReservationRepositoryInterface
{
    public function findById(string $id): ?Reserva
    {
        return Reserva::with(['zona', 'user', 'unidad'])->find($id);
    }

    public function getByCopropiedad(string $copropiedadId): Collection
    {
        return Reserva::whereHas('zona', function($q) use ($copropiedadId) {
                $q->where('copropiedad_id', $copropiedadId);
            })
            ->with(['zona', 'user', 'unidad'])
            ->orderByDesc('fecha')
            ->get();
    }

    public function getByUser(string $userId): Collection
    {
        return Reserva::where('user_id', $userId)
            ->with(['zona', 'user', 'unidad'])
            ->orderByDesc('fecha')
            ->get();
    }

    public function create(array $data): Reserva
    {
        return Reserva::create($data);
    }

    public function update(string $id, array $data): bool
    {
        $reserva = Reserva::findOrFail($id);
        return $reserva->update($data);
    }

    public function checkOverlap(string $zonaId, string $fecha, string $inicio, string $fin, ?string $excludeId = null): bool
    {
        return Reserva::where('zona_id', $zonaId)
            ->whereDate('fecha', $fecha)
            ->whereIn('estado', ['pendiente', 'aprobada', 'pagada'])
            ->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))
            ->where(function ($query) use ($inicio, $fin) {
                $query->where('hora_inicio', '<', $fin)
                      ->where('hora_fin', '>', $inicio);
            })->exists();
    }

    public function countByUnitAndMonth(string $unidadId, string $zonaId, string $year, string $month): int
    {
        return Reserva::where('unidad_id', $unidadId)
            ->where('zona_id', $zonaId)
            ->whereYear('fecha', $year)
            ->whereMonth('fecha', $month)
            ->whereIn('estado', ['pendiente', 'aprobada', 'pagada'])
            ->count();
    }
}

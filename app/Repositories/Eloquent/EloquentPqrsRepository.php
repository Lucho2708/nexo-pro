<?php

namespace App\Repositories\Eloquent;

use App\Modules\Operations\Models\Pqrs;
use App\Repositories\Interfaces\PqrsRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class EloquentPqrsRepository implements PqrsRepositoryInterface
{
    public function findById(string $id): ?Pqrs
    {
        return Pqrs::with(['unidad', 'user'])->find($id);
    }

    public function getByCopropiedad(string $copropiedadId): Collection
    {
        return Pqrs::where(function($q) use ($copropiedadId) {
                $q->where('copropiedad_id', $copropiedadId)
                  ->orWhereHas('unidad', fn($u) => $u->where('copropiedad_id', $copropiedadId));
            })
            ->with(['unidad', 'user'])
            ->orderByDesc('created_at')
            ->get();
    }

    public function getByUser(string $userId): Collection
    {
        return Pqrs::where('user_id', $userId)
            ->with(['unidad', 'user'])
            ->orderByDesc('created_at')
            ->get();
    }

    public function create(array $data): Pqrs
    {
        return Pqrs::create($data);
    }

    public function update(string $id, array $data): bool
    {
        $pqrs = Pqrs::findOrFail($id);
        return $pqrs->update($data);
    }

    public function getCountsByStatus(string $copropiedadId): array
    {
        return Pqrs::where(function($q) use ($copropiedadId) {
                $q->where('copropiedad_id', $copropiedadId)
                  ->orWhereHas('unidad', fn($u) => $u->where('copropiedad_id', $copropiedadId));
            })
            ->select('estado', DB::raw('count(*) as total'))
            ->groupBy('estado')
            ->pluck('total', 'estado')
            ->toArray();
    }

    public function getUrgentCount(string $copropiedadId): int
    {
        return Pqrs::where(function($q) use ($copropiedadId) {
                $q->where('copropiedad_id', $copropiedadId)
                  ->orWhereHas('unidad', fn($u) => $u->where('copropiedad_id', $copropiedadId));
            })
            ->whereIn('estado', ['abierto', 'reabierto'])
            ->where('prioridad', 'alta')
            ->count();
    }
}

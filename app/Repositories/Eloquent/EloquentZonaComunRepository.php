<?php

namespace App\Repositories\Eloquent;

use App\Modules\Property\Models\ZonaComun;
use App\Repositories\Interfaces\ZonaComunRepositoryInterface;
use Illuminate\Support\Collection;

class EloquentZonaComunRepository implements ZonaComunRepositoryInterface
{
    public function getByCopropiedad(string $copropiedadId): Collection
    {
        return ZonaComun::where('copropiedad_id', $copropiedadId)
            ->orderBy('nombre')
            ->get();
    }

    public function getActiveByCopropiedad(string $copropiedadId): Collection
    {
        return ZonaComun::where('copropiedad_id', $copropiedadId)
            ->where('activa', true)
            ->orderBy('nombre')
            ->get();
    }

    public function findById(string $id): ?ZonaComun
    {
        return ZonaComun::find($id);
    }

    public function create(array $data): ZonaComun
    {
        return ZonaComun::create($data);
    }

    public function update(string $id, array $data): bool
    {
        $zona = ZonaComun::findOrFail($id);
        return $zona->update($data);
    }

    public function delete(string $id): bool
    {
        $zona = ZonaComun::findOrFail($id);
        return $zona->delete();
    }
}

<?php

namespace App\Repositories\Interfaces;

use App\Models\ZonaComun;
use Illuminate\Support\Collection;

interface ZonaComunRepositoryInterface
{
    public function getByCopropiedad(string $copropiedadId): Collection;
    
    public function getActiveByCopropiedad(string $copropiedadId): Collection;
    
    public function findById(string $id): ?ZonaComun;
    
    public function create(array $data): ZonaComun;
    
    public function update(string $id, array $data): bool;
    
    public function delete(string $id): bool;
}

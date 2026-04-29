<?php

namespace App\Repositories\Interfaces;

use App\Models\Pqrs;
use Illuminate\Support\Collection;

interface PqrsRepositoryInterface
{
    public function findById(string $id): ?Pqrs;
    
    public function getByCopropiedad(string $copropiedadId): Collection;
    
    public function getByUser(string $userId): Collection;
    
    public function create(array $data): Pqrs;
    
    public function update(string $id, array $data): bool;
    
    public function getCountsByStatus(string $copropiedadId): array;
}

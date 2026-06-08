<?php

namespace App\Repositories\Interfaces;

use App\Modules\Operations\Models\Reserva;
use Illuminate\Support\Collection;

interface ReservationRepositoryInterface
{
    public function findById(string $id): ?Reserva;
    
    public function getByCopropiedad(string $copropiedadId): Collection;
    
    public function getByUser(string $userId): Collection;
    
    public function create(array $data): Reserva;
    
    public function update(string $id, array $data): bool;
    
    public function checkOverlap(string $zonaId, string $fecha, string $inicio, string $fin, ?string $excludeId = null): bool;

    public function countByUnitAndMonth(string $unidadId, string $zonaId, string $year, string $month): int;
}

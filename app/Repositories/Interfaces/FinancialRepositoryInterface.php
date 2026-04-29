<?php

namespace App\Repositories\Interfaces;

use App\DTOs\TransactionDataDTO;
use App\Models\Transaccion;
use App\Models\Unidad;
use App\Models\Copropiedad;
use App\Models\ConceptoCobro;

interface FinancialRepositoryInterface
{
    public function createTransaction(TransactionDataDTO $data): Transaccion;
    
    public function updateUnitBalance(string $unidadId, float $amount, string $type): bool;
    
    public function getUnidad(string $id): Unidad;

    public function getUnitsByCopropiedad(string $copropiedadId): \Illuminate\Support\Collection;

    public function getUnitsWithPaginationAndFilters(string $copropiedadId, array $filters, int $perPage = 12): \Illuminate\Contracts\Pagination\LengthAwarePaginator;

    public function getConceptosByCopropiedad(string $copropiedadId): \Illuminate\Support\Collection;

    public function getCarteraStats(string $copropiedadId): array;

    public function getTorresByCopropiedad(string $copropiedadId): \Illuminate\Support\Collection;

    public function getCopropiedad(string $id): Copropiedad;

    public function getConceptByCode(string $copropiedadId, string $code): ?ConceptoCobro;
}

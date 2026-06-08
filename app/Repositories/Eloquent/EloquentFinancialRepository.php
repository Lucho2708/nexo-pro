<?php

namespace App\Repositories\Eloquent;

use App\DTOs\TransactionDataDTO;
use App\Modules\Finance\Models\Transaccion;
use App\Modules\Property\Models\Unidad;
use App\Modules\Property\Models\Copropiedad;
use App\Modules\Finance\Models\ConceptoCobro;
use App\Repositories\Interfaces\FinancialRepositoryInterface;

class EloquentFinancialRepository implements FinancialRepositoryInterface
{
    public function createTransaction(TransactionDataDTO $data): Transaccion
    {
        return Transaccion::create([
            'unidad_id'   => $data->unidadId,
            'concepto_id' => $data->conceptoId,
            'tipo'        => $data->tipo,
            'monto'       => $data->monto,
            'fecha'       => $data->fecha,
            'referencia'  => $data->referencia,
            'soporte_path'=> $data->soportePath,
            'user_id'     => $data->userId,
        ]);
    }

    public function updateUnitBalance(string $unidadId, float $amount, string $type): bool
    {
        $unidad = $this->getUnidad($unidadId);
        
        // Si es abono (pago), disminuye el saldo
        if ($type === 'abono') {
            return $unidad->decrement('saldo_actual', $amount) > 0;
        }
        
        // Si es cargo (deuda), aumenta el saldo
        return $unidad->increment('saldo_actual', $amount) > 0;
    }

    public function getUnidad(string $id): Unidad
    {
        return Unidad::findOrFail($id);
    }

    public function getUnitsByCopropiedad(string $copropiedadId): \Illuminate\Support\Collection
    {
        return Unidad::where('copropiedad_id', $copropiedadId)->get();
    }

    public function getUnitsWithPaginationAndFilters(string $copropiedadId, array $filters, int $perPage = 12): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return Unidad::where('copropiedad_id', $copropiedadId)
            ->when($filters['search'] ?? null, function($q, $search) {
                $q->where(function($query) use ($search) {
                    $query->where('nombre', 'like', "%{$search}%")
                        ->orWhere('propietario_nombre', 'like', "%{$search}%");
                });
            })
            ->when($filters['torre'] ?? null, fn($q, $torre) => $q->where('torre', $torre))
            ->orderBy('torre')
            ->orderBy('nombre')
            ->paginate($perPage)
            ->withQueryString();
    }

    public function getConceptosByCopropiedad(string $copropiedadId): \Illuminate\Support\Collection
    {
        return ConceptoCobro::where('copropiedad_id', $copropiedadId)->get();
    }

    public function getCarteraStats(string $copropiedadId): array
    {
        return [
            'total_recaudado' => 142580000, // Mock for visual demo
            'cartera_vencida' => Unidad::where('copropiedad_id', $copropiedadId)->sum('saldo_actual'),
            'porcentaje_recaudo' => 82.4,
            'unidades_mora' => Unidad::where('copropiedad_id', $copropiedadId)->where('saldo_actual', '>', 0)->count()
        ];
    }

    public function getTorresByCopropiedad(string $copropiedadId): \Illuminate\Support\Collection
    {
        return Unidad::where('copropiedad_id', $copropiedadId)
            ->distinct()
            ->pluck('torre')
            ->filter();
    }

    public function getCopropiedad(string $id): Copropiedad
    {
        return Copropiedad::findOrFail($id);
    }

    public function getConceptByCode(string $copropiedadId, string $code): ?ConceptoCobro
    {
        return ConceptoCobro::where('copropiedad_id', $copropiedadId)
            ->where('codigo', $code)
            ->first();
    }
}

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
        $unidad = $this->getUnidad($data->unidadId);

        return Transaccion::create([
            'copropiedad_id' => $unidad->copropiedad_id,
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
        $unidades = Unidad::where('copropiedad_id', $copropiedadId)->get();
        $totalUnidades = $unidades->count();
        $unitIds = $unidades->pluck('id');

        $carteraVencida = (float) $unidades->where('saldo_actual', '>', 0)->sum('saldo_actual');
        $unidadesMora = $unidades->where('saldo_actual', '>', 0)->count();

        // Total facturado del mes en curso para las unidades del conjunto
        $totalFacturadoMes = (float) Transaccion::whereIn('unidad_id', $unitIds)
            ->where('tipo', 'cargo')
            ->whereMonth('fecha', now()->month)
            ->whereYear('fecha', now()->year)
            ->sum('monto');

        // Total recaudado (abonos) del mes en curso
        $totalRecaudadoMes = (float) Transaccion::whereIn('unidad_id', $unitIds)
            ->where('tipo', 'abono')
            ->whereMonth('fecha', now()->month)
            ->whereYear('fecha', now()->year)
            ->sum('monto');

        // Si no hay facturas este mes aún, calcular el recaudo total histórico
        $totalRecaudado = $totalRecaudadoMes > 0 
            ? $totalRecaudadoMes 
            : (float) Transaccion::whereIn('unidad_id', $unitIds)->where('tipo', 'abono')->sum('monto');

        // Cálculo de porcentaje de recaudo e índice de solvencia real
        if ($totalFacturadoMes > 0) {
            $porcentajeRecaudo = round(($totalRecaudadoMes / $totalFacturadoMes) * 100, 1);
        } else {
            // Si no hay cobros en el mes, la solvencia se calcula según mora actual
            $porcentajeRecaudo = $totalUnidades > 0 
                ? round((($totalUnidades - $unidadesMora) / $totalUnidades) * 100, 1) 
                : 100.0;
        }

        return [
            'total_recaudado'    => $totalRecaudado,
            'cartera_vencida'    => $carteraVencida,
            'porcentaje_recaudo' => min(100.0, max(0.0, $porcentajeRecaudo)),
            'unidades_mora'      => $unidadesMora,
            'total_unidades'     => $totalUnidades,
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

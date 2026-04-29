<?php

namespace App\Actions\Cartera;

use App\Models\Unidad;
use App\Models\Transaccion;
use Illuminate\Support\Facades\DB;

class ProcessPaymentAction
{
    /**
     * Process a manual payment for a unit.
     */
    public function execute(
        string $unidadId,
        string $conceptoId,
        float $monto,
        string $fecha,
        ?string $referencia = null,
        ?string $soportePath = null
    ): Transaccion {
        return DB::transaction(function () use ($unidadId, $conceptoId, $monto, $fecha, $referencia, $soportePath) {
            $unidad = Unidad::findOrFail($unidadId);

            // 1. Create the Transaction
            $transaccion = Transaccion::create([
                'unidad_id' => $unidadId,
                'concepto_id' => $conceptoId,
                'tipo' => 'abono',
                'monto' => $monto,
                'fecha' => $fecha,
                'referencia' => $referencia,
                'soporte_path' => $soportePath,
            ]);

            // 2. Update Unit Balance (Abono reduces balance)
            $unidad->decrement('saldo_actual', $monto);

            return $transaccion;
        });
    }
}

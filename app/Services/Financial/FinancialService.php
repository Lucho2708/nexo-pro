<?php

namespace App\Services\Financial;

use App\DTOs\TransactionDataDTO;
use App\Models\Transaccion;
use App\Events\PaymentRegistered;
use App\Repositories\Interfaces\FinancialRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FinancialService
{
    public function __construct(
        private FinancialRepositoryInterface $repository
    ) {}

    /**
     * Registra una transacción y actualiza el saldo de la unidad de forma atómica.
     */
    public function registerTransaction(TransactionDataDTO $data): Transaccion
    {
        return DB::transaction(function () use ($data) {
            try {
                // 1. Registrar la transacción
                $transaction = $this->repository->createTransaction($data);

                // 2. Actualizar el saldo de la unidad
                $this->repository->updateUnitBalance($data->unidadId, $data->monto, $data->tipo);

                event(new PaymentRegistered($transaction));

                return $transaction;
            } catch (\Exception $e) {
                Log::error("Error registrando transacción financiera: " . $e->getMessage());
                throw $e;
            }
        });
    }
}

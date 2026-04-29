<?php

namespace App\Services\Financial;

use App\DTOs\TransactionDataDTO;
use App\Repositories\Interfaces\FinancialRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BillingService
{
    public function __construct(
        private FinancialRepositoryInterface $financialRepository
    ) {}

    /**
     * Generar cobro mensual de administración para todas las unidades.
     */
    public function generateMonthlyBilling(string $copropiedadId, float $amount): void
    {
        $units = $this->financialRepository->getUnitsByCopropiedad($copropiedadId);
        $concept = $this->financialRepository->getConceptByCode($copropiedadId, 'ADM');

        if (!$concept) {
            throw new \Exception("Concepto de Administración (ADM) no encontrado para esta copropiedad.");
        }

        DB::transaction(function () use ($units, $concept, $amount) {
            foreach ($units as $unit) {
                $dto = new TransactionDataDTO(
                    unidadId: $unit->id,
                    conceptoId: $concept->id,
                    monto: $amount,
                    tipo: 'cargo',
                    fecha: Carbon::now()->format('Y-m-d'),
                    referencia: 'FACTURACION_' . Carbon::now()->format('Y_m')
                );

                $this->financialRepository->createTransaction($dto);
                $this->financialRepository->updateUnitBalance($unit->id, $amount, 'cargo');
            }
        });
    }

    /**
     * Aplicar intereses de mora a las unidades con saldo pendiente.
     */
    public function applyLateInterests(string $copropiedadId): void
    {
        $copropiedad = $this->financialRepository->getCopropiedad($copropiedadId);
        $units = $this->financialRepository->getUnitsByCopropiedad($copropiedadId);
        $concept = $this->financialRepository->getConceptByCode($copropiedadId, 'INT');
        
        $interestRate = ($copropiedad->settings['interes_mora'] ?? 1.5) / 100;

        if (!$concept) {
            throw new \Exception("Concepto de Intereses (INT) no encontrado.");
        }

        DB::transaction(function () use ($units, $concept, $interestRate) {
            foreach ($units as $unit) {
                if ($unit->saldo_actual > 0) {
                    $interestAmount = $unit->saldo_actual * $interestRate;

                    if ($interestAmount > 0) {
                        $dto = new TransactionDataDTO(
                            unidadId: $unit->id,
                            conceptoId: $concept->id,
                            monto: $interestAmount,
                            tipo: 'cargo',
                            fecha: Carbon::now()->format('Y-m-d'),
                            referencia: 'INTERES_MORA'
                        );

                        $this->financialRepository->createTransaction($dto);
                        $this->financialRepository->updateUnitBalance($unit->id, $interestAmount, 'cargo');
                    }
                }
            }
        });
    }
}

<?php

namespace App\Jobs\Financial;

use App\Services\Financial\BillingService;
use Illuminate\Bus\Queue\SerializesModels;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels as QueueSerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class GenerateMonthlyBillingJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, QueueSerializesModels;

    public function __construct(
        private string $copropiedadId,
        private float $amount
    ) {}

    public function handle(BillingService $billingService): void
    {
        // 1. Aplicar intereses de mora primero (sobre el saldo anterior)
        $billingService->applyLateInterests($this->copropiedadId);

        // 2. Generar el cobro de la nueva administración
        $billingService->generateMonthlyBilling($this->copropiedadId, $this->amount);
    }
}

<?php

namespace App\Listeners;

use App\Events\PaymentRegistered;
use App\Modules\Operations\Models\FeatureUsageLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class AuditPaymentListener implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     */
    public function handle(PaymentRegistered $event): void
    {
        FeatureUsageLog::create([
            'copropiedad_id' => $event->transaccion->unidad->copropiedad_id,
            'user_id' => $event->transaccion->user_id,
            'feature' => 'pagos',
            'used_at' => now(),
        ]);
    }
}

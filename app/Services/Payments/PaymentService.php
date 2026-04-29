<?php

namespace App\Services\Payments;

use App\Models\Copropiedad;
use App\Models\Transaccion;
use App\Services\Payments\Providers\WompiProvider;
use App\Services\Payments\Providers\PayUProvider;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    /**
     * Get the configured provider for a copropiedad.
     */
    public function getProvider(Copropiedad $copropiedad): PaymentProviderInterface
    {
        $gateway = $copropiedad->getSetting('payment_gateway', 'wompi');
        
        return match ($gateway) {
            'payu' => new PayUProvider(),
            default => new WompiProvider(),
        };
    }

    /**
     * Prepare a payment session for a unit bill.
     */
    public function preparePayment(Transaccion $transaction): ?array
    {
        $copropiedad = $transaction->unidad->copropiedad;

        // Verify Feature Toggle
        if (!$copropiedad->hasFeature('payments_enabled')) {
            Log::warning("Intento de pago no autorizado para copropiedad {$copropiedad->id}. El servicio de pagos está deshabilitado.");
            // In a production app, we would fire an event here for the Super Admin alert
            return null;
        }

        $provider = $this->getProvider($copropiedad);
        
        // Calculate amount including gateway commission (Auto-summed logic)
        $commission = $provider->calculateFee($transaction->monto);
        $totalToPay = $transaction->monto + $commission;

        return [
            'provider' => $provider->getIdentifier(),
            'base_amount' => $transaction->monto,
            'commission' => $commission,
            'total_amount' => $totalToPay,
            'checkout_data' => $provider->generateCheckout($transaction, $totalToPay),
        ];
    }
}

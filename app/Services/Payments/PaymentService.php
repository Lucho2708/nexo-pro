<?php

namespace App\Services\Payments;

use App\Modules\Property\Models\Copropiedad;

class PaymentService
{
    /**
     * Calcula la comisión de la pasarela (Wompi por defecto).
     */
    public function calculateCommission(float $amount, string $gateway = 'wompi'): float
    {
        if ($gateway === 'wompi') {
            // Wompi cobra 2.85% + $800 COP + IVA (19%) sobre la comisión
            $baseCommission = ($amount * 0.0285) + 800;
            $iva = $baseCommission * 0.19;
            return round($baseCommission + $iva, 2);
        }

        return 0;
    }

    /**
     * Verifica si los pagos están habilitados para una copropiedad.
     */
    public function arePaymentsEnabled(Copropiedad $copropiedad): bool
    {
        return $copropiedad->settings['payments']['enabled'] ?? false;
    }

    /**
     * Determina qué pasarela usar.
     */
    public function getActiveGateway(Copropiedad $copropiedad): string
    {
        return $copropiedad->settings['payments']['active_gateway'] ?? 'wompi';
    }
}

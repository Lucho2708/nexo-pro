<?php

namespace App\Modules\Finance\Services;

use App\Modules\Property\Models\Copropiedad;
use App\Modules\Finance\Interfaces\PaymentGatewayInterface;
use App\Modules\Finance\Gateways\WompiGateway;
use App\Modules\Finance\Gateways\AvalPayGateway;
use Exception;

class PaymentManager
{
    /**
     * Resuelve la pasarela activa para una copropiedad.
     */
    public function getActiveGateway(Copropiedad $copropiedad): PaymentGatewayInterface
    {
        $settings = $copropiedad->settings['payments'] ?? [];
        $gatewayKey = $settings['active_gateway'] ?? 'wompi';

        return match ($gatewayKey) {
            'wompi' => new WompiGateway(),
            'aval_pay' => new AvalPayGateway(),
            default => throw new Exception("Pasarela de pago '{$gatewayKey}' no soportada o configurada."),
        };
    }

    /**
     * Obtiene todas las pasarelas disponibles para ser activadas.
     */
    public function getAvailableGateways(): array
    {
        return [
            ['id' => 'wompi', 'name' => 'Wompi (Bancolombia)'],
            ['id' => 'aval_pay', 'name' => 'Aval Pay Center'],
            ['id' => 'nequi', 'name' => 'Nequi Directo (Próximamente)'],
            ['id' => 'daviplata', 'name' => 'Daviplata (Próximamente)'],
        ];
    }
}

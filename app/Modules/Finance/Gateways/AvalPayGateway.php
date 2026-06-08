<?php

namespace App\Modules\Finance\Gateways;

use App\Modules\Finance\Interfaces\PaymentGatewayInterface;
use App\Modules\Finance\Models\Transaccion;

class AvalPayGateway implements PaymentGatewayInterface
{
    public function getIdentifier(): string { return 'aval_pay'; }
    public function getName(): string { return 'Aval Pay Center'; }

    public function initiatePayment(Transaccion $transaction, array $config): array
    {
        // Integración específica de Aval Pay
        return [
            'merchant_id' => $config['merchant_id'],
            'payment_url' => 'https://avalpaycenter.com/pay',
            'transaction_id' => $transaction->id
        ];
    }

    public function handleWebhook(array $payload, array $headers): array
    {
        // Parser de respuesta Aval
        return [];
    }

    public function calculateFee(float $amount): float
    {
        // Comisión Aval Pay (Ejemplo)
        return 1500; // Valor fijo o variable
    }

    public function verifySignature(array $payload, array $headers, string $secret): bool
    {
        return true;
    }
}

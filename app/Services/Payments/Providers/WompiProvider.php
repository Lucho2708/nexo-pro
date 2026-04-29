<?php

namespace App\Services\Payments\Providers;

use App\Services\Payments\PaymentProviderInterface;
use App\Models\Transaccion;

class WompiProvider implements PaymentProviderInterface
{
    protected string $publicKey;
    protected string $privateKey;

    public function __construct()
    {
        $this->publicKey = config('services.wompi.public_key', 'pub_test_placeholder');
        $this->privateKey = config('services.wompi.private_key', 'prv_test_placeholder');
    }

    public function generateCheckout(Transaccion $transaction, float $amountWithCommission): array
    {
        // For Wompi, we usually generate a widget signature or redirect to their checkout
        return [
            'url' => 'https://checkout.wompi.co/p/',
            'method' => 'GET',
            'params' => [
                'public-key' => $this->publicKey,
                'currency' => 'COP',
                'amount-in-cents' => (int) ($amountWithCommission * 100),
                'reference' => $transaction->id . '_' . time(),
                'redirect-url' => route('owner.dashboard'),
            ]
        ];
    }

    public function parseWebhook(array $payload): array
    {
        $data = $payload['data']['transaction'] ?? [];
        
        return [
            'reference' => $data['reference'] ?? '',
            'status' => $data['status'] === 'APPROVED' ? 'SUCCESS' : 'FAILED',
            'amount' => ($data['amount_in_cents'] ?? 0) / 100,
        ];
    }

    public function getIdentifier(): string
    {
        return 'wompi';
    }

    public function calculateFee(float $amount): float
    {
        // Standard Wompi Fee: 2.85% + $800 COP + IVA on fee
        $baseFee = ($amount * 0.0285) + 800;
        $ivaOnFee = $baseFee * 0.19;
        
        return round($baseFee + $ivaOnFee, 2);
    }
}

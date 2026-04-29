<?php

namespace App\Services\Payments\Providers;

use App\Services\Payments\PaymentProviderInterface;
use App\Models\Transaccion;

class PayUProvider implements PaymentProviderInterface
{
    protected string $apiKey;
    protected string $merchantId;

    public function __construct()
    {
        $this->apiKey = config('services.payu.api_key', 'payu_test_api_key');
        $this->merchantId = config('services.payu.merchant_id', 'payu_test_merchant_id');
    }

    public function generateCheckout(Transaccion $transaction, float $amountWithCommission): array
    {
        // For PayU Web Checkout
        return [
            'url' => 'https://checkout.payulatam.com/ppp-web-gateway-payu/',
            'method' => 'POST',
            'params' => [
                'merchantId' => $this->merchantId,
                'accountId' => config('services.payu.account_id', '12345'),
                'description' => 'Pago PH360 - Transacción #' . $transaction->id,
                'referenceCode' => $transaction->id . '_' . time(),
                'amount' => $amountWithCommission,
                'tax' => 0,
                'taxReturnBase' => 0,
                'currency' => 'COP',
                'signature' => $this->generateSignature($transaction->id, $amountWithCommission),
                'test' => config('services.payu.test_mode', true) ? 1 : 0,
                'buyerEmail' => auth()->user()?->email ?? 'test@example.com',
                'responseUrl' => route('owner.dashboard'),
                'confirmationUrl' => route('api.payments.webhook', ['provider' => 'payu']),
            ]
        ];
    }

    protected function generateSignature($txId, $amount): string
    {
        // PayU signature pattern: APIKEY~MERCHANTID~REFERENCECODE~AMOUNT~CURRENCY
        $reference = $txId . '_' . time();
        return md5("{$this->apiKey}~{$this->merchantId}~{$reference}~{$amount}~COP");
    }

    public function parseWebhook(array $payload): array
    {
        // PayU generic notification parser
        return [
            'reference' => $payload['reference_sale'] ?? '',
            'status' => ($payload['state_pol'] ?? '') === '4' ? 'SUCCESS' : 'FAILED',
            'amount' => (float) ($payload['value'] ?? 0),
        ];
    }

    public function getIdentifier(): string
    {
        return 'payu';
    }

    public function calculateFee(float $amount): float
    {
        // Standard PayU Fee: 3.49% + $900 COP + IVA on fee
        $baseFee = ($amount * 0.0349) + 900;
        $ivaOnFee = $baseFee * 0.19;
        
        return round($baseFee + $ivaOnFee, 2);
    }
}

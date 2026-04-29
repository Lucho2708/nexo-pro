<?php

namespace App\Services\Payments;

use App\Models\Transaccion;

interface PaymentProviderInterface
{
    /**
     * Generate the payment checkout URL/data.
     *
     * @param Transaccion $transaction
     * @param float $amountWithCommission
     * @return array{url: string, method: string, params: array}
     */
    public function generateCheckout(Transaccion $transaction, float $amountWithCommission): array;

    /**
     * Parse the webhook data from the provider.
     *
     * @param array $payload
     * @return array{reference: string, status: string, amount: float}
     */
    public function parseWebhook(array $payload): array;

    /**
     * Get the provider's unique identifier.
     */
    public function getIdentifier(): string;

    /**
     * Calculate the gateway fee for a given amount.
     */
    public function calculateFee(float $amount): float;
}

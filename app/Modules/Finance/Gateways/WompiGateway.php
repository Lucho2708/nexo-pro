<?php

namespace App\Modules\Finance\Gateways;

use App\Modules\Finance\Interfaces\PaymentGatewayInterface;
use App\Modules\Finance\Models\Transaccion;
use Illuminate\Support\Facades\Http;

class WompiGateway implements PaymentGatewayInterface
{
    public function getIdentifier(): string { return 'wompi'; }
    public function getName(): string { return 'Wompi'; }

    public function initiatePayment(Transaccion $transaction, array $config): array
    {
        $amountInCents = (int) ($transaction->monto * 100);
        $reference = $transaction->referencia;
        $integrityKey = $config['integrity_key'] ?? env('WOMPI_INTEGRITY_KEY', '');
        
        $signature = hash('sha256', $reference . $amountInCents . 'COP' . $integrityKey);
        
        return [
            'public_key' => $config['public_key'] ?? '',
            'reference' => $reference,
            'amount_in_cents' => $amountInCents,
            'currency' => 'COP',
            'signature' => $signature,
            'redirect_url' => route('owner.payments'),
        ];
    }

    public function handleWebhook(array $payload, array $headers): array
    {
        $data = $payload['data']['transaction'];
        
        return [
            'status' => $data['status'], // APPROVED, DECLINED, VOIDED
            'gateway_reference' => $data['id'],
            'amount' => $data['amount_in_cents'] / 100,
        ];
    }

    public function calculateFee(float $amount): float
    {
        // Comisión Wompi: 2.85% + $800
        return ($amount * 0.0285) + 800;
    }

    public function verifySignature(array $payload, array $headers, string $secret): bool
    {
        $checksum = $headers['x-event-checksum'][0] ?? $headers['X-Event-Checksum'][0] ?? (is_string($headers['x-event-checksum'] ?? null) ? $headers['x-event-checksum'] : null) ?? (is_string($headers['X-Event-Checksum'] ?? null) ? $headers['X-Event-Checksum'] : null);
        $timestamp = $headers['x-event-timestamp'][0] ?? $headers['X-Event-Timestamp'][0] ?? (is_string($headers['x-event-timestamp'] ?? null) ? $headers['x-event-timestamp'] : null) ?? (is_string($headers['X-Event-Timestamp'] ?? null) ? $headers['X-Event-Timestamp'] : null);

        if (!$checksum || !$timestamp) {
            return false;
        }
        
        $data = $payload['data']['transaction'] ?? [];
        $id = $data['id'] ?? '';
        $status = $data['status'] ?? '';
        $amount = $data['amount_in_cents'] ?? '';

        $rawString = "{$id}{$status}{$amount}{$timestamp}{$secret}";
        $expectedChecksum = hash('sha256', $rawString);

        return $checksum === $expectedChecksum;
    }
}

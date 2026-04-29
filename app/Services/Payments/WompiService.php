<?php

namespace App\Services\Payments;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WompiService
{
    protected ?string $publicKey = null;
    protected ?string $privateKey = null;
    protected ?string $integrityKey = null;
    protected ?string $webhookSecret = null;
    protected string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('services.wompi.base_url', 'https://sandbox.wompi.co/v1');
    }

    /**
     * Configures the service for a specific property.
     */
    public function forCopropiedad(\App\Models\Copropiedad $copropiedad): self
    {
        $settings = $copropiedad->settings ?? [];
        
        // Fallback to .env for development/master account if keys are not in DB
        $this->publicKey = $settings['wompi_public_key'] ?? config('services.wompi.public_key');
        $this->privateKey = $settings['wompi_private_key'] ?? config('services.wompi.private_key');
        $this->integrityKey = $settings['wompi_integrity_key'] ?? config('services.wompi.integrity_key');
        $this->webhookSecret = $settings['wompi_webhook_secret'] ?? config('services.wompi.webhook_secret');

        return $this;
    }

    /**
     * Generates the integrity signature for Wompi Widget/Checkout
     */
    public function generateIntegritySignature(string $reference, float $amount, string $currency = 'COP'): string
    {
        $amountInCents = (int) ($amount * 100);
        $rawString = $reference . $amountInCents . $currency . $this->integrityKey;
        
        return hash('sha256', $rawString);
    }

    /**
     * Validates the checksum from Wompi Webhook
     */
    public function validateWebhookChecksum(array $data, string $timestamp, string $checksum): bool
    {
        // Based on Wompi documentation:
        // concat(transaction.id, transaction.status, transaction.amount_in_cents, timestamp, secret)
        $transaction = $data['transaction'];
        $rawString = $transaction['id'] . $transaction['status'] . $transaction['amount_in_cents'] . $timestamp . $this->webhookSecret;
        
        $calculatedChecksum = hash('sha256', $rawString);
        
        return hash_equals($calculatedChecksum, $checksum);
    }

    /**
     * Fetch transaction details from Wompi API
     */
    public function getTransaction(string $wompiId): array
    {
        $response = Http::withToken($this->privateKey, 'Bearer')
            ->get("{$this->baseUrl}/transactions/{$wompiId}");

        if ($response->failed()) {
            Log::error("Wompi API Error: " . $response->body());
            throw new \Exception("Could not fetch transaction from Wompi.");
        }

        return $response->json('data');
    }

    public function getPublicKey(): string
    {
        if (is_null($this->publicKey)) {
            throw new \Exception("La llave pública de Wompi no está configurada. Por favor, revisa el archivo .env o la configuración de la copropiedad.");
        }
        return $this->publicKey;
    }
}

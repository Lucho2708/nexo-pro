<?php

declare(strict_types=1);

namespace NexoPro\Finance\Gateways;

use NexoPro\Finance\Contracts\PaymentGatewayInterface;
use NexoPro\Finance\DTOs\PaymentOrderDTO;
use InvalidArgumentException;

/**
 * WompiPaymentGateway
 *
 * [LÓGICA DE NEGOCIO]
 * Adaptador de producción para la pasarela de pagos Wompi (Bancolombia).
 * Permite a los residentes cancelar cuotas de administración mediante transferencias bancarias PSE,
 * Nequi directo, tarjetas de crédito y corresponsales bancarios de la red Bancolombia.
 * Aplica el cálculo de comisiones estándar (2.85% + $800 COP) y la firma de integridad SHA-256.
 *
 * [ASPECTO TÉCNICO]
 * Implementa PaymentGatewayInterface cumpliendo LSP y DIP.
 * - Genera firmas de integridad en prepareCheckout concatenando referencia + montoEnCentavos + moneda + integritySecret.
 * - Valida checksums de webhooks mediante hash_equals en tiempo constante para erradicar ataques de temporización (timing attacks).
 * Complejidad algorítmica: O(1) en preparación y verificación de hashes.
 */
final class WompiPaymentGateway implements PaymentGatewayInterface
{
    private const COMMISSION_PERCENT = 0.0285;
    private const COMMISSION_FIXED_COP = 800.0;

    public function getIdentifier(): string
    {
        return 'wompi';
    }

    public function getName(): string
    {
        return 'Wompi (Bancolombia / Nequi / PSE)';
    }

    /**
     * [LÓGICA DE NEGOCIO]
     * Prepara el paquete de datos firmado requerido por el widget de Wompi para abrir la pasarela de recaudo.
     *
     * [ASPECTO TÉCNICO]
     * Genera un hash SHA-256 según la especificación de Wompi: hash('sha256', reference + amountInCents + currency + integrityKey).
     */
    public function prepareCheckout(PaymentOrderDTO $order, array $config): array
    {
        $publicKey = (string) ($config['public_key'] ?? '');
        $integrityKey = (string) ($config['integrity_key'] ?? '');

        if (empty($publicKey)) {
            throw new InvalidArgumentException('La llave pública de Wompi no está configurada para esta copropiedad.');
        }

        $amountInCents = $order->getMontoEnCentavos();
        $rawSignature = "{$order->referencia}{$amountInCents}{$order->moneda}{$integrityKey}";
        $signature = hash('sha256', $rawSignature);

        return [
            'public_key'      => $publicKey,
            'currency'        => $order->moneda,
            'amount_in_cents' => $amountInCents,
            'reference'       => $order->referencia,
            'signature'       => $signature,
            'redirect_url'    => $order->urlRetorno,
            'customer_email'  => $order->correoTitular,
        ];
    }

    /**
     * [LÓGICA DE NEGOCIO]
     * Valida la firma del evento recibido en el webhook para asegurar que proviene legítimamente de los servidores de Wompi.
     *
     * [ASPECTO TÉCNICO]
     * Extrae el header x-event-checksum y x-event-timestamp. Computa el hash SHA-256 de (id + status + amountInCents + timestamp + secret)
     * y utiliza hash_equals para comparación segura contra side-channel timing attacks.
     */
    public function verifyWebhookSignature(array $payload, array $headers, string $secret): bool
    {
        $checksum = $this->extractHeader($headers, 'x-event-checksum');
        $timestamp = $this->extractHeader($headers, 'x-event-timestamp');

        if ($checksum === null || $timestamp === null || empty($secret)) {
            return false;
        }

        $transaction = $payload['data']['transaction'] ?? [];
        $id = (string) ($transaction['id'] ?? '');
        $status = (string) ($transaction['status'] ?? '');
        $amount = (string) ($transaction['amount_in_cents'] ?? '');

        $rawPayload = "{$id}{$status}{$amount}{$timestamp}{$secret}";
        $expectedChecksum = hash('sha256', $rawPayload);

        return hash_equals($expectedChecksum, $checksum);
    }

    /**
     * [LÓGICA DE NEGOCIO]
     * Normaliza el estado de la transacción bancaria.
     *
     * [ASPECTO TÉCNICO]
     * Extrae status, id de pasarela y monto real convertido a pesos.
     */
    public function parseWebhookPayload(array $payload): array
    {
        $transaction = $payload['data']['transaction'] ?? [];
        $amountInCents = (float) ($transaction['amount_in_cents'] ?? 0.0);

        return [
            'status'            => (string) ($transaction['status'] ?? 'PENDING'),
            'gateway_reference' => (string) ($transaction['id'] ?? ''),
            'amount'            => round($amountInCents / 100, 2),
        ];
    }

    /**
     * [LÓGICA DE NEGOCIO]
     * Calcula el descuento de comisión bancaria para informar el valor neto recibido por la copropiedad.
     */
    public function calculateFee(float $amount): float
    {
        return round(($amount * self::COMMISSION_PERCENT) + self::COMMISSION_FIXED_COP, 2);
    }

    private function extractHeader(array $headers, string $key): ?string
    {
        $lowerKey = strtolower($key);
        foreach ($headers as $k => $v) {
            if (strtolower((string) $k) === $lowerKey) {
                return is_array($v) ? (string) ($v[0] ?? '') : (string) $v;
            }
        }
        return null;
    }
}

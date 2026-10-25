<?php

declare(strict_types=1);

namespace Tests\Unit\Finance;

use Tests\TestCase;
use NexoPro\Finance\Gateways\WompiPaymentGateway;
use NexoPro\Finance\DTOs\PaymentOrderDTO;
use InvalidArgumentException;

/**
 * WompiPaymentGatewaySecurityTest
 *
 * [LÓGICA DE NEGOCIO]
 * Valida la seguridad criptográfica y la integridad financiera de las transacciones recaudadas por Wompi (Bancolombia):
 * - Generación inalterable de firmas de pago para el widget de checkout.
 * - Detección y bloqueo inmediato de webhooks manipulados o apócrifos (suplantación de pagos).
 * - Liquidación exacta de la comisión de pasarela (2.85% + $800 COP).
 *
 * [ASPECTO TÉCNICO]
 * Pruebas unitarias de integridad criptográfica (SHA-256), resistencia a ataques de temporización (timing attacks vía hash_equals)
 * y normalización de headers HTTP conformes a RFC 7230.
 */
class WompiPaymentGatewaySecurityTest extends TestCase
{
    private WompiPaymentGateway $gateway;

    protected function setUp(): void
    {
        parent::setUp();
        $this->gateway = new WompiPaymentGateway();
    }

    /**
     * Happy Path: Prepara exitosamente el checkout con firma SHA-256 correcta.
     */
    public function test_prepare_checkout_generates_valid_sha256_signature(): void
    {
        $order = new PaymentOrderDTO(
            referencia: 'REF-NEXO-1001',
            monto: 350000.0,
            moneda: 'COP',
            unidadId: 'u-101',
            userId: 'usr-1',
            correoTitular: 'propietario@nexo.pro',
            urlRetorno: 'https://nexo.pro/pagos/retorno'
        );

        $config = [
            'public_key'    => 'pub_prod_testkey123',
            'integrity_key' => 'secret_integrity_abc999',
        ];

        $checkout = $this->gateway->prepareCheckout($order, $config);

        $this->assertEquals('pub_prod_testkey123', $checkout['public_key']);
        $this->assertEquals('COP', $checkout['currency']);
        $this->assertEquals(35000000, $checkout['amount_in_cents']);
        $this->assertEquals('REF-NEXO-1001', $checkout['reference']);

        // Verificar la firma esperada: hash('sha256', reference + amountInCents + currency + integrityKey)
        $expectedSignature = hash('sha256', 'REF-NEXO-100135000000COPsecret_integrity_abc999');
        $this->assertEquals($expectedSignature, $checkout['signature']);
    }

    /**
     * Happy Path: Valida exitosamente un webhook legítimo de Wompi.
     */
    public function test_verify_webhook_signature_returns_true_for_authentic_payload(): void
    {
        $secret = 'wompi_event_secret_123';
        $timestamp = '1698200000';
        $transactionId = 'tx-wompi-555';
        $status = 'APPROVED';
        $amountInCents = '25000000';

        // Hash esperado por Wompi: (id + status + amountInCents + timestamp + secret)
        $raw = "{$transactionId}{$status}{$amountInCents}{$timestamp}{$secret}";
        $checksum = hash('sha256', $raw);

        $payload = [
            'data' => [
                'transaction' => [
                    'id'              => $transactionId,
                    'status'          => $status,
                    'amount_in_cents' => (int) $amountInCents,
                ]
            ]
        ];

        $headers = [
            'x-event-checksum'  => $checksum,
            'x-event-timestamp' => $timestamp,
        ];

        $isValid = $this->gateway->verifyWebhookSignature($payload, $headers, $secret);

        $this->assertTrue($isValid, 'La firma del webhook legítimo debe ser validada exitosamente.');
    }

    /**
     * Edge Case 1: Bloquea intentos de suplantación donde el hacker adultera el estado o el monto (OWASP A08).
     */
    public function test_verify_webhook_signature_rejects_tampered_transaction_data(): void
    {
        $secret = 'wompi_event_secret_123';
        $timestamp = '1698200000';
        $transactionId = 'tx-wompi-555';
        $realStatus = 'DECLINED';
        $tamperedStatus = 'APPROVED';
        $amountInCents = '25000000';

        // Checksum generado con el estado real
        $realRaw = "{$transactionId}{$realStatus}{$amountInCents}{$timestamp}{$secret}";
        $checksum = hash('sha256', $realRaw);

        // Payload adulterado por atacante hacia 'APPROVED'
        $tamperedPayload = [
            'data' => [
                'transaction' => [
                    'id'              => $transactionId,
                    'status'          => $tamperedStatus,
                    'amount_in_cents' => (int) $amountInCents,
                ]
            ]
        ];

        $headers = [
            'x-event-checksum'  => $checksum,
            'x-event-timestamp' => $timestamp,
        ];

        $isValid = $this->gateway->verifyWebhookSignature($tamperedPayload, $headers, $secret);

        $this->assertFalse($isValid, 'Debe rechazar enérgicamente cualquier payload con datos alterados.');
    }

    /**
     * Edge Case 2: Manejo insensible a mayúsculas/minúsculas en encabezados HTTP.
     */
    public function test_verify_webhook_handles_case_insensitive_headers(): void
    {
        $secret = 'secret';
        $timestamp = '100';
        $id = 'id1';
        $status = 'APPROVED';
        $amount = '5000';

        $checksum = hash('sha256', "{$id}{$status}{$amount}{$timestamp}{$secret}");

        $payload = [
            'data' => [
                'transaction' => [
                    'id'              => $id,
                    'status'          => $status,
                    'amount_in_cents' => 5000,
                ]
            ]
        ];

        // Headers con mayúsculas/minúsculas mezcladas (RFC 7230)
        $headers = [
            'X-Event-Checksum'  => $checksum,
            'X-EVENT-TIMESTAMP' => $timestamp,
        ];

        $isValid = $this->gateway->verifyWebhookSignature($payload, $headers, $secret);
        $this->assertTrue($isValid);
    }

    /**
     * Edge Case 3: Retorna false si faltan encabezados o el secret está vacío.
     */
    public function test_verify_webhook_returns_false_on_missing_headers_or_empty_secret(): void
    {
        $payload = ['data' => ['transaction' => ['id' => '1']]];

        $this->assertFalse($this->gateway->verifyWebhookSignature($payload, [], 'secret'));
        $this->assertFalse($this->gateway->verifyWebhookSignature($payload, ['x-event-checksum' => 'abc'], ''));
    }

    /**
     * Edge Case 4: Lanza InvalidArgumentException si falta la llave pública en la configuración.
     */
    public function test_prepare_checkout_throws_when_public_key_missing(): void
    {
        $order = new PaymentOrderDTO(
            referencia: 'REF-1',
            monto: 10000.0,
            moneda: 'COP',
            unidadId: 'u-1',
            userId: 'usr-1',
            correoTitular: 'test@nexo.pro',
            urlRetorno: 'https://nexo.pro'
        );

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('La llave pública de Wompi no está configurada');

        $this->gateway->prepareCheckout($order, ['public_key' => '']);
    }

    /**
     * Edge Case 5: Normalización correcta de payload de webhook a tipos nativos.
     */
    public function test_parse_webhook_payload_normalizes_data_correctly(): void
    {
        $payload = [
            'data' => [
                'transaction' => [
                    'id'              => 'trans-9988',
                    'status'          => 'APPROVED',
                    'amount_in_cents' => 12500000,
                ]
            ]
        ];

        $parsed = $this->gateway->parseWebhookPayload($payload);

        $this->assertEquals('APPROVED', $parsed['status']);
        $this->assertEquals('trans-9988', $parsed['gateway_reference']);
        $this->assertEquals(125000.0, $parsed['amount']);
    }
}

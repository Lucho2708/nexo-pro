<?php

namespace App\Modules\Finance\Interfaces;

use App\Modules\Property\Models\Copropiedad;
use App\Modules\Finance\Models\Transaccion;

interface PaymentGatewayInterface
{
    /**
     * Identificador único de la pasarela (ej: 'wompi', 'aval_pay').
     */
    public function getIdentifier(): string;

    /**
     * Nombre legible para el usuario.
     */
    public function getName(): string;

    /**
     * Inicia una sesión de pago y devuelve los datos para el frontend.
     */
    public function initiatePayment(Transaccion $transaction, array $config): array;

    /**
     * Procesa la respuesta de un webhook enviado por la pasarela.
     */
    public function handleWebhook(array $payload, array $headers): array;

    /**
     * Calcula la comisión que cobra la pasarela para un monto dado.
     */
    public function calculateFee(float $amount): float;

    /**
     * Verifica la autenticidad de la petición (Firma/Checksum).
     */
    public function verifySignature(array $payload, array $headers, string $secret): bool;
}

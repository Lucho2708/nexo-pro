<?php

declare(strict_types=1);

namespace NexoPro\Finance\Contracts;

use NexoPro\Finance\DTOs\PaymentOrderDTO;

/**
 * PaymentGatewayInterface
 *
 * [LÓGICA DE NEGOCIO]
 * Contrato de integración para pasarelas de pago electrónico autorizadas en Colombia (Wompi, AvalPay, PSE, etc.).
 * Define los métodos necesarios para inicializar transacciones de recaudo de expensas comunes,
 * calcular comisiones financieras aplicables y validar la autenticidad e integridad de las notificaciones
 * asíncronas (webhooks) recibidas de las entidades bancarias.
 *
 * [ASPECTO TÉCNICO]
 * Aplica el Patrón Strategy y el Principio de Inversión de Dependencias (DIP) y Segregación de Interfaces (ISP).
 * Desacopla el dominio financiero respecto a los SDKs y payloads HTTP de proveedores externos.
 * Requiere que la validación de firmas criptográficas se realice en tiempo constante para mitigar ataques de tiempo.
 */
interface PaymentGatewayInterface
{
    /**
     * [LÓGICA DE NEGOCIO]
     * Identificador único de la pasarela dentro del sistema NEXO-PRO (ej: 'wompi', 'aval_pay').
     *
     * [ASPECTO TÉCNICO]
     * Clave semántica inmutable utilizada para resolución de estrategias en factories.
     */
    public function getIdentifier(): string;

    /**
     * [LÓGICA DE NEGOCIO]
     * Nombre comercial de la entidad o pasarela para visualización en recibos y portales.
     */
    public function getName(): string;

    /**
     * [LÓGICA DE NEGOCIO]
     * Genera los parámetros de checkout criptográficamente firmados para que el copropietario pague en el widget bancario.
     *
     * [ASPECTO TÉCNICO]
     * @param PaymentOrderDTO $order DTO con la información inmutable de la orden.
     * @param array<string, mixed> $config Parámetros de credenciales de la copropiedad (llaves públicas, secretos).
     * @return array<string, mixed> Payload para inicialización del frontend de pagos.
     */
    public function prepareCheckout(PaymentOrderDTO $order, array $config): array;

    /**
     * [LÓGICA DE NEGOCIO]
     * Valida la firma criptográfica HMAC o checksum recibida en el webhook para garantizar que la notificación
     * proviene auténticamente de la pasarela y no ha sido alterada por un intermediario malicioso (Man-in-the-Middle).
     *
     * [ASPECTO TÉCNICO]
     * @param array<string, mixed> $payload Contenido JSON decodificado del webhook.
     * @param array<string, mixed> $headers Cabeceras HTTP de la petición recibida.
     * @param string $secret Secreto de eventos de la copropiedad.
     * @return bool True si la firma es matemáticamente válida, False en caso de discrepancia.
     */
    public function verifyWebhookSignature(array $payload, array $headers, string $secret): bool;

    /**
     * [LÓGICA DE NEGOCIO]
     * Extrae el estado definitivo de la transacción (aprobada, rechazada, anulada) a partir del payload del webhook.
     *
     * [ASPECTO TÉCNICO]
     * Normaliza el resultado a estados estandarizados: 'APPROVED', 'DECLINED', 'VOIDED', 'PENDING'.
     * @param array<string, mixed> $payload
     * @return array{status: string, gateway_reference: string, amount: float}
     */
    public function parseWebhookPayload(array $payload): array;

    /**
     * [LÓGICA DE NEGOCIO]
     * Calcula la comisión financiera cobrada por la pasarela por procesamiento de pago.
     *
     * [ASPECTO TÉCNICO]
     * Cálculo matemático O(1) con base en tarifas porcentuales y costos fijos por transacción.
     */
    public function calculateFee(float $amount): float;
}

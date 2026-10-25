<?php

declare(strict_types=1);

namespace NexoPro\Finance\DTOs;

/**
 * PaymentOrderDTO
 *
 * [LÓGICA DE NEGOCIO]
 * Objeto de transferencia de datos desacoplado que representa una orden de pago electrónico
 * de expensas comunes (administración ordinaria, multas, intereses o parqueaderos).
 * Sustituye completamente la mala práctica de instanciar o mockear modelos Eloquent en controladores.
 *
 * [ASPECTO TÉCNICO]
 * Inmutable (readonly class). Encapsula el monto en centavos/enteros para evitar errores de coma flotante
 * al interactuar con pasarelas bancarias (Wompi, AvalPay, PSE, Stripe).
 */
final readonly class PaymentOrderDTO
{
    /**
     * @param string $referencia Código único de referencia mercantil (ej: 'NEXO-A101-202610').
     * @param float $monto Monto nominal en moneda de curso legal (COP).
     * @param string $moneda Código ISO 4217 (ej: 'COP').
     * @param string $unidadId UUID de la unidad inmobiliaria destinataria del recaudo.
     * @param string $userId UUID del usuario que ordena el pago.
     * @param string $correoTitular Correo electrónico para notificación de recibo digital.
     * @param string $urlRetorno URL a la que el usuario será redirigido tras completar la transacción.
     */
    public function __construct(
        public string $referencia,
        public float $monto,
        public string $moneda,
        public string $unidadId,
        public string $userId,
        public string $correoTitular,
        public string $urlRetorno
    ) {}

    /**
     * [LÓGICA DE NEGOCIO]
     * Retorna el monto expresado en centavos según estándar de pasarelas de pago colombianas.
     *
     * [ASPECTO TÉCNICO]
     * Multiplicación entera O(1) con redondeo para evitar pérdida de precisión flotante.
     */
    public function getMontoEnCentavos(): int
    {
        return (int) round($this->monto * 100);
    }
}

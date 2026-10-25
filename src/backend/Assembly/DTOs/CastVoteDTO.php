<?php

declare(strict_types=1);

namespace NexoPro\Assembly\DTOs;

/**
 * CastVoteDTO
 *
 * [LÓGICA DE NEGOCIO]
 * Representa la intención de sufragio vinculante emitida por un copropietario o apoderado legal
 * en una asamblea ordinaria o extraordinaria. Captura las unidades representadas, la opción
 * seleccionada y los metadatos forenses requeridos por la legislación colombiana (Ley 675 de 2001)
 * para garantizar la validez jurídica e inimpugnabilidad de la votación.
 *
 * [ASPECTO TÉCNICO]
 * Objeto de transferencia de datos (DTO) inmutable con propiedades de solo lectura tipadas (PHP 8.3+).
 * Elimina la mutabilidad accidental de parámetros en capas intermedias, reduce la asignación en el heap
 * y garantiza una huella de memoria constante O(1) por cada papeleta de voto digital.
 */
final readonly class CastVoteDTO
{
    /**
     * @param string $asambleaId Identificador único universal (UUID) de la sesión de asamblea.
     * @param string $preguntaId Identificador único (UUID) de la moción sometida a votación.
     * @param string $opcionId Identificador único (UUID) de la opción elegida (A favor, En contra, etc.).
     * @param string $userId Identificador del usuario que emite el sufragio digital.
     * @param array<int, array{id: string, nombre: string, coeficiente: float}> $unidades Lista de unidades representadas.
     * @param string $ipAddress Dirección IP de origen para fines de trazabilidad probatoria.
     * @param string $userAgent Identificador del cliente/navegador del votante.
     */
    public function __construct(
        public string $asambleaId,
        public string $preguntaId,
        public string $opcionId,
        public string $userId,
        public array $unidades,
        public string $ipAddress = '127.0.0.1',
        public string $userAgent = 'NexoPro/1.0'
    ) {}

    /**
     * [LÓGICA DE NEGOCIO]
     * Suma los coeficientes porcentuales de todas las unidades representadas en el voto.
     *
     * [ASPECTO TÉCNICO]
     * Complejidad algorítmica O(N) donde N es el número de inmuebles apoderados (habitualmente N <= 5).
     * Ejecuta acumulación en coma flotante con redondeo de seguridad a 5 cifras decimales.
     */
    public function calcularPesoTotal(): float
    {
        $total = 0.0;
        foreach ($this->unidades as $unidad) {
            $total += (float) ($unidad['coeficiente'] ?? 0.0);
        }
        return round($total, 5);
    }
}

<?php

namespace NexoPro\Porteria\DTOs;

use InvalidArgumentException;

/**
 * [DTO / SOLID - SRP]
 * Encapsula la validación de un QR o PIN de visita en el módulo de garita.
 */
readonly class ValidarPaseVisitaDTO
{
    public function __construct(
        public string $tokenOPin,
        public string $guardaId,
        public ?string $visitanteNombre = null,
        public ?string $visitanteDocumento = null,
        public ?string $placaVehiculo = null
    ) {
        if (trim($this->tokenOPin) === '') {
            throw new InvalidArgumentException('El código QR o PIN es obligatorio para validar el pase.');
        }

        if (trim($this->guardaId) === '') {
            throw new InvalidArgumentException('El identificador del guarda es obligatorio.');
        }
    }

    public static function fromRequest(array $data, string $guardaId): self
    {
        return new self(
            tokenOPin: (string) ($data['token_o_pin'] ?? $data['token'] ?? $data['pin'] ?? ''),
            guardaId: $guardaId,
            visitanteNombre: isset($data['visitante_nombre']) ? (string) $data['visitante_nombre'] : null,
            visitanteDocumento: isset($data['visitante_documento']) ? (string) $data['visitante_documento'] : null,
            placaVehiculo: isset($data['placa_vehiculo']) ? (string) $data['placa_vehiculo'] : null
        );
    }
}

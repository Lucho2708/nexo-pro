<?php

namespace NexoPro\Porteria\DTOs;

use InvalidArgumentException;

/**
 * [DTO / SOLID - SRP]
 * Encapsula de forma inmutable los datos requeridos para efectuar
 * un relevo de turno rápido por PIN en la garita.
 */
readonly class RelevarTurnoDTO
{
    public function __construct(
        public string $copropiedadId,
        public string $guardaSalienteId,
        public string $guardaSalienteNombre,
        public string $pinEntrante
    ) {
        if (trim($this->copropiedadId) === '') {
            throw new InvalidArgumentException('El identificador de la copropiedad es obligatorio.');
        }

        if (!preg_match('/^[0-9]{4}$/', $this->pinEntrante)) {
            throw new InvalidArgumentException('El PIN de garita debe constar exactamente de 4 dígitos numéricos.');
        }
    }

    public static function fromRequest(array $data, string $guardaSalienteId, string $guardaSalienteNombre): self
    {
        return new self(
            copropiedadId: (string) ($data['copropiedad_id'] ?? ''),
            guardaSalienteId: $guardaSalienteId,
            guardaSalienteNombre: $guardaSalienteNombre,
            pinEntrante: (string) ($data['pin'] ?? '')
        );
    }
}

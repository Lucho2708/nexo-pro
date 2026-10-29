<?php

namespace NexoPro\Porteria\DTOs;

use InvalidArgumentException;

/**
 * [DTO / SOLID - SRP]
 * Encapsula la solicitud de citofonía virtual hacia el residente.
 */
readonly class SolicitarCitofoniaDTO
{
    public function __construct(
        public string $copropiedadId,
        public string $unidadId,
        public string $guardaId,
        public string $visitanteNombre,
        public ?string $visitanteDocumento = null,
        public string $tipoVisita = 'personal'
    ) {
        if (trim($this->copropiedadId) === '') {
            throw new InvalidArgumentException('El identificador de la copropiedad es obligatorio.');
        }

        if (trim($this->unidadId) === '') {
            throw new InvalidArgumentException('El identificador de la unidad de destino es obligatorio.');
        }

        if (trim($this->visitanteNombre) === '') {
            throw new InvalidArgumentException('El nombre del visitante es obligatorio para solicitar citofonía.');
        }
    }

    public static function fromRequest(array $data, string $guardaId): self
    {
        return new self(
            copropiedadId: (string) ($data['copropiedad_id'] ?? ''),
            unidadId: (string) ($data['unidad_id'] ?? ''),
            guardaId: $guardaId,
            visitanteNombre: (string) ($data['visitante_nombre'] ?? ''),
            visitanteDocumento: isset($data['visitante_documento']) ? (string) $data['visitante_documento'] : null,
            tipoVisita: (string) ($data['tipo_visita'] ?? 'personal')
        );
    }
}

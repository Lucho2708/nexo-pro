<?php

namespace NexoPro\Porteria\DTOs;

use DateTimeInterface;
use InvalidArgumentException;

/**
 * [DTO / SOLID - SRP]
 * Encapsula los datos requeridos para generar un pase de visita digital pre-autorizado.
 */
readonly class CrearPaseVisitaDTO
{
    public function __construct(
        public string $copropiedadId,
        public string $unidadId,
        public string $residenteId,
        public ?string $visitanteNombre,
        public ?string $visitanteDocumento,
        public ?string $placaVehiculo,
        public string $tipoPase,
        public DateTimeInterface $validoDesde,
        public DateTimeInterface $validoHasta,
        public int $usosPermitidos = 1
    ) {
        if (trim($this->copropiedadId) === '') {
            throw new InvalidArgumentException('El identificador de la copropiedad es obligatorio.');
        }

        if (trim($this->unidadId) === '') {
            throw new InvalidArgumentException('El identificador de la unidad es obligatorio.');
        }

        if ($this->validoHasta <= $this->validoDesde) {
            throw new InvalidArgumentException('La fecha de vencimiento debe ser posterior a la fecha de inicio.');
        }

        if ($this->usosPermitidos < 1) {
            throw new InvalidArgumentException('El pase debe permitir al menos un ingreso.');
        }
    }

    public static function fromRequest(array $data, string $residenteId): self
    {
        return new self(
            copropiedadId: (string) ($data['copropiedad_id'] ?? ''),
            unidadId: (string) ($data['unidad_id'] ?? ''),
            residenteId: $residenteId,
            visitanteNombre: isset($data['visitante_nombre']) ? (string) $data['visitante_nombre'] : null,
            visitanteDocumento: isset($data['visitante_documento']) ? (string) $data['visitante_documento'] : null,
            placaVehiculo: isset($data['placa_vehiculo']) ? (string) $data['placa_vehiculo'] : null,
            tipoPase: (string) ($data['tipo_pase'] ?? 'individual'),
            validoDesde: new \DateTimeImmutable((string) ($data['valido_desde'] ?? 'now')),
            validoHasta: new \DateTimeImmutable((string) ($data['valido_hasta'] ?? '+1 day')),
            usosPermitidos: (int) ($data['usos_permitidos'] ?? 1)
        );
    }
}

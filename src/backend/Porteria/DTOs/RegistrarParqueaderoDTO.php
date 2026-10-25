<?php

namespace NexoPro\Porteria\DTOs;

/**
 * [LÓGICA DE NEGOCIO / UX]
 * DTO para el registro de entrada vehicular y asignación de bahías en garita.
 *
 * [ASPECTO TÉCNICO / UI]
 * Readonly class tipada con soporte para placas y números de bahía.
 */
readonly class RegistrarParqueaderoDTO
{
    public function __construct(
        public string $copropiedadId,
        public string $placaVehiculo,
        public string $tipoEspacio = 'visitante',
        public ?string $unidadId = null,
        public ?string $numeroBahia = null,
        public string $guardaId = ''
    ) {}

    public static function fromRequest(array $data, string $guardaId): self
    {
        return new self(
            copropiedadId: (string) ($data['copropiedad_id'] ?? ''),
            placaVehiculo: strtoupper(trim((string) ($data['placa_vehiculo'] ?? ''))),
            tipoEspacio: (string) ($data['tipo_espacio'] ?? 'visitante'),
            unidadId: isset($data['unidad_id']) ? (string) $data['unidad_id'] : null,
            numeroBahia: isset($data['numero_bahia']) ? trim((string) $data['numero_bahia']) : null,
            guardaId: $guardaId
        );
    }
}

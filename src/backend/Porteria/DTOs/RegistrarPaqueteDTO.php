<?php

namespace NexoPro\Porteria\DTOs;

/**
 * [LÓGICA DE NEGOCIO / UX]
 * DTO para el registro de paquetería y correspondencia entregada en portería.
 *
 * [ASPECTO TÉCNICO / UI]
 * Readonly class tipada en PHP 8.3.
 */
readonly class RegistrarPaqueteDTO
{
    public function __construct(
        public string $copropiedadId,
        public string $unidadId,
        public string $empresaMensajeria,
        public ?string $codigoGuia = null,
        public ?string $descripcion = null,
        public string $guardaId = ''
    ) {}

    public static function fromRequest(array $data, string $guardaId): self
    {
        return new self(
            copropiedadId: (string) ($data['copropiedad_id'] ?? ''),
            unidadId: (string) ($data['unidad_id'] ?? ''),
            empresaMensajeria: trim((string) ($data['empresa_mensajeria'] ?? 'Servientrega')),
            codigoGuia: isset($data['codigo_guia']) ? trim((string) $data['codigo_guia']) : null,
            descripcion: isset($data['descripcion']) ? trim((string) $data['descripcion']) : null,
            guardaId: $guardaId
        );
    }
}

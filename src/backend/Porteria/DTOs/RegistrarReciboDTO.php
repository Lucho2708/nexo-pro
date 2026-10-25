<?php

namespace NexoPro\Porteria\DTOs;

/**
 * [LÓGICA DE NEGOCIO / UX]
 * DTO para la radicación de facturas de servicios públicos en garita.
 *
 * [ASPECTO TÉCNICO / UI]
 * Readonly class inmutable con validación de tipos.
 */
readonly class RegistrarReciboDTO
{
    public function __construct(
        public string $copropiedadId,
        public string $unidadId,
        public string $tipoServicio,
        public ?string $empresaEmisora = null,
        public ?string $periodoFacturado = null,
        public string $guardaId = ''
    ) {}

    public static function fromRequest(array $data, string $guardaId): self
    {
        return new self(
            copropiedadId: (string) ($data['copropiedad_id'] ?? ''),
            unidadId: (string) ($data['unidad_id'] ?? ''),
            tipoServicio: (string) ($data['tipo_servicio'] ?? 'acueducto'),
            empresaEmisora: isset($data['empresa_emisora']) ? trim((string) $data['empresa_emisora']) : null,
            periodoFacturado: isset($data['periodo_facturado']) ? trim((string) $data['periodo_facturado']) : null,
            guardaId: $guardaId
        );
    }
}

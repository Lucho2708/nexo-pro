<?php

namespace NexoPro\CRM\DTOs;

/**
 * [LÓGICA DE NEGOCIO / UX]
 * DTO para la transición controlada de estados de prospectos en el pipeline comercial.
 *
 * [ASPECTO TÉCNICO / UI]
 * Readonly class tipada en PHP 8.3.
 */
readonly class TransicionarLeadDTO
{
    public const ESTADOS_VALIDOS = [
        'nuevo',
        'contactado',
        'demo_agendada',
        'cotizado',
        'ganado',
        'perdido',
    ];

    public function __construct(
        public string $leadId,
        public string $nuevoEstado,
        public ?string $notasComerciales = null,
        public ?string $asesorId = null
    ) {
        if (!in_array($nuevoEstado, self::ESTADOS_VALIDOS, true)) {
            throw new \InvalidArgumentException("El estado '{$nuevoEstado}' no es una etapa válida del pipeline CRM.");
        }
    }

    public static function fromRequest(array $data, string $leadId, ?string $asesorId = null): self
    {
        return new self(
            leadId: $leadId,
            nuevoEstado: (string) ($data['estado'] ?? 'nuevo'),
            notasComerciales: isset($data['notas']) ? trim((string) $data['notas']) : null,
            asesorId: $asesorId
        );
    }
}

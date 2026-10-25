<?php

namespace NexoPro\Porteria\DTOs;

/**
 * [LÓGICA DE NEGOCIO / UX]
 * Objeto de transferencia inmutable para el registro de visitantes y domicilios en garita.
 *
 * [ASPECTO TÉCNICO / UI]
 * DTO tipado con PHP 8.3 readonly class para garantizar inmutabilidad.
 */
readonly class RegistrarVisitaDTO
{
    public function __construct(
        public string $copropiedadId,
        public string $unidadId,
        public string $visitanteNombre,
        public string $visitanteDocumento,
        public string $tipoVisita = 'personal',
        public ?string $placaVehiculo = null,
        public ?string $empresaServicio = null,
        public bool $preAutorizado = false,
        public string $guardaId = '',
        public ?string $observaciones = null
    ) {}

    public static function fromRequest(array $data, string $guardaId): self
    {
        return new self(
            copropiedadId: (string) ($data['copropiedad_id'] ?? ''),
            unidadId: (string) ($data['unidad_id'] ?? ''),
            visitanteNombre: trim((string) ($data['visitante_nombre'] ?? '')),
            visitanteDocumento: trim((string) ($data['visitante_documento'] ?? '')),
            tipoVisita: (string) ($data['tipo_visita'] ?? 'personal'),
            placaVehiculo: isset($data['placa_vehiculo']) ? strtoupper(trim((string) $data['placa_vehiculo'])) : null,
            empresaServicio: isset($data['empresa_servicio']) ? trim((string) $data['empresa_servicio']) : null,
            preAutorizado: (bool) ($data['pre_autorizado'] ?? false),
            guardaId: $guardaId,
            observaciones: isset($data['observaciones']) ? trim((string) $data['observaciones']) : null
        );
    }
}

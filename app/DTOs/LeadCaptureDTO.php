<?php

namespace App\DTOs;

use Illuminate\Http\Request;

/**
 * [LÓGICA DE NEGOCIO / UX]
 * Encapsula la información provista por el prospecto durante la solicitud de auditoría de ROI
 * o agendamiento de demostración personalizada desde la Landing Page.
 *
 * [ASPECTO TÉCNICO / UI]
 * Data Transfer Object inmutable (DIP / SRP) que aísla la capa de dominio de la instancia Request de Laravel.
 */
final class LeadCaptureDTO
{
    public function __construct(
        public readonly string $nombre,
        public readonly string $email,
        public readonly string $telefono,
        public readonly string $copropiedadNombre,
        public readonly int $unidadesTotales = 50,
        public readonly float $cuotaPromedioMensual = 350000.0,
        public readonly string $rol = 'administrador',
        public readonly ?string $websiteHp = null,
        public readonly ?string $ipAddress = null,
        public readonly ?string $userAgent = null,
        public readonly array $metadata = []
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            nombre: trim((string) ($request->input('nombre') ?? $request->input('fullName', ''))),
            email: strtolower(trim((string) $request->input('email', ''))),
            telefono: trim((string) ($request->input('telefono') ?? $request->input('phone', ''))),
            copropiedadNombre: trim((string) ($request->input('copropiedad_nombre') ?? $request->input('buildingName', ''))),
            unidadesTotales: max(1, (int) $request->input('unidades_totales', 50)),
            cuotaPromedioMensual: max(0.0, (float) $request->input('cuota_promedio_mensual', 350000.0)),
            rol: (string) $request->input('rol', 'administrador'),
            websiteHp: $request->input('website_hp') ?? $request->input('honeypot'),
            ipAddress: $request->ip(),
            userAgent: $request->userAgent(),
            metadata: (array) $request->input('metadata', [])
        );
    }
}

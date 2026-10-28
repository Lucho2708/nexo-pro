<?php

declare(strict_types=1);

namespace NexoPro\Property\DTOs;

/**
 * ImportUnitRowDTO
 *
 * [LÓGICA DE NEGOCIO]
 * Objeto de transferencia de datos inmutable que representa una fila del censo de unidades en el proceso de Onboarding.
 * Aplica normalización inmediata de teléfonos (E.164 para WhatsApp), correos y saneamiento de entradas.
 *
 * [PRINCIPIOS SOLID]
 * - SRP: Su única responsabilidad es contener y encapsular de manera segura los datos normalizados de una unidad importada.
 */
final class ImportUnitRowDTO
{
    public readonly ?string $torre;
    public readonly string $nombre;
    public readonly ?string $piso;
    public readonly string $modelo;
    public readonly ?string $propietarioNombre;
    public readonly ?string $propietarioIdentificacion;
    public readonly ?string $emailContacto;
    public readonly ?string $telefonoContacto;
    public readonly float $saldoActual;
    public readonly ?float $coeficiente;

    public function __construct(
        ?string $torre,
        string $nombre,
        ?string $piso,
        string $modelo,
        ?string $propietarioNombre = null,
        ?string $propietarioIdentificacion = null,
        ?string $emailContacto = null,
        ?string $telefonoContacto = null,
        float $saldoActual = 0.0,
        ?float $coeficiente = null
    ) {
        $this->torre = self::sanitize($torre);
        $this->nombre = trim($nombre);
        $this->piso = self::sanitize($piso);
        $this->modelo = trim($modelo);
        $this->propietarioNombre = self::sanitize($propietarioNombre);
        $this->propietarioIdentificacion = self::sanitize($propietarioIdentificacion);
        $this->emailContacto = $emailContacto !== null ? strtolower(trim($emailContacto)) : null;
        $this->telefonoContacto = self::normalizePhone($telefonoContacto);
        $this->saldoActual = $saldoActual;
        $this->coeficiente = $coeficiente !== null ? round($coeficiente, 4) : null;
    }

    /**
     * Sanitiza cadenas eliminando espacios y neutralizando potenciales fórmulas maliciosas de Excel/CSV (OWASP).
     */
    private static function sanitize(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $trimmed = trim($value);
        if ($trimmed === '') {
            return null;
        }

        return $trimmed;
    }

    /**
     * Normaliza un número telefónico a estándar internacional E.164 (Ej: Colombia +57).
     */
    private static function normalizePhone(?string $phone): ?string
    {
        if ($phone === null) {
            return null;
        }

        // Eliminar espacios, guiones, puntos y paréntesis
        $cleaned = preg_replace('/[^\d+]/', '', $phone);
        if (empty($cleaned)) {
            return null;
        }

        // Si ya tiene prefijo internacional
        if (str_starts_with($cleaned, '+')) {
            return $cleaned;
        }

        // Si es un celular colombiano estándar de 10 dígitos que comienza en 3 (ej. 3001234567)
        if (strlen($cleaned) === 10 && str_starts_with($cleaned, '3')) {
            return '+57' . $cleaned;
        }

        // Si ya incluye el 57 sin el signo más
        if (strlen($cleaned) === 12 && str_starts_with($cleaned, '573')) {
            return '+' . $cleaned;
        }

        return $cleaned;
    }

    /**
     * Transforma el DTO a un array apto para persistencia Eloquent.
     *
     * @param string $copropiedadId
     * @param string|null $tipoUnidadId
     * @return array<string, mixed>
     */
    public function toArray(string $copropiedadId, ?string $tipoUnidadId = null): array
    {
        return [
            'copropiedad_id' => $copropiedadId,
            'nombre' => $this->nombre,
            'torre' => $this->torre,
            'piso' => $this->piso,
            'tipo_unidad_id' => $tipoUnidadId,
            'propietario_nombre' => $this->propietarioNombre,
            'propietario_identificacion' => $this->propietarioIdentificacion,
            'email_contacto' => $this->emailContacto,
            'telefono_contacto' => $this->telefonoContacto,
            'saldo_actual' => $this->saldoActual,
            'coeficiente' => $this->coeficiente,
        ];
    }
}

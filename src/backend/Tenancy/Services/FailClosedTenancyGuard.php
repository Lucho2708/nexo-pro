<?php

declare(strict_types=1);

namespace NexoPro\Tenancy\Services;

use NexoPro\Tenancy\Contracts\TenantContextInterface;
use RuntimeException;

/**
 * FailClosedTenancyGuard
 *
 * [LÓGICA DE NEGOCIO]
 * Centinela de seguridad para aislamiento multi-inquilino bajo la filosofía "Fail-Closed" (falla cerrada).
 * Ante cualquier intento de mutación o lectura de datos sin que se haya especificado y verificado
 * el contexto de la copropiedad, el guardia bloquea de inmediato la operación, evitando cualquier riesgo
 * de fuga de información entre diferentes conjuntos residenciales o edificios.
 *
 * [ASPECTO TÉCNICO]
 * Mitigación activa contra OWASP A01:2021 (Broken Access Control) y prevención de Insecure Direct Object References (IDOR).
 * Valida que el UUID no sea nulo, vacío ni coincida con copropiedades ajenas antes de autorizar transacciones.
 * Complejidad algorítmica: O(1) en verificación en memoria.
 */
final class FailClosedTenancyGuard
{
    public function __construct(
        private readonly TenantContextInterface $tenantContext
    ) {}

    /**
     * [LÓGICA DE NEGOCIO]
     * Exige que exista un contexto activo de copropiedad. Si no existe, lanza una excepción de seguridad.
     *
     * [ASPECTO TÉCNICO]
     * @return string UUID verificado de la copropiedad activa.
     * @throws RuntimeException Si no hay contexto establecido y el usuario no es super-admin.
     */
    public function requireActiveTenantId(): string
    {
        $tenantId = $this->tenantContext->getActiveCopropiedadId();

        if ($tenantId === null || trim($tenantId) === '') {
            if ($this->tenantContext->isSuperAdmin()) {
                throw new RuntimeException('Operación transaccional bloqueada: El SuperAdmin debe seleccionar una copropiedad específica para realizar mutaciones.');
            }

            throw new RuntimeException('Aislamiento de seguridad activado: No se ha inicializado el contexto de copropiedad (Fail-Closed Policy).');
        }

        return $tenantId;
    }

    /**
     * [LÓGICA DE NEGOCIO]
     * Valida que un registro pertenezca estrictamente a la copropiedad activa de la sesión.
     *
     * [ASPECTO TÉCNICO]
     * @param string $recordCopropiedadId UUID de la copropiedad asignada al registro.
     * @return bool True si coincide o es SuperAdmin con privilegios globales.
     * @throws RuntimeException Si se detecta un intento de acceso cruzado entre copropiedades (IDOR).
     */
    public function assertBelongsToCurrentTenant(string $recordCopropiedadId): bool
    {
        $currentId = $this->requireActiveTenantId();

        if ($currentId !== $recordCopropiedadId) {
            throw new RuntimeException("Violación de límites de copropiedad detectada: Intento de manipular el recurso de la copropiedad {$recordCopropiedadId} desde la sesión de {$currentId}.");
        }

        return true;
    }
}

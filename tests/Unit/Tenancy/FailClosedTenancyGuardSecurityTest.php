<?php

declare(strict_types=1);

namespace Tests\Unit\Tenancy;

use Tests\TestCase;
use NexoPro\Tenancy\Services\FailClosedTenancyGuard;
use NexoPro\Tenancy\Contracts\TenantContextInterface;
use Mockery;
use Mockery\MockInterface;
use RuntimeException;

/**
 * FailClosedTenancyGuardSecurityTest
 *
 * [LÓGICA DE NEGOCIO]
 * Valida la política de seguridad Multi-Inquilino "Fail-Closed" (Falla Cerrada) para conjuntos residenciales:
 * - Bloqueo absoluto de cualquier lectura o mutación si no existe una copropiedad activa en sesión.
 * - Prevención estricta de ataques IDOR (Insecure Direct Object References) e intromisión cruzada entre edificios.
 * - Restricción para SuperAdministradores: prohibido mutar datos sin seleccionar previamente la copropiedad objetivo.
 *
 * [ASPECTO TÉCNICO]
 * Pruebas unitarias de control de acceso (OWASP A01:2021) e inmutabilidad de contexto mediante Mock de TenantContextInterface (DIP).
 */
class FailClosedTenancyGuardSecurityTest extends TestCase
{
    private TenantContextInterface&MockInterface $tenantContext;
    private FailClosedTenancyGuard $guard;

    protected function setUp(): void
    {
        parent::setUp();
        $this->tenantContext = Mockery::mock(TenantContextInterface::class);
        $this->guard = new FailClosedTenancyGuard($this->tenantContext);
    }

    /**
     * Happy Path: Retorna el UUID de la copropiedad cuando el contexto está debidamente inicializado.
     */
    public function test_require_active_tenant_id_returns_uuid_when_present(): void
    {
        $expectedId = 'coprop-1111-2222-3333-444455556666';

        $this->tenantContext->shouldReceive('getActiveCopropiedadId')
            ->once()
            ->andReturn($expectedId);

        $result = $this->guard->requireActiveTenantId();

        $this->assertEquals($expectedId, $result);
    }

    /**
     * Happy Path: Permite la operación si el registro pertenece a la copropiedad activa.
     */
    public function test_assert_belongs_to_current_tenant_passes_when_ids_match(): void
    {
        $tenantId = 'coprop-active-999';

        $this->tenantContext->shouldReceive('getActiveCopropiedadId')
            ->once()
            ->andReturn($tenantId);

        $result = $this->guard->assertBelongsToCurrentTenant($tenantId);

        $this->assertTrue($result);
    }

    /**
     * Edge Case 1: Lanza RuntimeException si el contexto es null (Fail-Closed).
     */
    public function test_require_active_tenant_throws_when_context_is_null_for_regular_user(): void
    {
        $this->tenantContext->shouldReceive('getActiveCopropiedadId')
            ->once()
            ->andReturn(null);

        $this->tenantContext->shouldReceive('isSuperAdmin')
            ->once()
            ->andReturn(false);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Aislamiento de seguridad activado: No se ha inicializado el contexto de copropiedad (Fail-Closed Policy).');

        $this->guard->requireActiveTenantId();
    }

    /**
     * Edge Case 2: Lanza RuntimeException si el contexto es una cadena vacía o con solo espacios en blanco.
     */
    public function test_require_active_tenant_throws_when_context_is_empty_or_whitespace(): void
    {
        $this->tenantContext->shouldReceive('getActiveCopropiedadId')
            ->once()
            ->andReturn("   \t   ");

        $this->tenantContext->shouldReceive('isSuperAdmin')
            ->once()
            ->andReturn(false);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Aislamiento de seguridad activado');

        $this->guard->requireActiveTenantId();
    }

    /**
     * Edge Case 3: SuperAdmin intentando ejecutar mutaciones sin elegir copropiedad recibe bloqueo explícito.
     */
    public function test_superadmin_blocked_from_mutations_without_selecting_target_tenant(): void
    {
        $this->tenantContext->shouldReceive('getActiveCopropiedadId')
            ->once()
            ->andReturn(null);

        $this->tenantContext->shouldReceive('isSuperAdmin')
            ->once()
            ->andReturn(true);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Operación transaccional bloqueada: El SuperAdmin debe seleccionar una copropiedad específica para realizar mutaciones.');

        $this->guard->requireActiveTenantId();
    }

    /**
     * Edge Case 4: Ataque IDOR - Usuario de Copropiedad A intenta mutar un recurso de Copropiedad B.
     */
    public function test_assert_belongs_to_current_tenant_blocks_idor_cross_tenant_attack(): void
    {
        $currentTenant = 'copropiedad-torres-del-parque';
        $victimTenant = 'copropiedad-los-rosales';

        $this->tenantContext->shouldReceive('getActiveCopropiedadId')
            ->once()
            ->andReturn($currentTenant);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage("Violación de límites de copropiedad detectada: Intento de manipular el recurso de la copropiedad {$victimTenant} desde la sesión de {$currentTenant}.");

        $this->guard->assertBelongsToCurrentTenant($victimTenant);
    }
}

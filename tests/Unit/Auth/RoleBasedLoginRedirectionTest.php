<?php

namespace Tests\Unit\Auth;

use Tests\TestCase;
use App\Modules\IAM\Models\User;
use App\Modules\IAM\Responses\LoginResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RoleBasedLoginRedirectionTest extends TestCase
{
    /**
     * [LÓGICA DE NEGOCIO / UX]
     * Valida que cada rol sea redirigido a su URL canónica correspondiente tras autenticarse:
     * - super_admin -> /super-admin/stats (superadmin.dashboard)
     * - backoffice  -> /backoffice/leads (backoffice.leads.index)
     * - admin       -> /admin/dashboard (admin.dashboard)
     * - security    -> /porteria (porteria.dashboard)
     * - owner       -> /portal (owner.dashboard)
     */
    public function test_admin_is_redirected_to_canonical_admin_dashboard(): void
    {
        $admin = new User([
            'name' => 'Administrador Prueba',
            'email' => 'admin@conjunto.com',
            'role' => 'admin',
        ]);
        $admin->id = (string) Str::uuid();

        $this->actingAs($admin);

        $request = Request::create('/login', 'POST');
        $response = (new LoginResponse())->toResponse($request);

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertStringContainsString('/admin/dashboard', $response->headers->get('Location'));
    }

    public function test_superadmin_is_redirected_to_superadmin_dashboard(): void
    {
        $superadmin = new User([
            'name' => 'Super Admin Prueba',
            'email' => 'super@nexopro.io',
            'role' => 'super_admin',
        ]);
        $superadmin->id = (string) Str::uuid();

        $this->actingAs($superadmin);

        $request = Request::create('/login', 'POST');
        $response = (new LoginResponse())->toResponse($request);

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertStringContainsString('/super-admin/stats', $response->headers->get('Location'));
    }

    public function test_security_is_redirected_to_porteria_dashboard(): void
    {
        $guard = new User([
            'name' => 'Guarda Turno',
            'email' => 'garita@conjunto.com',
            'role' => 'security',
        ]);
        $guard->id = (string) Str::uuid();

        $this->actingAs($guard);

        $request = Request::create('/login', 'POST');
        $response = (new LoginResponse())->toResponse($request);

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertStringContainsString('/porteria', $response->headers->get('Location'));
    }

    public function test_backoffice_is_redirected_to_backoffice_leads(): void
    {
        $sales = new User([
            'name' => 'Asesor Comercial',
            'email' => 'ventas@nexopro.io',
            'role' => 'backoffice',
        ]);
        $sales->id = (string) Str::uuid();

        $this->actingAs($sales);

        $request = Request::create('/login', 'POST');
        $response = (new LoginResponse())->toResponse($request);

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertStringContainsString('/backoffice/leads', $response->headers->get('Location'));
    }

    public function test_owner_is_redirected_to_portal_dashboard(): void
    {
        $owner = new User([
            'name' => 'Propietario Residente',
            'email' => 'residente@edificio.com',
            'role' => 'owner',
        ]);
        $owner->id = (string) Str::uuid();

        $this->actingAs($owner);

        $request = Request::create('/login', 'POST');
        $response = (new LoginResponse())->toResponse($request);

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertStringContainsString('/portal', $response->headers->get('Location'));
    }

    /**
     * [SEGURIDAD OWASP A01 / UX]
     * Si la sesión contiene una URL previa incompatible (ej. un admin que antes navegó a /portal/payments como invitado),
     * el sistema purga url.intended para evitar desviar al admin al portal del residente.
     */
    public function test_purges_incompatible_intended_url_for_admin(): void
    {
        $admin = new User([
            'name' => 'Admin Conjunto',
            'email' => 'admin@conjunto.com',
            'role' => 'admin',
        ]);
        $admin->id = (string) Str::uuid();

        $this->actingAs($admin);

        session(['url.intended' => 'http://localhost:8000/portal/payments']);

        $request = Request::create('/login', 'POST');
        $response = (new LoginResponse())->toResponse($request);

        $this->assertStringContainsString('/admin/dashboard', $response->headers->get('Location'));
        $this->assertNull(session('url.intended'));
    }
}

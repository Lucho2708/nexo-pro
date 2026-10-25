<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use App\Modules\IAM\Models\User;
use App\Modules\Property\Models\Copropiedad;
use Illuminate\Support\Str;

class EnvironmentRoutingIntegrationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware([
            \App\Http\Middleware\EnsureLegalConsent::class,
            \App\Http\Middleware\CheckLicenseStatus::class,
            \App\Http\Middleware\EnsureTwoFactorIsConfigured::class,
        ]);
    }
    public function test_guest_accessing_admin_dashboard_is_redirected_to_login(): void
    {
        $response = $this->get('/admin/dashboard');

        $response->assertRedirect('/login');
    }

    public function test_dashboard_dispatcher_routes_admin_to_canonical_admin_dashboard(): void
    {
        $admin = new User([
            'name' => 'Admin Conjunto',
            'email' => 'admin@conjunto.com',
            'role' => 'admin',
            'legal_terms_accepted' => true,
            'privacy_policy_accepted' => true,
            'two_factor_confirmed_at' => now(),
        ]);
        $admin->id = (string) Str::uuid();

        $this->actingAs($admin);

        $response = $this->get('/dashboard');

        $response->assertRedirect(route('admin.dashboard'));
    }

    public function test_dashboard_dispatcher_routes_owner_to_canonical_portal(): void
    {
        $owner = new User([
            'name' => 'Propietario Residente',
            'email' => 'owner@conjunto.com',
            'role' => 'owner',
            'legal_terms_accepted' => true,
            'privacy_policy_accepted' => true,
            'two_factor_confirmed_at' => now(),
        ]);
        $owner->id = (string) Str::uuid();

        $this->actingAs($owner);

        $response = $this->get('/dashboard');

        $response->assertRedirect(route('owner.dashboard'));
    }

    public function test_dashboard_dispatcher_routes_backoffice_to_canonical_crm(): void
    {
        $sales = new User([
            'name' => 'Comercial Expansión',
            'email' => 'ventas@nexopro.io',
            'role' => 'backoffice',
            'legal_terms_accepted' => true,
            'privacy_policy_accepted' => true,
            'two_factor_confirmed_at' => now(),
        ]);
        $sales->id = (string) Str::uuid();

        $this->actingAs($sales);

        $response = $this->get('/dashboard');

        $response->assertRedirect(route('backoffice.leads.index'));
    }

    public function test_dashboard_dispatcher_routes_security_to_canonical_porteria(): void
    {
        $guard = new User([
            'name' => 'Guarda Turno',
            'email' => 'garita@conjunto.com',
            'role' => 'security',
            'legal_terms_accepted' => true,
            'privacy_policy_accepted' => true,
            'two_factor_confirmed_at' => now(),
        ]);
        $guard->id = (string) Str::uuid();

        $this->actingAs($guard);

        $response = $this->get('/dashboard');

        $response->assertRedirect(route('porteria.dashboard'));
    }

    public function test_dashboard_dispatcher_routes_superadmin_to_canonical_stats(): void
    {
        $super = new User([
            'name' => 'Super Admin',
            'email' => 'super@nexopro.io',
            'role' => 'super_admin',
            'legal_terms_accepted' => true,
            'privacy_policy_accepted' => true,
            'two_factor_confirmed_at' => now(),
        ]);
        $super->id = (string) Str::uuid();

        $this->actingAs($super);

        $response = $this->get('/dashboard');

        $response->assertRedirect(route('superadmin.dashboard'));
    }

    public function test_owner_is_forbidden_from_admin_dashboard(): void
    {
        $owner = new User([
            'name' => 'Propietario Residente',
            'email' => 'owner@conjunto.com',
            'role' => 'owner',
            'legal_terms_accepted' => true,
            'privacy_policy_accepted' => true,
            'two_factor_confirmed_at' => now(),
        ]);
        $owner->id = (string) Str::uuid();

        $this->actingAs($owner);

        $response = $this->get('/admin/dashboard');

        $response->assertForbidden();
    }
}

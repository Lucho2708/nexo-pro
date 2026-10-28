<?php

namespace Tests\Feature\Auth;

use App\Modules\IAM\Models\User;
use App\Modules\Property\Models\Copropiedad;
use App\Modules\Finance\Models\ConceptoCobro;
use App\Notifications\WelcomeAdminNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class OnboardingRegistrationE2ETest extends TestCase
{
    use RefreshDatabase;

    public function test_onboarding_page_renders_with_query_parameters_and_plans(): void
    {
        $response = $this->get('/register?plan=boutique&billing=annual');

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->component('Auth/Onboarding')
            ->has('available_plans')
            ->where('selected_plan_slug', 'boutique')
            ->where('initial_billing_cycle', 'annual')
        );
    }

    public function test_registration_validation_requires_departamento_and_all_mandatory_fields(): void
    {
        $response = $this->post('/register', []);

        $response->assertSessionHasErrors([
            'nit',
            'nombre_copropiedad',
            'direccion',
            'departamento',
            'ciudad',
            'plan',
            'name',
            'email',
            'password',
            'terms',
        ]);
    }

    public function test_registration_rejects_duplicate_nit_and_duplicate_email(): void
    {
        // 1. Existing Copropiedad with known NIT
        Copropiedad::factory()->create([
            'nit' => '900.123.456-7',
            'nombre' => 'Edificio Existente',
        ]);

        // 2. Existing User with known Email
        User::factory()->create([
            'email' => 'admin.existente@nexopro.co',
        ]);

        $payload = [
            'nit' => '900.123.456-7',
            'nombre_copropiedad' => 'Nuevo Intento',
            'direccion' => 'Carrera 10 # 20-30',
            'departamento' => 'Cundinamarca',
            'ciudad' => 'Bogotá D.C.',
            'plan' => 'condominio_pro',
            'name' => 'Nuevo Admin',
            'email' => 'admin.existente@nexopro.co',
            'password' => 'Password123!',
            'terms' => true,
        ];

        $response = $this->post('/register', $payload);

        $response->assertSessionHasErrors(['nit', 'email']);
    }

    public function test_successful_registration_stores_departamento_and_logs_in_admin(): void
    {
        Notification::fake();

        $payload = [
            'nit' => '901.888.777-1',
            'nombre_copropiedad' => 'Conjunto Residencial Las Acacias',
            'direccion' => 'Calle 45 # 12-34',
            'departamento' => 'Antioquia',
            'ciudad' => 'Medellín',
            'plan' => 'condominio_pro',
            'billing_cycle' => 'monthly',
            'name' => 'Gloria Patricia Montoya',
            'email' => 'gloria.admin@lasacacias.com',
            'password' => 'SecretoSeguro2026!',
            'terms' => true,
        ];

        $response = $this->post('/register', $payload);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticated();

        // Check copropiedad in property schema
        $this->assertDatabaseHas('property.copropiedades', [
            'nit' => '901.888.777-1',
            'nombre' => 'Conjunto Residencial Las Acacias',
            'direccion' => 'Calle 45 # 12-34',
            'departamento' => 'Antioquia',
            'ciudad' => 'Medellín',
            'plan' => 'condominio_pro',
            'license_status' => 'active',
        ]);

        // Check user creation and role
        $this->assertDatabaseHas('users', [
            'name' => 'Gloria Patricia Montoya',
            'email' => 'gloria.admin@lasacacias.com',
            'role' => 'admin',
        ]);

        $copropiedad = Copropiedad::where('nit', '901.888.777-1')->firstOrFail();
        $user = User::where('email', 'gloria.admin@lasacacias.com')->firstOrFail();

        $this->assertEquals($copropiedad->id, $user->current_copropiedad_id);
        $this->assertNotNull($user->terms_accepted_at);

        // Verify default concepts were seeded
        $this->assertDatabaseHas('finance.conceptos_cobro', [
            'copropiedad_id' => $copropiedad->id,
            'codigo' => 'ADM',
        ]);

        // Notification sent
        Notification::assertSentTo($user, WelcomeAdminNotification::class);
    }

    public function test_registration_completes_even_if_notification_throws_transport_exception(): void
    {
        // Mock Notification facade to simulate mail server failure
        Notification::shouldReceive('send')
            ->andThrow(new \RuntimeException('Connection could not be established with host smtp.mail.com'));

        $payload = [
            'nit' => '901.999.888-2',
            'nombre_copropiedad' => 'Edificio San Jeronimo',
            'direccion' => 'Avenida El Poblado # 10-20',
            'departamento' => 'Antioquia',
            'ciudad' => 'Medellín',
            'plan' => 'boutique',
            'billing_cycle' => 'annual',
            'name' => 'Carlos Administrador',
            'email' => 'carlos.admin@sanjeronimo.com',
            'password' => 'SecretoRobusto2026!',
            'terms' => true,
        ];

        $response = $this->post('/register', $payload);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticated();

        $this->assertDatabaseHas('property.copropiedades', [
            'nit' => '901.999.888-2',
            'nombre' => 'Edificio San Jeronimo',
            'departamento' => 'Antioquia',
            'ciudad' => 'Medellín',
        ]);
    }
}

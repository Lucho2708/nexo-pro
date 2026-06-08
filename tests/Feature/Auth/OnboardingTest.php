<?php

use App\Modules\Property\Models\Copropiedad;
use App\Modules\IAM\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('renders the onboarding page', function () {
    $response = $this->get('/register');

    $response->assertStatus(200);
});

it('validates a registration request', function () {
    $response = $this->post('/register', []);

    $response->assertSessionHasErrors([
        'nit', 'nombre_copropiedad', 'direccion', 'ciudad', 
        'plan', 'unidades_totales', 'torres',
        'name', 'email', 'password', 'terms'
    ]);
});

it('registers a new copropiedad and admin user', function () {
    $data = [
        'nit' => '123456789-0',
        'nombre_copropiedad' => 'Residencial El Sol',
        'direccion' => 'Calle 123 #45-67',
        'ciudad' => 'Medellín',
        'plan' => 'pro',
        'unidades_totales' => 120,
        'torres' => 3,
        'name' => 'Admin User',
        'email' => 'admin@elsol.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'terms' => true,
    ];

    $response = $this->post('/register', $data);

    $response->assertRedirect(route('dashboard'));
    $this->assertAuthenticated();

    $this->assertDatabaseHas('property.copropiedades', [
        'nit' => '123456789-0',
        'nombre' => 'Residencial El Sol',
        'plan' => 'pro',
    ]);

    $this->assertDatabaseHas('users', [
        'name' => 'Admin User',
        'email' => 'admin@elsol.com',
    ]);

    $user = User::where('email', 'admin@elsol.com')->first();
    $this->assertNotNull($user->current_copropiedad_id);
    $this->assertNotNull($user->terms_accepted_at);
    
    $copropiedad = Copropiedad::where('nit', '123456789-0')->first();
    $this->assertEquals($copropiedad->id, $user->current_copropiedad_id);
});

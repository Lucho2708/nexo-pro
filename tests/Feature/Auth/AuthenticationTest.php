<?php

use App\Modules\IAM\Models\User;
use App\Modules\Property\Models\Copropiedad;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->copropiedad = Copropiedad::create([
        'nit' => '900-1',
        'nombre' => 'Test Cop',
        'direccion' => 'Calle 1',
        'ciudad' => 'Medellin',
    ]);

    $this->user = User::create([
        'name' => 'Admin User',
        'email' => 'admin@test.com',
        'password' => bcrypt('password'),
        'current_copropiedad_id' => $this->copropiedad->id,
        'role' => 'admin',
    ]);
});

it('renders the login page', function () {
    $response = $this->get('/login');

    $response->assertStatus(200);
});

it('can authenticate with valid credentials', function () {
    $response = $this->post('/login', [
        'email' => 'admin@test.com',
        'password' => 'password',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('dashboard'));
});

it('does not authenticate with invalid password', function () {
    $response = $this->post('/login', [
        'email' => 'admin@test.com',
        'password' => 'wrong-password',
    ]);

    $this->assertGuest();
    $response->assertSessionHasErrors('email');
});

it('can logout', function () {
    $response = $this->actingAs($this->user)->post('/logout');

    $this->assertGuest();
    $response->assertRedirect('/');
});

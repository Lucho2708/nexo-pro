<?php

use App\Modules\IAM\Models\User;
use App\Modules\Property\Models\Copropiedad;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('a super admin can login and is redirected to super admin dashboard', function () {
    $user = User::factory()->create([
        'email' => 'superadmin@nexo.pro',
        'password' => bcrypt('password'),
        'role' => 'super_admin',
    ]);

    $response = $this->post('/login', [
        'email' => 'superadmin@nexo.pro',
        'password' => 'password',
    ]);

    $response->assertRedirect(route('superadmin.dashboard'));
    $this->assertAuthenticatedAs($user);
});

test('an admin can login and is redirected to admin dashboard', function () {
    $copropiedad = Copropiedad::factory()->create();
    $user = User::factory()->create([
        'email' => 'admin@nexo.pro',
        'password' => bcrypt('password'),
        'role' => 'admin',
        'current_copropiedad_id' => $copropiedad->id,
    ]);

    $response = $this->post('/login', [
        'email' => 'admin@nexo.pro',
        'password' => 'password',
    ]);

    $response->assertRedirect(route('dashboard'));
    $this->assertAuthenticatedAs($user);
});

test('an owner can login and is redirected to owner dashboard', function () {
    $copropiedad = Copropiedad::factory()->create();
    $user = User::factory()->create([
        'email' => 'owner@nexo.pro',
        'password' => bcrypt('password'),
        'role' => 'owner',
        'current_copropiedad_id' => $copropiedad->id,
    ]);

    $response = $this->post('/login', [
        'email' => 'owner@nexo.pro',
        'password' => 'password',
    ]);

    $response->assertRedirect(route('owner.dashboard'));
    $this->assertAuthenticatedAs($user);
});

test('it returns an error with invalid credentials', function () {
    User::factory()->create([
        'email' => 'user@nexo.pro',
        'password' => bcrypt('password'),
    ]);

    $response = $this->post('/login', [
        'email' => 'user@nexo.pro',
        'password' => 'wrong-password',
    ]);

    $response->assertSessionHasErrors('email');
    $this->assertGuest();
});

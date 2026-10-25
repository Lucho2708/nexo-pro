<?php

use App\Modules\IAM\Models\User;
use App\Modules\Property\Models\Copropiedad;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('Login Flow & Relative Redirects', function () {
    beforeEach(function () {
        $this->copropiedad = Copropiedad::factory()->create();

        $this->superAdmin = User::factory()->create([
            'email' => 'admin@nexo-pro.com.co',
            'password' => bcrypt('1070608568Luis'),
            'role' => 'super_admin',
        ]);

        $this->admin = User::factory()->create([
            'email' => 'admin_multi@nexo.pro',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'current_copropiedad_id' => $this->copropiedad->id,
        ]);

        $this->owner = User::factory()->create([
            'email' => 'propietario@nexo.pro',
            'password' => bcrypt('password'),
            'role' => 'owner',
            'current_copropiedad_id' => $this->copropiedad->id,
        ]);
    });

    it('authenticates super admin and redirects to superadmin dashboard', function () {
        $response = $this->post('/login', [
            'email' => 'admin@nexo-pro.com.co',
            'password' => '1070608568Luis',
        ]);

        $this->assertAuthenticatedAs($this->superAdmin);
        $response->assertRedirect(route('superadmin.dashboard', [], false));
    });

    it('authenticates admin and redirects to dashboard route', function () {
        $response = $this->post('/login', [
            'email' => 'admin_multi@nexo.pro',
            'password' => 'password',
        ]);

        $this->assertAuthenticatedAs($this->admin);
        $response->assertRedirect(route('dashboard', [], false));
    });

    it('authenticates owner and redirects to owner dashboard route', function () {
        $response = $this->post('/login', [
            'email' => 'propietario@nexo.pro',
            'password' => 'password',
        ]);

        $this->assertAuthenticatedAs($this->owner);
        $response->assertRedirect(route('owner.dashboard', [], false));
    });
});

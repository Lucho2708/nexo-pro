<?php

use App\Modules\IAM\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->superAdmin = User::factory()->create(['role' => 'super_admin']);
    $this->regularUser = User::factory()->create(['role' => 'admin', 'name' => 'Regular Admin']);
});

it('prevents non-superadmin from accessing users index', function () {
    $this->actingAs($this->regularUser)
        ->get(route('superadmin.users.index'))
        ->assertForbidden();
});

it('allows superadmin to view users index', function () {
    $this->actingAs($this->superAdmin)
        ->get(route('superadmin.users.index'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page->component('SuperAdmin/Users/Index'));
});

it('allows superadmin to toggle user status', function () {
    $this->actingAs($this->superAdmin)
        ->patch(route('superadmin.users.toggle-status', $this->regularUser))
        ->assertRedirect()
        ->assertSessionHas('success', "El usuario {$this->regularUser->name} ha sido desactivado correctamente.");
});

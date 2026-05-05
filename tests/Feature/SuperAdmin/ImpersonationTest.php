<?php

use App\Modules\IAM\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->superAdmin = User::factory()->create(['role' => 'super_admin']);
    $this->targetUser = User::factory()->create(['role' => 'admin', 'name' => 'John Target']);
});

it('prevents non-superadmin from impersonating users', function () {
    $this->actingAs($this->targetUser)
        ->post(route('superadmin.impersonate', $this->superAdmin))
        ->assertForbidden();
});

it('allows superadmin to start impersonating a user', function () {
    $this->actingAs($this->superAdmin)
        ->post(route('superadmin.impersonate', $this->targetUser))
        ->assertRedirect(route('dashboard'))
        ->assertSessionHas('success', "Ahora estás navegando como {$this->targetUser->name}");

    expect(auth()->id())->toBe($this->targetUser->id);
    expect(session('impersonator_id'))->toBe($this->superAdmin->id);
});

it('allows impersonator to stop impersonating and return to superadmin', function () {
    $this->actingAs($this->superAdmin)
        ->post(route('superadmin.impersonate', $this->targetUser));

    $this->post(route('superadmin.impersonate.stop'))
        ->assertRedirect(route('dashboard'))
        ->assertSessionHas('success', 'Has regresado a tu cuenta de Super Admin');

    expect(auth()->id())->toBe($this->superAdmin->id);
    expect(session()->has('impersonator_id'))->toBeFalse();
});

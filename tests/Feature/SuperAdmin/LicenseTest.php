<?php

use App\Models\Copropiedad;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->superAdmin = User::factory()->create(['role' => 'super_admin']);
    $this->regularUser = User::factory()->create(['role' => 'admin']);
    $this->copropiedad = Copropiedad::factory()->create([
        'plan' => 'basic',
        'license_status' => 'active',
        'settings' => Copropiedad::defaultSettings(),
    ]);
});

it('prevents non-superadmin from accessing licenses dashboard', function () {
    $this->actingAs($this->regularUser)
        ->get(route('superadmin.licenses.index'))
        ->assertForbidden();
});

it('allows superadmin to view licenses dashboard', function () {
    $this->actingAs($this->superAdmin)
        ->get(route('superadmin.licenses.index'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page->component('SuperAdmin/Licenses/Index'));
});

it('allows superadmin to update a license', function () {
    $data = [
        'plan' => 'pro',
        'license_status' => 'suspended',
        'payments_enabled' => true,
        'can_charge_online' => true,
        'pqrs_enabled' => false,
        'reservas_enabled' => true,
    ];

    $this->actingAs($this->superAdmin)
        ->post(route('superadmin.licenses.update', $this->copropiedad), $data)
        ->assertRedirect()
        ->assertSessionHas('success', "Licencia de {$this->copropiedad->nombre} actualizada correctamente.");

    $this->copropiedad->refresh();
    
    expect($this->copropiedad->plan)->toBe('pro');
    expect($this->copropiedad->license_status)->toBe('suspended');
    
    $settings = $this->copropiedad->settings;
    expect($settings['payments_enabled'])->toBeTrue();
    expect($settings['can_charge_online'])->toBeTrue();
    expect($settings['pqrs_enabled'])->toBeFalse();
    expect($settings['reservas_enabled'])->toBeTrue();
});

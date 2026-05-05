<?php

use App\Models\Copropiedad;
use App\Modules\IAM\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('a super admin can enable virtual asamblea for a copropiedad', function () {
    // 1. Setup: Super Admin and a Copropiedad
    $superAdmin = User::factory()->create(['role' => 'super_admin']);
    $copropiedad = Copropiedad::factory()->create();

    $this->actingAs($superAdmin);

    // 2. Act: Update settings to enable asamblea
    $response = $this->put(route('superadmin.copropiedades.update', $copropiedad), [
        'nombre' => $copropiedad->nombre,
        'nit' => $copropiedad->nit,
        'settings' => [
            'asamblea_virtual_enabled' => true
        ]
    ]);

    // 3. Assert: Verify redirect and database state
    $response->assertStatus(302);
    expect($copropiedad->refresh()->hasFeature('asamblea_virtual_enabled'))->toBeTrue();
});

test('a non-super admin cannot enable virtual asamblea', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $copropiedad = Copropiedad::factory()->create();

    $this->actingAs($admin);

    $response = $this->put(route('superadmin.copropiedades.update', $copropiedad), [
        'settings' => ['asamblea_virtual_enabled' => true]
    ]);

    $response->assertStatus(403);
});

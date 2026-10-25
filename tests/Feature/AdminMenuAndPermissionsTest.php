<?php

use App\Modules\IAM\Models\User;
use App\Modules\IAM\Models\Role;
use App\Modules\IAM\Models\Permission;
use App\Modules\Property\Models\Copropiedad;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('Admin Menu and Permissions RBAC Fallback', function () {
    beforeEach(function () {
        $this->copropiedad = Copropiedad::factory()->create([
            'settings' => [
                'asamblea_virtual_active' => true,
                'is_standalone' => false,
            ],
        ]);

        // Crear permisos y rol admin
        $adminRole = Role::firstOrCreate(['name' => 'admin'], ['display_name' => 'Admin']);
        $perms = [
            'finance:view', 'finance:manage',
            'pqrs:view', 'pqrs:manage',
            'reservations:view', 'reservations:manage',
            'property:manage', 'assembly:admin'
        ];

        foreach ($perms as $p) {
            $perm = Permission::firstOrCreate(['name' => $p], ['display_name' => $p]);
            $adminRole->permissions()->syncWithoutDetaching([$perm->id]);
        }
    });

    it('provides all admin permissions even if role_user pivot was not pre-populated', function () {
        // Usuario con columna role = 'admin', pero sin relación previa en iam.role_user
        $adminUser = User::factory()->create([
            'email' => 'test_admin_pivot@nexo.pro',
            'role' => 'admin',
            'current_copropiedad_id' => $this->copropiedad->id,
        ]);

        expect($adminUser->roles()->count())->toBe(0);

        $response = $this->actingAs($adminUser)->get(route('dashboard'));

        $response->assertStatus(200);
        $page = $response->viewData('page');
        $userProps = $page['props']['auth']['user'];

        expect($userProps['permissions'])
            ->toContain('finance:manage')
            ->toContain('property:manage')
            ->toContain('pqrs:manage')
            ->toContain('reservations:manage')
            ->toContain('assembly:admin');
    });
});

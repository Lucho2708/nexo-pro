<?php

namespace Database\Seeders;

use App\Modules\IAM\Models\Role;
use App\Modules\IAM\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['name' => 'super_admin', 'display_name' => 'Super Administrador', 'description' => 'Acceso total'],
            ['name' => 'admin', 'display_name' => 'Administrador PH', 'description' => 'Admin de propiedad'],
            ['name' => 'owner', 'display_name' => 'Propietario', 'description' => 'Acceso propietario'],
            ['name' => 'resident', 'display_name' => 'Residente', 'description' => 'Acceso residente'],
            ['name' => 'security', 'display_name' => 'Personal de Portería', 'description' => 'Control de garita y accesos'],
            ['name' => 'backoffice', 'display_name' => 'Comercial / Backoffice', 'description' => 'Gestión de prospectos y telemetría'],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(['name' => $role['name']], $role);
        }

        $permissions = [
            ['name' => 'finance:view', 'display_name' => 'Ver Cartera'],
            ['name' => 'finance:manage', 'display_name' => 'Gestionar Cartera'],
            ['name' => 'pqrs:view', 'display_name' => 'Ver PQRS'],
            ['name' => 'pqrs:manage', 'display_name' => 'Gestionar PQRS'],
            ['name' => 'reservations:view', 'display_name' => 'Ver Reservas'],
            ['name' => 'reservations:manage', 'display_name' => 'Gestionar Reservas'],
            ['name' => 'property:manage', 'display_name' => 'Gestionar Propiedad'],
            ['name' => 'assembly:admin', 'display_name' => 'Gestionar Asambleas'],
            ['name' => 'assembly:vote', 'display_name' => 'Votar en Asambleas'],
        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate(['name' => $permission['name']], $permission);
        }
        
        // Asignar permisos al admin
        $admin = Role::where('name', 'admin')->first();
        $adminPermissions = Permission::whereIn('name', [
            'finance:view', 'finance:manage', 
            'pqrs:view', 'pqrs:manage', 
            'reservations:view', 'reservations:manage',
            'property:manage', 'assembly:admin',
        ])->get();
        $admin->permissions()->sync($adminPermissions->pluck('id'));

        // Asignar permisos al propietario
        $owner = Role::where('name', 'owner')->first();
        if ($owner) {
            $ownerPermissions = Permission::whereIn('name', [
                'finance:view',
                'pqrs:view', 'pqrs:manage',
                'reservations:view', 'reservations:manage',
                'assembly:vote',
            ])->get();
            $owner->permissions()->sync($ownerPermissions->pluck('id'));
        }
    }
}

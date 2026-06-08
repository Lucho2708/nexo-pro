<?php

namespace App\Modules\IAM\Database\Seeders;

use Illuminate\Database\Seeder;
use App\Modules\IAM\Models\Role;
use App\Modules\IAM\Models\Permission;

class IAMPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Definir Permisos
        $permissions = [
            // Gestión de Inmuebles
            'property:manage' => 'Administrar unidades y copropiedad',
            'property:view' => 'Ver información del conjunto',
            
            // Finanzas
            'finance:manage' => 'Gestionar cartera y pagos',
            'finance:view' => 'Consultar estados de cuenta',
            
            // Asambleas
            'assembly:admin' => 'Moderar y gestionar asambleas',
            'assembly:vote' => 'Votar en asambleas',
            
            // Comunidad
            'pqrs:manage' => 'Responder y gestionar PQRS',
            'pqrs:create' => 'Crear peticiones y quejas',
            'reservations:manage' => 'Gestionar reservas de zonas comunes',
            'reservations:create' => 'Realizar reservas',
        ];

        foreach ($permissions as $name => $display) {
            Permission::updateOrCreate(['name' => $name], ['display_name' => $display]);
        }

        // 2. Definir Roles y asignar Permisos
        
        // ADMIN: Tiene casi todo (excepto lo del Super Admin)
        $admin = Role::updateOrCreate(['name' => 'admin'], [
            'display_name' => 'Administrador de Copropiedad',
            'description' => 'Responsable de la gestión diaria del conjunto'
        ]);
        $admin->permissions()->sync(Permission::pluck('id'));

        // OWNER: Perfil residente
        $owner = Role::updateOrCreate(['name' => 'owner'], [
            'display_name' => 'Propietario / Residente',
            'description' => 'Acceso al portal del propietario'
        ]);
        $owner->permissions()->sync(
            Permission::whereIn('name', [
                'property:view', 
                'finance:view', 
                'assembly:vote', 
                'pqrs:create', 
                'reservations:create'
            ])->pluck('id')
        );

        // CONTADOR (Ejemplo de rol granular)
        $accountant = Role::updateOrCreate(['name' => 'accountant'], [
            'display_name' => 'Contador',
            'description' => 'Solo acceso a módulos financieros'
        ]);
        $accountant->permissions()->sync(
            Permission::whereIn('name', ['finance:manage', 'finance:view'])->pluck('id')
        );
    }
}

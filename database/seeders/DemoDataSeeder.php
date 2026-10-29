<?php

namespace Database\Seeders;

use App\Modules\Operations\Models\Announcement;
use App\Modules\IAM\Models\User;
use App\Modules\IAM\Models\Role;
use App\Modules\Property\Models\Copropiedad;
use App\Modules\Property\Models\Unidad;
use App\Modules\Finance\Models\ConceptoCobro;
use App\Modules\Property\Models\ZonaComun;
use App\Modules\Finance\Models\Transaccion;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        $password = Hash::make('password');

        // Roles RBAC
        $adminRole = Role::where('name', 'admin')->first();
        $ownerRole = Role::where('name', 'owner')->first();

        // 1. Administrador que maneja varios conjuntos (2 conjuntos)
        $adminMulti = User::updateOrCreate(
            ['email' => 'admin_multi@nexo.pro'],
            [
                'name' => 'Administrador Multi-Conjunto',
                'password' => $password,
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );
        if ($adminRole) {
            $adminMulti->roles()->syncWithoutDetaching([$adminRole->id]);
        }

        // 2. Administrador que maneja 1 solo conjunto
        $adminSimple = User::updateOrCreate(
            ['email' => 'admin_simple@nexo.pro'],
            [
                'name' => 'Administrador Simple (1 Conjunto)',
                'password' => $password,
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );
        if ($adminRole) {
            $adminSimple->roles()->syncWithoutDetaching([$adminRole->id]);
        }

        // 3. Copropiedades requeridas para la matriz de pruebas
        $coproRoble = Copropiedad::firstOrCreate(
            ['nit' => '900.123.456-1'],
            [
                'nombre' => 'Residencial El Roble',
                'plan' => 'pro',
                'direccion' => 'Calle Falsa 123',
                'ciudad' => 'Bogotá',
                'unidades_totales' => 10,
                'torres' => 2,
            ]
        );

        $coproValle = Copropiedad::firstOrCreate(
            ['nit' => '900.789.012-2'],
            [
                'nombre' => 'Altos del Valle',
                'plan' => 'pro',
                'direccion' => 'Carrera 45 # 67-89',
                'ciudad' => 'Medellín',
                'unidades_totales' => 8,
                'torres' => 2,
            ]
        );

        $coproSabana = Copropiedad::firstOrCreate(
            ['nit' => '900.456.789-3'],
            [
                'nombre' => 'Portal de la Sabana',
                'plan' => 'pro',
                'direccion' => 'Avenida Principal # 12-34',
                'ciudad' => 'Chía',
                'unidades_totales' => 15,
                'torres' => 1,
            ]
        );

        // Asignación de conjuntos a administradores:
        // Admin Multi -> 2 conjuntos (Residencial El Roble y Altos del Valle)
        $adminMulti->managedCopropiedades()->syncWithoutDetaching([$coproRoble->id, $coproValle->id]);
        if (is_null($adminMulti->current_copropiedad_id)) {
            $adminMulti->current_copropiedad_id = $coproRoble->id;
            $adminMulti->save();
        }

        // Admin Simple -> 1 conjunto (Portal de la Sabana)
        $adminSimple->managedCopropiedades()->syncWithoutDetaching([$coproSabana->id]);
        if (is_null($adminSimple->current_copropiedad_id)) {
            $adminSimple->current_copropiedad_id = $coproSabana->id;
            $adminSimple->save();
        }

        // Datos operativos base para cada copropiedad (conceptos y amenidades)
        foreach ([$coproRoble, $coproValle, $coproSabana] as $copro) {
            ConceptoCobro::firstOrCreate(
                ['copropiedad_id' => $copro->id, 'codigo' => 'ADM'],
                [
                    'nombre' => 'Administración',
                    'descripcion' => 'Cuota de administración ordinaria',
                    'valor_fijo' => 150000,
                    'es_obligatorio' => true,
                ]
            );
            ConceptoCobro::firstOrCreate(
                ['copropiedad_id' => $copro->id, 'codigo' => 'PARQ'],
                [
                    'nombre' => 'Parqueadero',
                    'descripcion' => 'Canon mensual de parqueadero',
                    'valor_fijo' => 50000,
                    'es_obligatorio' => false,
                ]
            );
            ZonaComun::firstOrCreate(
                ['copropiedad_id' => $copro->id, 'nombre' => 'Salón Social'],
                ['activa' => true, 'costo' => 0, 'capacidad_maxima' => 50]
            );
            ZonaComun::firstOrCreate(
                ['copropiedad_id' => $copro->id, 'nombre' => 'Zona BBQ'],
                ['activa' => true, 'costo' => 0, 'capacidad_maxima' => 20]
            );
        }

        // 4. Creación de Unidades específicas para los Propietarios de Prueba
        // Unidad 1: Apto 101 en Residencial El Roble
        $unidadSimple101 = Unidad::firstOrCreate(
            [
                'copropiedad_id' => $coproRoble->id,
                'torre' => 'Torre A',
                'nombre' => '101',
            ],
            [
                'piso' => '1',
                'coeficiente' => 0.05000,
                'propietario_nombre' => 'Propietario Simple',
                'email_contacto' => 'propietario_simple@nexo.pro',
                'saldo_actual' => 0,
            ]
        );

        // Unidades para Propietario Multi (2 propiedades):
        // Unidad 2: Apto 102 en Residencial El Roble (Copropiedad 1)
        $unidadMulti102 = Unidad::firstOrCreate(
            [
                'copropiedad_id' => $coproRoble->id,
                'torre' => 'Torre A',
                'nombre' => '102',
            ],
            [
                'piso' => '1',
                'coeficiente' => 0.05000,
                'propietario_nombre' => 'Propietario Multi',
                'email_contacto' => 'propietario_multi@nexo.pro',
                'saldo_actual' => 150000,
            ]
        );

        // Unidad 3: Apto 201 en Altos del Valle (Copropiedad 2)
        $unidadMulti201 = Unidad::firstOrCreate(
            [
                'copropiedad_id' => $coproValle->id,
                'torre' => 'Torre B',
                'nombre' => '201',
            ],
            [
                'piso' => '2',
                'coeficiente' => 0.06250,
                'propietario_nombre' => 'Propietario Multi',
                'email_contacto' => 'propietario_multi@nexo.pro',
                'saldo_actual' => 0,
            ]
        );

        // 5. Usuario que tiene 1 sola propiedad
        $ownerSimple = User::updateOrCreate(
            ['email' => 'propietario_simple@nexo.pro'],
            [
                'name' => 'Propietario Simple (1 Propiedad)',
                'password' => $password,
                'role' => 'owner',
                'email_verified_at' => now(),
                'current_copropiedad_id' => $coproRoble->id,
            ]
        );
        if ($ownerRole) {
            $ownerSimple->roles()->syncWithoutDetaching([$ownerRole->id]);
        }
        $ownerSimple->unidades()->syncWithoutDetaching([
            $unidadSimple101->id => ['role' => 'owner'],
        ]);

        // 6. Usuario que tiene 2 propiedades (multi-propiedad y multi-copropiedad)
        $ownerMulti = User::updateOrCreate(
            ['email' => 'propietario_multi@nexo.pro'],
            [
                'name' => 'Propietario Multi-Propiedad (2 Propiedades)',
                'password' => $password,
                'role' => 'owner',
                'email_verified_at' => now(),
                'current_copropiedad_id' => $coproRoble->id,
            ]
        );
        if ($ownerRole) {
            $ownerMulti->roles()->syncWithoutDetaching([$ownerRole->id]);
        }
        $ownerMulti->unidades()->syncWithoutDetaching([
            $unidadMulti102->id => ['role' => 'owner'],
            $unidadMulti201->id => ['role' => 'owner'],
        ]);

        // 7. Personal de Garita / Portería
        $securityRole = Role::where('name', 'security')->first();

        $guardaRoble = User::updateOrCreate(
            ['email' => 'guarda@nexo.pro'],
            [
                'name' => 'Carlos Rodríguez (Guarda Titular)',
                'password' => $password,
                'role' => 'security',
                'email_verified_at' => now(),
                'current_copropiedad_id' => $coproRoble->id,
                'pin_porteria' => '4812',
                'is_active' => true,
            ]
        );
        if ($securityRole) {
            $guardaRoble->roles()->syncWithoutDetaching([$securityRole->id]);
        }

        $guardaRelevo = User::updateOrCreate(
            ['email' => 'guarda_relevo@nexo.pro'],
            [
                'name' => 'Nelson Gómez (Guarda Relevo)',
                'password' => $password,
                'role' => 'security',
                'email_verified_at' => now(),
                'current_copropiedad_id' => $coproRoble->id,
                'pin_porteria' => '9945',
                'is_active' => true,
            ]
        );
        if ($securityRole) {
            $guardaRelevo->roles()->syncWithoutDetaching([$securityRole->id]);
        }

        // Transacciones contables de demostración
        $conceptoAdmRoble = ConceptoCobro::where('copropiedad_id', $coproRoble->id)->where('codigo', 'ADM')->first();
        if ($conceptoAdmRoble) {
            if (Transaccion::where('unidad_id', $unidadSimple101->id)->doesntExist()) {
                Transaccion::factory()->create([
                    'unidad_id' => $unidadSimple101->id,
                    'concepto_id' => $conceptoAdmRoble->id,
                    'tipo' => 'abono',
                    'monto' => 150000,
                    'fecha' => now()->subDays(5),
                ]);
            }
            if (Transaccion::where('unidad_id', $unidadMulti102->id)->doesntExist()) {
                Transaccion::factory()->create([
                    'unidad_id' => $unidadMulti102->id,
                    'concepto_id' => $conceptoAdmRoble->id,
                    'tipo' => 'cargo',
                    'monto' => 150000,
                    'fecha' => now()->subDays(10),
                ]);
            }
        }

        // Anuncio informativo inicial
        Announcement::firstOrCreate(
            ['title' => 'Bienvenido a NEXO-PRO'],
            [
                'message' => 'Plataforma integral de gestión de propiedad horizontal activa y operativa.',
                'type' => 'info',
                'target_role' => 'all',
                'starts_at' => now(),
                'expires_at' => now()->addDays(30),
            ]
        );

        // Pre-aprobar términos legales para acceso inmediato sin bloqueos de onboarding
        $activeDocs = \App\Modules\IAM\Models\LegalDocument::where('is_active', true)->get();
        $targetEmails = [
            'admin_multi@nexo.pro',
            'admin_simple@nexo.pro',
            'propietario_simple@nexo.pro',
            'propietario_multi@nexo.pro',
            'guarda@nexo.pro',
            'guarda_relevo@nexo.pro',
        ];

        foreach (User::whereIn('email', $targetEmails)->get() as $demoUser) {
            foreach ($activeDocs as $doc) {
                \App\Modules\IAM\Models\LegalConsent::firstOrCreate([
                    'user_id' => $demoUser->id,
                    'legal_document_id' => $doc->id,
                    'version' => $doc->version,
                ], [
                    'accepted_at' => now(),
                    'ip_address' => '127.0.0.1',
                    'user_agent' => 'Seed/Demo',
                ]);
            }
        }
    }
}

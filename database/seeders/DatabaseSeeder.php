<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Copropiedad;
use App\Models\Unidad;
use App\Models\ConceptoCobro;
use App\Models\ZonaComun;
use App\Models\Reserva;
use App\Models\Transaccion;
use App\Models\Pqrs;
use App\Models\Announcement;
use App\Models\FeatureUsageLog;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $password = Hash::make('password');

        // 1. Super Administrador
        $superAdmin = new User([
            'name' => 'Super Admin Nexo',
            'email' => 'superadmin@nexo.pro',
            'password' => $password,
        ]);
        $superAdmin->role = 'super_admin';
        $superAdmin->save();

        // Anuncios Globales (Simulación)
        Announcement::create([
            'title' => 'Actualización de Plataforma 2.0',
            'message' => 'Hemos lanzado nuevas funcionalidades en el módulo de reservas y pagos en línea.',
            'type' => 'info',
            'target_role' => 'all',
            'starts_at' => now(),
            'expires_at' => now()->addDays(30),
        ]);

        // 2. Administrador que maneja 2 conjuntos
        $adminMulti = new User([
            'name' => 'Administrador Multi-Conjunto',
            'email' => 'admin_multi@nexo.pro',
            'password' => $password,
        ]);
        $adminMulti->role = 'admin';
        $adminMulti->save();

        // 3. Administrador que maneja 1 conjunto
        $adminSimple = new User([
            'name' => 'Administrador Simple',
            'email' => 'admin_simple@nexo.pro',
            'password' => $password,
        ]);
        $adminSimple->role = 'admin';
        $adminSimple->save();

        // 4. Copropiedades
        $conjuntos = [
            ['nombre' => 'Residencial El Roble', 'nit' => '900.123.456-1', 'admin' => $adminMulti],
            ['nombre' => 'Altos del Valle', 'nit' => '900.789.012-2', 'admin' => $adminMulti],
            ['nombre' => 'Portal de la Sabana', 'nit' => '900.456.789-3', 'admin' => $adminSimple],
        ];

        foreach ($conjuntos as $index => $data) {
            $copro = Copropiedad::factory()->create([
                'nombre' => $data['nombre'],
                'nit' => $data['nit'],
                'plan' => 'pro',
            ]);

            // Vincular al Admin correspondiente
            $admin = $data['admin'];
            $admin->managedCopropiedades()->attach($copro->id);
            
            // Setear current_copropiedad si no tiene
            if (is_null($admin->current_copropiedad_id)) {
                $admin->current_copropiedad_id = $copro->id;
                $admin->save();
            }

            // Datos Base para Operación de la Copropiedad
            $conceptoAdmin = ConceptoCobro::factory()->create(['copropiedad_id' => $copro->id, 'nombre' => 'Administración', 'codigo' => 'ADM']);
            $conceptoParq = ConceptoCobro::factory()->create(['copropiedad_id' => $copro->id, 'nombre' => 'Parqueadero', 'codigo' => 'PARQ']);
            $zonaComun1 = ZonaComun::factory()->create(['copropiedad_id' => $copro->id, 'nombre' => 'Salón Social', 'activa' => true]);
            $zonaComun2 = ZonaComun::factory()->create(['copropiedad_id' => $copro->id, 'nombre' => 'Zona BBQ', 'activa' => true]);

            // Crear Unidades y Propietarios
            for ($i = 1; $i <= 5; $i++) {
                // Identificadores limpios por torre/piso sin colisión
                $torre = $i <= 3 ? 'Torre A' : 'Torre B';
                $piso = rand(1, 5);
                $numeroApto = $piso . '0' . $i;
                
                $unidad = Unidad::factory()->create([
                    'copropiedad_id' => $copro->id,
                    'nombre' => $numeroApto,
                    'torre' => $torre,
                    'piso' => $piso,
                    'saldo_actual' => rand(0, 1) ? 0 : rand(50000, 300000), // Algunos con mora, otros al día
                ]);

                // Propietario (Owner)
                $owner = new User([
                    'name' => 'Propietario ' . $torre . ' ' . $numeroApto,
                    'email' => 'propietario' . $numeroApto . '_' . $index . '_' . $i . '@nexo.pro',
                    'password' => $password,
                ]);
                $owner->role = 'owner';
                $owner->current_copropiedad_id = $copro->id;
                $owner->save();

                // Vincular propietario a la unidad
                $owner->unidades()->attach($unidad->id, ['role' => 'owner']);

                // Generar Transacciones (Pagos) para la unidad
                Transaccion::factory()->count(2)->create([
                    'unidad_id' => $unidad->id,
                    'concepto_id' => $conceptoAdmin->id,
                    'tipo' => 'abono',
                    'monto' => 150000,
                    'fecha' => now()->subDays(rand(1, 30)),
                ]);

                // Generar Reservas (Aleatorias a futuro y pasado)
                Reserva::factory()->create([
                    'zona_id' => rand(0,1) ? $zonaComun1->id : $zonaComun2->id,
                    'user_id' => $owner->id,
                    'unidad_id' => $unidad->id,
                    'fecha' => now()->addDays(rand(-5, 10))->toDateString(),
                    'estado' => rand(0, 1) ? 'aprobada' : 'pendiente',
                ]);

                // Generar PQRS (Casos de atención al cliente)
                Pqrs::factory()->create([
                    'unidad_id' => $unidad->id,
                    'user_id' => $owner->id,
                    'asunto' => 'Inconveniente en ' . $zonaComun1->nombre,
                    'estado' => rand(0, 1) ? 'abierto' : 'cerrado',
                ]);
            }

            // Simular logs de uso del sistema (Audit/Analytics)
            FeatureUsageLog::create([
                'user_id' => $admin->id,
                'copropiedad_id' => $copro->id,
                'feature' => 'dashboard',
                'used_at' => now(),
            ]);
            FeatureUsageLog::create([
                'user_id' => $admin->id,
                'copropiedad_id' => $copro->id,
                'feature' => 'cartera',
                'used_at' => now()->subHours(2),
            ]);
        }
    }
}

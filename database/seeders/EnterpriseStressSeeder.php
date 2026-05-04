<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Copropiedad;
use App\Models\Unidad;
use App\Models\Asamblea;
use App\Models\Pregunta;
use App\Models\Opcion;
use App\Models\FeatureUsageLog;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class EnterpriseStressSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('es_ES');
        $startTime = microtime(true);
        $password = Hash::make('password');

        $this->command->info('🚀 Iniciando Inundación de Datos Enterprise...');

        // 1. Crear Super Admin si no existe
        $superAdmin = User::firstOrCreate(
            ['email' => 'superadmin@nexo.pro'],
            [
                'name' => 'Super Admin Pro',
                'password' => $password,
                'role' => 'super_admin'
            ]
        );

        // 2. Crear 5 Copropiedades Gigantes
        $copropiedades = [];
        for ($i = 0; $i < 5; $i++) {
            $copropiedades[] = Copropiedad::create([
                'nombre' => $faker->company . ' Residencial',
                'nit' => rand(800, 999) . '.' . rand(100, 999) . '.' . rand(100, 999) . '-' . rand(0, 9),
                'direccion' => $faker->address,
                'ciudad' => $faker->city,
                'plan' => 'enterprise',
                'license_status' => 'active',
                'settings' => Copropiedad::defaultSettings()
            ]);
        }

        // 3. Generar Usuarios (5,000 Propietarios)
        $this->command->info('👥 Generando 5,000 usuarios...');
        $users = [];
        $batchSize = 1000;
        for ($i = 0; $i < 5000; $i++) {
            $users[] = [
                'id' => (string) Str::uuid(),
                'name' => $faker->name,
                'email' => "user{$i}_" . Str::random(5) . "@nexo.pro",
                'password' => $password,
                'role' => 'owner',
                'current_copropiedad_id' => $copropiedades[array_rand($copropiedades)]->id,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            if (count($users) === $batchSize) {
                DB::table('users')->insert($users);
                $users = [];
            }
        }

        // 4. Generar 12,500 Unidades
        $this->command->info('🏢 Generando 12,500 unidades...');
        $allUserIds = DB::table('users')->where('role', 'owner')->pluck('id')->toArray();
        
        foreach ($copropiedades as $copro) {
            $units = [];
            for ($i = 1; $i <= 2500; $i++) {
                $units[] = [
                    'id' => (string) Str::uuid(),
                    'copropiedad_id' => $copro->id,
                    'nombre' => (string) (1000 + $i),
                    'torre' => 'Torre ' . ceil($i / 500),
                    'piso' => ceil(($i % 500) / 20),
                    'propietario_nombre' => $faker->name,
                    'coeficiente' => 0.0004,
                    'saldo_actual' => rand(0, 1000000),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                if (count($units) === 500) {
                    DB::table('unidades')->insert($units);
                    $units = [];
                }
            }
        }

        // 5. Generar Conceptos de Cobro y 100,000 Transacciones
        $this->command->info('💰 Generando 100,000 transacciones...');
        
        $conceptos = [];
        foreach ($copropiedades as $copro) {
            $id = (string) Str::uuid();
            DB::table('concepto_cobros')->insert([
                'id' => $id,
                'copropiedad_id' => $copro->id,
                'nombre' => 'Cuota de Administración',
                'codigo' => 'ADM-001',
                'valor_fijo' => 250000,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $conceptos[$copro->id] = $id;
        }

        $unitsWithCopro = DB::table('unidades')->select('id', 'copropiedad_id')->get();
        $transactions = [];
        for ($i = 0; $i < 100000; $i++) {
            $unit = $unitsWithCopro->random();
            $transactions[] = [
                'id' => (string) Str::uuid(),
                'unidad_id' => $unit->id,
                'concepto_id' => $conceptos[$unit->copropiedad_id],
                'tipo' => rand(0, 1) ? 'cargo' : 'abono',
                'monto' => rand(50000, 500000),
                'fecha' => now()->subDays(rand(1, 365)),
                'referencia' => 'PAG-' . Str::upper(Str::random(8)),
                'created_at' => now(),
                'updated_at' => now(),
            ];

            if (count($transactions) === 2000) {
                DB::table('transacciones')->insert($transactions);
                $transactions = [];
            }
        }

        // 6. Generar Asambleas y 50,000 Votos
        $this->command->info('🗳️ Generando 50,000 votos...');
        foreach ($copropiedades as $copro) {
            $asamblea = Asamblea::create([
                'copropiedad_id' => $copro->id,
                'titulo' => 'Asamblea General Ordinaria ' . now()->year,
                'fecha' => now(),
                'status' => 'in_progress'
            ]);

            $pregunta = Pregunta::create([
                'asamblea_id' => $asamblea->id,
                'titulo' => '¿Aprueba el presupuesto del año actual?',
                'descripcion' => 'Votación masiva de prueba',
                'status' => 'active'
            ]);

            $opciones = [];
            $opciones[] = Opcion::create(['pregunta_id' => $pregunta->id, 'titulo' => 'Sí']);
            $opciones[] = Opcion::create(['pregunta_id' => $pregunta->id, 'titulo' => 'No']);

            $votos = [];
            $unitsInCopro = DB::table('unidades')->where('copropiedad_id', $copro->id)->pluck('id')->toArray();
            
            $this->command->info("   -> Insertando " . count($unitsInCopro) . " votos para {$copro->nombre}");
            
            foreach ($unitsInCopro as $uId) {
                $votos[] = [
                    'id' => (string) Str::uuid(),
                    'pregunta_id' => $pregunta->id,
                    'opcion_id' => $opciones[array_rand($opciones)]->id,
                    'unidad_id' => $uId,
                    'user_id' => $allUserIds[array_rand($allUserIds)],
                    'peso' => 1.0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                if (count($votos) === 500) {
                    DB::table('votos')->insert($votos);
                    $votos = [];
                }
            }
            if (!empty($votos)) {
                DB::table('votos')->insert($votos);
            }
        }

        // 7. Generar 200,000 Logs (Prueba de GIN Index)
        $this->command->info('📜 Generando 200,000 logs de auditoría...');
        $logs = [];
        for ($i = 0; $i < 200000; $i++) {
            $logs[] = [
                'id' => (string) Str::uuid(),
                'user_id' => $allUserIds[array_rand($allUserIds)],
                'copropiedad_id' => $copropiedades[array_rand($copropiedades)]->id,
                'feature' => ['dashboard', 'cartera', 'votos', 'asamblea', 'reservas'][array_rand(['dashboard', 'cartera', 'votos', 'asamblea', 'reservas'])],
                'used_at' => now()->subMinutes(rand(1, 100000)),
            ];

            if (count($logs) === 5000) {
                DB::table('feature_usage_logs')->insert($logs);
                $logs = [];
            }
        }

        // 8. Generar 50,000 registros de Telemetría (System Metrics)
        $this->command->info('📊 Generando 50,000 registros de telemetría...');
        $metrics = [];
        for ($i = 0; $i < 50000; $i++) {
            $metrics[] = [
                'id' => (string) Str::uuid(),
                'path' => $faker->randomElement(['/api/v1/votos', '/api/v1/cartera', '/dashboard', '/asambleas/show']),
                'method' => $faker->randomElement(['GET', 'POST']),
                'status_code' => $faker->randomElement([200, 200, 200, 201, 400, 404, 500]),
                'latency_ms' => rand(15, 250),
                'created_at' => now()->subMinutes(rand(1, 43200)), // Último mes
                'updated_at' => now(),
            ];

            if (count($metrics) === 5000) {
                DB::table('system_metrics')->insert($metrics);
                $metrics = [];
            }
        }

        $endTime = microtime(true);
        $duration = round($endTime - $startTime, 2);
        $this->command->info("✅ Inundación completada en {$duration} segundos.");
    }
}

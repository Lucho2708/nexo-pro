<?php

namespace Database\Seeders;

use App\Models\Copropiedad;
use App\Modules\IAM\Models\User;
use App\Models\Unidad;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class StressTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plans = ['basic', 'pro', 'enterprise'];
        $adminData = [
            ['name' => 'Admin Basic', 'email' => 'admin.basic@nexo.com', 'conjunto' => 'Residencial El Valle'],
            ['name' => 'Admin Pro', 'email' => 'admin.pro@nexo.com', 'conjunto' => 'Torres del Horizonte'],
            ['name' => 'Admin Enterprise', 'email' => 'admin.ent@nexo.com', 'conjunto' => 'Club House Premium'],
        ];

        foreach ($adminData as $index => $data) {
            $plan = $plans[$index];

            // 1. Crear Copropiedad
            $copropiedad = Copropiedad::create([
                'nit' => '900' . rand(100000, 999999) . '-' . $index,
                'nombre' => $data['conjunto'],
                'direccion' => 'Calle ' . rand(10, 99) . ' # ' . rand(10, 99),
                'ciudad' => 'Medellín',
                'plan' => $plan,
                'unidades_totales' => 1000,
                'torres' => 5,
            ]);

            // 2. Crear Administrador
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make('password'),
                'current_copropiedad_id' => $copropiedad->id,
                'role' => 'admin',
            ]);

            // 3. Vincular Admin
            $user->managedCopropiedades()->attach($copropiedad->id);

            // 4. Generar 1,000 Unidades masivamente (Batch Insert)
            $units = [];
            $batchSize = 250; // Para no saturar el buffer de la DB
            
            for ($i = 0; $i < 1000; $i++) {
                $units[] = [
                    'copropiedad_id' => $copropiedad->id,
                    'nombre' => (string)(100 + $i),
                    'torre' => ceil(($i + 1) / 200), // 5 torres de 200 unidades cada una
                    'piso' => ceil((($i % 200) + 1) / 10), // 20 pisos por torre, 10 aptos por piso
                    'coeficiente' => 0.001,
                    'propietario_nombre' => 'Propietario Apto ' . (100 + $i),
                    'propietario_identificacion' => 'ID-' . rand(1000000, 9999999),
                    'email_contacto' => 'apto' . (100 + $i) . '@ejemplo.com',
                    'saldo_actual' => rand(0, 500000),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                if (count($units) === $batchSize) {
                    DB::table('unidades')->insert($units);
                    $units = [];
                }
            }

            // Insertar remanentes
            if (count($units) > 0) {
                DB::table('unidades')->insert($units);
            }

            $this->command->info("Copropiedad '{$copropiedad->nombre}' poblada con 1,000 unidades (Plan: {$plan})");
        }
    }
}

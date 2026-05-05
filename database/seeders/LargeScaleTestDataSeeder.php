<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Modules\IAM\Models\User;
use App\Models\Copropiedad;
use App\Models\Unidad;
use App\Models\ZonaComun;
use App\Models\Reserva;
use App\Models\Transaccion;
use App\Models\Pqrs;
use App\Models\ConceptoCobro;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class LargeScaleTestDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create 3 Administrators
        $admins = User::factory()->count(3)->create([
            'role' => 'admin',
            'password' => Hash::make('password'),
        ]);

        $mainAdmin = $admins->first();

        // 2. Main Admin has 3 Conjuntos (Copropiedades)
        $copropiedades = Copropiedad::factory()->count(3)->create();
        
        // Link them to the admin via pivot table if necessary (managedCopropiedades)
        foreach ($copropiedades as $copro) {
            $mainAdmin->managedCopropiedades()->attach($copro->id);
        }

        foreach ($copropiedades as $copro) {
            $this->command->info("Generating data for: {$copro->nombre}");

            // 3. 1000 Units per Conjunto (Apartments starting from 101)
            $unitsData = [];
            for ($i = 1; $i <= 1000; $i++) {
                $floor = ceil($i / 10); // 10 units per floor
                $roomNumber = str_pad($i % 10 == 0 ? 10 : $i % 10, 2, '0', STR_PAD_LEFT);
                $name = "{$floor}{$roomNumber}";
                
                $unitsData[] = [
                    'id' => (string) Str::uuid(),
                    'copropiedad_id' => $copro->id,
                    'nombre' => $name,
                    'torre' => 'Torre ' . ceil($i / 250), // 4 towers
                    'piso' => $floor,
                    'coeficiente' => 0.1,
                    'propietario_nombre' => 'Propietario ' . $name,
                    'propietario_identificacion' => 'ID' . $i . rand(1000, 9999),
                    'email_contacto' => "unidad{$name}_{$copro->id}@example.com",
                    'saldo_actual' => rand(0, 500000),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                if (count($unitsData) >= 200) {
                    Unidad::insert($unitsData);
                    $unitsData = [];
                }
            }
            if (!empty($unitsData)) {
                Unidad::insert($unitsData);
            }

            $allUnits = Unidad::where('copropiedad_id', $copro->id)->get();

            // 4. Common Zones
            $zonas = ZonaComun::factory()->count(5)->create([
                'copropiedad_id' => $copro->id
            ]);

            // 5. Default Concepts for Transactions
            $conceptos = ConceptoCobro::factory()->count(3)->create([
                'copropiedad_id' => $copro->id
            ]);

            // 6. Create some Owners (Users) and link them
            $owners = User::factory()->count(100)->create([
                'role' => 'owner',
                'current_copropiedad_id' => $copro->id
            ]);

            foreach ($owners as $owner) {
                $randomUnits = $allUnits->random(rand(1, 2));
                $owner->unidades()->attach($randomUnits->pluck('id')->toArray(), ['role' => 'propietario']);
            }

            // 7. Transactions (Payments and Charges)
            foreach ($allUnits->random(300) as $unit) {
                Transaccion::factory()->count(rand(2, 5))->create([
                    'unidad_id' => $unit->id,
                    'concepto_id' => $conceptos->random()->id,
                ]);
            }

            // 8. Reservations
            foreach ($owners->random(50) as $owner) {
                $unit = $owner->unidades()->first();
                if ($unit) {
                    Reserva::factory()->count(rand(1, 3))->create([
                        'user_id' => $owner->id,
                        'unidad_id' => $unit->id,
                        'zona_id' => $zonas->random()->id,
                    ]);
                }
            }

            // 9. PQRs (Reported, Managed, Reopened)
            // Reported (abierto/en_proceso)
            foreach ($allUnits->random(30) as $unit) {
                Pqrs::factory()->create([
                    'unidad_id' => $unit->id,
                    'user_id' => $owners->random()->id,
                    'estado' => rand(0, 1) ? 'abierto' : 'en_proceso'
                ]);
            }

            // Managed (cerrado)
            foreach ($allUnits->random(40) as $unit) {
                Pqrs::factory()->create([
                    'unidad_id' => $unit->id,
                    'user_id' => $owners->random()->id,
                    'estado' => 'cerrado',
                    'respuesta' => 'Su solicitud ha sido gestionada satisfactoriamente.',
                    'fecha_respuesta' => now()->subDays(rand(1, 10))
                ]);
            }

            // Reopened (reabierto)
            foreach ($allUnits->random(15) as $unit) {
                Pqrs::factory()->create([
                    'unidad_id' => $unit->id,
                    'user_id' => $owners->random()->id,
                    'estado' => 'reabierto',
                    'mensaje' => 'No estoy de acuerdo con la respuesta inicial, solicito revisión.'
                ]);
            }
        }
    }
}

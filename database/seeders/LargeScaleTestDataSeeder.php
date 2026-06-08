<?php

namespace Database\Seeders;

use App\Modules\IAM\Models\User;
use App\Modules\Property\Models\Copropiedad;
use App\Modules\Property\Models\Unidad;
use App\Modules\Property\Models\TipoUnidad;
use App\Modules\Property\Models\ZonaComun;
use App\Modules\Property\Services\CoeficienteCalculator;
use App\Modules\Finance\Models\ConceptoCobro;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LargeScaleTestDataSeeder extends Seeder
{
    public function run(): void
    {
        $password = Hash::make('password');
        $calculator = new CoeficienteCalculator();
        $totalCopropiedades = 40;
        $unidadesPorCopropiedad = 300;

        $this->command->info("Iniciando población de {$totalCopropiedades} conjuntos...");

        for ($c = 1; $c <= $totalCopropiedades; $c++) {
            DB::transaction(function () use ($c, $password, $calculator, $unidadesPorCopropiedad) {
                // 1. Crear Copropiedad
                $copro = Copropiedad::create([
                    'nit' => "900." . str_pad($c, 6, '0', STR_PAD_LEFT) . "-1",
                    'nombre' => "Mega Conjunto Residencial " . Str::upper(Str::random(3)) . " $c",
                    'direccion' => "Calle " . rand(1, 200) . " # " . rand(1, 100) . "-" . rand(1, 99),
                    'ciudad' => 'Bogotá',
                    'plan' => 'pro',
                    'area_construida_total' => 15000, // 15k m2 base
                    'settings' => array_merge(Copropiedad::defaultSettings(), [
                        'asamblea_virtual_enabled' => true,
                        'payments_enabled' => true
                    ])
                ]);

                // 2. Crear Administrador para este conjunto
                $admin = User::create([
                    'name' => "Administrador Conjunto $c",
                    'email' => "admin$c@nexo.pro",
                    'password' => $password,
                    'role' => 'admin',
                    'current_copropiedad_id' => $copro->id,
                    'terms_accepted_at' => now(),
                ]);
                $admin->managedCopropiedades()->attach($copro->id);

                // 3. Crear Concepto de Administración
                ConceptoCobro::create([
                    'copropiedad_id' => $copro->id,
                    'nombre' => 'Administración Mensual',
                    'codigo' => 'ADM',
                    'es_obligatorio' => true
                ]);

                // 4. Crear un Tipo de Unidad base
                $tipo = TipoUnidad::create([
                    'copropiedad_id' => $copro->id,
                    'nombre' => 'Apartamento Estándar',
                    'area_m2' => 50.00 // Todos iguales para simplificar el cálculo masivo inicial
                ]);

                // 5. Inserción Masiva de Unidades (Optimizado)
                $unidadesData = [];
                for ($u = 1; $u <= $unidadesPorCopropiedad; $u++) {
                    $torreNum = ceil($u / 50); // 6 torres (A-F)
                    $torre = chr(64 + $torreNum); // A, B, C...
                    $piso = ceil(($u % 50 ?: 50) / 5); // 10 pisos
                    $apto = str_pad($u, 3, '0', STR_PAD_LEFT);

                    $unidadesData[] = [
                        'id' => Str::uuid(),
                        'copropiedad_id' => $copro->id,
                        'tipo_unidad_id' => $tipo->id,
                        'nombre' => $apto,
                        'torre' => "Torre $torre",
                        'piso' => $piso,
                        'coeficiente' => 0,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
                Unidad::insert($unidadesData);

                // 6. Calcular Coeficientes Reales para asegurar el 100%
                $copro->update(['area_construida_total' => $unidadesPorCopropiedad * 50.00]);
                $calculator->calculateForCopropiedad($copro);
            });

            $this->command->info("Conjunto $c/40 completado (300 unidades).");
        }

        $this->command->info("¡Población a gran escala finalizada exitosamente!");
    }
}

<?php

namespace Database\Seeders;

use App\Models\Copropiedad;
use App\Models\User;
use App\Models\Unidad;
use App\Models\Transaccion;
use App\Models\ConceptoCobro;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FinancialStressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $copropiedades = Copropiedad::all();
        $baseFee = 250000;

        foreach ($copropiedades as $copropiedad) {
            $this->command->info("Procesando financiera para: {$copropiedad->nombre}...");

            // 1. Crear concepto de cobro si no existe
            $concepto = ConceptoCobro::firstOrCreate(
                ['copropiedad_id' => $copropiedad->id, 'codigo' => 'ADMIN'],
                [
                    'nombre' => 'Cuota de Administración',
                    'descripcion' => 'Cobro mensual ordinario',
                    'valor_fijo' => $baseFee,
                    'es_obligatorio' => true
                ]
            );

            $unidades = Unidad::where('copropiedad_id', $copropiedad->id)->get();
            
            // Resetear saldos antes de poblar para evitar acumulación
            Unidad::where('copropiedad_id', $copropiedad->id)->update(['saldo_actual' => 0]);
            
            $batchTransactions = [];
            $batchSize = 200;

            foreach ($unidades as $index => $unidad) {
                // Determinar estado basado en el índice (0-299 Mora, 300-699 Al Día, 700-999 Parcial)
                $type = $index < 300 ? 'mora' : ($index < 700 ? 'al_dia' : 'parcial');

                // A. Siempre hay un CARGO (Deuda)
                $batchTransactions[] = [
                    'unidad_id' => $unidad->id,
                    'concepto_id' => $concepto->id,
                    'tipo' => 'cargo',
                    'monto' => $baseFee,
                    'fecha' => now(),
                    'referencia' => 'COB-' . $unidad->nombre . '-ABR',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                $saldo = $baseFee;

                // B. Aplicar ABONOS según el tipo
                if ($type === 'al_dia') {
                    $batchTransactions[] = [
                        'unidad_id' => $unidad->id,
                        'concepto_id' => $concepto->id,
                        'tipo' => 'abono',
                        'monto' => $baseFee,
                        'fecha' => now(),
                        'referencia' => 'PAG-FULL-' . $unidad->nombre,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                    $saldo = 0;
                } elseif ($type === 'parcial') {
                    $pagoParcial = 150000;
                    $batchTransactions[] = [
                        'unidad_id' => $unidad->id,
                        'concepto_id' => $concepto->id,
                        'tipo' => 'abono',
                        'monto' => $pagoParcial,
                        'fecha' => now(),
                        'referencia' => 'PAG-PARC-' . $unidad->nombre,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                    $saldo = $baseFee - $pagoParcial;
                }

                // Actualizar saldo en la unidad (Esto es pesado pero necesario para el dashboard)
                $unidad->update(['saldo_actual' => $saldo]);

                if (count($batchTransactions) >= $batchSize) {
                    DB::table('transacciones')->insert($batchTransactions);
                    $batchTransactions = [];
                }
            }

            // Insertar remanentes
            if (count($batchTransactions) > 0) {
                DB::table('transacciones')->insert($batchTransactions);
            }
        }
    }
}

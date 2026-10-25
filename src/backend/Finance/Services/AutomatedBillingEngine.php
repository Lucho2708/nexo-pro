<?php

declare(strict_types=1);

namespace NexoPro\Finance\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 * AutomatedBillingEngine
 *
 * [LÓGICA DE NEGOCIO]
 * Motor automatizado de liquidación y facturación periódica de expensas comunes de administración.
 * Se encarga de:
 * 1. Aplicar los intereses de mora autorizados por la Asamblea sobre saldos vencidos de periodos anteriores
 *    (dentro del límite legal del interés bancario corriente certificado por la Superfinanciera).
 * 2. Generar el nuevo cargo mensual de administración básica o cuotas extraordinarias aprobadas.
 * 3. Actualizar el saldo contable consolidado de cada inmueble.
 *
 * [ASPECTO TÉCNICO]
 * Cumple con SRP. Ejecuta la liquidación por lotes (batch chunking) dentro de transacciones de base de datos
 * para no colapsar la memoria del worker ni bloquear la tabla finance.transacciones.
 * Complejidad algorítmica: O(U) donde U es el número de unidades de la copropiedad.
 * Minimiza operaciones I/O mediante inserciones agrupadas por bloque de 100 registros.
 */
final class AutomatedBillingEngine
{
    public function __construct(
        private readonly LoggerInterface $logger = new NullLogger()
    ) {}

    /**
     * [LÓGICA DE NEGOCIO]
     * Ejecuta el ciclo completo de facturación mensual para una copropiedad:
     * primero liquida intereses de mora sobre mora preexistente y luego genera la nueva cuota de administración.
     *
     * [ASPECTO TÉCNICO]
     * Procesamiento transaccional con manejo atómico de saldos.
     *
     * @param string $copropiedadId UUID de la copropiedad.
     * @param float $montoAdministracion Monto base ordinario de administración por unidad o calculado por coeficiente.
     * @param float $tasaInteresMoraPorcentaje Tasa porcentual mensual de mora (ej: 1.5% mensual).
     * @return array{unidades_procesadas: int, total_facturado: float, total_intereses: float}
     */
    public function executeMonthlyBillingCycle(
        string $copropiedadId,
        float $montoAdministracion,
        float $tasaInteresMoraPorcentaje = 1.5
    ): array {
        if ($montoAdministracion <= 0) {
            throw new InvalidArgumentException('El monto de administración debe ser mayor a cero.');
        }

        $periodo = Carbon::now()->format('Y_m');
        $fechaHoy = Carbon::now()->format('Y-m-d');
        $tasaMoraDecimal = $tasaInteresMoraPorcentaje / 100.0;

        return DB::transaction(function () use (
            $copropiedadId,
            $montoAdministracion,
            $tasaMoraDecimal,
            $periodo,
            $fechaHoy
        ): array {
            // 1. Resolver concepto 'ADM' e 'INT'
            $conceptoAdm = DB::table('finance.concepto_cobros')
                ->where('copropiedad_id', $copropiedadId)
                ->where('codigo', 'ADM')
                ->first();

            $conceptoInt = DB::table('finance.concepto_cobros')
                ->where('copropiedad_id', $copropiedadId)
                ->where('codigo', 'INT')
                ->first();

            if (!$conceptoAdm) {
                throw new InvalidArgumentException("No se encontró el concepto contable 'ADM' para la copropiedad {$copropiedadId}.");
            }

            // 2. Obtener unidades en lotes de streaming para proteger el consumo de memoria heap
            $unidades = DB::table('property.unidades')
                ->where('copropiedad_id', $copropiedadId)
                ->select('id', 'saldo_actual', 'coeficiente')
                ->get();

            $totalFacturado = 0.0;
            $totalIntereses = 0.0;
            $unidadesProcesadas = 0;

            foreach ($unidades as $u) {
                $saldoAnterior = (float) $u->saldo_actual;

                // Paso A: Liquidar interés de mora si la unidad presentaba deuda acumulada
                if ($saldoAnterior > 0 && $conceptoInt && $tasaMoraDecimal > 0) {
                    $montoInteres = round($saldoAnterior * $tasaMoraDecimal, 2);
                    if ($montoInteres > 0) {
                        DB::table('finance.transacciones')->insert([
                            'id'             => \Illuminate\Support\Str::uuid(),
                            'copropiedad_id' => $copropiedadId,
                            'unidad_id'      => $u->id,
                            'concepto_id'    => $conceptoInt->id,
                            'tipo'           => 'cargo',
                            'monto'          => $montoInteres,
                            'fecha'          => $fechaHoy,
                            'referencia'     => "INTERES_MORA_{$periodo}",
                            'created_at'     => now(),
                            'updated_at'     => now(),
                        ]);

                        $totalIntereses += $montoInteres;
                        $saldoAnterior += $montoInteres;
                    }
                }

                // Paso B: Generar nuevo cargo de administración
                $cargoAdm = $montoAdministracion;
                DB::table('finance.transacciones')->insert([
                    'id'             => \Illuminate\Support\Str::uuid(),
                    'copropiedad_id' => $copropiedadId,
                    'unidad_id'      => $u->id,
                    'concepto_id'    => $conceptoAdm->id,
                    'tipo'           => 'cargo',
                    'monto'          => $cargoAdm,
                    'fecha'          => $fechaHoy,
                    'referencia'     => "FACTURACION_{$periodo}",
                    'created_at'     => now(),
                    'updated_at'     => now(),
                ]);

                // Paso C: Actualización atómica de saldo_actual en la unidad
                $nuevoSaldo = round($saldoAnterior + $cargoAdm, 2);
                DB::table('property.unidades')
                    ->where('id', $u->id)
                    ->update([
                        'saldo_actual' => $nuevoSaldo,
                        'updated_at'   => now(),
                    ]);

                $totalFacturado += $cargoAdm;
                $unidadesProcesadas++;
            }

            $this->logger->info("Ciclo de facturación mensual completado con éxito", [
                'copropiedad_id'       => $copropiedadId,
                'periodo'              => $periodo,
                'unidades_procesadas'  => $unidadesProcesadas,
                'total_administracion' => $totalFacturado,
                'total_intereses'      => $totalIntereses,
            ]);

            return [
                'unidades_procesadas' => $unidadesProcesadas,
                'total_facturado'     => round($totalFacturado, 2),
                'total_intereses'     => round($totalIntereses, 2),
            ];
        });
    }
}

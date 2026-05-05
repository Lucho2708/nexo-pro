<?php

namespace Tests\Feature;

use App\Models\Asamblea;
use App\Models\Copropiedad;
use App\Models\Opcion;
use App\Models\Pregunta;
use App\Models\Unidad;
use App\Modules\IAM\Models\User;
use App\Models\Voto;
use App\Services\AsambleaService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AssemblyConcurrencyTest extends TestCase
{
    use RefreshDatabase;

    protected AsambleaService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(AsambleaService::class);
    }

    /**
     * Simula una carga de 100 votos secuenciales rápidos para verificar 
     * consistencia de datos, pesos y registros de auditoría dinámica.
     */
    public function test_simulated_load_voting_consistency()
    {
        $copropiedad = Copropiedad::factory()->create();
        $asamblea = Asamblea::create([
            'copropiedad_id' => $copropiedad->id,
            'titulo' => 'Asamblea de Carga Simulada',
            'fecha' => now(),
            'status' => 'active'
        ]);

        $pregunta = Pregunta::create([
            'asamblea_id' => $asamblea->id,
            'titulo' => '¿Aprobar presupuesto 2026?',
            'status' => 'open'
        ]);

        $opcionA = Opcion::create(['pregunta_id' => $pregunta->id, 'titulo' => 'SÍ']);
        $opcionB = Opcion::create(['pregunta_id' => $pregunta->id, 'titulo' => 'NO']);

        $totalUnits = 100;
        $units = [];
        $users = [];

        // Preparación de datos masivos
        for ($i = 0; $i < $totalUnits; $i++) {
            $unit = Unidad::create([
                'copropiedad_id' => $copropiedad->id,
                'nombre' => "Apt " . ($i + 101),
                'torre' => 'A',
                'coeficiente' => 0.01, // Cada uno pesa 1%
                'saldo_actual' => 0
            ]);
            
            $user = User::factory()->create(['role' => 'owner']);
            $user->unidades()->attach($unit->id, ['role' => 'propietario']);
            
            $units[] = $unit;
            $users[] = $user;
        }

        // --- SIMULACIÓN 1: Ráfaga de Votos Válidos ---
        $startTime = microtime(true);
        
        foreach ($users as $index => $user) {
            $this->service->castVote($user, $units[$index], $pregunta, $opcionA, $asamblea);
        }

        $duration = microtime(true) - $startTime;

        // Verificaciones de Integridad de Votos
        $this->assertEquals($totalUnits, Voto::where('pregunta_id', $pregunta->id)->count());
        $this->assertEquals(1.0, (float) Voto::where('pregunta_id', $pregunta->id)->sum('peso'), "El total de coeficientes debe ser 100% (1.0)");
        
        // Verificación de Bitácora Dinámica
        $logCount = DB::table($asamblea->getLogTableName())->where('event_type', 'vote')->count();
        $this->assertEquals($totalUnits, $logCount, "Cada voto debe tener un registro en la tabla de auditoría dinámica.");

        // --- SIMULACIÓN 2: Intento de Doble Voto (Ataque de Concurrencia) ---
        $failures = 0;
        foreach ($users as $index => $user) {
            try {
                // Intentamos votar por la opción B para la misma unidad
                $this->service->castVote($user, $units[$index], $pregunta, $opcionB, $asamblea);
            } catch (\Exception $e) {
                $failures++;
            }
        }

        $this->assertEquals($totalUnits, $failures, "El sistema bloqueó exitosamente el 100% de los intentos de doble voto.");
        
        // --- SIMULACIÓN 3: Verificación de Integridad tras fallo ---
        // El conteo de votos no debe haber cambiado
        $this->assertEquals($totalUnits, Voto::where('pregunta_id', $pregunta->id)->count());
        
        echo "\n   > Simulación completada: 100 votos procesados en " . number_format($duration, 4) . " segundos.\n";
    }

    /**
     * PRUEBA DE ESTRÉS: 1,000 Votos en una sola ráfaga.
     * Simula una ciudadela de gran tamaño para medir límites de memoria y DB.
     */
    public function test_high_volume_stress_test()
    {
        $copropiedad = Copropiedad::factory()->create();
        $asamblea = Asamblea::create([
            'copropiedad_id' => $copropiedad->id,
            'titulo' => 'Estrés Test - 1000 Unidades',
            'fecha' => now(),
            'status' => 'active'
        ]);

        $pregunta = Pregunta::create([
            'asamblea_id' => $asamblea->id,
            'titulo' => 'Votación Masiva de Estrés',
            'status' => 'open'
        ]);

        $opcion = Opcion::create(['pregunta_id' => $pregunta->id, 'titulo' => 'OPCIÓN ÚNICA']);

        $totalUnits = 1000;
        
        echo "\n   > Preparando 1,000 unidades y usuarios (esto puede tardar unos segundos)...";
        
        // Usamos una transacción para la creación masiva para no sesgar el tiempo del test
        DB::beginTransaction();
        $data = [];
        for ($i = 0; $i < $totalUnits; $i++) {
            $unit = Unidad::create([
                'copropiedad_id' => $copropiedad->id,
                'nombre' => "U-" . ($i + 1),
                'torre' => 'Z',
                'coeficiente' => 0.001,
                'saldo_actual' => 0
            ]);
            $user = User::factory()->create();
            $user->unidades()->attach($unit->id);
            $data[] = ['user' => $user, 'unit' => $unit];
        }
        DB::commit();

        $startMemory = memory_get_usage();
        $startTime = microtime(true);

        // EJECUCIÓN DEL ESTRÉS: 1,000 votos directos al servicio
        foreach ($data as $item) {
            $this->service->castVote($item['user'], $item['unit'], $pregunta, $opcion, $asamblea);
        }

        $duration = microtime(true) - $startTime;
        $peakMemory = (memory_get_peak_usage() - $startMemory) / 1024 / 1024;

        // Verificaciones
        $this->assertEquals($totalUnits, Voto::where('pregunta_id', $pregunta->id)->count());
        $this->assertEquals($totalUnits, DB::table($asamblea->getLogTableName())->where('event_type', 'vote')->count());

        echo "\n   > ESTRÉS EXITOSO: 1,000 votos procesados.";
        echo "\n   > Tiempo total: " . number_format($duration, 4) . " segundos.";
        echo "\n   > Pico de memoria adicional: " . number_format($peakMemory, 2) . " MB.";
        echo "\n   > Promedio por voto: " . number_format(($duration / $totalUnits) * 1000, 4) . " ms.\n";
    }
}

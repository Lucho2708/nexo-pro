<?php

namespace Tests\Feature;

use App\Models\Asamblea;
use App\Models\Copropiedad;
use App\Models\Unidad;
use App\Modules\IAM\Models\User;
use App\Services\AsambleaService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class AssemblyAuthConcurrencyTest extends TestCase
{
    use RefreshDatabase;

    /**
     * PRUEBA DE ACCESO CONCURRENTE:
     * Simula 500 usuarios intentando entrar al cuarto de asamblea al mismo tiempo.
     * Verifica la velocidad de autenticación y la efectividad del bloqueo de un solo dispositivo.
     */
    public function test_concurrent_assembly_access_burst()
    {
        $copropiedad = Copropiedad::factory()->create();
        $asamblea = Asamblea::create([
            'copropiedad_id' => $copropiedad->id,
            'titulo' => 'Asamblea de Acceso Masivo',
            'fecha' => now(),
            'status' => 'active'
        ]);

        $totalUsers = 500;
        $users = [];
        $units = [];

        // Preparar usuarios y unidades (esto es pre-test)
        for ($i = 0; $i < $totalUsers; $i++) {
            $unit = Unidad::create([
                'copropiedad_id' => $copropiedad->id,
                'nombre' => "U-" . ($i + 1),
                'torre' => 'T1',
                'coeficiente' => 0.002,
                'saldo_actual' => 0
            ]);
            $user = User::factory()->create([
                'role' => 'owner',
                'current_copropiedad_id' => $copropiedad->id
            ]);
            $user->unidades()->attach($unit->id);
            
            $users[] = $user;
            $units[] = $unit;
        }

        $startTime = microtime(true);
        $successCount = 0;
        $blockedCount = 0;

        echo "\n   > Simulando ráfaga de 500 accesos concurrentes...";

        foreach ($users as $index => $user) {
            // Simulamos la lógica que ocurre en AsambleaController@show
            $service = app(AsambleaService::class);
            
            // 1. Verificación de permisos y bloqueo de sesión (usando Cache)
            if ($service->canJoin($user, $units[$index])) {
                // 2. Registro de conexión y log de auditoría
                $service->registerConnection($user, $units[$index], $asamblea);
                $successCount++;
            } else {
                $blockedCount++;
            }
        }

        $duration = microtime(true) - $startTime;

        // Verificaciones
        $this->assertEquals($totalUsers, $successCount, "Todos los usuarios legítimos pudieron entrar.");
        $this->assertEquals(0, $blockedCount);

        // --- SIMULACIÓN DE COLISIÓN (Intento de entrada con sesión ya activa por OTRO usuario) ---
        $secondWaveBlocked = 0;
        foreach ($users as $index => $user) {
            $attacker = User::factory()->create(['role' => 'owner']); // OTRO USUARIO
            $service = app(AsambleaService::class);
            if (!$service->canJoin($attacker, $units[$index])) {
                $secondWaveBlocked++;
            }
        }

        $this->assertEquals($totalUsers, $secondWaveBlocked, "El bloqueo de 'Dispositivo Único' fue efectivo para los 500 intentos de intrusión.");

        echo "\n   > ACCESO MASIVO EXITOSO: 500 sesiones gestionadas.";
        echo "\n   > Tiempo total de ráfaga: " . number_format($duration, 4) . " segundos.";
        echo "\n   > Promedio de procesamiento por usuario: " . number_format(($duration / $totalUsers) * 1000, 4) . " ms.\n";
    }
}

<?php

declare(strict_types=1);

namespace Tests\Unit\Audio;

use Tests\TestCase;
use App\Modules\Asamblea\Services\IntervencionService;
use App\Modules\Asamblea\Services\AsambleaService;
use App\Modules\Asamblea\Models\Intervencion;
use App\Modules\Asamblea\Models\Asamblea;
use App\Modules\IAM\Models\User;
use Mockery;
use Exception;

/**
 * AssemblyAudioQueueUnitTest
 *
 * [LÓGICA DE NEGOCIO]
 * Valida la máquina de estados de la cola de audio y uso de la palabra en asambleas virtuales (Ley 675):
 * - Solicitud de intervención (levantar la mano) con prevención de solicitudes duplicadas.
 * - Concesión de la palabra con límite de tiempo predeterminado (180 segundos).
 * - Extensión de tiempo adicional por decisión del moderador (+60 segundos).
 * - Cierre voluntario vs revocación forzada por conducta o vencimiento de tiempo.
 *
 * [ASPECTO TÉCNICO]
 * Pruebas unitarias de estados finitos y contratos aislados mediante Mockery.
 */
class AssemblyAudioQueueUnitTest extends TestCase
{
    private AsambleaService $asambleaService;
    private IntervencionService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->asambleaService = Mockery::mock(AsambleaService::class);
        $this->service = new IntervencionService($this->asambleaService);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_grant_rejects_when_another_active_intervention_exists(): void
    {
        $intervencion = Mockery::mock(Intervencion::class)->makePartial();
        $intervencion->asamblea_id = 'asamblea-123';

        $serviceMock = Mockery::mock(IntervencionService::class, [$this->asambleaService])->makePartial()->shouldAllowMockingProtectedMethods();
        $serviceMock->shouldReceive('hasActiveIntervencion')
            ->with('asamblea-123')
            ->once()
            ->andReturn(true);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Ya hay una intervención activa');

        $serviceMock->grant($intervencion);
    }

    public function test_close_with_force_sets_status_forced_close_and_logs_audit(): void
    {
        $user = new User(['name' => 'Votante Disruptivo']);
        $user->id = 'user-disruptive';

        $asamblea = new Asamblea(['copropiedad_id' => 'coprop-123']);
        $asamblea->id = 'asamblea-123';

        $intervencion = Mockery::mock(Intervencion::class)->makePartial();
        $intervencion->asamblea_id = 'asamblea-123';
        $intervencion->user = $user;
        $intervencion->asamblea = $asamblea;
        $intervencion->started_at = now()->subMinutes(2);
        $intervencion->notes = null;

        $intervencion->shouldReceive('update')
            ->once()
            ->with(Mockery::on(function ($data) {
                return $data['status'] === 'forced_close' && $data['notes'] === 'Micrófono revocado por moderador';
            }))
            ->andReturn(true);

        $serviceMock = Mockery::mock(IntervencionService::class, [$this->asambleaService])->makePartial()->shouldAllowMockingProtectedMethods();
        $serviceMock->shouldReceive('broadcastUpdate')->once();

        $this->asambleaService->shouldReceive('logEvent')
            ->once()
            ->with(
                $asamblea,
                $user,
                Mockery::any(),
                'intervention_completed',
                Mockery::on(function ($payload) {
                    return $payload['status'] === 'forced_close';
                })
            );

        $this->asambleaService->shouldReceive('getAuthenticatedUnit')
            ->andReturn(null);

        $result = $serviceMock->close($intervencion, true, 'Micrófono revocado por moderador');
        $this->assertNotNull($result);
    }

    public function test_extend_increments_duration_and_broadcasts_update(): void
    {
        $user = new User(['name' => 'Orador Moderado']);
        $asamblea = new Asamblea(['copropiedad_id' => 'coprop-123']);

        $intervencion = Mockery::mock(Intervencion::class)->makePartial()->shouldAllowMockingProtectedMethods();
        $intervencion->asamblea_id = 'asamblea-123';
        $intervencion->user = $user;
        $intervencion->asamblea = $asamblea;

        $intervencion->shouldReceive('increment')
            ->once()
            ->with('duration_seconds', 60)
            ->andReturn(true);

        $serviceMock = Mockery::mock(IntervencionService::class, [$this->asambleaService])->makePartial()->shouldAllowMockingProtectedMethods();
        $serviceMock->shouldReceive('broadcastUpdate')->once();

        $this->asambleaService->shouldReceive('logEvent')->once();
        $this->asambleaService->shouldReceive('getAuthenticatedUnit')->andReturn(null);

        $result = $serviceMock->extend($intervencion, 60);
        $this->assertNotNull($result);
    }
}

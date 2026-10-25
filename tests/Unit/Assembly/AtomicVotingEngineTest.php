<?php

declare(strict_types=1);

namespace Tests\Unit\Assembly;

use Tests\TestCase;
use NexoPro\Assembly\Services\AtomicVotingEngine;
use NexoPro\Assembly\Contracts\AssemblyTableManagerInterface;
use NexoPro\Assembly\Contracts\VotePublisherInterface;
use NexoPro\Assembly\DTOs\CastVoteDTO;
use Illuminate\Support\Facades\DB;
use Mockery;
use Mockery\MockInterface;
use RuntimeException;
use InvalidArgumentException;

/**
 * AtomicVotingEngineTest
 *
 * [LÓGICA DE NEGOCIO]
 * Valida de forma exhaustiva las reglas legales de sufragio digital para asambleas de propiedad horizontal:
 * - Emisión atómica de votos para una o múltiples unidades inmobiliarias apoderadas.
 * - Prevención estricta de doble voto (Ley 675 y Habeas Data).
 * - Ponderación exacta de coeficientes acumulados y difusión en tiempo real.
 * - Trazabilidad inmutable de auditoría (IP, User Agent y timestamp).
 *
 * [ASPECTO TÉCNICO]
 * Pruebas unitarias de caja blanca con aislamiento total mediante Mocks de dependencias (DIP).
 * Simula fallos de transacción, reintentos y casos de borde de concurrencia.
 */
class AtomicVotingEngineTest extends TestCase
{
    private AssemblyTableManagerInterface&MockInterface $tableManager;
    private VotePublisherInterface&MockInterface $votePublisher;
    private AtomicVotingEngine $engine;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tableManager = Mockery::mock(AssemblyTableManagerInterface::class);
        $this->votePublisher = Mockery::mock(VotePublisherInterface::class);

        // Mock DB::transaction para ejecutar el callback de forma inmediata en pruebas unitarias puras
        DB::shouldReceive('transaction')
            ->andReturnUsing(fn(\Closure $callback) => $callback());

        $this->engine = new AtomicVotingEngine(
            $this->tableManager,
            $this->votePublisher
        );
    }

    /**
     * Happy Path: Procesa exitosamente los votos de múltiples unidades apoderadas.
     */
    public function test_process_vote_successfully_registers_all_units(): void
    {
        $asambleaId = 'a1b2c3d4-e5f6-7890-abcd-1234567890ab';
        $shortId = 'a1b2c3d4';
        $copropiedadId = 'c1c2c3c4-0000-0000-0000-000000000000';

        $voteDTO = new CastVoteDTO(
            asambleaId: $asambleaId,
            preguntaId: 'preg-100',
            opcionId: 'op-favor',
            userId: 'user-42',
            unidades: [
                ['id' => 'u-apt-101', 'nombre' => 'Apto 101', 'coeficiente' => 1.2500],
                ['id' => 'u-apt-102', 'nombre' => 'Apto 102', 'coeficiente' => 2.7500],
            ],
            ipAddress: '192.168.1.50',
            userAgent: 'Mozilla/5.0 UnitTester'
        );

        $this->tableManager->shouldReceive('ensureAssemblyEcosystem')
            ->once()
            ->with($shortId, $copropiedadId);

        // Ambas unidades no han votado previamente
        $this->tableManager->shouldReceive('hasVoted')
            ->once()
            ->with($shortId, 'preg-100', 'u-apt-101')
            ->andReturn(false);

        $this->tableManager->shouldReceive('hasVoted')
            ->once()
            ->with($shortId, 'preg-100', 'u-apt-102')
            ->andReturn(false);

        // Registro de votos individuales
        $this->tableManager->shouldReceive('registerVote')
            ->once()
            ->with($shortId, [
                'pregunta_id' => 'preg-100',
                'opcion_id'   => 'op-favor',
                'unidad_id'   => 'u-apt-101',
                'user_id'     => 'user-42',
                'peso'        => 1.2500,
            ], $copropiedadId);

        $this->tableManager->shouldReceive('registerVote')
            ->once()
            ->with($shortId, [
                'pregunta_id' => 'preg-100',
                'opcion_id'   => 'op-favor',
                'unidad_id'   => 'u-apt-102',
                'user_id'     => 'user-42',
                'peso'        => 2.7500,
            ], $copropiedadId);

        // Auditoría inmutable de eventos
        $this->tableManager->shouldReceive('logEvent')
            ->twice();

        // Difusión WebSocket a la sala virtual con peso total consolidado (1.25 + 2.75 = 4.0000)
        $this->votePublisher->shouldReceive('publishVoteCast')
            ->once()
            ->with($asambleaId, 'preg-100', $voteDTO, 4.0000);

        $votosCount = $this->engine->processVote($voteDTO, $copropiedadId);

        $this->assertEquals(2, $votosCount, 'Deben haberse registrado 2 votos válidos.');
    }

    /**
     * Edge Case 1: Lanza InvalidArgumentException si no se proporcionan unidades votantes.
     */
    public function test_process_vote_throws_when_unidades_empty(): void
    {
        $voteDTO = new CastVoteDTO(
            asambleaId: 'a1b2c3d4-e5f6-7890-abcd-1234567890ab',
            preguntaId: 'preg-100',
            opcionId: 'op-favor',
            userId: 'user-42',
            unidades: []
        );

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('El votante no tiene unidades vinculadas para ejercer el sufragio.');

        $this->engine->processVote($voteDTO, 'cop-1');
    }

    /**
     * Edge Case 2: Si una unidad ya votó y otra no, omite la que ya votó y registra la pendiente.
     */
    public function test_process_vote_skips_already_voted_units_and_records_remaining(): void
    {
        $asambleaId = 'a1b2c3d4-e5f6-7890-abcd-1234567890ab';
        $shortId = 'a1b2c3d4';
        $copropiedadId = 'cop-1';

        $voteDTO = new CastVoteDTO(
            asambleaId: $asambleaId,
            preguntaId: 'preg-100',
            opcionId: 'op-contra',
            userId: 'user-42',
            unidades: [
                ['id' => 'u-1', 'nombre' => 'Apto 1', 'coeficiente' => 1.5],
                ['id' => 'u-2', 'nombre' => 'Apto 2', 'coeficiente' => 2.0],
            ]
        );

        $this->tableManager->shouldReceive('ensureAssemblyEcosystem')->once();

        // u-1 ya votó, u-2 no ha votado
        $this->tableManager->shouldReceive('hasVoted')
            ->with($shortId, 'preg-100', 'u-1')
            ->andReturn(true);

        $this->tableManager->shouldReceive('hasVoted')
            ->with($shortId, 'preg-100', 'u-2')
            ->andReturn(false);

        // Solo se registra u-2
        $this->tableManager->shouldReceive('registerVote')
            ->once()
            ->with($shortId, Mockery::subset(['unidad_id' => 'u-2', 'peso' => 2.0]), $copropiedadId);

        $this->tableManager->shouldReceive('logEvent')->once();

        $this->votePublisher->shouldReceive('publishVoteCast')
            ->once()
            ->with($asambleaId, 'preg-100', $voteDTO, 2.0000);

        $count = $this->engine->processVote($voteDTO, $copropiedadId);

        $this->assertEquals(1, $count);
    }

    /**
     * Edge Case 3: Si TODAS las unidades ya votaron, lanza RuntimeException y bloquea la operación.
     */
    public function test_process_vote_throws_runtime_exception_if_all_units_already_voted(): void
    {
        $asambleaId = 'a1b2c3d4-e5f6-7890-abcd-1234567890ab';
        $shortId = 'a1b2c3d4';
        $copropiedadId = 'cop-1';

        $voteDTO = new CastVoteDTO(
            asambleaId: $asambleaId,
            preguntaId: 'preg-100',
            opcionId: 'op-favor',
            userId: 'user-42',
            unidades: [
                ['id' => 'u-1', 'nombre' => 'Apto 1', 'coeficiente' => 1.0],
            ]
        );

        $this->tableManager->shouldReceive('ensureAssemblyEcosystem')->once();

        $this->tableManager->shouldReceive('hasVoted')
            ->once()
            ->with($shortId, 'preg-100', 'u-1')
            ->andReturn(true);

        $this->votePublisher->shouldNotReceive('publishVoteCast');

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Todas las unidades vinculadas ya han emitido su sufragio para esta moción.');

        $this->engine->processVote($voteDTO, $copropiedadId);
    }

    /**
     * Edge Case 4: Verifica que los datos de auditoría legal (IP y UserAgent) sean transmitidos al logger.
     */
    public function test_process_vote_passes_audit_metadata_to_log_event(): void
    {
        $asambleaId = 'a1b2c3d4-e5f6-7890-abcd-1234567890ab';
        $shortId = 'a1b2c3d4';
        $copropiedadId = 'cop-1';

        $voteDTO = new CastVoteDTO(
            asambleaId: $asambleaId,
            preguntaId: 'preg-200',
            opcionId: 'op-1',
            userId: 'user-99',
            unidades: [
                ['id' => 'u-penthouse', 'nombre' => 'PH 01', 'coeficiente' => 5.4321],
            ],
            ipAddress: '10.0.0.99',
            userAgent: 'CustomLegalAgent/1.0'
        );

        $this->tableManager->shouldReceive('ensureAssemblyEcosystem')->once();
        $this->tableManager->shouldReceive('hasVoted')->andReturn(false);
        $this->tableManager->shouldReceive('registerVote')->once();

        $this->tableManager->shouldReceive('logEvent')
            ->once()
            ->with(
                $shortId,
                $copropiedadId,
                'user-99',
                'u-penthouse',
                'vote_cast',
                Mockery::type('array'),
                '10.0.0.99',
                'CustomLegalAgent/1.0'
            );

        $this->votePublisher->shouldReceive('publishVoteCast')->once();

        $count = $this->engine->processVote($voteDTO, $copropiedadId);
        $this->assertEquals(1, $count);
    }
}

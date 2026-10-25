<?php

declare(strict_types=1);

namespace Tests\Unit\Notifications;

use Tests\TestCase;
use App\Modules\Operations\Models\Announcement;
use App\Modules\IAM\Models\User;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Auth;
use Mockery;
use Mockery\MockInterface;

/**
 * NotificationSystemUnitTest
 *
 * [LÓGICA DE NEGOCIO]
 * Valida la gestión de avisos y notificaciones en tiempo real para copropietarios:
 * - Marcado de lectura sin duplicidad (idempotencia).
 * - Cálculo reactivo de comunicados pendientes de lectura.
 * - Despacho seguro de eventos para la campanita de notificaciones.
 *
 * [ASPECTO TÉCNICO]
 * Pruebas unitarias puras con Mockery (DIP y SRP).
 */
class NotificationSystemUnitTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_mark_as_read_attaches_user_timestamp_when_not_previously_read(): void
    {
        $user = new User();
        $user->id = 101;
        Auth::shouldReceive('user')->andReturn($user);

        $relationMock = Mockery::mock();
        $relationMock->shouldReceive('where')
            ->with('user_id', 101)
            ->once()
            ->andReturnSelf();
        $relationMock->shouldReceive('exists')
            ->once()
            ->andReturn(false);
        $relationMock->shouldReceive('attach')
            ->once()
            ->with(101, Mockery::on(function ($arg) {
                return isset($arg['read_at']);
            }))
            ->andReturnNull();

        $announcement = Mockery::mock(Announcement::class)->makePartial();
        $announcement->shouldReceive('usersRead')->andReturn($relationMock);

        $controller = new NotificationController();
        $response = $controller->markAsRead($announcement);

        $this->assertEquals(200, $response->getStatusCode());
        $data = json_decode($response->getContent(), true);
        $this->assertEquals('success', $data['status']);
    }

    public function test_mark_as_read_is_idempotent_and_skips_duplicate_attach(): void
    {
        $user = new User();
        $user->id = 101;
        Auth::shouldReceive('user')->andReturn($user);

        $relationMock = Mockery::mock();
        $relationMock->shouldReceive('where')
            ->with('user_id', 101)
            ->once()
            ->andReturnSelf();
        $relationMock->shouldReceive('exists')
            ->once()
            ->andReturn(true); // Ya fue leído
        $relationMock->shouldNotReceive('attach'); // No debe volver a insertar

        $announcement = Mockery::mock(Announcement::class)->makePartial();
        $announcement->shouldReceive('usersRead')->andReturn($relationMock);

        $controller = new NotificationController();
        $response = $controller->markAsRead($announcement);

        $this->assertEquals(200, $response->getStatusCode());
        $data = json_decode($response->getContent(), true);
        $this->assertEquals('success', $data['status']);
    }
}

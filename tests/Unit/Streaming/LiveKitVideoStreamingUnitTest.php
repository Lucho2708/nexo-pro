<?php

declare(strict_types=1);

namespace Tests\Unit\Streaming;

use Tests\TestCase;
use App\Modules\Asamblea\Services\AsambleaService;
use App\Modules\Asamblea\Models\Asamblea;
use App\Modules\IAM\Models\User;
use App\Modules\Property\Models\Unidad;
use App\Services\Tenant\AssemblyTableManager;
use Mockery;
use Illuminate\Support\Facades\Config;

/**
 * LiveKitVideoStreamingUnitTest
 *
 * [LÓGICA DE NEGOCIO]
 * Valida la generación y emisión de credenciales criptográficas WebRTC (LiveKit) para asambleas virtuales:
 * - Aislamiento estricto de sala por copropiedad ("asamblea_{copropiedad_id}").
 * - Diferenciación de privilegios: Los administradores y superadministradores pueden transmitir video/audio;
 *   los copropietarios residentes ingresan en modo escucha y solo suscriben medios hasta obtener la palabra.
 * - Soporte de token resiliente con llaves de contingencia configurables.
 *
 * [ASPECTO TÉCNICO]
 * Pruebas unitarias de decodificación e inspección de JWT emitidos por Agence104 LiveKit SDK.
 */
class LiveKitVideoStreamingUnitTest extends TestCase
{
    private AssemblyTableManager $tableManager;
    private AsambleaService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->tableManager = Mockery::mock(AssemblyTableManager::class);
        $this->service = new AsambleaService($this->tableManager);

        Config::set('services.livekit.key', 'test_key_livekit');
        Config::set('services.livekit.secret', 'test_secret_livekit_super_secure_12345');
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_generate_token_produces_valid_jwt_with_room_name_from_asamblea(): void
    {
        $user = new User([
            'name' => 'Carlos Administrador',
            'role' => 'admin',
        ]);
        $user->id = 'user-uuid-admin';
        $user->current_copropiedad_id = 'coprop-active-123';

        $asamblea = new Asamblea([
            'copropiedad_id' => 'coprop-target-999',
        ]);
        $asamblea->id = 'asamblea-uuid-888';

        $unidad = new Unidad([
            'torre' => 'ADM',
            'nombre' => 'CONSEJO',
        ]);

        $tokenJwt = $this->service->generateToken($user, $unidad, $asamblea);

        $this->assertNotEmpty($tokenJwt);
        $parts = explode('.', $tokenJwt);
        $this->assertCount(3, $parts, 'El token debe ser un JWT canónico de 3 partes');

        $payload = json_decode(base64_decode(str_replace(['-', '_'], ['+', '/'], $parts[1])), true);
        
        $this->assertEquals('test_key_livekit', $payload['iss']);
        $this->assertStringContainsString('T-ADM U-CONSEJO | Carlos Administrador', $payload['sub']);
        $this->assertEquals('asamblea_coprop-target-999', $payload['video']['room']);
        $this->assertTrue($payload['video']['canPublish'], 'El administrador debe tener permiso de publicación');
        $this->assertTrue($payload['video']['canSubscribe']);
    }

    public function test_resident_token_restricts_publishing_until_floor_is_granted(): void
    {
        $user = new User([
            'name' => 'Maria Residente',
            'role' => 'owner',
        ]);
        $user->id = 'user-uuid-owner';
        $user->current_copropiedad_id = 'coprop-123';

        $asamblea = new Asamblea([
            'copropiedad_id' => 'coprop-123',
        ]);

        $unidad = new Unidad([
            'torre' => 'A',
            'nombre' => '302',
        ]);

        $tokenJwt = $this->service->generateToken($user, $unidad, $asamblea);
        $parts = explode('.', $tokenJwt);
        $payload = json_decode(base64_decode(str_replace(['-', '_'], ['+', '/'], $parts[1])), true);

        $this->assertFalse($payload['video']['canPublish'], 'El residente común no debe publicar medios automáticamente');
        $this->assertTrue($payload['video']['canSubscribe'], 'El residente común siempre puede suscribir y escuchar');
        $this->assertTrue($payload['video']['canPublishData'], 'El residente común puede emitir datos para votación y chat');
    }

    public function test_superadmin_without_current_copropiedad_receives_correct_room_from_asamblea(): void
    {
        $user = new User([
            'name' => 'Superadmin Global',
            'role' => 'super_admin',
        ]);
        $user->id = 'super-uuid-001';
        $user->current_copropiedad_id = null; // Contexto global

        $asamblea = new Asamblea([
            'copropiedad_id' => 'copropiedad-especifica-777',
        ]);

        $unidad = new Unidad(['torre' => 'ROOT', 'nombre' => 'SRE']);

        $tokenJwt = $this->service->generateToken($user, $unidad, $asamblea);
        $parts = explode('.', $tokenJwt);
        $payload = json_decode(base64_decode(str_replace(['-', '_'], ['+', '/'], $parts[1])), true);

        $this->assertEquals('asamblea_copropiedad-especifica-777', $payload['video']['room']);
        $this->assertTrue($payload['video']['canPublish']);
    }
}

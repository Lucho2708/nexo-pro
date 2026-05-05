<?php

namespace Tests\Feature\Asamblea;

use App\Models\Asamblea;
use App\Modules\IAM\Models\User;
use App\Models\Unidad;
use App\Services\AsambleaService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AsambleaTokenTest extends TestCase
{
    use RefreshDatabase;

    private AsambleaService $asambleaService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->asambleaService = app(AsambleaService::class);
    }

    /** @test */
    public function test_it_generates_a_valid_livekit_token()
    {
        // 1. Arrange: Create a user, assembly and unit
        $user = User::factory()->create(['name' => 'Test User']);
        $asamblea = Asamblea::factory()->create();
        $unidad = Unidad::factory()->create([
            'torre' => '1',
            'nombre' => '101',
            'copropiedad_id' => $asamblea->copropiedad_id
        ]);

        // 2. Act: Generate token
        $token = $this->asambleaService->generateToken($user, $unidad);

        // 3. Assert: Token should not be empty and should be a string
        $this->assertIsString($token);
        $this->assertNotEmpty($token);
        
        // We can check if it's a JWT (starts with ey)
        $this->assertStringStartsWith('ey', $token);
    }

    /** @test */
    public function test_it_fails_if_livekit_secret_is_too_short()
    {
        // This test simulates the error we had: "Provided key is too short"
        // We temporarily change the config to a short secret
        config(['services.livekit.secret' => 'short']);

        $user = User::factory()->create();
        $asamblea = Asamblea::factory()->create();
        $unidad = Unidad::factory()->create(['copropiedad_id' => $asamblea->copropiedad_id]);

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Provided key is too short');

        $this->asambleaService->generateToken($user, $unidad);
    }
}

<?php

namespace Tests\Feature;

use App\Modules\Asamblea\Models\Asamblea;
use App\Modules\Property\Models\Copropiedad;
use App\Services\Tenant\StandaloneOnboardingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class StandaloneAssemblyOnboardingTest extends TestCase
{
    use RefreshDatabase;

    private StandaloneOnboardingService $onboardingService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->onboardingService = app(StandaloneOnboardingService::class);
    }

    /** @test */
    public function test_it_renders_the_guest_login_page()
    {
        $copropiedad = Copropiedad::factory()->create();
        $asamblea = Asamblea::factory()->create(['copropiedad_id' => $copropiedad->id]);

        $response = $this->get(route('asambleas.guest.login', $asamblea));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('Asamblea/GuestLogin')
            ->has('asamblea')
        );
    }

    /** @test */
    public function test_it_can_import_external_units_and_validate_access_by_document()
    {
        $copropiedad = Copropiedad::factory()->create([
            'settings' => array_merge(Copropiedad::defaultSettings(), ['asamblea_virtual_enabled' => true])
        ]);
        $asamblea = Asamblea::factory()->create(['copropiedad_id' => $copropiedad->id]);

        $externalData = [
            ['torre' => 'A', 'apto' => '101', 'nombre' => 'Juan Perez', 'coeficiente' => 0.5, 'documento' => '12345678'],
            ['torre' => 'A', 'apto' => '102', 'nombre' => 'Maria Lopez', 'coeficiente' => 0.5, 'documento' => '87654321'],
        ];

        // Act 1: Importamos los datos
        $this->onboardingService->importExternalData($asamblea, $externalData);

        $shortId = substr($asamblea->id, 0, 8);
        $this->assertDatabaseCount("asquorum_{$shortId}", 2);

        // Act 2: Validamos acceso (Opción B: últimos 4 dígitos)
        // Escenario Exitoso
        $record = $this->onboardingService->validateAccess($asamblea, 'A - 101', '5678');
        $this->assertNotNull($record);
        $this->assertEquals(0.5, $record->coeficiente);

        // Escenario Fallido (Dígitos incorrectos)
        $invalidRecord = $this->onboardingService->validateAccess($asamblea, 'A - 101', '0000');
        $this->assertNull($invalidRecord);
    }

    /** @test */
    public function test_it_can_authenticate_a_guest_user_via_controller()
    {
        $copropiedad = Copropiedad::factory()->create([
            'settings' => array_merge(Copropiedad::defaultSettings(), ['asamblea_virtual_enabled' => true])
        ]);
        $asamblea = Asamblea::factory()->create(['copropiedad_id' => $copropiedad->id]);

        $externalData = [
            ['torre' => 'A', 'apto' => '101', 'nombre' => 'Juan Perez', 'coeficiente' => 0.5, 'documento' => '12345678'],
        ];

        // 1. Importamos
        $this->onboardingService->importExternalData($asamblea, $externalData);

        // 2. Intentamos Login vía HTTP
        $response = $this->post(route('asambleas.guest.attempt', $asamblea), [
            'nombre_unidad' => 'A - 101',
            'documento_ultimos_4' => '5678'
        ]);

        // 3. Verificamos redirección a la sala
        $response->assertRedirect(route('asambleas.show', $asamblea));
        
        // 4. Verificamos que el usuario esté autenticado
        $this->assertAuthenticated();
        
        $user = auth()->user();
        $this->assertEquals('invitado', $user->role);
        $this->assertStringContainsString('guest_', $user->email);
    }
}

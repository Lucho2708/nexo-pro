<?php

namespace Tests\Feature;

use App\Modules\IAM\Models\User;
use App\Modules\Asamblea\Models\Asamblea;
use App\Modules\Property\Models\Copropiedad;
use App\Modules\Asamblea\Services\AsambleaService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class AsambleaDynamicIsolationTest extends TestCase
{
    use RefreshDatabase;

    private AsambleaService $asambleaService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->asambleaService = app(AsambleaService::class);
    }

    /** @test */
    public function test_it_creates_all_dynamic_isolation_tables_when_assembly_is_initialized()
    {
        $copropiedad = Copropiedad::factory()->create([
            'settings' => array_merge(Copropiedad::defaultSettings(), ['asamblea_virtual_enabled' => true])
        ]);
        $asamblea = Asamblea::factory()->create([
            'copropiedad_id' => $copropiedad->id,
            'status' => 'scheduled'
        ]);

        \App\Modules\Property\Models\Unidad::factory()->create(['copropiedad_id' => $copropiedad->id]);

        // Act: Inicializamos el ecosistema dinámico
        $this->asambleaService->initializeDynamicEcosystem($asamblea);

        // Assert: Verificamos que existan las tablas dinámicas
        $shortId = substr($asamblea->id, 0, 8);
        
        $this->assertTrue(Schema::hasTable("aslog_{$shortId}"), "Log table should exist");
        $this->assertTrue(Schema::hasTable("asvotos_{$shortId}"), "Votes table should exist");
        $this->assertTrue(Schema::hasTable("asquorum_{$shortId}"), "Quorum table should exist");
        $this->assertTrue(Schema::hasTable("aspreguntas_{$shortId}"), "Questions table should exist");

        // Verificamos que se haya poblado el quórum
        $this->assertEquals(1, \DB::table("asquorum_{$shortId}")->count(), "Quorum table should be populated with 1 unit");
    }

    /** @test */
    public function test_it_allows_access_to_standalone_users_registered_only_in_dynamic_quorum()
    {
        $copropiedad = Copropiedad::factory()->create([
            'settings' => array_merge(Copropiedad::defaultSettings(), ['asamblea_virtual_enabled' => true])
        ]);
        $asamblea = Asamblea::factory()->create(['copropiedad_id' => $copropiedad->id]);
        $unidad = \App\Modules\Property\Models\Unidad::factory()->create(['copropiedad_id' => $copropiedad->id]);
        
        // Creamos un usuario "Huésped" que NO tiene relación con la unidad en la tabla global
        $guestUser = User::factory()->create([
            'current_copropiedad_id' => $copropiedad->id
        ]);

        // Inicializamos el ecosistema
        $this->asambleaService->initializeDynamicEcosystem($asamblea);
        $shortId = substr($asamblea->id, 0, 8);

        // Vinculamos al usuario directamente en la tabla de quórum dinámica (Escenario Standalone)
        \DB::table("asquorum_{$shortId}")
            ->where('unidad_id', $unidad->id)
            ->update(['user_id' => $guestUser->id]);

        // Act: El usuario intenta entrar a la asamblea
        $response = $this->actingAs($guestUser)
            ->get(route('asambleas.show', $asamblea));

        // Assert: Debe permitirle la entrada (Status 200) y encontrar su unidad
        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('Asamblea/Show')
            ->has('unidades', 1) // Debe haber encontrado la unidad vía asquorum
        );
    }
}

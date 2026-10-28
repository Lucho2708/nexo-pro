<?php

namespace Tests\Feature\Admin;

use App\Modules\IAM\Models\User;
use App\Modules\Property\Models\ComponenteUnidad;
use App\Modules\Property\Models\Copropiedad;
use App\Modules\Property\Models\TipoUnidad;
use App\Modules\Property\Models\Unidad;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class PlanUnitCapacityEnforcementTest extends TestCase
{
    use RefreshDatabase;

    private Copropiedad $copropiedad;
    private User $adminUser;
    private TipoUnidad $tipoUnidad;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(\Database\Seeders\RolePermissionSeeder::class);

        $this->copropiedad = Copropiedad::create([
            'nombre' => 'Edificio Boutique 50',
            'nit' => '900555666-1',
            'direccion' => 'Calle 85 # 11-20',
            'ciudad' => 'Bogotá',
            'plan' => 'boutique', // max 60 units
            'unit_types_locked' => false,
            'settings' => Copropiedad::defaultSettings(),
        ]);

        $this->adminUser = User::factory()->create([
            'current_copropiedad_id' => $this->copropiedad->id,
            'role' => 'admin',
            'email_verified_at' => now(),
            'terms_accepted_at' => now(),
        ]);
        $this->adminUser->managedCopropiedades()->attach($this->copropiedad->id);
        $this->adminUser->assignRole('admin');

        $this->tipoUnidad = TipoUnidad::create([
            'copropiedad_id' => $this->copropiedad->id,
            'nombre' => 'Apartamento Tipo A',
            'area_m2' => 75.0,
        ]);
    }

    public function test_bulk_unit_generator_is_blocked_and_persists_nothing_when_exceeding_plan_limit(): void
    {
        // 2 towers * 5 floors * 8 units_per_floor = 80 units (Plan limit is 60)
        $response = $this->actingAs($this->adminUser)
            ->post(route('admin.settings.generate_units'), [
                'structure_type' => 'vertical',
                'prefix' => 'Torre',
                'separator' => '-',
                'towers' => 2,
                'floors' => 5,
                'units_per_floor' => 8,
                'numbering_type' => 'floor',
            ]);

        $response->assertSessionHas('error');
        $errorMessage = session('error');
        
        $this->assertStringContainsString('BOUTIQUE', $errorMessage);
        $this->assertStringContainsString('60', $errorMessage);
        $this->assertStringContainsString('80', $errorMessage);

        // Verify ZERO units were persisted (Rule: "no cargar nada")
        $this->assertEquals(0, Unidad::where('copropiedad_id', $this->copropiedad->id)->count());
        $this->assertFalse((bool) $this->copropiedad->fresh()->unit_types_locked);
    }

    public function test_bulk_unit_generator_succeeds_when_within_plan_limit(): void
    {
        // 1 tower * 5 floors * 6 units_per_floor = 30 units (Plan limit is 60)
        $response = $this->actingAs($this->adminUser)
            ->post(route('admin.settings.generate_units'), [
                'structure_type' => 'vertical',
                'prefix' => 'Torre',
                'separator' => '-',
                'towers' => 1,
                'floors' => 5,
                'units_per_floor' => 6,
                'numbering_type' => 'floor',
            ]);

        $response->assertSessionHasNoErrors();
        $this->assertEquals(30, Unidad::where('copropiedad_id', $this->copropiedad->id)->count());
        $this->assertTrue((bool) $this->copropiedad->fresh()->unit_types_locked);
    }

    public function test_registration_without_torres_and_unidades_totales_succeeds(): void
    {
        $response = $this->post(route('register'), [
            'nit' => '900777888-5',
            'nombre_copropiedad' => 'Conjunto Residencial El Portal',
            'direccion' => 'Carrera 7 # 120-40',
            'ciudad' => 'Bogotá',
            'plan' => 'condominio_pro',
            'billing_cycle' => 'annual',
            'name' => 'Carlos Administrador',
            'email' => 'carlos.admin@elportal.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'terms' => true,
        ]);

        $response->assertRedirect(route('dashboard'));

        $createdCopropiedad = Copropiedad::where('nit', '900777888-5')->first();
        $this->assertNotNull($createdCopropiedad);
        $this->assertEquals('condominio_pro', $createdCopropiedad->plan);
        $this->assertEquals(0, $createdCopropiedad->unidades()->count());
    }
}

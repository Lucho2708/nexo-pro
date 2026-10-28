<?php

namespace Tests\Feature\Admin;

use App\Modules\IAM\Models\User;
use App\Modules\Property\Models\ComponenteUnidad;
use App\Modules\Property\Models\Copropiedad;
use App\Modules\Property\Models\TipoUnidad;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class SettingsManagementE2ETest extends TestCase
{
    use RefreshDatabase;

    private Copropiedad $copropiedad;
    private User $adminUser;
    private User $residentUser;
    private ComponenteUnidad $componente;

    protected function setUp(): void
    {
        parent::setUp();

        $this->copropiedad = Copropiedad::create([
            'nombre' => 'Residencial San Carlos E2E Settings',
            'nit' => '900888999-6',
            'direccion' => 'Calle 100 # 19-40',
            'ciudad' => 'Bogotá',
            'license_status' => 'active',
            'license_expires_at' => now()->addYear(),
            'area_construida_total' => 14200.00,
            'unit_types_locked' => false,
            'settings' => Copropiedad::defaultSettings(),
        ]);

        $this->seed(\Database\Seeders\RolePermissionSeeder::class);

        $this->adminUser = User::factory()->create([
            'current_copropiedad_id' => $this->copropiedad->id,
            'role' => 'admin',
            'email_verified_at' => now(),
            'terms_accepted_at' => now(),
        ]);
        $this->adminUser->managedCopropiedades()->attach($this->copropiedad->id);
        $this->adminUser->assignRole('admin');

        $this->residentUser = User::factory()->create([
            'current_copropiedad_id' => $this->copropiedad->id,
            'role' => 'owner',
            'email_verified_at' => now(),
            'terms_accepted_at' => now(),
        ]);
        $this->residentUser->assignRole('owner');

        $this->componente = ComponenteUnidad::firstOrCreate(['nombre' => 'Habitación Principal']);
    }

    public function test_admin_can_access_canonical_settings_page_with_metrics_and_catalogs(): void
    {
        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.settings'));

        $response->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Admin/Settings/Index')
                ->has('copropiedad')
                ->has('settings')
                ->has('componentes_catalogo')
                ->has('metrics')
                ->where('copropiedad.id', $this->copropiedad->id)
                ->where('metrics.area_construida_total', fn ($val) => (float) $val === 14200.0)
                ->where('metrics.unit_types_locked', false)
            );
    }

    public function test_admin_can_update_settings_and_gateways(): void
    {
        $payload = [
            'area_construida_total' => 16500.50,
            'settings' => [
                'payments_enabled' => true,
                'wompi_public_key' => 'pub_test_QWERTY123456',
                'wompi_integrity_key' => 'int_test_INTEGRITY789',
                'wompi_private_key' => 'prv_test_SECRET999',
                'wompi_webhook_secret' => 'wh_test_SIGNATURE000',
                'pqrs_enabled' => true,
                'reservas_enabled' => true,
                'interes_mora' => 1.85,
                'gateways' => [
                    'wompi' => ['enabled' => true, 'label' => 'Wompi PSE'],
                    'aval' => ['enabled' => false, 'label' => 'Aval Pay Center', 'url' => 'https://avalpay.com/checkout'],
                    'manual' => ['enabled' => true, 'label' => 'Consignación Directa'],
                ],
            ],
        ];

        $response = $this->actingAs($this->adminUser)
            ->patch(route('admin.settings.update'), $payload);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $fresh = $this->copropiedad->fresh();
        $this->assertEquals(16500.50, (float)$fresh->area_construida_total);
        $this->assertTrue($fresh->settings['payments_enabled']);
        $this->assertEquals('pub_test_QWERTY123456', $fresh->settings['wompi_public_key']);
        $this->assertEquals(1.85, (float)$fresh->settings['interes_mora']);
    }

    public function test_admin_can_create_unit_model_with_architectural_components(): void
    {
        $payload = [
            'nombre' => 'Apartamento Tipo Penthouse Duplex',
            'area_m2' => 145.80,
            'componentes' => [
                [
                    'id' => $this->componente->id,
                    'cantidad' => 3,
                ],
            ],
        ];

        $response = $this->actingAs($this->adminUser)
            ->post(route('admin.unit-types.store'), $payload);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('property.tipos_unidad', [
            'copropiedad_id' => $this->copropiedad->id,
            'nombre' => 'Apartamento Tipo Penthouse Duplex',
            'area_m2' => 145.80,
        ]);
    }

    public function test_admin_can_delete_unlinked_unit_model(): void
    {
        $tipo = TipoUnidad::create([
            'copropiedad_id' => $this->copropiedad->id,
            'nombre' => 'Modelo Borrador',
            'area_m2' => 45.0,
        ]);

        $response = $this->actingAs($this->adminUser)
            ->delete(route('admin.unit-types.destroy', $tipo->id));

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('property.tipos_unidad', ['id' => $tipo->id]);
    }

    public function test_admin_can_create_custom_component_in_catalog(): void
    {
        $payload = [
            'nombre' => 'Terraza Panorámica BBQ',
        ];

        $response = $this->actingAs($this->adminUser)
            ->post(route('admin.componentes.store'), $payload);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('property.componentes_unidad', [
            'nombre' => 'Terraza Panorámica BBQ',
        ]);
    }

    public function test_admin_can_download_census_template(): void
    {
        // Require at least one model
        TipoUnidad::create([
            'copropiedad_id' => $this->copropiedad->id,
            'nombre' => 'Apartamento 2H',
            'area_m2' => 60.0,
        ]);

        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.settings.download_template'));

        $response->assertOk();
        $this->assertEquals(
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            $response->headers->get('content-type')
        );
    }

    public function test_resident_cannot_access_or_update_settings(): void
    {
        $response = $this->actingAs($this->residentUser)
            ->get(route('admin.settings'));

        $response->assertStatus(403);
    }

    public function test_unauthenticated_user_is_redirected_to_login(): void
    {
        $response = $this->get(route('admin.settings'));
        $response->assertRedirect(route('login'));
    }
}

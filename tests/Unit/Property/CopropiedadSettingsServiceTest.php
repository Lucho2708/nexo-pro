<?php

namespace Tests\Unit\Property;

use App\Modules\IAM\Models\User;
use App\Modules\Property\Models\ComponenteUnidad;
use App\Modules\Property\Models\Copropiedad;
use App\Modules\Property\Models\TipoUnidad;
use App\Modules\Property\Models\Unidad;
use App\Services\Property\CopropiedadSettingsService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CopropiedadSettingsServiceTest extends TestCase
{
    use RefreshDatabase;

    private CopropiedadSettingsService $service;
    private Copropiedad $copropiedad;
    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = app(CopropiedadSettingsService::class);

        $this->copropiedad = Copropiedad::create([
            'nombre' => 'Residencial Los Álamos Settings Unit',
            'nit' => '900111222-5',
            'direccion' => 'Calle 80 # 25-10',
            'ciudad' => 'Bogotá',
            'license_status' => 'active',
            'license_expires_at' => now()->addYear(),
            'area_construida_total' => 12500.50,
            'unit_types_locked' => false,
            'settings' => Copropiedad::defaultSettings(),
        ]);

        $this->admin = User::factory()->create([
            'role' => 'admin',
            'current_copropiedad_id' => $this->copropiedad->id,
        ]);
        $this->admin->managedCopropiedades()->attach($this->copropiedad->id);
    }

    public function test_computes_copropiedad_tactical_metrics_accurately(): void
    {
        // 2 Modelos
        $modeloA = TipoUnidad::create([
            'copropiedad_id' => $this->copropiedad->id,
            'nombre' => 'Apartamento 2H',
            'area_m2' => 65.0,
        ]);

        $modeloB = TipoUnidad::create([
            'copropiedad_id' => $this->copropiedad->id,
            'nombre' => 'Penthouse 3H',
            'area_m2' => 120.0,
        ]);

        // 3 Unidades
        Unidad::create([
            'copropiedad_id' => $this->copropiedad->id,
            'tipo_unidad_id' => $modeloA->id,
            'nombre' => '101',
            'torre' => '1',
            'coeficiente' => 0.02,
        ]);

        Unidad::create([
            'copropiedad_id' => $this->copropiedad->id,
            'tipo_unidad_id' => $modeloA->id,
            'nombre' => '102',
            'torre' => '1',
            'coeficiente' => 0.02,
        ]);

        Unidad::create([
            'copropiedad_id' => $this->copropiedad->id,
            'tipo_unidad_id' => $modeloB->id,
            'nombre' => 'PH-01',
            'torre' => '1',
            'coeficiente' => 0.05,
        ]);

        $metrics = $this->service->getSettingsMetrics($this->copropiedad);

        $this->assertEquals(3, $metrics['total_unidades']);
        $this->assertEquals(2, $metrics['total_modelos']);
        $this->assertEquals(12500.50, (float)$metrics['area_construida_total']);
        $this->assertEquals(250.0, (float)$metrics['area_privada_total']); // 65 + 65 + 120
        $this->assertFalse($metrics['unit_types_locked']);
        $this->assertFalse($metrics['payments_enabled']);
    }

    public function test_updates_settings_merging_defaults_and_preserves_unspecified(): void
    {
        $payload = [
            'area_construida_total' => 15000.00,
            'settings' => [
                'payments_enabled' => true,
                'wompi_public_key' => 'pub_test_12345',
                'interes_mora' => 2.0,
                'pqrs_enabled' => true,
                'reservas_enabled' => false,
            ],
        ];

        $updated = $this->service->updateSettings($this->copropiedad, $payload);

        $this->assertTrue($updated);
        $fresh = $this->copropiedad->fresh();

        $this->assertEquals(15000.00, (float)$fresh->area_construida_total);
        $this->assertTrue($fresh->settings['payments_enabled']);
        $this->assertEquals('pub_test_12345', $fresh->settings['wompi_public_key']);
        $this->assertEquals(2.0, (float)$fresh->settings['interes_mora']);
        $this->assertTrue($fresh->settings['pqrs_enabled']);
        $this->assertFalse($fresh->settings['reservas_enabled']);
    }

    public function test_can_delete_unlinked_unit_model(): void
    {
        $comp = ComponenteUnidad::create(['nombre' => 'Balcón']);
        $tipo = TipoUnidad::create([
            'copropiedad_id' => $this->copropiedad->id,
            'nombre' => 'Modelo Temporal',
            'area_m2' => 50.0,
        ]);
        $tipo->componentes()->attach($comp->id, ['cantidad' => 1]);

        $deleted = $this->service->deleteTipoUnidad($tipo);

        $this->assertTrue($deleted);
        $this->assertDatabaseMissing('property.tipos_unidad', ['id' => $tipo->id]);
    }

    public function test_prevents_deleting_unit_model_if_linked_to_units(): void
    {
        $tipo = TipoUnidad::create([
            'copropiedad_id' => $this->copropiedad->id,
            'nombre' => 'Modelo Con Unidades',
            'area_m2' => 80.0,
        ]);

        Unidad::create([
            'copropiedad_id' => $this->copropiedad->id,
            'tipo_unidad_id' => $tipo->id,
            'nombre' => '201',
            'torre' => '1',
            'area_m2' => 80.0,
            'coeficiente' => 0.02,
        ]);

        $this->expectException(\DomainException::class);
        $this->service->deleteTipoUnidad($tipo);
    }
}

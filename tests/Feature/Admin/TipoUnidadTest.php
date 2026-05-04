<?php

namespace Tests\Feature\Admin;

use App\Models\ComponenteUnidad;
use App\Models\Copropiedad;
use App\Models\TipoUnidad;
use App\Models\Unidad;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TipoUnidadTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_unit_type_with_components_and_quantities(): void
    {
        // 1. Setup: Crear copropiedad y componentes base
        $copropiedad = Copropiedad::factory()->create();
        $hab = ComponenteUnidad::create(['nombre' => 'Habitación']);
        $bano = ComponenteUnidad::create(['nombre' => 'Baño']);

        // 2. Action: Crear un Tipo de Unidad (Modelo)
        $tipo = TipoUnidad::create([
            'copropiedad_id' => $copropiedad->id,
            'nombre' => 'Apartamento Estándar Tipo A',
            'area_m2' => 75.50
        ]);

        // 3. Action: Asociar componentes con cantidades (Pivot)
        $tipo->componentes()->attach([
            $hab->id => ['cantidad' => 3],
            $bano->id => ['cantidad' => 2]
        ]);

        // 4. Assert: Verificar en base de datos
        $this->assertDatabaseHas('tipos_unidad', [
            'nombre' => 'Apartamento Estándar Tipo A',
            'area_m2' => 75.50
        ]);

        $this->assertCount(2, $tipo->componentes);
        
        $this->assertEquals(3, $tipo->componentes()->where('nombre', 'Habitación')->first()->pivot->cantidad);
        $this->assertEquals(2, $tipo->componentes()->where('nombre', 'Baño')->first()->pivot->cantidad);
    }

    public function test_can_link_real_unit_to_a_unit_type(): void
    {
        $copropiedad = Copropiedad::factory()->create();
        $tipo = TipoUnidad::create([
            'copropiedad_id' => $copropiedad->id,
            'nombre' => 'Penthouse Duplex',
            'area_m2' => 150.00
        ]);

        $unidad = Unidad::factory()->create([
            'copropiedad_id' => $copropiedad->id,
            'nombre' => '1001',
            'tipo_unidad_id' => $tipo->id
        ]);

        $this->assertEquals('Penthouse Duplex', $unidad->tipoUnidad->nombre);
        $this->assertEquals(150.00, $unidad->tipoUnidad->area_m2);
    }

    public function test_can_generate_vertical_units_in_bulk(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        $copropiedad = Copropiedad::factory()->create();
        $user->managedCopropiedades()->attach($copropiedad->id);
        $user->update(['current_copropiedad_id' => $copropiedad->id]);

        $this->actingAs($user);

        $payload = [
            'structure_type' => 'vertical',
            'prefix' => 'Torre',
            'towers' => 2,
            'floors' => 5,
            'units_per_floor' => 4,
            'numbering_type' => 'floor', // 101, 102
            'separator' => '-'
        ];

        $response = $this->post(route('admin.settings.generate_units'), $payload);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        // 2 towers * 5 floors * 4 units = 40 units
        $this->assertDatabaseCount('unidades', 40);

        // Check a specific unit name
        $this->assertDatabaseHas('unidades', [
            'copropiedad_id' => $copropiedad->id,
            'nombre' => 'Torre 1-101'
        ]);

        $this->assertDatabaseHas('unidades', [
            'copropiedad_id' => $copropiedad->id,
            'nombre' => 'Torre 2-504'
        ]);

        $this->assertTrue($copropiedad->fresh()->unit_types_locked);
    }

    public function test_can_generate_horizontal_units_in_bulk(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        $copropiedad = Copropiedad::factory()->create();
        $user->managedCopropiedades()->attach($copropiedad->id);
        $user->update(['current_copropiedad_id' => $copropiedad->id]);

        $this->actingAs($user);

        $payload = [
            'structure_type' => 'horizontal',
            'prefix' => 'Casa',
            'total_units' => 150,
            'separator' => 'space'
        ];

        $response = $this->post(route('admin.settings.generate_units'), $payload);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseCount('unidades', 150);

        $this->assertDatabaseHas('unidades', [
            'copropiedad_id' => $copropiedad->id,
            'nombre' => 'Casa 1'
        ]);

        $this->assertDatabaseHas('unidades', [
            'copropiedad_id' => $copropiedad->id,
            'nombre' => 'Casa 150'
        ]);
        
        $this->assertTrue($copropiedad->fresh()->unit_types_locked);
    }
}

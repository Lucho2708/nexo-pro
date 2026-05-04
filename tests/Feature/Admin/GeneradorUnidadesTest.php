<?php

namespace Tests\Feature\Admin;

use App\Models\Copropiedad;
use App\Models\Unidad;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GeneradorUnidadesTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_bulk_generate_units_mathematically_accurate()
    {
        $copropiedad = Copropiedad::create(['nit' => '999', 'nombre' => 'Test', 'direccion' => '-', 'ciudad' => '-']);
        $admin = User::create([
            'name' => 'Admin Gen',
            'email' => 'gen@t.com',
            'password' => 'x',
            'role' => 'admin',
            'current_copropiedad_id' => $copropiedad->id
        ]);
        $admin->managedCopropiedades()->attach($copropiedad->id);

        $tipo = \App\Models\TipoUnidad::create([
            'copropiedad_id' => $copropiedad->id,
            'nombre' => 'Apto',
            'area_m2' => 50
        ]);

        $payload = [
            'torre' => 'Torre A',
            'pisos' => 5,
            'aptos_por_piso' => 4,
            'default_coeficiente' => 1.0,
            'tipo_unidad_id' => $tipo->id
        ];

        // Action
        $response = $this->actingAs($admin)
            ->post(route('unidades.bulk-generate'), $payload);

        // Assertion
        $response->assertRedirect();
        
        // 5 * 4 = 20 unidades creadas
        $this->assertEquals(20, Unidad::where('copropiedad_id', $copropiedad->id)->count());

        // Verificar la nomenclatura: Piso 3, Apto 2 = 302
        $this->assertDatabaseHas('unidades', [
            'copropiedad_id' => $copropiedad->id,
            'torre' => 'Torre A',
            'nombre' => '302',
            'piso' => 3,
            'coeficiente' => 1.0,
            'saldo_actual' => 0
        ]);
        
        // Piso 1, Apto 4 = 104
        $this->assertDatabaseHas('unidades', [
            'nombre' => '104',
            'torre' => 'Torre A'
        ]);
        
        // Piso 5, Apto 4 = 504
        $this->assertDatabaseHas('unidades', [
            'nombre' => '504'
        ]);
    }
}

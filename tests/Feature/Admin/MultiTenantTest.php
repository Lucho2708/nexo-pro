<?php

namespace Tests\Feature\Admin;

use App\Models\Copropiedad;
use App\Models\User;
use App\Models\Unidad;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MultiTenantTest extends TestCase
{
    use RefreshDatabase;

    public function test_an_admin_can_switch_to_a_managed_copropiedad()
    {
        $admin = User::create([
            'name' => 'Multi Admin',
            'email' => 'admin@multi.com',
            'password' => bcrypt('password'),
            'role' => 'admin'
        ]);

        $copropiedadA = Copropiedad::create(['nit' => '111', 'nombre' => 'A', 'direccion' => 'A', 'ciudad' => 'C']);
        $copropiedadB = Copropiedad::create(['nit' => '222', 'nombre' => 'B', 'direccion' => 'B', 'ciudad' => 'C']);
        
        $admin->managedCopropiedades()->attach([$copropiedadA->id, $copropiedadB->id]);
        $admin->update(['current_copropiedad_id' => $copropiedadA->id]);

        $this->actingAs($admin)
            ->post(route('tenant.switch', $copropiedadB))
            ->assertRedirect();
            
        $this->assertEquals($copropiedadB->id, $admin->fresh()->current_copropiedad_id);
    }

    public function test_an_admin_cannot_switch_to_an_unmanaged_copropiedad()
    {
        $admin = User::create(['name' => 'A2', 'email' => 'a2@t.com', 'password' => 'x', 'role' => 'admin']);
        
        $copropiedadA = Copropiedad::create(['nit' => '333', 'nombre' => 'C', 'direccion' => 'C', 'ciudad' => 'C']);
        $copropiedadStranger = Copropiedad::create(['nit' => '444', 'nombre' => 'D', 'direccion' => 'D', 'ciudad' => 'C']);
        
        $admin->managedCopropiedades()->attach($copropiedadA->id);
        $admin->update(['current_copropiedad_id' => $copropiedadA->id]);

        $this->actingAs($admin)
            ->post(route('tenant.switch', $copropiedadStranger))
            ->assertStatus(403); // Forbidden
            
        $this->assertEquals($copropiedadA->id, $admin->fresh()->current_copropiedad_id);
    }

    public function test_an_owner_can_switch_if_they_own_units_in_target()
    {
        $owner = User::create(['name' => 'Owner', 'email' => 'own@t.com', 'password' => 'x', 'role' => 'owner']);
        
        $copropiedadA = Copropiedad::create(['nit' => '555', 'nombre' => 'E', 'direccion' => 'E', 'ciudad' => 'E']);
        $copropiedadB = Copropiedad::create(['nit' => '666', 'nombre' => 'F', 'direccion' => 'F', 'ciudad' => 'F']);

        $unidadB = Unidad::create([
            'copropiedad_id' => $copropiedadB->id,
            'nombre' => '101',
            'torre' => 'Unica',
            'saldo_actual' => 0
        ]);
        
        $owner->unidades()->attach($unidadB->id, ['role' => 'propietario']);
        $owner->update(['current_copropiedad_id' => $copropiedadA->id]);

        $this->actingAs($owner)
            ->post(route('tenant.switch', $copropiedadB))
            ->assertRedirect();
            
        $this->assertEquals($copropiedadB->id, $owner->fresh()->current_copropiedad_id);
    }
}

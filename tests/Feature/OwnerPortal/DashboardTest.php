<?php

namespace Tests\Feature\OwnerPortal;

use App\Models\ConceptoCobro;
use App\Models\Copropiedad;
use App\Models\Unidad;
use App\Modules\IAM\Models\User;
use App\Models\Transaccion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Inertia\Testing\AssertableInertia as Assert;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->copropiedad = Copropiedad::create([
            'nombre' => 'Residencial Nexus', 
            'direccion' => 'Calle 100',
            'nit' => '1234567-9',
            'ciudad' => 'Medellin'
        ]);
        
        $this->user = User::factory()->create([
            'current_copropiedad_id' => $this->copropiedad->id,
            'role' => 'owner'
        ]);
        $this->otherUser = User::factory()->create([
            'current_copropiedad_id' => $this->copropiedad->id,
            'role' => 'owner'
        ]);
    }

    public function test_an_owner_can_see_their_units_and_balance()
    {
        $unidad = Unidad::create([
            'copropiedad_id' => $this->copropiedad->id,
            'nombre' => '101',
            'torre' => 'A',
            'saldo_actual' => 500000
        ]);

        $this->user->unidades()->attach($unidad->id, ['role' => 'propietario']);

        $response = $this->actingAs($this->user)
            ->get(route('owner.dashboard'));

        $response->assertStatus(200);
        $response->assertInertia(fn (Assert $page) => $page
            ->component('Owner/Dashboard')
            ->has('unidades', 1)
            ->where('unidades.0.id', $unidad->id)
            ->where('total_saldo', 500000)
        );
    }

    public function test_an_owner_cannot_see_units_they_do_not_own()
    {
        $otherUnidad = Unidad::create([
            'copropiedad_id' => $this->copropiedad->id,
            'nombre' => '999',
            'torre' => 'X',
            'saldo_actual' => 1000000
        ]);

        $this->otherUser->unidades()->attach($otherUnidad->id, ['role' => 'propietario']);

        $response = $this->actingAs($this->user)
            ->get(route('owner.dashboard'));

        $response->assertStatus(200);
        $response->assertInertia(fn (Assert $page) => $page
            ->component('Owner/Dashboard')
            ->has('unidades', 0)
            ->where('total_saldo', 0)
        );
    }
}

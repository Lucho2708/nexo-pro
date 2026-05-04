<?php

namespace Tests\Feature\Asamblea;

use App\Models\Asamblea;
use App\Models\Copropiedad;
use App\Models\Unidad;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AsambleaAccessTest extends TestCase
{
    use RefreshDatabase;

    protected Copropiedad $copropiedad;
    protected Asamblea $asamblea;

    protected function setUp(): void
    {
        parent::setUp();
        $this->copropiedad = Copropiedad::factory()->create([
            'settings' => array_merge(Copropiedad::defaultSettings(), ['asamblea_virtual_enabled' => true])
        ]);
        $this->asamblea = Asamblea::factory()->create([
            'copropiedad_id' => $this->copropiedad->id,
            'status' => 'in_progress'
        ]);
    }

    public function test_owner_can_access_assembly_of_their_property(): void
    {
        $user = User::factory()->create([
            'role' => 'owner',
            'current_copropiedad_id' => $this->copropiedad->id
        ]);
        $unidad = Unidad::factory()->create(['copropiedad_id' => $this->copropiedad->id]);
        $user->unidades()->attach($unidad->id, ['role' => 'propietario']);

        $response = $this->actingAs($user)->get(route('asambleas.show', $this->asamblea->id));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('Asamblea/Show')
            ->has('token')
            ->where('asamblea.id', $this->asamblea->id)
        );
    }

    public function test_user_cannot_access_assembly_of_another_property(): void
    {
        $otraCopropiedad = Copropiedad::factory()->create();
        $user = User::factory()->create(['role' => 'owner']);
        $unidad = Unidad::factory()->create(['copropiedad_id' => $otraCopropiedad->id]);
        $user->unidades()->attach($unidad->id, ['role' => 'propietario']);

        $response = $this->actingAs($user)->get(route('asambleas.show', $this->asamblea->id));

        $response->assertStatus(403);
    }

    public function test_admin_can_access_assembly_of_their_managed_property(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'current_copropiedad_id' => $this->copropiedad->id
        ]);
        $admin->managedCopropiedades()->attach($this->copropiedad->id);

        $response = $this->actingAs($admin)->get(route('asambleas.show', $this->asamblea->id));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->where('is_admin', true)
        );
    }

    public function test_cannot_access_non_existent_assembly(): void
    {
        $user = User::factory()->create(['role' => 'owner']);
        $response = $this->actingAs($user)->get('/asambleas/non-existent-uuid');

        $response->assertStatus(404);
    }
}

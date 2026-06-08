<?php

namespace Tests\Feature;

use App\Modules\Property\Models\Copropiedad;
use App\Modules\IAM\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StandaloneRestrictionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_standalone_admin_is_redirected_from_dashboard_to_asambleas()
    {
        $copropiedad = Copropiedad::factory()->create([
            'settings' => array_merge(Copropiedad::defaultSettings(), [
                'is_standalone' => true
            ])
        ]);

        $admin = User::factory()->create([
            'role' => 'admin',
            'current_copropiedad_id' => $copropiedad->id
        ]);

        $response = $this->actingAs($admin)->get(route('dashboard'));

        $response->assertRedirect(route('admin.asambleas.index'));
    }

    /** @test */
    public function test_standalone_admin_cannot_access_cartera_module()
    {
        $copropiedad = Copropiedad::factory()->create([
            'settings' => array_merge(Copropiedad::defaultSettings(), [
                'is_standalone' => true
            ])
        ]);

        $admin = User::factory()->create([
            'role' => 'admin',
            'current_copropiedad_id' => $copropiedad->id
        ]);

        $response = $this->actingAs($admin)->get(route('cartera.index'));

        $response->assertRedirect(route('admin.asambleas.index'));
        $response->assertSessionHas('error');
    }

    /** @test */
    public function test_regular_admin_can_still_access_everything()
    {
        $copropiedad = Copropiedad::factory()->create(); // Regular property

        $admin = User::factory()->create([
            'role' => 'admin',
            'current_copropiedad_id' => $copropiedad->id
        ]);

        $response = $this->actingAs($admin)->get(route('cartera.index'));

        $response->assertStatus(200);
    }
}

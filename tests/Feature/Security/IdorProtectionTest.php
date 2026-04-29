<?php

namespace Tests\Feature\Security;

use App\Models\User;
use App\Models\Pqrs;
use App\Models\Copropiedad;
use App\Models\Unidad;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IdorProtectionTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Step 1: Reproduction of IDOR Vulnerability.
     * With Multitenantable trait, cross-tenant access returns 404 instead of 403
     * because the resource is not found in the scoped query.
     */
    public function test_a_user_cannot_download_a_pqrs_belonging_to_another_user_copropiedad()
    {
        // User 1 in Copropiedad A
        $copropiedadA = Copropiedad::factory()->create();
        $userA = User::factory()->create(['role' => 'owner', 'current_copropiedad_id' => $copropiedadA->id]);
        $unidadA = Unidad::factory()->create(['copropiedad_id' => $copropiedadA->id]);
        $pqrsA = Pqrs::factory()->create([
            'user_id' => $userA->id,
            'unidad_id' => $unidadA->id,
            'estado' => 'abierto',
            'copropiedad_id' => $copropiedadA->id
        ]);

        // User 2 in Copropiedad B (Malicious or unauthorized access attempt)
        $copropiedadB = Copropiedad::factory()->create();
        $userB = User::factory()->create(['role' => 'owner', 'current_copropiedad_id' => $copropiedadB->id]);

        $response = $this->actingAs($userB)
            ->get(route('pqrs.download', $pqrsA));
        
        $response->assertStatus(404);
    }

    public function test_an_admin_cannot_access_a_pqrs_from_another_copropiedad()
    {
        $copropiedadA = Copropiedad::factory()->create();
        $unidadA = Unidad::factory()->create(['copropiedad_id' => $copropiedadA->id]);
        $pqrsA = Pqrs::factory()->create(['unidad_id' => $unidadA->id, 'copropiedad_id' => $copropiedadA->id]);

        $copropiedadB = Copropiedad::factory()->create();
        $adminB = User::factory()->create(['role' => 'admin', 'current_copropiedad_id' => $copropiedadB->id]);

        $response = $this->actingAs($adminB)
            ->patch(route('pqrs.update', $pqrsA), ['respuesta' => 'Hack Attempt']);
            
        $response->assertStatus(404);
    }

    public function test_an_admin_cannot_download_account_statement_from_another_copropiedad()
    {
        $copropiedadA = Copropiedad::factory()->create();
        $unidadA = Unidad::factory()->create(['copropiedad_id' => $copropiedadA->id]);

        $copropiedadB = Copropiedad::factory()->create();
        $adminB = User::factory()->create(['role' => 'admin', 'current_copropiedad_id' => $copropiedadB->id]);

        $response = $this->actingAs($adminB)
            ->get(route('cartera.statement.download', $unidadA));
            
        $response->assertStatus(404);
    }
}

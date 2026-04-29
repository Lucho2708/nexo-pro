<?php

namespace Tests\Feature\Admin;

use App\Models\Copropiedad;
use App\Models\Pqrs;
use App\Models\Unidad;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PqrsManagementTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $superAdmin;
    private Pqrs $pqrs;

    protected function setUp(): void
    {
        parent::setUp();

        $copropiedad = Copropiedad::factory()->create([
            'nombre' => 'Edificio Test',
        ]);

        $this->admin = User::factory()->create([
            'role' => 'admin',
            'current_copropiedad_id' => $copropiedad->id
        ]);

        $this->superAdmin = User::factory()->create([
            'role' => 'super_admin'
        ]);

        $unidad = Unidad::factory()->create([
            'copropiedad_id' => $copropiedad->id,
            'nombre' => '101',
            'torre' => 'A'
        ]);

        $resident = User::factory()->create([
            'role' => 'owner',
            'current_copropiedad_id' => $copropiedad->id
        ]);

        $this->pqrs = Pqrs::create([
            'unidad_id' => $unidad->id,
            'user_id' => $resident->id,
            'copropiedad_id' => $copropiedad->id,
            'tipo' => 'queja',
            'asunto' => 'Prueba',
            'mensaje' => 'Mensaje de prueba',
            'prioridad' => 'media',
            'estado' => 'abierto'
        ]);
    }

    public function test_admin_can_respond_and_close_pqrs()
    {
        $this->actingAs($this->admin);

        $response = $this->patch(route('pqrs.update', $this->pqrs->id), [
            'respuesta' => 'Solucionado.',
            'cerrar' => true
        ]);

        $response->assertStatus(302); // Redirect back
        $this->assertDatabaseHas('pqrs', [
            'id' => $this->pqrs->id,
            'respuesta' => 'Solucionado.',
            'estado' => 'cerrado'
        ]);
    }

    public function test_super_admin_can_respond_pqrs()
    {
        $this->actingAs($this->superAdmin);

        $response = $this->patch(route('pqrs.update', $this->pqrs->id), [
            'respuesta' => 'Respuesta desde SuperAdmin.',
            'cerrar' => false
        ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('pqrs', [
            'id' => $this->pqrs->id,
            'respuesta' => 'Respuesta desde SuperAdmin.',
            'estado' => 'en_proceso'
        ]);
    }
}

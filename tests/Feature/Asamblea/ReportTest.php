<?php

use App\Models\Copropiedad;
use App\Modules\IAM\Models\User;
use App\Models\Unidad;
use App\Models\Asamblea;
use App\Models\Pregunta;
use App\Models\Opcion;
use App\Models\Voto;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('an admin can download the assembly audit report', function () {
    // 1. Setup
    $copropiedad = Copropiedad::factory()->create([
        'nombre' => 'Residencias Elite',
        'settings' => ['asamblea_virtual_enabled' => true]
    ]);
    $admin = User::factory()->create(['current_copropiedad_id' => $copropiedad->id, 'role' => 'admin']);
    
    $asamblea = Asamblea::factory()->create([
        'copropiedad_id' => $copropiedad->id,
        'titulo' => 'Asamblea de Prueba',
        'status' => 'finished'
    ]);

    // Add some questions and votes
    $pregunta = Pregunta::create(['asamblea_id' => $asamblea->id, 'titulo' => 'Pregunta 1', 'status' => 'closed']);
    $opcion = Opcion::create(['pregunta_id' => $pregunta->id, 'titulo' => 'SÍ']);
    Voto::create([
        'pregunta_id' => $pregunta->id,
        'user_id' => $admin->id,
        'unidad_id' => Unidad::factory()->create(['copropiedad_id' => $copropiedad->id])->id,
        'opcion_id' => $opcion->id,
        'peso' => 0.1
    ]);

    $this->actingAs($admin);

    // 2. Act
    $response = $this->get(route('asambleas.report', $asamblea));

    // 3. Assert
    $response->assertStatus(200);
    $response->assertHeader('Content-Type', 'application/pdf');
});

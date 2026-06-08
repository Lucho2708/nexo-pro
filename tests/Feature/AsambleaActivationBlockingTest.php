<?php

use App\Modules\Property\Models\Copropiedad;
use App\Modules\IAM\Models\User;
use Illuminate\Support\Facades\Auth;

beforeEach(function () {
    $this->copropiedad = Copropiedad::factory()->create([
        'settings' => array_merge(Copropiedad::defaultSettings(), ['asamblea_virtual_active' => false])
    ]);
    
    $this->admin = User::factory()->create([
        'role' => 'admin',
        'current_copropiedad_id' => $this->copropiedad->id
    ]);
    
    $this->admin->managedCopropiedades()->attach($this->copropiedad->id);
});

test('admin cannot access assembly index if module is inactive', function () {
    $this->actingAs($this->admin)
        ->get(route('admin.asambleas.index'))
        ->assertStatus(403)
        ->assertSee('El módulo de Asamblea Virtual no está activo para este conjunto');
});

test('admin can access assembly index if module is active', function () {
    $this->copropiedad->update(['settings' => array_merge($this->copropiedad->settings, ['asamblea_virtual_active' => true])]);
    
    $this->actingAs($this->admin)
        ->get(route('admin.asambleas.index'))
        ->assertStatus(200);
});

test('actions are audited', function () {
    $this->copropiedad->update(['settings' => array_merge($this->copropiedad->settings, ['asamblea_virtual_active' => true])]);
    
    $this->actingAs($this->admin)
        ->post(route('admin.asambleas.store'), [
            'titulo' => 'Asamblea de Prueba',
            'fecha' => now()->addDays(7)->format('Y-m-d'),
            'hora_inicio' => '18:00',
            'quorum_esperado' => 51
        ])
        ->assertStatus(302);

    $this->assertDatabaseHas('operations.feature_usage_logs', [
        'feature' => 'ASAMBLEA',
        'action' => 'CREATE_ASSEMBLY',
        'user_id' => $this->admin->id
    ]);
});

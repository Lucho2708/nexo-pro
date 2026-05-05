<?php

use App\Modules\IAM\Models\User;
use App\Models\Copropiedad;
use App\Models\Unidad;
use App\Models\ZonaComun;
use App\Models\Pqrs;
use App\Models\Announcement;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->copropiedad = Copropiedad::factory()->create(['license_status' => 'active']);
    
    $this->superAdmin = User::factory()->create(['role' => 'super_admin']);
    
    $this->admin = User::factory()->create([
        'role' => 'admin',
        'current_copropiedad_id' => $this->copropiedad->id
    ]);
    $this->admin->managedCopropiedades()->attach($this->copropiedad->id);

    $this->owner = User::factory()->create([
        'role' => 'owner',
        'current_copropiedad_id' => $this->copropiedad->id
    ]);
    $this->unidad = Unidad::factory()->create(['copropiedad_id' => $this->copropiedad->id]);
    $this->owner->unidades()->attach($this->unidad->id, ['role' => 'owner']);
});

/**
 * 1. RUTAS PÚBLICAS Y GUEST
 */
test('public and guest routes are accessible', function () {
    $this->get(route('home'))->assertStatus(200);
    $this->get(route('login'))->assertStatus(200);
    $this->get(route('register'))->assertStatus(200);
});

/**
 * 2. RUTAS DE SUPER ADMIN
 */
test('super admin routes are restricted correctly', function () {
    $routes = [
        'superadmin.dashboard',
        'superadmin.licenses.index',
        'superadmin.users.index',
        'superadmin.audit',
        'superadmin.announcements.index',
    ];

    foreach ($routes as $routeName) {
        // SuperAdmin can access
        $this->actingAs($this->superAdmin)->get(route($routeName))->assertStatus(200);
        
        // Admin cannot access (403)
        $this->actingAs($this->admin)->get(route($routeName))->assertStatus(403);
        
        // Owner cannot access (403)
        $this->actingAs($this->owner)->get(route($routeName))->assertStatus(403);
    }
});

/**
 * 3. RUTAS DE ADMINISTRADOR
 */
test('admin routes are restricted correctly', function () {
    $routes = [
        'dashboard',
        'cartera.index',
        'admin.zonas.index',
        'admin.reservas.index',
        'admin.copropiedades.create',
    ];

    foreach ($routes as $routeName) {
        // Admin can access
        $this->actingAs($this->admin)->get(route($routeName))->assertStatus(200);
        
        $response = $this->actingAs($this->owner)->get(route($routeName));
        if ($routeName === 'dashboard') {
            $response->assertStatus(302);
        } else {
            $response->assertStatus(403);
        }
    }
});

/**
 * 4. RUTAS DE PROPIETARIO (OWNER)
 */
test('owner routes are restricted correctly', function () {
    $routes = [
        'owner.dashboard',
        'reservas.index',
        'pqrs.index',
    ];

    foreach ($routes as $routeName) {
        // Owner can access
        $this->actingAs($this->owner)->get(route($routeName))->assertStatus(200);
        
        // Admin cannot access owner dashboard (Ensures EnsureOwner middleware works)
        if ($routeName === 'owner.dashboard') {
            $this->actingAs($this->admin)->get(route($routeName))->assertStatus(403);
        }
    }
});

/**
 * 5. RUTAS COMPARTIDAS / COMUNES
 */
test('shared routes are accessible by authenticated users', function () {
    $this->actingAs($this->owner)->get(route('profile.edit'))->assertStatus(200);
    $this->actingAs($this->admin)->get(route('profile.edit'))->assertStatus(200);
});

/**
 * 6. VALIDACIÓN DE LICENCIA (MIDDLEWARE)
 */
test('protected routes are blocked if license is suspended', function () {
    $this->copropiedad->update(['license_status' => 'suspended']);
    
    // Should redirect to a "suspended" or error page, or abort 403
    // In our implementation, check-license aborts 403 or redirects.
    $this->actingAs($this->admin)->get(route('dashboard'))->assertStatus(403);
    $this->actingAs($this->owner)->get(route('owner.dashboard'))->assertStatus(403);
});

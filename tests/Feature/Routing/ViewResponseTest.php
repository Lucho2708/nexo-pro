<?php

use App\Models\User;
use App\Models\Copropiedad;
use App\Models\Unidad;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;

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

test('guest sees the Welcome component on home route', function () {
    $this->get(route('home'))
        ->assertInertia(fn (Assert $page) => $page
            ->component('Welcome')
        );
});

test('guest sees the Login component on login route', function () {
    $this->get(route('login'))
        ->assertInertia(fn (Assert $page) => $page
            ->component('Auth/Login')
        );
});

test('admin sees the Dashboard component with required props', function () {
    $this->actingAs($this->admin)
        ->get(route('dashboard'))
        ->assertInertia(fn (Assert $page) => $page
            ->component('Dashboard')
            ->has('stats')
            ->has('chartData')
            ->has('notifications')
        );
});

test('owner sees the Owner/Dashboard component with required props', function () {
    $this->actingAs($this->owner)
        ->get(route('owner.dashboard'))
        ->assertInertia(fn (Assert $page) => $page
            ->component('Owner/Dashboard')
            ->has('unidades')
            ->has('total_saldo')
            ->has('transacciones')
            ->has('features')
        );
});

test('super admin sees the SuperAdmin/Dashboard component with platform metrics', function () {
    $this->actingAs($this->superAdmin)
        ->get(route('superadmin.dashboard'))
        ->assertInertia(fn (Assert $page) => $page
            ->component('SuperAdmin/Dashboard')
            ->has('copropiedades')
            ->has('metrics.total_users')
            ->has('metrics.total_conjuntos')
        );
});

test('admin sees the Cartera/Index component with financial data', function () {
    $this->actingAs($this->admin)
        ->get(route('cartera.index'))
        ->assertInertia(fn (Assert $page) => $page
            ->component('Cartera/Index')
            ->has('unidades.data')
            ->has('conceptos')
            ->has('stats')
            ->has('filters')
        );
});

<?php

use App\Models\Copropiedad;
use App\Models\FeatureUsageLog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->superAdmin = User::factory()->create([
        'role' => 'super_admin',
    ]);

    $this->admin = User::factory()->create([
        'role' => 'admin',
    ]);

    $this->copropiedad = Copropiedad::factory()->create();
});

test('a super admin can view the audit logs', function () {
    FeatureUsageLog::create([
        'user_id' => $this->admin->id,
        'copropiedad_id' => $this->copropiedad->id,
        'feature' => 'dashboard',
        'used_at' => now(),
    ]);

    $response = $this->actingAs($this->superAdmin)
        ->get(route('superadmin.audit'));

    $response->assertStatus(200);
    $response->assertInertia(fn (Assert $page) => $page
        ->component('SuperAdmin/Audit/Index')
        ->has('logs.data', 1)
        ->has('features')
        ->has('chartData')
    );
});

test('non-super admins cannot view the audit logs', function () {
    $response = $this->actingAs($this->admin)
        ->get(route('superadmin.audit'));

    $response->assertStatus(403);
});

test('it can filter audit logs by feature', function () {
    FeatureUsageLog::create([
        'user_id' => $this->admin->id,
        'copropiedad_id' => $this->copropiedad->id,
        'feature' => 'dashboard',
        'used_at' => now(),
    ]);

    FeatureUsageLog::create([
        'user_id' => $this->admin->id,
        'copropiedad_id' => $this->copropiedad->id,
        'feature' => 'reservas',
        'used_at' => now()->subDay(),
    ]);

    $response = $this->actingAs($this->superAdmin)
        ->get(route('superadmin.audit', ['feature' => 'reservas']));

    $response->assertStatus(200);
    $response->assertInertia(fn (Assert $page) => $page
        ->component('SuperAdmin/Audit/Index')
        ->has('logs.data', 1) // Only one log matches 'reservas'
        ->where('logs.data.0.feature', 'reservas')
    );
});

test('the usage chart data groups logs correctly by used_at instead of created_at', function () {
    // This indirectly tests our SQLSTATE[42S22] bug fix.
    // If it fails with 'Column not found: 1054 Unknown column created_at', the test will blow up.
    FeatureUsageLog::create([
        'user_id' => $this->admin->id,
        'copropiedad_id' => $this->copropiedad->id,
        'feature' => 'dashboard',
        'used_at' => now(),
    ]);

    $response = $this->actingAs($this->superAdmin)
        ->get(route('superadmin.audit'));

    $response->assertStatus(200);
    $response->assertInertia(fn (Assert $page) => $page
        ->has('chartData.categories', 16) // Default 15 days ago + today = 16
        ->has('chartData.series.0.data', 16)
    );
});

<?php

use App\Modules\IAM\Models\User;
use App\Modules\Property\Models\Copropiedad;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;

uses(RefreshDatabase::class);

test('an admin with multiple copropiedades sees them in available_copropiedades prop', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $copro1 = Copropiedad::factory()->create();
    $copro2 = Copropiedad::factory()->create();
    $copro3 = Copropiedad::factory()->create();

    $admin->managedCopropiedades()->attach([$copro1->id, $copro2->id, $copro3->id]);
    $admin->update(['current_copropiedad_id' => $copro1->id]);

    $this->actingAs($admin)
        ->get(route('dashboard'))
        ->assertInertia(fn (Assert $page) => $page
            ->has('auth.user.available_copropiedades', 3)
            ->where('auth.user.available_copropiedades.0.id', $copro1->id)
            ->where('auth.user.available_copropiedades.1.id', $copro2->id)
            ->where('auth.user.available_copropiedades.2.id', $copro3->id)
        );
});

test('a super admin sees all copropiedades in available_copropiedades prop', function () {
    $superAdmin = User::factory()->create(['role' => 'super_admin']);
    Copropiedad::factory()->count(5)->create();

    $expectedCount = Copropiedad::count();

    $this->actingAs($superAdmin)
        ->get(route('superadmin.dashboard'))
        ->assertInertia(fn (Assert $page) => $page
            ->has('auth.user.available_copropiedades', $expectedCount)
        );
});

<?php

namespace Tests\Feature\Auth;

use App\Modules\IAM\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TwoFactorAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_without_2fa_has_access_when_disabled_globally()
    {
        // 2FA está deshabilitado por defecto (ahora falso en la migración)
        $user = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
    }

    public function test_user_without_2fa_is_redirected_to_setup_when_enabled_globally()
    {
        // Forzamos la habilitación global
        \App\Models\GlobalSetting::set('2fa_enabled', true);
        
        $user = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertRedirect(route('two-factor.setup'));
    }

    public function test_user_with_2fa_can_access_dashboard()
    {
        $user = User::factory()->create([
            'role' => 'admin',
            'two_factor_secret' => 'secret-key',
            'two_factor_confirmed_at' => now(),
        ]);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
    }

    public function test_super_admin_can_reset_user_2fa()
    {
        $superAdmin = User::factory()->create([
            'role' => 'super_admin',
            'two_factor_secret' => 'super-secret',
            'two_factor_confirmed_at' => now(),
        ]);
        
        $user = User::factory()->create([
            'two_factor_secret' => 'secret-key',
            'two_factor_confirmed_at' => now(),
        ]);

        $response = $this->actingAs($superAdmin)
            ->post("/super-admin/users/{$user->id}/reset-2fa");

        $response->assertStatus(302); // Redirect back
        $this->assertNull($user->fresh()->two_factor_secret);
        $this->assertNull($user->fresh()->two_factor_confirmed_at);
    }
}

<?php

use App\Modules\IAM\Models\User;
use App\Modules\Property\Models\Copropiedad;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('redirects guests to login', function () {
    $response = $this->get('/dashboard');

    $response->assertRedirect('/login'); // Assuming Laravel default or we need to define it
});

it('renders the dashboard with stats for authenticated users', function () {
    $copropiedad = Copropiedad::create([
        'nit' => '900-1',
        'nombre' => 'Test Cop',
        'direccion' => 'Calle 1',
        'ciudad' => 'Medellin',
    ]);

    $user = User::create([
        'name' => 'Admin User',
        'email' => 'admin@test.com',
        'password' => bcrypt('password'),
        'current_copropiedad_id' => $copropiedad->id,
        'role' => 'admin',
    ]);

    $response = $this->actingAs($user)->get('/dashboard');

    $response->assertStatus(200);
    $response->assertInertia(fn ($page) => $page
        ->component('Dashboard')
        ->has('stats')
        ->has('chartData')
        ->has('overduePayments')
        ->has('notifications')
    );
});

it('can mark a notification as read', function () {
    $copropiedad = Copropiedad::create(['nit' => '900-1', 'nombre' => 'Test', 'direccion' => 'C1', 'ciudad' => 'M']);
    $user = User::create(['name' => 'Admin', 'email' => 'a@t.com', 'password' => 'pass', 'current_copropiedad_id' => $copropiedad->id, 'role' => 'admin']);
    
    $notification = $user->notifications()->create([
        'title' => 'Test Alert',
        'message' => 'Something happened',
    ]);

    $response = $this->actingAs($user)->patch(route('notifications.read', $notification));

    $response->assertRedirect();
    $this->assertNotNull($notification->fresh()->read_at);
});

<?php

use App\Models\Announcement;
use App\Models\Copropiedad;
use App\Modules\IAM\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->superAdmin = User::factory()->create(['role' => 'super_admin']);
    $this->copropiedad = Copropiedad::factory()->create(['nombre' => 'Conjunto A']);
    $this->admin = User::factory()->create(['role' => 'admin']);
    $this->owner = User::factory()->create(['role' => 'owner']);
});

it('can create an announcement linked to a copropiedad', function () {
    $this->actingAs($this->superAdmin);

    // This will fail because copropiedad_id is not in $fillable yet or column missing
    $announcement = Announcement::create([
        'title' => 'Aviso Conjunto A',
        'message' => 'Esto solo es para el conjunto A',
        'type' => 'info',
        'target_role' => 'owner',
        'copropiedad_id' => $this->copropiedad->id,
        'is_active' => true,
    ]);

    $this->assertDatabaseHas('announcements', [
        'id' => $announcement->id,
        'copropiedad_id' => $this->copropiedad->id
    ]);
});

it('allows users to mark announcements as read', function () {
    $announcement = Announcement::factory()->create([
        'is_active' => true,
        'target_role' => 'all'
    ]);

    // Will fail because route notifications.mark-as-read doesn't exist
    $this->actingAs($this->owner)
        ->post(route('notifications.mark-as-read', $announcement))
        ->assertOk();

    $this->assertDatabaseHas('announcement_user', [
        'announcement_id' => $announcement->id,
        'user_id' => $this->owner->id,
    ]);
});

it('only shows relevant announcements on dashboard', function () {
    $this->withoutExceptionHandling();
    // Global announcement
    Announcement::factory()->create([
        'title' => 'Global',
        'is_active' => true,
        'target_role' => 'all',
        'copropiedad_id' => null
    ]);

    // Specific announcement for Conjunto A
    Announcement::factory()->create([
        'title' => 'Solo Conjunto A',
        'is_active' => true,
        'target_role' => 'owner',
        'copropiedad_id' => $this->copropiedad->id
    ]);

    // Another property
    $otherCopropiedad = Copropiedad::factory()->create(['nombre' => 'Conjunto B']);
    Announcement::factory()->create([
        'title' => 'Solo Conjunto B',
        'is_active' => true,
        'target_role' => 'owner',
        'copropiedad_id' => $otherCopropiedad->id
    ]);

    // Owner is part of Conjunto A
    $this->owner->current_copropiedad_id = $this->copropiedad->id;
    $this->owner->save();

    $response = $this->followingRedirects()->actingAs($this->owner)->get(route('dashboard'));

    // Should work now as it follows the redirect to owner.dashboard
    $response->assertInertia(fn ($page) => $page
        ->has('announcements', 2)
        ->where('announcements.0.title', 'Global')
        ->where('announcements.1.title', 'Solo Conjunto A')
    );
});

<?php

use App\Models\Announcement;
use App\Modules\IAM\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->superAdmin = User::factory()->create(['role' => 'super_admin']);
    $this->regularUser = User::factory()->create(['role' => 'admin']);
});

it('prevents non-superadmin from accessing announcements', function () {
    $this->actingAs($this->regularUser)
        ->get(route('superadmin.announcements.index'))
        ->assertForbidden();
});

it('allows superadmin to view announcements', function () {
    $this->actingAs($this->superAdmin)
        ->get(route('superadmin.announcements.index'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page->component('SuperAdmin/Announcements/Index'));
});

it('allows superadmin to store a new announcement', function () {
    $data = [
        'title' => 'New Feature Update',
        'message' => 'We have updated the system.',
        'type' => 'info',
        'target_role' => 'all',
    ];

    $this->actingAs($this->superAdmin)
        ->post(route('superadmin.announcements.store'), $data)
        ->assertRedirect()
        ->assertSessionHas('success', 'Anuncio publicado correctamente.');

    $this->assertDatabaseHas('announcements', $data);
});

it('allows superadmin to update an announcement', function () {
    $announcement = Announcement::create([
        'title' => 'Old Title',
        'message' => 'Old message',
        'type' => 'info',
        'target_role' => 'all',
    ]);

    $data = [
        'title' => 'Updated Title',
        'message' => 'Updated message',
        'type' => 'warning',
        'target_role' => 'admin',
    ];

    $this->actingAs($this->superAdmin)
        ->patch(route('superadmin.announcements.update', $announcement), $data)
        ->assertRedirect()
        ->assertSessionHas('success', 'Anuncio actualizado.');

    $this->assertDatabaseHas('announcements', $data);
});

it('allows superadmin to delete an announcement', function () {
    $announcement = Announcement::create([
        'title' => 'To delete',
        'message' => 'Delete me',
        'type' => 'danger',
        'target_role' => 'owner',
    ]);

    $this->actingAs($this->superAdmin)
        ->delete(route('superadmin.announcements.destroy', $announcement))
        ->assertRedirect()
        ->assertSessionHas('success', 'Anuncio eliminado.');

    $this->assertDatabaseMissing('announcements', ['id' => $announcement->id]);
});

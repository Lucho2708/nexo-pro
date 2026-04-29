<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('ProfileController', function () {
    it('displays the profile edit page', function () {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('profile.edit'));

        $response->assertOk();
    });

    it('updates user profile information', function () {
        $user = User::factory()->create([
            'name' => 'Old Name',
            'email' => 'old@example.com',
            'email_verified_at' => now(),
        ]);

        $response = $this->actingAs($user)->patch(route('profile.update'), [
            'name' => 'New Name',
            'email' => 'new@example.com',
        ]);

        $response->assertSessionHasNoErrors()
            ->assertRedirect();

        $user->refresh();

        expect($user->name)->toBe('New Name')
            ->and($user->email)->toBe('new@example.com')
            ->and($user->email_verified_at)->toBeNull();
    });

    it('updates user profile without changing email verification if email is same', function () {
        $user = User::factory()->create([
            'name' => 'Old Name',
            'email' => 'same@example.com',
            'email_verified_at' => now(),
        ]);

        $response = $this->actingAs($user)->patch(route('profile.update'), [
            'name' => 'New Name',
            'email' => 'same@example.com',
        ]);

        $response->assertSessionHasNoErrors()
            ->assertRedirect();

        $user->refresh();

        expect($user->name)->toBe('New Name')
            ->and($user->email)->toBe('same@example.com')
            ->and($user->email_verified_at)->not->toBeNull();
    });

    it('updates the user password', function () {
        $user = User::factory()->create([
            'password' => Hash::make('password123'),
        ]);

        $response = $this->actingAs($user)->put(route('profile.password'), [
            'current_password' => 'password123',
            'password' => 'newpassword1234',
            'password_confirmation' => 'newpassword1234',
        ]);

        $response->assertSessionHasNoErrors()
            ->assertRedirect();

        expect(Hash::check('newpassword1234', $user->refresh()->password))->toBeTrue();
    });

    it('fails to update password with wrong current password', function () {
        $user = User::factory()->create([
            'password' => Hash::make('password123'),
        ]);

        $response = $this->actingAs($user)->put(route('profile.password'), [
            'current_password' => 'wrongpassword',
            'password' => 'newpassword1234',
            'password_confirmation' => 'newpassword1234',
        ]);

        $response->assertSessionHasErrors('current_password');
    });
});

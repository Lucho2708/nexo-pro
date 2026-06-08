<?php

namespace App\Modules\IAM\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\IAM\Requests\ProfileUpdateRequest;
use App\Modules\IAM\Requests\PasswordUpdateRequest;
use App\Modules\IAM\Models\Profile;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request)
    {
        return Inertia::render('Profile/Edit', [
            'status' => session('status'),
            'user' => $request->user()->load('profile'),
            'roles' => $request->user()->roles()->pluck('name'),
            'permissions' => $request->user()->roles()->with('permissions')->get()->pluck('permissions')->flatten()->pluck('name')->unique()->values()
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request)
    {
        $user = $request->user();
        $validated = $request->validated();

        // 1. Actualizar datos base del usuario
        $user->fill([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        // 2. Actualizar o crear perfil extendido
        $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'phone' => $validated['phone'] ?? null,
                'bio' => $validated['bio'] ?? null,
                'preferences' => $validated['preferences'] ?? $user->profile?->preferences,
            ]
        );

        // 3. Manejo de Avatar (si se envió)
        if ($request->hasFile('avatar')) {
            if ($user->profile?->avatar_path) {
                Storage::disk('public')->delete($user->profile->avatar_path);
            }
            
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->profile()->update(['avatar_path' => $path]);
        }

        return back()->with('status', 'profile-updated');
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(PasswordUpdateRequest $request)
    {
        $validated = $request->validated();

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('status', 'password-updated');
    }
}

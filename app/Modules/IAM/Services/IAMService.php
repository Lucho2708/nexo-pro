<?php

namespace App\Modules\IAM\Services;

use App\Modules\IAM\Interfaces\IAMServiceInterface;
use App\Modules\IAM\Models\User;
use Illuminate\Support\Facades\Auth;

class IAMService implements IAMServiceInterface
{
    public function authenticate(array $credentials): bool
    {
        return Auth::attempt($credentials);
    }

    public function getCurrentUser(): ?User
    {
        /** @var User|null $user */
        $user = Auth::user();
        return $user;
    }

    public function logout(): void
    {
        Auth::logout();
    }
}

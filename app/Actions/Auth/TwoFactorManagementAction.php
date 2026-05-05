<?php

namespace App\Actions\Auth;

use App\Modules\IAM\Models\User;
use Illuminate\Support\Facades\DB;

class TwoFactorManagementAction
{
    /**
     * Reset 2FA for a specific user.
     *
     * @param User $user
     * @return void
     */
    public function reset(User $user): void
    {
        DB::transaction(function () use ($user) {
            $user->forceFill([
                'two_factor_secret' => null,
                'two_factor_recovery_codes' => null,
                'two_factor_confirmed_at' => null,
            ])->save();
        });
    }

    /**
     * Toggle 2FA mandatory status for a specific user (if we had a flag, but here it is global).
     * Since it is global, this might just be the reset action for now.
     */
}

<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;

class UpdateLastLogin
{
    /**
     * Handle the event.
     * SRP: Only updates last_login_at — nothing else.
     */
    public function handle(Login $event): void
    {
        $event->user->updateQuietly(['last_login_at' => now()]);
    }
}

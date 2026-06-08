<?php

namespace App\Http\Controllers;

use App\Modules\Operations\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Marca un anuncio como leído por el usuario actual.
     */
    public function markAsRead(Announcement $announcement)
    {
        $user = Auth::user();

        // Evitar duplicados
        if (!$announcement->usersRead()->where('user_id', $user->id)->exists()) {
            $announcement->usersRead()->attach($user->id, [
                'read_at' => now()
            ]);
        }

        return response()->json(['status' => 'success']);
    }
}

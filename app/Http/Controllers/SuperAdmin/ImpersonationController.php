<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ImpersonationController extends Controller
{
    public function start(User $user)
    {
        // Seguridad preventiva redundante
        abort_unless(auth()->user()->isSuperAdmin(), 403, 'Acción restringida a Super Administradores');

        // Guardar el ID del Super Admin original para poder volver
        session(['impersonator_id' => Auth::id()]);

        Auth::login($user);

        return redirect()->route('dashboard')->with('success', "Ahora estás navegando como {$user->name}");
    }

    public function stop()
    {
        $originalId = session('impersonator_id');

        if ($originalId) {
            Auth::loginUsingId($originalId);
            session()->forget('impersonator_id');
            return redirect()->route('dashboard')->with('success', 'Has regresado a tu cuenta de Super Admin');
        }

        return redirect('/');
    }
}

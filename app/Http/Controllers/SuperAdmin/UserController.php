<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Modules\IAM\Models\User;
use Illuminate\Http\Request;
use App\Modules\IAM\Actions\Auth\TwoFactorManagementAction;
use App\Traits\Auditable;
use Inertia\Inertia;

class UserController extends Controller
{
    use Auditable;

    public function index(Request $request)
    {
        try {
            $users = User::with(['currentCopropiedad'])
                ->when($request->search, function($query, $search) {
                    $query->where('name', 'like', "%{$search}%")
                          ->orWhere('email', 'like', "%{$search}%");
                })
                ->latest()
                ->paginate(10)
                ->withQueryString();
        } catch (\Throwable $e) {
            dd("CONTROLLER EXCEPTION:", $e);
        }

        $stats = [
            'total' => User::count(),
            'active' => User::where('is_active', true)->count(),
            'admins' => User::where('role', 'admin')->count() + User::where('role', 'super_admin')->count(),
            'sec_enabled' => User::whereNotNull('two_factor_secret')->count(),
        ];

        return Inertia::render('SuperAdmin/Users/Index', [
            'users' => $users->through(fn ($user) => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'is_active' => $user->is_active,
                'current_copropiedad' => $user->currentCopropiedad,
                'has_2fa_enabled' => $user->hasEnabledTwoFactorAuthentication(),
                'last_login_at_human' => $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Nunca',
                'avatar' => "https://api.dicebear.com/7.x/avataaars/svg?seed=" . urlencode($user->email),
            ]),
            'filters' => $request->only(['search']),
            'stats' => $stats,
        ]);
    }

    public function toggleStatus(User $user)
    {
        $user->is_active = !$user->is_active;
        $user->save();

        $this->audit('USUARIOS', $user->is_active ? 'HABILITACION_CUENTA' : 'SUSPENSION_CUENTA', [
            'affected_user_id' => $user->id,
            'affected_user_name' => $user->name,
        ]);

        $statusLabel = $user->is_active ? 'activado' : 'desactivado';
        return back()->with('success', "El usuario {$user->name} ha sido {$statusLabel} correctamente.");
    }

    public function resetTwoFactor(User $user, TwoFactorManagementAction $action)
    {
        $action->reset($user);

        $this->audit('SEGURIDAD', 'RESET_2FA_FORCE', [
            'affected_user_id' => $user->id,
            'affected_user_name' => $user->name,
        ]);

        return back()->with('success', "El 2FA de {$user->name} ha sido reseteado. Deberá enrolarse en su próximo inicio de sesión.");
    }
}

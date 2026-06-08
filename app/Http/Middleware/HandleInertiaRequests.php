<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
use App\Modules\Operations\Models\Announcement;
use Illuminate\Support\Facades\Auth;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $user = $request->user();

        return [
            ...parent::share($request),
            'auth' => [
                'user' => $user ? [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                    'is_standalone' => $user->is_standalone,
                    'current_copropiedad' => $user->current_copropiedad_id ? $user->currentCopropiedad()->first() : null,
                    'copropiedad_settings' => $user->current_copropiedad_id ? ($user->currentCopropiedad()->first()->settings ?? []) : [],
                    'available_copropiedades' => $this->getAvailableProperties($user),
                    'is_impersonating' => session()->has('impersonator_id'),
                    'permissions' => $this->getSafePermissions($user),
                    'roles' => $this->getSafeRoles($user),
                ] : null,
            ],
            'notifications' => $this->getSafeNotifications($user),
            'announcements' => $this->getSafeAnnouncements($user),
            'flash' => [
                'success' => fn () => $request->session()->get('success'),
                'error' => fn () => $request->session()->get('error'),
            ],
        ];
    }

    protected function getSafePermissions($user) {
        try {
            return $user->roles()->with('permissions')->get()->pluck('permissions')->flatten()->pluck('name')->unique()->values();
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Error in getSafePermissions: ' . $e->getMessage());
            return [];
        }
    }

    protected function getSafeRoles($user) {
        try {
            return $user->roles()->pluck('name');
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Error in getSafeRoles: ' . $e->getMessage());
            return [];
        }
    }

    protected function getSafeNotifications($user)
    {
        if (! $user) return [];
        try {
            return $user->notifications()->unread()->latest()->take(5)->get();
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Error in getSafeNotifications: ' . $e->getMessage());
            return [];
        }
    }

    protected function getSafeAnnouncements($user)
    {
        try {
            if (!\Illuminate\Support\Facades\Schema::hasTable('operations.announcements')) {
                return [];
            }

            $query = Announcement::active();

            // Filtrado por Copropiedad: Globales + La seleccionada
            $query->where(function($q) use ($user) {
                $q->whereNull('copropiedad_id');
                
                if ($user && $user->current_copropiedad_id) {
                    $q->orWhere('copropiedad_id', $user->current_copropiedad_id);
                }
            });

            // Filtrado por Rol
            if ($user && !$user->isSuperAdmin()) {
                $query->where(function($q) use ($user) {
                    $q->where('target_role', 'all')
                      ->orWhere('target_role', $user->role);
                });
            }

            if ($user) {
                $query->withExists(['usersRead as is_read' => function($q) use ($user) {
                    $q->where('user_id', $user->id);
                }]);
            }

            return $query->latest()->get();
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Error in getSafeAnnouncements: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Resolve all authorized properties for the session tenant switcher.
     */
    protected function getAvailableProperties($user)
    {
        if (! $user) return [];

        try {
            if ($user->isSuperAdmin()) {
                return \App\Modules\Property\Models\Copropiedad::all();
            }

            if ($user->isAdmin()) {
                return $user->managedCopropiedades;
            }

            if ($user->isOwner()) {
                return $user->unidades()->with('copropiedad')->get()->pluck('copropiedad')->unique('id')->values();
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Error in getAvailableProperties: ' . $e->getMessage());
            return [];
        }

        return [];
    }
}

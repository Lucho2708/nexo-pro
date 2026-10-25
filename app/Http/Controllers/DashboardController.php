<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

use App\Modules\Operations\Models\Notification;
use App\Services\Analytics\AnalyticsService;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function __construct(
        private AnalyticsService $analyticsService
    ) {}

    /**
     * Dispatcher central: Redirige a cada usuario autenticado a su URL canónica según su entorno/rol.
     */
    public function index(): mixed
    {
        $user = Auth::user();

        return match (true) {
            $user->isSuperAdmin() => redirect()->route('superadmin.dashboard'),
            $user->isBackoffice() => redirect()->route('backoffice.leads.index'),
            $user->isAdmin()      => redirect()->route('admin.dashboard'),
            $user->isSecurity()    => redirect()->route('porteria.dashboard'),
            default               => redirect()->route('owner.dashboard'),
        };
    }

    /**
     * Dashboard canónico del Administrador de Copropiedad.
     */
    public function adminDashboard(): mixed
    {
        $user = Auth::user();

        $copropiedadId = $user->current_copropiedad_id;
        $copropiedad = $user->currentCopropiedad;

        // Redirección Standalone: Si es solo asamblea, redirigir al módulo de asambleas
        $isStandalone = $user->is_standalone || ($copropiedad && ($copropiedad->settings['is_standalone'] ?? false));
        if ($isStandalone) {
            return redirect()->route('admin.asambleas.index');
        }

        if (!$copropiedadId) {
            // Si el admin no tiene conjunto asignado o seleccionado, vista de configuración
            return Inertia::render('Admin/Copropiedades/SetupRequired');
        }

        return Inertia::render('Dashboard', [
            'stats' => $this->analyticsService->getAdminDashboardStats($copropiedadId),
            'chartData' => $this->analyticsService->getChartData($copropiedadId),
            'overduePayments' => $this->analyticsService->getOverduePayments($copropiedadId),
            'notifications' => $user->notifications()->unread()->latest()->take(5)->get(),
        ]);
    }

    /**
     * Marcar notificación administrativa como leída.
     */
    public function markNotificationAsRead(Notification $notification)
    {
        \Log::info("Attempting to mark notification {$notification->id} as read for user " . Auth::id());
        if ($notification->user_id === Auth::id()) {
            $notification->update(['read_at' => now()]);
            \Log::info("Notification {$notification->id} marked as read.");
        } else {
            \Log::info("User " . Auth::id() . " does not own notification {$notification->id} (owner: {$notification->user_id})");
        }

        return back();
    }
}

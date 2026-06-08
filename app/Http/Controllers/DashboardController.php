<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

use App\Modules\Operations\Models\Notification;
use App\Http\Controllers\SuperAdmin\CopropiedadController;
use App\Services\Analytics\AnalyticsService;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function __construct(
        private AnalyticsService $analyticsService
    ) {}

    /**
     * Display the administrative dashboard (Dispatcher).
     */
    public function index(): mixed
    {
        $user = Auth::user();

        // ── DASHBOARD DE SUPER ADMIN ─────────────────────────────────
        if ($user->isSuperAdmin()) {
            return app(CopropiedadController::class)->index();
        }

        // ── DASHBOARD DE PROPIETARIO ─────────────────────────────────
        if ($user->isOwner()) {
            return redirect()->route('owner.dashboard');
        }

        // ── DASHBOARD DE ADMINISTRADOR (Actual) ──────────────────────
        $copropiedadId = $user->current_copropiedad_id;
        $copropiedad = $user->currentCopropiedad;

        // Redirección Standalone: Si es solo asamblea, no ve el dashboard general
        $isStandalone = $user->is_standalone || ($copropiedad && ($copropiedad->settings['is_standalone'] ?? false));
        if ($isStandalone) {
            return redirect()->route('admin.asambleas.index');
        }

        if (!$copropiedadId) {
            // Si el admin no tiene conjunto activo, redirigir a creación o selección
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
     * Mark a notification as read.
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

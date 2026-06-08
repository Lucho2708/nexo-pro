<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReservaRequest;
use App\Http\Requests\UpdateReservaStatusRequest;
use App\Modules\Operations\Models\Reserva;
use App\Services\Operations\ReservationService;
use App\Repositories\Interfaces\ReservationRepositoryInterface;
use App\Repositories\Interfaces\ZonaComunRepositoryInterface;
use App\Traits\Auditable;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ReservaController extends Controller
{
    use Auditable;

    public function __construct(
        private ReservationService $reservationService,
        private ReservationRepositoryInterface $repository,
        private ZonaComunRepositoryInterface $zonaRepository
    ) {}

    /**
     * Display a listing of zones and user bookings.
     */
    public function index()
    {
        $user = auth()->user();
        
        $zonas = $this->zonaRepository->getActiveByCopropiedad($user->current_copropiedad_id);

        $reservas = $this->repository->getByUser($user->id);

        return Inertia::render('Owner/Reservas/Index', [
            'zonas' => $zonas,
            'reservas' => $reservas,
            'unidades' => $user->unidades
        ]);
    }

    /**
     * Store a new reservation.
     */
    public function store(StoreReservaRequest $request)
    {
        $user = auth()->user();
        $validated = $request->validated();

        try {
            $this->reservationService->createReservation($validated, $user->id);

            $this->audit('RESERVAS', 'SOLICITUD_RESERVA', [
                'zona_comun_id' => $validated['zona_id'],
                'fecha_reserva' => $validated['fecha'] ?? null,
            ]);

            return redirect()->back()->with('success', 'Reserva solicitada exitosamente.');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Cancel a reservation.
     */
    public function cancel(Reserva $reserva)
    {
        $user = auth()->user();
        
        if ($reserva->user_id !== $user->id && !$user->isAdmin()) {
            abort(403);
        }

        $this->reservationService->cancelReservation($reserva->id);

        $this->audit('RESERVAS', 'CANCELACION_RESERVA', [
            'reserva_id' => $reserva->id,
            'zona_nombre' => $reserva->zonaComun?->nombre,
        ]);

        return back()->with('success', 'Reserva cancelada.');
    }

    /**
     * Admin view to manage reservations.
     */
    public function adminIndex()
    {
        $user = auth()->user();
        $reservas = $this->reservationService->getAllForAdmin($user->current_copropiedad_id);

        return Inertia::render('Admin/Reservas/Manager', [
            'reservas' => $reservas
        ]);
    }

    /**
     * Admin update status.
     */
    public function updateStatus(UpdateReservaStatusRequest $request, string $id)
    {
        $validated = $request->validated();

        $this->reservationService->updateStatus($id, $validated['estado'], $validated['notas']);

        $this->audit('RESERVAS', 'ADMIN_UPDATE_STATUS', [
            'reserva_id' => $id,
            'nuevo_estado' => $validated['estado'],
        ]);

        return back()->with('success', 'Estado de reserva actualizado.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Modules\Operations\Models\Pqrs;
use App\Modules\Property\Models\Unidad;
use App\Http\Requests\StorePqrsRequest;
use App\Http\Requests\RespondPqrsRequest;
use App\Mail\PqrsCreatedNotification;
use App\Services\Pqrs\PqrsService;
use App\Repositories\Interfaces\PqrsRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Traits\Auditable;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class PqrsController extends Controller
{
    use AuthorizesRequests, Auditable;

    public function __construct(
        private PqrsService $pqrsService,
        private PqrsRepositoryInterface $repository
    ) {}

    /**
     * Download PQRS as PDF.
     */
    public function downloadPdf(Pqrs $pqrs)
    {
        $this->authorize('view', $pqrs);

        $pdf = Pdf::loadView('reports.pqrs', compact('pqrs'));
        return $pdf->download("PQRS-{$pqrs->id}.pdf");
    }

    /**
     * Display a listing of PQRS.
     */
    public function index()
    {
        $user = auth()->user();

        if ($user->isOwner()) {
            $pqrs = $this->repository->getByUser($user->id);
            return Inertia::render('Owner/Pqrs/Index', [
                'pqrs' => $pqrs,
                'unidades' => $user->unidades
            ]);
        }

        if ($user->isAdmin() || $user->isSuperAdmin()) {
            $pqrs = $this->repository->getByCopropiedad($user->current_copropiedad_id);
            return Inertia::render('Admin/Pqrs/Index', [
                'pqrs' => $pqrs,
                'metrics' => $this->pqrsService->getDashboardMetrics($user->current_copropiedad_id)
            ]);
        }

        return redirect()->route('dashboard');
    }

    /**
     * Store a new PQRS.
     */
    public function store(StorePqrsRequest $request)
    {
        $validated = $request->validated();

        $adjuntosPaths = [];
        if ($request->hasFile('adjuntos')) {
            foreach ($request->file('adjuntos') as $file) {
                $adjuntosPaths[] = $file->store('pqrs/attachments', 'public');
            }
        }

        $validated['adjuntos'] = $adjuntosPaths;
        $pqrs = $this->pqrsService->createPqrs($validated, auth()->id());

        $this->audit('PQRS', 'RADICACION_NUEVA', [
            'pqrs_id' => $pqrs->id,
            'categoria' => $pqrs->categoria,
            'unidad' => $pqrs->unidad?->nombre_unidad,
        ]);

        // Notify Admin
        $adminEmail = $pqrs->unidad->copropiedad->users()->where('role', 'admin')->first()?->email;
        if ($adminEmail) {
            Mail::to($adminEmail)->send(new PqrsCreatedNotification($pqrs));
        }

        return back()->with('success', 'PQRS radicada exitosamente.');
    }

    /**
     * Update the PQRS.
     */
    public function update(Request $request, Pqrs $pqrs)
    {
        $this->authorize('update', $pqrs);
        $user = auth()->user();

        // Admin o SuperAdmin pueden responder
        if ($user->isAdmin() || $user->isSuperAdmin()) {
            
            $validateRequest = resolve(RespondPqrsRequest::class);
            $validated = $validateRequest->validated();

            $this->pqrsService->respondPqrs($pqrs->id, $validated['respuesta'], $validated['cerrar'] ?? false);

            $this->audit('PQRS', $validated['cerrar'] ? 'RESPUESTA_Y_CIERRE' : 'RESPUESTA_ADMIN', [
                'pqrs_id' => $pqrs->id,
            ]);

            return back()->with('success', 'Respuesta enviada exitosamente.');
        }

        if ($user->isOwner() && $pqrs->user_id === $user->id) {
            $this->pqrsService->reopenPqrs($pqrs->id);

            $this->audit('PQRS', 'REAPERTURA_PROPIETARIO', [
                'pqrs_id' => $pqrs->id,
            ]);

            return back()->with('success', 'PQRS reabierta.');
        }

        abort(403);
    }
}

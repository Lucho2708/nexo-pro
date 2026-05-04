<?php

namespace App\Http\Controllers;

use App\Models\Asamblea;
use App\Models\Unidad;
use App\Models\Pregunta;
use App\Models\Opcion;
use App\Services\AsambleaService;
use App\Services\AsambleaReportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Inertia\Inertia;
use Inertia\Response;

/**
 * AsambleaController — S: SRP aplicado (solo gestiona la sala y votación básica).
 * Las intervenciones han sido movidas a IntervencionController.
 */
class AsambleaController extends Controller
{
    public function __construct(
        protected AsambleaService $asambleaService,
        protected AsambleaReportService $reportService
    ) {}

    /**
     * Display the virtual assembly room.
     */
    public function show(Request $request, Asamblea $asamblea): Response
    {
        $user = $request->user();
        $isAdmin = $user->isAdmin() || $user->isSuperAdmin();
        $unidades = $this->asambleaService->getAuthenticatedUnits($user, $asamblea);

        if (!$isAdmin && $unidades->isEmpty()) {
            abort(403, 'No tienes una unidad asociada a esta copropiedad.');
        }

        // Para simplificar, si hay varias unidades, usamos la primera para la validación de canJoin básica
        // o podríamos iterar. Por ahora, permitimos entrar si al menos una unidad es válida.
        $masterUnidad = $unidades->first();

        if ($masterUnidad && !$this->asambleaService->canJoin($user, $masterUnidad)) {
            return Inertia::render('Asamblea/AccessDenied', [
                'asamblea' => $asamblea,
                'unidad' => $masterUnidad,
                'message' => 'Ya existe un dispositivo conectado para esta unidad. ¿Deseas cerrar la otra sesión e ingresar desde aquí?',
                'can_reset' => true
            ]);
        }

        if ($masterUnidad) {
            foreach ($unidades as $u) {
                $this->asambleaService->registerConnection($user, $u, $asamblea);
            }
        }

        return Inertia::render('Asamblea/Show', [
            'asamblea' => $asamblea->load('copropiedad'),
            'preguntas' => $this->asambleaService->getPreguntasWithOpciones($asamblea),
            'token' => $this->asambleaService->generateToken($user, $masterUnidad ?? new Unidad(['torre' => 'MOD', 'nombre' => 'ADMIN'])),
            'unidades' => $unidades, 
            'is_admin' => $isAdmin,
            'user' => $user, 
            'livekit_url' => config('services.livekit.url', 'ws://localhost:7880'),
        ]);
    }

    /**
     * Cast a vote for a question.
     */
    public function votar(Request $request, Pregunta $pregunta)
    {
        $user = $request->user();
        $unidades = $this->asambleaService->getAuthenticatedUnits($user, $pregunta->asamblea);

        if ($unidades->isEmpty()) {
            abort(403, 'No tienes unidades asociadas.');
        }

        try {
            $opcion = Opcion::findOrFail($request->opcion_id);
            $this->asambleaService->castVote($user, $unidades, $pregunta, $opcion, $pregunta->asamblea);

            return response()->json(['message' => 'Voto registrado correctamente para todas tus unidades.']);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 403);
        }
    }

    /**
     * Get live results for a specific question.
     */
    public function results(Pregunta $pregunta)
    {
        $asamblea = $pregunta->asamblea;
        $shortId = substr($asamblea->id, 0, 8);
        $votesTable = "asvotos_{$shortId}";

        if (Schema::hasTable($votesTable)) {
            $results = DB::table($votesTable)
                ->where('pregunta_id', $pregunta->id)
                ->select('opcion_id', DB::raw('SUM(peso) as total_peso'), DB::raw('COUNT(*) as total_votos'))
                ->groupBy('opcion_id')
                ->get();

            return response()->json([
                'results' => $results,
                'total_participating_units' => DB::table($votesTable)->where('pregunta_id', $pregunta->id)->count(),
                'total_participating_weight' => DB::table($votesTable)->where('pregunta_id', $pregunta->id)->sum('peso'),
            ]);
        }

        $results = $pregunta->votos()
            ->select('opcion_id', \DB::raw('SUM(peso) as total_peso'), \DB::raw('COUNT(*) as total_votos'))
            ->groupBy('opcion_id')
            ->get();

        return response()->json([
            'results' => $results,
            'total_participating_units' => $pregunta->votos()->count(),
            'total_participating_weight' => $pregunta->votos()->sum('peso'),
        ]);
    }

    /**
     * Generate and download the audit report.
     */
    public function report(Request $request, Asamblea $asamblea)
    {
        return $this->reportService->generatePdf($asamblea)->download("auditoria_asamblea_{$asamblea->id}.pdf");
    }

    /**
     * Force reset a connection for a unit.
     */
    public function resetConnection(Asamblea $asamblea, AsambleaService $asambleaService)
    {
        $user = auth()->user();
        $unidades = $asambleaService->getAuthenticatedUnits($user, $asamblea);

        foreach ($unidades as $u) {
            $asambleaService->clearConnection($u);
        }

        return back()->with('success', 'Conexión reseteada correctamente.');
    }
}

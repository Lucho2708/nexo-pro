<?php

namespace App\Http\Controllers;

use App\Models\Asamblea;
use App\Models\Unidad;
use App\Models\Pregunta;
use App\Models\Opcion;
use App\Services\AsambleaService;
use App\Services\AsambleaReportService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

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
        $unidad = $this->asambleaService->getAuthenticatedUnit($user, $asamblea);

        if (!$isAdmin && !$unidad) {
            abort(403, 'No tienes una unidad asociada a esta copropiedad.');
        }

        if ($unidad && !$this->asambleaService->canJoin($user, $unidad)) {
            return Inertia::render('Asamblea/AccessDenied', [
                'asamblea' => $asamblea,
                'unidad' => $unidad,
                'message' => 'Ya existe un dispositivo conectado para esta unidad. ¿Deseas cerrar la otra sesión e ingresar desde aquí?',
                'can_reset' => true
            ]);
        }

        if ($unidad) {
            $this->asambleaService->registerConnection($user, $unidad, $asamblea);
        }

        return Inertia::render('Asamblea/Show', [
            'asamblea' => $asamblea->load('copropiedad'),
            'token' => $this->asambleaService->generateToken($user, $unidad ?? new Unidad(['torre' => 'MOD', 'nombre' => 'ADMIN'])),
            'unidad' => $unidad,
            'is_admin' => $isAdmin,
            'livekit_url' => config('services.livekit.url', 'ws://localhost:7880'),
        ]);
    }

    /**
     * Cast a vote for a question.
     */
    public function votar(Request $request, Pregunta $pregunta)
    {
        $user = $request->user();
        $unidad = $this->asambleaService->getAuthenticatedUnit($user, $pregunta->asamblea);

        if (!$unidad) {
            abort(403, 'No tienes una unidad asociada.');
        }

        try {
            $opcion = Opcion::findOrFail($request->opcion_id);
            $this->asambleaService->castVote($user, $unidad, $pregunta, $opcion, $pregunta->asamblea);

            return response()->json(['message' => 'Voto registrado correctamente.']);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 403);
        }
    }

    /**
     * Generate and download the audit report.
     */
    public function report(Request $request, Asamblea $asamblea)
    {
        if ($request->user()->role !== 'admin' && $request->user()->role !== 'super_admin') {
            abort(403);
        }

        return $this->reportService->generatePdf($asamblea)->download("auditoria_asamblea_{$asamblea->id}.pdf");
    }

    public function toggleHand(Request $request, Asamblea $asamblea, AsambleaService $asambleaService)
    {
        $user = $request->user();
        $unidad = $asambleaService->getAuthenticatedUnit($user, $asamblea);
        $isRaised = $request->is_raised;

        broadcast(new \App\Events\HandRaised($asamblea->id, [
            'user_id' => $user->id,
            'name' => $user->name,
            'unidad' => $unidad ? "{$unidad->torre}-{$unidad->nombre}" : 'Admin',
            'is_raised' => $isRaised
        ]))->toOthers();

        return response()->json(['status' => 'ok']);
    }

    public function requestIntervencion(Request $request, Asamblea $asamblea)
    {
        try {
            if ($asamblea->status !== 'in_progress') {
                return response()->json(['message' => 'La asamblea debe estar en curso para solicitar la palabra.'], 403);
            }

            $intervencion = \App\Models\Intervencion::create([
                'asamblea_id' => $asamblea->id,
                'user_id' => $request->user()->id,
                'status' => 'pending',
                'requested_at' => now(),
            ]);

            $intervencion->load('user.unidades'); // Cargar unidades para el broadcast

            broadcast(new \App\Events\IntervencionUpdated($asamblea->id, $intervencion->toArray()))->toOthers();

            return response()->json($intervencion, 201);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Error al solicitar intervención: " . $e->getMessage(), [
                'user_id' => $request->user()->id,
                'asamblea_id' => $asamblea->id,
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['message' => 'Error interno al procesar la solicitud.'], 500);
        }
    }

    public function cancelIntervencion(Request $request, \App\Models\Intervencion $intervencion)
    {
        if ($intervencion->user_id !== $request->user()->id || $intervencion->status !== 'pending') {
            return response()->json(['message' => 'No autorizado o la petición no está pendiente.'], 403);
        }

        $intervencion->update(['status' => 'cancelled']);
        broadcast(new \App\Events\IntervencionUpdated($intervencion->asamblea_id, $intervencion->toArray()))->toOthers();

        return response()->json($intervencion);
    }

    public function grantIntervencion(Request $request, \App\Models\Intervencion $intervencion)
    {
        $anyActive = \App\Models\Intervencion::where('asamblea_id', $intervencion->asamblea_id)
            ->where('status', 'active')
            ->exists();

        if ($anyActive) {
            return response()->json(['message' => 'Ya hay una intervención activa. Debe finalizarla primero.'], 422);
        }

        $intervencion->update([
            'status' => 'active',
            'started_at' => now(),
            'duration_seconds' => 180, // 3 min por defecto al empezar
        ]);

        broadcast(new \App\Events\IntervencionUpdated($intervencion->asamblea_id, $intervencion->toArray()))->toOthers();

        return response()->json($intervencion);
    }

    public function closeIntervencion(Request $request, \App\Models\Intervencion $intervencion, AsambleaService $asambleaService)
    {
        $finishedAt = now();
        $startedAt = $intervencion->started_at ?? $finishedAt;
        $duration = (int) abs($finishedAt->diffInSeconds($startedAt));

        $status = $request->force ? 'forced_close' : 'completed';
        $notes = $request->notes ?? $intervencion->notes;

        $intervencion->update([
            'status' => $status,
            'finished_at' => $finishedAt,
            'duration_seconds' => $duration,
            'notes' => $notes,
        ]);

        broadcast(new \App\Events\IntervencionUpdated($intervencion->asamblea_id, $intervencion->toArray()))->toOthers();

        // Log legal
        $user = $intervencion->user;
        $unidad = $asambleaService->getAuthenticatedUnit($user, $intervencion->asamblea);
        
        $asambleaService->logEvent($intervencion->asamblea, $user, $unidad ?? new \App\Models\Unidad(), 'intervention_completed', [
            'status' => $status,
            'duration' => $duration,
            'notes' => $notes,
            'started_at' => $startedAt->toIso8601String(),
            'finished_at' => $finishedAt->toIso8601String(),
        ]);

        return response()->json($intervencion);
    }

    public function extendIntervencion(Request $request, \App\Models\Intervencion $intervencion, AsambleaService $asambleaService)
    {
        $seconds = $request->seconds ?? 60;
        $intervencion->increment('duration_seconds', $seconds);

        broadcast(new \App\Events\IntervencionUpdated($intervencion->asamblea_id, $intervencion->toArray()))->toOthers();

        // Log legal
        $asambleaService->logEvent($intervencion->asamblea, $intervencion->user, $asambleaService->getAuthenticatedUnit($intervencion->user, $intervencion->asamblea) ?? new \App\Models\Unidad(), 'intervention_extended', [
            'added_seconds' => $seconds,
            'new_total_limit' => $intervencion->duration_seconds
        ]);

        return response()->json($intervencion);
    }

    public function resetConnection(Asamblea $asamblea, AsambleaService $asambleaService)
    {
        $user = auth()->user();
        $unidad = $asambleaService->getAuthenticatedUnit($user, $asamblea);

        if ($unidad) {
            $asambleaService->clearConnection($unidad);
        }

        return back()->with('success', 'Conexión reseteada correctamente.');
    }
}

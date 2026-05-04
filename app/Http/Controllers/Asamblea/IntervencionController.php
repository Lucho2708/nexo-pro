<?php

namespace App\Http\Controllers\Asamblea;

use App\Http\Controllers\Controller;
use App\Models\Asamblea;
use App\Models\Intervencion;
use App\Services\IntervencionService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class IntervencionController extends Controller
{
    public function __construct(
        protected IntervencionService $intervencionService
    ) {}

    /**
     * Request the word.
     */
    public function request(Request $request, Asamblea $asamblea): JsonResponse
    {
        if ($asamblea->status !== 'in_progress') {
            return response()->json(['message' => 'La asamblea debe estar en curso para solicitar la palabra.'], 403);
        }

        $intervencion = $this->intervencionService->request($asamblea, $request->user());

        return response()->json($intervencion, 201);
    }

    /**
     * Cancel a pending request.
     */
    public function cancel(Request $request, Intervencion $intervencion): JsonResponse
    {
        if ($intervencion->user_id !== $request->user()->id || $intervencion->status !== 'pending') {
            return response()->json(['message' => 'No autorizado o la petición no está pendiente.'], 403);
        }

        $intervencion = $this->intervencionService->cancel($intervencion);

        return response()->json($intervencion);
    }

    /**
     * Grant the word (Admin only - protected by middleware).
     */
    public function grant(Request $request, Intervencion $intervencion): JsonResponse
    {
        try {
            $intervencion = $this->intervencionService->grant($intervencion);
            return response()->json($intervencion);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    /**
     * Close an active intervention.
     */
    public function close(Request $request, Intervencion $intervencion): JsonResponse
    {
        $user = $request->user();
        $isAdmin = $user->isAdmin() || $user->isSuperAdmin();

        // Solo el admin o el dueño de la intervención pueden cerrarla.
        if (!$isAdmin && $intervencion->user_id !== $user->id) {
            return response()->json(['message' => 'No autorizado.'], 403);
        }

        // Solo el admin puede forzar el cierre.
        $force = $isAdmin ? (bool) $request->force : false;

        $intervencion = $this->intervencionService->close(
            $intervencion, 
            $force, 
            $request->notes
        );

        return response()->json($intervencion);
    }

    /**
     * Extend intervention time (Admin only).
     */
    public function extend(Request $request, Intervencion $intervencion): JsonResponse
    {
        $intervencion = $this->intervencionService->extend(
            $intervencion, 
            $request->seconds ?? 60
        );

        return response()->json($intervencion);
    }
}

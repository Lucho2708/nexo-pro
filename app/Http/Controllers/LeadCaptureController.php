<?php

namespace App\Http\Controllers;

use App\Actions\CaptureLeadAction;
use App\DTOs\LeadCaptureDTO;
use App\Http\Requests\StoreLeadRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use NexoPro\Operations\Contracts\LeadScoringServiceInterface;

/**
 * [LÓGICA DE NEGOCIO / UX]
 * Punto de entrada para la captación de leads y simulación de ROI desde la Landing Page corporativa.
 * Garantiza respuesta inmediata (< 300ms) para no perder la atención del prospecto y feedback claro.
 *
 * [ASPECTO TÉCNICO / UI]
 * Controlador RESTful delgado respetando SRP. Delega la validación a StoreLeadRequest
 * y la persistencia a CaptureLeadAction (DIP). Protegido con Rate Limiting en rutas web.
 */
class LeadCaptureController extends Controller
{
    /**
     * Endpoint para simulación reactiva de ROI sin persistencia obligatoria.
     */
    public function simulate(Request $request, LeadScoringServiceInterface $scoringService): JsonResponse
    {
        $unidades = max(10, (int) $request->query('unidades', 120));
        $cuota = max(50000.0, (float) $request->query('cuota_mensual', 350000.0));
        $rol = (string) $request->query('rol', 'administrador');

        $calculation = $scoringService->calculateAndScore($unidades, $cuota, $rol);

        return response()->json([
            'success' => true,
            'data' => $calculation->toArray(),
        ]);
    }

    /**
     * Captura y persiste el prospecto evaluado por la calculadora o formulario de contacto.
     */
    public function store(StoreLeadRequest $request, CaptureLeadAction $action): JsonResponse|RedirectResponse
    {
        $dto = LeadCaptureDTO::fromRequest($request);
        $lead = $action->execute($dto);

        // Si fue interceptado por Honeypot como bot, retornamos éxito fingido para neutralizar el scraper
        if ($dto->websiteHp) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Solicitud procesada correctamente.',
                ], 200);
            }
            return back()->with('success', 'Solicitud recibida correctamente.');
        }

        $mensajeExito = '¡Transmisión Exitosa! Dispatcher activado. Un consultor técnico de NEXO PRO iniciará el protocolo en menos de 24 horas.';

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $mensajeExito,
                'lead_id' => $lead?->id,
                'segmento' => $lead?->segmento,
                'prioridad' => $lead?->metadata['prioridad_contacto'] ?? 'estandar',
            ], 201);
        }

        return back()->with('success', $mensajeExito);
    }
}

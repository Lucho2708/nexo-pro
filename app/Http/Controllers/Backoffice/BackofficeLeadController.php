<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use App\Modules\Operations\Models\Lead;
use NexoPro\CRM\Contracts\LeadCrmServiceInterface;
use NexoPro\CRM\DTOs\TransicionarLeadDTO;
use Illuminate\Support\Facades\Auth;

/**
 * [LÓGICA DE NEGOCIO / UX]
 * Controlador del módulo Backoffice CRM comercial de NEXO PRO.
 * Permite a los ejecutivos de cuenta gestionar el embudo de ventas, prospectos y conversión de copropiedades.
 *
 * [ASPECTO TÉCNICO / UI]
 * Controlador bajo principios SOLID (DIP/SRP) que inyecta LeadCrmServiceInterface.
 */
class BackofficeLeadController extends Controller
{
    public function __construct(
        private LeadCrmServiceInterface $crmService
    ) {}

    public function index(Request $request): Response
    {
        $leads = Lead::latest('created_at')->get();

        $resumen = $this->crmService->obtenerResumenPipeline($leads);
        $metricas = $this->crmService->calcularMetricasConversion($leads);

        return Inertia::render('Backoffice/Leads/Index', [
            'leads' => $leads,
            'resumen' => $resumen,
            'metricas' => $metricas,
        ]);
    }

    public function updateEstado(Request $request, Lead $lead)
    {
        $validated = $request->validate([
            'estado' => 'required|string|in:nuevo,contactado,demo_agendada,cotizado,ganado,perdido',
            'notas' => 'nullable|string|max:1000',
        ]);

        $dto = TransicionarLeadDTO::fromRequest($validated, $lead->id, Auth::id());
        $this->crmService->transicionarEstado($lead, $dto);

        return redirect()->back()->with('success', 'Etapa de prospecto actualizada.');
    }
}

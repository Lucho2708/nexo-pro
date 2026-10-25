<?php

namespace NexoPro\CRM\Services;

use App\Modules\Operations\Models\Lead;
use NexoPro\CRM\Contracts\LeadCrmServiceInterface;
use NexoPro\CRM\DTOs\TransicionarLeadDTO;
use Illuminate\Support\Collection;

/**
 * [LÓGICA DE NEGOCIO / UX]
 * Servicio de embudo comercial y CRM para el equipo de Backoffice.
 * Controla la progresión de los prospectos de copropiedades desde la captación en la landing page
 * hasta el cierre comercial y conversión en conjunto activo.
 *
 * [ASPECTO TÉCNICO / UI]
 * Cumple con principios SOLID:
 * - SRP: Encapsula exclusivamente las reglas comerciales del pipeline de ventas.
 * - OCP: Permite incorporar webhooks hacia HubSpot/Salesforce mediante eventos sin modificar el servicio.
 * - LSP: Respeta el contrato LeadCrmServiceInterface.
 * - ISP: Interfaz enfocada únicamente en el CRM.
 * - DIP: Desacoplado de la infraestructura HTTP.
 */
class LeadCrmService implements LeadCrmServiceInterface
{
    public function transicionarEstado(Lead $lead, TransicionarLeadDTO $dto): Lead
    {
        $metadata = $lead->metadata ?? [];
        if ($dto->notasComerciales) {
            $metadata['ultima_nota'] = $dto->notasComerciales;
            $historial = $metadata['historial_notas'] ?? [];
            $historial[] = [
                'nota' => $dto->notasComerciales,
                'estado' => $dto->nuevoEstado,
                'fecha' => now()->toIso8601String(),
                'asesor_id' => $dto->asesorId,
            ];
            $metadata['historial_notas'] = $historial;
        }

        $lead->estado = $dto->nuevoEstado;
        $lead->metadata = $metadata;

        // Si el modelo ya está persistido, guardamos; de lo contrario mantenemos atributos en memoria
        if ($lead->exists) {
            $lead->save();
        }

        return $lead;
    }

    public function obtenerResumenPipeline(Collection $leads): array
    {
        $etapas = TransicionarLeadDTO::ESTADOS_VALIDOS;
        $resumen = [];

        foreach ($etapas as $etapa) {
            $leadsEnEtapa = $leads->where('estado', $etapa);
            $resumen[$etapa] = [
                'total_leads' => $leadsEnEtapa->count(),
                'total_unidades' => (int) $leadsEnEtapa->sum('unidades_totales'),
                'ahorro_proyectado_total' => (float) $leadsEnEtapa->sum('ahorro_estimado_anual'),
            ];
        }

        return $resumen;
    }

    public function calcularMetricasConversion(Collection $leads): array
    {
        $total = $leads->count();
        if ($total === 0) {
            return [
                'tasa_conversion' => 0.0,
                'ganados' => 0,
                'perdidos' => 0,
                'activos' => 0,
            ];
        }

        $ganados = $leads->where('estado', 'ganado')->count();
        $perdidos = $leads->where('estado', 'perdido')->count();
        $activos = $total - ($ganados + $perdidos);

        return [
            'tasa_conversion' => round(($ganados / $total) * 100, 2),
            'ganados' => $ganados,
            'perdidos' => $perdidos,
            'activos' => $activos,
        ];
    }
}

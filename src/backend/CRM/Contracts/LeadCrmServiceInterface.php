<?php

namespace NexoPro\CRM\Contracts;

use App\Modules\Operations\Models\Lead;
use NexoPro\CRM\DTOs\TransicionarLeadDTO;
use Illuminate\Support\Collection;

/**
 * [LÓGICA DE NEGOCIO / UX]
 * Define las operaciones de embudo comercial y CRM para el equipo de Backoffice de NEXO PRO.
 *
 * [ASPECTO TÉCNICO / UI]
 * Contrato inmutable bajo Clean Architecture (DIP).
 */
interface LeadCrmServiceInterface
{
    public function transicionarEstado(Lead $lead, TransicionarLeadDTO $dto): Lead;

    public function obtenerResumenPipeline(Collection $leads): array;

    public function calcularMetricasConversion(Collection $leads): array;
}

<?php

namespace Tests\Unit\CRM;

use Tests\TestCase;
use App\Modules\Operations\Models\Lead;
use NexoPro\CRM\Contracts\LeadCrmServiceInterface;
use NexoPro\CRM\Services\LeadCrmService;
use NexoPro\CRM\DTOs\TransicionarLeadDTO;
use Illuminate\Support\Str;

class LeadCrmPipelineTest extends TestCase
{
    private LeadCrmServiceInterface $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new LeadCrmService();
    }

    /**
     * [LÓGICA DE NEGOCIO / UX]
     * Permite avanzar un prospecto a través de las etapas del embudo comercial.
     */
    public function test_transiciona_lead_a_etapa_valida(): void
    {
        $lead = new Lead([
            'nombre' => 'Dra. Patricia Ortiz',
            'email' => 'patricia@edificiomirasol.com',
            'telefono' => '+573105556677',
            'copropiedad_nombre' => 'Edificio Mirasol Real',
            'unidades_totales' => 120,
            'estado' => 'nuevo',
            'score' => 75,
        ]);
        $lead->id = (string) Str::uuid();

        $dto = new TransicionarLeadDTO(
            leadId: $lead->id,
            nuevoEstado: 'demo_agendada',
            notasComerciales: 'Demostración agendada para el viernes 10:00 AM'
        );

        $leadActualizado = $this->service->transicionarEstado($lead, $dto);

        $this->assertEquals('demo_agendada', $leadActualizado->estado);
        $this->assertEquals('Demostración agendada para el viernes 10:00 AM', $leadActualizado->metadata['ultima_nota'] ?? null);
    }

    /**
     * [EDGE CASE / SEGURIDAD]
     * Rechaza transiciones hacia estados inválidos en el pipeline.
     */
    public function test_rechaza_estado_invalido_en_pipeline(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $lead = new Lead(['estado' => 'nuevo']);
        $dto = new TransicionarLeadDTO(
            leadId: '123',
            nuevoEstado: 'estado_inexistente',
            notasComerciales: 'Test'
        );

        $this->service->transicionarEstado($lead, $dto);
    }

    /**
     * [LÓGICA DE NEGOCIO / UX]
     * Agrupa y totaliza leads por columna del tablero Kanban CRM.
     */
    public function test_agrupa_metricas_por_columna_kanban(): void
    {
        $leads = collect([
            new Lead(['estado' => 'nuevo', 'unidades_totales' => 80]),
            new Lead(['estado' => 'nuevo', 'unidades_totales' => 120]),
            new Lead(['estado' => 'contactado', 'unidades_totales' => 200]),
            new Lead(['estado' => 'demo_agendada', 'unidades_totales' => 350]),
            new Lead(['estado' => 'ganado', 'unidades_totales' => 150]),
        ]);

        $columnas = $this->service->obtenerResumenPipeline($leads);

        $this->assertEquals(2, $columnas['nuevo']['total_leads']);
        $this->assertEquals(200, $columnas['nuevo']['total_unidades']);
        $this->assertEquals(1, $columnas['contactado']['total_leads']);
        $this->assertEquals(1, $columnas['demo_agendada']['total_leads']);
        $this->assertEquals(1, $columnas['ganado']['total_leads']);
    }
}

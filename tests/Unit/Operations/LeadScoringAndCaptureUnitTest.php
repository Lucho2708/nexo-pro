<?php

namespace Tests\Unit\Operations;

use App\Actions\CaptureLeadAction;
use App\DTOs\LeadCaptureDTO;
use App\Http\Requests\StoreLeadRequest;
use App\Modules\Operations\Models\Lead;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use NexoPro\Operations\Contracts\LeadScoringServiceInterface;
use NexoPro\Operations\DTOs\LeadCalculationDTO;
use NexoPro\Operations\Services\LeadScoringService;
use Tests\TestCase;

/**
 * [LÓGICA DE NEGOCIO / UX]
 * Pruebas unitarias de calificación algorítmica de leads y proyección de ROI para copropiedades.
 * Valida segmentación comercial, scoring por roles decisores y tiempos de atención SLA.
 *
 * [ASPECTO TÉCNICO / UI]
 * Tests unitarios deterministas con ciclo TDD, validación de DTOs inmutables,
 * mitigación de bots mediante Honeypot y cobertura completa de casos borde.
 */
class LeadScoringAndCaptureUnitTest extends TestCase
{
    private LeadScoringService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new LeadScoringService();
    }

    public function test_determine_segment_classifies_correctly_based_on_units(): void
    {
        $this->assertSame('enterprise', $this->service->determineSegment(300));
        $this->assertSame('enterprise', $this->service->determineSegment(750));
        $this->assertSame('standard', $this->service->determineSegment(299));
        $this->assertSame('standard', $this->service->determineSegment(100));
        $this->assertSame('boutique', $this->service->determineSegment(99));
        $this->assertSame('boutique', $this->service->determineSegment(15));
    }

    public function test_compute_score_rewards_enterprise_size_and_decision_maker_roles(): void
    {
        // Administrador con 400 unidades
        $scoreAdmin = $this->service->computeScore(400, 'administrador');
        $this->assertGreaterThanOrEqual(80, $scoreAdmin);

        // Constructor con 600 unidades
        $scoreConstructor = $this->service->computeScore(600, 'constructor');
        $this->assertSame(100, $scoreConstructor);

        // Propietario con 40 unidades
        $scorePropietario = $this->service->computeScore(40, 'propietario');
        $this->assertLessThan(50, $scorePropietario);
    }

    public function test_calculate_and_score_produces_valid_roi_and_hours_metrics(): void
    {
        $dto = $this->service->calculateAndScore(
            unidades: 200,
            cuotaPromedioMensual: 400000.0,
            rol: 'administrador'
        );

        $this->assertInstanceOf(LeadCalculationDTO::class, $dto);
        $this->assertSame(200, $dto->unidadesTotales);
        $this->assertSame('standard', $dto->segmento);

        // 200 * 0.22 = 44 horas/mes
        $this->assertSame(44, $dto->horasAhorradasMes);

        // Cartera anual: 200 * 400.000 * 12 * 0.045 = 43.200.000 COP
        $this->assertEqualsWithDelta(43200000.0, $dto->carteraRecuperableEstimada, 1.0);

        // Ahorro neto anual: 44 * 12 * 25.000 + 43.200.000 = 13.200.000 + 43.200.000 = 56.400.000 COP
        $this->assertEqualsWithDelta(56400000.0, $dto->ahorroEstimadoAnual, 1.0);

        // Score: 38 (200 unidades) + 45 (administrador) = 83 (score >= 80 -> prioridad 'urgente')
        $this->assertSame(83, $dto->score);
        $this->assertSame('urgente', $dto->prioridadContacto);

        // Para un perfil intermedio (120 unidades, rol miembro_consejo -> score: 30 + 38 = 68 -> 'alta')
        $dtoConsejo = $this->service->calculateAndScore(120, 350000.0, 'miembro_consejo');
        $this->assertSame('standard', $dtoConsejo->segmento);
        $this->assertSame(68, $dtoConsejo->score);
        $this->assertSame('alta', $dtoConsejo->prioridadContacto);
    }

    public function test_capture_lead_dto_parses_request_and_legacy_fields_transparently(): void
    {
        $request = Request::create('/leads', 'POST', [
            'fullName' => 'Ing. Carlos Mendoza',
            'email' => 'carlos@condominio.com',
            'phone' => '+57 310 999 8888',
            'buildingName' => 'Altos de la Colina',
            'unidades_totales' => 150,
            'cuota_promedio_mensual' => 380000,
            'rol' => 'administrador',
        ]);

        $dto = LeadCaptureDTO::fromRequest($request);

        $this->assertSame('Ing. Carlos Mendoza', $dto->nombre);
        $this->assertSame('carlos@condominio.com', $dto->email);
        $this->assertSame('+57 310 999 8888', $dto->telefono);
        $this->assertSame('Altos de la Colina', $dto->copropiedadNombre);
        $this->assertSame(150, $dto->unidadesTotales);
        $this->assertEquals(380000.0, $dto->cuotaPromedioMensual);
        $this->assertNull($dto->websiteHp);
    }

    public function test_honeypot_silently_intercepts_bot_submissions_without_persistence(): void
    {
        $mockService = $this->createMock(LeadScoringServiceInterface::class);
        $mockService->expects($this->never())->method('calculateAndScore');

        $action = new CaptureLeadAction($mockService);

        $botDto = new LeadCaptureDTO(
            nombre: 'Spam Bot 3000',
            email: 'bot@spamnetwork.ru',
            telefono: '0000000000',
            copropiedadNombre: 'Spam Tower',
            websiteHp: 'https://malicious-spam-site.com/seo-boost'
        );

        $result = $action->execute($botDto);

        $this->assertNull($result);
    }

    public function test_store_lead_request_detects_spam_bots(): void
    {
        $request = new StoreLeadRequest();
        $request->merge(['website_hp' => 'http://spam.org']);

        $this->assertTrue($request->isSpamBot());

        $cleanRequest = new StoreLeadRequest();
        $cleanRequest->merge(['website_hp' => '']);

        $this->assertFalse($cleanRequest->isSpamBot());
    }
}

<?php

namespace App\Actions;

use App\DTOs\LeadCaptureDTO;
use App\Modules\Operations\Models\Lead;
use Illuminate\Support\Facades\Log;
use NexoPro\Operations\Contracts\LeadScoringServiceInterface;

/**
 * [LÓGICA DE NEGOCIO / UX]
 * Orquesta la captura de prospectos: califica el lead en tiempo real con el motor financiero,
 * determina el segmento de mercado (Enterprise, Standard o Boutique) y persiste los datos
 * de forma inmutable para el equipo de ventas.
 *
 * [ASPECTO TÉCNICO / UI]
 * Action invokable bajo Single Responsibility Principle (SRP). Depende de la abstracción
 * LeadScoringServiceInterface (DIP). Filtra silenciosamente envíos de bots que hayan completado
 * el campo trampa honeypot sin alertar al scraper (OWASP A04).
 */
class CaptureLeadAction
{
    public function __construct(
        private readonly LeadScoringServiceInterface $scoringService
    ) {}

    public function execute(LeadCaptureDTO $dto): ?Lead
    {
        // 1. Detección silenciosa de Spam / Bot
        if (!empty($dto->websiteHp)) {
            Log::warning('Honeypot activado en formulario de leads. Petición bot descartada.', [
                'ip' => $dto->ipAddress,
                'email' => $dto->email,
            ]);
            return null;
        }

        // 2. Cálculo financiero y scoring algorítmico
        $calculation = $this->scoringService->calculateAndScore(
            unidades: $dto->unidadesTotales,
            cuotaPromedioMensual: $dto->cuotaPromedioMensual,
            rol: $dto->rol
        );

        // 3. Persistencia transaccional del lead
        $lead = Lead::create([
            'nombre' => $dto->nombre,
            'email' => $dto->email,
            'telefono' => $dto->telefono,
            'copropiedad_nombre' => $dto->copropiedadNombre,
            'unidades_totales' => $calculation->unidadesTotales,
            'rol' => $dto->rol,
            'segmento' => $calculation->segmento,
            'cuota_promedio_mensual' => $calculation->cuotaPromedioMensual,
            'ahorro_estimado_anual' => $calculation->ahorroEstimadoAnual,
            'horas_ahorradas_mes' => $calculation->horasAhorradasMes,
            'cartera_recuperable_estimada' => $calculation->carteraRecuperableEstimada,
            'estado' => 'nuevo',
            'score' => $calculation->score,
            'ip_address' => $dto->ipAddress,
            'user_agent' => $dto->userAgent ? substr($dto->userAgent, 0, 255) : null,
            'metadata' => array_merge($dto->metadata, [
                'prioridad_contacto' => $calculation->prioridadContacto,
                'capturado_en' => now()->toIso8601String(),
                'origen' => 'landing_roi_calculator',
            ]),
        ]);

        Log::info("Lead capturado y calificado: {$lead->id}", [
            'segmento' => $lead->segmento,
            'score' => $lead->score,
            'prioridad' => $calculation->prioridadContacto,
            'unidades' => $lead->unidades_totales,
        ]);

        return $lead;
    }
}

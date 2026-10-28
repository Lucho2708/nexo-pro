<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Frontend\StoreDemoRequest;
use App\Services\Subscription\PlanService;
use Inertia\Inertia;

class LandingController extends Controller
{
    /**
     * Display the landing page.
     *
     * @return \Inertia\Response
     */
    public function index(PlanService $planService)
    {
        return Inertia::render('Welcome', [
            'features' => $this->getFeatures(),
            'plans' => array_values($planService->getPlans()),
        ]);
    }

    /**
     * Handle a demo request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function requestDemo(StoreDemoRequest $request)
    {
        // En una aplicación real, guardaríamos esto o enviaríamos notificaciones
        return back()->with('success', 'Dispatcher activado. Un consultor técnico se pondrá en contacto pronto.');
    }

    /**
     * Get the static features data for the landing page.
     * 
     * @return array
     */
    private function getFeatures(): array
    {
        return [
            [
                'icon' => 'payments',
                'title' => 'Recaudo Automatizado',
                'description' => 'Pasarela de pagos integrada con conciliación bancaria instantánea. Cero errores, 100% transparencia.',
                'tag' => 'MÓDULO FINANCIERO'
            ],
            [
                'icon' => 'groups',
                'title' => 'Asambleas Virtuales',
                'description' => 'Votaciones encriptadas y quórum automático bajo la Ley 675. Democracia digital inquebrantable.',
                'tag' => 'MODERACIÓN PRO'
            ],
            [
                'icon' => 'engineering',
                'title' => 'Soporte de Ingeniería',
                'description' => 'Acceso directo a ingenieros de Nexo-Pro para resolver incidencias críticas de infraestructura.',
                'tag' => 'SOPORTE TÉCNICO'
            ]
        ];
    }

    /**
     * Get the subscription plans data aligned with the B2B high-conversion strategy.
     * 
     * @return array
     */
    private function getPlans(): array
    {
        return [
            [
                'name' => 'BOUTIQUE',
                'price_monthly' => 189000,
                'price_annual' => 151200,
                'period_label' => 'Por Copropiedad / Mes',
                'description' => 'Solución completa para edificios y conjuntos pequeños.',
                'features' => [
                    'Hasta 60 unidades privadas',
                    'Recaudo PSE, Wompi y Nequi',
                    'App móvil para residentes',
                    'Citofonía virtual y portería',
                    'Liquidación automática de cuotas',
                    'Paz y salvos digitales con QR'
                ],
                'is_recommended' => false,
            ],
            [
                'name' => 'CONDOMINIO PRO',
                'price_monthly' => 289000,
                'price_annual' => 231200,
                'period_label' => 'Por Copropiedad / Mes',
                'description' => 'El estándar de oro para administradores profesionales y consejos.',
                'features' => [
                    'Hasta 150 unidades privadas',
                    'Asambleas Virtuales Ley 675 en vivo',
                    'Votación atómica en tiempo real',
                    'Conciliación bancaria automática',
                    'Cobranza por WhatsApp con link directo',
                    'Dispatcher de reservas de zonas comunes',
                    'Auditoría forense de operaciones'
                ],
                'is_recommended' => true,
            ],
            [
                'name' => 'MACRO-CONJUNTO',
                'price_monthly' => 420000,
                'price_annual' => 336000,
                'period_label' => 'Por Copropiedad / Mes',
                'description' => 'Máxima potencia para grandes copropiedades y urbanizaciones multitorre.',
                'features' => [
                    'Hasta 350 unidades privadas',
                    'Múltiples torres y porterías simultáneas',
                    'Migración asistida de datos Excel en 24h',
                    'Soporte técnico prioritario en asambleas',
                    'Acceso multi-administrador y consejo',
                    'Control vehicular y de parqueaderos'
                ],
                'is_recommended' => false,
            ],
        ];
    }
}

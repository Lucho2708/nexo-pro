<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Frontend\StoreDemoRequest;
use Inertia\Inertia;

class LandingController extends Controller
{
    /**
     * Display the landing page.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        return Inertia::render('Welcome', [
            'features' => $this->getFeatures(),
            'plans' => $this->getPlans(),
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
     * Get the subscription plans data.
     * 
     * @return array
     */
    private function getPlans(): array
    {
        return [
            [
                'name' => 'BASIC CORE',
                'price_monthly' => 189000,
                'price_annual' => 151200,
                'description' => 'Potencia esencial para edificios boutique.',
                'features' => [
                    'Soporte de hasta 50 unidades',
                    'Recaudo Pasarela Base',
                    'App Residentes (iOS/Android)',
                    'Monitor de Salud Financiera'
                ],
                'is_recommended' => false,
            ],
            [
                'name' => 'ELITE MONITOR',
                'price_monthly' => 389000,
                'price_annual' => 311200,
                'description' => 'El estándar de oro para grandes copropiedades.',
                'features' => [
                    'Unidades Ilimitadas',
                    'Asambleas Virtuales Full',
                    'Dispatcher de Reservas Pro',
                    'Soporte Prioritario de Ingeniería',
                    'Auditoría Forense de Logs'
                ],
                'is_recommended' => true,
            ],
            [
                'name' => 'ENTERPRISE',
                'price_monthly' => null,
                'price_annual' => null,
                'description' => 'Soluciones a medida para corporativos y constructoras.',
                'features' => [
                    'Integraciones vía API API-Nexo',
                    'Account Manager dedicado',
                    'Implementación In-Site',
                    'SLA de Respuesta Industrial'
                ],
                'is_recommended' => false,
            ],
        ];
    }
}

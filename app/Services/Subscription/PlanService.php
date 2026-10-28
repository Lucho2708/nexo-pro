<?php

namespace App\Services\Subscription;

use App\Modules\Property\Models\Copropiedad;

class PlanService
{
    /**
     * Official canonical subscription plans mapping.
     */
    private const PLANS = [
        'boutique' => [
            'slug' => 'boutique',
            'name' => 'BOUTIQUE',
            'max_units' => 60,
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
                'Paz y salvos digitales con QR',
            ],
            'is_recommended' => false,
        ],
        'condominio_pro' => [
            'slug' => 'condominio_pro',
            'name' => 'CONDOMINIO PRO',
            'max_units' => 150,
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
                'Auditoría forense de operaciones',
            ],
            'is_recommended' => true,
        ],
        'macro_conjunto' => [
            'slug' => 'macro_conjunto',
            'name' => 'MACRO-CONJUNTO',
            'max_units' => 350,
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
                'Control vehicular y de parqueaderos',
            ],
            'is_recommended' => false,
        ],
        'enterprise' => [
            'slug' => 'enterprise',
            'name' => 'EMPRESA / CONSTRUCTORA',
            'max_units' => null, // Ilimitado / Custom
            'price_monthly' => null,
            'price_annual' => null,
            'period_label' => 'A la medida',
            'description' => 'Para constructoras y empresas administradoras con múltiples conjuntos.',
            'features' => [
                'Unidades privadas ilimitadas',
                'Mando Central multi-copropiedad',
                'Descuentos por volumen desde 4 copropiedades',
                'Migración asistida de datos sin costo',
                'SLA de soporte exclusivo 24/7',
            ],
            'is_recommended' => false,
        ],
    ];

    /**
     * Legacy slug mapping to canonical slugs.
     */
    private const LEGACY_MAP = [
        'basic' => 'boutique',
        'pro' => 'condominio_pro',
        'enterprise' => 'enterprise',
        'standalone' => 'enterprise',
    ];

    /**
     * Get all official plans.
     */
    public function getPlans(): array
    {
        return self::PLANS;
    }

    /**
     * Get a single plan by slug (with legacy fallback).
     */
    public function getPlan(?string $slug): array
    {
        $normalized = strtolower(trim((string)$slug));

        if (isset(self::PLANS[$normalized])) {
            return self::PLANS[$normalized];
        }

        if (isset(self::LEGACY_MAP[$normalized])) {
            return self::PLANS[self::LEGACY_MAP[$normalized]];
        }

        return self::PLANS['condominio_pro'];
    }

    /**
     * Validate whether a copropiedad can add a given number of units within its contracted plan limit.
     *
     * @return array{allowed: bool, max_units: ?int, current_units: int, attempted_total: int, plan_name: string, message: ?string}
     */
    public function validateUnitCapacity(Copropiedad $copropiedad, int $additionalUnits = 0): array
    {
        $plan = $this->getPlan($copropiedad->plan);
        $maxUnits = $plan['max_units'];
        $currentUnits = $copropiedad->unidades()->count();
        $attemptedTotal = $currentUnits + $additionalUnits;

        if ($maxUnits === null) {
            // Enterprise / Unlimited plan
            return [
                'allowed' => true,
                'max_units' => null,
                'current_units' => $currentUnits,
                'attempted_total' => $attemptedTotal,
                'plan_name' => $plan['name'],
                'message' => null,
            ];
        }

        if ($attemptedTotal > $maxUnits) {
            $message = "El plan actual ({$plan['name']}) tiene un límite máximo de {$maxUnits} unidades privadas. Intentas registrar {$attemptedTotal} unidades. El plan debe ser actualizado antes de continuar.";

            return [
                'allowed' => false,
                'max_units' => $maxUnits,
                'current_units' => $currentUnits,
                'attempted_total' => $attemptedTotal,
                'plan_name' => $plan['name'],
                'message' => $message,
            ];
        }

        return [
            'allowed' => true,
            'max_units' => $maxUnits,
            'current_units' => $currentUnits,
            'attempted_total' => $attemptedTotal,
            'plan_name' => $plan['name'],
            'message' => null,
        ];
    }
}

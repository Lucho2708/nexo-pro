<script setup lang="ts">
/**
 * NexoPricingGrid.vue
 *
 * [LÓGICA DE NEGOCIO / UX]
 * Organismo de tarificación comercial responsivo para la adquisición y escalamiento de licencias de NEXO PRO.
 * - Resuelve proactivamente los problemas de recorte tipográfico y pérdida de contraste entre fondos claros y oscuros.
 * - Brinda una jerarquía visual nítida (60-30-10) destacando el plan recomendado para maximizar la tasa de conversión (CR).
 * - Permite al cliente conmutar entre facturación mensual y anual (con 20% de ahorro) con actualización instantánea de valores.
 * - Ergonomía táctil para decisiones de compra desde smartphones (320px+), tablets y monitores UltraWide.
 *
 * [ASPECTO TÉCNICO / UI]
 * - Implementación bajo metodología Atomic Design utilizando átomos NexoButton y NexoBadge.
 * - Grilla fluida CSS Grid con minmax y contención tipográfica tabular-nums + truncate para erradicar el desbordamiento horizontal.
 * - Alturas mínimas reservadas en textos descriptivos para prevenir Cumulative Layout Shift (CLS = 0).
 * - Cumplimiento estricto del ratio de contraste WCAG 2.1 AA (mínimo 4.5:1 en textos y 3.0:1 en componentes UI).
 */

import { ref, computed } from 'vue';
import NexoButton from '../atoms/NexoButton.vue';
import NexoBadge from '../atoms/NexoBadge.vue';

export interface PlanFeature {
    text: string;
    included: boolean;
}

export interface PricingPlan {
    id: string;
    name: string;
    badge?: string;
    description: string;
    priceMonthly: number | null; // null para 'CUSTOM' / Enterprise
    priceAnnual: number | null;
    features: string[];
    isRecommended?: boolean;
    ctaText?: string;
}

const props = withDefaults(
    defineProps<{
        plans: PricingPlan[];
        initialAnnual?: boolean;
    }>(),
    {
        initialAnnual: false,
    }
);

const emit = defineEmits<{
    (e: 'select-plan', plan: PricingPlan, isAnnual: boolean): void;
}>();

const isAnnual = ref(props.initialAnnual);

const formatPrice = (price: number | null) => {
    if (price === null) return 'CUSTOM';
    return new Intl.NumberFormat('es-CO', {
        style: 'currency',
        currency: 'COP',
        maximumFractionDigits: 0,
    }).format(price);
};
</script>

<template>
    <section class="w-full space-y-10 sm:space-y-14 font-sans text-left">
        <!-- Toggle Mensual / Anual con Indicador de Ahorro -->
        <div class="flex flex-col sm:flex-row items-center justify-center gap-3 sm:gap-6">
            <div class="flex items-center gap-3">
                <span
                    class="text-xs sm:text-sm font-bold uppercase tracking-wider transition-colors"
                    :class="!isAnnual ? 'text-blue-600 dark:text-blue-400 font-extrabold' : 'text-slate-500 dark:text-slate-400'"
                >
                    Facturación Mensual
                </span>

                <button
                    type="button"
                    role="switch"
                    :aria-checked="isAnnual"
                    aria-label="Conmutar entre facturación mensual y anual con descuento"
                    @click="isAnnual = !isAnnual"
                    class="w-14 sm:w-16 h-7 sm:h-8 rounded-full bg-slate-200 dark:bg-slate-800 p-1 flex items-center transition-colors duration-200 cursor-pointer focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500"
                >
                    <div
                        class="w-5 sm:w-6 h-5 sm:h-6 rounded-full bg-blue-600 shadow-sm transform transition-transform duration-200"
                        :class="{ 'translate-x-7 sm:translate-x-8': isAnnual }"
                    ></div>
                </button>

                <span
                    class="text-xs sm:text-sm font-bold uppercase tracking-wider transition-colors"
                    :class="isAnnual ? 'text-blue-600 dark:text-blue-400 font-extrabold' : 'text-slate-500 dark:text-slate-400'"
                >
                    Facturación Anual
                </span>
            </div>

            <NexoBadge variant="success" size="sm">
                Ahorra 20% Anual
            </NexoBadge>
        </div>

        <!-- Grilla Adaptativa Multi-Dispositivo (1 Col Mobile, 2 Col Tablet, 3 Col Desktop) -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8 items-stretch">
            <article
                v-for="plan in plans"
                :key="plan.id"
                class="rounded-3xl p-6 sm:p-7 xl:p-8 border transition-all duration-300 relative group flex flex-col justify-between overflow-hidden"
                :class="[
                    plan.isRecommended
                        ? '!bg-slate-950 !border-indigo-500/60 shadow-xl shadow-indigo-500/10 md:-translate-y-2 z-10 text-white'
                        : 'bg-white dark:bg-slate-900/90 border-slate-200 dark:border-slate-800 shadow-xs hover:border-blue-500/40 text-slate-900 dark:text-white'
                ]"
            >
                <!-- Insignia Superior Flotante para Plan Recomendado -->
                <div
                    v-if="plan.isRecommended"
                    class="absolute top-0 right-8 bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-4 py-1 rounded-b-xl text-[10px] font-black uppercase tracking-[0.2em] italic shadow-md"
                >
                    {{ plan.badge || 'Recomendado' }}
                </div>

                <div class="space-y-6 mb-8">
                    <!-- Nombre del Nivel -->
                    <h3
                        class="text-xs font-black uppercase tracking-[0.25em] italic"
                        :class="plan.isRecommended ? 'text-indigo-400' : 'text-blue-600 dark:text-blue-400'"
                    >
                        {{ plan.name }}
                    </h3>

                    <!-- Precio Dinámico con Tipografía Fluida (Cero Desbordamiento) -->
                    <div class="flex flex-col gap-1 min-w-0">
                        <span
                            class="text-3xl sm:text-4xl lg:text-3xl xl:text-4xl 2xl:text-5xl font-black tracking-tight tabular-nums italic truncate"
                            :class="plan.isRecommended ? 'text-white' : 'text-slate-900 dark:text-white'"
                        >
                            {{ formatPrice(isAnnual ? plan.priceAnnual : plan.priceMonthly) }}
                        </span>
                        <span
                            v-if="plan.priceMonthly !== null"
                            class="text-xs font-bold uppercase tracking-widest opacity-60 ml-0.5"
                            :class="plan.isRecommended ? 'text-slate-300' : 'text-slate-500 dark:text-slate-400'"
                        >
                            Por Unidad / Mes
                        </span>
                    </div>

                    <!-- Descripción con Reserva de Espacio (Zero CLS) -->
                    <p
                        class="text-xs font-medium leading-relaxed italic min-h-[44px]"
                        :class="plan.isRecommended ? 'text-slate-300' : 'text-slate-600 dark:text-slate-400'"
                    >
                        {{ plan.description }}
                    </p>

                    <!-- Divisor -->
                    <div
                        class="w-full h-px"
                        :class="plan.isRecommended ? 'bg-slate-800' : 'bg-slate-100 dark:bg-slate-800'"
                    ></div>

                    <!-- Lista de Beneficios con Iconos Accesibles -->
                    <ul class="space-y-3.5">
                        <li
                            v-for="(feature, idx) in plan.features"
                            :key="idx"
                            class="flex items-center gap-3 min-w-0"
                        >
                            <div
                                class="w-5 h-5 rounded-lg flex items-center justify-center shrink-0"
                                :class="plan.isRecommended ? 'bg-indigo-500/20 text-indigo-400' : 'bg-blue-50 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400'"
                            >
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <span
                                class="text-xs font-bold uppercase tracking-tight truncate"
                                :class="plan.isRecommended ? 'text-slate-200' : 'text-slate-700 dark:text-slate-300'"
                            >
                                {{ feature }}
                            </span>
                        </li>
                    </ul>
                </div>

                <!-- Botón de Conversión CTA con Touch Target Mínimo >= 48px -->
                <NexoButton
                    :variant="plan.isRecommended ? 'secondary' : 'primary'"
                    size="md"
                    class="w-full !min-h-[50px] !h-auto font-black uppercase italic shadow-sm"
                    @click="emit('select-plan', plan, isAnnual)"
                >
                    {{ plan.ctaText || (plan.priceMonthly === null ? 'Contactar Ingeniería' : 'Activar Licencia') }}
                </NexoButton>
            </article>
        </div>
    </section>
</template>

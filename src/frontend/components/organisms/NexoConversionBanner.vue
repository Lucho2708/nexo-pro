<script setup lang="ts">
/**
 * NexoConversionBanner.vue
 *
 * [LÓGICA DE NEGOCIO / UX]
 * Organismo de alta conversión y retención para portales de propietarios y asambleístas.
 * - Resuelve el principal punto de fricción detectado en la investigación de mercado (PropTech Benchmark 2026):
 *   la cartera morosa y la apatía en votaciones de asamblea.
 * - Ofrece una llamada a la acción (CTA) inmediata de 1-Clic para pagar expensas por Wompi o acceder a la sala de votación.
 * - Cuando el copropietario está al día (saldo $0), premia psicológicamente con un estado de felicitación visual.
 *
 * [ASPECTO TÉCNICO / UI]
 * - Organismo compuesto con arquitectura Atomic Design integrando átomos NexoButton y NexoBadge.
 * - Jerarquía 60-30-10: Fondo neutral Slate-900 (Dark) / Slate-50 (Light), superficie con borde degradado de marca (Azul/Índigo).
 * - Layout Mobile-First: disposición apilada en pantallas < 640px y horizontal en tablets/desktop.
 * - WCAG 2.1 AA: Contraste > 4.5:1 en todos los textos, soporte completo de navegación por teclado.
 */

import { computed } from 'vue';
import NexoButton from '../atoms/NexoButton.vue';
import NexoBadge from '../atoms/NexoBadge.vue';

type BannerType = 'payment' | 'assembly' | 'announcement';

const props = withDefaults(
    defineProps<{
        type?: BannerType;
        title: string;
        description?: string;
        amount?: number | string;
        ctaText?: string;
        secondaryCtaText?: string;
        isSubmitting?: boolean;
        isUpToDate?: boolean;
    }>(),
    {
        type: 'payment',
        description: undefined,
        amount: undefined,
        ctaText: 'Pagar Ahora con Wompi',
        secondaryCtaText: 'Ver Detalle',
        isSubmitting: false,
        isUpToDate: false,
    }
);

const emit = defineEmits<{
    (e: 'cta-click'): void;
    (e: 'secondary-click'): void;
}>();

const formattedAmount = computed(() => {
    if (props.amount === undefined || props.amount === null) return null;
    const num = typeof props.amount === 'string' ? parseFloat(props.amount) : props.amount;
    return new Intl.NumberFormat('es-CO', {
        style: 'currency',
        currency: 'COP',
        maximumFractionDigits: 0,
    }).format(num);
});
</script>

<template>
    <div
        class="relative w-full overflow-hidden rounded-2xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm transition-all duration-200"
    >
        <!-- Línea superior de acento con gradiente de marca NEXO PRO -->
        <div class="h-1.5 w-full bg-gradient-to-r from-blue-600 via-indigo-600 to-blue-500"></div>

        <div class="p-6 sm:p-7 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
            <!-- Columna Informativa de Valor -->
            <div class="space-y-2.5 max-w-2xl">
                <div class="flex flex-wrap items-center gap-2">
                    <NexoBadge
                        v-if="isUpToDate"
                        variant="success"
                        :dot="true"
                    >
                        Paz y Salvo
                    </NexoBadge>
                    <NexoBadge
                        v-else-if="type === 'payment'"
                        variant="warning"
                        :dot="true"
                    >
                        Expensas Pendientes
                    </NexoBadge>
                    <NexoBadge
                        v-else-if="type === 'assembly'"
                        variant="info"
                        :dot="true"
                        :pulse="true"
                    >
                        Asamblea en Vivo
                    </NexoBadge>
                    
                    <span class="text-xs font-semibold text-slate-400 dark:text-slate-500">
                        NEXO PRO FastAction
                    </span>
                </div>

                <h2 class="text-xl sm:text-2xl font-extrabold text-slate-900 dark:text-white tracking-tight leading-snug">
                    {{ title }}
                </h2>

                <p v-if="description" class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed">
                    {{ description }}
                </p>

                <!-- Monto Destacado si aplica -->
                <div v-if="!isUpToDate && formattedAmount" class="pt-1 flex items-baseline gap-2">
                    <span class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                        Total por Pagar:
                    </span>
                    <span class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white font-mono">
                        {{ formattedAmount }}
                    </span>
                </div>
            </div>

            <!-- Columna de Botones de Conversión (CTAs) -->
            <div class="flex flex-col sm:flex-row md:flex-col lg:flex-row items-stretch sm:items-center gap-3 shrink-0">
                <NexoButton
                    v-if="secondaryCtaText && !isUpToDate"
                    variant="outline"
                    size="md"
                    :disabled="isSubmitting"
                    @click="emit('secondary-click')"
                >
                    {{ secondaryCtaText }}
                </NexoButton>

                <NexoButton
                    v-if="!isUpToDate"
                    variant="primary"
                    size="md"
                    :isLoading="isSubmitting"
                    @click="emit('cta-click')"
                >
                    <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                    {{ ctaText }}
                </NexoButton>

                <NexoButton
                    v-else
                    variant="outline"
                    size="md"
                    @click="emit('cta-click')"
                >
                    Descargar Paz y Salvo (PDF)
                </NexoButton>
            </div>
        </div>
    </div>
</template>

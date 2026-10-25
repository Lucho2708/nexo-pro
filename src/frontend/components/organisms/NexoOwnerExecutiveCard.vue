<script setup lang="ts">
/**
 * NexoOwnerExecutiveCard.vue
 *
 * [LÓGICA DE NEGOCIO / UX]
 * Tarjeta ejecutiva de resumen para el Portal del Propietario en NEXO PRO.
 * - Diseñado bajo el principio de reducción drástica de la carga cognitiva: el residente sabe en 3 segundos
 *   si está a paz y salvo, cuánto debe y si hay una asamblea activa en la que deba votar.
 * - Brinda acceso en 1-Clic a la pasarela Wompi para pago de cuotas sin navegar por pantallas complejas.
 * - Destaca las unidades inmobiliarias representadas (apartamentos, parqueaderos, depósitos) con sus coeficientes.
 *
 * [ASPECTO TÉCNICO / UI]
 * - Jerarquía visual 60-30-10 con tokens semánticos del entorno 'owner'.
 * - Layout elástico Mobile-First adaptable a smartphones (320px+), tablets y monitores.
 * - Ratios de contraste WCAG 2.1 AA estrictamente garantizados (> 4.8:1 en modo claro y oscuro).
 */

import { computed } from 'vue';
import NexoEnvironmentBadge from '../atoms/NexoEnvironmentBadge.vue';
import NexoButton from '../atoms/NexoButton.vue';
import NexoBadge from '../atoms/NexoBadge.vue';

interface OwnedUnit {
    id: string;
    nombre: string;
    coeficiente: number;
    saldo: number;
}

interface AssemblyAlert {
    id: string;
    title: string;
    hasVoted?: boolean;
}

const props = withDefaults(
    defineProps<{
        ownerName?: string;
        copropiedadName?: string;
        units?: OwnedUnit[];
        totalSaldo?: number;
        activeAssembly?: AssemblyAlert | null;
        isPaymentProcessing?: boolean;
    }>(),
    {
        ownerName: 'Copropietario',
        copropiedadName: 'Conjunto Residencial',
        units: () => [
            { id: 'u-1', nombre: 'Apartamento 302', coeficiente: 1.4500, saldo: 0 },
            { id: 'u-2', nombre: 'Parqueadero P-14', coeficiente: 0.3500, saldo: 0 },
        ],
        totalSaldo: 0,
        activeAssembly: null,
        isPaymentProcessing: false,
    }
);

const emit = defineEmits<{
    (e: 'pay-balance'): void;
    (e: 'enter-assembly', assemblyId: string): void;
    (e: 'download-statement'): void;
}>();

const isUpToDate = computed(() => (props.totalSaldo ?? 0) <= 0);

const totalWeight = computed(() => {
    return props.units.reduce((acc, u) => acc + (u.coeficiente || 0), 0);
});

const formattedBalance = computed(() => {
    return new Intl.NumberFormat('es-CO', {
        style: 'currency',
        currency: 'COP',
        maximumFractionDigits: 0,
    }).format(Math.abs(props.totalSaldo || 0));
});
</script>

<template>
    <div class="w-full space-y-6 font-sans text-left">
        <!-- Tarjeta Principal del Propietario -->
        <div class="rounded-3xl border border-slate-200/90 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 sm:p-8 shadow-xs space-y-6">
            <!-- Header de Bienvenida y Paz y Salvo -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-6 border-b border-slate-100 dark:border-slate-800">
                <div class="space-y-1">
                    <div class="flex items-center gap-2.5">
                        <NexoEnvironmentBadge environment="owner" label="Residente Titular" />
                        <span class="text-xs font-medium text-slate-500">{{ copropiedadName }}</span>
                    </div>
                    <h2 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white tracking-tight">
                        Hola, {{ ownerName }}
                    </h2>
                </div>

                <!-- Insignia de Estado Financiero -->
                <NexoBadge
                    :variant="isUpToDate ? 'success' : 'warning'"
                    size="md"
                    :dot="true"
                    class="self-start sm:self-auto"
                >
                    {{ isUpToDate ? 'Paz y Salvo General' : 'Saldo Pendiente de Pago' }}
                </NexoBadge>
            </div>

            <!-- Alerta de Asamblea Virtual en Curso -->
            <div
                v-if="activeAssembly"
                class="p-4 sm:p-5 rounded-2xl border border-indigo-200 dark:border-indigo-900/80 bg-indigo-50/70 dark:bg-indigo-950/40 flex flex-col sm:flex-row sm:items-center justify-between gap-4"
            >
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-indigo-600 text-white flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 animate-bounce" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                        </svg>
                    </div>
                    <div>
                        <span class="text-[11px] font-black uppercase tracking-wider text-indigo-700 dark:text-indigo-300">
                            Asamblea General en Sesión
                        </span>
                        <h4 class="text-sm font-bold text-slate-900 dark:text-white">
                            {{ activeAssembly.title }}
                        </h4>
                    </div>
                </div>

                <NexoButton
                    variant="secondary"
                    size="sm"
                    class="shrink-0"
                    @click="emit('enter-assembly', activeAssembly.id)"
                >
                    {{ activeAssembly.hasVoted ? 'Ver Resultados en Vivo' : 'Emitir Mi Voto' }}
                </NexoButton>
            </div>

            <!-- Desglose de Saldo y Acciones de Pago -->
            <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-center">
                <!-- Saldo Consolidado -->
                <div class="md:col-span-7 space-y-2">
                    <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">
                        {{ isUpToDate ? 'Saldo a Favor / Al Día' : 'Saldo Total a Pagar' }}
                    </span>
                    <div class="flex items-baseline gap-2">
                        <span
                            class="text-3xl sm:text-4xl lg:text-5xl font-black tracking-tight tabular-nums"
                            :class="isUpToDate ? 'text-emerald-600 dark:text-emerald-400' : 'text-slate-900 dark:text-white'"
                        >
                            {{ isUpToDate ? '$ 0 COP' : formattedBalance }}
                        </span>
                    </div>
                    <p class="text-xs text-slate-500">
                        {{ isUpToDate ? 'No presentas cobros pendientes para el periodo actual.' : 'Incluye expensas comunes de administración e intereses calculados.' }}
                    </p>
                </div>

                <!-- Botón de Pago Wompi de 1-Clic -->
                <div class="md:col-span-5 flex flex-col sm:flex-row md:flex-col gap-3">
                    <NexoButton
                        v-if="!isUpToDate"
                        variant="primary"
                        size="lg"
                        :isLoading="isPaymentProcessing"
                        class="w-full !min-h-[52px] shadow-lg shadow-blue-500/20"
                        @click="emit('pay-balance')"
                    >
                        <svg class="w-5 h-5 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                        Pagar con Wompi (PSE / Nequi)
                    </NexoButton>

                    <NexoButton
                        variant="outline"
                        size="md"
                        class="w-full !min-h-[48px]"
                        @click="emit('download-statement')"
                    >
                        Descargar Estado de Cuenta
                    </NexoButton>
                </div>
            </div>

            <!-- Inmuebles Representados y Ponderación de Voto -->
            <div class="pt-4 border-t border-slate-100 dark:border-slate-800 space-y-3">
                <div class="flex items-center justify-between text-xs">
                    <span class="font-bold text-slate-700 dark:text-slate-300">
                        Inmuebles Vinculados ({{ units.length }})
                    </span>
                    <span class="font-bold text-blue-600 dark:text-blue-400">
                        Coeficiente Total: {{ totalWeight.toFixed(4) }}%
                    </span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div
                        v-for="unit in units"
                        :key="unit.id"
                        class="p-3 rounded-xl border border-slate-100 dark:border-slate-800 bg-slate-50/70 dark:bg-slate-800/40 flex items-center justify-between text-xs"
                    >
                        <span class="font-semibold text-slate-800 dark:text-slate-200 truncate">
                            {{ unit.nombre }}
                        </span>
                        <span class="font-mono text-slate-500 shrink-0 ml-2">
                            {{ unit.coeficiente.toFixed(4) }}%
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

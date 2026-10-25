<script setup lang="ts">
/**
 * NexoAdminOperationalGrid.vue
 *
 * [LÓGICA DE NEGOCIO / UX]
 * Organismo de control operativo y financiero para administradores de propiedad horizontal en NEXO PRO.
 * - Brinda una visión inmediata de la salud financiera del conjunto: recaudo del mes, cartera vencida y morosidad.
 * - Alerta de asambleas activas o programadas, permitiendo el ingreso directo a la sala de moderación.
 * - Facilita la toma de decisiones con accesos directos para liquidación de expensas y descarga de estados de cuenta.
 *
 * [ASPECTO TÉCNICO / UI]
 * - Jerarquía visual 60-30-10 con tokens semánticos del entorno 'admin'.
 * - Grilla fluida responsive (1 columna en móvil, 2 en tablet, 4 en desktop).
 * - Cumplimiento estricto de WCAG 2.1 AA con contraste superior a 4.5:1 y prevención de CLS.
 */

import { computed } from 'vue';
import NexoEnvironmentBadge from '../atoms/NexoEnvironmentBadge.vue';
import NexoButton from '../atoms/NexoButton.vue';
import NexoBadge from '../atoms/NexoBadge.vue';

interface ActiveAssembly {
    id: string;
    title: string;
    date: string;
    status: 'scheduled' | 'in_progress';
    quorumPercentage?: number;
}

const props = withDefaults(
    defineProps<{
        copropiedadName?: string;
        totalUnits?: number;
        carteraTotal?: number;
        carteraMora?: number;
        recaudoPorcentaje?: number;
        activeAssembly?: ActiveAssembly | null;
        openPqrsCount?: number;
        isBillingInProgress?: boolean;
    }>(),
    {
        copropiedadName: 'Torres del Parque P.H.',
        totalUnits: 120,
        carteraTotal: 42500000,
        carteraMora: 3200000,
        recaudoPorcentaje: 92.4,
        activeAssembly: null,
        openPqrsCount: 3,
        isBillingInProgress: false,
    }
);

const emit = defineEmits<{
    (e: 'trigger-billing'): void;
    (e: 'enter-assembly', assemblyId: string): void;
    (e: 'view-cartera'): void;
}>();

const formatCurrency = (amount: number) => {
    return new Intl.NumberFormat('es-CO', {
        style: 'currency',
        currency: 'COP',
        maximumFractionDigits: 0,
    }).format(amount);
};
</script>

<template>
    <div class="w-full space-y-6 font-sans text-left">
        <!-- Barra de Contexto Operativo -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 p-5 rounded-2xl border border-slate-200/90 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-xs">
            <div class="space-y-1">
                <div class="flex items-center gap-2.5">
                    <NexoEnvironmentBadge environment="admin" label="Administración Operativa" />
                    <span class="text-xs font-bold text-slate-500 dark:text-slate-400">
                        {{ totalUnits }} Inmuebles Registrados
                    </span>
                </div>
                <h2 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white tracking-tight">
                    {{ copropiedadName }}
                </h2>
            </div>

            <!-- Acciones Rápidas del Administrador -->
            <div class="flex items-center gap-2.5 shrink-0">
                <NexoButton
                    variant="outline"
                    size="sm"
                    @click="emit('view-cartera')"
                >
                    Reporte de Cartera
                </NexoButton>

                <NexoButton
                    variant="primary"
                    size="sm"
                    :isLoading="isBillingInProgress"
                    @click="emit('trigger-billing')"
                >
                    Facturar Periodo Mensual
                </NexoButton>
            </div>
        </div>

        <!-- Alerta de Asamblea Activa en Curso (Si aplica) -->
        <div
            v-if="activeAssembly"
            class="p-5 rounded-2xl border border-blue-200 dark:border-blue-900/80 bg-blue-50/60 dark:bg-blue-950/40 flex flex-col sm:flex-row sm:items-center justify-between gap-4"
        >
            <div class="flex items-center gap-3 min-w-0">
                <div class="w-10 h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                    </svg>
                </div>
                <div class="min-w-0">
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-black uppercase tracking-wider text-blue-700 dark:text-blue-300">
                            {{ activeAssembly.status === 'in_progress' ? 'Asamblea en Curso' : 'Asamblea Programada' }}
                        </span>
                        <NexoBadge v-if="activeAssembly.quorumPercentage" variant="info" size="sm">
                            Quórum: {{ activeAssembly.quorumPercentage.toFixed(2) }}%
                        </NexoBadge>
                    </div>
                    <h3 class="text-base font-bold text-slate-900 dark:text-white truncate">
                        {{ activeAssembly.title }}
                    </h3>
                </div>
            </div>

            <NexoButton
                variant="primary"
                size="sm"
                class="shrink-0"
                @click="emit('enter-assembly', activeAssembly.id)"
            >
                Entrar a la Sala Virtual
            </NexoButton>
        </div>

        <!-- Grilla de KPIs Financieros y Operativos (4 Columnas) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Recaudo Mensual -->
            <div class="p-5 rounded-2xl border border-slate-200/90 dark:border-slate-800 bg-white dark:bg-slate-900 space-y-2">
                <span class="text-xs font-semibold uppercase text-slate-500 tracking-wider">Recaudo Efectivo</span>
                <div class="flex items-baseline justify-between">
                    <span class="text-2xl font-black text-slate-900 dark:text-white tracking-tight tabular-nums">
                        {{ recaudoPorcentaje }}%
                    </span>
                    <NexoBadge variant="success" size="sm">Al Día</NexoBadge>
                </div>
                <p class="text-xs text-slate-500">Meta presupuestal: > 90%</p>
            </div>

            <!-- Total Cartera Facturada -->
            <div class="p-5 rounded-2xl border border-slate-200/90 dark:border-slate-800 bg-white dark:bg-slate-900 space-y-2">
                <span class="text-xs font-semibold uppercase text-slate-500 tracking-wider">Total Facturado</span>
                <span class="block text-2xl font-black text-slate-900 dark:text-white tracking-tight tabular-nums truncate">
                    {{ formatCurrency(carteraTotal) }}
                </span>
                <p class="text-xs text-slate-500">Cuotas de administración</p>
            </div>

            <!-- Cartera en Mora -->
            <div class="p-5 rounded-2xl border border-slate-200/90 dark:border-slate-800 bg-white dark:bg-slate-900 space-y-2">
                <span class="text-xs font-semibold uppercase text-slate-500 tracking-wider">Mora Pendiente</span>
                <div class="flex items-baseline justify-between">
                    <span class="text-2xl font-black text-red-600 dark:text-red-400 tracking-tight tabular-nums truncate">
                        {{ formatCurrency(carteraMora) }}
                    </span>
                    <NexoBadge variant="error" size="sm">Gestión</NexoBadge>
                </div>
                <p class="text-xs text-slate-500">Intereses de mora aplicados</p>
            </div>

            <!-- PQRS y Soporte Abierto -->
            <div class="p-5 rounded-2xl border border-slate-200/90 dark:border-slate-800 bg-white dark:bg-slate-900 space-y-2">
                <span class="text-xs font-semibold uppercase text-slate-500 tracking-wider">PQRS Pendientes</span>
                <div class="flex items-baseline justify-between">
                    <span class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">
                        {{ openPqrsCount }}
                    </span>
                    <NexoBadge variant="warning" size="sm">Abiertas</NexoBadge>
                </div>
                <p class="text-xs text-slate-500">Tiempo de respuesta: &lt; 24h</p>
            </div>
        </div>
    </div>
</template>

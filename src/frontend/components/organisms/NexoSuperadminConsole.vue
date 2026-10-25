<script setup lang="ts">
/**
 * NexoSuperadminConsole.vue
 *
 * [LÓGICA DE NEGOCIO / UX]
 * Organismo de mando y observabilidad para el Superadministrador de NEXO PRO.
 * - Permite vigilar en tiempo real la salud de toda la infraestructura multi-inquilino.
 * - Centraliza el control de licencias comerciales activas, expiraciones y conmutación de tenants.
 * - Facilita el análisis forense de incidentes con bitácora de auditoría inmutable (IPs, eventos y timestamps).
 *
 * [ASPECTO TÉCNICO / UI]
 * - Interfaz de alta densidad visual (compact density) con tipografía monospaced para identificadores UUID y logs.
 * - Paleta adaptativa con soporte Modo Claro (Slate-100 / Blanco) y Modo Oscuro (Slate-950 / Slate-900).
 * - Componentes atómicos integrados: NexoDataPill y NexoEnvironmentBadge.
 */

import NexoEnvironmentBadge from '../atoms/NexoEnvironmentBadge.vue';
import NexoDataPill from '../atoms/NexoDataPill.vue';

interface SystemHealth {
    database: 'online' | 'degraded' | 'offline';
    reverb: 'online' | 'degraded' | 'offline';
    livekit: 'online' | 'degraded' | 'offline';
    workers: 'online' | 'degraded' | 'offline';
}

interface AuditLogEntry {
    id: string;
    timestamp: string;
    level: 'info' | 'warn' | 'error';
    message: string;
    ip: string;
    tenantId: string;
}

withDefaults(
    defineProps<{
        totalProperties?: number;
        activeLicenses?: number;
        systemHealth?: SystemHealth;
        recentLogs?: AuditLogEntry[];
    }>(),
    {
        totalProperties: 124,
        activeLicenses: 119,
        systemHealth: () => ({
            database: 'online',
            reverb: 'online',
            livekit: 'online',
            workers: 'online',
        }),
        recentLogs: () => [
            { id: 'ev-1', timestamp: '12:04:15', level: 'info', message: 'Licencia renovada para Conjunto Altos del Parque', ip: '190.158.24.12', tenantId: 'torres-parque' },
            { id: 'ev-2', timestamp: '12:03:50', level: 'info', message: 'Broadcast Reverb emitido: asamblea.a1b2c3d4', ip: '127.0.0.1', tenantId: 'as-a1b2c3d4' },
            { id: 'ev-3', timestamp: '12:01:22', level: 'warn', message: 'Conexión lenta detectada en nodo RTC LiveKit', ip: '186.155.10.88', tenantId: 'global' },
        ],
    }
);

const emit = defineEmits<{
    (e: 'purge-logs'): void;
    (e: 'manage-licenses'): void;
}>();
</script>

<template>
    <div class="w-full space-y-6 font-sans text-left">
        <!-- Barra de Control y Telemetría Superior -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 p-4 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-xs">
            <div class="flex items-center gap-3">
                <NexoEnvironmentBadge environment="superadmin" />
                <span class="text-xs font-mono font-bold text-slate-500 dark:text-slate-400">
                    Host: 127.0.0.1:8000 • PHP 8.3 / PGSQL 16
                </span>
            </div>

            <div class="flex items-center gap-2">
                <button
                    type="button"
                    @click="emit('manage-licenses')"
                    class="px-3 py-1.5 rounded-lg text-xs font-bold border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700 text-slate-800 dark:text-slate-200 transition-colors cursor-pointer"
                >
                    Gestionar Licencias
                </button>
                <button
                    type="button"
                    @click="emit('purge-logs')"
                    class="px-3 py-1.5 rounded-lg text-xs font-bold bg-sky-600 hover:bg-sky-700 text-white transition-colors cursor-pointer shadow-xs"
                >
                    Purgar Caché de Logs
                </button>
            </div>
        </div>

        <!-- Rejilla de Observabilidad de Infraestructura (4 Nodos Clave) -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
            <!-- PostgreSQL -->
            <div class="p-3.5 rounded-xl border border-slate-200/90 dark:border-slate-800 bg-white dark:bg-slate-900/90">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500">PostgreSQL 16</span>
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                </div>
                <div class="mt-2 flex items-baseline justify-between">
                    <span class="text-lg font-mono font-extrabold text-slate-900 dark:text-white">DB_NEXOPRO</span>
                    <NexoDataPill value="Active" variant="success" />
                </div>
            </div>

            <!-- Reverb WebSockets -->
            <div class="p-3.5 rounded-xl border border-slate-200/90 dark:border-slate-800 bg-white dark:bg-slate-900/90">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Reverb WebSockets</span>
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                </div>
                <div class="mt-2 flex items-baseline justify-between">
                    <span class="text-lg font-mono font-extrabold text-slate-900 dark:text-white">Port 8080</span>
                    <NexoDataPill value="0.0.0.0" variant="info" mono />
                </div>
            </div>

            <!-- LiveKit RTC -->
            <div class="p-3.5 rounded-xl border border-slate-200/90 dark:border-slate-800 bg-white dark:bg-slate-900/90">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500">LiveKit RTC</span>
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                </div>
                <div class="mt-2 flex items-baseline justify-between">
                    <span class="text-lg font-mono font-extrabold text-slate-900 dark:text-white">Port 7880</span>
                    <NexoDataPill value="P2P Mesh" variant="success" />
                </div>
            </div>

            <!-- Queue Workers -->
            <div class="p-3.5 rounded-xl border border-slate-200/90 dark:border-slate-800 bg-white dark:bg-slate-900/90">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Worker Daemon</span>
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                </div>
                <div class="mt-2 flex items-baseline justify-between">
                    <span class="text-lg font-mono font-extrabold text-slate-900 dark:text-white">Queue: default</span>
                    <NexoDataPill value="0 Jobs" variant="neutral" />
                </div>
            </div>
        </div>

        <!-- Consola Densa de Bitácora Forense (Audit Stream) -->
        <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 overflow-hidden shadow-xs">
            <div class="p-4 border-b border-slate-200 dark:border-slate-800 flex justify-between items-center bg-slate-50/50 dark:bg-slate-800/40">
                <div class="flex items-center gap-2">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-800 dark:text-slate-200">
                        Flujo de Auditoría del Sistema (Realtime GIN Index)
                    </h3>
                    <span class="px-2 py-0.5 rounded-md text-[10px] font-mono bg-sky-100 dark:bg-sky-950/80 text-sky-800 dark:text-sky-300 font-bold">
                        Live Stream
                    </span>
                </div>
                <span class="text-xs font-mono text-slate-400">Total Tenants: {{ totalProperties }}</span>
            </div>

            <div class="divide-y divide-slate-100 dark:divide-slate-800/80 font-mono text-xs">
                <div
                    v-for="log in recentLogs"
                    :key="log.id"
                    class="p-3 sm:px-4 sm:py-2.5 flex flex-col sm:flex-row sm:items-center justify-between gap-2 hover:bg-slate-50 dark:hover:bg-slate-800/40 transition-colors"
                >
                    <div class="flex items-center gap-2.5 min-w-0">
                        <span class="text-slate-400 shrink-0 text-[11px]">[{{ log.timestamp }}]</span>
                        <NexoDataPill
                            :value="log.level.toUpperCase()"
                            :variant="log.level === 'error' ? 'danger' : (log.level === 'warn' ? 'warning' : 'info')"
                        />
                        <span class="text-slate-800 dark:text-slate-200 font-sans truncate">
                            {{ log.message }}
                        </span>
                    </div>

                    <div class="flex items-center gap-2 shrink-0 self-end sm:self-auto text-[11px]">
                        <span class="text-slate-400">IP: {{ log.ip }}</span>
                        <span class="px-2 py-0.5 rounded bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 text-[10px]">
                            {{ log.tenantId }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

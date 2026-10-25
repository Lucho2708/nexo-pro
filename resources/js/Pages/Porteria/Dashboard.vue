<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import SecurityLayout from '@/Layouts/SecurityLayout.vue';
import Card from '@/Components/UI/Card.vue';

defineOptions({ layout: SecurityLayout });

defineProps<{
    metrics: {
        visitas_activas: number;
        paquetes_pendientes: number;
        recibos_en_casillero: number;
        parqueaderos: {
            capacidad_total: number;
            ocupados: number;
            disponibles: number;
            porcentaje_ocupacion: number;
            alerta_sobrecupo: boolean;
        };
    };
    copropiedad: any;
}>();
</script>

<template>
    <Head title="Consola de Garita • Portería" />

    <div class="space-y-8">
        <!-- Status & Welcome Banner -->
        <div class="bg-gradient-to-r from-primary/10 via-indigo-500/10 to-transparent p-6 md:p-8 rounded-[2.5rem] border border-primary/20 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <span class="text-[10px] font-black uppercase tracking-[0.25em] text-primary">Estación de Control Táctico</span>
                <h2 class="text-2xl md:text-3xl font-black uppercase tracking-tight text-on-surface dark:text-white mt-1">
                    Panel Principal de Garita
                </h2>
                <p class="text-xs text-on-surface-variant font-medium mt-1">
                    Supervisión en tiempo real de accesos, correspondencia y parqueaderos para {{ copropiedad?.nombre }}.
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <Link
                    :href="route('porteria.visitas.index')"
                    class="min-h-[48px] px-5 py-3 rounded-2xl bg-primary text-white font-black text-xs uppercase tracking-wider shadow-lg shadow-primary/25 hover:scale-[1.02] active:scale-95 transition-all flex items-center gap-2"
                >
                    <span class="material-symbols-rounded">person_add</span>
                    Registrar Visita
                </Link>
                <Link
                    :href="route('porteria.paquetes.index')"
                    class="min-h-[48px] px-5 py-3 rounded-2xl bg-surface-container-high hover:bg-surface-container text-on-surface font-black text-xs uppercase tracking-wider border border-outline-variant/15 transition-all flex items-center gap-2"
                >
                    <span class="material-symbols-rounded">inventory_2</span>
                    Recibir Paquete
                </Link>
            </div>
        </div>

        <!-- Metric Grid (Touch Ergonomics >= 56px) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Visitas Activas -->
            <Link :href="route('porteria.visitas.index')" class="block group">
                <Card class="p-6 transition-all group-hover:border-primary/40 group-hover:shadow-lg">
                    <div class="flex items-center justify-between">
                        <span class="w-12 h-12 rounded-2xl bg-primary/10 text-primary flex items-center justify-center">
                            <span class="material-symbols-rounded text-2xl">groups</span>
                        </span>
                        <span class="text-[10px] font-black text-primary uppercase tracking-widest">En Conjunto</span>
                    </div>
                    <div class="mt-4">
                        <p class="text-3xl md:text-4xl font-black text-on-surface dark:text-white tracking-tight">
                            {{ metrics.visitas_activas }}
                        </p>
                        <p class="text-xs font-bold text-on-surface-variant uppercase tracking-wider mt-1">
                            Visitas Activas
                        </p>
                    </div>
                </Card>
            </Link>

            <!-- Paquetes Pendientes -->
            <Link :href="route('porteria.paquetes.index')" class="block group">
                <Card class="p-6 transition-all group-hover:border-amber-500/40 group-hover:shadow-lg">
                    <div class="flex items-center justify-between">
                        <span class="w-12 h-12 rounded-2xl bg-amber-500/10 text-amber-500 flex items-center justify-center">
                            <span class="material-symbols-rounded text-2xl">package_2</span>
                        </span>
                        <span class="text-[10px] font-black text-amber-500 uppercase tracking-widest">Por Entregar</span>
                    </div>
                    <div class="mt-4">
                        <p class="text-3xl md:text-4xl font-black text-on-surface dark:text-white tracking-tight">
                            {{ metrics.paquetes_pendientes }}
                        </p>
                        <p class="text-xs font-bold text-on-surface-variant uppercase tracking-wider mt-1">
                            Paquetes en Garita
                        </p>
                    </div>
                </Card>
            </Link>

            <!-- Recibos en Casillero -->
            <Link :href="route('porteria.recibos.index')" class="block group">
                <Card class="p-6 transition-all group-hover:border-indigo-500/40 group-hover:shadow-lg">
                    <div class="flex items-center justify-between">
                        <span class="w-12 h-12 rounded-2xl bg-indigo-500/10 text-indigo-500 flex items-center justify-center">
                            <span class="material-symbols-rounded text-2xl">receipt_long</span>
                        </span>
                        <span class="text-[10px] font-black text-indigo-500 uppercase tracking-widest">Servicios</span>
                    </div>
                    <div class="mt-4">
                        <p class="text-3xl md:text-4xl font-black text-on-surface dark:text-white tracking-tight">
                            {{ metrics.recibos_en_casillero }}
                        </p>
                        <p class="text-xs font-bold text-on-surface-variant uppercase tracking-wider mt-1">
                            Recibos Públicos
                        </p>
                    </div>
                </Card>
            </Link>

            <!-- Aforo Parqueaderos de Visitantes -->
            <Link :href="route('porteria.parqueaderos.index')" class="block group">
                <Card class="p-6 transition-all group-hover:border-emerald-500/40 group-hover:shadow-lg">
                    <div class="flex items-center justify-between">
                        <span class="w-12 h-12 rounded-2xl bg-emerald-500/10 text-emerald-500 flex items-center justify-center">
                            <span class="material-symbols-rounded text-2xl">local_parking</span>
                        </span>
                        <span 
                            class="text-[10px] font-black uppercase tracking-widest"
                            :class="metrics.parqueaderos.alerta_sobrecupo ? 'text-rose-500 animate-pulse' : 'text-emerald-500'"
                        >
                            {{ metrics.parqueaderos.alerta_sobrecupo ? 'Agotado' : 'Disponible' }}
                        </span>
                    </div>
                    <div class="mt-4">
                        <p class="text-3xl md:text-4xl font-black text-on-surface dark:text-white tracking-tight">
                            {{ metrics.parqueaderos.disponibles }} <span class="text-sm font-bold text-on-surface-variant">/ {{ metrics.parqueaderos.capacidad_total }}</span>
                        </p>
                        <p class="text-xs font-bold text-on-surface-variant uppercase tracking-wider mt-1">
                            Cupos Visitantes Libres
                        </p>
                    </div>
                </Card>
            </Link>
        </div>

        <!-- Parqueaderos Monitor Bar -->
        <Card class="p-6 md:p-8 space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h3 class="text-sm md:text-base font-black uppercase tracking-tight text-on-surface dark:text-white flex items-center gap-2">
                        <span class="material-symbols-rounded text-primary">directions_car</span>
                        Capacidad de Parqueadero de Visitantes
                    </h3>
                    <p class="text-xs text-on-surface-variant mt-0.5">
                        {{ metrics.parqueaderos.ocupados }} bahías ocupadas de {{ metrics.parqueaderos.capacidad_total }} bahías habilitadas.
                    </p>
                </div>
                <div class="text-right">
                    <span class="text-xl font-black text-on-surface dark:text-white">
                        {{ metrics.parqueaderos.porcentaje_ocupacion }}%
                    </span>
                    <span class="text-xs font-bold text-on-surface-variant uppercase ml-1">Ocupación</span>
                </div>
            </div>

            <!-- Visual Progress Bar -->
            <div class="w-full h-4 rounded-full bg-surface-container-high overflow-hidden p-0.5 border border-outline-variant/20">
                <div 
                    class="h-full rounded-full transition-all duration-500"
                    :class="[
                        metrics.parqueaderos.alerta_sobrecupo 
                            ? 'bg-rose-500' 
                            : (metrics.parqueaderos.porcentaje_ocupacion > 75 ? 'bg-amber-500' : 'bg-emerald-500')
                    ]"
                    :style="{ width: `${metrics.parqueaderos.porcentaje_ocupacion}%` }"
                ></div>
            </div>
        </Card>
    </div>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import BackofficeLayout from '@/Layouts/BackofficeLayout.vue';
import Card from '@/Components/UI/Card.vue';

defineOptions({ layout: BackofficeLayout });

const props = defineProps<{
    leads: any[];
    resumen: Record<string, { total_leads: number; total_unidades: number; ahorro_proyectado_total: number }>;
    metricas: { tasa_conversion: number; ganados: number; perdidos: number; activos: number };
}>();

const etapas = [
    { key: 'nuevo', label: 'Nuevos Prospectos', color: 'bg-blue-500' },
    { key: 'contactado', label: 'Contactados', color: 'bg-indigo-500' },
    { key: 'demo_agendada', label: 'Demostración Agendada', color: 'bg-purple-500' },
    { key: 'cotizado', label: 'Cotización Enviada', color: 'bg-amber-500' },
    { key: 'ganado', label: 'Cerrado / Ganado', color: 'bg-emerald-500' },
    { key: 'perdido', label: 'Descartado', color: 'bg-slate-500' },
];

const cambiarEstado = (leadId: string, nuevoEstado: string) => {
    router.patch(route('backoffice.leads.update-estado', leadId), {
        estado: nuevoEstado,
    });
};
</script>

<template>
    <Head title="Pipeline CRM • Backoffice Comercial" />

    <div class="space-y-8">
        <!-- Header & Top Metrics -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <span class="text-[10px] font-black uppercase tracking-[0.25em] text-indigo-500">Adquisición & Expansión PropTech</span>
                <h2 class="text-2xl md:text-3xl font-black uppercase tracking-tight text-on-surface dark:text-white mt-1">
                    Embudo Comercial de Copropiedades
                </h2>
                <p class="text-xs text-on-surface-variant font-medium mt-1">
                    Gestión integral de leads capturados desde la calculadora de ROI y landing page.
                </p>
            </div>

            <!-- Conversion Badge -->
            <div class="flex items-center gap-4 bg-surface-container-high/60 dark:bg-white/5 p-4 rounded-2xl border border-outline-variant/15">
                <div>
                    <span class="text-[9px] font-black uppercase tracking-widest text-on-surface-variant">Tasa de Cierre</span>
                    <p class="text-2xl font-black text-emerald-500 mt-0.5">{{ metricas.tasa_conversion }}%</p>
                </div>
                <div class="border-l border-outline-variant/20 pl-4">
                    <span class="text-[9px] font-black uppercase tracking-widest text-on-surface-variant">Copropiedades Ganadas</span>
                    <p class="text-2xl font-black text-on-surface dark:text-white mt-0.5">{{ metricas.ganados }}</p>
                </div>
            </div>
        </div>

        <!-- Kanban Board Columns -->
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-6 gap-5 overflow-x-auto pb-4">
            <div 
                v-for="etapa in etapas" 
                :key="etapa.key" 
                class="bg-surface-container-low/80 dark:bg-[#0b0e14]/60 rounded-3xl p-4 border border-outline-variant/15 flex flex-col min-w-[280px]"
            >
                <!-- Column Header -->
                <div class="flex items-center justify-between pb-3 border-b border-outline-variant/10 mb-4">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full" :class="etapa.color"></span>
                        <h3 class="text-xs font-black uppercase tracking-wider text-on-surface dark:text-white">
                            {{ etapa.label }}
                        </h3>
                    </div>
                    <span class="px-2 py-0.5 rounded-lg bg-surface-container text-[10px] font-black text-on-surface-variant">
                        {{ resumen[etapa.key]?.total_leads || 0 }}
                    </span>
                </div>

                <!-- Cards list in stage -->
                <div class="space-y-3.5 flex-1 overflow-y-auto max-h-[calc(100vh-280px)] no-scrollbar pr-1">
                    <Card 
                        v-for="lead in leads.filter(l => l.estado === etapa.key)" 
                        :key="lead.id" 
                        class="p-4 rounded-2xl border border-outline-variant/15 shadow-sm space-y-3 hover:border-primary/40 transition-all bg-surface"
                    >
                        <div class="flex items-start justify-between gap-2">
                            <div>
                                <h4 class="text-xs font-black uppercase tracking-tight text-on-surface dark:text-white leading-tight">
                                    {{ lead.copropiedad_nombre || 'Copropiedad' }}
                                </h4>
                                <p class="text-[10px] font-bold text-on-surface-variant mt-0.5">{{ lead.nombre }}</p>
                            </div>
                            <span class="px-2 py-0.5 rounded-md text-[9px] font-black uppercase tracking-wider bg-primary/10 text-primary shrink-0">
                                {{ lead.unidades_totales }} Unids
                            </span>
                        </div>

                        <div class="space-y-1 text-[10px] font-medium text-on-surface-variant border-t border-outline-variant/10 pt-2.5">
                            <p class="flex items-center gap-1.5 truncate">
                                <span class="material-symbols-rounded text-xs text-primary">call</span>
                                {{ lead.telefono }}
                            </p>
                            <p class="flex items-center gap-1.5 truncate">
                                <span class="material-symbols-rounded text-xs text-indigo-500">mail</span>
                                {{ lead.email }}
                            </p>
                        </div>

                        <!-- Action Transitions -->
                        <div class="pt-2 border-t border-outline-variant/10 flex items-center justify-between gap-1">
                            <span class="text-[9px] font-black uppercase tracking-widest text-primary">Score: {{ lead.score }}/100</span>
                            
                            <select 
                                :value="lead.estado" 
                                @change="cambiarEstado(lead.id, ($event.target as HTMLSelectElement).value)"
                                class="text-[9px] font-black uppercase py-1 px-2 rounded-lg bg-surface-container border border-outline-variant/20 text-on-surface focus:outline-none"
                            >
                                <option v-for="e in etapas" :key="e.key" :value="e.key">{{ e.label }}</option>
                            </select>
                        </div>
                    </Card>

                    <div v-if="leads.filter(l => l.estado === etapa.key).length === 0" class="p-8 text-center text-on-surface-variant/40 text-[10px] font-black uppercase tracking-widest">
                        Sin prospectos
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

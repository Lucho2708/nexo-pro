<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, computed, watch } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Card from '@/Components/UI/Card.vue';
import Badge from '@/Components/UI/Badge.vue';
import Button from '@/Components/UI/Button.vue';
import Select from '@/Components/UI/Select.vue';
import DatePicker from '@/Components/UI/DatePicker.vue';
import Pagination from '@/Components/UI/Pagination.vue';
import Modal from '@/Components/UI/Modal.vue';
import Tooltip from '@/Components/UI/Tooltip.vue';
import Table from '@/Components/UI/Table.vue';
import VueApexCharts from 'vue3-apexcharts';

// Definir layout persistente
defineOptions({ layout: AuthenticatedLayout });

const props = defineProps<{
    logs: {
        data: Array<any>;
        links: Array<any>;
        total: number;
        per_page: number;
    };
    features: Array<{value: string, label: string}>;
    copropiedades: Array<{id: number, nombre: string}>;
    filters: any;
    chartData: {
        categories: Array<string>;
        series: Array<any>;
    };
}>();

const form = ref({
    search: props.filters.search || '',
    feature: props.filters.feature || '',
    copropiedad_id: props.filters.copropiedad_id || '',
    date_from: props.filters.date_from || '',
    date_to: props.filters.date_to || '',
});

const tableColumns = [
    { key: 'timestamp', label: 'TIMESTAMP', sortable: true, sortField: 'used_at' },
    { key: 'evento', label: 'EVENTO', sortable: true, sortField: 'action' },
    { key: 'actor', label: 'ACTOR OPERATIVO', sortable: true },
    { key: 'actions', label: 'INSPECCIÓN', sortable: false },
];

const isRefreshing = ref(false);

const debounce = (fn: Function, delay: number) => {
    let timeoutId: ReturnType<typeof setTimeout>;
    return (...args: any[]) => {
        clearTimeout(timeoutId);
        timeoutId = setTimeout(() => fn(...args), delay);
    };
};

const applyFilters = (isManual = false) => {
    if (isManual) isRefreshing.value = true;
    router.get(route('superadmin.audit'), {
        ...form.value
    }, {
        preserveState: true,
        preserveScroll: true,
        replace: true,
        only: ['logs', 'filters', 'chartData'],
        onFinish: () => {
             if (isManual) setTimeout(() => isRefreshing.value = false, 600);
        }
    });
};

const debouncedApply = debounce(() => applyFilters(), 400);
watch(form, () => debouncedApply(), { deep: true });

const exportAudit = () => {
    window.location.href = route('superadmin.audit.export', form.value);
};

// Lógica de Detalle
const showDetailModal = ref(false);
const activeLog = ref<any>(null);

const viewDetail = (log: any) => {
    activeLog.value = log;
    showDetailModal.value = true;
};

const chartOptions = computed(() => ({
    chart: { type: 'area', toolbar: { show: false }, sparkline: { enabled: true } },
    stroke: { curve: 'smooth', width: 2 },
    fill: { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.3, opacityTo: 0.05, stops: [0, 90, 100] } },
    colors: ['#3b82f6']
}));

const featureConfigs: Record<string, any> = {
    dashboard: { icon: 'monitoring', color: 'text-blue-500 bg-blue-500/10', border: 'border-blue-500/20' },
    cartera:   { icon: 'account_balance_wallet', color: 'text-emerald-500 bg-emerald-500/10', border: 'border-emerald-500/20' },
    pqrs:      { icon: 'forum', color: 'text-pink-500 bg-pink-500/10', border: 'border-pink-500/20' },
    reservas:  { icon: 'calendar_today', color: 'text-amber-500 bg-amber-500/10', border: 'border-amber-500/20' },
    pagos:     { icon: 'payments', color: 'text-indigo-500 bg-indigo-500/10', border: 'border-indigo-500/20' },
    seguridad: { icon: 'security', color: 'text-red-500 bg-red-500/10', border: 'border-red-500/20' },
    usuarios:  { icon: 'group', color: 'text-purple-500 bg-purple-500/10', border: 'border-purple-500/20' },
    licencias: { icon: 'verified', color: 'text-primary bg-primary/10', border: 'border-primary/20' },
    configuracion: { icon: 'settings', color: 'text-orange-500 bg-orange-500/10', border: 'border-orange-500/20' },
    comunicacion: { icon: 'campaign', color: 'text-cyan-500 bg-cyan-500/10', border: 'border-cyan-500/20' },
    notificaciones: { icon: 'notifications_active', color: 'text-yellow-500 bg-yellow-500/10', border: 'border-yellow-500/20' },
    conjuntos: { icon: 'business', color: 'text-teal-500 bg-teal-500/10', border: 'border-teal-500/20' },
    transacciones: { icon: 'receipt_long', color: 'text-lime-500 bg-lime-500/10', border: 'border-lime-500/20' },
};

const getFeatureConfig = (feature: string | null | undefined) => {
    if (!feature) return { icon: 'settings_slow_motion', color: 'text-on-surface-variant/40 bg-surface-container', border: 'border-outline-variant/10' };
    const key = feature.toLowerCase();
    return featureConfigs[key] || { icon: 'rocket_launch', color: 'text-primary bg-primary/10', border: 'border-primary/20' };
};

const resetFilters = () => {
    form.value = { search: '', feature: '', copropiedad_id: '', date_from: '', date_to: '' };
};
</script>

<template>
    <Head title="Auditoría Forense — NEXO-PRO" />

    <div class="space-y-8 pb-20 animate-in fade-in duration-700">
        <!-- Header Industrial Adaptativo -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 px-1">
            <div class="flex items-center gap-6">
                <div class="w-14 h-14 rounded-2xl bg-primary flex items-center justify-center shadow-2xl shadow-primary/20">
                    <span class="material-symbols-rounded text-2xl text-white">security_update_good</span>
                </div>
                <div>
                    <h2 class="text-3xl font-black text-primary tracking-tighter uppercase italic leading-none">BITÁCORA <span class="text-on-surface-variant/20 dark:text-white/10 italic">NIVEL 4</span></h2>
                    <div class="flex items-center gap-2 mt-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        <p class="text-[8px] font-black text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.3em]">Monitoreo Persistente — En línea</p>
                    </div>
                </div>
            </div>
            
            <div class="flex items-center gap-2 bg-surface-container-low dark:bg-white/5 p-1.5 rounded-2xl border border-outline-variant/10 dark:border-white/5 shadow-sm">
                <Button variant="ghost" icon="refresh" @click="applyFilters(true)" class="!w-10 !h-10 !p-0 !rounded-xl transition-all dark:!text-white/60" :class="{'rotate-180 opacity-50': isRefreshing}"></Button>
                <div class="h-6 w-px bg-outline-variant/20 dark:bg-white/10 mx-1"></div>
                <Button variant="outline" icon="filter_list_off" @click="resetFilters" class="!border-transparent !text-[9px] uppercase font-black px-4 h-10 dark:!text-white/60">Limpiar</Button>
                <Button variant="primary" icon="description" @click="exportAudit" class="!rounded-xl !h-10 shadow-lg shadow-primary/10 !text-[9px] font-black uppercase px-6">Exportar</Button>
            </div>
        </div>

        <!-- Dashboard de Métricas Adaptativo -->
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            <div class="lg:col-span-3 bg-[#0b0e14] rounded-[2.5rem] p-8 relative overflow-hidden group border border-white/5 shadow-2xl">
                <div class="absolute top-0 right-0 p-6 text-white/5 opacity-40">
                    <span class="material-symbols-rounded text-6xl">insights</span>
                </div>
                <div class="flex items-end justify-between relative z-10">
                    <div>
                        <h4 class="text-[9px] font-black text-primary uppercase tracking-[0.3em] mb-3">Reporte de frecuencia de eventos</h4>
                        <p class="text-4xl font-black text-white tracking-tighter italic">99.9% <span class="text-xs text-white/20 font-bold tracking-[0.2em] uppercase not-italic ml-2 italic">Integridad Total</span></p>
                    </div>
                    <div class="flex flex-col items-end">
                        <span class="text-[7px] font-black text-white/20 uppercase tracking-[0.3em] mb-2">Actividad Consolidada</span>
                        <div class="w-40 h-10">
                             <VueApexCharts type="area" height="100%" :options="chartOptions" :series="chartData.series" />
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-primary rounded-[2.5rem] p-8 text-on-primary shadow-2xl shadow-primary/20 flex flex-col justify-between">
                <div class="flex justify-between items-start">
                    <h4 class="text-[10px] font-black uppercase tracking-[0.3em] opacity-60 text-white">Eventos Validados</h4>
                    <span class="material-symbols-rounded text-2xl opacity-40 text-white">hub</span>
                </div>
                <div class="mt-4">
                    <p class="text-5xl font-black tracking-tighter leading-none text-white">{{ props.logs.total.toLocaleString() }}</p>
                    <p class="text-[8px] font-bold uppercase tracking-widest mt-3 opacity-40 text-white">Registros en el periodo actual</p>
                </div>
            </div>
        </div>

        <!-- Barra de Filtros ADAPTATIVA -->
        <div class="bg-surface-container-low/50 dark:bg-white/[0.02] backdrop-blur-xl border border-outline-variant/10 dark:border-white/5 rounded-[2.5rem] p-8 shadow-xl space-y-8">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                <!-- Búsqueda Universal -->
                <div class="md:col-span-4 space-y-2">
                    <label class="text-[9px] font-black text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.2em] ml-2">Búsqueda de Usuario</label>
                    <div class="relative group">
                        <span class="material-symbols-rounded absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant/40 group-focus-within:text-primary transition-colors text-lg">manage_search</span>
                        <input 
                            v-model="form.search"
                            placeholder="Email o Nombre..."
                            class="w-full h-12 pl-12 pr-4 bg-white dark:bg-white/[0.03] border border-outline-variant/10 dark:border-white/5 rounded-2xl text-xs font-bold text-on-surface dark:text-white focus:ring-4 focus:ring-primary/5 dark:focus:ring-primary/10 outline-none transition-all placeholder:text-on-surface-variant/20 shadow-sm"
                        />
                    </div>
                </div>

                <!-- Filtro por Conjunto -->
                <div class="md:col-span-3 space-y-2">
                    <label class="text-[9px] font-black text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.2em] ml-2">Asociación de Conjunto</label>
                     <Select 
                        v-model="form.copropiedad_id"
                        :options="[{ value: '', label: 'Todas las Copropiedades' }, ...copropiedades.map(c => ({ value: c.id, label: c.nombre.toUpperCase() }))]"
                        class="!h-12 !rounded-2xl !bg-white dark:!bg-white/[0.03] !text-[11px] !font-black dark:!text-white/80 !shadow-sm !border-outline-variant/10 dark:!border-white/5"
                    />
                </div>

                <!-- Ventana de Tiempo -->
                <div class="md:col-span-5 grid grid-cols-2 gap-4 items-end">
                    <div class="space-y-2">
                        <label class="text-[9px] font-black text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.2em] ml-2">Desde (Fecha)</label>
                        <DatePicker v-model="form.date_from" icon="event_available" class="!h-12 !bg-white dark:!bg-white/[0.03] !rounded-2xl !border-outline-variant/10 dark:!border-white/5 dark:!text-white/80" />
                    </div>
                    <div class="space-y-2">
                         <label class="text-[9px] font-black text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.2em] ml-2">Hasta (Fecha)</label>
                        <DatePicker v-model="form.date_to" icon="event_busy" class="!h-12 !bg-white dark:!bg-white/[0.03] !rounded-2xl !border-outline-variant/10 dark:!border-white/5 dark:!text-white/80" />
                    </div>
                </div>
            </div>

            <!-- Chips de Módulo -->
            <div class="flex flex-wrap items-center gap-2 pt-6 border-t border-outline-variant/5 dark:border-white/5">
                <span class="text-[9px] font-black text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.3em] mr-4">Filtro por Categoría:</span>
                <button 
                    @click="form.feature = ''"
                    class="px-5 h-8 rounded-xl text-[9px] font-black uppercase tracking-widest transition-all"
                    :class="form.feature === '' ? 'bg-primary text-white shadow-xl shadow-primary/20' : 'bg-white dark:bg-white/[0.03] text-on-surface-variant/60 dark:text-white/40 border border-outline-variant/10 dark:border-white/5 hover:bg-surface-container dark:hover:bg-white/10 hover:text-primary'"
                >
                    Universal
                </button>
                <button 
                    v-for="feature in features" 
                    :key="feature.value"
                    @click="form.feature = feature.value"
                    class="px-5 h-8 rounded-xl text-[9px] font-black uppercase tracking-widest transition-all flex items-center gap-2"
                    :class="form.feature === feature.value ? 'bg-primary text-white shadow-xl shadow-primary/20 font-black' : 'bg-white dark:bg-white/[0.03] text-on-surface-variant/60 dark:text-white/40 border border-outline-variant/10 dark:border-white/5 hover:bg-surface-container dark:hover:bg-white/10 hover:text-primary'"
                >
                    <span class="material-symbols-rounded text-[14px]">{{ getFeatureConfig(feature.value).icon }}</span>
                    {{ feature.label }}
                </button>
            </div>
        </div>

        <!-- Feed de Auditoria ADAPTATIVO -->
        <Card content-class="!p-0 overflow-hidden !rounded-[2.5rem]">
            <Table :columns="tableColumns" :data="logs.data" :hideLocalSearch="true">
                <template #cell-timestamp="{ row }">
                    <div class="relative flex flex-col py-1">
                        <p class="text-[10px] font-black text-primary uppercase tracking-tighter leading-none italic">{{ row.used_at_human }}</p>
                        <p class="text-[8px] font-bold text-on-surface-variant/30 dark:text-white/10 uppercase mt-2 tracking-widest">{{ new Date(row.used_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'}) }}</p>
                    </div>
                </template>

                <template #cell-evento="{ row }">
                    <div class="flex items-center gap-5 py-1">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 border border-outline-variant/10 dark:border-white/5 transition-all duration-500 bg-primary/5 group-hover:bg-primary group-hover:text-white" :class="[getFeatureConfig(row.feature).color, getFeatureConfig(row.feature).border]">
                            <span class="material-symbols-rounded text-lg leading-none font-variation-settings-fill">{{ getFeatureConfig(row.feature).icon }}</span>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-[8px] font-black text-on-surface-variant/30 dark:text-white/10 uppercase tracking-[0.3em] leading-none mb-1">{{ row.feature }}</span>
                            <span class="text-[11px] font-black text-on-surface dark:text-white uppercase leading-none tracking-tight group-hover:text-primary transition-colors">{{ row.action ? row.action.replace(/_/g, ' ') : 'ACTO_CORE' }}</span>
                        </div>
                    </div>
                </template>

                <template #cell-actor="{ row }">
                    <div class="flex items-center gap-4 py-1">
                        <img :src="row.user?.avatar" class="w-8 h-8 rounded-[1rem] object-cover border border-outline-variant/10 dark:border-white/10 shadow-sm" />
                        <div class="flex flex-col">
                             <div class="flex items-center gap-2">
                                <span class="text-[10px] font-black text-on-surface dark:text-white uppercase tracking-tight">{{ row.user?.name || 'Kernel' }}</span>
                                <Badge v-if="row.copropiedad" variant="neutral" class="!px-2 !py-0.5 !text-[7px] !font-black !bg-primary/5 !text-primary !border-primary/10 tracking-widest uppercase">{{ row.copropiedad.nombre }}</Badge>
                             </div>
                             <p class="text-[8px] font-bold text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.2em] mt-1">{{ row.user?.email || 'AUTH_DRIVEN' }}</p>
                        </div>
                    </div>
                </template>

                <template #cell-actions="{ row }">
                    <div class="flex justify-end pr-4 py-1">
                        <Tooltip text="Inspecionar Payload">
                            <button @click="viewDetail(row)" class="w-10 h-10 rounded-xl bg-surface-container dark:bg-white/5 border border-outline-variant/10 dark:border-white/5 flex items-center justify-center text-on-surface-variant dark:text-white/40 hover:bg-primary dark:hover:bg-primary hover:text-white dark:hover:text-white transition-all shadow-sm active:scale-95">
                                <span class="material-symbols-rounded text-lg font-variation-settings-fill">terminal</span>
                            </button>
                        </Tooltip>
                    </div>
                </template>
            </Table>
        </Card>

        <!-- Modal Forense -->
        <Modal :show="showDetailModal" @close="showDetailModal = false" max-width="2xl">
            <div class="p-10 space-y-10 bg-[#0b0e14] text-white">
                <header class="flex items-start justify-between border-b border-white/5 pb-8">
                    <div class="flex items-center gap-6">
                        <div class="w-16 h-16 rounded-[1.8rem] flex items-center justify-center text-white bg-primary shadow-2xl shadow-primary/40 rotate-12">
                            <span class="material-symbols-rounded text-3xl">terminal</span>
                        </div>
                        <div>
                            <h3 class="text-3xl font-black tracking-tighter uppercase leading-none italic">Análisis <span class="text-primary font-black">Forense</span></h3>
                            <p class="text-[10px] font-bold text-white/40 uppercase tracking-[0.3em] mt-2">Detección de rastro técnico del núcleo</p>
                        </div>
                    </div>
                    <button @click="showDetailModal = false" class="text-white/20 hover:text-white transition-colors">
                        <span class="material-symbols-rounded text-3xl">close</span>
                    </button>
                </header>

                <div v-if="activeLog" class="grid grid-cols-1 md:grid-cols-2 gap-10">
                    <div class="space-y-6">
                        <h5 class="text-[11px] font-black text-primary uppercase tracking-[0.3em]">Metadata Primaria</h5>
                        <div class="space-y-4">
                            <div class="flex justify-between items-center py-3 border-b border-white/5 px-2 hover:bg-white/[0.02]">
                                <span class="text-[10px] font-bold text-white/40 uppercase tracking-widest">ID_EVENT</span>
                                <span class="text-xs font-black text-primary font-mono select-all">#{{ activeLog.id }}</span>
                            </div>
                            <div class="flex justify-between items-center py-3 border-b border-white/5 px-2 hover:bg-white/[0.02]">
                                <span class="text-[10px] font-bold text-white/40 uppercase tracking-widest">IP_ORIGEN</span>
                                <span class="text-xs font-black text-emerald-400 select-all">{{ activeLog.metadata?.ip || 'SECURE_VPN' }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="space-y-6">
                        <h5 class="text-[11px] font-black text-primary uppercase tracking-[0.3em]">Actor Operativo</h5>
                        <div class="bg-white/5 rounded-[2rem] p-6 flex flex-col items-center text-center border border-white/5">
                            <img :src="activeLog.user?.avatar" class="w-16 h-16 rounded-[1.5rem] bg-white/10 border-4 border-white/5 shadow-2xl mb-4" />
                            <p class="text-base font-black text-white uppercase tracking-tight leading-none">{{ activeLog.user?.name || 'Kernel' }}</p>
                            <p class="text-[10px] font-bold text-white/30 uppercase tracking-[0.2em] mt-2 italic">{{ activeLog.user?.email || 'ROOT_ACCESS' }}</p>
                        </div>
                    </div>
                </div>

                <div v-if="activeLog" class="space-y-6">
                     <h5 class="text-[11px] font-black text-primary uppercase tracking-[0.3em]">Payload Final</h5>
                     <div class="bg-[#05070a] rounded-[2.5rem] p-8 border border-white/5 shadow-inner relative group">
                        <pre class="text-emerald-400/90 font-mono text-[11px] leading-relaxed overflow-x-auto whitespace-pre-wrap max-h-48 custom-scrollbar">
{{ JSON.stringify(activeLog.metadata, null, 4) }}
                        </pre>
                     </div>
                </div>

                <div class="flex justify-end pt-4">
                    <button @click="showDetailModal = false" class="px-12 py-4 bg-primary text-white font-black text-[11px] uppercase tracking-[0.3em] rounded-[1.5rem] shadow-2xl shadow-primary/30 hover:scale-105 transition-all outline-none">CERRAR INSPECCIÓN</button>
                </div>
            </div>
        </Modal>
    </div>
</template>

<style scoped>
.material-symbols-rounded {
    font-variation-settings: 'opsz' 24, 'wght' 600, 'FILL' 0;
}
.group:hover .font-variation-settings-fill {
    font-variation-settings: 'FILL' 1;
}
.custom-scrollbar::-webkit-scrollbar {
    width: 6px;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background: rgba(16, 185, 129, 0.2);
    border-radius: 10px;
}
</style>

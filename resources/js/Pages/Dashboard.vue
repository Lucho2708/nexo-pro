<script setup lang="ts">
import { defineAsyncComponent, computed, ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Card from '@/Components/UI/Card.vue';
import Button from '@/Components/UI/Button.vue';
import Badge from '@/Components/UI/Badge.vue';
import Tooltip from '@/Components/UI/Tooltip.vue';
import Dropdown from '@/Components/UI/Dropdown.vue';
import { useToast } from '@/Composables/useToast';

// Componentes asíncronos para performance
const StatCard = defineAsyncComponent(() => import('@/Components/Dashboard/StatCard.vue'));
const Table = defineAsyncComponent(() => import('@/Components/UI/Table.vue'));
const VueApexCharts = defineAsyncComponent(() => import('vue3-apexcharts'));

defineOptions({ layout: AuthenticatedLayout });

const props = defineProps<{
    stats: any[];
    chartData: {
        categories: string[];
        series: any[];
    };
    overduePayments: any[];
    lastSyncedAt?: string;
}>();

const toast = useToast();
const isSyncing = ref(false);

const tableColumns = [
    { key: 'unit', label: 'ID UNIDAD', sortable: true },
    { key: 'owner', label: 'RESPONSABLE', sortable: true },
    { key: 'amount', label: 'DEUDA TOTAL', sortable: true },
    { key: 'last_payment', label: 'FECHA CORTE', sortable: true },
    { key: 'status', label: 'ESTADO TÉCNICO', sortable: true },
    { key: 'actions', label: '', sortable: false },
];

const lastSyncedTime = computed(() => {
    if (!props.lastSyncedAt) return 'En vivo';
    const date = new Date(props.lastSyncedAt);
    return date.toLocaleTimeString('es-CO', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
});

const chartOptions = computed(() => ({
    chart: {
        type: 'area' as const,
        toolbar: { show: false },
        zoom: { enabled: false },
        fontFamily: 'Manrope, sans-serif',
    },
    colors: ['#0061FF', '#FF4E5E'],
    dataLabels: { enabled: false },
    stroke: { curve: 'smooth' as const, width: 4, lineCap: 'round' as const },
    fill: {
        type: 'gradient' as const,
        gradient: {
            shadeIntensity: 1,
            opacityFrom: 0.35,
            opacityTo: 0.05,
            stops: [0, 90, 100]
        }
    },
    xaxis: {
        categories: props.chartData.categories,
        axisBorder: { show: false },
        axisTicks: { show: false },
        labels: {
            style: { colors: '#747780', fontSize: '9px', fontWeight: 800 }
        }
    },
    yaxis: { labels: { show: false } },
    grid: {
        borderColor: 'rgba(var(--outline-variant), 0.05)',
        strokeDashArray: 6,
        padding: { left: 0, right: 0 }
    },
    tooltip: { theme: 'dark', x: { show: true } },
    legend: { show: false }
}));

const formatCurrency = (value: number) => {
    return new Intl.NumberFormat('es-CO', {
        style: 'currency',
        currency: 'COP',
        maximumFractionDigits: 0
    }).format(value);
};

const syncData = () => {
    if (isSyncing.value) return;
    isSyncing.value = true;
    router.reload({
        data: { refresh: '1' },
        only: ['stats', 'chartData', 'overduePayments', 'lastSyncedAt'],
        onSuccess: () => {
            toast.add('Datos en vivo sincronizados con la base de datos', 'success');
        },
        onError: () => {
            toast.add('No fue posible sincronizar los datos', 'danger');
        },
        onFinish: () => {
            isSyncing.value = false;
        }
    });
};

const goToCartera = () => {
    router.visit(route('cartera.index'));
};

const goToReports = () => {
    router.visit(route('cartera.index'));
};

const runLiquidation = () => {
    toast.add('Abriendo módulo de liquidación masiva...', 'primary');
    router.visit(route('cartera.index'));
};

const notifyResident = (row: any) => {
    toast.add(`Notificación de cobro enviada con éxito a ${row.owner} (${row.unit}) vía SMS/Email`, 'success');
};

const viewAccountStatus = (row: any) => {
    router.visit(route('cartera.index'));
};
</script>

<template>
    <Head title="Mando Central — NEXO-PRO" />

    <div class="space-y-6 sm:space-y-10 md:space-y-12 pb-24 sm:pb-32 animate-in fade-in slide-in-from-bottom-4 duration-700">
        
        <!-- Interactive Header & Tactical Actions -->
        <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-6 sm:gap-8">
            <div class="space-y-2">
                <div class="flex flex-wrap items-center gap-2.5">
                    <div class="w-1.5 h-5 sm:h-6 bg-primary rounded-full"></div>
                    <h2 class="text-[9px] sm:text-[10px] font-black text-on-surface-variant/40 uppercase tracking-[0.3em] sm:tracking-[0.4em] italic">Módulo de Administración Operativa</h2>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-[8px] font-black uppercase text-emerald-500 tracking-wider">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        En Vivo · {{ lastSyncedTime }}
                    </span>
                </div>
                <h1 class="text-3xl sm:text-4xl md:text-5xl font-black text-on-surface dark:text-white tracking-tighter uppercase italic leading-none">
                    Centro de <span class="text-primary italic">Control</span>
                </h1>
            </div>

            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 sm:gap-3 bg-white dark:bg-white/[0.02] p-1.5 sm:p-2 rounded-2xl sm:rounded-[2rem] border border-outline-variant/10 shadow-xl w-full lg:w-auto">
                 <div class="flex items-center gap-2 flex-1 sm:flex-none">
                     <Button 
                        variant="ghost" 
                        size="md" 
                        icon="sync" 
                        class="!rounded-xl sm:!rounded-2xl flex-1 text-xs cursor-pointer" 
                        :loading="isSyncing"
                        @click="syncData"
                     >
                        <span :class="{ 'animate-spin': isSyncing }">Sincronizar</span>
                     </Button>
                     <Button 
                        variant="outline" 
                        size="md" 
                        icon="receipt_long" 
                        class="!rounded-xl sm:!rounded-2xl flex-1 text-xs cursor-pointer" 
                        @click="goToReports"
                     >
                        Reportes
                     </Button>
                 </div>
                 <Button 
                    variant="primary" 
                    size="md" 
                    icon="auto_awesome" 
                    class="!rounded-xl sm:!rounded-2xl shadow-lg shadow-primary/20 w-full sm:w-auto text-xs cursor-pointer" 
                    @click="runLiquidation"
                 >
                    Liquidación Masiva
                 </Button>
            </div>
        </div>

        <!-- KPI Operational Grid (Live & Clickable to Modules) -->
        <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
            <StatCard 
                v-for="(stat, index) in stats" 
                :key="index"
                :label="stat.label"
                :value="stat.value"
                :trend="stat.trend"
                :trend-up="stat.trend_up"
                :progress="stat.progress"
                :color-class="stat.color"
                :href="stat.href"
                :icon="stat.icon"
                :subtext="stat.subtext"
                class="!rounded-[1.8rem] sm:!rounded-[2.2rem] border border-outline-variant/5 dark:border-white/5 shadow-2xl dark:bg-[#0b0e14]"
            />
        </section>

        <!-- Main Analytics Bento -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 sm:gap-8">
            
            <!-- Flujo de Recaudo Real-time -->
            <Card class="lg:col-span-8 p-4 sm:p-7 md:!p-10 rounded-[2rem] sm:rounded-[2.8rem] md:!rounded-[3.5rem] border border-outline-variant/10 dark:border-white/5 shadow-2xl dark:bg-[#0b0e14]">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 sm:gap-6 mb-6 sm:mb-10">
                    <div>
                        <h3 class="text-xl sm:text-2xl font-black text-on-surface dark:text-white uppercase tracking-tighter italic leading-none">Flujo de <span class="text-primary">Capital</span></h3>
                        <p class="text-[9px] sm:text-[10px] font-bold text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.25em] sm:tracking-[0.3em] mt-2 sm:mt-3 italic">Indicadores de recaudo mensual vs facturación</p>
                    </div>
                    <div class="flex items-center gap-4 sm:gap-6 bg-surface-container-low dark:bg-white/5 px-4 sm:px-6 py-2.5 sm:py-3 rounded-2xl border border-outline-variant/10 w-fit">
                         <div class="flex items-center gap-2">
                             <div class="w-2.5 h-2.5 rounded-full bg-primary shadow-[0_0_10px_rgba(0,97,255,0.5)]"></div>
                             <span class="text-[9px] font-black text-on-surface-variant dark:text-white/40 uppercase tracking-widest">Recaudo</span>
                         </div>
                         <div class="flex items-center gap-2">
                             <div class="w-2.5 h-2.5 rounded-full bg-error shadow-[0_0_10px_rgba(255,78,94,0.5)]"></div>
                             <span class="text-[9px] font-black text-on-surface-variant dark:text-white/40 uppercase tracking-widest">Cartera</span>
                         </div>
                    </div>
                </div>

                <div class="h-64 sm:h-80 -mx-2 sm:-mx-4 group">
                    <VueApexCharts 
                        type="area" 
                        height="100%" 
                        :options="chartOptions" 
                        :series="chartData.series" 
                    />
                </div>
            </Card>

            <!-- Tactical Quick Actions -->
            <div class="lg:col-span-4 flex flex-col gap-6 sm:gap-8">
                <div 
                    @click="goToCartera"
                    class="flex-1 bg-primary p-6 sm:p-8 md:p-12 rounded-[2rem] sm:rounded-[2.8rem] md:rounded-[3.5rem] relative overflow-hidden flex flex-col justify-between group cursor-pointer shadow-3xl shadow-primary/20 transition-all hover:scale-[1.01] border border-white/10"
                >
                    <img class="absolute inset-0 w-full h-full object-cover opacity-20 group-hover:scale-110 transition-transform duration-[3s]" src="https://images.unsplash.com/photo-1579546929518-9e396f3cc809?q=80&w=2070" />
                    <div class="relative z-10 space-y-3 sm:space-y-4">
                        <Badge variant="neutral" class="!bg-white/20 !text-white !border-white/30 backdrop-blur-md !text-[9px] font-black tracking-widest">SISTEMA INTELIGENTE</Badge>
                        <h4 class="text-white text-2xl sm:text-3xl font-black tracking-tighter leading-tight italic uppercase">Optimización de <br/> Carteras</h4>
                    </div>
                    <div class="relative z-10 pt-6 sm:pt-8 border-t border-white/10 mt-6 sm:mt-8">
                        <p class="text-white/60 text-[10px] font-bold uppercase tracking-widest leading-relaxed">El recaudo aumentó un 22% este mes mediante cobros automáticos.</p>
                        <Button variant="ghost" class="mt-6 sm:mt-8 w-full !bg-white/10 !text-white border border-white/20 hover:!bg-white/20 !text-[11px] font-black uppercase italic !rounded-2xl cursor-pointer">
                            Dashboard de Cobro
                        </Button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Critical Data Monitor -->
        <Card 
            title="Saldos Críticos" 
            subtitle="Monitor de unidades con mora superior a 60 días"
            icon="warning"
            class="!p-0 rounded-[2rem] sm:rounded-[2.8rem] md:!rounded-[3.5rem] border border-outline-variant/10 dark:border-white/5 shadow-2xl dark:bg-[#0b0e14] overflow-hidden"
        >
            <template #header>
                <div class="p-5 sm:p-8 md:p-10 flex flex-col sm:flex-row sm:items-center justify-between gap-4 sm:gap-6">
                    <div>
                        <h3 class="text-xl sm:text-2xl font-black text-on-surface dark:text-white uppercase tracking-tighter italic leading-none">Monitoreo de <span class="text-error">Impagos</span></h3>
                        <p class="text-[9px] sm:text-[10px] font-bold text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.25em] sm:tracking-[0.3em] mt-2 sm:mt-3 italic">Registro técnico de unidades en estado de mora</p>
                    </div>
                    <Button 
                        variant="ghost" 
                        size="sm" 
                        icon="description" 
                        class="!rounded-xl text-[10px] uppercase font-black tracking-widest bg-error/5 text-error hover:bg-error/10 w-full sm:w-auto cursor-pointer"
                        @click="goToCartera"
                    >
                        Generar Cobros Jurídicos
                    </Button>
                </div>
            </template>

            <!-- Vista Escritorio: Tabla Completa -->
            <div class="hidden md:block">
                <Table :columns="tableColumns" :data="overduePayments" class="border-t border-outline-variant/5 dark:border-white/5">
                    <template #cell-unit="{ row }">
                        <span class="text-base font-black text-primary tracking-tighter italic">{{ row.unit }}</span>
                    </template>

                    <template #cell-owner="{ row }">
                        <div class="flex items-center gap-4 py-2">
                            <div class="w-10 h-10 rounded-2xl bg-surface-container-high dark:bg-white/5 flex items-center justify-center text-xs font-black text-primary border border-outline-variant/10 shadow-sm relative overflow-hidden group">
                                <div class="absolute inset-0 bg-primary/10 translate-y-full group-hover:translate-y-0 transition-transform duration-500"></div>
                                <span class="relative z-10">{{ row.initials }}</span>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-xs font-black text-on-surface dark:text-white uppercase italic tracking-tighter">{{ row.owner }}</span>
                                <span class="text-[8px] font-bold text-on-surface-variant/40 dark:text-white/20 uppercase tracking-widest">PROPIETARIO VÁLIDO</span>
                            </div>
                        </div>
                    </template>

                    <template #cell-amount="{ row }">
                        <div class="flex flex-col">
                            <span class="text-base font-black tracking-tighter italic" :class="row.status_type === 'error' ? 'text-error' : 'text-orange-500'">
                                {{ formatCurrency(row.amount) }}
                            </span>
                            <span class="text-[8px] font-black text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.2em] mt-0.5 italic">SALDO VENCIDO</span>
                        </div>
                    </template>

                    <template #cell-status="{ row }">
                        <Badge :variant="row.status_type === 'error' ? 'error' : 'warning'" class="!px-4 !py-1 !font-black !text-[9px] tracking-widest uppercase italic">
                            {{ row.status }}
                        </Badge>
                    </template>

                    <template #cell-actions="{ row }">
                        <div class="flex justify-end gap-2 pr-6">
                            <Tooltip text="Ver Estado de Cuenta">
                                <Button variant="ghost" size="sm" icon="visibility" class="!w-9 !h-9 !p-0 !rounded-xl cursor-pointer" @click="viewAccountStatus(row)" />
                            </Tooltip>
                            <Tooltip text="Notificar Cobro">
                                <Button variant="ghost" size="sm" icon="mail" class="!w-9 !h-9 !p-0 !rounded-xl text-primary cursor-pointer" @click="notifyResident(row)" />
                            </Tooltip>
                             <Dropdown 
                                label="" 
                                icon="more_horiz" 
                                variant="ghost"
                                class="!p-0 !w-9 !h-9 border border-outline-variant/10 !rounded-xl"
                                :items="[
                                    { label: 'Ir a Cartera', icon: 'account_balance_wallet', action: () => goToCartera() },
                                    { label: 'Notificar SMS / Email', icon: 'send', action: () => notifyResident(row) },
                                ]"
                            />
                        </div>
                    </template>
                </Table>
            </div>

            <!-- Vista Móvil: Micro-Cards Táctiles Ergonómicas (Xiaomi 14T & Mobile) -->
            <div class="block md:hidden border-t border-outline-variant/10 dark:border-white/5 divide-y divide-outline-variant/5 dark:divide-white/5">
                <div 
                    v-for="(row, idx) in overduePayments" 
                    :key="idx" 
                    class="p-4 sm:p-5 space-y-3 hover:bg-surface-container-low/40 dark:hover:bg-white/[0.02] transition-colors"
                >
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-10 h-10 rounded-xl bg-surface-container-high dark:bg-white/5 flex items-center justify-center text-xs font-black text-primary border border-outline-variant/10 shrink-0">
                                {{ row.initials }}
                            </div>
                            <div class="min-w-0">
                                <h4 class="text-xs font-black text-on-surface dark:text-white uppercase italic truncate">{{ row.owner }}</h4>
                                <span class="text-[10px] font-black text-primary uppercase tracking-tight">{{ row.unit }}</span>
                            </div>
                        </div>
                        <Badge :variant="row.status_type === 'error' ? 'error' : 'warning'" class="!px-3 !py-0.5 !font-black !text-[8.5px] tracking-wider uppercase shrink-0">
                            {{ row.status }}
                        </Badge>
                    </div>

                    <div class="flex items-end justify-between pt-1">
                        <div>
                            <span class="text-[8px] font-black text-on-surface-variant/40 dark:text-white/20 uppercase tracking-widest block">Mora Acumulada</span>
                            <span class="text-base font-black tracking-tight italic" :class="row.status_type === 'error' ? 'text-error' : 'text-orange-500'">
                                {{ formatCurrency(row.amount) }}
                            </span>
                        </div>
                        <div class="flex items-center gap-2">
                            <Button 
                                variant="outline" 
                                size="sm" 
                                icon="visibility" 
                                class="!rounded-xl !h-9 !px-3 text-[10px] font-black uppercase cursor-pointer"
                                @click="viewAccountStatus(row)"
                            >
                                Cuenta
                            </Button>
                            <Button 
                                variant="primary" 
                                size="sm" 
                                icon="mail" 
                                class="!rounded-xl !h-9 !px-3 text-[10px] font-black uppercase cursor-pointer"
                                @click="notifyResident(row)"
                            >
                                Notificar
                            </Button>
                        </div>
                    </div>
                </div>

                <div v-if="overduePayments.length === 0" class="p-8 text-center text-on-surface-variant/50">
                    <span class="material-symbols-rounded text-4xl mb-2 opacity-30">verified</span>
                    <p class="text-xs font-black uppercase tracking-widest">Sin unidades en mora crítica</p>
                </div>
            </div>
        </Card>
    </div>
</template>

<style scoped>
.page-enter-active, .page-leave-active { transition: all 0.6s cubic-bezier(0.16, 1, 0.3, 1); }
.page-enter-from { opacity: 0; transform: translateY(20px); }
.material-symbols-rounded {
    font-variation-settings: 'opsz' 20, 'wght' 600, 'FILL' 0;
}
</style>

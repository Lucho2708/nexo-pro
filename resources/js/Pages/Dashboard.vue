<script setup lang="ts">
import { defineAsyncComponent, computed } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Card from '@/Components/UI/Card.vue';
import Button from '@/Components/UI/Button.vue';
import Badge from '@/Components/UI/Badge.vue';
import Tooltip from '@/Components/UI/Tooltip.vue';
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
}>();

const toast = useToast();

const tableColumns = [
    { key: 'unit', label: 'ID UNIDAD', sortable: true },
    { key: 'owner', label: 'RESPONSABLE', sortable: true },
    { key: 'amount', label: 'DEUDA TOTAL', sortable: true },
    { key: 'last_payment', label: 'FECHA CORTE', sortable: true },
    { key: 'status', label: 'ESTADO TÉCNICO', sortable: true },
    { key: 'actions', label: '', sortable: false },
];

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

const runLiquidation = () => {
    toast.add('Iniciando ciclo de liquidación masiva...', 'primary');
    setTimeout(() => toast.add('¡Liquidación completada!', 'success'), 2500);
};
</script>

<template>
    <Head title="Mando Central — NEXO-PRO" />

    <div class="space-y-12 pb-32 animate-in fade-in slide-in-from-bottom-4 duration-700">
        
        <!-- Interactive Header & Tactical Actions -->
        <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-8">
            <div class="space-y-2">
                <div class="flex items-center gap-3">
                    <div class="w-1.5 h-6 bg-primary rounded-full"></div>
                    <h2 class="text-[10px] font-black text-on-surface-variant/40 uppercase tracking-[0.4em] italic">Módulo de Administración Operativa</h2>
                </div>
                <h1 class="text-5xl font-black text-on-surface dark:text-white tracking-tighter uppercase italic leading-none">
                    Centro de <span class="text-primary italic">Control</span>
                </h1>
            </div>

            <div class="flex flex-wrap items-center gap-3 bg-white dark:bg-white/[0.02] p-2 rounded-[2rem] border border-outline-variant/10 shadow-xl">
                 <Button variant="ghost" size="md" icon="sync" class="!rounded-2xl" @click="toast.add('Sincronizando con base de datos...', 'primary')">Sincronizar</Button>
                 <Button variant="outline" size="md" icon="receipt_long" class="!rounded-2xl" href="#">Reportes</Button>
                 <Button variant="primary" size="md" icon="auto_awesome" class="!rounded-2xl shadow-lg shadow-primary/20" @click="runLiquidation">Liquidación Masiva</Button>
            </div>
        </div>

        <!-- KPI Operational Grid -->
        <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <StatCard 
                v-for="(stat, index) in stats" 
                :key="index"
                :label="stat.label"
                :value="stat.value"
                :trend="stat.trend"
                :trend-up="stat.trend_up"
                :progress="stat.progress"
                :color-class="stat.color"
                class="!rounded-[2.5rem] border border-outline-variant/5 dark:border-white/5 shadow-2xl dark:bg-[#0b0e14]"
            />
        </section>

        <!-- Main Analytics Bento -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            <!-- Flujo de Recaudo Real-time -->
            <Card class="lg:col-span-8 !p-10 !rounded-[3.5rem] border border-outline-variant/10 dark:border-white/5 shadow-2xl dark:bg-[#0b0e14]">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
                    <div>
                        <h3 class="text-2xl font-black text-on-surface dark:text-white uppercase tracking-tighter italic leading-none">Flujo de <span class="text-primary">Capital</span></h3>
                        <p class="text-[10px] font-bold text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.3em] mt-3 italic">Indicadores de recaudo mensual vs facturación</p>
                    </div>
                    <div class="flex items-center gap-6 bg-surface-container-low dark:bg-white/5 px-6 py-3 rounded-2xl border border-outline-variant/10">
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

                <div class="h-80 -mx-4 group">
                    <VueApexCharts 
                        type="area" 
                        height="100%" 
                        :options="chartOptions" 
                        :series="chartData.series" 
                    />
                </div>
            </Card>

            <!-- Tactical Quick Actions -->
            <div class="lg:col-span-4 flex flex-col gap-8">
                <div class="flex-1 bg-primary p-12 rounded-[3.5rem] relative overflow-hidden flex flex-col justify-between group cursor-pointer shadow-3xl shadow-primary/20 transition-all hover:scale-[1.02] border border-white/10">
                    <img class="absolute inset-0 w-full h-full object-cover opacity-20 group-hover:scale-110 transition-transform duration-[3s]" src="https://images.unsplash.com/photo-1579546929518-9e396f3cc809?q=80&w=2070" />
                    <div class="relative z-10 space-y-4">
                        <Badge variant="neutral" class="!bg-white/20 !text-white !border-white/30 backdrop-blur-md !text-[9px] font-black tracking-widest">SISTEMA INTELIGENTE</Badge>
                        <h4 class="text-white text-3xl font-black tracking-tighter leading-tight italic uppercase">Optimización de <br/> Carteras</h4>
                    </div>
                    <div class="relative z-10 pt-8 border-t border-white/10 mt-8">
                        <p class="text-white/60 text-[10px] font-bold uppercase tracking-widest leading-relaxed">El recaudo aumentó un 22% este mes mediante cobros automáticos.</p>
                        <Button variant="ghost" class="mt-8 w-full !bg-white/10 !text-white border border-white/20 hover:!bg-white/20 !text-[11px] font-black uppercase italic !rounded-2xl">Dashboard de Cobro</Button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Critical Data Monitor (Tables) -->
        <Card 
            title="Saldos Críticos" 
            subtitle="Monitor de unidades con mora superior a 60 días"
            icon="warning"
            class="!p-0 !rounded-[3.5rem] border border-outline-variant/10 dark:border-white/5 shadow-2xl dark:bg-[#0b0e14] overflow-hidden"
        >
            <template #header>
                <div class="p-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div>
                        <h3 class="text-2xl font-black text-on-surface dark:text-white uppercase tracking-tighter italic leading-none">Monitoreo de <span class="text-error">Impagos</span></h3>
                        <p class="text-[10px] font-bold text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.3em] mt-3 italic">Registro técnico de unidades en estado de mora</p>
                    </div>
                    <Button variant="ghost" size="sm" icon="description" class="!rounded-xl text-[10px] uppercase font-black tracking-widest bg-error/5 text-error hover:bg-error/10">Generar Cobros Jurídicos</Button>
                </div>
            </template>

            <Table :columns="tableColumns" :data="overduePayments" class="border-t border-outline-variant/5 dark:border-white/5">
                <template #cell-unit="{ row }">
                    <span class="text-lg font-black text-primary tracking-tighter italic">Lote {{ row.unit }}</span>
                </template>

                <template #cell-owner="{ row }">
                    <div class="flex items-center gap-4 py-2">
                        <div class="w-12 h-12 rounded-2xl bg-surface-container-high dark:bg-white/5 flex items-center justify-center text-xs font-black text-primary border border-outline-variant/10 shadow-sm relative overflow-hidden group">
                            <div class="absolute inset-0 bg-primary/10 translate-y-full group-hover:translate-y-0 transition-transform duration-500"></div>
                            <span class="relative z-10">{{ row.initials }}</span>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-xs font-black text-on-surface dark:text-white uppercase italic tracking-tighter">{{ row.owner }}</span>
                            <span class="text-[9px] font-bold text-on-surface-variant/40 dark:text-white/20 uppercase tracking-widest">PROPIETARIO VÁLIDO</span>
                        </div>
                    </div>
                </template>

                <template #cell-amount="{ row }">
                    <div class="flex flex-col">
                        <span class="text-lg font-black tracking-tighter italic" :class="row.status_type === 'error' ? 'text-error' : 'text-orange-500'">
                            {{ formatCurrency(row.amount) }}
                        </span>
                        <span class="text-[8px] font-black text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.2em] mt-1 italic">SALDO VENCIDO</span>
                    </div>
                </template>

                <template #cell-status="{ row }">
                    <Badge :variant="row.status_type === 'error' ? 'error' : 'warning'" class="!px-5 !py-1 !font-black !text-[10px] tracking-widest uppercase italic">
                        {{ row.status }}
                    </Badge>
                </template>

                <template #cell-actions="{ row }">
                    <div class="flex justify-end gap-2 pr-6">
                        <Tooltip text="Ver Estado de Cuenta">
                            <Button variant="ghost" size="sm" icon="visibility" class="!w-10 !h-10 !p-0 !rounded-xl" @click="toast.add('Cargando hoja de vida...', 'primary')" />
                        </Tooltip>
                        <Tooltip text="Notificar Cobro">
                            <Button variant="ghost" size="sm" icon="mail" class="!w-10 !h-10 !p-0 !rounded-xl text-primary" @click="toast.add('Notificación enviada vía Email/SMS', 'success')" />
                        </Tooltip>
                         <Dropdown 
                            label="" 
                            icon="more_horiz" 
                            variant="ghost"
                            class="!p-0 !w-10 !h-10 border border-outline-variant/10 !rounded-xl"
                            :items="[
                                { label: 'Editar Propietario', icon: 'manage_accounts', action: () => {} },
                                { label: 'Excluir de mora', icon: 'verified', action: () => {} },
                            ]"
                        />
                    </div>
                </template>
            </Table>
        </Card>
    </div>
</template>

<style scoped>
.page-enter-active, .page-leave-active { transition: all 0.6s cubic-bezier(0.16, 1, 0.3, 1); }
.page-enter-from { opacity: 0; transform: translateY(20px); }
</style>

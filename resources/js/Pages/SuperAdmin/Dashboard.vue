<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Card from '@/Components/UI/Card.vue';
import Button from '@/Components/UI/Button.vue';
import Badge from '@/Components/UI/Badge.vue';
import StatCard from '@/Components/Dashboard/StatCard.vue';
import { useToast } from '@/Composables/useToast';
import { defineAsyncComponent } from 'vue';

const VueApexCharts = defineAsyncComponent(() => import('vue3-apexcharts'));

// Definir layout persistente
defineOptions({ layout: AuthenticatedLayout });

const props = defineProps<{
    metrics: {
        total_users: number;
        user_growth: number;
        active_24h: number;
        total_conjuntos: number;
        license_stats: {
            active: number;
            suspended: number;
            trial: number;
            expiring: number;
        };
        module_ranking: Array<{ feature: string; total: number }>;
        recent_audit: Array<any>;
        telemetry: Array<{ time: string; latency: number; requests: number; criticals: number; controlled: number }>;
        system_health: {
            uptime: string;
            latency: string;
            storage: string;
            errors: number;
        };
    };
}>();

const toast = useToast();

const chartOptions = {
    chart: {
        type: 'area',
        height: 350,
        toolbar: { show: false },
        animations: { enabled: true, easing: 'easeinout', speed: 800 },
        background: 'transparent',
    },
    colors: ['#00173c', '#3b82f6', '#ef4444', '#f59e0b'],
    dataLabels: { enabled: false },
    stroke: { curve: 'smooth', width: [3, 2, 2, 1], dashArray: [0, 5, 0, 0] },
    fill: {
        type: 'gradient',
        gradient: {
            shadeIntensity: 1,
            opacityFrom: 0.45,
            opacityTo: 0.05,
            stops: [20, 100, 100, 100]
        }
    },
    grid: { show: false },
    xaxis: {
        categories: props.metrics.telemetry.map(t => t.time),
        labels: { show: false },
        axisBorder: { show: false },
        axisTicks: { show: false },
    },
    yaxis: { show: false },
    tooltip: {
        theme: 'dark',
        x: { show: true },
        y: { formatter: (val: number) => val.toFixed(0) }
    },
    legend: { show: false }
};

const chartSeries = [
    {
        name: 'Latencia (ms)',
        data: props.metrics.telemetry.map(t => t.latency)
    },
    {
        name: 'Carga (Req/s)',
        data: props.metrics.telemetry.map(t => t.requests / 10)
    },
    {
        name: 'Críticos (500)',
        data: props.metrics.telemetry.map(t => t.criticals)
    },
    {
        name: 'Controlados',
        data: props.metrics.telemetry.map(t => t.controlled)
    }
];

const featureIcons: Record<string, any> = {
    dashboard: { icon: 'monitoring', color: 'text-blue-500 bg-blue-500/10' },
    cartera:   { icon: 'account_balance_wallet', color: 'text-emerald-500 bg-emerald-500/10' },
    pqrs:      { icon: 'forum', color: 'text-pink-500 bg-pink-500/10' },
    reservas:  { icon: 'calendar_today', color: 'text-amber-500 bg-amber-500/10' },
    pagos:     { icon: 'payments', color: 'text-indigo-500 bg-indigo-500/10' },
};
</script>

<template>
    <Head title="Mando Central — NEXO-PRO" />

    <div class="space-y-10 pb-20 animate-in fade-in duration-700">
        <!-- Header Estratégico -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 px-1">
            <div>
                <h2 class="text-4xl font-black text-primary tracking-tighter uppercase leading-tight">Mando Central</h2>
                <div class="flex items-center gap-3 mt-2">
                    <div class="flex items-center gap-1.5 px-3 py-1 bg-emerald-500/10 rounded-full">
                        <div class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></div>
                        <span class="text-[9px] font-black text-emerald-600 uppercase tracking-widest">Plataforma Normal</span>
                    </div>
                    <p class="text-[10px] font-bold text-on-surface-variant/40 uppercase tracking-[0.2em]">Inteligencia y monitoreo global</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <Button variant="ghost" icon="history" @click="router.get(route('superadmin.audit'))">Auditoría</Button>
                <Button variant="primary" icon="add_business" :href="route('register')" class="shadow-brand/25 shadow-xl">Nuevo Conjunto</Button>
            </div>
        </div>

        <!-- KPIs Principales -->
        <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <StatCard 
                label="Usuarios Activos 24h" 
                :value="metrics.active_24h.toString()" 
                subtext="Impacto real del día"
                icon="bolt"
                color-class="bg-brand-gradient text-white"
            />
            <StatCard 
                label="Cuentas Totales" 
                :value="metrics.total_users.toString()" 
                :trend="`+${metrics.user_growth}%`"
                :trend-up="metrics.user_growth >= 0"
                subtext="Crecimiento neto"
                icon="group"
            />
            <StatCard 
                label="Copropiedades" 
                :value="metrics.total_conjuntos.toString()" 
                subtext="Licencias activas"
                icon="business"
                color-class="bg-secondary text-white"
            />
            
            <div class="bg-surface-container-highest/50 backdrop-blur-md p-8 rounded-[2.5rem] border border-outline-variant/10 flex flex-col justify-between group overflow-hidden relative shadow-sm">
                <div class="absolute -right-4 -top-4 w-20 h-20 bg-primary/5 rounded-full group-hover:scale-150 transition-transform duration-700"></div>
                <div class="relative z-10 space-y-4">
                    <p class="text-[9px] font-black uppercase tracking-[0.2em] text-on-surface-variant/40 leading-none">Salud de Red</p>
                    <div class="grid grid-cols-2 gap-4 mt-2">
                        <div>
                            <p class="text-[10px] font-black text-primary/40 uppercase">Uptime</p>
                            <p class="text-sm font-black text-primary tracking-tight">{{ metrics.system_health.uptime }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-primary/40 uppercase">Ping</p>
                            <p class="text-sm font-black text-primary tracking-tight">{{ metrics.system_health.latency }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Gráfica de Telemetría Global con ApexCharts -->
        <div class="bg-surface-container-low border border-outline-variant/10 rounded-[3rem] p-10 relative overflow-hidden group shadow-sm">
            <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 relative z-10 gap-6">
                <div>
                    <h3 class="text-lg font-black text-primary uppercase tracking-tighter">Performance de Infraestructura</h3>
                    <p class="text-[10px] font-bold text-on-surface-variant/40 uppercase tracking-[0.2em] mt-1">Telemetría de las últimas 12 horas</p>
                </div>
                <div class="flex flex-wrap items-center gap-x-6 gap-y-2">
                    <div class="flex items-center gap-2">
                        <div class="w-2 h-2 rounded bg-[#00173c]"></div>
                        <span class="text-[9px] font-black text-on-surface-variant uppercase tracking-widest">Latencia</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-2 h-2 rounded border border-dashed border-blue-500"></div>
                        <span class="text-[9px] font-black text-on-surface-variant uppercase tracking-widest">Carga</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-2 h-2 rounded bg-error shadow-[0_0_8px_rgba(239,68,68,0.4)]"></div>
                        <span class="text-[9px] font-black text-error uppercase tracking-widest">Críticos</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-2 h-2 rounded bg-amber-500"></div>
                        <span class="text-[9px] font-black text-amber-600 uppercase tracking-widest">Controlados</span>
                    </div>
                </div>
            </div>
            
            <div class="h-72 relative z-10">
                <VueApexCharts 
                    width="100%" 
                    height="100%" 
                    :options="chartOptions" 
                    :series="chartSeries"
                />
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <div class="lg:col-span-8 space-y-8">
                <div class="grid md:grid-cols-4 gap-4">
                    <div v-for="(val, key) in metrics.license_stats" :key="key" class="bg-surface-container-low p-6 rounded-3xl border border-outline-variant/5 flex flex-col gap-1 transition-all hover:border-primary/20 shadow-sm">
                        <p class="text-[9px] font-black text-on-surface-variant/40 uppercase tracking-widest">{{ key === 'expiring' ? 'Vencimientos' : key }}</p>
                        <div class="flex items-end justify-between">
                            <p class="text-2xl font-black text-primary leading-none">{{ val }}</p>
                            <div :class="[
                                'w-2 h-2 rounded-full',
                                key === 'active' ? 'bg-emerald-500' : key === 'expiring' ? 'bg-amber-500 animate-pulse' : key === 'suspended' ? 'bg-error' : 'bg-primary'
                            ]"></div>
                        </div>
                    </div>
                </div>

                <Card title="Auditoría de Actividad" subtitle="Registro transaccional de la plataforma" icon="list_alt">
                    <div class="mt-4 overflow-hidden border border-outline-variant/5 rounded-2xl">
                        <div v-for="(log, idx) in metrics.recent_audit" :key="log.id" 
                            class="flex items-center gap-5 p-5 transition-colors border-b border-outline-variant/5 last:border-none hover:bg-primary/[0.02]"
                        >
                            <div class="w-11 h-11 rounded-2xl flex items-center justify-center shrink-0 shadow-sm" :class="featureIcons[log.feature]?.color || 'bg-surface-container-high text-on-surface-variant'">
                                <span class="material-symbols-rounded text-xl">{{ featureIcons[log.feature]?.icon || 'rocket_launch' }}</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2">
                                    <span class="text-xs font-black text-primary uppercase tracking-tight">{{ log.user?.name }}</span>
                                    <span class="text-[9px] font-bold text-on-surface-variant/30 uppercase tracking-tighter">en</span>
                                    <span class="text-[10px] font-black text-on-surface uppercase">{{ log.copropiedad?.nombre }}</span>
                                </div>
                                <p class="text-[11px] font-medium text-on-surface-variant mt-0.5">Ejecutó acción en módulo <span class="uppercase font-bold">{{ log.feature }}</span></p>
                            </div>
                            <div class="text-right shrink-0">
                                <p class="text-[9px] font-black text-on-surface-variant/40 uppercase tracking-widest">{{ log.used_at_human || 'Ahora' }}</p>
                            </div>
                        </div>
                    </div>
                    <template #header>
                        <Button variant="ghost" size="sm" @click="router.get(route('superadmin.audit'))" class="!text-[10px] font-black uppercase">Ver Bitácora</Button>
                    </template>
                </Card>
            </div>

            <div class="lg:col-span-4 space-y-8">
                <Card title="Impacto Técnico" subtitle="Demanda de recursos por módulo" icon="analytics">
                    <div class="space-y-6 mt-6">
                        <div v-for="(item, index) in metrics.module_ranking" :key="item.feature" class="space-y-2">
                            <div class="flex justify-between items-center px-1">
                                <div class="flex items-center gap-3">
                                    <span class="material-symbols-rounded text-[16px] text-primary">{{ featureIcons[item.feature]?.icon || 'stars' }}</span>
                                    <p class="text-[10px] font-black text-on-surface uppercase tracking-widest">{{ item.feature }}</p>
                                </div>
                                <Badge variant="secondary" class="!text-[9px] font-black tracking-tighter">{{ item.total }} <span class="ml-1 opacity-50">OPS</span></Badge>
                            </div>
                            <div class="h-2 w-full bg-surface-container-low rounded-full overflow-hidden p-[2px] border border-outline-variant/10 shadow-inner">
                                <div 
                                    class="h-full bg-brand-gradient rounded-full transition-all duration-1000"
                                    :style="`width: ${Math.min(100, (item.total / (metrics.module_ranking[0]?.total || 1)) * 100)}%`"
                                ></div>
                            </div>
                        </div>
                    </div>
                </Card>

                <div class="premium-elevated rounded-[2.5rem] p-8 border border-primary/5 bg-gradient-to-br from-surface-container-low to-surface-container-highest shadow-sm">
                    <h4 class="text-xs font-black text-primary uppercase tracking-[0.2em] mb-6 flex items-center gap-2">
                        <span class="material-symbols-rounded text-sm">terminal</span>
                        Estadísticas de Servidor
                    </h4>
                    <div class="space-y-5">
                        <div class="flex items-center justify-between">
                            <span class="text-[10px] font-bold text-on-surface-variant/60 uppercase">Uso de Almacenamiento</span>
                            <span class="text-xs font-black text-primary">{{ metrics.system_health.storage }}</span>
                        </div>
                        <div class="h-1.5 w-full bg-primary/5 rounded-full overflow-hidden">
                            <div class="h-full bg-primary rounded-full" :style="`width: ${metrics.system_health.storage}`"></div>
                        </div>
                        
                        <div class="flex items-center justify-between pt-2">
                            <span class="text-[10px] font-bold text-on-surface-variant/60 uppercase">Errores de Sistema (24h)</span>
                            <Badge :variant="metrics.system_health.errors === 0 ? 'success' : 'error'" class="!text-[8px]">{{ metrics.system_health.errors }} DETECTADOS</Badge>
                        </div>
                    </div>
                    
                    <div class="mt-8 grid grid-cols-2 gap-3">
                        <Button variant="outline" size="sm" class="!rounded-2xl !text-[9px] uppercase font-black" @click="toast.add('Backup programado', 'primary')">Backup</Button>
                        <Button variant="outline" size="sm" class="!rounded-2xl !text-[9px] uppercase font-black" @click="toast.add('Caché limpia', 'success')">Clear Cache</Button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.material-symbols-rounded {
    font-variation-settings: 'opsz' 24, 'wght' 400, 'FILL' 1;
}
.bg-brand-gradient {
    background: linear-gradient(135deg, var(--color-primary) 0%, #3b82f6 100%);
}
</style>

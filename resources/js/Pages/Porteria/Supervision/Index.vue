<script setup lang="ts">
import { ref, computed } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import SecurityLayout from '@/Layouts/SecurityLayout.vue';
import Card from '@/Components/UI/Card.vue';
import Modal from '@/Components/UI/Modal.vue';

defineOptions({ layout: SecurityLayout });

interface PuestoItem {
    id: string;
    nombre: string;
    ciudad: string;
    direccion: string;
    estado_semaforo: 'NORMAL' | 'ADVERTENCIA' | 'CRITICO';
    novedades_urgentes_24h: number;
    ultimo_relevo_at: string | null;
    ultima_minuta: {
        id: string;
        tipo_evento: string;
        prioridad: string;
        descripcion: string;
        hash_inmutable: string;
        created_at: string;
    } | null;
}

interface DashboardB2B {
    kpis: {
        total_puestos: number;
        puestos_operativos: number;
        puestos_alerta: number;
        alertas_criticas: number;
    };
    puestos: PuestoItem[];
}

const props = defineProps<{
    dashboard: DashboardB2B;
}>();

const searchQuery = ref('');
const filterStatus = ref<'TODOS' | 'CRITICO' | 'ADVERTENCIA' | 'NORMAL'>('TODOS');
const selectedPuesto = ref<PuestoItem | null>(null);

const puestosFiltrados = computed(() => {
    return props.dashboard.puestos.filter((puesto) => {
        const matchesSearch = puesto.nombre.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
                              puesto.ciudad.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
                              puesto.direccion.toLowerCase().includes(searchQuery.value.toLowerCase());
        
        const matchesStatus = filterStatus.value === 'TODOS' || puesto.estado_semaforo === filterStatus.value;

        return matchesSearch && matchesStatus;
    });
});

const getStatusBadge = (status: 'NORMAL' | 'ADVERTENCIA' | 'CRITICO') => {
    switch (status) {
        case 'CRITICO':
            return {
                bg: 'bg-rose-500/10 text-rose-500 border-rose-500/30',
                dot: 'bg-rose-500 animate-ping',
                label: 'Crítico',
                desc: 'Novedad urgente sin resolver o turno desatendido',
            };
        case 'ADVERTENCIA':
            return {
                bg: 'bg-amber-500/10 text-amber-500 border-amber-500/30',
                dot: 'bg-amber-500',
                label: 'Advertencia',
                desc: 'Atención preventiva requerida',
            };
        case 'NORMAL':
        default:
            return {
                bg: 'bg-emerald-500/10 text-emerald-500 border-emerald-500/30',
                dot: 'bg-emerald-500',
                label: 'Operativo',
                desc: 'Garita en línea con relevo activo',
            };
    }
};

const formatHash = (hash?: string) => {
    if (!hash) return 'N/A';
    return `${hash.slice(0, 10)}...${hash.slice(-8)}`;
};
</script>

<template>
    <Head title="Supervisión Corporativa B2B • Portería y Seguridad" />

    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2">
                    <span class="text-[10px] font-black uppercase tracking-[0.25em] text-primary">Portal Corporativo B2B</span>
                    <span class="px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider bg-primary/10 text-primary border border-primary/20">
                        Supervisión Multi-Copropiedad
                    </span>
                </div>
                <h2 class="text-2xl font-black uppercase tracking-tight text-on-surface dark:text-white mt-1">
                    Control Central de Puestos de Vigilancia
                </h2>
            </div>

            <div class="flex items-center gap-2">
                <span class="text-xs font-bold text-on-surface-variant flex items-center gap-1.5 bg-surface-container px-3 py-2 rounded-xl border border-outline-variant/15">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                    SuperVigilancia Colombia
                </span>
            </div>
        </div>

        <!-- Global Metrics KPIs -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <Card class="p-5 border border-outline-variant/15">
                <span class="text-[10px] font-black uppercase tracking-wider text-on-surface-variant block">Total Puestos</span>
                <div class="text-3xl font-black text-on-surface dark:text-white mt-1 flex items-baseline gap-2">
                    {{ dashboard.kpis.total_puestos }}
                    <span class="text-xs font-bold text-on-surface-variant">Garitas</span>
                </div>
            </Card>

            <Card class="p-5 border border-outline-variant/15">
                <span class="text-[10px] font-black uppercase tracking-wider text-emerald-500 block">Puestos Operativos</span>
                <div class="text-3xl font-black text-emerald-500 mt-1 flex items-baseline gap-2">
                    {{ dashboard.kpis.puestos_operativos }}
                    <span class="text-xs font-bold text-emerald-500/80">Normales</span>
                </div>
            </Card>

            <Card class="p-5 border border-outline-variant/15">
                <span class="text-[10px] font-black uppercase tracking-wider text-amber-500 block">En Observación</span>
                <div class="text-3xl font-black text-amber-500 mt-1 flex items-baseline gap-2">
                    {{ dashboard.kpis.puestos_alerta }}
                    <span class="text-xs font-bold text-amber-500/80">Alertas</span>
                </div>
            </Card>

            <Card class="p-5 border border-rose-500/30 bg-rose-500/5">
                <span class="text-[10px] font-black uppercase tracking-wider text-rose-500 block">Incidentes Críticos (24h)</span>
                <div class="text-3xl font-black text-rose-500 mt-1 flex items-baseline gap-2">
                    {{ dashboard.kpis.alertas_criticas }}
                    <span class="text-xs font-bold text-rose-500/80">Urgentes</span>
                </div>
            </Card>
        </div>

        <!-- Filter & Search Toolbar -->
        <Card class="p-4 border border-outline-variant/15 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="relative flex-1 max-w-md">
                <span class="material-symbols-rounded absolute left-3.5 top-1/2 -translate-y-1/2 text-on-surface-variant text-lg">search</span>
                <input
                    v-model="searchQuery"
                    type="text"
                    placeholder="Buscar puesto por nombre, dirección o ciudad..."
                    class="w-full min-h-[44px] pl-10 pr-4 rounded-xl bg-surface-container-low border border-outline-variant/20 text-xs font-bold text-on-surface focus:ring-2 focus:ring-primary focus:outline-none"
                />
            </div>

            <div class="w-full max-w-full flex items-center gap-2 overflow-x-auto overscroll-x-contain touch-pan-x no-scrollbar">
                <button
                    @click="filterStatus = 'TODOS'"
                    type="button"
                    class="min-h-[40px] px-3.5 rounded-xl text-xs font-black uppercase tracking-wider transition-all border"
                    :class="filterStatus === 'TODOS' ? 'bg-primary text-white border-primary shadow-xs' : 'bg-surface-container border-outline-variant/15 text-on-surface-variant'"
                >
                    Todos ({{ dashboard.puestos.length }})
                </button>
                <button
                    @click="filterStatus = 'CRITICO'"
                    type="button"
                    class="min-h-[40px] px-3.5 rounded-xl text-xs font-black uppercase tracking-wider transition-all border"
                    :class="filterStatus === 'CRITICO' ? 'bg-rose-500 text-white border-rose-500 shadow-xs' : 'bg-surface-container border-outline-variant/15 text-rose-500 hover:bg-rose-500/10'"
                >
                    Críticos
                </button>
                <button
                    @click="filterStatus = 'ADVERTENCIA'"
                    type="button"
                    class="min-h-[40px] px-3.5 rounded-xl text-xs font-black uppercase tracking-wider transition-all border"
                    :class="filterStatus === 'ADVERTENCIA' ? 'bg-amber-500 text-slate-950 border-amber-500 shadow-xs' : 'bg-surface-container border-outline-variant/15 text-amber-500 hover:bg-amber-500/10'"
                >
                    Advertencias
                </button>
                <button
                    @click="filterStatus = 'NORMAL'"
                    type="button"
                    class="min-h-[40px] px-3.5 rounded-xl text-xs font-black uppercase tracking-wider transition-all border"
                    :class="filterStatus === 'NORMAL' ? 'bg-emerald-500 text-white border-emerald-500 shadow-xs' : 'bg-surface-container border-outline-variant/15 text-emerald-500 hover:bg-emerald-500/10'"
                >
                    Operativos
                </button>
            </div>
        </Card>

        <!-- Monitored Buildings Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            <Card
                v-for="puesto in puestosFiltrados"
                :key="puesto.id"
                class="p-6 border transition-all hover:scale-[1.01] flex flex-col justify-between"
                :class="[
                    puesto.estado_semaforo === 'CRITICO'
                        ? 'border-rose-500/40 bg-rose-500/5'
                        : (puesto.estado_semaforo === 'ADVERTENCIA' ? 'border-amber-500/30 bg-amber-500/5' : 'border-outline-variant/15')
                ]"
            >
                <div class="space-y-4">
                    <!-- Building Header & Semáforo -->
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <span class="text-[10px] font-black uppercase tracking-wider text-on-surface-variant block">
                                {{ puesto.ciudad }}
                            </span>
                            <h3 class="text-base font-black text-on-surface dark:text-white leading-tight mt-0.5">
                                {{ puesto.nombre }}
                            </h3>
                            <p class="text-[11px] text-on-surface-variant font-medium mt-0.5">
                                {{ puesto.direccion }}
                            </p>
                        </div>

                        <!-- Status Badge -->
                        <div 
                            class="px-2.5 py-1 rounded-full text-[9px] font-black uppercase tracking-wider border flex items-center gap-1.5 shrink-0"
                            :class="getStatusBadge(puesto.estado_semaforo).bg"
                        >
                            <span class="w-2 h-2 rounded-full" :class="getStatusBadge(puesto.estado_semaforo).dot"></span>
                            {{ getStatusBadge(puesto.estado_semaforo).label }}
                        </div>
                    </div>

                    <!-- Operational Metrics of this Building -->
                    <div class="grid grid-cols-2 gap-2 pt-2 border-t border-outline-variant/10 text-xs">
                        <div class="p-3 rounded-xl bg-surface-container-low">
                            <span class="text-[9px] font-black uppercase tracking-wider text-on-surface-variant block">Incidentes 24h</span>
                            <span 
                                class="text-sm font-black mt-0.5 block"
                                :class="puesto.novedades_urgentes_24h > 0 ? 'text-rose-500' : 'text-on-surface dark:text-white'"
                            >
                                {{ puesto.novedades_urgentes_24h }} Urgentes
                            </span>
                        </div>

                        <div class="p-3 rounded-xl bg-surface-container-low">
                            <span class="text-[9px] font-black uppercase tracking-wider text-on-surface-variant block">Último Relevo</span>
                            <span class="text-xs font-bold text-on-surface dark:text-white mt-0.5 block truncate">
                                {{ puesto.ultimo_relevo_at ? new Date(puesto.ultimo_relevo_at).toLocaleTimeString('es-CO', { hour: '2-digit', minute: '2-digit' }) : 'Sin turno' }}
                            </span>
                        </div>
                    </div>

                    <!-- Latest Log Entry -->
                    <div v-if="puesto.ultima_minuta" class="p-3.5 rounded-xl bg-surface-container border border-outline-variant/10 text-xs space-y-1.5">
                        <div class="flex items-center justify-between text-[10px] font-bold text-on-surface-variant">
                            <span class="uppercase font-black text-primary">{{ puesto.ultima_minuta.tipo_evento.replace('_', ' ') }}</span>
                            <span>{{ new Date(puesto.ultima_minuta.created_at).toLocaleTimeString('es-CO', { hour: '2-digit', minute: '2-digit' }) }}</span>
                        </div>
                        <p class="text-on-surface dark:text-white/90 text-xs line-clamp-2 font-medium">
                            {{ puesto.ultima_minuta.descripcion }}
                        </p>
                        <div class="text-[9px] font-mono text-on-surface-variant truncate">
                            SHA: {{ formatHash(puesto.ultima_minuta.hash_inmutable) }}
                        </div>
                    </div>
                    <div v-else class="p-3.5 rounded-xl bg-surface-container text-center text-xs text-on-surface-variant font-bold">
                        Sin anotaciones en minuta durante el turno.
                    </div>
                </div>

                <!-- Footer Action Buttons -->
                <div class="pt-4 mt-4 border-t border-outline-variant/10 flex items-center justify-between gap-2">
                    <button
                        @click="selectedPuesto = puesto"
                        type="button"
                        class="min-h-[44px] flex-1 px-4 rounded-xl bg-primary text-white text-xs font-black uppercase tracking-wider shadow-sm hover:scale-[1.02] active:scale-95 transition-all flex items-center justify-center gap-1.5"
                    >
                        <span class="material-symbols-rounded text-base">verified_user</span>
                        Auditar Garita
                    </button>
                    <Link
                        :href="route('porteria.minuta.index')"
                        class="min-h-[44px] px-3.5 rounded-xl bg-surface-container hover:bg-surface-container-high text-on-surface-variant text-xs font-black uppercase tracking-wider flex items-center justify-center border border-outline-variant/20"
                        title="Ver Bitácora Completa"
                    >
                        <span class="material-symbols-rounded text-base">open_in_new</span>
                    </Link>
                </div>
            </Card>
        </div>

        <div v-if="puestosFiltrados.length === 0" class="p-16 text-center text-on-surface-variant font-bold uppercase text-xs">
            No se encontraron puestos de vigilancia con los criterios de búsqueda aplicados.
        </div>

        <!-- Modal Inspección Forense de Garita -->
        <Modal :show="Boolean(selectedPuesto)" @close="selectedPuesto = null" max-width="md">
            <div v-if="selectedPuesto" class="p-6 md:p-8 space-y-6">
                <div>
                    <span class="text-[10px] font-black uppercase tracking-[0.25em] text-primary">Inspección Forense de Garita</span>
                    <h3 class="text-xl font-black uppercase tracking-tight text-on-surface dark:text-white mt-1">
                        {{ selectedPuesto.nombre }}
                    </h3>
                    <p class="text-xs text-on-surface-variant font-medium mt-0.5">
                        {{ selectedPuesto.direccion }} • {{ selectedPuesto.ciudad }}
                    </p>
                </div>

                <!-- Status Card -->
                <div 
                    class="p-4 rounded-2xl border space-y-2"
                    :class="getStatusBadge(selectedPuesto.estado_semaforo).bg"
                >
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full" :class="getStatusBadge(selectedPuesto.estado_semaforo).dot"></span>
                        <h4 class="text-xs font-black uppercase tracking-wider">
                            Estado: {{ getStatusBadge(selectedPuesto.estado_semaforo).label }}
                        </h4>
                    </div>
                    <p class="text-[11px] font-medium leading-relaxed">
                        {{ getStatusBadge(selectedPuesto.estado_semaforo).desc }}
                    </p>
                </div>

                <!-- Forensic Detail -->
                <div class="space-y-3 text-xs">
                    <div class="p-3.5 rounded-xl bg-surface-container-low border border-outline-variant/15 flex items-center justify-between">
                        <span class="font-bold text-on-surface-variant">Novedades Urgentes (24h)</span>
                        <span class="font-black text-rose-500">{{ selectedPuesto.novedades_urgentes_24h }}</span>
                    </div>

                    <div class="p-3.5 rounded-xl bg-surface-container-low border border-outline-variant/15 flex items-center justify-between">
                        <span class="font-bold text-on-surface-variant">Último Sello Forense SHA-256</span>
                        <span class="font-mono text-[10px] font-black text-emerald-500">
                            {{ formatHash(selectedPuesto.ultima_minuta?.hash_inmutable) }}
                        </span>
                    </div>

                    <div class="p-3.5 rounded-xl bg-surface-container-low border border-outline-variant/15 flex items-center justify-between">
                        <span class="font-bold text-on-surface-variant">Cumplimiento SuperVigilancia</span>
                        <span class="font-black text-emerald-500 uppercase tracking-wider text-[10px]">Certificado</span>
                    </div>
                </div>

                <div class="pt-2 flex items-center justify-end">
                    <button
                        @click="selectedPuesto = null"
                        type="button"
                        class="min-h-[48px] px-6 rounded-xl bg-primary text-white text-xs font-black uppercase tracking-wider shadow-sm hover:scale-[1.02] active:scale-95 transition-all"
                    >
                        Cerrar Inspección
                    </button>
                </div>
            </div>
        </Modal>
    </div>
</template>

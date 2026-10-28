<script setup lang="ts">
import { ref, computed } from 'vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Card from '@/Components/UI/Card.vue';
import Button from '@/Components/UI/Button.vue';
import Badge from '@/Components/UI/Badge.vue';
import Modal from '@/Components/UI/Modal.vue';
import Table from '@/Components/UI/Table.vue';
import StatCard from '@/Components/Dashboard/StatCard.vue';
import { useToast } from '@/Composables/useToast';

defineOptions({ layout: AuthenticatedLayout });

interface Zona {
    id: string;
    nombre: string;
    capacidad_maxima?: number;
    costo?: number;
    activa?: boolean;
}

interface ReservaItem {
    id: string;
    copropiedad_id: string;
    zona_id: string;
    unidad_id: string;
    user_id: string;
    fecha: string;
    hora_inicio: string;
    hora_fin: string;
    cantidad_personas: number;
    monto_pagado: number;
    estado: 'pendiente' | 'aprobada' | 'pagada' | 'rechazada' | 'cancelada';
    notas_admin: string | null;
    created_at: string;
    updated_at: string;
    zona?: Zona;
    unidad?: {
        id: string;
        nombre: string;
        torre?: string;
    };
    user?: {
        id: string;
        name: string;
        email: string;
    };
}

interface Metrics {
    pendiente?: number;
    aprobada?: number;
    pagada?: number;
    rechazada?: number;
    cancelada?: number;
    total?: number;
    recaudo_total?: number;
    tasa_aprobacion?: number;
}

const props = defineProps<{
    reservas: ReservaItem[];
    zonas?: Zona[];
    metrics?: Metrics;
}>();

const toast = useToast();
const selectedReserva = ref<ReservaItem | null>(null);

// Formulario de actualización de estado
const form = useForm({
    estado: 'aprobada',
    notas: '',
});

// Filtros reactivos
const searchQuery = ref('');
const statusFilter = ref<'todos' | 'pendiente' | 'aprobada' | 'pagada' | 'rechazada' | 'cancelada'>('todos');
const zonaFilter = ref<string>('todas');
const dateScopeFilter = ref<'todas' | 'proximas' | 'pasadas'>('todas');

const filteredReservas = computed(() => {
    const today = new Date().toISOString().slice(0, 10);

    return (props.reservas || []).filter((item) => {
        // Filtro por Estado
        if (statusFilter.value !== 'todos' && item.estado !== statusFilter.value) {
            return false;
        }

        // Filtro por Zona Común
        if (zonaFilter.value !== 'todas' && item.zona_id !== zonaFilter.value) {
            return false;
        }

        // Filtro por Rango Temporal
        if (dateScopeFilter.value === 'proximas' && item.fecha < today) {
            return false;
        }
        if (dateScopeFilter.value === 'pasadas' && item.fecha >= today) {
            return false;
        }

        // Filtro por Búsqueda de Texto
        if (searchQuery.value.trim() !== '') {
            const query = searchQuery.value.toLowerCase().trim();
            const radicado = (item.id || '').toLowerCase();
            const zonaNombre = (item.zona?.nombre || '').toLowerCase();
            const residente = (item.user?.name || '').toLowerCase();
            const torre = (item.unidad?.torre || '').toLowerCase();
            const unidad = (item.unidad?.nombre || '').toLowerCase();
            const notas = (item.notas_admin || '').toLowerCase();

            return (
                radicado.includes(query) ||
                zonaNombre.includes(query) ||
                residente.includes(query) ||
                torre.includes(query) ||
                unidad.includes(query) ||
                notas.includes(query)
            );
        }

        return true;
    });
});

const openManageModal = (res: ReservaItem) => {
    selectedReserva.value = res;
    form.estado = res.estado;
    form.notas = res.notas_admin || '';
};

const submitUpdate = () => {
    if (!selectedReserva.value) return;

    form.patch(route('admin.reservas.status', selectedReserva.value.id), {
        preserveScroll: true,
        onSuccess: () => {
            selectedReserva.value = null;
            toast.add('Estado de reserva actualizado y notificado al residente', 'success');
        },
        onError: () => {
            toast.add('Ocurrió un error al actualizar el estado.', 'error');
        },
    });
};

const applyPresetNote = (text: string) => {
    if (!form.notas) {
        form.notas = text;
    } else {
        form.notas += ` ${text}`;
    }
};

const quickUpdateStatus = (res: ReservaItem, newStatus: 'aprobada' | 'pagada' | 'rechazada') => {
    router.patch(
        route('admin.reservas.status', res.id),
        {
            estado: newStatus,
            notas: newStatus === 'aprobada' 
                ? 'Reserva autorizada por administración.' 
                : (newStatus === 'pagada' ? 'Pago validado satisfactoriamente.' : 'Solicitud no procedente.'),
        },
        {
            preserveScroll: true,
            onSuccess: () => toast.add(`Reserva marcada como ${getStatusLabel(newStatus)}`, 'success'),
        }
    );
};

const getStatusVariant = (status: string): any => {
    const variants: Record<string, string> = {
        pendiente: 'warning',
        aprobada: 'primary',
        pagada: 'success',
        cancelada: 'neutral',
        rechazada: 'error',
    };
    return variants[status] || 'neutral';
};

const getStatusLabel = (status: string): string => {
    const labels: Record<string, string> = {
        pendiente: 'Por Validar',
        aprobada: 'Confirmada',
        pagada: 'Liquidada / Pagada',
        cancelada: 'Anulada',
        rechazada: 'Rechazada',
    };
    return labels[status] || status.toUpperCase();
};

const formatCurrency = (value: number | string | undefined): string => {
    const num = Number(value) || 0;
    return new Intl.NumberFormat('es-CO', {
        style: 'currency',
        currency: 'COP',
        maximumFractionDigits: 0,
    }).format(num);
};

const formatShortId = (id: string): string => {
    if (!id) return '';
    return id.substring(0, 8).toUpperCase();
};

const formatDate = (date: string): string => {
    if (!date) return '—';
    return new Date(date).toLocaleDateString('es-CO', {
        weekday: 'short',
        day: 'numeric',
        month: 'short',
        year: 'numeric',
    });
};

const clearFilters = () => {
    searchQuery.value = '';
    statusFilter.value = 'todos';
    zonaFilter.value = 'todas';
    dateScopeFilter.value = 'todas';
};

const hasActiveFilters = computed(() => {
    return (
        searchQuery.value.trim() !== '' ||
        statusFilter.value !== 'todos' ||
        zonaFilter.value !== 'todas' ||
        dateScopeFilter.value !== 'todas'
    );
});

const tableColumns = [
    { key: 'zona_fecha', label: 'ACTIVO / CRONOGRAMA', sortable: false },
    { key: 'residente', label: 'USUARIO / INMUEBLE', sortable: false },
    { key: 'horario', label: 'HORARIO & AFORO', sortable: false },
    { key: 'costo', label: 'TARIFA / RECAUDO', sortable: false },
    { key: 'estado', label: 'ESTADO OPERATIVO', sortable: false },
    { key: 'acciones', label: '', sortable: false },
];
</script>

<template>
    <Head title="Gestor de Reservas — NEXO-PRO" />

    <div class="space-y-6 sm:space-y-10 md:space-y-12 pb-28 sm:pb-32 animate-in fade-in slide-in-from-bottom-4 duration-500">
        
        <!-- Tactical Header -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 sm:gap-8 px-1">
            <div class="space-y-2 sm:space-y-3">
                <div class="flex items-center gap-2.5">
                    <div class="w-1.5 sm:w-2 h-5 sm:h-6 bg-primary rounded-full shadow-[0_0_10px_rgba(var(--primary),0.5)]"></div>
                    <p class="text-[9px] sm:text-[10px] font-black text-on-surface-variant/50 dark:text-white/30 uppercase tracking-[0.3em] italic leading-none">
                        Módulo de Ocupación y Comunal
                    </p>
                </div>
                <h2 class="text-3xl sm:text-4xl md:text-5xl font-black text-on-surface dark:text-white tracking-tighter uppercase italic leading-none">
                    Gestor de <span class="text-primary italic">Reservas</span>
                </h2>
                <p class="text-[10px] sm:text-[11px] font-bold text-on-surface-variant/50 dark:text-white/20 uppercase tracking-[0.15em] sm:tracking-[0.2em] mt-1 sm:mt-2 leading-relaxed italic">
                    Dispatcher de disponibilidad, aprobación de cronogramas y liquidación de áreas comunes
                </p>
            </div>
            
            <div class="flex flex-wrap items-center gap-3 w-full sm:w-auto">
                <Button 
                    variant="outline" 
                    icon="home_work" 
                    class="!rounded-2xl h-12 px-6 text-xs font-black uppercase italic w-full sm:w-auto cursor-pointer" 
                    @click="router.get(route('admin.zonas.index'))"
                >
                    Configurar Espacios
                </Button>
                <div class="px-4 py-2.5 rounded-2xl bg-primary/10 border border-primary/20 flex items-center gap-3 w-full sm:w-auto justify-between sm:justify-start">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span class="text-[10px] font-black text-primary uppercase tracking-widest">Sincronización en Vivo</span>
                    </div>
                    <span class="text-xs font-black text-primary tabular-nums">{{ filteredReservas.length }} / {{ reservas?.length || 0 }}</span>
                </div>
            </div>
        </div>

        <!-- Tactical Real-time Metrics Cards -->
        <section class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-6">
            <StatCard 
                label="POR VALIDAR (PENDIENTES)" 
                :value="(metrics?.pendiente ?? 0).toString()" 
                color-class="!bg-warning" 
                class="!rounded-[1.8rem] sm:!rounded-[2.5rem] border border-outline-variant/10 dark:border-white/5 shadow-xl dark:bg-[#0b0e14]" 
            />
            <StatCard 
                label="CONFIRMADAS / ACTIVAS" 
                :value="((metrics?.aprobada ?? 0) + (metrics?.pagada ?? 0)).toString()" 
                color-class="!bg-primary" 
                class="!rounded-[1.8rem] sm:!rounded-[2.5rem] border border-outline-variant/10 dark:border-white/5 shadow-xl dark:bg-[#0b0e14]" 
            />
            <div class="p-5 sm:p-6 rounded-[1.8rem] sm:rounded-[2.5rem] bg-emerald-500/10 border border-emerald-500/20 shadow-xl flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-[9px] sm:text-[10px] font-black uppercase tracking-widest text-emerald-600 dark:text-emerald-400">RECAUDO LIQUIDADO</span>
                    <span class="material-symbols-rounded text-emerald-500 text-lg">payments</span>
                </div>
                <div class="mt-2">
                    <span class="text-2xl sm:text-3xl font-black text-emerald-600 dark:text-emerald-400 tabular-nums leading-none">
                        {{ formatCurrency(metrics?.recaudo_total) }}
                    </span>
                    <p class="text-[8.5px] font-bold text-emerald-600/70 dark:text-emerald-400/70 uppercase tracking-widest mt-1">
                        {{ metrics?.pagada ?? 0 }} reservas liquidadas
                    </p>
                </div>
            </div>
            <div class="p-5 sm:p-6 rounded-[1.8rem] sm:rounded-[2.5rem] bg-surface-container-high/60 dark:bg-white/5 border border-outline-variant/10 dark:border-white/5 shadow-xl flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-[9px] sm:text-[10px] font-black uppercase tracking-widest text-on-surface-variant/70">TASA APROBACIÓN</span>
                    <span class="material-symbols-rounded text-primary text-lg">event_available</span>
                </div>
                <div class="mt-2 flex items-baseline gap-2">
                    <span class="text-3xl sm:text-4xl font-black text-on-surface dark:text-white tabular-nums">
                        {{ metrics?.tasa_aprobacion ?? 0 }}%
                    </span>
                    <span class="text-[10px] font-bold text-on-surface-variant/50 uppercase">
                        ({{ metrics?.total ?? 0 }} globales)
                    </span>
                </div>
            </div>
        </section>

        <!-- Reactive Search & Multi-facet Filter Console -->
        <Card class="p-4 sm:p-6 md:p-8 rounded-[2rem] sm:rounded-[2.5rem] border border-outline-variant/10 dark:border-white/5 shadow-xl dark:bg-[#0b0e14] space-y-4">
            <!-- Search input & Selects -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3 sm:gap-4 items-center">
                <!-- Search Box -->
                <div class="lg:col-span-5 relative">
                    <span class="material-symbols-rounded absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant/40 text-xl pointer-events-none">search</span>
                    <input 
                        v-model="searchQuery" 
                        type="text" 
                        placeholder="Buscar zona, residente, torre, unidad o notas..." 
                        class="w-full pl-12 pr-4 py-3 sm:py-3.5 bg-surface-container-low dark:bg-white/[0.03] border border-outline-variant/15 dark:border-white/10 rounded-2xl text-xs sm:text-sm font-medium text-on-surface dark:text-white placeholder:text-on-surface-variant/40 focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all"
                        aria-label="Buscar reservas"
                    />
                    <button 
                        v-if="searchQuery" 
                        @click="searchQuery = ''" 
                        class="absolute right-3.5 top-1/2 -translate-y-1/2 w-6 h-6 rounded-full bg-on-surface-variant/10 flex items-center justify-center text-on-surface-variant/60 hover:text-on-surface cursor-pointer"
                        aria-label="Limpiar búsqueda"
                    >
                        <span class="material-symbols-rounded text-sm">close</span>
                    </button>
                </div>

                <!-- Zone Filter -->
                <div class="lg:col-span-4">
                    <select 
                        v-model="zonaFilter"
                        class="w-full py-3 sm:py-3.5 px-4 bg-surface-container-low dark:bg-white/[0.03] border border-outline-variant/15 dark:border-white/10 rounded-2xl text-xs font-black uppercase tracking-wider text-on-surface dark:text-white focus:outline-none focus:border-primary cursor-pointer"
                        aria-label="Filtrar por zona común"
                    >
                        <option value="todas">Área Común: Todas</option>
                        <option v-for="z in (zonas || [])" :key="z.id" :value="z.id">
                            {{ z.nombre }}
                        </option>
                    </select>
                </div>

                <!-- Date Scope Filter -->
                <div class="lg:col-span-3">
                    <select 
                        v-model="dateScopeFilter"
                        class="w-full py-3 sm:py-3.5 px-4 bg-surface-container-low dark:bg-white/[0.03] border border-outline-variant/15 dark:border-white/10 rounded-2xl text-xs font-black uppercase tracking-wider text-on-surface dark:text-white focus:outline-none focus:border-primary cursor-pointer"
                        aria-label="Filtrar por cronograma temporal"
                    >
                        <option value="todas">Cronograma: Todo</option>
                        <option value="proximas">Próximas & Hoy</option>
                        <option value="pasadas">Históricas / Pasadas</option>
                    </select>
                </div>
            </div>

            <!-- Status Tabs (Horizontal Scrollable on Mobile) -->
            <div class="flex items-center justify-between border-t border-outline-variant/5 dark:border-white/5 pt-3 gap-2 overflow-x-auto no-scrollbar">
                <div class="flex items-center gap-1.5 sm:gap-2 min-w-max">
                    <button 
                        @click="statusFilter = 'todos'"
                        :class="statusFilter === 'todos' ? 'bg-primary text-white shadow-md shadow-primary/20' : 'bg-surface-container-low dark:bg-white/5 text-on-surface-variant/70 hover:text-on-surface dark:hover:text-white'"
                        class="px-3.5 sm:px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-wider transition-all cursor-pointer flex items-center gap-2"
                    >
                        <span>Todas</span>
                        <span class="px-1.5 py-0.5 rounded-full text-[9px]" :class="statusFilter === 'todos' ? 'bg-white/20 text-white' : 'bg-on-surface-variant/10 text-on-surface-variant'">{{ reservas?.length || 0 }}</span>
                    </button>
                    <button 
                        @click="statusFilter = 'pendiente'"
                        :class="statusFilter === 'pendiente' ? 'bg-warning text-white shadow-md shadow-warning/20' : 'bg-surface-container-low dark:bg-white/5 text-on-surface-variant/70 hover:text-on-surface dark:hover:text-white'"
                        class="px-3.5 sm:px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-wider transition-all cursor-pointer flex items-center gap-2"
                    >
                        <span>Por Validar</span>
                        <span class="px-1.5 py-0.5 rounded-full text-[9px]" :class="statusFilter === 'pendiente' ? 'bg-white/20 text-white' : 'bg-warning/10 text-warning'">{{ metrics?.pendiente ?? 0 }}</span>
                    </button>
                    <button 
                        @click="statusFilter = 'aprobada'"
                        :class="statusFilter === 'aprobada' ? 'bg-primary text-white shadow-md shadow-primary/20' : 'bg-surface-container-low dark:bg-white/5 text-on-surface-variant/70 hover:text-on-surface dark:hover:text-white'"
                        class="px-3.5 sm:px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-wider transition-all cursor-pointer flex items-center gap-2"
                    >
                        <span>Confirmadas</span>
                        <span class="px-1.5 py-0.5 rounded-full text-[9px]" :class="statusFilter === 'aprobada' ? 'bg-white/20 text-white' : 'bg-primary/10 text-primary'">{{ metrics?.aprobada ?? 0 }}</span>
                    </button>
                    <button 
                        @click="statusFilter = 'pagada'"
                        :class="statusFilter === 'pagada' ? 'bg-emerald-600 text-white shadow-md shadow-emerald-600/20' : 'bg-surface-container-low dark:bg-white/5 text-on-surface-variant/70 hover:text-on-surface dark:hover:text-white'"
                        class="px-3.5 sm:px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-wider transition-all cursor-pointer flex items-center gap-2"
                    >
                        <span>Liquidadas</span>
                        <span class="px-1.5 py-0.5 rounded-full text-[9px]" :class="statusFilter === 'pagada' ? 'bg-white/20 text-white' : 'bg-emerald-500/10 text-emerald-600'">{{ metrics?.pagada ?? 0 }}</span>
                    </button>
                    <button 
                        @click="statusFilter = 'rechazada'"
                        :class="statusFilter === 'rechazada' ? 'bg-error text-white shadow-md shadow-error/20' : 'bg-surface-container-low dark:bg-white/5 text-on-surface-variant/70 hover:text-on-surface dark:hover:text-white'"
                        class="px-3.5 sm:px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-wider transition-all cursor-pointer flex items-center gap-2"
                    >
                        <span>Rechazadas</span>
                        <span class="px-1.5 py-0.5 rounded-full text-[9px]" :class="statusFilter === 'rechazada' ? 'bg-white/20 text-white' : 'bg-error/10 text-error'">{{ metrics?.rechazada ?? 0 }}</span>
                    </button>
                </div>

                <button 
                    v-if="hasActiveFilters"
                    @click="clearFilters"
                    class="text-[9px] font-black uppercase tracking-widest text-primary hover:underline whitespace-nowrap px-2 cursor-pointer"
                >
                    Reiniciar Filtros
                </button>
            </div>
        </Card>

        <!-- Main Content Area: Responsive Dual Layout -->
        <Card class="!p-0 rounded-[2rem] sm:rounded-[2.8rem] md:!rounded-[3.5rem] border border-outline-variant/10 dark:border-white/5 shadow-2xl dark:bg-[#0b0e14] overflow-hidden">
            <!-- Header bar -->
            <div class="p-5 sm:p-8 md:p-10 flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-outline-variant/5 dark:border-white/5">
                <div class="flex items-center gap-4">
                    <div class="w-12 sm:w-14 h-12 sm:h-14 bg-primary/10 rounded-2xl flex items-center justify-center text-primary shadow-inner shrink-0">
                        <span class="material-symbols-rounded text-2xl sm:text-3xl">event_available</span>
                    </div>
                    <div>
                        <h3 class="text-xl sm:text-2xl font-black text-on-surface dark:text-white uppercase tracking-tighter italic leading-none">
                            Cronograma de <span class="text-primary italic">Ocupación</span>
                        </h3>
                        <p class="text-[9px] sm:text-[10px] font-bold text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.25em] mt-2 italic">
                            {{ filteredReservas.length }} solicitudes listadas según los filtros seleccionados
                        </p>
                    </div>
                </div>
            </div>

            <!-- Empty State -->
            <div v-if="filteredReservas.length === 0" class="py-16 sm:py-24 text-center px-4">
                <div class="w-16 h-16 sm:w-20 sm:h-20 bg-primary/5 rounded-full flex items-center justify-center mx-auto mb-4 text-primary/40">
                    <span class="material-symbols-rounded text-3xl sm:text-4xl">calendar_today</span>
                </div>
                <h4 class="text-base sm:text-lg font-black text-on-surface dark:text-white uppercase tracking-tight">
                    No hay reservas registradas
                </h4>
                <p class="text-xs text-on-surface-variant/50 dark:text-white/30 max-w-sm mx-auto mt-2 font-medium">
                    {{ hasActiveFilters ? 'Prueba ajustando los términos de búsqueda o los filtros seleccionados.' : 'No existen solicitudes de uso de zonas comunes en este momento.' }}
                </p>
                <button 
                    v-if="hasActiveFilters" 
                    @click="clearFilters" 
                    class="mt-4 px-4 py-2 rounded-xl bg-primary/10 text-primary text-xs font-black uppercase tracking-wider hover:bg-primary/20 transition-all cursor-pointer"
                >
                    Ver Todas las Reservas
                </button>
            </div>

            <!-- 1. MOBILE NATIVE RESERVATION CARDS (Xiaomi 14T & Viewports < 768px) -->
            <div v-else class="block md:hidden divide-y divide-outline-variant/10 dark:divide-white/5">
                <article 
                    v-for="item in filteredReservas" 
                    :key="item.id"
                    class="p-4 sm:p-5 space-y-3.5 hover:bg-surface-container-high/40 dark:hover:bg-white/[0.02] transition-colors"
                >
                    <!-- Top Zone & Status Row -->
                    <div class="flex items-center justify-between gap-2">
                        <div class="flex items-center gap-2 min-w-0">
                            <span class="w-2 h-2 rounded-full bg-primary shrink-0"></span>
                            <h4 class="text-xs sm:text-sm font-black text-on-surface dark:text-white uppercase tracking-tight truncate">
                                {{ item.zona?.nombre || 'Espacio Común' }}
                            </h4>
                        </div>
                        <Badge :variant="getStatusVariant(item.estado)" class="!rounded-lg !px-2.5 !py-0.5 text-[8.5px] font-black uppercase tracking-wider shrink-0">
                            {{ getStatusLabel(item.estado) }}
                        </Badge>
                    </div>

                    <!-- Date & Time Vector Card -->
                    <div class="grid grid-cols-2 gap-2 p-3 rounded-2xl bg-surface-container-low dark:bg-white/[0.02] border border-outline-variant/10 dark:border-white/5 text-xs">
                        <div class="flex items-center gap-2 min-w-0">
                            <span class="material-symbols-rounded text-primary text-base shrink-0">calendar_month</span>
                            <div class="min-w-0">
                                <p class="text-[8px] font-black text-on-surface-variant/50 uppercase tracking-widest">FECHA</p>
                                <p class="text-[10px] font-black text-on-surface dark:text-white uppercase truncate">{{ formatDate(item.fecha) }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 min-w-0">
                            <span class="material-symbols-rounded text-secondary text-base shrink-0">schedule</span>
                            <div class="min-w-0">
                                <p class="text-[8px] font-black text-on-surface-variant/50 uppercase tracking-widest">HORARIO</p>
                                <p class="text-[10px] font-black text-on-surface dark:text-white truncate tabular-nums">
                                    {{ item.hora_inicio?.slice(0,5) }} - {{ item.hora_fin?.slice(0,5) }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Resident, Unit & Financial Row -->
                    <div class="flex items-center justify-between gap-3 text-xs">
                        <div class="flex items-center gap-2.5 min-w-0">
                            <div class="w-8 h-8 rounded-xl bg-surface-container-high dark:bg-white/5 flex items-center justify-center text-primary shrink-0 border border-outline-variant/10">
                                <span class="material-symbols-rounded text-base">person</span>
                            </div>
                            <div class="min-w-0">
                                <p class="text-[10px] font-black text-on-surface dark:text-white uppercase truncate">
                                    {{ item.unidad ? `Torre ${item.unidad.torre || '—'} · ${item.unidad.nombre}` : 'Sin Unidad' }}
                                </p>
                                <p class="text-[9px] font-medium text-on-surface-variant/60 dark:text-white/40 truncate">
                                    {{ item.user?.name || 'Residente' }}
                                </p>
                            </div>
                        </div>

                        <div class="text-right shrink-0">
                            <p class="text-[11px] font-black text-primary tabular-nums leading-none">
                                {{ item.monto_pagado > 0 ? formatCurrency(item.monto_pagado) : 'USO CORTESÍA' }}
                            </p>
                            <p class="text-[8px] font-bold text-on-surface-variant/40 uppercase tracking-widest mt-0.5">
                                Aforo: {{ item.cantidad_personas || '—' }} pers.
                            </p>
                        </div>
                    </div>

                    <!-- Notes preview if any -->
                    <div v-if="item.notas_admin" class="p-2.5 rounded-xl bg-primary/5 border border-primary/10 text-[9px] text-on-surface-variant dark:text-white/70 italic">
                        <span class="font-bold text-primary not-italic uppercase tracking-wider">Nota Admin:</span> {{ item.notas_admin }}
                    </div>

                    <!-- Primary Action Buttons (Tactile >= 44x44px for Xiaomi 14T thumb zone) -->
                    <div class="pt-1 grid grid-cols-2 gap-2">
                        <button 
                            v-if="item.estado === 'pendiente'"
                            @click="quickUpdateStatus(item, 'aprobada')"
                            class="h-11 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-black text-[10px] uppercase tracking-wider flex items-center justify-center gap-1.5 shadow-md shadow-emerald-600/20 active:scale-98 transition-all cursor-pointer"
                        >
                            <span class="material-symbols-rounded text-base">check</span>
                            <span>Aprobar</span>
                        </button>
                        <button 
                            @click="openManageModal(item)"
                            class="h-11 rounded-xl bg-primary hover:bg-primary-dark text-white font-black text-[10px] uppercase tracking-wider flex items-center justify-center gap-1.5 shadow-md shadow-primary/20 active:scale-98 transition-all cursor-pointer"
                            :class="item.estado !== 'pendiente' ? 'col-span-2' : ''"
                            aria-label="Gestionar reserva"
                        >
                            <span class="material-symbols-rounded text-base">settings_input_component</span>
                            <span>Gestionar / Mando</span>
                        </button>
                    </div>
                </article>
            </div>

            <!-- 2. DESKTOP HIGH-DENSITY OPERATIONAL TABLE (Visible on md+ screens) -->
            <div class="hidden md:block">
                <Table :columns="tableColumns" :data="filteredReservas" class="border-t border-outline-variant/5 dark:border-white/5">
                    <template #cell-zona_fecha="{ row }">
                        <div class="flex flex-col py-2">
                            <span class="text-sm font-black text-primary uppercase tracking-tight italic leading-none truncate">
                                {{ row.zona?.nombre || 'Zona Común' }}
                            </span>
                            <div class="flex items-center gap-2 mt-1.5">
                                <span class="w-1.5 h-1.5 bg-primary rounded-full"></span>
                                <span class="text-[9px] text-on-surface-variant/60 dark:text-white/40 font-black uppercase tracking-widest italic">
                                    {{ formatDate(row.fecha) }}
                                </span>
                            </div>
                        </div>
                    </template>

                    <template #cell-residente="{ row }">
                        <div class="flex items-center gap-3.5 py-2">
                            <div class="w-10 h-10 rounded-2xl bg-surface-container-high dark:bg-white/5 flex items-center justify-center text-xs font-black text-primary border border-outline-variant/10 shadow-sm shrink-0">
                                <span class="material-symbols-rounded text-lg">person</span>
                            </div>
                            <div class="flex flex-col min-w-0">
                                <span class="text-xs font-black text-on-surface dark:text-white uppercase italic tracking-tighter truncate leading-none">
                                    {{ row.user?.name || 'Residente' }}
                                </span>
                                <span class="text-[9px] font-bold text-on-surface-variant/40 dark:text-white/20 uppercase tracking-widest mt-1.5 leading-none truncate">
                                    {{ row.unidad ? `TORRE ${row.unidad.torre || '—'} · ${row.unidad.nombre}` : 'SIN UNIDAD' }}
                                </span>
                            </div>
                        </div>
                    </template>

                    <template #cell-horario="{ row }">
                        <div class="flex flex-col py-2">
                            <div class="flex items-center gap-2 bg-surface-container dark:bg-white/5 px-3 py-1.5 rounded-xl border border-outline-variant/10 w-fit">
                                <span class="material-symbols-rounded text-sm text-primary">schedule</span>
                                <span class="text-[10px] font-black text-on-surface dark:text-white italic tracking-wider tabular-nums">
                                    {{ row.hora_inicio?.slice(0,5) }} / {{ row.hora_fin?.slice(0,5) }}
                                </span>
                            </div>
                            <span class="text-[8.5px] font-bold text-on-surface-variant/40 uppercase tracking-widest mt-1">
                                Aforo: {{ row.cantidad_personas || '—' }} personas
                            </span>
                        </div>
                    </template>

                    <template #cell-costo="{ row }">
                        <div class="flex flex-col py-2">
                            <span class="text-xs font-black text-on-surface dark:text-white italic tracking-tighter leading-none whitespace-nowrap">
                                {{ row.monto_pagado > 0 ? formatCurrency(row.monto_pagado) : 'COSTO CERO' }}
                            </span>
                            <span class="text-[8px] font-black text-on-surface-variant/40 dark:text-white/20 uppercase tracking-widest mt-1 italic">
                                {{ row.monto_pagado > 0 ? 'TARIFA OFICIAL' : 'USO CORTESÍA' }}
                            </span>
                        </div>
                    </template>

                    <template #cell-estado="{ row }">
                        <div class="flex justify-center">
                            <Badge :variant="getStatusVariant(row.estado)" class="!px-4 !py-1 !font-black !text-[8.5px] tracking-[0.1em] uppercase italic border">
                                {{ getStatusLabel(row.estado) }}
                            </Badge>
                        </div>
                    </template>

                    <template #cell-acciones="{ row }">
                        <div class="flex items-center justify-end gap-2 pr-6">
                            <button 
                                v-if="row.estado === 'pendiente'"
                                @click="quickUpdateStatus(row, 'aprobada')"
                                class="w-9 h-9 rounded-xl bg-emerald-600/10 hover:bg-emerald-600 text-emerald-600 hover:text-white flex items-center justify-center transition-all border border-emerald-600/20 cursor-pointer"
                                title="Aprobar inmediatamente"
                            >
                                <span class="material-symbols-rounded text-base">check</span>
                            </button>
                            <Button 
                                variant="primary" 
                                size="sm" 
                                icon="settings_input_component" 
                                class="!rounded-xl !h-10 !px-4 !text-[10px] font-black uppercase italic shadow-md shadow-primary/10 cursor-pointer"
                                @click="openManageModal(row)"
                            >
                                Gestionar
                            </Button>
                        </div>
                    </template>
                </Table>
            </div>
        </Card>

        <!-- Dynamic Management Console (Modal) -->
        <Modal 
            v-if="selectedReserva" 
            @close="selectedReserva = null"
            class="!max-w-2xl"
        >
            <template #title>
                <div class="flex items-center gap-3 sm:gap-4">
                    <div class="w-11 h-11 sm:w-12 sm:h-12 bg-primary/10 rounded-2xl flex items-center justify-center text-primary border border-primary/20 shrink-0">
                        <span class="material-symbols-rounded text-2xl">published_with_changes</span>
                    </div>
                    <div class="min-w-0">
                        <h3 class="text-xl sm:text-2xl font-black text-on-surface dark:text-white uppercase tracking-tighter italic leading-none truncate">
                            Mando de <span class="text-primary italic">Aprobación</span>
                        </h3>
                        <p class="text-[9px] font-bold text-on-surface-variant/40 dark:text-white/20 uppercase tracking-widest mt-1 truncate">
                            Solicitud ID: #{{ formatShortId(selectedReserva.id) }} — {{ selectedReserva.zona?.nombre }}
                        </p>
                    </div>
                </div>
            </template>

            <div class="space-y-6 mt-6 pb-2">
                <!-- Info Cluster Box -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 bg-surface-container-low dark:bg-white/[0.03] p-5 sm:p-6 rounded-[2rem] border border-outline-variant/10 dark:border-white/5">
                    <div class="space-y-1">
                        <p class="text-[9px] font-black text-primary uppercase tracking-wider">Zona Común Solicitada:</p>
                        <p class="text-sm sm:text-base font-black text-on-surface dark:text-white uppercase">{{ selectedReserva.zona?.nombre }}</p>
                        <p class="text-[9px] text-on-surface-variant/60 font-medium">Aforo: {{ selectedReserva.cantidad_personas }} personas</p>
                    </div>
                    <div class="sm:text-right space-y-1">
                        <p class="text-[9px] font-black text-primary uppercase tracking-wider">Fecha & Horario:</p>
                        <p class="text-xs sm:text-sm font-black text-on-surface dark:text-white uppercase">{{ formatDate(selectedReserva.fecha) }}</p>
                        <p class="text-[10px] font-bold text-on-surface-variant/60 tabular-nums">
                            {{ selectedReserva.hora_inicio?.slice(0,5) }} a {{ selectedReserva.hora_fin?.slice(0,5) }}
                        </p>
                    </div>
                </div>

                <!-- Form -->
                <form @submit.prevent="submitUpdate" class="space-y-6">
                    <!-- Status Selector Cards -->
                    <div class="space-y-2">
                        <label class="block text-xs font-black text-on-surface dark:text-white uppercase tracking-wider">
                            Estado de la Reserva
                        </label>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                            <button 
                                v-for="st in ['pendiente', 'aprobada', 'pagada', 'rechazada', 'cancelada']"
                                :key="st"
                                type="button"
                                @click="form.estado = st"
                                class="h-11 px-2.5 rounded-xl border text-[10px] font-black uppercase tracking-wider transition-all duration-200 cursor-pointer flex items-center justify-center text-center select-none"
                                :class="[
                                    form.estado === st 
                                        ? 'border-primary bg-primary text-white shadow-md shadow-primary/20 scale-[1.02]' 
                                        : 'border-outline-variant/15 bg-surface-container-low dark:bg-white/5 text-on-surface-variant hover:border-primary/40'
                                ]"
                            >
                                {{ getStatusLabel(st) }}
                            </button>
                        </div>
                    </div>

                    <!-- Preset Observations for Quick Mobile Typing -->
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <label for="admin-notas" class="block text-xs font-black text-on-surface dark:text-white uppercase tracking-wider">
                                Observaciones para el Residente
                            </label>
                            <span class="text-[9px] font-bold text-on-surface-variant/40">
                                {{ form.notas.length }} caracteres
                            </span>
                        </div>

                        <!-- Canned Preset Answers -->
                        <div class="flex flex-wrap gap-1.5 pb-1">
                            <button 
                                type="button" 
                                @click="applyPresetNote('Reserva autorizada. Favor reclamar llaves en portería.')"
                                class="px-2.5 py-1 rounded-lg bg-surface-container-high dark:bg-white/5 hover:bg-primary/10 text-[9px] font-bold text-on-surface-variant/70 hover:text-primary transition-all border border-outline-variant/10 cursor-pointer"
                            >
                                + Llaves en portería
                            </button>
                            <button 
                                type="button" 
                                @click="applyPresetNote('Comprobante de pago validado y conciliado.')"
                                class="px-2.5 py-1 rounded-lg bg-surface-container-high dark:bg-white/5 hover:bg-primary/10 text-[9px] font-bold text-on-surface-variant/70 hover:text-primary transition-all border border-outline-variant/10 cursor-pointer"
                            >
                                + Pago conciliado
                            </button>
                            <button 
                                type="button" 
                                @click="applyPresetNote('Rechazada por mantenimiento programado en el área comunal.')"
                                class="px-2.5 py-1 rounded-lg bg-surface-container-high dark:bg-white/5 hover:bg-primary/10 text-[9px] font-bold text-on-surface-variant/70 hover:text-primary transition-all border border-outline-variant/10 cursor-pointer"
                            >
                                + Mantenimiento programado
                            </button>
                        </div>

                        <textarea 
                            id="admin-notas"
                            v-model="form.notas" 
                            rows="4" 
                            class="w-full bg-surface-container-low dark:bg-white/[0.03] border border-outline-variant/20 dark:border-white/10 rounded-2xl p-4 text-xs sm:text-sm font-medium text-on-surface dark:text-white focus:bg-surface dark:focus:bg-[#0b0e14] focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all placeholder:text-on-surface-variant/40"
                            placeholder="Aclaraciones sobre entrega de llaves, depósito en garantía, aforo permitido o motivos de rechazo..."
                        ></textarea>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row items-center gap-3 pt-3 border-t border-outline-variant/10 dark:border-white/5">
                        <Button 
                            type="submit" 
                            variant="primary" 
                            size="lg" 
                            class="w-full sm:flex-1 !h-12 !rounded-xl font-black text-xs uppercase tracking-wider shadow-lg shadow-primary/20 cursor-pointer"
                            :disabled="form.processing"
                            :loading="form.processing"
                            icon="check_circle"
                        >
                            {{ form.processing ? 'Sincronizando...' : 'Actualizar Estado de Reserva' }}
                        </Button>
                        <Button 
                            type="button" 
                            variant="ghost" 
                            class="w-full sm:w-auto text-on-surface-variant/60 hover:text-on-surface cursor-pointer" 
                            @click="selectedReserva = null"
                        >
                            Cancelar
                        </Button>
                    </div>
                </form>
            </div>
        </Modal>
    </div>
</template>

<style scoped>
.material-symbols-rounded {
    font-variation-settings: 'opsz' 24, 'wght' 600, 'FILL' 0;
}
</style>

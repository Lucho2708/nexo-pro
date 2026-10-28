<script setup lang="ts">
import { ref, computed } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Card from '@/Components/UI/Card.vue';
import Button from '@/Components/UI/Button.vue';
import Badge from '@/Components/UI/Badge.vue';
import Modal from '@/Components/UI/Modal.vue';
import StatCard from '@/Components/Dashboard/StatCard.vue';
import Table from '@/Components/UI/Table.vue';
import { useToast } from '@/Composables/useToast';

defineOptions({ layout: AuthenticatedLayout });

interface PqrsItem {
    id: string;
    copropiedad_id: string;
    unidad_id: string;
    user_id: string;
    tipo: 'peticion' | 'queja' | 'reclamo' | 'sugerencia' | 'reporte_danos' | 'felicitaciones';
    asunto: string;
    mensaje: string;
    prioridad: 'baja' | 'media' | 'alta';
    estado: 'abierto' | 'en_proceso' | 'cerrado' | 'reabierto';
    respuesta: string | null;
    fecha_respuesta: string | null;
    adjuntos: string[] | null;
    created_at: string;
    updated_at: string;
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
    abierto?: number;
    en_proceso?: number;
    reabierto?: number;
    cerrado?: number;
    total?: number;
    tasa_resolucion?: number;
    urgentes_activas?: number;
}

const props = defineProps<{
    pqrs: PqrsItem[];
    metrics: Metrics;
}>();

const toast = useToast();
const selectedPqrs = ref<PqrsItem | null>(null);

// Formulario de respuesta
const form = useForm({
    respuesta: '',
    cerrar: true,
});

// Filtros reactivos
const searchQuery = ref('');
const statusFilter = ref<'todos' | 'abierto' | 'en_proceso' | 'reabierto' | 'cerrado'>('todos');
const priorityFilter = ref<'todas' | 'baja' | 'media' | 'alta'>('todas');
const typeFilter = ref<string>('todos');

const filteredPqrs = computed(() => {
    return (props.pqrs || []).filter((item) => {
        // Filtro por Estado
        if (statusFilter.value !== 'todos' && item.estado !== statusFilter.value) {
            return false;
        }

        // Filtro por Prioridad
        if (priorityFilter.value !== 'todas' && item.prioridad !== priorityFilter.value) {
            return false;
        }

        // Filtro por Tipo / Categoría
        if (typeFilter.value !== 'todos' && item.tipo !== typeFilter.value) {
            return false;
        }

        // Filtro por Búsqueda de Texto
        if (searchQuery.value.trim() !== '') {
            const query = searchQuery.value.toLowerCase().trim();
            const radicado = (item.id || '').toLowerCase();
            const asunto = (item.asunto || '').toLowerCase();
            const mensaje = (item.mensaje || '').toLowerCase();
            const residente = (item.user?.name || '').toLowerCase();
            const torre = (item.unidad?.torre || '').toLowerCase();
            const unidad = (item.unidad?.nombre || '').toLowerCase();

            return (
                radicado.includes(query) ||
                asunto.includes(query) ||
                mensaje.includes(query) ||
                residente.includes(query) ||
                torre.includes(query) ||
                unidad.includes(query)
            );
        }

        return true;
    });
});

const openResponseModal = (item: PqrsItem) => {
    selectedPqrs.value = item;
    form.respuesta = item.respuesta || '';
    form.cerrar = item.estado !== 'cerrado';
};

const getUpdateRoute = (id: string): string => {
    try {
        return route('admin.pqrs.update', id);
    } catch {
        return route('pqrs.update', id);
    }
};

const getDownloadRoute = (id: string): string => {
    try {
        return route('admin.pqrs.download', id);
    } catch {
        return route('pqrs.download', id);
    }
};

const submitResponse = () => {
    if (!selectedPqrs.value) return;

    if (!form.respuesta.trim()) {
        toast.add('La respuesta oficial no puede estar vacía.', 'error');
        return;
    }

    form.patch(getUpdateRoute(selectedPqrs.value.id), {
        preserveScroll: true,
        onSuccess: () => {
            selectedPqrs.value = null;
            form.reset();
            toast.add('Respuesta y actualización de estado procesadas con éxito', 'success');
        },
        onError: () => {
            toast.add('Ocurrió un error al enviar la respuesta.', 'error');
        },
    });
};

const applyPresetReply = (text: string) => {
    if (!form.respuesta) {
        form.respuesta = text;
    } else {
        form.respuesta += ` ${text}`;
    }
};

const getStatusVariant = (status: string): any => {
    const variants: Record<string, string> = {
        abierto: 'primary',
        en_proceso: 'warning',
        cerrado: 'success',
        reabierto: 'error',
    };
    return variants[status] || 'neutral';
};

const getStatusLabel = (status: string): string => {
    const labels: Record<string, string> = {
        abierto: 'Abierto / Nuevo',
        en_proceso: 'En Trámite',
        cerrado: 'Caso Resuelto',
        reabierto: 'Escalada Crítica',
    };
    return labels[status] || status.toUpperCase();
};

const getPriorityVariant = (priority: string): any => {
    const variants: Record<string, string> = {
        alta: 'error',
        media: 'warning',
        baja: 'neutral',
    };
    return variants[priority] || 'neutral';
};

const getTypeLabel = (type: string): string => {
    const labels: Record<string, string> = {
        peticion: 'Petición',
        queja: 'Queja',
        reclamo: 'Reclamo',
        sugerencia: 'Sugerencia',
        reporte_danos: 'Reporte de Daños',
        felicitaciones: 'Felicitaciones',
    };
    return labels[type] || type.replace('_', ' ').toUpperCase();
};

const formatShortId = (id: string): string => {
    if (!id) return '';
    return id.substring(0, 8).toUpperCase();
};

const formatDate = (date: string): string => {
    if (!date) return '—';
    return new Date(date).toLocaleDateString('es-CO', {
        day: '2-digit',
        month: 'short',
        year: 'numeric',
    });
};

const clearFilters = () => {
    searchQuery.value = '';
    statusFilter.value = 'todos';
    priorityFilter.value = 'todas';
    typeFilter.value = 'todos';
};

const hasActiveFilters = computed(() => {
    return (
        searchQuery.value.trim() !== '' ||
        statusFilter.value !== 'todos' ||
        priorityFilter.value !== 'todas' ||
        typeFilter.value !== 'todos'
    );
});

const tableColumns = [
    { key: 'radicado', label: 'RADICADO', sortable: false },
    { key: 'residente', label: 'ORIGEN / INMUEBLE', sortable: false },
    { key: 'asunto_tipo', label: 'INCIDENCIA & CATEGORÍA', sortable: false },
    { key: 'prioridad', label: 'PRIORIDAD', sortable: false },
    { key: 'estado', label: 'ESTADO ACTUAL', sortable: false },
    { key: 'acciones', label: '', sortable: false },
];
</script>

<template>
    <Head title="Monitor PQRS — NEXO-PRO" />

    <div class="space-y-6 sm:space-y-10 md:space-y-12 pb-28 sm:pb-32 animate-in fade-in slide-in-from-bottom-4 duration-500">
        
        <!-- Tactical Header -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 sm:gap-8 px-1">
            <div class="space-y-2 sm:space-y-3">
                <div class="flex items-center gap-2.5">
                    <div class="w-1.5 sm:w-2 h-5 sm:h-6 bg-primary rounded-full shadow-[0_0_10px_rgba(var(--primary),0.5)]"></div>
                    <p class="text-[9px] sm:text-[10px] font-black text-on-surface-variant/50 dark:text-white/30 uppercase tracking-[0.3em] italic leading-none">
                        Módulo de Atención y Conciliación
                    </p>
                </div>
                <h2 class="text-3xl sm:text-4xl md:text-5xl font-black text-on-surface dark:text-white tracking-tighter uppercase italic leading-none">
                    Monitor <span class="text-primary italic">PQRS</span>
                </h2>
                <p class="text-[10px] sm:text-[11px] font-bold text-on-surface-variant/50 dark:text-white/20 uppercase tracking-[0.15em] sm:tracking-[0.2em] mt-1 sm:mt-2 leading-relaxed italic">
                    Resolución técnica, atención al residente y cumplimiento de acuerdos de servicio
                </p>
            </div>
            
            <div class="flex items-center gap-3 w-full sm:w-auto">
                <div class="px-4 py-2.5 rounded-2xl bg-primary/10 border border-primary/20 flex items-center gap-3 w-full sm:w-auto justify-between sm:justify-start">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span class="text-[10px] font-black text-primary uppercase tracking-widest">Sincronización en Vivo</span>
                    </div>
                    <span class="text-xs font-black text-primary tabular-nums">{{ filteredPqrs.length }} / {{ pqrs?.length || 0 }}</span>
                </div>
            </div>
        </div>

        <!-- Tactical Real-time Metrics Cards -->
        <section class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-6">
            <StatCard 
                label="SOLICITUDES ABIERTAS" 
                :value="(metrics?.abierto ?? 0).toString()" 
                color-class="!bg-primary" 
                class="!rounded-[1.8rem] sm:!rounded-[2.5rem] border border-outline-variant/10 dark:border-white/5 shadow-xl dark:bg-[#0b0e14]" 
            />
            <StatCard 
                label="EN TRÁMITE / ANÁLISIS" 
                :value="(metrics?.en_proceso ?? 0).toString()" 
                color-class="!bg-warning" 
                class="!rounded-[1.8rem] sm:!rounded-[2.5rem] border border-outline-variant/10 dark:border-white/5 shadow-xl dark:bg-[#0b0e14]" 
            />
            <StatCard 
                label="CRÍTICAS / REABIERTAS" 
                :value="(metrics?.reabierto ?? 0).toString()" 
                color-class="!bg-error" 
                class="!rounded-[1.8rem] sm:!rounded-[2.5rem] border border-outline-variant/10 dark:border-white/5 shadow-xl dark:bg-[#0b0e14]" 
            />
            <div class="col-span-2 sm:col-span-1 p-5 sm:p-6 rounded-[1.8rem] sm:rounded-[2.5rem] bg-emerald-500/10 border border-emerald-500/20 shadow-xl flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-[9px] sm:text-[10px] font-black uppercase tracking-widest text-emerald-600 dark:text-emerald-400">TASA RESOLUCIÓN</span>
                    <span class="material-symbols-rounded text-emerald-500 text-lg">verified</span>
                </div>
                <div class="mt-2 flex items-baseline gap-2">
                    <span class="text-3xl sm:text-4xl font-black text-emerald-600 dark:text-emerald-400 tabular-nums">
                        {{ metrics?.tasa_resolucion ?? 0 }}%
                    </span>
                    <span class="text-[10px] font-bold text-emerald-600/70 dark:text-emerald-400/70 uppercase">
                        ({{ metrics?.cerrado ?? 0 }} resueltos)
                    </span>
                </div>
            </div>
        </section>

        <!-- Reactive Search & Multi-facet Filter Console -->
        <Card class="p-4 sm:p-6 md:p-8 rounded-[2rem] sm:rounded-[2.5rem] border border-outline-variant/10 dark:border-white/5 shadow-xl dark:bg-[#0b0e14] space-y-4">
            <!-- Search input & Secondary Selects -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3 sm:gap-4 items-center">
                <!-- Search Box -->
                <div class="lg:col-span-6 relative">
                    <span class="material-symbols-rounded absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant/40 text-xl pointer-events-none">search</span>
                    <input 
                        v-model="searchQuery" 
                        type="text" 
                        placeholder="Buscar radicado, residente, torre, unidad o asunto..." 
                        class="w-full pl-12 pr-4 py-3 sm:py-3.5 bg-surface-container-low dark:bg-white/[0.03] border border-outline-variant/15 dark:border-white/10 rounded-2xl text-xs sm:text-sm font-medium text-on-surface dark:text-white placeholder:text-on-surface-variant/40 focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all"
                        aria-label="Buscar requerimientos"
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

                <!-- Priority Filter -->
                <div class="lg:col-span-3">
                    <select 
                        v-model="priorityFilter"
                        class="w-full py-3 sm:py-3.5 px-4 bg-surface-container-low dark:bg-white/[0.03] border border-outline-variant/15 dark:border-white/10 rounded-2xl text-xs font-black uppercase tracking-wider text-on-surface dark:text-white focus:outline-none focus:border-primary cursor-pointer"
                        aria-label="Filtrar por prioridad"
                    >
                        <option value="todas">Prioridad: Todas</option>
                        <option value="alta">Prioridad: Alta</option>
                        <option value="media">Prioridad: Media</option>
                        <option value="baja">Prioridad: Baja</option>
                    </select>
                </div>

                <!-- Category / Type Filter -->
                <div class="lg:col-span-3">
                    <select 
                        v-model="typeFilter"
                        class="w-full py-3 sm:py-3.5 px-4 bg-surface-container-low dark:bg-white/[0.03] border border-outline-variant/15 dark:border-white/10 rounded-2xl text-xs font-black uppercase tracking-wider text-on-surface dark:text-white focus:outline-none focus:border-primary cursor-pointer"
                        aria-label="Filtrar por categoría"
                    >
                        <option value="todos">Categoría: Todas</option>
                        <option value="peticion">Peticiones</option>
                        <option value="queja">Quejas</option>
                        <option value="reclamo">Reclamos</option>
                        <option value="sugerencia">Sugerencias</option>
                        <option value="reporte_danos">Reporte Daños</option>
                        <option value="felicitaciones">Felicitaciones</option>
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
                        <span>Todos</span>
                        <span class="px-1.5 py-0.5 rounded-full text-[9px]" :class="statusFilter === 'todos' ? 'bg-white/20 text-white' : 'bg-on-surface-variant/10 text-on-surface-variant'">{{ pqrs?.length || 0 }}</span>
                    </button>
                    <button 
                        @click="statusFilter = 'abierto'"
                        :class="statusFilter === 'abierto' ? 'bg-primary text-white shadow-md shadow-primary/20' : 'bg-surface-container-low dark:bg-white/5 text-on-surface-variant/70 hover:text-on-surface dark:hover:text-white'"
                        class="px-3.5 sm:px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-wider transition-all cursor-pointer flex items-center gap-2"
                    >
                        <span>Abiertos</span>
                        <span class="px-1.5 py-0.5 rounded-full text-[9px]" :class="statusFilter === 'abierto' ? 'bg-white/20 text-white' : 'bg-primary/10 text-primary'">{{ metrics?.abierto ?? 0 }}</span>
                    </button>
                    <button 
                        @click="statusFilter = 'en_proceso'"
                        :class="statusFilter === 'en_proceso' ? 'bg-warning text-white shadow-md shadow-warning/20' : 'bg-surface-container-low dark:bg-white/5 text-on-surface-variant/70 hover:text-on-surface dark:hover:text-white'"
                        class="px-3.5 sm:px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-wider transition-all cursor-pointer flex items-center gap-2"
                    >
                        <span>En Trámite</span>
                        <span class="px-1.5 py-0.5 rounded-full text-[9px]" :class="statusFilter === 'en_proceso' ? 'bg-white/20 text-white' : 'bg-warning/10 text-warning'">{{ metrics?.en_proceso ?? 0 }}</span>
                    </button>
                    <button 
                        @click="statusFilter = 'reabierto'"
                        :class="statusFilter === 'reabierto' ? 'bg-error text-white shadow-md shadow-error/20' : 'bg-surface-container-low dark:bg-white/5 text-on-surface-variant/70 hover:text-on-surface dark:hover:text-white'"
                        class="px-3.5 sm:px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-wider transition-all cursor-pointer flex items-center gap-2"
                    >
                        <span>Críticos</span>
                        <span class="px-1.5 py-0.5 rounded-full text-[9px]" :class="statusFilter === 'reabierto' ? 'bg-white/20 text-white' : 'bg-error/10 text-error'">{{ metrics?.reabierto ?? 0 }}</span>
                    </button>
                    <button 
                        @click="statusFilter = 'cerrado'"
                        :class="statusFilter === 'cerrado' ? 'bg-emerald-600 text-white shadow-md shadow-emerald-600/20' : 'bg-surface-container-low dark:bg-white/5 text-on-surface-variant/70 hover:text-on-surface dark:hover:text-white'"
                        class="px-3.5 sm:px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-wider transition-all cursor-pointer flex items-center gap-2"
                    >
                        <span>Resueltos</span>
                        <span class="px-1.5 py-0.5 rounded-full text-[9px]" :class="statusFilter === 'cerrado' ? 'bg-white/20 text-white' : 'bg-emerald-500/10 text-emerald-600'">{{ metrics?.cerrado ?? 0 }}</span>
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
                        <span class="material-symbols-rounded text-2xl sm:text-3xl">forum</span>
                    </div>
                    <div>
                        <h3 class="text-xl sm:text-2xl font-black text-on-surface dark:text-white uppercase tracking-tighter italic leading-none">
                            Bandeja de <span class="text-primary italic">Requerimientos</span>
                        </h3>
                        <p class="text-[9px] sm:text-[10px] font-bold text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.25em] mt-2 italic">
                            {{ filteredPqrs.length }} solicitudes listadas según los criterios actuales
                        </p>
                    </div>
                </div>
            </div>

            <!-- Empty State -->
            <div v-if="filteredPqrs.length === 0" class="py-16 sm:py-24 text-center px-4">
                <div class="w-16 h-16 sm:w-20 sm:h-20 bg-primary/5 rounded-full flex items-center justify-center mx-auto mb-4 text-primary/40">
                    <span class="material-symbols-rounded text-3xl sm:text-4xl">inbox</span>
                </div>
                <h4 class="text-base sm:text-lg font-black text-on-surface dark:text-white uppercase tracking-tight">
                    No se encontraron solicitudes
                </h4>
                <p class="text-xs text-on-surface-variant/50 dark:text-white/30 max-w-sm mx-auto mt-2 font-medium">
                    {{ hasActiveFilters ? 'Prueba ajustando los términos de búsqueda o los filtros seleccionados.' : 'No hay requerimientos registrados en este momento para la copropiedad.' }}
                </p>
                <button 
                    v-if="hasActiveFilters" 
                    @click="clearFilters" 
                    class="mt-4 px-4 py-2 rounded-xl bg-primary/10 text-primary text-xs font-black uppercase tracking-wider hover:bg-primary/20 transition-all cursor-pointer"
                >
                    Ver Todas las Solicitudes
                </button>
            </div>

            <!-- 1. MOBILE NATIVE INCIDENT CARDS (Visible on Xiaomi 14T and viewports < 768px) -->
            <div v-else class="block md:hidden divide-y divide-outline-variant/10 dark:divide-white/5">
                <article 
                    v-for="item in filteredPqrs" 
                    :key="item.id"
                    class="p-4 sm:p-5 space-y-3.5 hover:bg-surface-container-high/40 dark:hover:bg-white/[0.02] transition-colors"
                >
                    <!-- Top Radicado & Status Row -->
                    <div class="flex items-center justify-between gap-2">
                        <div class="flex items-center gap-2 min-w-0">
                            <span class="px-2.5 py-1 rounded-lg bg-primary/10 text-primary font-black text-[10px] tracking-wider italic">
                                #{{ formatShortId(item.id) }}
                            </span>
                            <span class="text-[9px] font-bold text-on-surface-variant/40 truncate">
                                {{ formatDate(item.created_at) }}
                            </span>
                        </div>
                        <Badge :variant="getStatusVariant(item.estado)" class="!rounded-lg !px-2.5 !py-0.5 text-[8.5px] font-black uppercase tracking-wider shrink-0">
                            {{ getStatusLabel(item.estado) }}
                        </Badge>
                    </div>

                    <!-- Asunto & Tipo Badge -->
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <span class="px-2 py-0.5 bg-on-surface-variant/10 dark:bg-white/10 rounded text-[8px] font-black uppercase tracking-wider text-primary">
                                {{ getTypeLabel(item.tipo) }}
                            </span>
                            <Badge :variant="getPriorityVariant(item.prioridad)" class="!rounded !px-1.5 !py-0.2 text-[8px] font-black uppercase tracking-widest">
                                {{ item.prioridad }}
                            </Badge>
                            <span v-if="item.adjuntos && item.adjuntos.length > 0" class="flex items-center gap-0.5 text-[8px] font-black text-on-surface-variant/50">
                                <span class="material-symbols-rounded text-xs">attach_file</span>
                                {{ item.adjuntos.length }}
                            </span>
                        </div>
                        <h4 class="text-sm font-black text-on-surface dark:text-white uppercase tracking-tight line-clamp-2 leading-snug">
                            {{ item.asunto }}
                        </h4>
                    </div>

                    <!-- Resident & Inmueble Chip -->
                    <div class="flex items-center justify-between p-3 rounded-2xl bg-surface-container-low dark:bg-white/[0.02] border border-outline-variant/10 dark:border-white/5 text-xs">
                        <div class="flex items-center gap-2.5 min-w-0">
                            <div class="w-8 h-8 rounded-xl bg-primary/10 text-primary flex items-center justify-center shrink-0">
                                <span class="material-symbols-rounded text-base">home</span>
                            </div>
                            <div class="min-w-0">
                                <p class="text-[10px] font-black text-on-surface dark:text-white uppercase truncate">
                                    {{ item.unidad ? `Torre ${item.unidad.torre || '—'} · ${item.unidad.nombre}` : 'Sin Unidad' }}
                                </p>
                                <p class="text-[9px] font-medium text-on-surface-variant/60 dark:text-white/40 truncate">
                                    {{ item.user?.name || 'Residente Anónimo' }}
                                </p>
                            </div>
                        </div>

                        <!-- Attachments indicator if any -->
                        <a 
                            :href="getDownloadRoute(item.id)" 
                            class="w-9 h-9 rounded-xl bg-surface-container-high dark:bg-white/5 text-on-surface-variant/70 hover:text-primary flex items-center justify-center shrink-0 border border-outline-variant/10 cursor-pointer"
                            aria-label="Descargar PDF"
                            title="Descargar PDF"
                        >
                            <span class="material-symbols-rounded text-base">picture_as_pdf</span>
                        </a>
                    </div>

                    <!-- Primary Action Button (Tactile >= 44x44px for Xiaomi 14T thumb zone) -->
                    <div class="pt-1">
                        <button 
                            @click="openResponseModal(item)"
                            class="w-full h-12 rounded-xl bg-primary hover:bg-primary-dark text-white font-black text-xs uppercase tracking-wider flex items-center justify-center gap-2 shadow-md shadow-primary/20 active:scale-98 transition-all cursor-pointer"
                            aria-label="Gestionar requerimiento"
                        >
                            <span class="material-symbols-rounded text-lg">rate_review</span>
                            <span>Gestionar / Responder</span>
                        </button>
                    </div>
                </article>
            </div>

            <!-- 2. DESKTOP HIGH-DENSITY DATA TABLE (Visible on md+ screens) -->
            <div class="hidden md:block">
                <Table :columns="tableColumns" :data="filteredPqrs" class="border-t border-outline-variant/5 dark:border-white/5">
                    <template #cell-radicado="{ row }">
                        <div class="flex flex-col py-2">
                            <span class="text-sm font-black text-primary tracking-tighter italic whitespace-nowrap">
                                #{{ formatShortId(row.id) }}
                            </span>
                            <span class="text-[9px] text-on-surface-variant/50 dark:text-white/30 font-black uppercase tracking-widest mt-0.5 italic">
                                {{ formatDate(row.created_at) }}
                            </span>
                        </div>
                    </template>

                    <template #cell-residente="{ row }">
                        <div class="flex items-center gap-3.5 py-2">
                            <div class="w-10 h-10 rounded-2xl bg-surface-container-high dark:bg-white/5 flex items-center justify-center text-xs font-black text-primary border border-outline-variant/10 shadow-sm shrink-0">
                                <span class="material-symbols-rounded text-lg">person_pin_circle</span>
                            </div>
                            <div class="flex flex-col min-w-0">
                                <span class="text-xs font-black text-on-surface dark:text-white uppercase italic tracking-tighter truncate leading-none">
                                    {{ row.unidad ? `TORRE ${row.unidad.torre || '—'} · ${row.unidad.nombre}` : 'SIN UNIDAD' }}
                                </span>
                                <span class="text-[9px] font-bold text-on-surface-variant/50 dark:text-white/30 uppercase tracking-widest mt-1.5 truncate">
                                    {{ row.user?.name || 'Residente' }}
                                </span>
                            </div>
                        </div>
                    </template>

                    <template #cell-asunto_tipo="{ row }">
                        <div class="flex flex-col py-2 max-w-xs">
                            <span class="text-xs font-black text-on-surface dark:text-white uppercase tracking-tight italic truncate">
                                {{ row.asunto }}
                            </span>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="text-[8px] text-primary font-black uppercase tracking-[0.2em] px-2 py-0.5 bg-primary/10 rounded-md inline-block">
                                    {{ getTypeLabel(row.tipo) }}
                                </span>
                                <span v-if="row.adjuntos?.length" class="text-[8px] font-bold text-on-surface-variant/40 flex items-center gap-0.5">
                                    <span class="material-symbols-rounded text-xs">attach_file</span>
                                    {{ row.adjuntos.length }}
                                </span>
                            </div>
                        </div>
                    </template>

                    <template #cell-prioridad="{ row }">
                        <div class="flex justify-center">
                            <Badge :variant="getPriorityVariant(row.prioridad)" class="!px-3.5 !py-1 !font-black !text-[9px] tracking-widest uppercase italic">
                                {{ row.prioridad }}
                            </Badge>
                        </div>
                    </template>

                    <template #cell-estado="{ row }">
                        <div class="flex justify-center">
                            <Badge :variant="getStatusVariant(row.estado)" class="!px-3.5 !py-1 !font-black !text-[8.5px] tracking-widest uppercase italic border">
                                {{ getStatusLabel(row.estado) }}
                            </Badge>
                        </div>
                    </template>

                    <template #cell-acciones="{ row }">
                        <div class="flex items-center justify-end gap-2 pr-6">
                            <a 
                                :href="getDownloadRoute(row.id)" 
                                class="w-10 h-10 rounded-xl bg-surface-container-high dark:bg-white/5 hover:bg-primary/10 text-on-surface-variant hover:text-primary flex items-center justify-center transition-all border border-outline-variant/10 cursor-pointer"
                                title="Descargar Expediente PDF"
                                aria-label="Descargar PDF"
                            >
                                <span class="material-symbols-rounded text-lg">download</span>
                            </a>
                            <Button 
                                variant="primary" 
                                size="sm" 
                                icon="rate_review" 
                                class="!rounded-xl !h-10 !px-4 !text-[10px] font-black uppercase italic shadow-md shadow-primary/10 cursor-pointer"
                                @click="openResponseModal(row)"
                            >
                                Gestionar
                            </Button>
                        </div>
                    </template>
                </Table>
            </div>
        </Card>

        <!-- Incident Resolution Console (Modal) -->
        <Modal 
            v-if="selectedPqrs" 
            @close="selectedPqrs = null"
            class="!max-w-2xl"
        >
            <template #title>
                <div class="flex items-center gap-3 sm:gap-4">
                    <div class="w-11 h-11 sm:w-12 sm:h-12 bg-primary/10 rounded-2xl flex items-center justify-center text-primary border border-primary/20 shrink-0">
                        <span class="material-symbols-rounded text-2xl">mark_chat_read</span>
                    </div>
                    <div class="min-w-0">
                        <h3 class="text-xl sm:text-2xl font-black text-on-surface dark:text-white uppercase tracking-tighter italic leading-none truncate">
                            Gestión <span class="text-primary italic">#{{ formatShortId(selectedPqrs.id) }}</span>
                        </h3>
                        <p class="text-[9px] font-bold text-on-surface-variant/50 dark:text-white/30 uppercase tracking-widest mt-1 truncate">
                            {{ selectedPqrs.unidad ? `Torre ${selectedPqrs.unidad.torre || '—'} · ${selectedPqrs.unidad.nombre}` : 'Inmueble' }} — {{ selectedPqrs.user?.name || 'Residente' }}
                        </p>
                    </div>
                </div>
            </template>

            <div class="space-y-6 mt-6 pb-2">
                <!-- Incident Statement Box -->
                <div class="p-5 sm:p-6 rounded-[2rem] bg-surface-container-low dark:bg-white/[0.03] border border-outline-variant/10 dark:border-white/5 space-y-3">
                    <div class="flex items-center justify-between gap-2">
                        <span class="text-[9px] font-black text-primary uppercase tracking-[0.2em] italic">
                            {{ getTypeLabel(selectedPqrs.tipo) }} · {{ formatDate(selectedPqrs.created_at) }}
                        </span>
                        <Badge :variant="getPriorityVariant(selectedPqrs.prioridad)" class="!rounded text-[8px] font-black uppercase">
                            Prioridad {{ selectedPqrs.prioridad }}
                        </Badge>
                    </div>

                    <h4 class="text-sm sm:text-base font-black text-on-surface dark:text-white uppercase tracking-tight">
                        {{ selectedPqrs.asunto }}
                    </h4>

                    <p class="text-xs sm:text-sm font-medium text-on-surface-variant/80 dark:text-white/70 leading-relaxed italic border-l-2 border-primary/40 pl-3">
                        "{{ selectedPqrs.mensaje }}"
                    </p>

                    <!-- Attachments Display -->
                    <div v-if="selectedPqrs.adjuntos && selectedPqrs.adjuntos.length > 0" class="pt-2 border-t border-outline-variant/10 dark:border-white/5">
                        <p class="text-[9px] font-black text-on-surface-variant/50 uppercase tracking-widest mb-2">Archivos Adjuntos del Residente:</p>
                        <div class="flex flex-wrap gap-2">
                            <a 
                                v-for="(adjunto, index) in selectedPqrs.adjuntos" 
                                :key="index"
                                :href="`/storage/${adjunto}`"
                                target="_blank"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-surface-container-high dark:bg-white/5 hover:bg-primary/10 text-on-surface text-[10px] font-bold border border-outline-variant/10 transition-colors"
                            >
                                <span class="material-symbols-rounded text-sm text-primary">attachment</span>
                                <span>Adjunto {{ index + 1 }}</span>
                                <span class="material-symbols-rounded text-xs text-on-surface-variant/40">open_in_new</span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Official Response Form -->
                <form @submit.prevent="submitResponse" class="space-y-4">
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <label for="pqrs-respuesta" class="block text-xs font-black text-on-surface dark:text-white uppercase tracking-wider">
                                Respuesta Oficial de Administración
                            </label>
                            <span class="text-[9px] font-bold text-on-surface-variant/40">
                                {{ form.respuesta.length }} caracteres
                            </span>
                        </div>

                        <!-- Canned Preset Answers (One-touch on mobile) -->
                        <div class="flex flex-wrap gap-1.5 pb-1">
                            <button 
                                type="button" 
                                @click="applyPresetReply('Se programó inspección técnica en sitio para verificar la novedad.')"
                                class="px-2.5 py-1 rounded-lg bg-surface-container-high dark:bg-white/5 hover:bg-primary/10 text-[9px] font-bold text-on-surface-variant/70 hover:text-primary transition-all border border-outline-variant/10 cursor-pointer"
                            >
                                + Inspección técnica
                            </button>
                            <button 
                                type="button" 
                                @click="applyPresetReply('Incidencia atendida por el equipo operativo y resuelta a conformidad.')"
                                class="px-2.5 py-1 rounded-lg bg-surface-container-high dark:bg-white/5 hover:bg-primary/10 text-[9px] font-bold text-on-surface-variant/70 hover:text-primary transition-all border border-outline-variant/10 cursor-pointer"
                            >
                                + Caso resuelto
                            </button>
                            <button 
                                type="button" 
                                @click="applyPresetReply('Se solicita información adicional o evidencia complementaria para continuar.')"
                                class="px-2.5 py-1 rounded-lg bg-surface-container-high dark:bg-white/5 hover:bg-primary/10 text-[9px] font-bold text-on-surface-variant/70 hover:text-primary transition-all border border-outline-variant/10 cursor-pointer"
                            >
                                + Solicitar evidencia
                            </button>
                        </div>

                        <textarea 
                            id="pqrs-respuesta"
                            v-model="form.respuesta" 
                            rows="4" 
                            class="w-full bg-surface-container-low dark:bg-white/[0.03] border border-outline-variant/20 dark:border-white/10 rounded-2xl p-4 text-xs sm:text-sm font-medium text-on-surface dark:text-white focus:bg-surface dark:focus:bg-[#0b0e14] focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all placeholder:text-on-surface-variant/40"
                            placeholder="Redacta la respuesta formal y detallada para el residente..."
                            required
                        ></textarea>
                    </div>

                    <!-- Close/Resolve Checkbox Card -->
                    <label 
                        for="cerrar-pqrs-check" 
                        class="flex items-center gap-3.5 p-4 rounded-2xl bg-surface-container-low dark:bg-white/[0.02] border border-outline-variant/15 dark:border-white/10 cursor-pointer hover:border-primary/40 transition-colors select-none"
                    >
                        <input 
                            id="cerrar-pqrs-check"
                            type="checkbox" 
                            v-model="form.cerrar" 
                            class="w-5 h-5 rounded-lg border-outline-variant/30 text-primary focus:ring-primary cursor-pointer shrink-0" 
                        />
                        <div class="min-w-0">
                            <p class="text-xs font-black uppercase text-on-surface dark:text-white tracking-wider">
                                Marcar Requerimiento como Resuelto / Cerrado
                            </p>
                            <p class="text-[9px] font-medium text-on-surface-variant/60 dark:text-white/40 mt-0.5">
                                Cambia el estado a 'Caso Resuelto' y envía notificación al correo del copropietario.
                            </p>
                        </div>
                    </label>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row items-center gap-3 pt-3 border-t border-outline-variant/10 dark:border-white/5">
                        <Button 
                            type="submit" 
                            variant="primary" 
                            size="lg" 
                            class="w-full sm:flex-1 !h-12 !rounded-xl font-black text-xs uppercase tracking-wider shadow-lg shadow-primary/20 cursor-pointer"
                            :disabled="form.processing"
                            :loading="form.processing"
                            icon="send"
                        >
                            {{ form.processing ? 'Registrando...' : 'Emitir Respuesta Oficial' }}
                        </Button>
                        <a 
                            :href="getDownloadRoute(selectedPqrs.id)"
                            target="_blank"
                            class="w-full sm:w-auto h-12 px-5 rounded-xl bg-surface-container-high dark:bg-white/5 hover:bg-primary/10 text-on-surface hover:text-primary font-black text-xs uppercase tracking-wider border border-outline-variant/15 flex items-center justify-center gap-2 transition-all cursor-pointer"
                        >
                            <span class="material-symbols-rounded text-lg">download</span>
                            <span>Expediente PDF</span>
                        </a>
                        <Button 
                            type="button" 
                            variant="ghost" 
                            class="w-full sm:w-auto text-on-surface-variant/60 hover:text-on-surface cursor-pointer" 
                            @click="selectedPqrs = null"
                        >
                            Cancelar
                        </Button>
                    </div>
                </form>
            </div>
        </Modal>
    </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue';
import { Head, useForm, router, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Card from '@/Components/UI/Card.vue';
import Button from '@/Components/UI/Button.vue';
import Badge from '@/Components/UI/Badge.vue';
import Modal from '@/Components/UI/Modal.vue';
import ConfirmModal from '@/Components/UI/ConfirmModal.vue';
import Input from '@/Components/UI/Input.vue';
import { useToast } from '@/Composables/useToast';

defineOptions({ layout: AuthenticatedLayout });

const props = defineProps<{
    zonas: Array<any>;
    stats?: {
        total_zonas: number;
        zonas_activas: number;
        zonas_inactivas: number;
        aforo_total_pax: number;
        porcentaje_operatividad: number;
        reservas_mes: number;
    };
}>();

const toast = useToast();
const showModal = ref(false);
const showDeleteConfirm = ref(false);
const editingZona = ref<any>(null);
const zoneToDelete = ref<any>(null);
const isDeleting = ref(false);

// Filtros y Búsqueda Reactiva
const searchQuery = ref('');
const activeFilter = ref<'all' | 'active' | 'inactive' | 'free' | 'paid'>('all');

// Catálogo de tipos de espacio con diseño premium
const zoneTypes = [
    { id: 'salon', label: 'Salón Social', icon: 'celebration', gradient: 'from-purple-500/20 to-indigo-500/20', iconColor: 'text-purple-400', border: 'border-purple-500/30' },
    { id: 'bbq', label: 'Zona BBQ / Parrilla', icon: 'outdoor_grill', gradient: 'from-amber-500/20 to-orange-500/20', iconColor: 'text-amber-400', border: 'border-amber-500/30' },
    { id: 'piscina', label: 'Piscina / Húmedas', icon: 'pool', gradient: 'from-cyan-500/20 to-blue-500/20', iconColor: 'text-cyan-400', border: 'border-cyan-500/30' },
    { id: 'gym', label: 'Gimnasio / Fitness', icon: 'fitness_center', gradient: 'from-emerald-500/20 to-teal-500/20', iconColor: 'text-emerald-400', border: 'border-emerald-500/30' },
    { id: 'cancha', label: 'Cancha / Deportes', icon: 'sports_soccer', gradient: 'from-rose-500/20 to-red-500/20', iconColor: 'text-rose-400', border: 'border-rose-500/30' },
    { id: 'coworking', label: 'Coworking / Sala', icon: 'laptop_chromebook', gradient: 'from-blue-500/20 to-sky-500/20', iconColor: 'text-blue-400', border: 'border-blue-500/30' },
    { id: 'parque', label: 'Zona Infantil', icon: 'toys', gradient: 'from-pink-500/20 to-orange-500/20', iconColor: 'text-pink-400', border: 'border-pink-500/30' },
    { id: 'otro', label: 'Otro Espacio', icon: 'apartment', gradient: 'from-slate-500/20 to-zinc-500/20', iconColor: 'text-slate-300', border: 'border-slate-500/30' },
];

const getZoneTypeConfig = (zona: any) => {
    const rawType = zona.settings?.tipo;
    if (rawType) {
        const found = zoneTypes.find(t => t.id === rawType);
        if (found) return found;
    }
    const nameLower = (zona.nombre || '').toLowerCase();
    if (nameLower.includes('piscina') || nameLower.includes('jacuzzi') || nameLower.includes('turco') || nameLower.includes('sauna')) {
        return zoneTypes[2];
    }
    if (nameLower.includes('bbq') || nameLower.includes('parrilla') || nameLower.includes('asador')) {
        return zoneTypes[1];
    }
    if (nameLower.includes('gym') || nameLower.includes('gimnasio') || nameLower.includes('fitness')) {
        return zoneTypes[3];
    }
    if (nameLower.includes('cancha') || nameLower.includes('tenis') || nameLower.includes('futbol') || nameLower.includes('squash')) {
        return zoneTypes[4];
    }
    if (nameLower.includes('cowork') || nameLower.includes('estudio') || nameLower.includes('reuniones') || nameLower.includes('juntas')) {
        return zoneTypes[5];
    }
    if (nameLower.includes('infantil') || nameLower.includes('juegos') || nameLower.includes('parque')) {
        return zoneTypes[6];
    }
    if (nameLower.includes('salon') || nameLower.includes('social') || nameLower.includes('comunal') || nameLower.includes('eventos')) {
        return zoneTypes[0];
    }
    return zoneTypes[7];
};

const form = useForm({
    nombre: '',
    descripcion: '',
    capacidad_maxima: 20,
    costo: 0,
    activa: true,
    imagen_path: '',
    settings: {
        tipo: 'salon',
        horario_apertura: '08:00',
        horario_cierre: '22:00',
        tiempo_maximo_horas: 4,
        deposito_garantia: 0,
        requiere_aprobacion: true,
    }
});

const openCreateModal = () => {
    editingZona.value = null;
    form.reset();
    form.settings = {
        tipo: 'salon',
        horario_apertura: '08:00',
        horario_cierre: '22:00',
        tiempo_maximo_horas: 4,
        deposito_garantia: 0,
        requiere_aprobacion: true,
    };
    form.capacidad_maxima = 20;
    form.costo = 0;
    form.activa = true;
    showModal.value = true;
};

const openEditModal = (zona: any) => {
    editingZona.value = zona;
    form.nombre = zona.nombre;
    form.descripcion = zona.descripcion || '';
    form.capacidad_maxima = zona.capacidad_maxima;
    form.costo = Number(zona.costo) || 0;
    form.activa = Boolean(zona.activa);
    form.imagen_path = zona.imagen_path || '';
    form.settings = {
        tipo: zona.settings?.tipo || getZoneTypeConfig(zona).id,
        horario_apertura: zona.settings?.horario_apertura || '08:00',
        horario_cierre: zona.settings?.horario_cierre || '22:00',
        tiempo_maximo_horas: zona.settings?.tiempo_maximo_horas ?? 4,
        deposito_garantia: Number(zona.settings?.deposito_garantia) || 0,
        requiere_aprobacion: zona.settings?.requiere_aprobacion ?? true,
    };
    showModal.value = true;
};

const submitForm = () => {
    if (!form.nombre.trim()) {
        toast.add('El nombre del espacio es obligatorio.', 'danger');
        return;
    }

    if (editingZona.value) {
        form.patch(route('admin.zonas.update', editingZona.value.id), {
            preserveScroll: true,
            onSuccess: () => {
                showModal.value = false;
                toast.add(`Zona '${form.nombre}' actualizada exitosamente.`, 'success');
            },
        });
    } else {
        form.post(route('admin.zonas.store'), {
            preserveScroll: true,
            onSuccess: () => {
                showModal.value = false;
                toast.add(`Zona '${form.nombre}' registrada e integrada al catálogo.`, 'success');
            },
        });
    }
};

const toggleStatus = (zona: any) => {
    router.patch(route('admin.zonas.toggle', zona.id), {}, {
        preserveScroll: true,
        onSuccess: () => {
            const nuevoEstado = !zona.activa ? 'Operativa para reservas' : 'Pausada por Mantenimiento';
            toast.add(`'${zona.nombre}' ahora está ${nuevoEstado}.`, 'primary');
        }
    });
};

const openDeleteModal = (zona: any) => {
    zoneToDelete.value = zona;
    showDeleteConfirm.value = true;
};

const executeDelete = () => {
    if (!zoneToDelete.value) return;
    isDeleting.value = true;
    router.delete(route('admin.zonas.destroy', zoneToDelete.value.id), {
        preserveScroll: true,
        onSuccess: () => {
            showDeleteConfirm.value = false;
            zoneToDelete.value = null;
            toast.add('Zona común retirada del inventario.', 'success');
        },
        onError: () => {
            showDeleteConfirm.value = false;
        },
        onFinish: () => {
            isDeleting.value = false;
        }
    });
};

const filteredZonas = computed(() => {
    return props.zonas.filter((zona) => {
        // Filtro por búsqueda de texto
        const query = searchQuery.value.toLowerCase().trim();
        const matchesQuery = !query || 
            zona.nombre.toLowerCase().includes(query) ||
            (zona.descripcion && zona.descripcion.toLowerCase().includes(query)) ||
            (getZoneTypeConfig(zona).label.toLowerCase().includes(query));

        if (!matchesQuery) return false;

        // Filtro por chip
        if (activeFilter.value === 'active') return zona.activa;
        if (activeFilter.value === 'inactive') return !zona.activa;
        if (activeFilter.value === 'free') return Number(zona.costo) === 0;
        if (activeFilter.value === 'paid') return Number(zona.costo) > 0;

        return true;
    });
});

const formatCurrency = (value: number) => {
    return new Intl.NumberFormat('es-CO', {
        style: 'currency',
        currency: 'COP',
        maximumFractionDigits: 0
    }).format(value);
};
</script>

<template>
    <Head title="Zonas Comunes — NEXO-PRO" />

    <div class="space-y-6 sm:space-y-10 pb-32 animate-in fade-in slide-in-from-bottom-4 duration-700">
        
        <!-- Header Táctico y Acciones Rápidas -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 px-1">
            <div class="space-y-2 sm:space-y-3">
                <div class="flex items-center gap-2.5">
                    <div class="w-1.5 sm:w-2 h-5 sm:h-6 bg-primary rounded-full shadow-[0_0_10px_rgba(var(--primary),0.4)]"></div>
                    <p class="text-[9px] sm:text-[10px] font-black text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.3em] sm:tracking-[0.4em] italic leading-none">
                        Infraestructura & Amenidades Colectivas
                    </p>
                </div>
                <h2 class="text-3xl sm:text-4xl md:text-5xl font-black text-on-surface dark:text-white tracking-tighter uppercase italic leading-none">
                    Zonas <span class="text-primary italic">Comunes</span>
                </h2>
                <p class="text-[10px] sm:text-[11px] font-bold text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.15em] sm:tracking-[0.2em] mt-1 sm:mt-2 leading-relaxed italic">
                    Control de disponibilidad, aforo máximo, tarifas de uso y reglas de reserva del conjunto
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-2.5 sm:gap-3">
                <Link 
                    :href="route('admin.reservas.index')" 
                    class="h-11 sm:h-14 px-5 sm:px-6 rounded-2xl bg-surface-container hover:bg-surface-container-high border border-outline-variant/10 flex items-center justify-center gap-2 text-[10px] sm:text-[11px] font-black uppercase italic text-on-surface-variant hover:text-on-surface transition-all active:scale-95 cursor-pointer"
                >
                    <span class="material-symbols-rounded text-base sm:text-lg text-primary">calendar_month</span>
                    <span>Ver Reservas</span>
                </Link>

                <Button 
                    variant="primary" 
                    size="lg" 
                    icon="add_circle" 
                    class="!h-11 sm:!h-14 !px-6 sm:!px-8 !rounded-2xl shadow-xl shadow-primary/20 text-[10px] sm:text-[11px] font-black uppercase italic cursor-pointer w-full sm:w-auto justify-center" 
                    @click="openCreateModal"
                >
                    Registrar Nueva Zona
                </Button>
            </div>
        </div>

        <!-- Indicadores Tácticos de Alto Impacto (Live KPIs) -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-6">
            <Card class="!p-4 sm:!p-6 !rounded-2xl sm:!rounded-3xl border border-outline-variant/10 dark:border-white/5 shadow-xl dark:bg-[#0b0e14] group hover:scale-[1.01] transition-all">
                <p class="text-[8px] sm:text-[9.5px] font-black text-on-surface-variant/50 dark:text-white/30 uppercase tracking-[0.2em] mb-2 sm:mb-4 italic leading-none">Total Espacios</p>
                <div class="flex items-center justify-between">
                    <h3 class="text-2xl sm:text-4xl font-black text-on-surface dark:text-white tracking-tighter leading-none italic">
                        {{ stats?.total_zonas ?? zonas.length }}
                    </h3>
                    <div class="w-9 h-9 sm:w-12 sm:h-12 bg-primary/10 rounded-xl sm:rounded-2xl flex items-center justify-center text-primary shrink-0">
                        <span class="material-symbols-rounded text-xl sm:text-2xl">apartment</span>
                    </div>
                </div>
                <div class="mt-3 sm:mt-5 flex items-center gap-1.5">
                    <div class="w-1.5 h-1.5 rounded-full bg-primary"></div>
                    <p class="text-[8px] sm:text-[9px] text-on-surface-variant/40 dark:text-white/20 font-black uppercase tracking-widest italic truncate">Inventario Certificado</p>
                </div>
            </Card>

            <Card class="!p-4 sm:!p-6 !rounded-2xl sm:!rounded-3xl border border-outline-variant/10 dark:border-white/5 shadow-xl dark:bg-[#0b0e14] group hover:scale-[1.01] transition-all">
                <p class="text-[8px] sm:text-[9.5px] font-black text-on-surface-variant/50 dark:text-white/30 uppercase tracking-[0.2em] mb-2 sm:mb-4 italic leading-none">Zonas Operativas</p>
                <div class="flex items-center justify-between">
                    <h3 class="text-2xl sm:text-4xl font-black text-emerald-500 tracking-tighter leading-none italic">
                        {{ stats?.zonas_activas ?? zonas.filter(z => z.activa).length }}
                    </h3>
                    <div class="w-9 h-9 sm:w-12 sm:h-12 bg-emerald-500/10 rounded-xl sm:rounded-2xl flex items-center justify-center text-emerald-500 shrink-0 border border-emerald-500/20">
                        <span class="material-symbols-rounded text-xl sm:text-2xl">check_circle</span>
                    </div>
                </div>
                <div class="mt-3 sm:mt-5 flex items-center gap-1.5">
                    <div class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></div>
                    <p class="text-[8px] sm:text-[9px] text-emerald-500 font-black uppercase tracking-widest italic truncate">
                        {{ stats?.porcentaje_operatividad ?? 100 }}% Habilitadas
                    </p>
                </div>
            </Card>

            <Card class="!p-4 sm:!p-6 !rounded-2xl sm:!rounded-3xl border border-outline-variant/10 dark:border-white/5 shadow-xl dark:bg-[#0b0e14] group hover:scale-[1.01] transition-all">
                <p class="text-[8px] sm:text-[9.5px] font-black text-on-surface-variant/50 dark:text-white/30 uppercase tracking-[0.2em] mb-2 sm:mb-4 italic leading-none">En Mantenimiento</p>
                <div class="flex items-center justify-between">
                    <h3 class="text-2xl sm:text-4xl font-black text-warning tracking-tighter leading-none italic">
                        {{ stats?.zonas_inactivas ?? zonas.filter(z => !z.activa).length }}
                    </h3>
                    <div class="w-9 h-9 sm:w-12 sm:h-12 bg-warning/10 rounded-xl sm:rounded-2xl flex items-center justify-center text-warning shrink-0 border border-warning/20">
                        <span class="material-symbols-rounded text-xl sm:text-2xl">construction</span>
                    </div>
                </div>
                <div class="mt-3 sm:mt-5 flex items-center gap-1.5">
                    <div class="w-1.5 h-1.5 rounded-full bg-warning"></div>
                    <p class="text-[8px] sm:text-[9px] text-on-surface-variant/40 dark:text-white/20 font-black uppercase tracking-widest italic truncate">Pausadas Temporalmente</p>
                </div>
            </Card>

            <Card class="!p-4 sm:!p-6 !rounded-2xl sm:!rounded-3xl border border-outline-variant/10 dark:border-white/5 shadow-xl dark:bg-[#0b0e14] group hover:scale-[1.01] transition-all">
                <p class="text-[8px] sm:text-[9.5px] font-black text-on-surface-variant/50 dark:text-white/30 uppercase tracking-[0.2em] mb-2 sm:mb-4 italic leading-none">Aforo Global PAX</p>
                <div class="flex items-center justify-between">
                    <h3 class="text-2xl sm:text-4xl font-black text-primary tracking-tighter leading-none italic">
                        {{ stats?.aforo_total_pax ?? zonas.reduce((acc, z) => acc + (z.capacidad_maxima || 0), 0) }}
                    </h3>
                    <div class="w-9 h-9 sm:w-12 sm:h-12 bg-primary/10 rounded-xl sm:rounded-2xl flex items-center justify-center text-primary shrink-0">
                        <span class="material-symbols-rounded text-xl sm:text-2xl">groups</span>
                    </div>
                </div>
                <div class="mt-3 sm:mt-5 flex items-center gap-1.5">
                    <div class="w-1.5 h-1.5 rounded-full bg-primary animate-pulse"></div>
                    <p class="text-[8px] sm:text-[9px] text-on-surface-variant/40 dark:text-white/20 font-black uppercase tracking-widest italic truncate">Capacidad Simultánea</p>
                </div>
            </Card>
        </div>

        <!-- Barra Reactiva de Búsqueda y Filtros Rápidos -->
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3 sm:gap-4 bg-surface-container-low/40 dark:bg-white/[0.02] p-2.5 sm:p-3 rounded-2xl sm:rounded-3xl border border-outline-variant/10">
            <!-- Buscador en tiempo real -->
            <div class="relative flex-1">
                <span class="material-symbols-rounded absolute left-3.5 top-1/2 -translate-y-1/2 text-on-surface-variant/40 text-lg sm:text-xl">search</span>
                <input 
                    v-model="searchQuery" 
                    type="text" 
                    placeholder="Buscar zona por nombre, amenidad o descripción..."
                    class="w-full pl-10 pr-4 py-2.5 sm:py-3 bg-surface dark:bg-[#0b0e14] border border-outline-variant/15 dark:border-white/10 rounded-xl sm:rounded-2xl text-xs sm:text-sm font-medium text-on-surface dark:text-white placeholder:text-on-surface-variant/40 focus:outline-none focus:border-primary transition-all"
                />
                <button 
                    v-if="searchQuery" 
                    @click="searchQuery = ''"
                    class="absolute right-3.5 top-1/2 -translate-y-1/2 text-on-surface-variant/40 hover:text-on-surface cursor-pointer"
                >
                    <span class="material-symbols-rounded text-base">close</span>
                </button>
            </div>

            <!-- Chips de Filtro Rápido -->
            <div class="flex items-center gap-1.5 overflow-x-auto no-scrollbar pb-1 sm:pb-0">
                <button 
                    @click="activeFilter = 'all'"
                    class="px-3.5 py-2 rounded-xl text-[9px] sm:text-[10px] font-black uppercase tracking-wider transition-all whitespace-nowrap cursor-pointer select-none"
                    :class="activeFilter === 'all' ? 'bg-primary text-white shadow-md' : 'bg-surface dark:bg-[#0b0e14] text-on-surface-variant/70 border border-outline-variant/10 hover:text-on-surface'"
                >
                    Todas ({{ zonas.length }})
                </button>
                <button 
                    @click="activeFilter = 'active'"
                    class="px-3.5 py-2 rounded-xl text-[9px] sm:text-[10px] font-black uppercase tracking-wider transition-all whitespace-nowrap cursor-pointer select-none flex items-center gap-1.5"
                    :class="activeFilter === 'active' ? 'bg-emerald-500 text-white shadow-md' : 'bg-surface dark:bg-[#0b0e14] text-on-surface-variant/70 border border-outline-variant/10 hover:text-emerald-500'"
                >
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                    Operativas
                </button>
                <button 
                    @click="activeFilter = 'inactive'"
                    class="px-3.5 py-2 rounded-xl text-[9px] sm:text-[10px] font-black uppercase tracking-wider transition-all whitespace-nowrap cursor-pointer select-none flex items-center gap-1.5"
                    :class="activeFilter === 'inactive' ? 'bg-warning text-white shadow-md' : 'bg-surface dark:bg-[#0b0e14] text-on-surface-variant/70 border border-outline-variant/10 hover:text-warning'"
                >
                    <span class="w-1.5 h-1.5 rounded-full bg-warning"></span>
                    Mantenimiento
                </button>
                <button 
                    @click="activeFilter = 'free'"
                    class="px-3.5 py-2 rounded-xl text-[9px] sm:text-[10px] font-black uppercase tracking-wider transition-all whitespace-nowrap cursor-pointer select-none"
                    :class="activeFilter === 'free' ? 'bg-primary text-white shadow-md' : 'bg-surface dark:bg-[#0b0e14] text-on-surface-variant/70 border border-outline-variant/10 hover:text-on-surface'"
                >
                    Gratuitas
                </button>
            </div>
        </div>

        <!-- Grid de Activos e Infraestructura Común -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 sm:gap-8">
            <Card 
                v-for="zona in filteredZonas" 
                :key="zona.id"
                class="!p-0 rounded-[2rem] sm:rounded-[2.5rem] overflow-hidden border border-outline-variant/10 dark:border-white/5 transition-all duration-300 hover:shadow-2xl hover:-translate-y-1 dark:bg-[#0b0e14] group flex flex-col justify-between"
            >
                <div>
                    <!-- Visual Banner Header con Icono/Gradiente Especializado -->
                    <div 
                        class="relative h-44 sm:h-52 overflow-hidden flex items-center justify-center bg-gradient-to-br"
                        :class="[getZoneTypeConfig(zona).gradient]"
                    >
                        <!-- Si tiene foto se muestra, si no se muestra ilustración estilizada -->
                        <img 
                            v-if="zona.imagen_path" 
                            :src="zona.imagen_path" 
                            :alt="zona.nombre"
                            class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
                        />
                        <div v-else class="flex flex-col items-center justify-center p-6 text-center space-y-2 select-none">
                            <div 
                                class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl sm:rounded-3xl flex items-center justify-center shadow-inner border backdrop-blur-md"
                                :class="[getZoneTypeConfig(zona).border, getZoneTypeConfig(zona).iconColor, 'bg-white/10 dark:bg-black/20']"
                            >
                                <span class="material-symbols-rounded text-3xl sm:text-4xl">
                                    {{ getZoneTypeConfig(zona).icon }}
                                </span>
                            </div>
                            <span class="text-[9px] font-black uppercase tracking-[0.25em] text-on-surface-variant/60 dark:text-white/40">
                                {{ getZoneTypeConfig(zona).label }}
                            </span>
                        </div>

                        <!-- Badge de Estado Operativo (Top Left) -->
                        <div class="absolute top-3 sm:top-4 left-3 sm:left-4 z-20">
                            <div 
                                class="flex items-center gap-1.5 sm:gap-2 px-3 py-1.5 rounded-xl backdrop-blur-md border shadow-lg"
                                :class="zona.activa ? 'bg-emerald-500/20 text-emerald-400 border-emerald-500/30' : 'bg-warning/20 text-warning border-warning/30'"
                            >
                                <div class="w-2 h-2 rounded-full" :class="zona.activa ? 'bg-emerald-400 animate-pulse' : 'bg-warning'"></div>
                                <span class="text-[8.5px] sm:text-[9px] font-black uppercase tracking-wider leading-none">
                                    {{ zona.activa ? 'Operativa' : 'Mantenimiento' }}
                                </span>
                            </div>
                        </div>

                        <!-- Badge de Tarifa (Top Right) -->
                        <div class="absolute top-3 sm:top-4 right-3 sm:right-4 z-20">
                            <div class="px-3 py-1.5 rounded-xl bg-slate-950/80 backdrop-blur-md border border-white/15 text-white shadow-xl text-right">
                                <span class="text-[10px] sm:text-[11px] font-black tracking-tight block leading-none">
                                    {{ Number(zona.costo) > 0 ? formatCurrency(Number(zona.costo)) : 'USO GRATUITO' }}
                                </span>
                            </div>
                        </div>

                        <!-- Pill de Reservas Activas (Bottom Left) -->
                        <div v-if="zona.reservas_activas_count" class="absolute bottom-3 left-3 z-20">
                            <span class="px-2.5 py-1 rounded-lg bg-primary/90 text-white text-[8px] font-black uppercase tracking-wider shadow-md">
                                📅 {{ zona.reservas_activas_count }} reservas agendadas
                            </span>
                        </div>
                    </div>

                    <!-- Datos y Reglas de la Zona -->
                    <div class="p-5 sm:p-7 space-y-4">
                        <div class="flex items-start justify-between gap-2">
                            <div>
                                <h3 class="text-lg sm:text-xl font-black text-on-surface dark:text-white uppercase tracking-tight italic leading-tight group-hover:text-primary transition-colors">
                                    {{ zona.nombre }}
                                </h3>
                                <div class="flex flex-wrap items-center gap-3 mt-2">
                                    <div class="flex items-center gap-1 text-[9.5px] font-bold text-on-surface-variant/70 dark:text-white/50">
                                        <span class="material-symbols-rounded text-sm text-primary">groups</span>
                                        <span>{{ zona.capacidad_maxima }} PAX Máx</span>
                                    </div>
                                    <div class="flex items-center gap-1 text-[9.5px] font-bold text-on-surface-variant/70 dark:text-white/50">
                                        <span class="material-symbols-rounded text-sm text-primary">schedule</span>
                                        <span>{{ zona.settings?.horario_apertura || '08:00' }} - {{ zona.settings?.horario_cierre || '22:00' }}</span>
                                    </div>
                                    <div v-if="Number(zona.settings?.deposito_garantia) > 0" class="flex items-center gap-1 text-[9.5px] font-bold text-amber-500">
                                        <span class="material-symbols-rounded text-sm">security</span>
                                        <span>Depósito: {{ formatCurrency(Number(zona.settings.deposito_garantia)) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <p class="text-xs font-medium text-on-surface-variant/70 dark:text-white/50 leading-relaxed line-clamp-2 italic min-h-[2.5rem]">
                            {{ zona.descripcion || 'Sin protocolos o especificaciones adicionales documentadas.' }}
                        </p>
                    </div>
                </div>

                <!-- Footer con Botones de Acción Táctiles (>= 44px) -->
                <div class="p-4 sm:p-5 pt-0 border-t border-outline-variant/10 dark:border-white/5 mt-4 flex items-center gap-2">
                    <Button 
                        variant="outline" 
                        size="md" 
                        icon="tune" 
                        class="flex-1 !h-11 sm:!h-12 !rounded-xl !text-[10px] font-black uppercase italic justify-center cursor-pointer"
                        @click="openEditModal(zona)"
                    >
                        Configurar
                    </Button>

                    <Button 
                        :variant="zona.activa ? 'ghost' : 'primary'" 
                        size="md" 
                        :icon="zona.activa ? 'pause_circle' : 'play_circle'" 
                        class="flex-1 !h-11 sm:!h-12 !rounded-xl !text-[10px] font-black uppercase italic justify-center cursor-pointer"
                        :class="zona.activa ? 'text-warning hover:bg-warning/10' : ''"
                        @click="toggleStatus(zona)"
                    >
                        {{ zona.activa ? 'Pausar' : 'Habilitar' }}
                    </Button>

                    <button 
                        @click="openDeleteModal(zona)"
                        class="w-11 h-11 sm:w-12 sm:h-12 rounded-xl flex items-center justify-center text-error hover:bg-error/10 border border-error/20 transition-all active:scale-95 cursor-pointer shrink-0"
                        title="Eliminar Zona Común"
                        aria-label="Eliminar Zona Común"
                    >
                        <span class="material-symbols-rounded text-lg">delete</span>
                    </button>
                </div>
            </Card>

            <!-- Card para Agregar Nuevo Espacio (Placeholder Táctico) -->
            <button 
                @click="openCreateModal"
                class="min-h-[22rem] sm:min-h-[26rem] border-2 border-dashed border-outline-variant/20 dark:border-white/10 rounded-[2rem] sm:rounded-[2.5rem] flex flex-col items-center justify-center p-6 sm:p-8 hover:border-primary hover:bg-primary/5 transition-all group cursor-pointer select-none"
            >
                <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl bg-primary/10 flex items-center justify-center text-primary group-hover:scale-110 group-hover:bg-primary group-hover:text-white transition-all shadow-xl shadow-primary/10">
                    <span class="material-symbols-rounded text-3xl sm:text-4xl">add_home_work</span>
                </div>
                <h4 class="text-sm sm:text-base font-black text-on-surface dark:text-white uppercase tracking-tight italic mt-5 group-hover:text-primary transition-colors">
                    Integrar Nuevo Activo
                </h4>
                <p class="text-[9px] sm:text-[10px] font-bold text-on-surface-variant/40 dark:text-white/20 uppercase tracking-widest mt-1 text-center max-w-xs">
                    Piscina, BBQ, Salón Social, Gimnasio, Canchas o Espacios Comunitarios
                </p>
            </button>
        </div>

        <!-- Empty State si la búsqueda no arroja resultados -->
        <div v-if="filteredZonas.length === 0 && zonas.length > 0" class="p-12 text-center bg-surface-container-low/30 rounded-3xl border border-outline-variant/10">
            <span class="material-symbols-rounded text-5xl text-on-surface-variant/30 mb-3">search_off</span>
            <h4 class="text-base font-black text-on-surface uppercase tracking-tight italic">No se encontraron zonas coincidentes</h4>
            <p class="text-xs font-medium text-on-surface-variant/60 mt-1 max-w-md mx-auto">No hay ningún activo registrado que coincida con el término "{{ searchQuery }}".</p>
            <Button variant="ghost" size="sm" class="mt-4 !rounded-xl" @click="searchQuery = ''; activeFilter = 'all'">Restablecer Filtros</Button>
        </div>

        <!-- Modal de Creación / Configuración de Zona Común -->
        <Modal 
            :show="showModal" 
            @close="showModal = false"
            class="!max-w-2xl"
        >
            <div class="flex items-center gap-3 sm:gap-4 pb-4 border-b border-outline-variant/10">
                <div class="w-11 h-11 sm:w-12 sm:h-12 bg-primary/10 rounded-2xl flex items-center justify-center text-primary shrink-0">
                    <span class="material-symbols-rounded text-2xl">{{ editingZona ? 'tune' : 'add_business' }}</span>
                </div>
                <div>
                    <h3 class="text-xl sm:text-2xl font-black text-on-surface dark:text-white uppercase tracking-tight italic leading-tight">
                        {{ editingZona ? 'Calibrar Zona Común' : 'Registrar Nueva Amenidad' }}
                    </h3>
                    <p class="text-[9.5px] sm:text-[10.5px] font-bold text-on-surface-variant/60 dark:text-white/40 uppercase tracking-wider italic">
                        Parámetros de capacidad, horarios operativos y condiciones económicas
                    </p>
                </div>
            </div>

            <form @submit.prevent="submitForm" class="space-y-6 mt-6 max-h-[75vh] overflow-y-auto pr-1 no-scrollbar">
                
                <!-- Sección 1: Datos Principales -->
                <div class="space-y-4">
                    <p class="text-[10px] font-black text-primary uppercase tracking-[0.25em] italic">1. Identidad del Espacio</p>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <Input 
                            v-model="form.nombre"
                            label="Nombre del Espacio / Activo"
                            placeholder="Ej: Salón Social Torre 1, Zona BBQ Terraza..."
                            icon="apartment"
                            required
                            :error="form.errors.nombre"
                            class="!rounded-2xl"
                        />

                        <!-- Selector de Categoría / Tipo -->
                        <div class="space-y-1.5">
                            <label class="block text-[10px] font-black uppercase tracking-widest text-on-surface-variant">Tipo de Amenidad</label>
                            <select 
                                v-model="form.settings.tipo" 
                                class="w-full h-14 bg-surface dark:bg-[#0b0e14] border border-outline-variant/20 rounded-2xl px-4 text-xs font-bold text-on-surface focus:outline-none focus:border-primary"
                            >
                                <option v-for="t in zoneTypes" :key="t.id" :value="t.id">{{ t.label }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <Input 
                            v-model="form.capacidad_maxima"
                            label="Aforo Máximo (PAX)"
                            type="number"
                            placeholder="Ej: 50"
                            icon="groups"
                            required
                            :error="form.errors.capacidad_maxima"
                            class="!rounded-2xl"
                        />

                        <Input 
                            v-model="form.costo"
                            label="Tarifa por Reserva (COP)"
                            type="number"
                            placeholder="0 (Gratis si es 0)"
                            icon="payments"
                            required
                            :error="form.errors.costo"
                            class="!rounded-2xl"
                        />
                    </div>
                </div>

                <!-- Sección 2: Reglas de Horario y Depósito -->
                <div class="space-y-4 pt-4 border-t border-outline-variant/10">
                    <p class="text-[10px] font-black text-primary uppercase tracking-[0.25em] italic">2. Reglas de Operación & Horarios</p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <Input 
                            v-model="form.settings.horario_apertura"
                            label="Hora de Apertura"
                            type="time"
                            icon="schedule"
                            class="!rounded-2xl"
                        />
                        <Input 
                            v-model="form.settings.horario_cierre"
                            label="Hora de Cierre"
                            type="time"
                            icon="schedule"
                            class="!rounded-2xl"
                        />
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <Input 
                            v-model="form.settings.tiempo_maximo_horas"
                            label="Tiempo Máximo de Uso (Horas)"
                            type="number"
                            placeholder="Ej: 4"
                            icon="timelapse"
                            class="!rounded-2xl"
                        />
                        <Input 
                            v-model="form.settings.deposito_garantia"
                            label="Depósito Reembolsable (COP)"
                            type="number"
                            placeholder="Ej: 100000"
                            icon="security"
                            class="!rounded-2xl"
                        />
                    </div>
                </div>

                <!-- Sección 3: Descripción y Normas -->
                <div class="space-y-2 pt-4 border-t border-outline-variant/10">
                    <label class="block text-[10px] font-black uppercase tracking-widest text-on-surface-variant">Reglamento y Condiciones de Entrega</label>
                    <textarea 
                        v-model="form.descripcion" 
                        rows="3" 
                        class="w-full bg-surface dark:bg-[#0b0e14] border border-outline-variant/20 rounded-2xl p-4 text-xs font-medium text-on-surface focus:outline-none focus:border-primary transition-all placeholder:text-on-surface-variant/40"
                        placeholder="Describe los protocolos de aseo, niveles de ruido permitidos y condiciones para el reintegro del depósito..."
                    ></textarea>
                    <p v-if="form.errors.descripcion" class="text-xs text-error font-semibold">{{ form.errors.descripcion }}</p>
                </div>

                <!-- Botones de Acción Modal -->
                <div class="flex flex-col sm:flex-row gap-3 pt-6 border-t border-outline-variant/10">
                    <Button 
                        type="button" 
                        variant="ghost" 
                        class="flex-1 !h-12 sm:!h-14 !rounded-2xl text-xs font-black uppercase tracking-wider cursor-pointer" 
                        @click="showModal = false"
                    >
                        Cancelar
                    </Button>
                    <Button 
                        type="submit" 
                        variant="primary" 
                        size="lg" 
                        class="flex-1 !h-12 sm:!h-14 !rounded-2xl font-black uppercase italic shadow-xl shadow-primary/20 cursor-pointer justify-center"
                        :disabled="form.processing"
                        :icon="editingZona ? 'save' : 'add_circle'"
                    >
                        {{ form.processing ? 'Guardando...' : (editingZona ? 'Actualizar Activo' : 'Guardar y Publicar') }}
                    </Button>
                </div>
            </form>
        </Modal>

        <!-- Modal de Confirmación de Eliminación Segura -->
        <ConfirmModal 
            :show="showDeleteConfirm"
            title="¿Eliminar Zona Común?"
            :message="`Estás a punto de retirar '${zoneToDelete?.nombre}' del inventario del conjunto. Esta acción no se puede deshacer y fallará si existen reservas activas agendadas.`"
            confirm-label="Sí, Eliminar Espacio"
            cancel-label="Conservar Zona"
            variant="error"
            :loading="isDeleting"
            @confirm="executeDelete"
            @cancel="showDeleteConfirm = false"
        />
    </div>
</template>

<style scoped>
.material-symbols-rounded {
    font-variation-settings: 'opsz' 24, 'wght' 600, 'FILL' 0;
}
</style>

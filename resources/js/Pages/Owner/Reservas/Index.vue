<script setup lang="ts">
import { Head, useForm, router } from '@inertiajs/vue3';
import OwnerLayout from '@/Layouts/OwnerLayout.vue';
import Card from '@/Components/UI/Card.vue';
import Button from '@/Components/UI/Button.vue';
import Badge from '@/Components/UI/Badge.vue';
import DatePicker from '@/Components/UI/DatePicker.vue';
import Modal from '@/Components/UI/Modal.vue';
import { useToast } from '@/Composables/useToast';
import { ref, computed } from 'vue';

defineOptions({ layout: OwnerLayout });

const props = defineProps<{
    zonas: Array<any>;
    reservas: Array<any>;
    unidades: Array<any>;
    auth?: any;
}>();

const toast = useToast();
const step = ref(1);
const selectedZona = ref<any>(null);
const cancellingReserva = ref<any>(null);
const activeTab = ref<'all' | 'aprobada' | 'pendiente' | 'historial'>('all');

const slots = ['08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00', '21:00'];

const form = useForm({
    zona_id: '',
    unidad_id: props.unidades?.[0]?.id || '',
    fecha: '',
    hora_inicio: '',
    hora_fin: '',
    cantidad_personas: 1,
});

const selectZona = (zona: any) => {
    selectedZona.value = zona;
    form.zona_id = zona.id;
    form.cantidad_personas = Math.min(zona.capacidad_maxima || 10, 2);
    step.value = 2;
};

const selectSlot = (slot: string) => {
    form.hora_inicio = slot;
    const [h, m] = slot.split(':');
    const startHour = parseInt(h, 10);
    const endHour = (startHour + 1) % 24;
    form.hora_fin = `${String(endHour).padStart(2, '0')}:${m}`;
    step.value = 3;
};

const submit = () => {
    form.post(route('reservas.store'), {
        onSuccess: () => {
            step.value = 1;
            selectedZona.value = null;
            form.reset();
            toast.add('¡Solicitud de reserva radicada con éxito!', 'success');
        },
        onError: () => {
            toast.add('No se pudo procesar la reserva. Verifica el horario y tu disponibilidad.', 'error');
        }
    });
};

const confirmCancel = (reserva: any) => {
    cancellingReserva.value = reserva;
};

const executeCancel = () => {
    if (!cancellingReserva.value) return;
    router.post(route('reservas.cancel', cancellingReserva.value.id), {}, {
        onSuccess: () => {
            cancellingReserva.value = null;
            toast.add('Reserva cancelada exitosamente.', 'info');
        },
        onError: () => {
            toast.add('No se pudo cancelar la reserva.', 'error');
        }
    });
};

const getStatusVariant = (status: string): any => {
    switch (status) {
        case 'pendiente': return 'warning';
        case 'aprobada': return 'success';
        case 'cancelada': return 'neutral';
        case 'pagada': return 'secondary';
        default: return 'neutral';
    }
};

const getZoneIcon = (nombre: string) => {
    const n = (nombre || '').toLowerCase();
    if (n.includes('piscina') || n.includes('acuátic')) return 'pool';
    if (n.includes('bbq') || n.includes('asador')) return 'outdoor_grill';
    if (n.includes('gimnasio') || n.includes('gym')) return 'fitness_center';
    if (n.includes('cancha') || n.includes('fútbol') || n.includes('squash')) return 'sports_soccer';
    if (n.includes('salón') || n.includes('social') || n.includes('eventos')) return 'celebration';
    if (n.includes('cine') || n.includes('teatro')) return 'movie';
    if (n.includes('juego') || n.includes('infantil')) return 'toys';
    return 'meeting_room';
};

const formatCurrency = (value?: number) => {
    const val = parseFloat(String(value || 0));
    if (val <= 0) return 'Gratuito';
    return new Intl.NumberFormat('es-CO', {
        style: 'currency',
        currency: 'COP',
        maximumFractionDigits: 0
    }).format(val);
};

const filteredReservas = computed(() => {
    if (!props.reservas) return [];
    if (activeTab.value === 'all') return props.reservas;
    if (activeTab.value === 'historial') return props.reservas.filter(r => r.estado === 'cancelada' || r.estado === 'completada');
    return props.reservas.filter(r => r.estado === activeTab.value);
});
</script>

<template>
    <Head title="Reservas de Zonas Comunes — NEXO-PRO" />

    <!-- Cancel Confirmation Modal -->
    <Modal
        v-if="cancellingReserva"
        :show="!!cancellingReserva"
        title="Cancelar Reserva"
        @close="cancellingReserva = null"
    >
        <div class="space-y-4">
            <p class="text-sm text-on-surface">
                ¿Estás seguro de que deseas cancelar la reserva del espacio
                <strong>{{ cancellingReserva.zona?.nombre || cancellingReserva.zona_comun?.nombre }}</strong>
                programada para el <strong>{{ cancellingReserva.fecha }}</strong> de <strong>{{ cancellingReserva.hora_inicio?.slice(0,5) }} a {{ cancellingReserva.hora_fin?.slice(0,5) }}</strong>?
            </p>
            <p class="text-xs text-on-surface-variant/70">
                Esta acción liberará la franja horaria inmediatamente para otros residentes del conjunto.
            </p>
            <div class="pt-4 border-t border-outline-variant/10 flex justify-end gap-3">
                <Button variant="ghost" size="sm" @click="cancellingReserva = null">Mantener Reserva</Button>
                <Button variant="danger" size="sm" icon="delete" @click="executeCancel">Sí, Cancelar</Button>
            </div>
        </div>
    </Modal>

    <!-- Main Container -->
    <div class="max-w-5xl mx-auto w-full pb-20 space-y-8">
        
        <!-- Header & Wizard Indicator -->
        <header class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 px-1">
            <div>
                <h2 class="text-2xl md:text-3xl font-black text-on-surface tracking-tight leading-tight">
                    Zonas Comunes y Amenidades
                </h2>
                <p class="text-xs text-on-surface-variant/60 font-medium mt-0.5">Disfruta y reserva los espacios de esparcimiento de tu conjunto</p>
            </div>
            
            <!-- Wizard Stepper Indicator -->
            <div v-if="step > 1" class="flex items-center gap-3 bg-surface-container-lowest dark:bg-surface-container-low py-2 px-4 rounded-2xl border border-outline-variant/20 shadow-xs self-start sm:self-auto">
                <span class="text-[10px] font-black uppercase tracking-wider text-on-surface-variant/60">Paso {{ step }} de 3</span>
                <div class="flex items-center gap-1.5">
                    <div class="w-6 h-6 rounded-full flex items-center justify-center text-[10px] font-black transition-all"
                        :class="step >= 1 ? 'bg-primary text-white' : 'bg-surface-container text-on-surface-variant/40'"
                    >1</div>
                    <div class="w-3 h-0.5 bg-outline-variant/30"></div>
                    <div class="w-6 h-6 rounded-full flex items-center justify-center text-[10px] font-black transition-all"
                        :class="step >= 2 ? 'bg-primary text-white' : 'bg-surface-container text-on-surface-variant/40'"
                    >2</div>
                    <div class="w-3 h-0.5 bg-outline-variant/30"></div>
                    <div class="w-6 h-6 rounded-full flex items-center justify-center text-[10px] font-black transition-all"
                        :class="step >= 3 ? 'bg-primary text-white' : 'bg-surface-container text-on-surface-variant/40'"
                    >3</div>
                </div>
            </div>
        </header>

        <!-- Wizard Flow -->
        <div>
            <!-- Paso 1: Selección de Espacio -->
            <div v-if="step === 1" class="space-y-12">
                
                <!-- Zone Cards Grid -->
                <section class="space-y-4">
                    <div class="flex items-center justify-between px-1">
                        <h3 class="text-xs font-black text-on-surface uppercase tracking-wider flex items-center gap-2">
                            <span class="material-symbols-rounded text-primary text-base">domain</span>
                            Espacios Habilitados ({{ zonas?.length || 0 }})
                        </h3>
                    </div>
                    
                    <div v-if="zonas && zonas.length > 0" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                        <div 
                            v-for="zona in zonas" 
                            :key="zona.id"
                            @click="selectZona(zona)"
                            class="p-5 sm:p-6 rounded-[2rem] bg-surface-container-lowest dark:bg-surface-container-low border border-outline-variant/20 shadow-sm hover:border-primary/40 hover:shadow-md transition-all cursor-pointer group flex flex-col justify-between gap-5 min-h-[220px]"
                        >
                            <div class="flex items-start justify-between gap-4">
                                <div class="w-14 h-14 rounded-2xl bg-primary/10 text-primary flex items-center justify-center group-hover:scale-105 group-hover:bg-primary group-hover:text-white transition-all shadow-inner shrink-0">
                                    <span class="material-symbols-rounded text-3xl">
                                        {{ getZoneIcon(zona.nombre) }}
                                    </span>
                                </div>
                                <Badge :variant="zona.costo > 0 ? 'secondary' : 'success'" class="!text-[9px] uppercase tracking-wider">
                                    {{ formatCurrency(zona.costo) }}
                                </Badge>
                            </div>

                            <div>
                                <h4 class="text-lg font-black text-on-surface group-hover:text-primary transition-colors tracking-tight truncate leading-tight">
                                    {{ zona.nombre }}
                                </h4>
                                <p class="text-xs text-on-surface-variant/70 line-clamp-2 mt-1 leading-relaxed">
                                    {{ zona.descripcion || 'Espacio común acondicionado para uso exclusivo y recreación de residentes.' }}
                                </p>
                            </div>

                            <div class="pt-3 border-t border-outline-variant/10 flex items-center justify-between text-xs">
                                <span class="text-on-surface-variant/60 font-bold flex items-center gap-1">
                                    <span class="material-symbols-rounded text-sm">groups</span>
                                    <span>Máx. {{ zona.capacidad_maxima || 10 }} pers.</span>
                                </span>
                                <span class="text-primary font-bold flex items-center gap-1 group-hover:translate-x-1 transition-transform">
                                    <span>Reservar</span>
                                    <span class="material-symbols-rounded text-base">arrow_forward</span>
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Empty State for Zones -->
                    <div v-else class="p-12 text-center rounded-[2rem] bg-surface-container-lowest dark:bg-surface-container-low border border-outline-variant/20 flex flex-col items-center gap-3">
                        <div class="w-16 h-16 rounded-2xl bg-primary/10 text-primary flex items-center justify-center">
                            <span class="material-symbols-rounded text-3xl">meeting_room</span>
                        </div>
                        <h4 class="text-base font-black text-on-surface">No hay zonas comunes activas</h4>
                        <p class="text-xs text-on-surface-variant/70 max-w-md">
                            La administración de la copropiedad aún no ha registrado amenidades disponibles para reserva.
                        </p>
                    </div>
                </section>

                <!-- My Recent Reservations -->
                <section class="space-y-4 pt-6 border-t border-outline-variant/20">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 px-1">
                        <h3 class="text-xs font-black text-on-surface-variant/60 uppercase tracking-widest flex items-center gap-2">
                            <span class="material-symbols-rounded text-base">history</span>
                            Tus Solicitudes y Reservas ({{ filteredReservas.length }})
                        </h3>

                        <!-- Filter Tabs -->
                        <div class="flex items-center gap-1 bg-surface-container-high/40 p-1 rounded-xl border border-outline-variant/10 self-start sm:self-auto">
                            <button 
                                v-for="tab in [
                                    { key: 'all', label: 'Todas' },
                                    { key: 'aprobada', label: 'Aprobadas' },
                                    { key: 'pendiente', label: 'Pendientes' },
                                    { key: 'historial', label: 'Historial' }
                                ]"
                                :key="tab.key"
                                type="button"
                                @click="activeTab = tab.key as any"
                                class="px-2.5 py-1 rounded-lg text-xs font-bold transition-all cursor-pointer"
                                :class="activeTab === tab.key ? 'bg-primary text-white shadow-xs' : 'text-on-surface-variant hover:text-on-surface'"
                            >
                                {{ tab.label }}
                            </button>
                        </div>
                    </div>
                    
                    <div v-if="filteredReservas.length > 0" class="grid grid-cols-1 md:grid-cols-2 gap-3.5">
                        <div 
                            v-for="res in filteredReservas" 
                            :key="res.id" 
                            class="p-4 sm:p-5 rounded-2xl bg-surface-container-lowest dark:bg-surface-container-low border border-outline-variant/20 flex items-center justify-between gap-3 shadow-xs"
                        >
                            <div class="flex items-center gap-3.5 min-w-0">
                                <div class="w-11 h-11 rounded-xl bg-primary/10 text-primary flex items-center justify-center shrink-0">
                                    <span class="material-symbols-rounded text-xl">event_available</span>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-xs sm:text-sm font-black text-on-surface truncate">
                                        {{ res.zona?.nombre || res.zona_comun?.nombre }}
                                    </p>
                                    <p class="text-[10px] text-on-surface-variant/60 font-medium mt-0.5">
                                        {{ res.fecha }} · {{ res.hora_inicio?.slice(0,5) }} - {{ res.hora_fin?.slice(0,5) }}
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 shrink-0">
                                <Badge :variant="getStatusVariant(res.estado)" class="!text-[9px] uppercase tracking-wider">
                                    {{ res.estado }}
                                </Badge>
                                <button 
                                    v-if="res.estado === 'pendiente' || res.estado === 'aprobada'"
                                    @click="confirmCancel(res)"
                                    type="button"
                                    class="p-1.5 rounded-lg text-error hover:bg-error/10 transition-colors"
                                    title="Cancelar Reserva"
                                >
                                    <span class="material-symbols-rounded text-lg">cancel</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div v-else class="py-12 text-center text-on-surface-variant/50 flex flex-col items-center gap-2">
                        <span class="material-symbols-rounded text-4xl opacity-40">calendar_today</span>
                        <p class="text-xs font-bold">No tienes reservas registradas con este filtro</p>
                    </div>
                </section>
            </div>

            <!-- Paso 2: Selección de Fecha y Franja Horaria -->
            <div v-else-if="step === 2" class="max-w-2xl mx-auto space-y-6">
                <div class="flex items-center gap-4">
                    <Button variant="ghost" icon="arrow_back" class="!rounded-full !w-10 !h-10 !p-0" @click="step = 1"></Button>
                    <div>
                        <span class="text-[10px] font-bold text-on-surface-variant/60 uppercase tracking-widest">Paso 2 de 3</span>
                        <h3 class="text-xl sm:text-2xl font-black text-on-surface tracking-tight leading-tight">
                            {{ selectedZona?.nombre }}
                        </h3>
                    </div>
                </div>

                <div class="rounded-[2.5rem] p-6 sm:p-8 bg-surface-container-lowest dark:bg-surface-container-low border border-outline-variant/20 shadow-md space-y-8">
                    <!-- Unit Selector if user has multiple units -->
                    <div v-if="unidades && unidades.length > 1" class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-primary flex items-center gap-2">
                            <span class="w-4 h-4 rounded-full bg-primary/10 text-primary flex items-center justify-center text-[10px]">1</span>
                            ¿A nombre de cuál de tus inmuebles reservas?
                        </label>
                        <select 
                            v-model="form.unidad_id" 
                            class="w-full bg-surface-container border border-outline-variant/30 rounded-xl px-4 py-2.5 text-xs font-bold text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/20"
                        >
                            <option v-for="u in unidades" :key="u.id" :value="u.id">
                                {{ u.torre }} - {{ u.nombre }}
                            </option>
                        </select>
                    </div>

                    <!-- Date Picker -->
                    <div class="space-y-2">
                        <DatePicker 
                            v-model="form.fecha"
                            label="Fecha de la Reserva"
                            icon="calendar_today"
                            :error="form.errors.fecha"
                        />
                    </div>

                    <!-- Slots Selection -->
                    <div class="space-y-3">
                        <label class="text-[10px] font-black uppercase tracking-widest text-primary flex items-center gap-2">
                            <span class="w-4 h-4 rounded-full bg-primary/10 text-primary flex items-center justify-center text-[10px]">2</span>
                            Elige la franja de inicio (Bloques de 1 Hora)
                        </label>
                        
                        <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 gap-2.5">
                            <button 
                                v-for="slot in slots" 
                                :key="slot"
                                type="button"
                                @click="selectSlot(slot)"
                                :disabled="!form.fecha"
                                class="py-3 rounded-xl text-xs font-bold transition-all duration-200 cursor-pointer text-center"
                                :class="[
                                    !form.fecha ? 'opacity-30 cursor-not-allowed bg-surface-container' : '',
                                    form.hora_inicio === slot 
                                        ? 'bg-primary text-white shadow-md scale-105' 
                                        : 'bg-surface-container text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface'
                                ]"
                            >
                                {{ slot }}
                            </button>
                        </div>
                        <p v-if="!form.fecha" class="text-[10px] text-amber-500 font-bold uppercase tracking-wider text-center mt-2">
                            Selecciona primero una fecha para habilitar los horarios
                        </p>
                    </div>
                </div>
            </div>

            <!-- Paso 3: Confirmación Final -->
            <div v-else-if="step === 3" class="max-w-xl mx-auto space-y-6">
                <div class="flex items-center gap-4">
                    <Button variant="ghost" icon="arrow_back" class="!rounded-full !w-10 !h-10 !p-0" @click="step = 2"></Button>
                    <div>
                        <span class="text-[10px] font-bold text-on-surface-variant/60 uppercase tracking-widest">Paso 3 de 3</span>
                        <h3 class="text-xl sm:text-2xl font-black text-on-surface tracking-tight leading-tight">
                            Confirmación de Reserva
                        </h3>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-primary to-[#002855] text-white rounded-[2.5rem] p-6 sm:p-8 shadow-2xl relative overflow-hidden space-y-6">
                    <div class="text-center space-y-2">
                        <div class="w-14 h-14 rounded-2xl bg-white/10 flex items-center justify-center mx-auto border border-white/20">
                            <span class="material-symbols-rounded text-3xl text-emerald-400">task_alt</span>
                        </div>
                        <h4 class="text-xl font-black uppercase tracking-tight">Verifica los Datos</h4>
                        <p class="text-xs text-white/70">Revisa la información antes de enviar la solicitud</p>
                    </div>

                    <div class="bg-white/10 backdrop-blur-md rounded-2xl p-5 border border-white/15 space-y-4 text-xs">
                        <div class="flex items-center justify-between pb-3 border-b border-white/10">
                            <span class="text-white/70 font-medium">Espacio Común:</span>
                            <span class="font-black text-sm uppercase">{{ selectedZona?.nombre }}</span>
                        </div>
                        <div class="flex items-center justify-between pb-3 border-b border-white/10">
                            <span class="text-white/70 font-medium">Fecha Programada:</span>
                            <span class="font-black text-sm">{{ form.fecha }}</span>
                        </div>
                        <div class="flex items-center justify-between pb-3 border-b border-white/10">
                            <span class="text-white/70 font-medium">Horario Reservado:</span>
                            <span class="font-black text-sm">{{ form.hora_inicio }} - {{ form.hora_fin }} (1 hora)</span>
                        </div>
                        <div class="flex items-center justify-between pb-3 border-b border-white/10">
                            <span class="text-white/70 font-medium">Tarifa Aplicable:</span>
                            <span class="font-black text-sm text-emerald-300">{{ formatCurrency(selectedZona?.costo) }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-white/70 font-medium">Capacidad Máxima:</span>
                            <span class="font-bold">{{ selectedZona?.capacidad_maxima || 10 }} Personas</span>
                        </div>
                    </div>

                    <div class="text-[10px] text-white/60 text-center leading-relaxed">
                        Al confirmar, aceptas el reglamento de uso de zonas comunes de la copropiedad y el compromiso de entrega en condiciones óptimas de aseo y orden.
                    </div>

                    <Button 
                        variant="secondary" 
                        size="lg" 
                        class="w-full !rounded-xl !py-4 shadow-xl text-xs font-black uppercase tracking-wider"
                        :loading="form.processing"
                        @click="submit"
                    >
                        Confirmar y Radicar Reserva
                    </Button>
                </div>
            </div>
        </div>
    </div>
</template>

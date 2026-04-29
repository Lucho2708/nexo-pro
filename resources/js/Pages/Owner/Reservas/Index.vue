<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import OwnerLayout from '@/Layouts/OwnerLayout.vue';
import Card from '@/Components/UI/Card.vue';
import Button from '@/Components/UI/Button.vue';
import Badge from '@/Components/UI/Badge.vue';
import Input from '@/Components/UI/Input.vue';
import DatePicker from '@/Components/UI/DatePicker.vue';
import { ref } from 'vue';

defineOptions({ layout: OwnerLayout });

const props = defineProps<{
    zonas: Array<any>;
    reservas: Array<any>;
    unidades: Array<any>;
    auth: any;
}>();

const step = ref(1);
const selectedZona = ref<any>(null);
const slots = ['09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00'];

const form = useForm({
    zona_id: '',
    unidad_id: props.unidades[0]?.id || '',
    fecha: '',
    hora_inicio: '',
    hora_fin: '',
    cantidad_personas: 1,
});

const selectZona = (zona: any) => {
    selectedZona.value = zona;
    form.zona_id = zona.id;
    step.value = 2;
};

const selectSlot = (slot: string) => {
    form.hora_inicio = slot;
    // Auto-set 1 hour duration
    const [h, m] = slot.split(':');
    form.hora_fin = `${String(parseInt(h) + 1).padStart(2, '0')}:${m}`;
    step.value = 3;
};

const submit = () => {
    form.post(route('reservas.store'), {
        onSuccess: () => {
            step.value = 1;
            selectedZona.value = null;
            form.reset();
        },
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
</script>

<template>
    <Head title="Reservas - PH360" />

    <div class="max-w-5xl mx-auto w-full pb-20">
            <header class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-6">
                <div>
                    <h2 class="text-3xl font-black text-primary tracking-tighter uppercase leading-none">Reservas</h2>
                    <p class="text-xs font-bold text-on-surface-variant/60 uppercase tracking-widest mt-2">Disfruta tus espacios comunes</p>
                </div>
                
                <!-- Wizard Progress -->
                <div v-if="step > 1 || selectedZona" class="flex items-center gap-4 bg-surface-container-low/50 py-2.5 px-6 rounded-2xl border border-outline-variant/10 shadow-sm">
                    <span class="text-[9px] font-black text-on-surface-variant/40 uppercase tracking-[0.2em] mr-2">Progreso {{ step }}/3</span>
                    <div class="flex items-center gap-2">
                        <div :class="step >= 1 ? 'bg-primary text-on-primary shadow-md scale-110' : 'bg-surface-container-high text-on-surface-variant/30'" class="w-6 h-6 rounded-full flex items-center justify-center text-[10px] font-black transition-all duration-500">1</div>
                        <div class="w-6 h-0.5 bg-outline-variant/20 rounded-full"></div>
                        <div :class="step >= 2 ? 'bg-primary text-on-primary shadow-md scale-110' : 'bg-surface-container-high text-on-surface-variant/30'" class="w-6 h-6 rounded-full flex items-center justify-center text-[10px] font-black transition-all duration-500">2</div>
                        <div class="w-6 h-0.5 bg-outline-variant/20 rounded-full"></div>
                        <div :class="step >= 3 ? 'bg-primary text-on-primary shadow-md scale-110' : 'bg-surface-container-high text-on-surface-variant/30'" class="w-6 h-6 rounded-full flex items-center justify-center text-[10px] font-black transition-all duration-500">3</div>
                    </div>
                </div>
            </header>

            <!-- Main Wizard Content with Transitions -->
            <div class="relative min-h-[500px]">
                <Transition
                    enter-active-class="transition duration-500 ease-out"
                    enter-from-class="opacity-0 translate-x-8"
                    enter-to-class="opacity-100 translate-x-0"
                    leave-active-class="transition duration-300 ease-in absolute w-full"
                    leave-from-class="opacity-100 translate-x-0"
                    leave-to-class="opacity-0 -translate-x-8"
                >
                    <!-- Paso 1: Selección de Área -->
                    <div v-if="step === 1" :key="1" class="space-y-12">
                        <section>
                            <h3 class="text-xs font-black text-on-surface uppercase tracking-[0.2em] mb-8 flex items-center gap-2">
                                <span class="material-symbols-outlined text-sm text-primary">touch_app</span> Selecciona el espacio
                            </h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                <Card 
                                    v-for="zona in zonas" 
                                    :key="zona.id"
                                    hover
                                    @click="selectZona(zona)"
                                    class="cursor-pointer group"
                                    content-class="!pt-4"
                                >
                                    <div class="flex flex-col gap-6">
                                        <div class="w-14 h-14 rounded-2xl bg-primary/5 text-primary flex items-center justify-center border border-primary/10 shadow-sm group-hover:scale-110 group-hover:bg-primary group-hover:text-on-primary transition-all duration-500">
                                            <span class="material-symbols-outlined text-3xl">
                                                {{ zona.nombre.toLowerCase().includes('piscina') ? 'pool' : 'meeting_room' }}
                                            </span>
                                        </div>
                                        
                                        <div>
                                            <p class="text-xl font-black text-on-surface uppercase tracking-tight leading-none mb-2">{{ zona.nombre }}</p>
                                            <div class="flex items-center gap-3">
                                                <Badge variant="neutral" class="!lowercase">Capacidad: {{ zona.capacidad_maxima }} personas</Badge>
                                            </div>
                                        </div>
                                        
                                        <div class="flex items-center justify-between border-t border-outline-variant/5 pt-4">
                                            <span class="text-[11px] font-black uppercase tracking-widest" :class="zona.costo > 0 ? 'text-secondary' : 'text-green-600'">
                                                {{ zona.costo > 0 ? '$' + new Intl.NumberFormat('es-CO').format(zona.costo) : 'Gratuito' }}
                                            </span>
                                            <span class="material-symbols-outlined text-on-surface-variant/20 group-hover:text-primary group-hover:translate-x-1 transition-all">arrow_forward</span>
                                        </div>
                                    </div>
                                </Card>
                            </div>
                        </section>

                        <!-- Historial de reservas -->
                        <section class="mt-16">
                            <h3 class="text-xs font-black text-on-surface-variant/40 uppercase tracking-widest mb-8 flex items-center gap-2">
                                <span class="material-symbols-outlined text-sm">history</span> Mis Reservas Recientes
                            </h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div v-for="res in reservas" :key="res.id" class="bg-surface-container-low/50 rounded-2xl p-5 border border-outline-variant/10 flex items-center justify-between hover:bg-surface-container-low transition-colors">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-xl bg-white flex items-center justify-center text-primary shadow-sm border border-outline-variant/10">
                                            <span class="material-symbols-outlined text-xl">event_available</span>
                                        </div>
                                        <div>
                                            <p class="text-[11px] font-black text-on-surface uppercase tracking-tight">{{ res.zona.nombre }}</p>
                                            <p class="text-[9px] text-on-surface-variant/50 font-bold uppercase mt-0.5">{{ new Date(res.fecha).toLocaleDateString() }} • {{ res.hora_inicio.slice(0,5) }}</p>
                                        </div>
                                    </div>
                                    <Badge :variant="getStatusVariant(res.estado)">{{ res.estado }}</Badge>
                                </div>
                            </div>

                            <div v-if="reservas.length === 0" class="py-12 text-center opacity-20 flex flex-col items-center gap-3">
                                <span class="material-symbols-outlined text-4xl italic">calendar_today</span>
                                <p class="text-[10px] font-black uppercase tracking-widest">Sin actividad reciente</p>
                            </div>
                        </section>
                    </div>

                    <!-- Paso 2: Selección de Fecha y Hora -->
                    <div v-else-if="step === 2" :key="2" class="max-w-2xl mx-auto space-y-8">
                        <div class="flex items-center gap-4">
                            <Button variant="ghost" icon="arrow_back" class="!rounded-full !w-12 !h-12 !p-0" @click="step = 1"></Button>
                            <div>
                                <p class="text-[10px] font-bold text-on-surface-variant/40 uppercase tracking-widest leading-none">Paso 2 de 3</p>
                                <h3 class="text-2xl font-black text-primary uppercase tracking-tighter mt-1">{{ selectedZona.nombre }}</h3>
                            </div>
                        </div>

                        <Card content-class="space-y-10">
                            <DatePicker 
                                v-model="form.fecha"
                                label="1. Selecciona el día"
                                icon="calendar_today"
                                :error="form.errors.fecha"
                            />

                            <div>
                                <label class="text-[10px] font-black text-primary uppercase tracking-[0.2em] mb-6 flex items-center gap-2">
                                    <span class="bg-primary/10 text-primary w-5 h-5 rounded flex items-center justify-center text-[10px]">2</span>
                                    Elige el horario (Bloques de 1h)
                                </label>
                                
                                <div class="grid grid-cols-3 md:grid-cols-4 gap-3">
                                    <button 
                                        v-for="slot in slots" 
                                        :key="slot"
                                        @click="selectSlot(slot)"
                                        :disabled="!form.fecha"
                                        :class="[
                                            !form.fecha ? 'opacity-20 cursor-not-allowed' : '',
                                            form.hora_inicio === slot 
                                                ? 'bg-primary text-on-primary shadow-xl shadow-primary/20 scale-105' 
                                                : 'bg-surface-container-high text-on-surface-variant hover:bg-outline-variant/30 hover:scale-105'
                                        ]"
                                        class="py-4 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all duration-300"
                                    >
                                        {{ slot }}
                                    </button>
                                </div>
                                <p v-if="!form.fecha" class="text-[10px] text-error font-black uppercase tracking-widest mt-6 text-center italic">Selecciona una fecha primero</p>
                            </div>
                        </Card>
                    </div>

                    <!-- Paso 3: Confirmación Final -->
                    <div v-else-if="step === 3" :key="3" class="max-w-xl mx-auto space-y-8">
                        <div class="flex items-center gap-4">
                            <Button variant="ghost" icon="arrow_back" class="!rounded-full !w-12 !h-12 !p-0" @click="step = 2"></Button>
                            <h3 class="text-xl font-black text-on-surface-variant/40 uppercase tracking-widest">Verifica los datos</h3>
                        </div>

                        <div class="bg-primary text-on-primary rounded-[3rem] p-10 md:p-12 shadow-2xl relative overflow-hidden text-center md:text-left">
                            <!-- Watermark Decoration -->
                            <div class="absolute -top-10 -right-10 opacity-5 pointer-events-none">
                                <span class="material-symbols-outlined text-[200px]">verified</span>
                            </div>

                            <div class="mb-12 text-center">
                                <div class="w-20 h-20 bg-white/10 rounded-[2rem] flex items-center justify-center mx-auto mb-6 border border-white/20 shadow-inner">
                                    <span class="material-symbols-outlined text-4xl" style="font-variation-settings: 'FILL' 1;">task_alt</span>
                                </div>
                                <h3 class="text-4xl font-black tracking-tighter uppercase mb-2">Confirmación</h3>
                                <p class="text-on-primary-container text-[10px] font-bold uppercase tracking-widest opacity-60">Resumen de tu solicitud de espacio</p>
                            </div>

                            <div class="bg-white/5 rounded-[2rem] p-8 backdrop-blur-md border border-white/10 space-y-8 text-left relative z-10">
                                <div class="flex items-center gap-5">
                                    <div class="w-14 h-14 rounded-2xl bg-secondary/20 text-secondary flex items-center justify-center shrink-0 border border-secondary/20">
                                        <span class="material-symbols-outlined text-2xl">location_on</span>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-[9px] text-white/40 font-black uppercase tracking-[0.2em] mb-1">Espacio solicitado</p>
                                        <p class="text-xl font-black tracking-tight uppercase truncate">{{ selectedZona.nombre }}</p>
                                    </div>
                                </div>

                                <div class="flex items-center gap-5">
                                    <div class="w-14 h-14 rounded-2xl bg-emerald-500/20 text-emerald-300 flex items-center justify-center shrink-0 border border-emerald-500/20">
                                        <span class="material-symbols-outlined text-2xl">calendar_today</span>
                                    </div>
                                    <div>
                                        <p class="text-[9px] text-white/40 font-black uppercase tracking-[0.2em] mb-1">Fecha y Franja Horaria</p>
                                        <p class="text-lg font-black tracking-tight">{{ form.fecha.split('-').reverse().join(' / ') }}</p>
                                        <p class="text-xs font-bold opacity-60">{{ form.hora_inicio }} - {{ form.hora_fin }} (1 hora)</p>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-10 flex flex-wrap justify-center gap-x-6 gap-y-3 text-[10px] font-black uppercase tracking-widest opacity-40">
                                <span class="flex items-center gap-2"><span class="material-symbols-outlined text-sm">cleaning_services</span> Entrega aseada</span>
                                <span class="flex items-center gap-2"><span class="material-symbols-outlined text-sm">groups</span> Máx {{ selectedZona.capacidad_maxima }} px</span>
                            </div>

                            <div class="mt-12">
                                <Button 
                                    variant="secondary" 
                                    size="lg" 
                                    class="w-full !rounded-[1.5rem] !py-6 shadow-2xl shadow-black/40"
                                    :loading="form.processing"
                                    @click="submit"
                                >
                                    Confirmar Reserva
                                </Button>
                            </div>
                        </div>
                    </div>
                </Transition>
            </div>
        </div>
</template>

<style scoped>
.material-symbols-outlined {
    font-variation-settings: 'opsz' 24;
}
</style>

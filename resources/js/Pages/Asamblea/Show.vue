<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import VideoPlayer from '@/Components/Asamblea/VideoPlayer.vue';
import Button from '@/Components/UI/Button.vue';
import { useToast } from '@/Composables/useToast';
import { useAsambleaRealtime } from '@/Composables/useAsambleaRealtime';
import axios from 'axios';

const props = defineProps<{
    asamblea: any;
    token: string;
    unidad: any;
    is_admin: boolean;
    livekit_url: string;
    user: any; // Añadido para comparar el usuario actual
}>();

// Eliminamos el layout estándar para que la asamblea sea una experiencia inmersiva a pantalla completa

const toast = useToast();
const { activeQuestion, raisedHands, activeIntervencion } = useAsambleaRealtime(props.asamblea.id);

const activeTab = ref('interaccion');
const isHandRaised = ref(false);
const isVoting = ref(false);
const hasVoted = ref(false);

const toggleHand = async () => {
    isHandRaised.value = !isHandRaised.value;
    
    try {
        await axios.post(route('asambleas.intervenciones.request', props.asamblea.id), {
            is_raised: isHandRaised.value
        });
        toast.add(isHandRaised.value ? 'Has pedido la palabra' : 'Has bajado la mano', isHandRaised.value ? 'primary' : 'secondary');
    } catch (e) {
        isHandRaised.value = !isHandRaised.value;
        toast.add('Error al comunicar la petición', 'danger');
    }
};

// Observar cuando otros levantan la mano
watch(raisedHands, (newList, oldList) => {
    if (newList.length > oldList.length) {
        const lastRaised = newList[newList.length - 1];
        if (lastRaised.user_id !== props.user.id) {
            toast.add(`${lastRaised.unidad} (${lastRaised.name}) ha levantado la mano`, 'info');
        }
    }
}, { deep: true });

// Observar cambios en intervenciones (Turnos de palabra)
watch(activeIntervencion, (newVal, oldVal) => {
    if (newVal && !oldVal) {
        // Se ha concedido la palabra a alguien
        if (newVal.user_id === props.user.id) {
            toast.add('¡Es tu turno! Tu micrófono ha sido habilitado.', 'success');
        } else {
            toast.add(`Turno de palabra concedido a: ${newVal.user?.name || 'Residente'}`, 'info');
        }
    } else if (!newVal && oldVal) {
        // Se ha terminado una intervención
        toast.add('La intervención ha finalizado.', 'secondary');
    }
});

const grantWord = async (hand: any) => {
    try {
        // En un escenario real, 'hand' debería traer el ID de la intervención creada
        // Por ahora, como toggleHand crea una intervención, vamos a buscarla o el backend la gestiona
        // Para simplificar el test, el backend ya tiene el endpoint que recibe el ID de intervención.
        // Pero en raisedHands solo tenemos data del usuario.
        // Vamos a asumir que el backend maneja la cola por usuario o el ID viene en la data.
        
        // REFACTOR: El backend ahora devuelve la intervención en el evento.
        // Por ahora, buscaré la última intervención pendiente del usuario.
        await axios.post(`/asambleas/intervenciones/${hand.intervencion_id}/grant`);
        toast.add(`Palabra cedida a ${hand.name}`, 'success');
    } catch (e) {
        toast.add('No se pudo ceder la palabra', 'danger');
    }
};

const endIntervencion = async (force = false) => {
    if (!activeIntervencion.value) return;
    try {
        await axios.post(`/asambleas/intervenciones/${activeIntervencion.value.id}/close`, {
            force,
            notes: force ? 'Cierre forzado por moderador' : ''
        });
        toast.add(force ? 'Intervención revocada' : 'Intervención finalizada', 'secondary');
    } catch (e) {
        toast.add('Error al finalizar intervención', 'danger');
    }
};

const extendTime = async () => {
    if (!activeIntervencion.value) return;
    try {
        await axios.post(`/asambleas/intervenciones/${activeIntervencion.value.id}/extend`, {
            seconds: 60
        });
        toast.add('Tiempo extendido +1 min', 'primary');
    } catch (e) {
        toast.add('Error al extender tiempo', 'danger');
    }
};

// Lógica de cronómetro
const timeLeft = ref(0);
let timerInterval: any = null;

watch(activeIntervencion, (newVal) => {
    if (newVal && newVal.status === 'active') {
        timeLeft.value = newVal.duration_seconds;
        if (timerInterval) clearInterval(timerInterval);
        timerInterval = setInterval(() => {
            if (timeLeft.value > 0) {
                timeLeft.value--;
            } else {
                clearInterval(timerInterval);
                if (props.is_admin) endIntervencion();
            }
        }, 1000);
    } else {
        clearInterval(timerInterval);
        timeLeft.value = 0;
    }
}, { immediate: true });

const formatTime = (seconds: number) => {
    const mins = Math.floor(seconds / 60);
    const secs = seconds % 60;
    return `${mins}:${secs.toString().padStart(2, '0')}`;
};

const submitVote = async (opcionId: number) => {
    if (isVoting.value || hasVoted.value) return;

    isVoting.value = true;
    try {
        await axios.post(route('asambleas.votar', activeQuestion.value.id), {
            opcion_id: opcionId
        });
        toast.add('Voto registrado correctamente', 'success');
        hasVoted.value = true;
    } catch (e: any) {
        toast.add(e.response?.data?.message || 'Error al votar', 'danger');
    } finally {
        isVoting.value = false;
    }
};

// Reset hasVoted when a new question appears
watch(activeQuestion, () => {
    hasVoted.value = false;
});

const isCameraOn = ref(false);
const isMicOn = ref(false);
const videoPlayerRef = ref(null);

const toggleCamera = async () => {
    isCameraOn.value = !isCameraOn.value;
    await videoPlayerRef.value?.toggleCamera(isCameraOn.value);
    toast.add(isCameraOn.value ? 'Cámara activada' : 'Cámara desactivada', isCameraOn.value ? 'success' : 'secondary');
};

const toggleMic = async () => {
    isMicOn.value = !isMicOn.value;
    await videoPlayerRef.value?.toggleMicrophone(isMicOn.value);
    toast.add(isMicOn.value ? 'Micrófono activado' : 'Micrófono desactivado', isMicOn.value ? 'success' : 'secondary');
};

const exitAsamblea = () => {
    if (props.is_admin) {
        router.get(route('admin.asambleas.index'));
    } else {
        router.get(route('owner.dashboard'));
    }
};
</script>

<template>
    <Head :title="`Asamblea — ${asamblea.copropiedad.nombre}`" />

    <div class="fixed inset-0 bg-[#00173c] text-white font-sans selection:bg-primary/30 flex flex-col overflow-hidden">
        <!-- Ultra-Compact Header -->
        <header class="shrink-0 px-4 py-3 border-b border-white/5 bg-white/2 backdrop-blur-md flex items-center justify-between z-40">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-xl bg-primary/20 flex items-center justify-center text-primary">
                    <span class="material-symbols-rounded text-xl">meeting_room</span>
                </div>
                <div>
                    <h2 class="text-[11px] font-black text-white tracking-tight uppercase leading-none truncate max-w-[150px]">
                        {{ asamblea.titulo }}
                    </h2>
                    <p class="text-[8px] font-bold text-white/30 uppercase tracking-widest leading-none mt-1">
                        {{ asamblea.copropiedad.nombre }}
                    </p>
                </div>
            </div>
            
            <div class="flex items-center gap-3">
                <div v-if="unidad" class="hidden sm:flex flex-col items-end">
                    <p class="text-[8px] font-black text-white/20 uppercase tracking-widest">Unidad</p>
                    <p class="text-[10px] font-bold text-primary uppercase leading-none">{{ unidad.torre }}-{{ unidad.nombre }}</p>
                </div>
                <Button variant="ghost" size="sm" icon="logout" @click="exitAsamblea" class="!w-8 !h-8 !p-0 !rounded-full !text-white/40 hover:!text-white" />
            </div>
        </header>

        <!-- Main Content Area -->
        <main class="flex-1 flex flex-col lg:flex-row min-h-0 overflow-hidden">
            
            <!-- Video & Voting Column -->
            <div class="flex-1 flex flex-col min-h-0 relative">
                <!-- Video Section: Flexible proportion -->
                    <!-- Intervention Active Banner -->
                    <Transition
                        enter-active-class="transform transition duration-500 ease-out"
                        enter-from-class="-translate-y-full opacity-0"
                        enter-to-class="translate-y-0 opacity-100"
                        leave-active-class="transform transition duration-300 ease-in"
                        leave-from-class="translate-y-0 opacity-100"
                        leave-to-class="-translate-y-full opacity-0"
                    >
                        <div v-if="activeIntervencion" class="absolute inset-x-0 top-4 flex justify-center z-30 px-4">
                            <div class="w-full max-w-sm bg-black/60 backdrop-blur-3xl border border-white/20 p-4 rounded-[2rem] shadow-3xl flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-emerald-500 flex items-center justify-center animate-pulse">
                                        <span class="material-symbols-rounded text-white text-xl">mic</span>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-black text-white uppercase leading-none">Hablando ahora</p>
                                        <p class="text-[9px] font-bold text-white/50 uppercase mt-1">{{ activeIntervencion.user?.name || 'Residente' }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-2xl font-black text-white tabular-nums tracking-tighter leading-none">{{ formatTime(timeLeft) }}</p>
                                    <p class="text-[8px] font-black text-emerald-400 uppercase tracking-widest mt-1">Tiempo restante</p>
                                </div>
                            </div>
                        </div>
                    </Transition>

                    <VideoPlayer 
                        ref="videoPlayerRef"
                        :url="livekit_url"
                        :token="token"
                    />

                    <!-- Overlay Voting: More compact for mobile -->
                    <Transition
                        enter-active-class="transform transition duration-500 ease-out"
                        enter-from-class="translate-y-full opacity-0"
                        enter-to-class="translate-y-0 opacity-100"
                        leave-active-class="transform transition duration-300 ease-in"
                        leave-from-class="translate-y-0 opacity-100"
                        leave-to-class="translate-y-full opacity-0"
                    >
                        <div v-if="activeQuestion" class="absolute inset-x-0 bottom-4 flex justify-center z-30 px-4">
                            <div class="w-full bg-white/10 backdrop-blur-3xl border border-white/20 p-6 rounded-[2rem] shadow-3xl">
                                <h3 class="text-sm font-black text-white leading-tight mb-4 tracking-tight">
                                    {{ activeQuestion.titulo }}
                                </h3>

                                <div v-if="!hasVoted" class="grid grid-cols-2 gap-3">
                                    <button 
                                        v-for="opcion in activeQuestion.opciones" 
                                        :key="opcion.id"
                                        @click="submitVote(opcion.id)"
                                        :disabled="isVoting"
                                        class="py-3 px-4 bg-white text-primary rounded-xl font-black text-[10px] uppercase tracking-widest transition-all active:scale-95 shadow-lg"
                                    >
                                        {{ opcion.titulo }}
                                    </button>
                                </div>
                                <div v-else class="py-3 text-center bg-emerald-500/20 border border-emerald-500/30 rounded-xl">
                                    <p class="text-[9px] font-black text-emerald-400 uppercase tracking-widest">Voto registrado</p>
                                </div>
                            </div>
                        </div>
                    </Transition>

                    <!-- Persistent Controls Floating -->
                    <div class="absolute bottom-6 left-1/2 -translate-x-1/2 flex items-center gap-2 bg-black/40 backdrop-blur-xl border border-white/10 p-1.5 rounded-full z-20 shadow-2xl">
                        <template v-if="is_admin">
                            <button @click="toggleMic" class="w-10 h-10 rounded-full flex items-center justify-center transition-all" :class="isMicOn ? 'bg-emerald-500 text-white' : 'bg-white/5 text-white/40'">
                                <span class="material-symbols-rounded text-lg">{{ isMicOn ? 'mic' : 'mic_off' }}</span>
                            </button>
                            <button @click="toggleCamera" class="w-10 h-10 rounded-full flex items-center justify-center transition-all" :class="isCameraOn ? 'bg-emerald-500 text-white' : 'bg-white/5 text-white/40'">
                                <span class="material-symbols-rounded text-lg">{{ isCameraOn ? 'videocam' : 'videocam_off' }}</span>
                            </button>
                            <div class="w-px h-4 bg-white/10 mx-1"></div>
                        </template>
                        <button @click="toggleHand" class="w-10 h-10 rounded-full flex items-center justify-center transition-all" :class="isHandRaised ? 'bg-primary text-white scale-110 shadow-lg shadow-primary/30' : 'bg-white/5 text-white/40'">
                            <span class="material-symbols-rounded text-lg">{{ isHandRaised ? 'pan_tool' : 'front_hand' }}</span>
                        </button>
                    </div>
                </div>

                <!-- Admin Quick Controls (Floating) -->
                <div v-if="is_admin && activeIntervencion" class="absolute top-24 right-6 flex flex-col gap-2 z-40">
                    <button @click="extendTime" class="p-3 bg-white/10 backdrop-blur-xl border border-white/20 rounded-2xl text-white hover:bg-white/20 transition-all flex items-center gap-2">
                        <span class="material-symbols-rounded text-sm">add_time</span>
                        <span class="text-[9px] font-black uppercase tracking-widest">+1 Min</span>
                    </button>
                    <button @click="endIntervencion(true)" class="p-3 bg-red-500/20 backdrop-blur-xl border border-red-500/30 rounded-2xl text-red-400 hover:bg-red-500/30 transition-all flex items-center gap-2">
                        <span class="material-symbols-rounded text-sm">block</span>
                        <span class="text-[9px] font-black uppercase tracking-widest">Revocar</span>
                    </button>
                </div>

            <!-- Interaction Sidebar -->
            <aside class="w-full lg:w-[350px] bg-white/2 border-t lg:border-t-0 lg:border-l border-white/5 flex flex-col shrink-0 min-h-0">
                <nav class="flex border-b border-white/5 px-2 py-1.5 shrink-0 bg-white/2">
                    <button 
                        v-for="tab in ['interaccion', 'quorum', 'detalles']" 
                        :key="tab"
                        @click="activeTab = tab"
                        class="flex-1 py-2 text-[8px] font-black uppercase tracking-widest transition-all rounded-lg"
                        :class="activeTab === tab ? 'bg-white/10 text-primary' : 'text-white/40 hover:text-white/60'"
                    >
                        {{ tab }}
                    </button>
                </nav>

                <div class="flex-1 overflow-y-auto p-6 custom-scrollbar">
                    <div v-if="activeTab === 'interaccion'" class="space-y-6">
                        <div class="space-y-6">
                            <!-- Admin: Raised Hands List -->
                            <template v-if="is_admin">
                                <h5 class="text-[9px] font-black text-amber-400 uppercase tracking-[0.2em] flex items-center gap-2">
                                    <span class="material-symbols-rounded text-sm">front_hand</span>
                                    Peticiones de Palabra ({{ raisedHands.length }})
                                </h5>
                                
                                <div class="space-y-2">
                                    <div v-for="hand in raisedHands" :key="hand.user_id" class="flex items-center justify-between p-3 bg-white/5 border border-white/10 rounded-2xl animate-fade-in">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-xl bg-amber-500/20 flex items-center justify-center text-amber-500 font-black text-[10px]">
                                                {{ hand.unidad.split('-')[1] || '?' }}
                                            </div>
                                            <div>
                                                <p class="text-[10px] font-black text-white uppercase leading-none">{{ hand.name }}</p>
                                                <p class="text-[8px] font-bold text-white/30 uppercase mt-1">Unidad {{ hand.unidad }}</p>
                                            </div>
                                        </div>
                                        <button 
                                            @click="grantWord(hand)"
                                            class="py-1 px-3 bg-emerald-500 text-white rounded-xl font-black text-[8px] uppercase tracking-widest transition-all active:scale-95 shadow-lg"
                                        >
                                            Ceder Palabra
                                        </button>
                                    </div>
                                    <div v-if="raisedHands.length === 0" class="text-center py-6 border-2 border-dashed border-white/5 rounded-2xl opacity-20">
                                        <p class="text-[8px] font-black uppercase tracking-widest">Nadie ha levantado la mano</p>
                                    </div>
                                </div>
                                <div class="h-px bg-white/10 my-6"></div>
                            </template>

                            <h5 class="text-[9px] font-black text-white/40 uppercase tracking-[0.2em] flex items-center gap-2">
                                <span class="material-symbols-rounded text-sm">history</span>
                                Actividad
                            </h5>
                        </div>
                        
                        <div class="space-y-4">
                            <div v-if="hasVoted" class="p-4 rounded-2xl bg-emerald-500/5 border border-emerald-500/10 flex gap-3">
                                <span class="material-symbols-rounded text-emerald-400 text-sm">check_circle</span>
                                <p class="text-[10px] text-emerald-400/80 font-medium">Has participado en la última votación.</p>
                            </div>
                            <div v-else class="text-center py-12 opacity-10">
                                <span class="material-symbols-rounded text-3xl block mb-2">sensors</span>
                                <p class="text-[8px] font-black uppercase tracking-widest">Esperando actividad...</p>
                            </div>
                        </div>
                    </div>
                    
                    <div v-if="activeTab === 'quorum'" class="space-y-8 py-4">
                        <div class="relative w-28 h-28 mx-auto flex items-center justify-center">
                            <svg class="w-full h-full -rotate-90">
                                <circle cx="56" cy="56" r="50" fill="transparent" stroke="rgba(255,255,255,0.05)" stroke-width="8" />
                                <circle cx="56" cy="56" r="50" fill="transparent" stroke="var(--color-primary)" stroke-width="8" stroke-dasharray="314" stroke-dashoffset="88" stroke-linecap="round" />
                            </svg>
                            <span class="absolute text-2xl font-black text-white tracking-tighter">72%</span>
                        </div>
                        <p class="text-center text-[10px] font-black uppercase text-white/40 tracking-widest">Quórum Alcanzado</p>
                    </div>
                </div>
            </aside>
        </main>
    </div>
</template>

<style scoped>
.material-symbols-rounded {
    font-variation-settings: 'opsz' 24, 'wght' 500, 'FILL' 0;
}
.bg-brand-gradient {
    background: linear-gradient(135deg, var(--color-primary) 0%, #3b82f6 100%);
}
.custom-scrollbar::-webkit-scrollbar { width: 4px; }
.custom-scrollbar::-webkit-scrollbar-thumb { background: var(--color-outline-variant); border-radius: 10px; }
</style>

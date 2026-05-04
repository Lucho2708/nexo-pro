<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import VideoPlayer from '@/Components/Asamblea/VideoPlayer.vue';
import VotingModule from '@/Components/Asamblea/VotingModule.vue';
import LiveResults from '@/Components/Asamblea/LiveResults.vue';
import Button from '@/Components/UI/Button.vue';
import Modal from '@/Components/UI/Modal.vue';
import { useToast } from '@/Composables/useToast';
import { useAsambleaRealtime } from '@/Composables/useAsambleaRealtime';
import axios from 'axios';
import { useForm } from '@inertiajs/vue3';

const props = defineProps<{
    asamblea: any;
    token: string;
    unidades: any[];
    is_admin: boolean;
    livekit_url: string;
    user: any; 
}>();

const toast = useToast();
const { activeQuestion, raisedHands, activeIntervencion, actividad, asistentesCount, addActividad } = useAsambleaRealtime(props.asamblea.id);

const activeTab = ref('interaccion');
const isHandRaised = ref(false);
const isVoting = ref(false);
const hasVoted = ref(false);

const currentIntervencionId = ref<number | null>(null);
const showCreateQuestion = ref(false);

const openQuestion = async (id: number) => {
    try {
        await axios.post(`/asambleas/preguntas/${id}/open`);
        toast.add('Votación iniciada', 'success');
    } catch (e) {
        toast.add('Error al iniciar votación', 'danger');
    }
};

const closeQuestion = async (id: number) => {
    try {
        await axios.post(`/asambleas/preguntas/${id}/close`);
        toast.add('Votación cerrada', 'secondary');
    } catch (e) {
        toast.add('Error al cerrar votación', 'danger');
    }
};

const newQuestionForm = useForm({
    titulo: '',
    descripcion: '',
    tipo: 'simple',
    opciones: [
        { titulo: 'A Favor' },
        { titulo: 'En Contra' },
        { titulo: 'Abstención' }
    ]
});

const submitNewQuestion = () => {
    newQuestionForm.post(route('asambleas.preguntas.store', props.asamblea.id), {
        onSuccess: () => {
            showCreateQuestion.value = false;
            newQuestionForm.reset();
            toast.add('Pregunta creada y lista para lanzar', 'success');
        }
    });
};

const addOption = () => {
    newQuestionForm.opciones.push({ titulo: '' });
};

const removeOption = (index: number) => {
    if (newQuestionForm.opciones.length > 2) {
        newQuestionForm.opciones.splice(index, 1);
    }
};

const toggleHand = async () => {
    // Caso 1: El usuario está hablando actualmente y quiere finalizar
    if (activeIntervencion.value && activeIntervencion.value.user_id === props.user.id) {
        try {
            await axios.post(`/asambleas/intervenciones/${activeIntervencion.value.id}/close`, {
                force: false,
                notes: 'Finalizado voluntariamente por el usuario'
            });
            isHandRaised.value = false;
            toast.add('Has finalizado tu intervención', 'secondary');
        } catch (e) {
            toast.add('Error al finalizar la intervención', 'danger');
        }
        return;
    }

    // Caso 2: El usuario no ha pedido la palabra
    if (!isHandRaised.value) {
        try {
            const response = await axios.post(route('asambleas.intervenciones.request', props.asamblea.id));
            currentIntervencionId.value = response.data.id;
            isHandRaised.value = true;
            toast.add('Has pedido la palabra', 'primary');
        } catch (e) {
            toast.add('Error al solicitar la palabra', 'danger');
        }
    } else {
        // Caso 3: El usuario tiene la mano levantada (esperando) y quiere cancelar
        if (!currentIntervencionId.value) {
            const myHand = raisedHands.value.find(h => h.user_id === props.user.id);
            if (myHand) currentIntervencionId.value = myHand.intervencion_id;
        }

        if (currentIntervencionId.value) {
            try {
                await axios.post(`/asambleas/intervenciones/${currentIntervencionId.value}/cancel`);
                isHandRaised.value = false;
                currentIntervencionId.value = null;
                toast.add('Has bajado la mano', 'secondary');
            } catch (e) {
                toast.add('Error al cancelar la petición', 'danger');
            }
        } else {
            isHandRaised.value = false;
        }
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

const grantWord = async (hand: any) => {
    try {
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
        addActividad('Emitiste tu voto', 'success');
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

// Auto-activación de cámara y micro cuando te dan la palabra
watch(activeIntervencion, async (newVal) => {
    if (newVal && newVal.user_id === props.user.id && newVal.status === 'active') {
        toast.add('¡Te han concedido la palabra! Tu cámara y micrófono se activarán ahora.', 'success');
        
        // Pequeño delay para que el usuario se prepare
        setTimeout(async () => {
            isCameraOn.value = true;
            isMicOn.value = true;
            await videoPlayerRef.value?.toggleCamera(true);
            await videoPlayerRef.value?.toggleMicrophone(true);
        }, 500);
    } else if (!newVal && isMicOn.value && !props.is_admin) {
        // Desactivar automáticamente al terminar la intervención si no es admin
        isCameraOn.value = false;
        isMicOn.value = false;
        await videoPlayerRef.value?.toggleCamera(false);
        await videoPlayerRef.value?.toggleMicrophone(false);
        toast.add('Intervención finalizada. Periféricos desactivados.', 'secondary');
    }
});

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
                <div class="flex items-center gap-2 px-3 py-1.5 bg-emerald-500/10 border border-emerald-500/20 rounded-full">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span class="text-[10px] font-black text-emerald-400 tabular-nums">{{ asistentesCount }} ASISTENTES</span>
                </div>

                <div v-if="unidades && unidades.length > 0" class="hidden sm:flex flex-col items-end">
                    <p class="text-[8px] font-black text-white/20 uppercase tracking-widest">Unidades Representadas</p>
                    <p class="text-[10px] font-bold text-primary uppercase leading-none">
                        {{ unidades.map(u => `${u.torre}-${u.nombre}`).join(', ') }}
                    </p>
                </div>
                <Button variant="ghost" size="sm" icon="logout" @click="exitAsamblea" class="!w-8 !h-8 !p-0 !rounded-full !text-white/40 hover:!text-white" />
            </div>
        </header>

        <!-- Main Content Area -->
        <main class="flex-1 flex flex-col lg:flex-row min-h-0 overflow-hidden">
            
            <!-- Video & Voting Column -->
            <div class="flex-1 flex flex-col min-h-0 relative">
                <!-- Video Section -->
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
                    @participant-count="asistentesCount = $event"
                />

                <!-- Overlay Voting Module -->
                <div v-if="activeQuestion" class="absolute inset-x-0 bottom-8 flex justify-center z-30 px-4">
                    <VotingModule 
                        :question="activeQuestion"
                        :unidades="unidades || []"
                        :is-voting="isVoting"
                        :has-voted="hasVoted"
                        @vote="submitVote"
                    />
                </div>

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
                    <button @click="toggleHand" class="w-10 h-10 rounded-full flex items-center justify-center transition-all" 
                            :class="{
                                'bg-emerald-500 text-white scale-110 shadow-lg shadow-emerald-500/30': activeIntervencion?.user_id === user.id,
                                'bg-primary text-white scale-110 shadow-lg shadow-primary/30': isHandRaised && activeIntervencion?.user_id !== user.id,
                                'bg-white/5 text-white/40': !isHandRaised && !activeIntervencion
                            }">
                        <span class="material-symbols-rounded text-lg">
                            {{ activeIntervencion?.user_id === user.id ? 'check_circle' : (isHandRaised ? 'pan_tool' : 'front_hand') }}
                        </span>
                    </button>
                </div>
            </div>

                <!-- Interaction Sidebar -->
                <aside class="w-full lg:w-[350px] bg-white/2 border-t lg:border-t-0 lg:border-l border-white/5 flex flex-col shrink-0 h-[40vh] lg:h-auto min-h-0">
                    <nav class="flex border-b border-white/5 px-2 py-1.5 shrink-0 bg-white/2">
                        <button 
                            v-for="tab in (is_admin ? ['interaccion', 'moderacion', 'resultados', 'quorum', 'detalles'] : ['interaccion', 'quorum', 'detalles'])" 
                            :key="tab"
                            @click="activeTab = tab"
                            class="flex-1 py-2 text-[8px] font-black uppercase tracking-widest transition-all rounded-lg"
                            :class="activeTab === tab ? 'bg-white/10 text-primary' : 'text-white/40 hover:text-white/60'"
                        >
                            {{ tab }}
                        </button>
                    </nav>

                    <div class="flex-1 overflow-y-auto p-6 custom-scrollbar">
                        <!-- Pestaña: Interacción -->
                        <div v-if="activeTab === 'interaccion'" class="space-y-6">
                            <div class="space-y-6">
                                <!-- Raised Hands List -->
                                <template v-if="raisedHands.length > 0">
                                    <h5 class="text-[9px] font-black text-amber-400 uppercase tracking-[0.2em] flex items-center gap-2">
                                        <span class="material-symbols-rounded text-sm">front_hand</span>
                                        En Cola ({{ raisedHands.length }})
                                    </h5>
                                    
                                    <div class="space-y-2">
                                        <div v-for="(hand, index) in raisedHands" :key="hand.user_id" 
                                             class="flex items-center justify-between p-3 bg-white/5 border rounded-2xl animate-fade-in transition-colors"
                                             :class="hand.user_id === user.id ? 'border-primary/40 bg-primary/5' : 'border-white/10'">
                                            <div class="flex items-center gap-3">
                                                <div class="w-8 h-8 rounded-xl flex items-center justify-center font-black text-[10px]"
                                                     :class="hand.user_id === user.id ? 'bg-primary text-white' : 'bg-amber-500/20 text-amber-500'">
                                                     {{ index + 1 }}
                                                 </div>
                                                 <div>
                                                     <p class="text-[10px] font-black uppercase leading-none" :class="hand.user_id === user.id ? 'text-primary' : 'text-white'">
                                                         {{ hand.name }} {{ hand.user_id === user.id ? '(Tú)' : '' }}
                                                     </p>
                                                     <p class="text-[8px] font-bold text-white/30 uppercase mt-1">Unidad {{ hand.unidad }}</p>
                                                 </div>
                                             </div>
                                             <button 
                                                 v-if="is_admin"
                                                 @click="grantWord(hand)"
                                                 class="py-1 px-3 bg-emerald-500 text-white rounded-xl font-black text-[8px] uppercase tracking-widest transition-all active:scale-95 shadow-lg"
                                             >
                                                 Ceder Palabra
                                             </button>
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
                                <div v-for="item in actividad" :key="item.id" 
                                     class="p-3 rounded-2xl bg-white/5 border border-white/5 flex gap-3 animate-fade-in">
                                    <div class="w-1.5 h-1.5 rounded-full mt-1.5 shrink-0"
                                         :class="{
                                             'bg-emerald-500': item.tipo === 'success',
                                             'bg-amber-500': item.tipo === 'warning',
                                             'bg-primary': item.tipo === 'info'
                                         }"></div>
                                    <div>
                                        <p class="text-[10px] text-white/80 leading-tight">{{ item.mensaje }}</p>
                                        <p class="text-[8px] text-white/20 mt-1 font-bold uppercase tracking-widest">{{ item.hora }}</p>
                                    </div>
                                </div>

                                <div v-if="actividad.length === 0" class="text-center py-12 opacity-10">
                                    <span class="material-symbols-rounded text-3xl block mb-2">sensors</span>
                                    <p class="text-[8px] font-black uppercase tracking-widest">Esperando actividad...</p>
                                </div>
                            </div>
                        </div>

                        <!-- Pestaña: Moderación -->
                        <div v-if="activeTab === 'moderacion' && is_admin" class="space-y-6">
                            <div class="flex items-center justify-between mb-2">
                                <h4 class="text-[10px] font-black text-white/40 uppercase tracking-[0.2em]">Panel de Moderación</h4>
                                <Button @click="showCreateQuestion = true" variant="primary" size="xs" class="rounded-full !py-1 !px-3 !text-[8px]">Nueva Pregunta</Button>
                            </div>

                            <div v-if="activeQuestion" class="p-4 bg-primary/10 border border-primary/20 rounded-2xl animate-pulse">
                                <div class="flex justify-between items-start mb-2">
                                    <span class="text-[8px] font-black text-primary uppercase tracking-widest">Votación en Curso</span>
                                    <Button @click="closeQuestion(activeQuestion.id)" variant="danger" size="xs" class="rounded-full !py-1 !px-3 !text-[8px]">Cerrar Votación</Button>
                                </div>
                                <p class="text-[11px] font-black text-white uppercase">{{ activeQuestion.titulo }}</p>
                            </div>

                            <div class="h-px bg-white/10 my-4"></div>

                            <h5 class="text-[9px] font-black text-white/40 uppercase tracking-[0.2em] mb-4">Preguntas Preparadas</h5>
                            <div class="space-y-3">
                                <div v-for="pregunta in asamblea.preguntas.filter(p => p.status !== 'open')" :key="pregunta.id" 
                                     class="p-4 bg-white/5 border border-white/10 rounded-2xl group hover:border-white/20 transition-all">
                                    <div class="flex justify-between items-start gap-4">
                                        <div>
                                            <p class="text-[10px] font-black text-white uppercase leading-tight group-hover:text-primary transition-colors">{{ pregunta.titulo }}</p>
                                            <p class="text-[8px] font-bold text-white/20 uppercase mt-1">{{ pregunta.opciones.length }} opciones</p>
                                        </div>
                                        <button 
                                            v-if="!activeQuestion"
                                            @click="openQuestion(pregunta.id)"
                                            class="w-8 h-8 rounded-full bg-white/5 flex items-center justify-center text-white/40 hover:bg-emerald-500 hover:text-white transition-all shadow-lg active:scale-95"
                                        >
                                            <span class="material-symbols-rounded text-lg">play_arrow</span>
                                        </button>
                                    </div>
                                </div>
                                <div v-if="asamblea.preguntas.length === 0" class="text-center py-12 opacity-10">
                                    <span class="material-symbols-rounded text-3xl block mb-2">quiz</span>
                                    <p class="text-[8px] font-black uppercase tracking-widest">No hay preguntas creadas</p>
                                </div>
                            </div>
                        </div>

                        <!-- Pestaña: Resultados -->
                        <div v-if="activeTab === 'resultados' && is_admin">
                            <LiveResults 
                                :asamblea-id="asamblea.id"
                                :question="activeQuestion"
                            />
                        </div>

                        <!-- Pestaña: Quorum -->
                        <div v-if="activeTab === 'quorum'" class="space-y-6">
                            <h4 class="text-[10px] font-black text-white/40 uppercase tracking-[0.2em] mb-4">Estado del Quorum</h4>
                            <!-- Aquí irá la lógica de quorum que restauraremos si es necesario -->
                        </div>
                    </div>
                </aside>
            </main>

        <!-- New Question Modal -->
        <Modal :show="showCreateQuestion" @close="showCreateQuestion = false">
            <div class="p-6 bg-[#00173c] border border-white/10 rounded-3xl overflow-hidden">
                <h3 class="text-lg font-black text-white uppercase tracking-widest mb-6 flex items-center gap-2">
                    <span class="material-symbols-rounded text-primary">add_circle</span>
                    Nueva Pregunta para Votación
                </h3>

                <form @submit.prevent="submitNewQuestion" class="space-y-6">
                    <div>
                        <label class="block text-[10px] font-black text-white/40 uppercase tracking-widest mb-2">Enunciado de la Pregunta</label>
                        <input 
                            v-model="newQuestionForm.titulo" 
                            type="text" 
                            class="w-full bg-white/5 border border-white/10 rounded-2xl px-4 py-3 text-sm text-white focus:border-primary transition-all outline-none"
                            placeholder="Ej: ¿Aprueba el presupuesto para el periodo 2024?"
                            required
                        >
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-white/40 uppercase tracking-widest mb-2">Opciones de Respuesta</label>
                        <div class="space-y-3">
                            <div v-for="(opcion, index) in newQuestionForm.opciones" :key="index" class="flex gap-2">
                                <input 
                                    v-model="opcion.titulo" 
                                    type="text" 
                                    class="flex-1 bg-white/5 border border-white/10 rounded-xl px-4 py-2 text-xs text-white focus:border-primary transition-all outline-none"
                                    :placeholder="`Opción ${index + 1}`"
                                    required
                                >
                                <button 
                                    v-if="newQuestionForm.opciones.length > 2"
                                    type="button"
                                    @click="removeOption(index)"
                                    class="w-8 h-8 rounded-full flex items-center justify-center text-white/20 hover:text-red-400 transition-colors"
                                >
                                    <span class="material-symbols-rounded text-sm">delete</span>
                                </button>
                            </div>
                        </div>
                        <button 
                            type="button"
                            @click="addOption"
                            class="mt-4 flex items-center gap-2 text-[10px] font-black text-primary uppercase tracking-widest hover:opacity-80 transition-opacity"
                        >
                            <span class="material-symbols-rounded text-sm">add</span>
                            Añadir Opción
                        </button>
                    </div>

                    <div class="flex justify-end gap-3 pt-4">
                        <Button type="button" @click="showCreateQuestion = false" variant="ghost" size="sm" class="rounded-full !text-white/40 hover:!text-white">Cancelar</Button>
                        <Button type="submit" variant="primary" size="sm" class="rounded-full shadow-lg shadow-primary/30" :loading="newQuestionForm.processing">Guardar Pregunta</Button>
                    </div>
                </form>
            </div>
        </Modal>
    </div>
</template>

<style scoped>
.material-symbols-rounded {
    font-variation-settings: 'opsz' 24, 'wght' 500, 'FILL' 0;
}
.custom-scrollbar::-webkit-scrollbar { width: 4px; }
.custom-scrollbar::-webkit-scrollbar-thumb { background: var(--color-outline-variant); border-radius: 10px; }
</style>

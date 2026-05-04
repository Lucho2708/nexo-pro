<script setup lang="ts">
import { onMounted, onUnmounted, ref, watch } from 'vue';
import { 
    Room, 
    RoomEvent, 
    RemoteParticipant, 
    RemoteTrackPublication, 
    RemoteTrack, 
    Track,
    ConnectionState
} from 'livekit-client';

const props = defineProps<{
    url: string;
    token: string;
}>();

const emit = defineEmits(['connected', 'disconnected', 'error', 'state-change', 'participant-count']);

const videoElement = ref<HTMLVideoElement | null>(null);
const connectionState = ref<ConnectionState>(ConnectionState.Disconnected);
const isConnected = ref(false);
const error = ref<string | null>(null);
const isAudioBlocked = ref(false);
let room: Room;

const connect = async () => {
    try {
        room = new Room({
            adaptiveStream: true,
            dynacast: true,
        });

        // Configurar eventos
        room.on(RoomEvent.LocalTrackPublished, (publication: any) => {
            if (publication.track.kind === Track.Kind.Video) {
                publication.track.attach(videoElement.value!);
            }
        });

        room.on(RoomEvent.TrackSubscribed, (track: RemoteTrack, publication: RemoteTrackPublication, participant: RemoteParticipant) => {
            if (track.kind === Track.Kind.Video) {
                track.attach(videoElement.value!);
            } else if (track.kind === Track.Kind.Audio) {
                const audioEl = track.attach();
                document.body.appendChild(audioEl);
            }
        });

        room.on(RoomEvent.ConnectionStateChanged, (state: ConnectionState) => {
            connectionState.value = state;
            emit('connected', room); // Avisar que room está listo
            emit('state-change', state);
        });

        room.on(RoomEvent.AudioPlaybackStatusChanged, (canPlayback: boolean) => {
            isAudioBlocked.value = !canPlayback;
        });

        // Conteo de asistentes
        const updateParticipantCount = () => {
            emit('participant-count', room.numParticipants + 1); // +1 por el usuario local
        };

        room.on(RoomEvent.ParticipantConnected, updateParticipantCount);
        room.on(RoomEvent.ParticipantDisconnected, updateParticipantCount);

        connectionState.value = ConnectionState.Connecting;
        await room.connect(props.url, props.token);
        isConnected.value = true;
        connectionState.value = ConnectionState.Connected;
        updateParticipantCount();
    } catch (e: any) {
        error.value = e.message;
        emit('error', e);
    }
};

const handleStartAudio = async () => {
    if (room) {
        await room.startAudio();
        isAudioBlocked.value = false;
    }
};

const toggleCamera = async (enabled: boolean) => {
    await room?.localParticipant.setCameraEnabled(enabled);
};

const toggleMicrophone = async (enabled: boolean) => {
    await room?.localParticipant.setMicrophoneEnabled(enabled);
};

defineExpose({ toggleCamera, toggleMicrophone, getRoom: () => room });

onMounted(() => {
    connect();
});

onUnmounted(async () => {
    await room.disconnect();
});
</script>

<template>
    <div class="relative w-full h-full bg-black rounded-3xl overflow-hidden group shadow-2xl border border-white/5">
        <!-- Video Container -->
        <video 
            ref="videoElement" 
            class="w-full h-full object-contain"
            autoplay 
            playsinline
        ></video>

        <!-- Loading Overlay -->
        <div v-if="connectionState === ConnectionState.Connecting" 
            class="absolute inset-0 flex flex-col items-center justify-center bg-[#00173c]/90 backdrop-blur-sm z-20"
        >
            <div class="w-16 h-16 border-4 border-primary/20 border-t-primary rounded-full animate-spin mb-6"></div>
            <p class="text-xs font-black text-white uppercase tracking-[0.3em] animate-pulse">Sincronizando señal...</p>
        </div>

        <!-- Error Overlay -->
        <div v-if="error" 
            class="absolute inset-0 flex flex-col items-center justify-center bg-error/90 backdrop-blur-sm z-30 p-8 text-center"
        >
            <span class="material-symbols-rounded text-5xl text-white mb-4">error</span>
            <h3 class="text-xl font-black text-white uppercase tracking-tighter mb-2">Error de Conexión</h3>
            <p class="text-sm font-medium text-white/80 max-w-xs">{{ error }}</p>
            <button @click="connect" class="mt-6 px-6 py-2 bg-white text-error rounded-full font-black text-[10px] uppercase tracking-widest hover:scale-105 transition-transform">
                Reintentar
            </button>
        </div>

        <!-- Audio Blocked Overlay -->
        <div v-if="isAudioBlocked" 
            class="absolute inset-0 flex flex-col items-center justify-center bg-[#00173c]/60 backdrop-blur-md z-40 p-8 text-center"
        >
            <div class="w-20 h-20 bg-primary/20 rounded-full flex items-center justify-center mb-6 animate-bounce">
                <span class="material-symbols-rounded text-4xl text-primary">volume_off</span>
            </div>
            <h3 class="text-xl font-black text-white uppercase tracking-tighter mb-2 italic">Audio Desactivado</h3>
            <p class="text-xs font-bold text-white/60 uppercase tracking-widest mb-8">El navegador ha silenciado la asamblea. Haz clic para escuchar.</p>
            <button @click="handleStartAudio" class="px-10 py-4 bg-primary text-white rounded-2xl font-black text-[12px] uppercase tracking-[0.2em] italic hover:scale-105 transition-all shadow-3xl shadow-primary/40 flex items-center gap-3">
                <span class="material-symbols-rounded">volume_up</span>
                Activar Sonido de la Asamblea
            </button>
        </div>

        <!-- Controls / Info Overlay (Visible on Hover) -->
        <div class="absolute bottom-0 inset-x-0 p-8 bg-gradient-to-t from-black/80 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 z-10">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="flex items-center gap-2 px-3 py-1 bg-emerald-500/20 border border-emerald-500/20 rounded-full">
                        <div class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></div>
                        <span class="text-[9px] font-black text-emerald-400 uppercase tracking-widest">En Vivo</span>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <button class="w-10 h-10 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center text-white backdrop-blur-md transition-all">
                        <span class="material-symbols-rounded text-lg">fullscreen</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.material-symbols-rounded {
    font-variation-settings: 'opsz' 24, 'wght' 400, 'FILL' 1;
}
</style>

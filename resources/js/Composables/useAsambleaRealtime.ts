import { onMounted, onUnmounted, ref } from 'vue';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

// @ts-ignore
window.Pusher = Pusher;

export function useAsambleaRealtime(asambleaId: number) {
    const activeQuestion = ref<any>(null);
    const raisedHands = ref<any[]>([]);
    const activeIntervencion = ref<any>(null); // Intervención que está hablando ahora
    const echo = ref<Echo | null>(null);

    const initEcho = () => {
        if (typeof window === 'undefined') return; // Evitar error en SSR

        echo.value = new Echo({
            broadcaster: 'reverb',
            key: import.meta.env.VITE_REVERB_APP_KEY || 'test_key',
            wsHost: import.meta.env.VITE_REVERB_HOST || window.location.hostname,
            wsPort: import.meta.env.VITE_REVERB_PORT || 8080,
            wssPort: import.meta.env.VITE_REVERB_PORT || 8080,
            forceTLS: (import.meta.env.VITE_REVERB_SCHEME || 'https') === 'https',
            enabledTransports: ['ws', 'wss'],
        });

        // Escuchar eventos públicos de la asamblea
        echo.value.channel(`asamblea.${asambleaId}`)
            .listen('.hand.raised', (e: any) => {
                if (e.userData.is_raised) {
                    if (!raisedHands.value.find(h => h.user_id === e.userData.user_id)) {
                        raisedHands.value.push(e.userData);
                    }
                } else {
                    raisedHands.value = raisedHands.value.filter(h => h.user_id !== e.userData.user_id);
                }
            })
            .listen('.intervencion.updated', (e: any) => {
                const data = e.intervencionData;
                
                // 1. Gestionar la cola de espera (raisedHands)
                if (data.status === 'pending') {
                    // Añadir a la lista si no está (y formatear para la UI)
                    if (!raisedHands.value.find(h => h.user_id === data.user_id)) {
                        raisedHands.value.push({
                            user_id: data.user_id,
                            intervencion_id: data.id,
                            name: data.user?.name || 'Residente',
                            unidad: data.user?.unidades?.[0] ? `${data.user.unidades[0].torre}-${data.user.unidades[0].nombre}` : '?',
                        });
                    }
                } else {
                    // Si ya no es pending (es active, cancelled, etc), quitar de la lista
                    raisedHands.value = raisedHands.value.filter(h => h.user_id !== data.user_id);
                }

                // 2. Gestionar la intervención activa (quién habla ahora)
                if (data.status === 'active') {
                    activeIntervencion.value = data;
                } else if (['completed', 'forced_close', 'cancelled'].includes(data.status)) {
                    if (activeIntervencion.value?.id === data.id) {
                        activeIntervencion.value = null;
                    }
                }
            });

        // Escuchar eventos privados (preguntas)
        echo.value.private(`asamblea.${asambleaId}`)
            .listen('.PreguntaOpened', (e: any) => {
                activeQuestion.value = e.pregunta;
            })
            .listen('.PreguntaClosed', (e: any) => {
                activeQuestion.value = null;
            });
    };

    onMounted(() => {
        initEcho();
    });

    onUnmounted(() => {
        if (echo.value) {
            echo.value.leave(`asamblea.${asambleaId}`);
            echo.value.leaveChannel(`asamblea.${asambleaId}`);
        }
    });

    return {
        activeQuestion,
        raisedHands,
        activeIntervencion,
        echo
    };
}

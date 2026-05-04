import { onMounted, onUnmounted, ref } from 'vue';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

// @ts-ignore
if (typeof window !== 'undefined') {
    window.Pusher = Pusher;
}

export function useAsambleaRealtime(asambleaId: number) {
    const activeQuestion = ref<any>(null);
    const raisedHands = ref<any[]>([]);
    const activeIntervencion = ref<any>(null);
    const actividad = ref<any[]>([]);
    const asistentesCount = ref(0);
    const echo = ref<Echo | null>(null);

    const addActividad = (mensaje: string, tipo: 'info' | 'success' | 'warning' = 'info') => {
        actividad.value.unshift({
            id: Date.now(),
            mensaje,
            tipo,
            hora: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })
        });
        // Mantener solo los últimos 20 eventos
        if (actividad.value.length > 20) actividad.value.pop();
    };

    const initEcho = () => {
        if (typeof window === 'undefined') return; // Evitar error en SSR

        echo.value = new Echo({
            broadcaster: 'reverb',
            key: import.meta.env.VITE_REVERB_APP_KEY || 'test_key',
            wsHost: import.meta.env.VITE_REVERB_HOST || (typeof window !== 'undefined' ? window.location.hostname : 'localhost'),
            wsPort: import.meta.env.VITE_REVERB_PORT || 8080,
            wssPort: import.meta.env.VITE_REVERB_PORT || 8080,
            forceTLS: (import.meta.env.VITE_REVERB_SCHEME || 'https') === 'https',
            enabledTransports: ['ws', 'wss'],
        });

        // Canal Público de la Asamblea
        echo.value.channel(`asamblea.${asambleaId}`)
            .listen('.hand.raised', (e: any) => {
                const { userData } = e;
                if (userData.is_raised) {
                    if (!raisedHands.value.find(h => h.user_id === userData.user_id)) {
                        raisedHands.value.push(userData);
                        addActividad(`${userData.unidad} (${userData.name}) levantó la mano`, 'warning');
                    }
                } else {
                    raisedHands.value = raisedHands.value.filter(h => h.user_id !== userData.user_id);
                    addActividad(`${userData.name} bajó la mano`, 'info');
                }
            })
            .listen('.intervencion.updated', (e: any) => {
                const data = e.intervencionData;
                const userName = data.user?.name || 'Residente';
                const unidadStr = data.user?.unidades?.[0] ? `${data.user.unidades[0].torre}-${data.user.unidades[0].nombre}` : '';

                if (data.status === 'pending') {
                    // Mano levantada (petición de palabra)
                    if (!raisedHands.value.find(h => h.user_id === data.user_id)) {
                        raisedHands.value.push({
                            user_id: data.user_id,
                            intervencion_id: data.id,
                            name: userName,
                            unidad: unidadStr,
                        });
                        addActividad(`${unidadStr} (${userName}) pidió la palabra`, 'warning');
                    }
                } else if (data.status === 'active') {
                    // Palabra concedida
                    activeIntervencion.value = data;
                    raisedHands.value = raisedHands.value.filter(h => h.user_id !== data.user_id);
                    addActividad(`Palabra concedida a ${userName}`, 'success');
                } else if (['completed', 'forced_close', 'cancelled'].includes(data.status)) {
                    // Finalizada o Cancelada
                    if (activeIntervencion.value?.id === data.id) {
                        activeIntervencion.value = null;
                        addActividad(`Intervención de ${userName} finalizada`, 'info');
                    }
                    raisedHands.value = raisedHands.value.filter(h => h.user_id !== data.user_id);
                    
                    if (data.status === 'cancelled') {
                        addActividad(`${userName} canceló su petición`, 'info');
                    }
                }
            });

        // Canal Privado (Votaciones y Preguntas)
        echo.value.private(`asamblea.${asambleaId}`)
            .listen('.PreguntaOpened', (e: any) => {
                activeQuestion.value = e.pregunta;
                addActividad(`Nueva pregunta abierta: ${e.pregunta.titulo}`, 'info');
            })
            .listen('.PreguntaClosed', (e: any) => {
                activeQuestion.value = null;
                addActividad(`Pregunta cerrada`, 'info');
            });
    };

    onMounted(() => {
        initEcho();
    });

    onUnmounted(() => {
        if (echo.value) {
            echo.value.leave(`asamblea.${asambleaId}`);
        }
    });

    return {
        activeQuestion,
        raisedHands,
        activeIntervencion,
        actividad,
        asistentesCount,
        addActividad
    };
}

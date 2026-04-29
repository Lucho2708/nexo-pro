import { t as _plugin_vue_export_helper_default } from "./_plugin-vue_export-helper-BjJHhBxR.js";
import { t as useToast } from "./useToast-E_Hdzmc5.js";
import { t as Button_default } from "./Button-C-byZbTR.js";
import axios from "axios";
import { defineComponent, mergeProps, onMounted, onUnmounted, ref, unref, useSSRContext, watch } from "vue";
import { Head, router } from "@inertiajs/vue3";
import { ssrIncludeBooleanAttr, ssrInterpolate, ssrRenderAttrs, ssrRenderClass, ssrRenderComponent, ssrRenderList } from "vue/server-renderer";
import { ConnectionState, Room, RoomEvent, Track } from "livekit-client";
import Echo from "laravel-echo";
import Pusher from "pusher-js";
//#region resources/js/Components/Asamblea/VideoPlayer.vue?vue&type=script&setup=true&lang.ts
var VideoPlayer_vue_vue_type_script_setup_true_lang_default = /* @__PURE__ */ defineComponent({
	__name: "VideoPlayer",
	__ssrInlineRender: true,
	props: {
		url: {},
		token: {}
	},
	emits: [
		"connected",
		"disconnected",
		"error",
		"state-change"
	],
	setup(__props, { expose: __expose, emit: __emit }) {
		const props = __props;
		const emit = __emit;
		const room = new Room({
			adaptiveStream: true,
			dynacast: true
		});
		const videoElement = ref(null);
		const connectionState = ref(ConnectionState.Disconnected);
		const isConnected = ref(false);
		const error = ref(null);
		const connect = async () => {
			try {
				connectionState.value = ConnectionState.Connecting;
				await room.connect(props.url, props.token);
				isConnected.value = true;
				connectionState.value = ConnectionState.Connected;
				emit("connected", room);
			} catch (e) {
				error.value = e.message;
				emit("error", e);
			}
		};
		const toggleCamera = async (enabled) => {
			await room.localParticipant.setCameraEnabled(enabled);
		};
		const toggleMicrophone = async (enabled) => {
			await room.localParticipant.setMicrophoneEnabled(enabled);
		};
		__expose({
			toggleCamera,
			toggleMicrophone,
			room
		});
		room.on(RoomEvent.LocalTrackPublished, (publication) => {
			if (publication.track.kind === Track.Kind.Video) publication.track.attach(videoElement.value);
		});
		room.on(RoomEvent.TrackSubscribed, (track, publication, participant) => {
			if (track.kind === Track.Kind.Video) track.attach(videoElement.value);
			else if (track.kind === Track.Kind.Audio) track.attach(document.createElement("audio"));
		});
		room.on(RoomEvent.ConnectionStateChanged, (state) => {
			connectionState.value = state;
			emit("state-change", state);
		});
		onMounted(() => {
			connect();
		});
		onUnmounted(async () => {
			await room.disconnect();
		});
		return (_ctx, _push, _parent, _attrs) => {
			_push(`<div${ssrRenderAttrs(mergeProps({ class: "relative w-full h-full bg-black rounded-3xl overflow-hidden group shadow-2xl border border-white/5" }, _attrs))} data-v-4fe4e1d1><video class="w-full h-full object-contain" autoplay playsinline data-v-4fe4e1d1></video>`);
			if (connectionState.value === unref(ConnectionState).Connecting) _push(`<div class="absolute inset-0 flex flex-col items-center justify-center bg-[#00173c]/90 backdrop-blur-sm z-20" data-v-4fe4e1d1><div class="w-16 h-16 border-4 border-primary/20 border-t-primary rounded-full animate-spin mb-6" data-v-4fe4e1d1></div><p class="text-xs font-black text-white uppercase tracking-[0.3em] animate-pulse" data-v-4fe4e1d1>Sincronizando señal...</p></div>`);
			else _push(`<!---->`);
			if (error.value) _push(`<div class="absolute inset-0 flex flex-col items-center justify-center bg-error/90 backdrop-blur-sm z-30 p-8 text-center" data-v-4fe4e1d1><span class="material-symbols-rounded text-5xl text-white mb-4" data-v-4fe4e1d1>error</span><h3 class="text-xl font-black text-white uppercase tracking-tighter mb-2" data-v-4fe4e1d1>Error de Conexión</h3><p class="text-sm font-medium text-white/80 max-w-xs" data-v-4fe4e1d1>${ssrInterpolate(error.value)}</p><button class="mt-6 px-6 py-2 bg-white text-error rounded-full font-black text-[10px] uppercase tracking-widest hover:scale-105 transition-transform" data-v-4fe4e1d1> Reintentar </button></div>`);
			else _push(`<!---->`);
			_push(`<div class="absolute bottom-0 inset-x-0 p-8 bg-gradient-to-t from-black/80 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 z-10" data-v-4fe4e1d1><div class="flex items-center justify-between" data-v-4fe4e1d1><div class="flex items-center gap-4" data-v-4fe4e1d1><div class="flex items-center gap-2 px-3 py-1 bg-emerald-500/20 border border-emerald-500/20 rounded-full" data-v-4fe4e1d1><div class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse" data-v-4fe4e1d1></div><span class="text-[9px] font-black text-emerald-400 uppercase tracking-widest" data-v-4fe4e1d1>En Vivo</span></div></div><div class="flex items-center gap-3" data-v-4fe4e1d1><button class="w-10 h-10 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center text-white backdrop-blur-md transition-all" data-v-4fe4e1d1><span class="material-symbols-rounded text-lg" data-v-4fe4e1d1>fullscreen</span></button></div></div></div></div>`);
		};
	}
});
//#endregion
//#region resources/js/Components/Asamblea/VideoPlayer.vue
var _sfc_setup$1 = VideoPlayer_vue_vue_type_script_setup_true_lang_default.setup;
VideoPlayer_vue_vue_type_script_setup_true_lang_default.setup = (props, ctx) => {
	const ssrContext = useSSRContext();
	(ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/Asamblea/VideoPlayer.vue");
	return _sfc_setup$1 ? _sfc_setup$1(props, ctx) : void 0;
};
var VideoPlayer_default = /* @__PURE__ */ _plugin_vue_export_helper_default(VideoPlayer_vue_vue_type_script_setup_true_lang_default, [["__scopeId", "data-v-4fe4e1d1"]]);
//#endregion
//#region resources/js/Composables/useAsambleaRealtime.ts
window.Pusher = Pusher;
function useAsambleaRealtime(asambleaId) {
	const activeQuestion = ref(null);
	const raisedHands = ref([]);
	const activeIntervencion = ref(null);
	const echo = ref(null);
	const initEcho = () => {
		if (typeof window === "undefined") return;
		echo.value = new Echo({
			broadcaster: "reverb",
			key: "nexo_pro_key",
			wsHost: "0.0.0.0",
			wsPort: "443",
			wssPort: "443",
			forceTLS: true,
			enabledTransports: ["ws", "wss"]
		});
		echo.value.channel(`asamblea.${asambleaId}`).listen(".hand.raised", (e) => {
			if (e.userData.is_raised) {
				if (!raisedHands.value.find((h) => h.user_id === e.userData.user_id)) raisedHands.value.push(e.userData);
			} else raisedHands.value = raisedHands.value.filter((h) => h.user_id !== e.userData.user_id);
		}).listen(".intervencion.updated", (e) => {
			const data = e.intervencionData;
			if (data.status === "pending") {
				if (!raisedHands.value.find((h) => h.user_id === data.user_id)) raisedHands.value.push({
					user_id: data.user_id,
					intervencion_id: data.id,
					name: data.user?.name || "Residente",
					unidad: data.user?.unidades?.[0] ? `${data.user.unidades[0].torre}-${data.user.unidades[0].nombre}` : "?"
				});
			} else raisedHands.value = raisedHands.value.filter((h) => h.user_id !== data.user_id);
			if (data.status === "active") activeIntervencion.value = data;
			else if ([
				"completed",
				"forced_close",
				"cancelled"
			].includes(data.status)) {
				if (activeIntervencion.value?.id === data.id) activeIntervencion.value = null;
			}
		});
		echo.value.private(`asamblea.${asambleaId}`).listen(".PreguntaOpened", (e) => {
			activeQuestion.value = e.pregunta;
		}).listen(".PreguntaClosed", (e) => {
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
//#endregion
//#region resources/js/Pages/Asamblea/Show.vue?vue&type=script&setup=true&lang.ts
var Show_vue_vue_type_script_setup_true_lang_default = /* @__PURE__ */ defineComponent({
	__name: "Show",
	__ssrInlineRender: true,
	props: {
		asamblea: {},
		token: {},
		unidad: {},
		is_admin: { type: Boolean },
		livekit_url: {},
		user: {}
	},
	setup(__props) {
		const props = __props;
		const toast = useToast();
		const { activeQuestion, raisedHands, activeIntervencion } = useAsambleaRealtime(props.asamblea.id);
		const activeTab = ref("interaccion");
		const isHandRaised = ref(false);
		const isVoting = ref(false);
		const hasVoted = ref(false);
		watch(raisedHands, (newList, oldList) => {
			if (newList.length > oldList.length) {
				const lastRaised = newList[newList.length - 1];
				if (lastRaised.user_id !== props.user.id) toast.add(`${lastRaised.unidad} (${lastRaised.name}) ha levantado la mano`, "info");
			}
		}, { deep: true });
		watch(activeIntervencion, (newVal, oldVal) => {
			if (newVal && !oldVal) if (newVal.user_id === props.user.id) toast.add("¡Es tu turno! Tu micrófono ha sido habilitado.", "success");
			else toast.add(`Turno de palabra concedido a: ${newVal.user?.name || "Residente"}`, "info");
			else if (!newVal && oldVal) toast.add("La intervención ha finalizado.", "secondary");
		});
		const endIntervencion = async (force = false) => {
			if (!activeIntervencion.value) return;
			try {
				await axios.post(`/asambleas/intervenciones/${activeIntervencion.value.id}/close`, {
					force,
					notes: force ? "Cierre forzado por moderador" : ""
				});
				toast.add(force ? "Intervención revocada" : "Intervención finalizada", "secondary");
			} catch (e) {
				toast.add("Error al finalizar intervención", "danger");
			}
		};
		const timeLeft = ref(0);
		let timerInterval = null;
		watch(activeIntervencion, (newVal) => {
			if (newVal && newVal.status === "active") {
				timeLeft.value = newVal.duration_seconds;
				if (timerInterval) clearInterval(timerInterval);
				timerInterval = setInterval(() => {
					if (timeLeft.value > 0) timeLeft.value--;
					else {
						clearInterval(timerInterval);
						if (props.is_admin) endIntervencion();
					}
				}, 1e3);
			} else {
				clearInterval(timerInterval);
				timeLeft.value = 0;
			}
		}, { immediate: true });
		const formatTime = (seconds) => {
			return `${Math.floor(seconds / 60)}:${(seconds % 60).toString().padStart(2, "0")}`;
		};
		watch(activeQuestion, () => {
			hasVoted.value = false;
		});
		const isCameraOn = ref(false);
		const isMicOn = ref(false);
		const videoPlayerRef = ref(null);
		const exitAsamblea = () => {
			if (props.is_admin) router.get(route("admin.asambleas.index"));
			else router.get(route("owner.dashboard"));
		};
		return (_ctx, _push, _parent, _attrs) => {
			_push(`<!--[-->`);
			_push(ssrRenderComponent(unref(Head), { title: `Asamblea — ${__props.asamblea.copropiedad.nombre}` }, null, _parent));
			_push(`<div class="fixed inset-0 bg-[#00173c] text-white font-sans selection:bg-primary/30 flex flex-col overflow-hidden" data-v-3408abc1><header class="shrink-0 px-4 py-3 border-b border-white/5 bg-white/2 backdrop-blur-md flex items-center justify-between z-40" data-v-3408abc1><div class="flex items-center gap-3" data-v-3408abc1><div class="w-8 h-8 rounded-xl bg-primary/20 flex items-center justify-center text-primary" data-v-3408abc1><span class="material-symbols-rounded text-xl" data-v-3408abc1>meeting_room</span></div><div data-v-3408abc1><h2 class="text-[11px] font-black text-white tracking-tight uppercase leading-none truncate max-w-[150px]" data-v-3408abc1>${ssrInterpolate(__props.asamblea.titulo)}</h2><p class="text-[8px] font-bold text-white/30 uppercase tracking-widest leading-none mt-1" data-v-3408abc1>${ssrInterpolate(__props.asamblea.copropiedad.nombre)}</p></div></div><div class="flex items-center gap-3" data-v-3408abc1>`);
			if (__props.unidad) _push(`<div class="hidden sm:flex flex-col items-end" data-v-3408abc1><p class="text-[8px] font-black text-white/20 uppercase tracking-widest" data-v-3408abc1>Unidad</p><p class="text-[10px] font-bold text-primary uppercase leading-none" data-v-3408abc1>${ssrInterpolate(__props.unidad.torre)}-${ssrInterpolate(__props.unidad.nombre)}</p></div>`);
			else _push(`<!---->`);
			_push(ssrRenderComponent(Button_default, {
				variant: "ghost",
				size: "sm",
				icon: "logout",
				onClick: exitAsamblea,
				class: "!w-8 !h-8 !p-0 !rounded-full !text-white/40 hover:!text-white"
			}, null, _parent));
			_push(`</div></header><main class="flex-1 flex flex-col lg:flex-row min-h-0 overflow-hidden" data-v-3408abc1><div class="flex-1 flex flex-col min-h-0 relative" data-v-3408abc1>`);
			if (unref(activeIntervencion)) _push(`<div class="absolute inset-x-0 top-4 flex justify-center z-30 px-4" data-v-3408abc1><div class="w-full max-w-sm bg-black/60 backdrop-blur-3xl border border-white/20 p-4 rounded-[2rem] shadow-3xl flex items-center justify-between" data-v-3408abc1><div class="flex items-center gap-3" data-v-3408abc1><div class="w-10 h-10 rounded-full bg-emerald-500 flex items-center justify-center animate-pulse" data-v-3408abc1><span class="material-symbols-rounded text-white text-xl" data-v-3408abc1>mic</span></div><div data-v-3408abc1><p class="text-[10px] font-black text-white uppercase leading-none" data-v-3408abc1>Hablando ahora</p><p class="text-[9px] font-bold text-white/50 uppercase mt-1" data-v-3408abc1>${ssrInterpolate(unref(activeIntervencion).user?.name || "Residente")}</p></div></div><div class="text-right" data-v-3408abc1><p class="text-2xl font-black text-white tabular-nums tracking-tighter leading-none" data-v-3408abc1>${ssrInterpolate(formatTime(timeLeft.value))}</p><p class="text-[8px] font-black text-emerald-400 uppercase tracking-widest mt-1" data-v-3408abc1>Tiempo restante</p></div></div></div>`);
			else _push(`<!---->`);
			_push(ssrRenderComponent(VideoPlayer_default, {
				ref_key: "videoPlayerRef",
				ref: videoPlayerRef,
				url: __props.livekit_url,
				token: __props.token
			}, null, _parent));
			if (unref(activeQuestion)) {
				_push(`<div class="absolute inset-x-0 bottom-4 flex justify-center z-30 px-4" data-v-3408abc1><div class="w-full bg-white/10 backdrop-blur-3xl border border-white/20 p-6 rounded-[2rem] shadow-3xl" data-v-3408abc1><h3 class="text-sm font-black text-white leading-tight mb-4 tracking-tight" data-v-3408abc1>${ssrInterpolate(unref(activeQuestion).titulo)}</h3>`);
				if (!hasVoted.value) {
					_push(`<div class="grid grid-cols-2 gap-3" data-v-3408abc1><!--[-->`);
					ssrRenderList(unref(activeQuestion).opciones, (opcion) => {
						_push(`<button${ssrIncludeBooleanAttr(isVoting.value) ? " disabled" : ""} class="py-3 px-4 bg-white text-primary rounded-xl font-black text-[10px] uppercase tracking-widest transition-all active:scale-95 shadow-lg" data-v-3408abc1>${ssrInterpolate(opcion.titulo)}</button>`);
					});
					_push(`<!--]--></div>`);
				} else _push(`<div class="py-3 text-center bg-emerald-500/20 border border-emerald-500/30 rounded-xl" data-v-3408abc1><p class="text-[9px] font-black text-emerald-400 uppercase tracking-widest" data-v-3408abc1>Voto registrado</p></div>`);
				_push(`</div></div>`);
			} else _push(`<!---->`);
			_push(`<div class="absolute bottom-6 left-1/2 -translate-x-1/2 flex items-center gap-2 bg-black/40 backdrop-blur-xl border border-white/10 p-1.5 rounded-full z-20 shadow-2xl" data-v-3408abc1>`);
			if (__props.is_admin) _push(`<!--[--><button class="${ssrRenderClass([isMicOn.value ? "bg-emerald-500 text-white" : "bg-white/5 text-white/40", "w-10 h-10 rounded-full flex items-center justify-center transition-all"])}" data-v-3408abc1><span class="material-symbols-rounded text-lg" data-v-3408abc1>${ssrInterpolate(isMicOn.value ? "mic" : "mic_off")}</span></button><button class="${ssrRenderClass([isCameraOn.value ? "bg-emerald-500 text-white" : "bg-white/5 text-white/40", "w-10 h-10 rounded-full flex items-center justify-center transition-all"])}" data-v-3408abc1><span class="material-symbols-rounded text-lg" data-v-3408abc1>${ssrInterpolate(isCameraOn.value ? "videocam" : "videocam_off")}</span></button><div class="w-px h-4 bg-white/10 mx-1" data-v-3408abc1></div><!--]-->`);
			else _push(`<!---->`);
			_push(`<button class="${ssrRenderClass([isHandRaised.value ? "bg-primary text-white scale-110 shadow-lg shadow-primary/30" : "bg-white/5 text-white/40", "w-10 h-10 rounded-full flex items-center justify-center transition-all"])}" data-v-3408abc1><span class="material-symbols-rounded text-lg" data-v-3408abc1>${ssrInterpolate(isHandRaised.value ? "pan_tool" : "front_hand")}</span></button></div></div>`);
			if (__props.is_admin && unref(activeIntervencion)) _push(`<div class="absolute top-24 right-6 flex flex-col gap-2 z-40" data-v-3408abc1><button class="p-3 bg-white/10 backdrop-blur-xl border border-white/20 rounded-2xl text-white hover:bg-white/20 transition-all flex items-center gap-2" data-v-3408abc1><span class="material-symbols-rounded text-sm" data-v-3408abc1>add_time</span><span class="text-[9px] font-black uppercase tracking-widest" data-v-3408abc1>+1 Min</span></button><button class="p-3 bg-red-500/20 backdrop-blur-xl border border-red-500/30 rounded-2xl text-red-400 hover:bg-red-500/30 transition-all flex items-center gap-2" data-v-3408abc1><span class="material-symbols-rounded text-sm" data-v-3408abc1>block</span><span class="text-[9px] font-black uppercase tracking-widest" data-v-3408abc1>Revocar</span></button></div>`);
			else _push(`<!---->`);
			_push(`<aside class="w-full lg:w-[350px] bg-white/2 border-t lg:border-t-0 lg:border-l border-white/5 flex flex-col shrink-0 min-h-0" data-v-3408abc1><nav class="flex border-b border-white/5 px-2 py-1.5 shrink-0 bg-white/2" data-v-3408abc1><!--[-->`);
			ssrRenderList([
				"interaccion",
				"quorum",
				"detalles"
			], (tab) => {
				_push(`<button class="${ssrRenderClass([activeTab.value === tab ? "bg-white/10 text-primary" : "text-white/40 hover:text-white/60", "flex-1 py-2 text-[8px] font-black uppercase tracking-widest transition-all rounded-lg"])}" data-v-3408abc1>${ssrInterpolate(tab)}</button>`);
			});
			_push(`<!--]--></nav><div class="flex-1 overflow-y-auto p-6 custom-scrollbar" data-v-3408abc1>`);
			if (activeTab.value === "interaccion") {
				_push(`<div class="space-y-6" data-v-3408abc1><div class="space-y-6" data-v-3408abc1>`);
				if (__props.is_admin) {
					_push(`<!--[--><h5 class="text-[9px] font-black text-amber-400 uppercase tracking-[0.2em] flex items-center gap-2" data-v-3408abc1><span class="material-symbols-rounded text-sm" data-v-3408abc1>front_hand</span> Peticiones de Palabra (${ssrInterpolate(unref(raisedHands).length)}) </h5><div class="space-y-2" data-v-3408abc1><!--[-->`);
					ssrRenderList(unref(raisedHands), (hand) => {
						_push(`<div class="flex items-center justify-between p-3 bg-white/5 border border-white/10 rounded-2xl animate-fade-in" data-v-3408abc1><div class="flex items-center gap-3" data-v-3408abc1><div class="w-8 h-8 rounded-xl bg-amber-500/20 flex items-center justify-center text-amber-500 font-black text-[10px]" data-v-3408abc1>${ssrInterpolate(hand.unidad.split("-")[1] || "?")}</div><div data-v-3408abc1><p class="text-[10px] font-black text-white uppercase leading-none" data-v-3408abc1>${ssrInterpolate(hand.name)}</p><p class="text-[8px] font-bold text-white/30 uppercase mt-1" data-v-3408abc1>Unidad ${ssrInterpolate(hand.unidad)}</p></div></div><button class="py-1 px-3 bg-emerald-500 text-white rounded-xl font-black text-[8px] uppercase tracking-widest transition-all active:scale-95 shadow-lg" data-v-3408abc1> Ceder Palabra </button></div>`);
					});
					_push(`<!--]-->`);
					if (unref(raisedHands).length === 0) _push(`<div class="text-center py-6 border-2 border-dashed border-white/5 rounded-2xl opacity-20" data-v-3408abc1><p class="text-[8px] font-black uppercase tracking-widest" data-v-3408abc1>Nadie ha levantado la mano</p></div>`);
					else _push(`<!---->`);
					_push(`</div><div class="h-px bg-white/10 my-6" data-v-3408abc1></div><!--]-->`);
				} else _push(`<!---->`);
				_push(`<h5 class="text-[9px] font-black text-white/40 uppercase tracking-[0.2em] flex items-center gap-2" data-v-3408abc1><span class="material-symbols-rounded text-sm" data-v-3408abc1>history</span> Actividad </h5></div><div class="space-y-4" data-v-3408abc1>`);
				if (hasVoted.value) _push(`<div class="p-4 rounded-2xl bg-emerald-500/5 border border-emerald-500/10 flex gap-3" data-v-3408abc1><span class="material-symbols-rounded text-emerald-400 text-sm" data-v-3408abc1>check_circle</span><p class="text-[10px] text-emerald-400/80 font-medium" data-v-3408abc1>Has participado en la última votación.</p></div>`);
				else _push(`<div class="text-center py-12 opacity-10" data-v-3408abc1><span class="material-symbols-rounded text-3xl block mb-2" data-v-3408abc1>sensors</span><p class="text-[8px] font-black uppercase tracking-widest" data-v-3408abc1>Esperando actividad...</p></div>`);
				_push(`</div></div>`);
			} else _push(`<!---->`);
			if (activeTab.value === "quorum") _push(`<div class="space-y-8 py-4" data-v-3408abc1><div class="relative w-28 h-28 mx-auto flex items-center justify-center" data-v-3408abc1><svg class="w-full h-full -rotate-90" data-v-3408abc1><circle cx="56" cy="56" r="50" fill="transparent" stroke="rgba(255,255,255,0.05)" stroke-width="8" data-v-3408abc1></circle><circle cx="56" cy="56" r="50" fill="transparent" stroke="var(--color-primary)" stroke-width="8" stroke-dasharray="314" stroke-dashoffset="88" stroke-linecap="round" data-v-3408abc1></circle></svg><span class="absolute text-2xl font-black text-white tracking-tighter" data-v-3408abc1>72%</span></div><p class="text-center text-[10px] font-black uppercase text-white/40 tracking-widest" data-v-3408abc1>Quórum Alcanzado</p></div>`);
			else _push(`<!---->`);
			_push(`</div></aside></main></div><!--]-->`);
		};
	}
});
//#endregion
//#region resources/js/Pages/Asamblea/Show.vue
var _sfc_setup = Show_vue_vue_type_script_setup_true_lang_default.setup;
Show_vue_vue_type_script_setup_true_lang_default.setup = (props, ctx) => {
	const ssrContext = useSSRContext();
	(ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Asamblea/Show.vue");
	return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
var Show_default = /* @__PURE__ */ _plugin_vue_export_helper_default(Show_vue_vue_type_script_setup_true_lang_default, [["__scopeId", "data-v-3408abc1"]]);
//#endregion
export { Show_default as default };

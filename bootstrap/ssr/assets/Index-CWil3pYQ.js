import { t as _plugin_vue_export_helper_default } from "./_plugin-vue_export-helper-BjJHhBxR.js";
import { t as Card_default } from "./Card-C2-I3_la.js";
import { t as Button_default } from "./Button-5V-KYwxi.js";
import { t as Badge_default } from "./Badge-DWJ2qZRx.js";
import { t as DatePicker_default } from "./DatePicker-BrH7TOb6.js";
import { t as OwnerLayout_default } from "./OwnerLayout-DdbYh73s.js";
import { Fragment, createBlock, createCommentVNode, createTextVNode, createVNode, defineComponent, openBlock, ref, renderList, toDisplayString, unref, useSSRContext, withCtx } from "vue";
import { Head, useForm } from "@inertiajs/vue3";
import { ssrIncludeBooleanAttr, ssrInterpolate, ssrRenderClass, ssrRenderComponent, ssrRenderList, ssrRenderStyle } from "vue/server-renderer";
//#region resources/js/Pages/Owner/Reservas/Index.vue?vue&type=script&setup=true&lang.ts
var Index_vue_vue_type_script_setup_true_lang_default = /* @__PURE__ */ defineComponent({
	layout: OwnerLayout_default,
	__name: "Index",
	__ssrInlineRender: true,
	props: {
		zonas: {},
		reservas: {},
		unidades: {},
		auth: {}
	},
	setup(__props) {
		const props = __props;
		const step = ref(1);
		const selectedZona = ref(null);
		const slots = [
			"09:00",
			"10:00",
			"11:00",
			"12:00",
			"13:00",
			"14:00",
			"15:00",
			"16:00",
			"17:00",
			"18:00",
			"19:00",
			"20:00"
		];
		const form = useForm({
			zona_id: "",
			unidad_id: props.unidades[0]?.id || "",
			fecha: "",
			hora_inicio: "",
			hora_fin: "",
			cantidad_personas: 1
		});
		const selectZona = (zona) => {
			selectedZona.value = zona;
			form.zona_id = zona.id;
			step.value = 2;
		};
		const selectSlot = (slot) => {
			form.hora_inicio = slot;
			const [h, m] = slot.split(":");
			form.hora_fin = `${String(parseInt(h) + 1).padStart(2, "0")}:${m}`;
			step.value = 3;
		};
		const submit = () => {
			form.post(route("reservas.store"), { onSuccess: () => {
				step.value = 1;
				selectedZona.value = null;
				form.reset();
			} });
		};
		const getStatusVariant = (status) => {
			switch (status) {
				case "pendiente": return "warning";
				case "aprobada": return "success";
				case "cancelada": return "neutral";
				case "pagada": return "secondary";
				default: return "neutral";
			}
		};
		return (_ctx, _push, _parent, _attrs) => {
			_push(`<!--[-->`);
			_push(ssrRenderComponent(unref(Head), { title: "Reservas - PH360" }, null, _parent));
			_push(`<div class="max-w-5xl mx-auto w-full pb-20" data-v-165c3487><header class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-6" data-v-165c3487><div data-v-165c3487><h2 class="text-3xl font-black text-primary tracking-tighter uppercase leading-none" data-v-165c3487>Reservas</h2><p class="text-xs font-bold text-on-surface-variant/60 uppercase tracking-widest mt-2" data-v-165c3487>Disfruta tus espacios comunes</p></div>`);
			if (step.value > 1 || selectedZona.value) _push(`<div class="flex items-center gap-4 bg-surface-container-low/50 py-2.5 px-6 rounded-2xl border border-outline-variant/10 shadow-sm" data-v-165c3487><span class="text-[9px] font-black text-on-surface-variant/40 uppercase tracking-[0.2em] mr-2" data-v-165c3487>Progreso ${ssrInterpolate(step.value)}/3</span><div class="flex items-center gap-2" data-v-165c3487><div class="${ssrRenderClass([step.value >= 1 ? "bg-primary text-on-primary shadow-md scale-110" : "bg-surface-container-high text-on-surface-variant/30", "w-6 h-6 rounded-full flex items-center justify-center text-[10px] font-black transition-all duration-500"])}" data-v-165c3487>1</div><div class="w-6 h-0.5 bg-outline-variant/20 rounded-full" data-v-165c3487></div><div class="${ssrRenderClass([step.value >= 2 ? "bg-primary text-on-primary shadow-md scale-110" : "bg-surface-container-high text-on-surface-variant/30", "w-6 h-6 rounded-full flex items-center justify-center text-[10px] font-black transition-all duration-500"])}" data-v-165c3487>2</div><div class="w-6 h-0.5 bg-outline-variant/20 rounded-full" data-v-165c3487></div><div class="${ssrRenderClass([step.value >= 3 ? "bg-primary text-on-primary shadow-md scale-110" : "bg-surface-container-high text-on-surface-variant/30", "w-6 h-6 rounded-full flex items-center justify-center text-[10px] font-black transition-all duration-500"])}" data-v-165c3487>3</div></div></div>`);
			else _push(`<!---->`);
			_push(`</header><div class="relative min-h-[500px]" data-v-165c3487>`);
			if (step.value === 1) {
				_push(`<div class="space-y-12" data-v-165c3487><section data-v-165c3487><h3 class="text-xs font-black text-on-surface uppercase tracking-[0.2em] mb-8 flex items-center gap-2" data-v-165c3487><span class="material-symbols-outlined text-sm text-primary" data-v-165c3487>touch_app</span> Selecciona el espacio </h3><div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" data-v-165c3487><!--[-->`);
				ssrRenderList(__props.zonas, (zona) => {
					_push(ssrRenderComponent(Card_default, {
						key: zona.id,
						hover: "",
						onClick: ($event) => selectZona(zona),
						class: "cursor-pointer group",
						"content-class": "!pt-4"
					}, {
						default: withCtx((_, _push, _parent, _scopeId) => {
							if (_push) {
								_push(`<div class="flex flex-col gap-6" data-v-165c3487${_scopeId}><div class="w-14 h-14 rounded-2xl bg-primary/5 text-primary flex items-center justify-center border border-primary/10 shadow-sm group-hover:scale-110 group-hover:bg-primary group-hover:text-on-primary transition-all duration-500" data-v-165c3487${_scopeId}><span class="material-symbols-outlined text-3xl" data-v-165c3487${_scopeId}>${ssrInterpolate(zona.nombre.toLowerCase().includes("piscina") ? "pool" : "meeting_room")}</span></div><div data-v-165c3487${_scopeId}><p class="text-xl font-black text-on-surface uppercase tracking-tight leading-none mb-2" data-v-165c3487${_scopeId}>${ssrInterpolate(zona.nombre)}</p><div class="flex items-center gap-3" data-v-165c3487${_scopeId}>`);
								_push(ssrRenderComponent(Badge_default, {
									variant: "neutral",
									class: "!lowercase"
								}, {
									default: withCtx((_, _push, _parent, _scopeId) => {
										if (_push) _push(`Capacidad: ${ssrInterpolate(zona.capacidad_maxima)} personas`);
										else return [createTextVNode("Capacidad: " + toDisplayString(zona.capacidad_maxima) + " personas", 1)];
									}),
									_: 2
								}, _parent, _scopeId));
								_push(`</div></div><div class="flex items-center justify-between border-t border-outline-variant/5 pt-4" data-v-165c3487${_scopeId}><span class="${ssrRenderClass([zona.costo > 0 ? "text-secondary" : "text-green-600", "text-[11px] font-black uppercase tracking-widest"])}" data-v-165c3487${_scopeId}>${ssrInterpolate(zona.costo > 0 ? "$" + new Intl.NumberFormat("es-CO").format(zona.costo) : "Gratuito")}</span><span class="material-symbols-outlined text-on-surface-variant/20 group-hover:text-primary group-hover:translate-x-1 transition-all" data-v-165c3487${_scopeId}>arrow_forward</span></div></div>`);
							} else return [createVNode("div", { class: "flex flex-col gap-6" }, [
								createVNode("div", { class: "w-14 h-14 rounded-2xl bg-primary/5 text-primary flex items-center justify-center border border-primary/10 shadow-sm group-hover:scale-110 group-hover:bg-primary group-hover:text-on-primary transition-all duration-500" }, [createVNode("span", { class: "material-symbols-outlined text-3xl" }, toDisplayString(zona.nombre.toLowerCase().includes("piscina") ? "pool" : "meeting_room"), 1)]),
								createVNode("div", null, [createVNode("p", { class: "text-xl font-black text-on-surface uppercase tracking-tight leading-none mb-2" }, toDisplayString(zona.nombre), 1), createVNode("div", { class: "flex items-center gap-3" }, [createVNode(Badge_default, {
									variant: "neutral",
									class: "!lowercase"
								}, {
									default: withCtx(() => [createTextVNode("Capacidad: " + toDisplayString(zona.capacidad_maxima) + " personas", 1)]),
									_: 2
								}, 1024)])]),
								createVNode("div", { class: "flex items-center justify-between border-t border-outline-variant/5 pt-4" }, [createVNode("span", { class: ["text-[11px] font-black uppercase tracking-widest", zona.costo > 0 ? "text-secondary" : "text-green-600"] }, toDisplayString(zona.costo > 0 ? "$" + new Intl.NumberFormat("es-CO").format(zona.costo) : "Gratuito"), 3), createVNode("span", { class: "material-symbols-outlined text-on-surface-variant/20 group-hover:text-primary group-hover:translate-x-1 transition-all" }, "arrow_forward")])
							])];
						}),
						_: 2
					}, _parent));
				});
				_push(`<!--]--></div></section><section class="mt-16" data-v-165c3487><h3 class="text-xs font-black text-on-surface-variant/40 uppercase tracking-widest mb-8 flex items-center gap-2" data-v-165c3487><span class="material-symbols-outlined text-sm" data-v-165c3487>history</span> Mis Reservas Recientes </h3><div class="grid grid-cols-1 md:grid-cols-2 gap-4" data-v-165c3487><!--[-->`);
				ssrRenderList(__props.reservas, (res) => {
					_push(`<div class="bg-surface-container-low/50 rounded-2xl p-5 border border-outline-variant/10 flex items-center justify-between hover:bg-surface-container-low transition-colors" data-v-165c3487><div class="flex items-center gap-4" data-v-165c3487><div class="w-10 h-10 rounded-xl bg-white flex items-center justify-center text-primary shadow-sm border border-outline-variant/10" data-v-165c3487><span class="material-symbols-outlined text-xl" data-v-165c3487>event_available</span></div><div data-v-165c3487><p class="text-[11px] font-black text-on-surface uppercase tracking-tight" data-v-165c3487>${ssrInterpolate(res.zona.nombre)}</p><p class="text-[9px] text-on-surface-variant/50 font-bold uppercase mt-0.5" data-v-165c3487>${ssrInterpolate(new Date(res.fecha).toLocaleDateString())} • ${ssrInterpolate(res.hora_inicio.slice(0, 5))}</p></div></div>`);
					_push(ssrRenderComponent(Badge_default, { variant: getStatusVariant(res.estado) }, {
						default: withCtx((_, _push, _parent, _scopeId) => {
							if (_push) _push(`${ssrInterpolate(res.estado)}`);
							else return [createTextVNode(toDisplayString(res.estado), 1)];
						}),
						_: 2
					}, _parent));
					_push(`</div>`);
				});
				_push(`<!--]--></div>`);
				if (__props.reservas.length === 0) _push(`<div class="py-12 text-center opacity-20 flex flex-col items-center gap-3" data-v-165c3487><span class="material-symbols-outlined text-4xl italic" data-v-165c3487>calendar_today</span><p class="text-[10px] font-black uppercase tracking-widest" data-v-165c3487>Sin actividad reciente</p></div>`);
				else _push(`<!---->`);
				_push(`</section></div>`);
			} else if (step.value === 2) {
				_push(`<div class="max-w-2xl mx-auto space-y-8" data-v-165c3487><div class="flex items-center gap-4" data-v-165c3487>`);
				_push(ssrRenderComponent(Button_default, {
					variant: "ghost",
					icon: "arrow_back",
					class: "!rounded-full !w-12 !h-12 !p-0",
					onClick: ($event) => step.value = 1
				}, null, _parent));
				_push(`<div data-v-165c3487><p class="text-[10px] font-bold text-on-surface-variant/40 uppercase tracking-widest leading-none" data-v-165c3487>Paso 2 de 3</p><h3 class="text-2xl font-black text-primary uppercase tracking-tighter mt-1" data-v-165c3487>${ssrInterpolate(selectedZona.value.nombre)}</h3></div></div>`);
				_push(ssrRenderComponent(Card_default, { "content-class": "space-y-10" }, {
					default: withCtx((_, _push, _parent, _scopeId) => {
						if (_push) {
							_push(ssrRenderComponent(DatePicker_default, {
								modelValue: unref(form).fecha,
								"onUpdate:modelValue": ($event) => unref(form).fecha = $event,
								label: "1. Selecciona el día",
								icon: "calendar_today",
								error: unref(form).errors.fecha
							}, null, _parent, _scopeId));
							_push(`<div data-v-165c3487${_scopeId}><label class="text-[10px] font-black text-primary uppercase tracking-[0.2em] mb-6 flex items-center gap-2" data-v-165c3487${_scopeId}><span class="bg-primary/10 text-primary w-5 h-5 rounded flex items-center justify-center text-[10px]" data-v-165c3487${_scopeId}>2</span> Elige el horario (Bloques de 1h) </label><div class="grid grid-cols-3 md:grid-cols-4 gap-3" data-v-165c3487${_scopeId}><!--[-->`);
							ssrRenderList(slots, (slot) => {
								_push(`<button${ssrIncludeBooleanAttr(!unref(form).fecha) ? " disabled" : ""} class="${ssrRenderClass([[!unref(form).fecha ? "opacity-20 cursor-not-allowed" : "", unref(form).hora_inicio === slot ? "bg-primary text-on-primary shadow-xl shadow-primary/20 scale-105" : "bg-surface-container-high text-on-surface-variant hover:bg-outline-variant/30 hover:scale-105"], "py-4 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all duration-300"])}" data-v-165c3487${_scopeId}>${ssrInterpolate(slot)}</button>`);
							});
							_push(`<!--]--></div>`);
							if (!unref(form).fecha) _push(`<p class="text-[10px] text-error font-black uppercase tracking-widest mt-6 text-center italic" data-v-165c3487${_scopeId}>Selecciona una fecha primero</p>`);
							else _push(`<!---->`);
							_push(`</div>`);
						} else return [createVNode(DatePicker_default, {
							modelValue: unref(form).fecha,
							"onUpdate:modelValue": ($event) => unref(form).fecha = $event,
							label: "1. Selecciona el día",
							icon: "calendar_today",
							error: unref(form).errors.fecha
						}, null, 8, [
							"modelValue",
							"onUpdate:modelValue",
							"error"
						]), createVNode("div", null, [
							createVNode("label", { class: "text-[10px] font-black text-primary uppercase tracking-[0.2em] mb-6 flex items-center gap-2" }, [createVNode("span", { class: "bg-primary/10 text-primary w-5 h-5 rounded flex items-center justify-center text-[10px]" }, "2"), createTextVNode(" Elige el horario (Bloques de 1h) ")]),
							createVNode("div", { class: "grid grid-cols-3 md:grid-cols-4 gap-3" }, [(openBlock(), createBlock(Fragment, null, renderList(slots, (slot) => {
								return createVNode("button", {
									key: slot,
									onClick: ($event) => selectSlot(slot),
									disabled: !unref(form).fecha,
									class: [[!unref(form).fecha ? "opacity-20 cursor-not-allowed" : "", unref(form).hora_inicio === slot ? "bg-primary text-on-primary shadow-xl shadow-primary/20 scale-105" : "bg-surface-container-high text-on-surface-variant hover:bg-outline-variant/30 hover:scale-105"], "py-4 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all duration-300"]
								}, toDisplayString(slot), 11, ["onClick", "disabled"]);
							}), 64))]),
							!unref(form).fecha ? (openBlock(), createBlock("p", {
								key: 0,
								class: "text-[10px] text-error font-black uppercase tracking-widest mt-6 text-center italic"
							}, "Selecciona una fecha primero")) : createCommentVNode("", true)
						])];
					}),
					_: 1
				}, _parent));
				_push(`</div>`);
			} else if (step.value === 3) {
				_push(`<div class="max-w-xl mx-auto space-y-8" data-v-165c3487><div class="flex items-center gap-4" data-v-165c3487>`);
				_push(ssrRenderComponent(Button_default, {
					variant: "ghost",
					icon: "arrow_back",
					class: "!rounded-full !w-12 !h-12 !p-0",
					onClick: ($event) => step.value = 2
				}, null, _parent));
				_push(`<h3 class="text-xl font-black text-on-surface-variant/40 uppercase tracking-widest" data-v-165c3487>Verifica los datos</h3></div><div class="bg-primary text-on-primary rounded-[3rem] p-10 md:p-12 shadow-2xl relative overflow-hidden text-center md:text-left" data-v-165c3487><div class="absolute -top-10 -right-10 opacity-5 pointer-events-none" data-v-165c3487><span class="material-symbols-outlined text-[200px]" data-v-165c3487>verified</span></div><div class="mb-12 text-center" data-v-165c3487><div class="w-20 h-20 bg-white/10 rounded-[2rem] flex items-center justify-center mx-auto mb-6 border border-white/20 shadow-inner" data-v-165c3487><span class="material-symbols-outlined text-4xl" style="${ssrRenderStyle({ "font-variation-settings": "'FILL' 1" })}" data-v-165c3487>task_alt</span></div><h3 class="text-4xl font-black tracking-tighter uppercase mb-2" data-v-165c3487>Confirmación</h3><p class="text-on-primary-container text-[10px] font-bold uppercase tracking-widest opacity-60" data-v-165c3487>Resumen de tu solicitud de espacio</p></div><div class="bg-white/5 rounded-[2rem] p-8 backdrop-blur-md border border-white/10 space-y-8 text-left relative z-10" data-v-165c3487><div class="flex items-center gap-5" data-v-165c3487><div class="w-14 h-14 rounded-2xl bg-secondary/20 text-secondary flex items-center justify-center shrink-0 border border-secondary/20" data-v-165c3487><span class="material-symbols-outlined text-2xl" data-v-165c3487>location_on</span></div><div class="flex-1 min-w-0" data-v-165c3487><p class="text-[9px] text-white/40 font-black uppercase tracking-[0.2em] mb-1" data-v-165c3487>Espacio solicitado</p><p class="text-xl font-black tracking-tight uppercase truncate" data-v-165c3487>${ssrInterpolate(selectedZona.value.nombre)}</p></div></div><div class="flex items-center gap-5" data-v-165c3487><div class="w-14 h-14 rounded-2xl bg-emerald-500/20 text-emerald-300 flex items-center justify-center shrink-0 border border-emerald-500/20" data-v-165c3487><span class="material-symbols-outlined text-2xl" data-v-165c3487>calendar_today</span></div><div data-v-165c3487><p class="text-[9px] text-white/40 font-black uppercase tracking-[0.2em] mb-1" data-v-165c3487>Fecha y Franja Horaria</p><p class="text-lg font-black tracking-tight" data-v-165c3487>${ssrInterpolate(unref(form).fecha.split("-").reverse().join(" / "))}</p><p class="text-xs font-bold opacity-60" data-v-165c3487>${ssrInterpolate(unref(form).hora_inicio)} - ${ssrInterpolate(unref(form).hora_fin)} (1 hora)</p></div></div></div><div class="mt-10 flex flex-wrap justify-center gap-x-6 gap-y-3 text-[10px] font-black uppercase tracking-widest opacity-40" data-v-165c3487><span class="flex items-center gap-2" data-v-165c3487><span class="material-symbols-outlined text-sm" data-v-165c3487>cleaning_services</span> Entrega aseada</span><span class="flex items-center gap-2" data-v-165c3487><span class="material-symbols-outlined text-sm" data-v-165c3487>groups</span> Máx ${ssrInterpolate(selectedZona.value.capacidad_maxima)} px</span></div><div class="mt-12" data-v-165c3487>`);
				_push(ssrRenderComponent(Button_default, {
					variant: "secondary",
					size: "lg",
					class: "w-full !rounded-[1.5rem] !py-6 shadow-2xl shadow-black/40",
					loading: unref(form).processing,
					onClick: submit
				}, {
					default: withCtx((_, _push, _parent, _scopeId) => {
						if (_push) _push(` Confirmar Reserva `);
						else return [createTextVNode(" Confirmar Reserva ")];
					}),
					_: 1
				}, _parent));
				_push(`</div></div></div>`);
			} else _push(`<!---->`);
			_push(`</div></div><!--]-->`);
		};
	}
});
//#endregion
//#region resources/js/Pages/Owner/Reservas/Index.vue
var _sfc_setup = Index_vue_vue_type_script_setup_true_lang_default.setup;
Index_vue_vue_type_script_setup_true_lang_default.setup = (props, ctx) => {
	const ssrContext = useSSRContext();
	(ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Owner/Reservas/Index.vue");
	return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
var Index_default = /* @__PURE__ */ _plugin_vue_export_helper_default(Index_vue_vue_type_script_setup_true_lang_default, [["__scopeId", "data-v-165c3487"]]);
//#endregion
export { Index_default as default };

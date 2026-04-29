import { t as _plugin_vue_export_helper_default } from "./_plugin-vue_export-helper-BjJHhBxR.js";
import { t as useToast } from "./useToast-E_Hdzmc5.js";
import { t as AuthenticatedLayout_default } from "./AuthenticatedLayout-vxulH9w9.js";
import { t as Card_default } from "./Card-D4IwBRMd.js";
import { t as Button_default } from "./Button-C-byZbTR.js";
import { t as Modal_default } from "./Modal-DfwT9E3X.js";
import { t as Badge_default } from "./Badge-DsYztQFV.js";
import { t as Select_default } from "./Select-DRXhACf5.js";
import { t as Input_default } from "./Input-CbVZZMpc.js";
import { createTextVNode, createVNode, defineComponent, onMounted, ref, toDisplayString, unref, useSSRContext, vModelText, withCtx, withDirectives, withModifiers } from "vue";
import { Head, useForm } from "@inertiajs/vue3";
import { ssrInterpolate, ssrRenderClass, ssrRenderComponent, ssrRenderList } from "vue/server-renderer";
//#region resources/js/Pages/Support/Index.vue?vue&type=script&setup=true&lang.ts
var Index_vue_vue_type_script_setup_true_lang_default = /* @__PURE__ */ defineComponent({
	layout: AuthenticatedLayout_default,
	__name: "Index",
	__ssrInlineRender: true,
	props: {
		tickets: {},
		categories: {}
	},
	setup(__props) {
		const toast = useToast();
		const showCreateModal = ref(false);
		const isLoaded = ref(false);
		onMounted(() => {
			setTimeout(() => isLoaded.value = true, 400);
		});
		const form = useForm({
			subject: "",
			description: "",
			category: "other",
			priority: "low"
		});
		const priorityOptions = [
			{
				value: "low",
				label: "BAJA (Consulta / Duda)"
			},
			{
				value: "medium",
				label: "MEDIA (Error parcial)"
			},
			{
				value: "high",
				label: "ALTA (Función bloqueada)"
			},
			{
				value: "critical",
				label: "CRÍTICA (Caída de sistema)"
			}
		];
		const submitTicket = () => {
			form.post(route("support.store"), { onSuccess: () => {
				showCreateModal.value = false;
				form.reset();
				toast.add("Misión crítica reportada. Ingeniería en camino.", "success");
			} });
		};
		const getStatusVariant = (status) => {
			return {
				"open": "warning",
				"in_progress": "primary",
				"resolved": "success",
				"closed": "neutral"
			}[status] || "neutral";
		};
		const getPriorityColor = (priority) => {
			return {
				"low": "text-emerald-500 bg-emerald-500/10 border-emerald-500/20",
				"medium": "text-amber-500 bg-amber-500/10 border-amber-500/20",
				"high": "text-orange-500 bg-orange-500/10 border-orange-500/20",
				"critical": "text-red-500 bg-red-500/10 border-red-500/20 animate-pulse"
			}[priority] || "text-white/20 bg-white/5 border-white/5";
		};
		const formatDate = (date) => {
			return new Date(date).toLocaleDateString("es-CO", {
				day: "2-digit",
				month: "short",
				year: "numeric"
			}).toUpperCase();
		};
		return (_ctx, _push, _parent, _attrs) => {
			_push(`<!--[-->`);
			_push(ssrRenderComponent(unref(Head), { title: "Centro de Soporte Técnico — NEXO-PRO" }, null, _parent));
			_push(`<div class="space-y-12 pb-32 animate-in fade-in slide-in-from-bottom-4 duration-700" data-v-9bb53f46><div class="flex flex-col md:flex-row md:items-end justify-between gap-8 px-1" data-v-9bb53f46><div class="space-y-4" data-v-9bb53f46><div class="flex items-center gap-3" data-v-9bb53f46><div class="w-2 h-8 bg-primary rounded-full shadow-[0_0_15px_rgba(var(--primary),0.4)]" data-v-9bb53f46></div><p class="text-[10px] font-black text-on-surface-variant/40 dark:text-white/30 uppercase tracking-[0.5em] italic leading-none" data-v-9bb53f46>Dispatcher de Incidencias Técnicas</p></div><h2 class="text-5xl md:text-6xl font-black text-on-surface dark:text-white tracking-tighter uppercase italic leading-none" data-v-9bb53f46> Soporte <span class="text-primary italic" data-v-9bb53f46>Nexo-Pro</span></h2><p class="text-[11px] font-bold text-on-surface-variant/40 dark:text-white/10 uppercase tracking-[0.2em] mt-2 leading-relaxed italic" data-v-9bb53f46>Reporte de fallos del sistema, errores funcionales y escalabilidad técnica</p></div>`);
			_push(ssrRenderComponent(Button_default, {
				variant: "primary",
				icon: "add_reaction",
				class: "!rounded-[2rem] !h-20 !px-12 text-xs font-black uppercase italic shadow-2xl shadow-primary/20 hover:scale-105 active:scale-95 transition-all",
				onClick: ($event) => showCreateModal.value = true
			}, {
				default: withCtx((_, _push, _parent, _scopeId) => {
					if (_push) _push(`Nuevo reporte de sistema`);
					else return [createTextVNode("Nuevo reporte de sistema")];
				}),
				_: 1
			}, _parent));
			_push(`</div><div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8" data-v-9bb53f46>`);
			_push(ssrRenderComponent(Card_default, { class: "!p-10 !rounded-[3rem] border border-outline-variant/10 dark:border-white/5 shadow-2xl dark:bg-[#0b0e14] relative overflow-hidden group" }, {
				default: withCtx((_, _push, _parent, _scopeId) => {
					if (_push) _push(`<div class="absolute top-0 right-0 w-40 h-40 bg-primary/5 -mr-16 -mt-16 rounded-full blur-3xl group-hover:bg-primary/10 transition-all duration-1000" data-v-9bb53f46${_scopeId}></div><p class="text-[10px] font-black text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.3em] mb-8 italic leading-none" data-v-9bb53f46${_scopeId}>Tickets en Proceso</p><div class="flex items-end gap-4" data-v-9bb53f46${_scopeId}><h3 class="text-6xl font-black text-primary tracking-tighter leading-none italic" data-v-9bb53f46${_scopeId}>${ssrInterpolate(__props.tickets.filter((t) => t.status === "in_progress").length)}</h3><span class="text-[9px] font-black text-on-surface-variant/40 dark:text-white/20 uppercase tracking-widest pb-2 italic" data-v-9bb53f46${_scopeId}>Análisis Activo</span></div>`);
					else return [
						createVNode("div", { class: "absolute top-0 right-0 w-40 h-40 bg-primary/5 -mr-16 -mt-16 rounded-full blur-3xl group-hover:bg-primary/10 transition-all duration-1000" }),
						createVNode("p", { class: "text-[10px] font-black text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.3em] mb-8 italic leading-none" }, "Tickets en Proceso"),
						createVNode("div", { class: "flex items-end gap-4" }, [createVNode("h3", { class: "text-6xl font-black text-primary tracking-tighter leading-none italic" }, toDisplayString(__props.tickets.filter((t) => t.status === "in_progress").length), 1), createVNode("span", { class: "text-[9px] font-black text-on-surface-variant/40 dark:text-white/20 uppercase tracking-widest pb-2 italic" }, "Análisis Activo")])
					];
				}),
				_: 1
			}, _parent));
			_push(ssrRenderComponent(Card_default, { class: "!p-10 !rounded-[3rem] border border-outline-variant/10 dark:border-white/5 shadow-2xl dark:bg-[#0b0e14]" }, {
				default: withCtx((_, _push, _parent, _scopeId) => {
					if (_push) _push(`<p class="text-[10px] font-black text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.3em] mb-8 italic leading-none" data-v-9bb53f46${_scopeId}>Resueltos (30 días)</p><div class="flex items-end gap-4" data-v-9bb53f46${_scopeId}><h3 class="text-6xl font-black text-emerald-500 tracking-tighter leading-none italic" data-v-9bb53f46${_scopeId}>${ssrInterpolate(__props.tickets.filter((t) => t.status === "resolved").length)}</h3><span class="text-[9px] font-black text-emerald-500/40 uppercase tracking-widest pb-2 italic" data-v-9bb53f46${_scopeId}>Sistema Estable</span></div>`);
					else return [createVNode("p", { class: "text-[10px] font-black text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.3em] mb-8 italic leading-none" }, "Resueltos (30 días)"), createVNode("div", { class: "flex items-end gap-4" }, [createVNode("h3", { class: "text-6xl font-black text-emerald-500 tracking-tighter leading-none italic" }, toDisplayString(__props.tickets.filter((t) => t.status === "resolved").length), 1), createVNode("span", { class: "text-[9px] font-black text-emerald-500/40 uppercase tracking-widest pb-2 italic" }, "Sistema Estable")])];
				}),
				_: 1
			}, _parent));
			_push(ssrRenderComponent(Card_default, { class: "md:col-span-2 lg:col-span-1 !p-10 !rounded-[3rem] border border-outline-variant/10 dark:border-white/5 shadow-2xl dark:bg-[#0b0e14] flex flex-col justify-center bg-gradient-to-br from-emerald-500/[0.03] to-transparent" }, {
				default: withCtx((_, _push, _parent, _scopeId) => {
					if (_push) _push(`<div class="flex items-center gap-6" data-v-9bb53f46${_scopeId}><div class="w-16 h-16 bg-emerald-500/10 rounded-2xl flex items-center justify-center text-emerald-500 border border-emerald-500/20 shadow-lg shadow-emerald-500/10" data-v-9bb53f46${_scopeId}><span class="material-symbols-rounded text-3xl" data-v-9bb53f46${_scopeId}>verified</span></div><div data-v-9bb53f46${_scopeId}><p class="text-sm font-black text-on-surface dark:text-white uppercase italic tracking-tighter leading-none" data-v-9bb53f46${_scopeId}>Ingeniería Online</p><p class="text-[9px] font-bold text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.2em] mt-2" data-v-9bb53f46${_scopeId}>SLA MONITOR: ACTIVO</p></div></div>`);
					else return [createVNode("div", { class: "flex items-center gap-6" }, [createVNode("div", { class: "w-16 h-16 bg-emerald-500/10 rounded-2xl flex items-center justify-center text-emerald-500 border border-emerald-500/20 shadow-lg shadow-emerald-500/10" }, [createVNode("span", { class: "material-symbols-rounded text-3xl" }, "verified")]), createVNode("div", null, [createVNode("p", { class: "text-sm font-black text-on-surface dark:text-white uppercase italic tracking-tighter leading-none" }, "Ingeniería Online"), createVNode("p", { class: "text-[9px] font-bold text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.2em] mt-2" }, "SLA MONITOR: ACTIVO")])])];
				}),
				_: 1
			}, _parent));
			_push(`</div><div class="space-y-8" data-v-9bb53f46><!--[-->`);
			ssrRenderList(__props.tickets, (ticket) => {
				_push(`<div class="group bg-white dark:bg-[#0b0e14] border border-outline-variant/10 dark:border-white/5 p-10 rounded-[3.5rem] shadow-xl hover:shadow-primary/5 transition-all duration-700" data-v-9bb53f46><div class="flex flex-col lg:flex-row gap-10 lg:items-center" data-v-9bb53f46><div class="shrink-0 flex items-center justify-center" data-v-9bb53f46><div class="${ssrRenderClass([getPriorityColor(ticket.priority), "w-20 h-20 rounded-[2rem] flex items-center justify-center border-2 shadow-inner transition-transform group-hover:scale-105 duration-500"])}" data-v-9bb53f46><span class="material-symbols-rounded text-4xl leading-none" data-v-9bb53f46>${ssrInterpolate(ticket.priority === "critical" ? "priority_high" : "terminal")}</span></div></div><div class="flex-1 space-y-4" data-v-9bb53f46><div class="flex flex-wrap items-center gap-4" data-v-9bb53f46><h4 class="text-2xl font-black text-on-surface dark:text-white uppercase tracking-tighter italic leading-none" data-v-9bb53f46>${ssrInterpolate(ticket.subject)}</h4>`);
				_push(ssrRenderComponent(Badge_default, {
					variant: getStatusVariant(ticket.status),
					class: "!px-5 !py-1 !text-[9px] font-black uppercase italic tracking-widest !rounded-xl border-2"
				}, {
					default: withCtx((_, _push, _parent, _scopeId) => {
						if (_push) _push(`${ssrInterpolate(ticket.status.replace("_", " "))}`);
						else return [createTextVNode(toDisplayString(ticket.status.replace("_", " ")), 1)];
					}),
					_: 2
				}, _parent));
				_push(`</div><p class="text-sm border-l-4 border-primary/20 pl-6 font-medium text-on-surface-variant dark:text-white/50 leading-relaxed italic pr-12" data-v-9bb53f46>${ssrInterpolate(ticket.description)}</p><div class="flex flex-wrap items-center gap-8 pt-4 border-t border-outline-variant/5 dark:border-white/[0.02]" data-v-9bb53f46><div class="flex items-center gap-3" data-v-9bb53f46><span class="material-symbols-rounded text-lg text-primary/40" data-v-9bb53f46>category</span><span class="text-[10px] font-black text-on-surface dark:text-white/70 uppercase tracking-widest" data-v-9bb53f46>${ssrInterpolate(__props.categories.find((c) => c.value === ticket.category)?.label)}</span></div><div class="flex items-center gap-3" data-v-9bb53f46><span class="material-symbols-rounded text-lg text-primary/40" data-v-9bb53f46>calendar_month</span><span class="text-[10px] font-black text-on-surface dark:text-white/60 uppercase tracking-widest italic tabular-nums" data-v-9bb53f46>${ssrInterpolate(formatDate(ticket.created_at))}</span></div><div class="flex items-center gap-3" data-v-9bb53f46><span class="material-symbols-rounded text-lg text-primary/40" data-v-9bb53f46>fingerprint</span><span class="text-[10px] font-black text-on-surface-variant/40 dark:text-white/30 uppercase tracking-widest tabular-nums font-mono" data-v-9bb53f46>RAD_#${ssrInterpolate(ticket.id.slice(0, 8).toUpperCase())}</span></div></div></div>`);
				if (ticket.resolution_notes) _push(`<div class="lg:w-1/3 bg-primary/[0.02] dark:bg-white/[0.01] p-8 rounded-[2.5rem] border border-primary/10 relative overflow-hidden backdrop-blur-sm" data-v-9bb53f46><div class="absolute top-0 right-0 p-3 opacity-10" data-v-9bb53f46><span class="material-symbols-rounded text-4xl" data-v-9bb53f46>vpn_key</span></div><p class="text-[9px] font-black text-primary uppercase tracking-[0.4em] mb-4 leading-none italic" data-v-9bb53f46>Análisis Final:</p><p class="text-xs font-semibold text-on-surface-variant dark:text-white/60 leading-relaxed italic pr-4" data-v-9bb53f46>${ssrInterpolate(ticket.resolution_notes)}</p></div>`);
				else _push(`<!---->`);
				_push(`</div></div>`);
			});
			_push(`<!--]-->`);
			if (__props.tickets.length === 0) _push(`<div class="flex flex-col items-center justify-center py-40 border-2 border-dashed border-outline-variant/10 dark:border-white/10 rounded-[4rem] group hover:border-primary/20 transition-all duration-700" data-v-9bb53f46><div class="w-24 h-24 bg-emerald-500/5 rounded-full flex items-center justify-center mb-8 group-hover:scale-110 transition-transform duration-700 shadow-2xl" data-v-9bb53f46><span class="material-symbols-rounded text-6xl text-emerald-500/40" data-v-9bb53f46>verified</span></div><p class="text-xs font-black text-on-surface-variant/30 uppercase tracking-[0.4em] italic" data-v-9bb53f46>Sistema Operativo al 100%</p></div>`);
			else _push(`<!---->`);
			_push(`</div>`);
			_push(ssrRenderComponent(Modal_default, {
				show: showCreateModal.value,
				onClose: ($event) => showCreateModal.value = false,
				"max-width": "4xl",
				title: "Protocolo de Reporte de Incidencias"
			}, {
				default: withCtx((_, _push, _parent, _scopeId) => {
					if (_push) {
						_push(`<form class="space-y-12" data-v-9bb53f46${_scopeId}><div class="space-y-10" data-v-9bb53f46${_scopeId}><div class="space-y-2" data-v-9bb53f46${_scopeId}>`);
						_push(ssrRenderComponent(Input_default, {
							modelValue: unref(form).subject,
							"onUpdate:modelValue": ($event) => unref(form).subject = $event,
							label: "Asunto de la Incidencias",
							placeholder: "Ej: Fallo crítico en el motor de reservas...",
							required: "",
							icon: "emergency",
							class: "px-1"
						}, null, _parent, _scopeId));
						_push(`</div><div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-10 px-1" data-v-9bb53f46${_scopeId}>`);
						_push(ssrRenderComponent(Select_default, {
							modelValue: unref(form).category,
							"onUpdate:modelValue": ($event) => unref(form).category = $event,
							label: "Módulo Afectado",
							options: __props.categories,
							icon: "settings_input_component"
						}, null, _parent, _scopeId));
						_push(ssrRenderComponent(Select_default, {
							modelValue: unref(form).priority,
							"onUpdate:modelValue": ($event) => unref(form).priority = $event,
							label: "Criticidad Percibida",
							options: priorityOptions,
							icon: "bolt"
						}, null, _parent, _scopeId));
						_push(`</div><div class="space-y-4 px-1" data-v-9bb53f46${_scopeId}><div class="flex items-center gap-3 ml-2" data-v-9bb53f46${_scopeId}><div class="w-1 h-4 bg-primary rounded-full" data-v-9bb53f46${_scopeId}></div><label class="text-[10px] font-black text-on-surface-variant/50 dark:text-white/30 uppercase tracking-[0.3em] italic leading-none" data-v-9bb53f46${_scopeId}>Descripción Técnica y Evidencia del Fallo</label></div><textarea required rows="8" class="w-full bg-surface-container-low dark:bg-white/[0.03] border-2 border-outline-variant/10 dark:border-white/5 rounded-[3rem] p-10 text-[13px] font-medium text-on-surface dark:text-white/80 focus:ring-8 focus:ring-primary/5 outline-none transition-all placeholder:text-on-surface-variant/20 italic shadow-inner no-scrollbar resize-none" placeholder="Describa los pasos exactos para reproducir el incidente técnico..." data-v-9bb53f46${_scopeId}>${ssrInterpolate(unref(form).description)}</textarea></div></div><div class="flex flex-col sm:flex-row gap-6 border-t border-outline-variant/10 dark:border-white/5 pt-12" data-v-9bb53f46${_scopeId}>`);
						_push(ssrRenderComponent(Button_default, {
							type: "button",
							variant: "ghost",
							class: "order-2 sm:order-1 sm:w-1/3 !h-20 !rounded-3xl !text-[10px] font-black uppercase tracking-[0.3em] italic",
							onClick: ($event) => showCreateModal.value = false
						}, {
							default: withCtx((_, _push, _parent, _scopeId) => {
								if (_push) _push(` Abortar Reporte `);
								else return [createTextVNode(" Abortar Reporte ")];
							}),
							_: 1
						}, _parent, _scopeId));
						_push(ssrRenderComponent(Button_default, {
							type: "submit",
							variant: "primary",
							size: "lg",
							class: "order-1 sm:order-2 sm:w-2/3 !h-20 !rounded-3xl !text-xs font-black uppercase italic shadow-2xl shadow-primary/20",
							disabled: unref(form).processing,
							icon: "rocket_launch"
						}, {
							default: withCtx((_, _push, _parent, _scopeId) => {
								if (_push) _push(`${ssrInterpolate(unref(form).processing ? "SINCRONIZANDO..." : "Lanzar al Equipo de Ingeniería")}`);
								else return [createTextVNode(toDisplayString(unref(form).processing ? "SINCRONIZANDO..." : "Lanzar al Equipo de Ingeniería"), 1)];
							}),
							_: 1
						}, _parent, _scopeId));
						_push(`</div></form>`);
					} else return [createVNode("form", {
						onSubmit: withModifiers(submitTicket, ["prevent"]),
						class: "space-y-12"
					}, [createVNode("div", { class: "space-y-10" }, [
						createVNode("div", { class: "space-y-2" }, [createVNode(Input_default, {
							modelValue: unref(form).subject,
							"onUpdate:modelValue": ($event) => unref(form).subject = $event,
							label: "Asunto de la Incidencias",
							placeholder: "Ej: Fallo crítico en el motor de reservas...",
							required: "",
							icon: "emergency",
							class: "px-1"
						}, null, 8, ["modelValue", "onUpdate:modelValue"])]),
						createVNode("div", { class: "grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-10 px-1" }, [createVNode(Select_default, {
							modelValue: unref(form).category,
							"onUpdate:modelValue": ($event) => unref(form).category = $event,
							label: "Módulo Afectado",
							options: __props.categories,
							icon: "settings_input_component"
						}, null, 8, [
							"modelValue",
							"onUpdate:modelValue",
							"options"
						]), createVNode(Select_default, {
							modelValue: unref(form).priority,
							"onUpdate:modelValue": ($event) => unref(form).priority = $event,
							label: "Criticidad Percibida",
							options: priorityOptions,
							icon: "bolt"
						}, null, 8, ["modelValue", "onUpdate:modelValue"])]),
						createVNode("div", { class: "space-y-4 px-1" }, [createVNode("div", { class: "flex items-center gap-3 ml-2" }, [createVNode("div", { class: "w-1 h-4 bg-primary rounded-full" }), createVNode("label", { class: "text-[10px] font-black text-on-surface-variant/50 dark:text-white/30 uppercase tracking-[0.3em] italic leading-none" }, "Descripción Técnica y Evidencia del Fallo")]), withDirectives(createVNode("textarea", {
							"onUpdate:modelValue": ($event) => unref(form).description = $event,
							required: "",
							rows: "8",
							class: "w-full bg-surface-container-low dark:bg-white/[0.03] border-2 border-outline-variant/10 dark:border-white/5 rounded-[3rem] p-10 text-[13px] font-medium text-on-surface dark:text-white/80 focus:ring-8 focus:ring-primary/5 outline-none transition-all placeholder:text-on-surface-variant/20 italic shadow-inner no-scrollbar resize-none",
							placeholder: "Describa los pasos exactos para reproducir el incidente técnico..."
						}, null, 8, ["onUpdate:modelValue"]), [[vModelText, unref(form).description]])])
					]), createVNode("div", { class: "flex flex-col sm:flex-row gap-6 border-t border-outline-variant/10 dark:border-white/5 pt-12" }, [createVNode(Button_default, {
						type: "button",
						variant: "ghost",
						class: "order-2 sm:order-1 sm:w-1/3 !h-20 !rounded-3xl !text-[10px] font-black uppercase tracking-[0.3em] italic",
						onClick: ($event) => showCreateModal.value = false
					}, {
						default: withCtx(() => [createTextVNode(" Abortar Reporte ")]),
						_: 1
					}, 8, ["onClick"]), createVNode(Button_default, {
						type: "submit",
						variant: "primary",
						size: "lg",
						class: "order-1 sm:order-2 sm:w-2/3 !h-20 !rounded-3xl !text-xs font-black uppercase italic shadow-2xl shadow-primary/20",
						disabled: unref(form).processing,
						icon: "rocket_launch"
					}, {
						default: withCtx(() => [createTextVNode(toDisplayString(unref(form).processing ? "SINCRONIZANDO..." : "Lanzar al Equipo de Ingeniería"), 1)]),
						_: 1
					}, 8, ["disabled"])])], 32)];
				}),
				_: 1
			}, _parent));
			_push(`</div><!--]-->`);
		};
	}
});
//#endregion
//#region resources/js/Pages/Support/Index.vue
var _sfc_setup = Index_vue_vue_type_script_setup_true_lang_default.setup;
Index_vue_vue_type_script_setup_true_lang_default.setup = (props, ctx) => {
	const ssrContext = useSSRContext();
	(ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Support/Index.vue");
	return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
var Index_default = /* @__PURE__ */ _plugin_vue_export_helper_default(Index_vue_vue_type_script_setup_true_lang_default, [["__scopeId", "data-v-9bb53f46"]]);
//#endregion
export { Index_default as default };

import { t as StatCard_default } from "./StatCard-B3MO01gK.js";
import { t as _plugin_vue_export_helper_default } from "./_plugin-vue_export-helper-BjJHhBxR.js";
import { t as Table_default } from "./Table-DmKwoFFU.js";
import { t as AuthenticatedLayout_default } from "./AuthenticatedLayout-BYv1HA3l.js";
import { t as Card_default } from "./Card-C2-I3_la.js";
import { t as Button_default } from "./Button-5V-KYwxi.js";
import { t as Modal_default } from "./Modal-CeRVVYhy.js";
import { t as Badge_default } from "./Badge-DWJ2qZRx.js";
import { t as useToast } from "./useToast-Dcf8ak3V.js";
import { createTextVNode, createVNode, defineComponent, ref, toDisplayString, unref, useSSRContext, vModelCheckbox, vModelText, withCtx, withDirectives, withModifiers } from "vue";
import { Head, useForm } from "@inertiajs/vue3";
import { ssrIncludeBooleanAttr, ssrInterpolate, ssrLooseContain, ssrRenderComponent } from "vue/server-renderer";
//#region resources/js/Pages/Admin/Pqrs/Index.vue?vue&type=script&setup=true&lang.ts
var Index_vue_vue_type_script_setup_true_lang_default = /* @__PURE__ */ defineComponent({
	layout: AuthenticatedLayout_default,
	__name: "Index",
	__ssrInlineRender: true,
	props: {
		pqrs: {},
		metrics: {}
	},
	setup(__props) {
		const tableColumns = [
			{
				key: "radicado",
				label: "RADICADO TÉCNICO",
				sortable: false
			},
			{
				key: "residente",
				label: "ORIGEN / UNIDAD",
				sortable: false
			},
			{
				key: "asunto_tipo",
				label: "INCIDENCIA / SEGMENTO",
				sortable: false
			},
			{
				key: "prioridad",
				label: "NIVEL DE ESCALADA",
				sortable: false
			},
			{
				key: "estado",
				label: "ESTADO DE FLUJO",
				sortable: false
			},
			{
				key: "acciones",
				label: "",
				sortable: false
			}
		];
		const toast = useToast();
		const selectedPqrs = ref(null);
		const form = useForm({
			respuesta: "",
			cerrar: true
		});
		const openResponseModal = (item) => {
			selectedPqrs.value = item;
			form.respuesta = item.respuesta || "";
		};
		const submitResponse = () => {
			form.patch(route("pqrs.update", selectedPqrs.value.id), { onSuccess: () => {
				selectedPqrs.value = null;
				form.reset();
				toast.add("Protocolo de respuesta enviado exitosamente", "success");
			} });
		};
		const getStatusVariant = (status) => {
			return {
				"abierto": "primary",
				"en_proceso": "warning",
				"cerrado": "neutral",
				"reabierto": "error"
			}[status] || "neutral";
		};
		const getStatusLabel = (status) => {
			return {
				"abierto": "NUEVO REQUERIMIENTO",
				"en_proceso": "ANÁLISIS ACTIVO",
				"cerrado": "CASO RESUELTO",
				"reabierto": "ESCALADA CRÍTICA"
			}[status] || status.toUpperCase();
		};
		return (_ctx, _push, _parent, _attrs) => {
			_push(`<!--[-->`);
			_push(ssrRenderComponent(unref(Head), { title: "PQRS Monitor — NEXO-PRO" }, null, _parent));
			_push(`<div class="space-y-12 pb-32 animate-in fade-in slide-in-from-bottom-4 duration-700" data-v-3eae0784><div class="flex flex-col md:flex-row md:items-end justify-between gap-8 px-1" data-v-3eae0784><div class="space-y-3" data-v-3eae0784><div class="flex items-center gap-3" data-v-3eae0784><div class="w-1.5 h-6 bg-primary rounded-full shadow-[0_0_8px_rgba(var(--primary),0.5)]" data-v-3eae0784></div><p class="text-[10px] font-black text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.4em] italic leading-none" data-v-3eae0784>Módulo de Atención al Residente</p></div><h2 class="text-5xl font-black text-on-surface dark:text-white tracking-tighter uppercase italic leading-none" data-v-3eae0784>Monitor <span class="text-primary italic" data-v-3eae0784>PQRS</span></h2><p class="text-[11px] font-bold text-on-surface-variant/40 dark:text-white/10 uppercase tracking-[0.2em] mt-2 leading-relaxed italic" data-v-3eae0784>Gestión operativa de incidencias, peticiones y protocolos de respuesta</p></div><div class="flex items-center gap-3" data-v-3eae0784>`);
			_push(ssrRenderComponent(Button_default, {
				variant: "outline",
				icon: "hub",
				class: "!rounded-2xl !h-14 !px-8 text-[11px] font-black uppercase italic"
			}, {
				default: withCtx((_, _push, _parent, _scopeId) => {
					if (_push) _push(`Historial Maestro`);
					else return [createTextVNode("Historial Maestro")];
				}),
				_: 1
			}, _parent));
			_push(`</div></div><section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6" data-v-3eae0784>`);
			_push(ssrRenderComponent(StatCard_default, {
				label: "SOLICITUDES ACTIVAS",
				value: (__props.metrics.abierto || 0).toString(),
				"color-class": "!bg-primary",
				class: "!rounded-[2.5rem] border border-outline-variant/10 dark:border-white/5 shadow-2xl dark:bg-[#0b0e14]"
			}, null, _parent));
			_push(ssrRenderComponent(StatCard_default, {
				label: "EN ANÁLISIS",
				value: (__props.metrics.en_proceso || 0).toString(),
				"color-class": "!bg-warning",
				class: "!rounded-[2.5rem] border border-outline-variant/10 dark:border-white/5 shadow-2xl dark:bg-[#0b0e14]"
			}, null, _parent));
			_push(ssrRenderComponent(StatCard_default, {
				label: "CRÍTICAS / REABIERTAS",
				value: (__props.metrics.reabierto || 0).toString(),
				"color-class": "!bg-error",
				class: "!rounded-[2.5rem] border border-outline-variant/10 dark:border-white/5 shadow-2xl dark:bg-[#0b0e14]"
			}, null, _parent));
			_push(ssrRenderComponent(StatCard_default, {
				label: "RESOLUCIÓN TOTAL",
				value: (__props.metrics.cerrado || 0).toString(),
				"color-class": "!bg-emerald-500",
				class: "!rounded-[2.5rem] border border-outline-variant/10 dark:border-white/5 shadow-2xl dark:bg-[#0b0e14]"
			}, null, _parent));
			_push(`</section>`);
			_push(ssrRenderComponent(Card_default, { class: "!p-0 !rounded-[3.5rem] border border-outline-variant/10 dark:border-white/5 shadow-3xl dark:bg-[#0b0e14] overflow-hidden" }, {
				header: withCtx((_, _push, _parent, _scopeId) => {
					if (_push) _push(`<div class="p-10 flex items-center gap-4" data-v-3eae0784${_scopeId}><div class="w-14 h-14 bg-primary/10 rounded-2xl flex items-center justify-center text-primary shadow-inner" data-v-3eae0784${_scopeId}><span class="material-symbols-rounded text-2xl" data-v-3eae0784${_scopeId}>forum</span></div><div data-v-3eae0784${_scopeId}><h3 class="text-2xl font-black text-on-surface dark:text-white uppercase tracking-tighter italic leading-none" data-v-3eae0784${_scopeId}>Bandeja de <span class="text-primary italic" data-v-3eae0784${_scopeId}>Incidencias</span></h3><p class="text-[10px] font-bold text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.3em] mt-3 italic" data-v-3eae0784${_scopeId}>Control cronológico de requerimientos residentes</p></div></div>`);
					else return [createVNode("div", { class: "p-10 flex items-center gap-4" }, [createVNode("div", { class: "w-14 h-14 bg-primary/10 rounded-2xl flex items-center justify-center text-primary shadow-inner" }, [createVNode("span", { class: "material-symbols-rounded text-2xl" }, "forum")]), createVNode("div", null, [createVNode("h3", { class: "text-2xl font-black text-on-surface dark:text-white uppercase tracking-tighter italic leading-none" }, [createTextVNode("Bandeja de "), createVNode("span", { class: "text-primary italic" }, "Incidencias")]), createVNode("p", { class: "text-[10px] font-bold text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.3em] mt-3 italic" }, "Control cronológico de requerimientos residentes")])])];
				}),
				default: withCtx((_, _push, _parent, _scopeId) => {
					if (_push) _push(ssrRenderComponent(Table_default, {
						columns: tableColumns,
						data: __props.pqrs,
						class: "border-t border-outline-variant/5 dark:border-white/5"
					}, {
						"cell-radicado": withCtx(({ row }, _push, _parent, _scopeId) => {
							if (_push) _push(`<div class="flex flex-col" data-v-3eae0784${_scopeId}><span class="text-base font-black text-primary tracking-tighter italic whitespace-nowrap" data-v-3eae0784${_scopeId}>#${ssrInterpolate(String(row.id).padStart(5, "0"))}</span><span class="text-[9px] text-on-surface-variant/40 dark:text-white/20 font-black uppercase tracking-widest mt-1 italic" data-v-3eae0784${_scopeId}>${ssrInterpolate(new Date(row.created_at).toLocaleDateString())}</span></div>`);
							else return [createVNode("div", { class: "flex flex-col" }, [createVNode("span", { class: "text-base font-black text-primary tracking-tighter italic whitespace-nowrap" }, "#" + toDisplayString(String(row.id).padStart(5, "0")), 1), createVNode("span", { class: "text-[9px] text-on-surface-variant/40 dark:text-white/20 font-black uppercase tracking-widest mt-1 italic" }, toDisplayString(new Date(row.created_at).toLocaleDateString()), 1)])];
						}),
						"cell-residente": withCtx(({ row }, _push, _parent, _scopeId) => {
							if (_push) _push(`<div class="flex items-center gap-4 py-2" data-v-3eae0784${_scopeId}><div class="w-12 h-12 rounded-2xl bg-surface-container-high dark:bg-white/5 flex items-center justify-center text-xs font-black text-primary border border-outline-variant/10 shadow-sm" data-v-3eae0784${_scopeId}><span class="material-symbols-rounded text-xl" data-v-3eae0784${_scopeId}>person_pin_circle</span></div><div class="flex flex-col" data-v-3eae0784${_scopeId}><span class="text-xs font-black text-on-surface dark:text-white uppercase italic tracking-tighter leading-none" data-v-3eae0784${_scopeId}>TORRE ${ssrInterpolate(row.unidad.torre)} - ${ssrInterpolate(row.unidad.nombre)}</span><span class="text-[9px] font-bold text-on-surface-variant/40 dark:text-white/20 uppercase tracking-widest mt-1.5" data-v-3eae0784${_scopeId}>${ssrInterpolate(row.user.name)}</span></div></div>`);
							else return [createVNode("div", { class: "flex items-center gap-4 py-2" }, [createVNode("div", { class: "w-12 h-12 rounded-2xl bg-surface-container-high dark:bg-white/5 flex items-center justify-center text-xs font-black text-primary border border-outline-variant/10 shadow-sm" }, [createVNode("span", { class: "material-symbols-rounded text-xl" }, "person_pin_circle")]), createVNode("div", { class: "flex flex-col" }, [createVNode("span", { class: "text-xs font-black text-on-surface dark:text-white uppercase italic tracking-tighter leading-none" }, "TORRE " + toDisplayString(row.unidad.torre) + " - " + toDisplayString(row.unidad.nombre), 1), createVNode("span", { class: "text-[9px] font-bold text-on-surface-variant/40 dark:text-white/20 uppercase tracking-widest mt-1.5" }, toDisplayString(row.user.name), 1)])])];
						}),
						"cell-asunto_tipo": withCtx(({ row }, _push, _parent, _scopeId) => {
							if (_push) _push(`<div class="flex flex-col" data-v-3eae0784${_scopeId}><span class="text-sm font-black text-on-surface dark:text-white uppercase tracking-tighter italic leading-none" data-v-3eae0784${_scopeId}>${ssrInterpolate(row.asunto)}</span><span class="text-[9px] text-primary font-black uppercase tracking-[0.2em] mt-2 italic px-2 py-0.5 bg-primary/5 rounded-md inline-block w-fit" data-v-3eae0784${_scopeId}>${ssrInterpolate(row.tipo.replace("_", " "))}</span></div>`);
							else return [createVNode("div", { class: "flex flex-col" }, [createVNode("span", { class: "text-sm font-black text-on-surface dark:text-white uppercase tracking-tighter italic leading-none" }, toDisplayString(row.asunto), 1), createVNode("span", { class: "text-[9px] text-primary font-black uppercase tracking-[0.2em] mt-2 italic px-2 py-0.5 bg-primary/5 rounded-md inline-block w-fit" }, toDisplayString(row.tipo.replace("_", " ")), 1)])];
						}),
						"cell-prioridad": withCtx(({ row }, _push, _parent, _scopeId) => {
							if (_push) {
								_push(`<div class="flex justify-center" data-v-3eae0784${_scopeId}>`);
								_push(ssrRenderComponent(Badge_default, {
									variant: row.prioridad === "alta" ? "error" : row.prioridad === "media" ? "warning" : "success",
									class: "!px-5 !py-1 !font-black !text-[10px] tracking-widest uppercase italic"
								}, {
									default: withCtx((_, _push, _parent, _scopeId) => {
										if (_push) _push(`${ssrInterpolate(row.prioridad)}`);
										else return [createTextVNode(toDisplayString(row.prioridad), 1)];
									}),
									_: 2
								}, _parent, _scopeId));
								_push(`</div>`);
							} else return [createVNode("div", { class: "flex justify-center" }, [createVNode(Badge_default, {
								variant: row.prioridad === "alta" ? "error" : row.prioridad === "media" ? "warning" : "success",
								class: "!px-5 !py-1 !font-black !text-[10px] tracking-widest uppercase italic"
							}, {
								default: withCtx(() => [createTextVNode(toDisplayString(row.prioridad), 1)]),
								_: 2
							}, 1032, ["variant"])])];
						}),
						"cell-estado": withCtx(({ row }, _push, _parent, _scopeId) => {
							if (_push) {
								_push(`<div class="flex justify-center" data-v-3eae0784${_scopeId}>`);
								_push(ssrRenderComponent(Badge_default, {
									variant: getStatusVariant(row.estado),
									class: "!px-5 !py-1 !font-black !text-[8.5px] tracking-[0.1em] uppercase italic border-2"
								}, {
									default: withCtx((_, _push, _parent, _scopeId) => {
										if (_push) _push(`${ssrInterpolate(getStatusLabel(row.estado))}`);
										else return [createTextVNode(toDisplayString(getStatusLabel(row.estado)), 1)];
									}),
									_: 2
								}, _parent, _scopeId));
								_push(`</div>`);
							} else return [createVNode("div", { class: "flex justify-center" }, [createVNode(Badge_default, {
								variant: getStatusVariant(row.estado),
								class: "!px-5 !py-1 !font-black !text-[8.5px] tracking-[0.1em] uppercase italic border-2"
							}, {
								default: withCtx(() => [createTextVNode(toDisplayString(getStatusLabel(row.estado)), 1)]),
								_: 2
							}, 1032, ["variant"])])];
						}),
						"cell-acciones": withCtx(({ row }, _push, _parent, _scopeId) => {
							if (_push) {
								_push(`<div class="flex justify-end pr-6" data-v-3eae0784${_scopeId}>`);
								_push(ssrRenderComponent(Button_default, {
									variant: "primary",
									size: "md",
									icon: "shield_with_heart",
									class: "!rounded-xl !h-12 !px-6 !text-[10px] font-black uppercase italic shadow-lg shadow-primary/10",
									loading: unref(form).processing && selectedPqrs.value?.id === row.id,
									onClick: ($event) => openResponseModal(row)
								}, {
									default: withCtx((_, _push, _parent, _scopeId) => {
										if (_push) _push(` Gestionar `);
										else return [createTextVNode(" Gestionar ")];
									}),
									_: 2
								}, _parent, _scopeId));
								_push(`</div>`);
							} else return [createVNode("div", { class: "flex justify-end pr-6" }, [createVNode(Button_default, {
								variant: "primary",
								size: "md",
								icon: "shield_with_heart",
								class: "!rounded-xl !h-12 !px-6 !text-[10px] font-black uppercase italic shadow-lg shadow-primary/10",
								loading: unref(form).processing && selectedPqrs.value?.id === row.id,
								onClick: ($event) => openResponseModal(row)
							}, {
								default: withCtx(() => [createTextVNode(" Gestionar ")]),
								_: 1
							}, 8, ["loading", "onClick"])])];
						}),
						_: 1
					}, _parent, _scopeId));
					else return [createVNode(Table_default, {
						columns: tableColumns,
						data: __props.pqrs,
						class: "border-t border-outline-variant/5 dark:border-white/5"
					}, {
						"cell-radicado": withCtx(({ row }) => [createVNode("div", { class: "flex flex-col" }, [createVNode("span", { class: "text-base font-black text-primary tracking-tighter italic whitespace-nowrap" }, "#" + toDisplayString(String(row.id).padStart(5, "0")), 1), createVNode("span", { class: "text-[9px] text-on-surface-variant/40 dark:text-white/20 font-black uppercase tracking-widest mt-1 italic" }, toDisplayString(new Date(row.created_at).toLocaleDateString()), 1)])]),
						"cell-residente": withCtx(({ row }) => [createVNode("div", { class: "flex items-center gap-4 py-2" }, [createVNode("div", { class: "w-12 h-12 rounded-2xl bg-surface-container-high dark:bg-white/5 flex items-center justify-center text-xs font-black text-primary border border-outline-variant/10 shadow-sm" }, [createVNode("span", { class: "material-symbols-rounded text-xl" }, "person_pin_circle")]), createVNode("div", { class: "flex flex-col" }, [createVNode("span", { class: "text-xs font-black text-on-surface dark:text-white uppercase italic tracking-tighter leading-none" }, "TORRE " + toDisplayString(row.unidad.torre) + " - " + toDisplayString(row.unidad.nombre), 1), createVNode("span", { class: "text-[9px] font-bold text-on-surface-variant/40 dark:text-white/20 uppercase tracking-widest mt-1.5" }, toDisplayString(row.user.name), 1)])])]),
						"cell-asunto_tipo": withCtx(({ row }) => [createVNode("div", { class: "flex flex-col" }, [createVNode("span", { class: "text-sm font-black text-on-surface dark:text-white uppercase tracking-tighter italic leading-none" }, toDisplayString(row.asunto), 1), createVNode("span", { class: "text-[9px] text-primary font-black uppercase tracking-[0.2em] mt-2 italic px-2 py-0.5 bg-primary/5 rounded-md inline-block w-fit" }, toDisplayString(row.tipo.replace("_", " ")), 1)])]),
						"cell-prioridad": withCtx(({ row }) => [createVNode("div", { class: "flex justify-center" }, [createVNode(Badge_default, {
							variant: row.prioridad === "alta" ? "error" : row.prioridad === "media" ? "warning" : "success",
							class: "!px-5 !py-1 !font-black !text-[10px] tracking-widest uppercase italic"
						}, {
							default: withCtx(() => [createTextVNode(toDisplayString(row.prioridad), 1)]),
							_: 2
						}, 1032, ["variant"])])]),
						"cell-estado": withCtx(({ row }) => [createVNode("div", { class: "flex justify-center" }, [createVNode(Badge_default, {
							variant: getStatusVariant(row.estado),
							class: "!px-5 !py-1 !font-black !text-[8.5px] tracking-[0.1em] uppercase italic border-2"
						}, {
							default: withCtx(() => [createTextVNode(toDisplayString(getStatusLabel(row.estado)), 1)]),
							_: 2
						}, 1032, ["variant"])])]),
						"cell-acciones": withCtx(({ row }) => [createVNode("div", { class: "flex justify-end pr-6" }, [createVNode(Button_default, {
							variant: "primary",
							size: "md",
							icon: "shield_with_heart",
							class: "!rounded-xl !h-12 !px-6 !text-[10px] font-black uppercase italic shadow-lg shadow-primary/10",
							loading: unref(form).processing && selectedPqrs.value?.id === row.id,
							onClick: ($event) => openResponseModal(row)
						}, {
							default: withCtx(() => [createTextVNode(" Gestionar ")]),
							_: 1
						}, 8, ["loading", "onClick"])])]),
						_: 1
					}, 8, ["data"])];
				}),
				_: 1
			}, _parent));
			if (selectedPqrs.value) _push(ssrRenderComponent(Modal_default, {
				onClose: ($event) => selectedPqrs.value = null,
				class: "!max-w-2xl"
			}, {
				title: withCtx((_, _push, _parent, _scopeId) => {
					if (_push) _push(`<div class="flex items-center gap-4" data-v-3eae0784${_scopeId}><div class="w-12 h-12 bg-primary/5 rounded-2xl flex items-center justify-center text-primary border border-primary/10" data-v-3eae0784${_scopeId}><span class="material-symbols-rounded text-2xl" data-v-3eae0784${_scopeId}>mark_chat_read</span></div><div data-v-3eae0784${_scopeId}><h3 class="text-2xl font-black text-on-surface dark:text-white uppercase tracking-tighter italic leading-none" data-v-3eae0784${_scopeId}>Gestión Técnica <span class="text-primary italic" data-v-3eae0784${_scopeId}>#${ssrInterpolate(String(selectedPqrs.value.id).padStart(5, "0"))}</span></h3><p class="text-[9px] font-bold text-on-surface-variant/40 dark:text-white/20 uppercase tracking-widest mt-1" data-v-3eae0784${_scopeId}>Resolución de incidencia para Torre ${ssrInterpolate(selectedPqrs.value.unidad.torre)}</p></div></div>`);
					else return [createVNode("div", { class: "flex items-center gap-4" }, [createVNode("div", { class: "w-12 h-12 bg-primary/5 rounded-2xl flex items-center justify-center text-primary border border-primary/10" }, [createVNode("span", { class: "material-symbols-rounded text-2xl" }, "mark_chat_read")]), createVNode("div", null, [createVNode("h3", { class: "text-2xl font-black text-on-surface dark:text-white uppercase tracking-tighter italic leading-none" }, [createTextVNode("Gestión Técnica "), createVNode("span", { class: "text-primary italic" }, "#" + toDisplayString(String(selectedPqrs.value.id).padStart(5, "0")), 1)]), createVNode("p", { class: "text-[9px] font-bold text-on-surface-variant/40 dark:text-white/20 uppercase tracking-widest mt-1" }, "Resolución de incidencia para Torre " + toDisplayString(selectedPqrs.value.unidad.torre), 1)])])];
				}),
				default: withCtx((_, _push, _parent, _scopeId) => {
					if (_push) {
						_push(`<div class="space-y-10 mt-12 pb-6" data-v-3eae0784${_scopeId}><div class="relative" data-v-3eae0784${_scopeId}><div class="absolute left-6 top-10 bottom-0 w-1 bg-gradient-to-b from-primary/20 to-transparent rounded-full" data-v-3eae0784${_scopeId}></div><div class="flex gap-6 items-start relative z-10 mb-10" data-v-3eae0784${_scopeId}><div class="w-12 h-12 rounded-2xl bg-surface-container-high dark:bg-white/5 flex items-center justify-center text-on-surface-variant shadow-inner" data-v-3eae0784${_scopeId}><span class="material-symbols-rounded text-xl" data-v-3eae0784${_scopeId}>output</span></div><div class="flex-1 bg-surface-container-low dark:bg-white/[0.03] p-8 rounded-[2.5rem] border border-outline-variant/10 dark:border-white/5 shadow-sm" data-v-3eae0784${_scopeId}><p class="text-[9px] font-black text-primary uppercase tracking-[0.2em] mb-4 italic" data-v-3eae0784${_scopeId}>Mensaje del Residente:</p><p class="text-base font-medium text-on-surface-variant dark:text-white/60 leading-relaxed italic pr-6 italic" data-v-3eae0784${_scopeId}>&quot;${ssrInterpolate(selectedPqrs.value.mensaje)}&quot;</p></div></div><div class="flex gap-6 items-start relative z-10" data-v-3eae0784${_scopeId}><div class="w-12 h-12 rounded-2xl bg-primary/10 flex items-center justify-center text-primary shadow-inner" data-v-3eae0784${_scopeId}><span class="material-symbols-rounded text-xl" data-v-3eae0784${_scopeId}>reply_all</span></div><div class="flex-1 space-y-6" data-v-3eae0784${_scopeId}><form class="space-y-8" data-v-3eae0784${_scopeId}><div class="space-y-3" data-v-3eae0784${_scopeId}><label class="text-[10px] font-black text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.3em] ml-2 italic" data-v-3eae0784${_scopeId}>Respuesta Técnica Oficial</label><textarea rows="6" class="w-full bg-white dark:bg-white/[0.02] border-2 border-outline-variant/10 dark:border-white/5 rounded-[2rem] p-8 text-sm font-medium text-on-surface dark:text-white focus:ring-8 focus:ring-primary/5 outline-none transition-all placeholder:text-on-surface-variant/20 italic shadow-xl" placeholder="Redacta la resolución formal detallada..." required data-v-3eae0784${_scopeId}>${ssrInterpolate(unref(form).respuesta)}</textarea></div><div class="flex items-center gap-5 bg-surface-container dark:bg-white/5 p-6 rounded-3xl border border-outline-variant/10 dark:border-white/5 group cursor-pointer hover:bg-primary/5 transition-colors" data-v-3eae0784${_scopeId}><div class="relative flex items-center justify-center w-6 h-6" data-v-3eae0784${_scopeId}><input type="checkbox"${ssrIncludeBooleanAttr(Array.isArray(unref(form).cerrar) ? ssrLooseContain(unref(form).cerrar, null) : unref(form).cerrar) ? " checked" : ""} id="cerrar-pqr" class="peer appearance-none w-6 h-6 rounded-lg bg-surface dark:bg-white/5 border-2 border-outline-variant/30 checked:bg-primary checked:border-primary transition-all cursor-pointer" data-v-3eae0784${_scopeId}><span class="material-symbols-rounded text-white text-base absolute transition-all opacity-0 peer-checked:opacity-100 scale-50 peer-checked:scale-100" data-v-3eae0784${_scopeId}>check</span></div><label for="cerrar-pqr" class="text-[11px] font-black text-on-surface-variant dark:text-white/40 uppercase tracking-widest cursor-pointer select-none italic group-hover:text-primary transition-colors" data-v-3eae0784${_scopeId}>Ejecutar Cierre de Caso y Notificar</label></div><div class="flex flex-col gap-4 pt-6" data-v-3eae0784${_scopeId}>`);
						_push(ssrRenderComponent(Button_default, {
							type: "submit",
							variant: "primary",
							size: "lg",
							class: "w-full !h-16 !rounded-2xl !text-[11px] font-black uppercase italic shadow-2xl shadow-primary/20",
							disabled: unref(form).processing,
							icon: "send_and_archive"
						}, {
							default: withCtx((_, _push, _parent, _scopeId) => {
								if (_push) _push(`${ssrInterpolate(unref(form).processing ? "ENVIANDO PROTOCOLO..." : "DESPACHAR RESPUESTA")}`);
								else return [createTextVNode(toDisplayString(unref(form).processing ? "ENVIANDO PROTOCOLO..." : "DESPACHAR RESPUESTA"), 1)];
							}),
							_: 1
						}, _parent, _scopeId));
						_push(ssrRenderComponent(Button_default, {
							type: "button",
							variant: "ghost",
							class: "w-full !text-[9px] font-black uppercase tracking-widest text-on-surface-variant/40",
							onClick: ($event) => selectedPqrs.value = null
						}, {
							default: withCtx((_, _push, _parent, _scopeId) => {
								if (_push) _push(`Cancelar Operación`);
								else return [createTextVNode("Cancelar Operación")];
							}),
							_: 1
						}, _parent, _scopeId));
						_push(`</div></form></div></div></div></div>`);
					} else return [createVNode("div", { class: "space-y-10 mt-12 pb-6" }, [createVNode("div", { class: "relative" }, [
						createVNode("div", { class: "absolute left-6 top-10 bottom-0 w-1 bg-gradient-to-b from-primary/20 to-transparent rounded-full" }),
						createVNode("div", { class: "flex gap-6 items-start relative z-10 mb-10" }, [createVNode("div", { class: "w-12 h-12 rounded-2xl bg-surface-container-high dark:bg-white/5 flex items-center justify-center text-on-surface-variant shadow-inner" }, [createVNode("span", { class: "material-symbols-rounded text-xl" }, "output")]), createVNode("div", { class: "flex-1 bg-surface-container-low dark:bg-white/[0.03] p-8 rounded-[2.5rem] border border-outline-variant/10 dark:border-white/5 shadow-sm" }, [createVNode("p", { class: "text-[9px] font-black text-primary uppercase tracking-[0.2em] mb-4 italic" }, "Mensaje del Residente:"), createVNode("p", { class: "text-base font-medium text-on-surface-variant dark:text-white/60 leading-relaxed italic pr-6 italic" }, "\"" + toDisplayString(selectedPqrs.value.mensaje) + "\"", 1)])]),
						createVNode("div", { class: "flex gap-6 items-start relative z-10" }, [createVNode("div", { class: "w-12 h-12 rounded-2xl bg-primary/10 flex items-center justify-center text-primary shadow-inner" }, [createVNode("span", { class: "material-symbols-rounded text-xl" }, "reply_all")]), createVNode("div", { class: "flex-1 space-y-6" }, [createVNode("form", {
							onSubmit: withModifiers(submitResponse, ["prevent"]),
							class: "space-y-8"
						}, [
							createVNode("div", { class: "space-y-3" }, [createVNode("label", { class: "text-[10px] font-black text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.3em] ml-2 italic" }, "Respuesta Técnica Oficial"), withDirectives(createVNode("textarea", {
								"onUpdate:modelValue": ($event) => unref(form).respuesta = $event,
								rows: "6",
								class: "w-full bg-white dark:bg-white/[0.02] border-2 border-outline-variant/10 dark:border-white/5 rounded-[2rem] p-8 text-sm font-medium text-on-surface dark:text-white focus:ring-8 focus:ring-primary/5 outline-none transition-all placeholder:text-on-surface-variant/20 italic shadow-xl",
								placeholder: "Redacta la resolución formal detallada...",
								required: ""
							}, null, 8, ["onUpdate:modelValue"]), [[vModelText, unref(form).respuesta]])]),
							createVNode("div", { class: "flex items-center gap-5 bg-surface-container dark:bg-white/5 p-6 rounded-3xl border border-outline-variant/10 dark:border-white/5 group cursor-pointer hover:bg-primary/5 transition-colors" }, [createVNode("div", { class: "relative flex items-center justify-center w-6 h-6" }, [withDirectives(createVNode("input", {
								type: "checkbox",
								"onUpdate:modelValue": ($event) => unref(form).cerrar = $event,
								id: "cerrar-pqr",
								class: "peer appearance-none w-6 h-6 rounded-lg bg-surface dark:bg-white/5 border-2 border-outline-variant/30 checked:bg-primary checked:border-primary transition-all cursor-pointer"
							}, null, 8, ["onUpdate:modelValue"]), [[vModelCheckbox, unref(form).cerrar]]), createVNode("span", { class: "material-symbols-rounded text-white text-base absolute transition-all opacity-0 peer-checked:opacity-100 scale-50 peer-checked:scale-100" }, "check")]), createVNode("label", {
								for: "cerrar-pqr",
								class: "text-[11px] font-black text-on-surface-variant dark:text-white/40 uppercase tracking-widest cursor-pointer select-none italic group-hover:text-primary transition-colors"
							}, "Ejecutar Cierre de Caso y Notificar")]),
							createVNode("div", { class: "flex flex-col gap-4 pt-6" }, [createVNode(Button_default, {
								type: "submit",
								variant: "primary",
								size: "lg",
								class: "w-full !h-16 !rounded-2xl !text-[11px] font-black uppercase italic shadow-2xl shadow-primary/20",
								disabled: unref(form).processing,
								icon: "send_and_archive"
							}, {
								default: withCtx(() => [createTextVNode(toDisplayString(unref(form).processing ? "ENVIANDO PROTOCOLO..." : "DESPACHAR RESPUESTA"), 1)]),
								_: 1
							}, 8, ["disabled"]), createVNode(Button_default, {
								type: "button",
								variant: "ghost",
								class: "w-full !text-[9px] font-black uppercase tracking-widest text-on-surface-variant/40",
								onClick: ($event) => selectedPqrs.value = null
							}, {
								default: withCtx(() => [createTextVNode("Cancelar Operación")]),
								_: 1
							}, 8, ["onClick"])])
						], 32)])])
					])])];
				}),
				_: 1
			}, _parent));
			else _push(`<!---->`);
			_push(`</div><!--]-->`);
		};
	}
});
//#endregion
//#region resources/js/Pages/Admin/Pqrs/Index.vue
var _sfc_setup = Index_vue_vue_type_script_setup_true_lang_default.setup;
Index_vue_vue_type_script_setup_true_lang_default.setup = (props, ctx) => {
	const ssrContext = useSSRContext();
	(ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Admin/Pqrs/Index.vue");
	return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
var Index_default = /* @__PURE__ */ _plugin_vue_export_helper_default(Index_vue_vue_type_script_setup_true_lang_default, [["__scopeId", "data-v-3eae0784"]]);
//#endregion
export { Index_default as default };

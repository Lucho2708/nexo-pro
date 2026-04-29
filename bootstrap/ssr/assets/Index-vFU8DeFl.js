import { t as _plugin_vue_export_helper_default } from "./_plugin-vue_export-helper-BjJHhBxR.js";
import { t as useToast } from "./useToast-E_Hdzmc5.js";
import { t as AuthenticatedLayout_default } from "./AuthenticatedLayout-vxulH9w9.js";
import { t as Card_default } from "./Card-D4IwBRMd.js";
import { t as Button_default } from "./Button-C-byZbTR.js";
import { t as Modal_default } from "./Modal-DfwT9E3X.js";
import { t as Badge_default } from "./Badge-DsYztQFV.js";
import { t as Select_default } from "./Select-DRXhACf5.js";
import { Fragment, createBlock, createCommentVNode, createTextVNode, createVNode, defineComponent, openBlock, ref, renderList, toDisplayString, unref, useSSRContext, vModelText, withCtx, withDirectives, withModifiers } from "vue";
import { Head, useForm } from "@inertiajs/vue3";
import { ssrInterpolate, ssrRenderClass, ssrRenderComponent, ssrRenderList } from "vue/server-renderer";
//#region resources/js/Pages/SuperAdmin/Support/Index.vue?vue&type=script&setup=true&lang.ts
var Index_vue_vue_type_script_setup_true_lang_default = /* @__PURE__ */ defineComponent({
	layout: AuthenticatedLayout_default,
	__name: "Index",
	__ssrInlineRender: true,
	props: {
		tickets: {},
		stats: {}
	},
	setup(__props) {
		const toast = useToast();
		const selectedTicket = ref(null);
		const showManageModal = ref(false);
		const form = useForm({
			status: "",
			priority: "",
			resolution_notes: ""
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
				label: "CRÍTICA (Bloqueo Operativo)"
			}
		];
		const statusOptions = [
			{
				value: "open",
				label: "ABIERTO (Recibido)"
			},
			{
				value: "in_progress",
				label: "EN ANÁLISIS / TRABAJO"
			},
			{
				value: "resolved",
				label: "RESUELTO / DESPLEGADO"
			},
			{
				value: "closed",
				label: "CERRADO / ARCHIVADO"
			}
		];
		const openManageModal = (ticket) => {
			selectedTicket.value = ticket;
			form.status = ticket.status;
			form.priority = ticket.priority;
			form.resolution_notes = ticket.resolution_notes || "";
			showManageModal.value = true;
		};
		const submitUpdate = () => {
			form.patch(route("superadmin.support.update", selectedTicket.value.id), { onSuccess: () => {
				showManageModal.value = false;
				toast.add("Trazabilidad de incidencia actualizada", "primary");
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
		const getCategoryLabel = (cat) => {
			return {
				"payments": "FINANCIERO / PASARELA",
				"reservations": "OPERATIVO / RESERVAS",
				"billing": "ESTRUCTURAL / CARTERA",
				"ui_ux": "FRONTHEND / VISUAL",
				"access": "SEGURIDAD / ACCESO",
				"performance": "INFRAESTRUCTURA / LENTITUD",
				"other": "MISCELÁNEO"
			}[cat] || cat.toUpperCase();
		};
		return (_ctx, _push, _parent, _attrs) => {
			_push(`<!--[-->`);
			_push(ssrRenderComponent(unref(Head), { title: "Control Maestro de Soporte — SuperAdmin" }, null, _parent));
			_push(`<div class="space-y-12 pb-32 animate-in fade-in slide-in-from-bottom-4 duration-700" data-v-100ce04a><div class="flex flex-col xl:flex-row xl:items-center justify-between gap-10 px-1" data-v-100ce04a><div class="space-y-4" data-v-100ce04a><div class="flex items-center gap-3" data-v-100ce04a><div class="w-2 h-8 bg-secondary rounded-full shadow-[0_0_15px_rgba(var(--secondary),0.4)]" data-v-100ce04a></div><p class="text-[10px] font-black text-on-surface-variant/40 dark:text-white/30 uppercase tracking-[0.5em] italic leading-none" data-v-100ce04a>Dispatcher General de Infraestructura</p></div><h2 class="text-6xl font-black text-on-surface dark:text-white tracking-tighter uppercase italic leading-none" data-v-100ce04a> Mando de <span class="text-secondary italic" data-v-100ce04a>Soporte</span></h2><p class="text-[11px] font-bold text-on-surface-variant/40 dark:text-white/10 uppercase tracking-[0.2em] mt-2 leading-relaxed italic" data-v-100ce04a>Monitoreo de incidencias globales y calibración de criticidad del sistema</p></div><div class="grid grid-cols-2 sm:grid-cols-3 gap-6" data-v-100ce04a><!--[-->`);
			ssrRenderList(__props.stats, (count, status) => {
				_push(`<div class="px-8 py-6 rounded-[2.5rem] bg-white dark:bg-white/[0.02] border border-outline-variant/10 dark:border-white/5 flex flex-col items-center min-w-[140px] shadow-2xl relative overflow-hidden group" data-v-100ce04a><div class="absolute inset-0 bg-secondary/5 opacity-0 group-hover:opacity-100 transition-opacity duration-500" data-v-100ce04a></div><span class="text-[9px] font-black text-on-surface-variant/40 uppercase tracking-[0.4em] mb-2 relative z-10 italic" data-v-100ce04a>${ssrInterpolate(status.replace("_", " "))}</span><span class="text-4xl font-black text-on-surface dark:text-white italic tracking-tighter relative z-10" data-v-100ce04a>${ssrInterpolate(count)}</span></div>`);
			});
			_push(`<!--]--></div></div>`);
			_push(ssrRenderComponent(Card_default, { class: "!p-0 !rounded-[4rem] border-2 border-outline-variant/10 dark:border-white/5 shadow-3xl dark:bg-[#0b0e14] overflow-hidden" }, {
				default: withCtx((_, _push, _parent, _scopeId) => {
					if (_push) {
						_push(`<div class="overflow-x-auto no-scrollbar" data-v-100ce04a${_scopeId}><table class="w-full text-left min-w-[1200px]" data-v-100ce04a${_scopeId}><thead data-v-100ce04a${_scopeId}><tr class="bg-surface-container dark:bg-white/[0.01] border-b-2 border-outline-variant/10 dark:border-white/5" data-v-100ce04a${_scopeId}><th class="px-12 py-10 text-[10px] font-black text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.4em]" data-v-100ce04a${_scopeId}>Incidencia / Reporte</th><th class="px-12 py-10 text-[10px] font-black text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.4em]" data-v-100ce04a${_scopeId}>Origen / Tenant</th><th class="px-12 py-10 text-[10px] font-black text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.4em]" data-v-100ce04a${_scopeId}>Criticidad</th><th class="px-12 py-10 text-[10px] font-black text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.4em]" data-v-100ce04a${_scopeId}>Estado</th><th class="px-12 py-10 text-[10px] font-black text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.4em] text-right" data-v-100ce04a${_scopeId}>Mando</th></tr></thead><tbody class="divide-y divide-outline-variant/5 dark:divide-white/[0.02]" data-v-100ce04a${_scopeId}><!--[-->`);
						ssrRenderList(__props.tickets, (ticket) => {
							_push(`<tr class="group hover:bg-secondary/[0.012] dark:hover:bg-secondary/[0.05] transition-all duration-500" data-v-100ce04a${_scopeId}><td class="px-12 py-10" data-v-100ce04a${_scopeId}><div class="flex flex-col gap-2" data-v-100ce04a${_scopeId}><span class="text-xl font-black text-on-surface dark:text-white tracking-tighter uppercase italic leading-none transition-transform group-hover:translate-x-1" data-v-100ce04a${_scopeId}>${ssrInterpolate(ticket.subject)}</span><div class="flex items-center gap-3" data-v-100ce04a${_scopeId}><div class="w-2 h-4 bg-secondary/40 rounded-full" data-v-100ce04a${_scopeId}></div><span class="text-[10px] font-black text-secondary/60 uppercase tracking-widest italic" data-v-100ce04a${_scopeId}>${ssrInterpolate(getCategoryLabel(ticket.category))}</span></div></div></td><td class="px-12 py-10" data-v-100ce04a${_scopeId}><div class="flex flex-col gap-1" data-v-100ce04a${_scopeId}><span class="text-[13px] font-black text-on-surface dark:text-white/80 uppercase tracking-tighter" data-v-100ce04a${_scopeId}>${ssrInterpolate(ticket.copropiedad?.nombre || "SISTEMA_CORE")}</span><span class="text-[10px] font-bold text-on-surface-variant/30 uppercase tracking-widest italic font-mono" data-v-100ce04a${_scopeId}>${ssrInterpolate(ticket.user.name)}</span></div></td><td class="px-12 py-10" data-v-100ce04a${_scopeId}><div class="${ssrRenderClass([getPriorityColor(ticket.priority), "px-6 py-2 rounded-2xl border-2 tabular-nums inline-flex items-center gap-3 transition-all group-hover:scale-105 shadow-sm"])}" data-v-100ce04a${_scopeId}><span class="text-[10px] font-black uppercase tracking-widest italic" data-v-100ce04a${_scopeId}>${ssrInterpolate(ticket.priority)}</span></div></td><td class="px-12 py-10" data-v-100ce04a${_scopeId}>`);
							_push(ssrRenderComponent(Badge_default, {
								variant: getStatusVariant(ticket.status),
								class: "!px-7 !py-2 !font-black !text-[10px] tracking-[0.25em] uppercase italic border-2 !rounded-xl shadow-inner"
							}, {
								default: withCtx((_, _push, _parent, _scopeId) => {
									if (_push) _push(`${ssrInterpolate(ticket.status.replace("_", " "))}`);
									else return [createTextVNode(toDisplayString(ticket.status.replace("_", " ")), 1)];
								}),
								_: 2
							}, _parent, _scopeId));
							_push(`</td><td class="px-12 py-10 text-right" data-v-100ce04a${_scopeId}>`);
							_push(ssrRenderComponent(Button_default, {
								variant: "ghost",
								icon: "terminal",
								class: "!w-16 !h-16 !p-0 !rounded-[1.5rem] transition-all hover:bg-secondary/10 hover:text-secondary border-2 border-outline-variant/10 dark:border-white/5 active:scale-90 shadow-xl",
								onClick: ($event) => openManageModal(ticket)
							}, null, _parent, _scopeId));
							_push(`</td></tr>`);
						});
						_push(`<!--]--></tbody></table></div>`);
					} else return [createVNode("div", { class: "overflow-x-auto no-scrollbar" }, [createVNode("table", { class: "w-full text-left min-w-[1200px]" }, [createVNode("thead", null, [createVNode("tr", { class: "bg-surface-container dark:bg-white/[0.01] border-b-2 border-outline-variant/10 dark:border-white/5" }, [
						createVNode("th", { class: "px-12 py-10 text-[10px] font-black text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.4em]" }, "Incidencia / Reporte"),
						createVNode("th", { class: "px-12 py-10 text-[10px] font-black text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.4em]" }, "Origen / Tenant"),
						createVNode("th", { class: "px-12 py-10 text-[10px] font-black text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.4em]" }, "Criticidad"),
						createVNode("th", { class: "px-12 py-10 text-[10px] font-black text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.4em]" }, "Estado"),
						createVNode("th", { class: "px-12 py-10 text-[10px] font-black text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.4em] text-right" }, "Mando")
					])]), createVNode("tbody", { class: "divide-y divide-outline-variant/5 dark:divide-white/[0.02]" }, [(openBlock(true), createBlock(Fragment, null, renderList(__props.tickets, (ticket) => {
						return openBlock(), createBlock("tr", {
							key: ticket.id,
							class: "group hover:bg-secondary/[0.012] dark:hover:bg-secondary/[0.05] transition-all duration-500"
						}, [
							createVNode("td", { class: "px-12 py-10" }, [createVNode("div", { class: "flex flex-col gap-2" }, [createVNode("span", { class: "text-xl font-black text-on-surface dark:text-white tracking-tighter uppercase italic leading-none transition-transform group-hover:translate-x-1" }, toDisplayString(ticket.subject), 1), createVNode("div", { class: "flex items-center gap-3" }, [createVNode("div", { class: "w-2 h-4 bg-secondary/40 rounded-full" }), createVNode("span", { class: "text-[10px] font-black text-secondary/60 uppercase tracking-widest italic" }, toDisplayString(getCategoryLabel(ticket.category)), 1)])])]),
							createVNode("td", { class: "px-12 py-10" }, [createVNode("div", { class: "flex flex-col gap-1" }, [createVNode("span", { class: "text-[13px] font-black text-on-surface dark:text-white/80 uppercase tracking-tighter" }, toDisplayString(ticket.copropiedad?.nombre || "SISTEMA_CORE"), 1), createVNode("span", { class: "text-[10px] font-bold text-on-surface-variant/30 uppercase tracking-widest italic font-mono" }, toDisplayString(ticket.user.name), 1)])]),
							createVNode("td", { class: "px-12 py-10" }, [createVNode("div", { class: ["px-6 py-2 rounded-2xl border-2 tabular-nums inline-flex items-center gap-3 transition-all group-hover:scale-105 shadow-sm", getPriorityColor(ticket.priority)] }, [createVNode("span", { class: "text-[10px] font-black uppercase tracking-widest italic" }, toDisplayString(ticket.priority), 1)], 2)]),
							createVNode("td", { class: "px-12 py-10" }, [createVNode(Badge_default, {
								variant: getStatusVariant(ticket.status),
								class: "!px-7 !py-2 !font-black !text-[10px] tracking-[0.25em] uppercase italic border-2 !rounded-xl shadow-inner"
							}, {
								default: withCtx(() => [createTextVNode(toDisplayString(ticket.status.replace("_", " ")), 1)]),
								_: 2
							}, 1032, ["variant"])]),
							createVNode("td", { class: "px-12 py-10 text-right" }, [createVNode(Button_default, {
								variant: "ghost",
								icon: "terminal",
								class: "!w-16 !h-16 !p-0 !rounded-[1.5rem] transition-all hover:bg-secondary/10 hover:text-secondary border-2 border-outline-variant/10 dark:border-white/5 active:scale-90 shadow-xl",
								onClick: ($event) => openManageModal(ticket)
							}, null, 8, ["onClick"])])
						]);
					}), 128))])])])];
				}),
				_: 1
			}, _parent));
			_push(ssrRenderComponent(Modal_default, {
				show: showManageModal.value,
				onClose: ($event) => showManageModal.value = false,
				"max-width": "4xl",
				title: "Gestión de Infraestructura"
			}, {
				default: withCtx((_, _push, _parent, _scopeId) => {
					if (_push) if (selectedTicket.value) {
						_push(`<div class="space-y-12" data-v-100ce04a${_scopeId}><div class="grid grid-cols-1 lg:grid-cols-3 gap-10" data-v-100ce04a${_scopeId}><div class="lg:col-span-2 bg-white/[0.02] p-10 rounded-[3.5rem] border border-outline-variant/10 dark:border-white/5 relative italic shadow-inner" data-v-100ce04a${_scopeId}><p class="text-[10px] font-black text-secondary uppercase tracking-[0.4em] mb-6 leading-none italic" data-v-100ce04a${_scopeId}>Reporte del Usuario:</p><p class="text-[15px] font-medium text-on-surface-variant dark:text-white/70 leading-relaxed" data-v-100ce04a${_scopeId}>${ssrInterpolate(selectedTicket.value.description)}</p></div><div class="flex flex-col gap-6" data-v-100ce04a${_scopeId}><div class="p-8 rounded-[2rem] bg-secondary/5 border border-secondary/10 flex flex-col justify-center" data-v-100ce04a${_scopeId}><span class="text-[9px] font-black text-on-surface/40 uppercase tracking-widest mb-1" data-v-100ce04a${_scopeId}>RADICADO #</span><span class="text-xl font-black text-secondary tracking-tighter tabular-nums" data-v-100ce04a${_scopeId}>${ssrInterpolate(selectedTicket.value.id.slice(0, 12).toUpperCase())}</span></div><div class="p-8 rounded-[2rem] bg-white/[0.01] border border-white/5 flex flex-col justify-center" data-v-100ce04a${_scopeId}><span class="text-[9px] font-black text-on-surface/40 uppercase tracking-widest mb-1" data-v-100ce04a${_scopeId}>EMISOR</span><span class="text-xs font-black text-on-surface dark:text-white uppercase tracking-tighter truncate" data-v-100ce04a${_scopeId}>${ssrInterpolate(selectedTicket.value.user.name)}</span></div></div></div><form class="space-y-12 px-1" data-v-100ce04a${_scopeId}><div class="grid grid-cols-1 md:grid-cols-2 gap-10" data-v-100ce04a${_scopeId}>`);
						_push(ssrRenderComponent(Select_default, {
							modelValue: unref(form).priority,
							"onUpdate:modelValue": ($event) => unref(form).priority = $event,
							label: "Re-calibrar Impacto Real",
							options: priorityOptions,
							icon: "shield_with_heart"
						}, null, _parent, _scopeId));
						_push(ssrRenderComponent(Select_default, {
							modelValue: unref(form).status,
							"onUpdate:modelValue": ($event) => unref(form).status = $event,
							label: "Estado de la Operación",
							options: statusOptions,
							icon: "query_stats"
						}, null, _parent, _scopeId));
						_push(`</div><div class="space-y-4" data-v-100ce04a${_scopeId}><div class="flex items-center gap-3 ml-2" data-v-100ce04a${_scopeId}><div class="w-1.5 h-1.5 rounded-full bg-secondary animate-pulse" data-v-100ce04a${_scopeId}></div><label class="text-[10px] font-black text-on-surface-variant/50 dark:text-white/30 uppercase tracking-[0.3em] italic" data-v-100ce04a${_scopeId}>Bitácora de Ingeniería (Feed de Usuario)</label></div><textarea rows="6" class="w-full bg-surface-container-low dark:bg-white/[0.03] border-2 border-outline-variant/10 dark:border-white/5 rounded-[3.5rem] p-10 text-[14px] font-medium text-on-surface dark:text-white/90 focus:ring-8 focus:ring-secondary/5 outline-none transition-all placeholder:text-on-surface-variant/20 italic shadow-2xl resize-none no-scrollbar" placeholder="Escriba el diagnóstico final o el estado actual de la resolución..." data-v-100ce04a${_scopeId}>${ssrInterpolate(unref(form).resolution_notes)}</textarea></div><div class="flex flex-col sm:flex-row gap-6 border-t border-outline-variant/10 dark:border-white/5 pt-12" data-v-100ce04a${_scopeId}>`);
						_push(ssrRenderComponent(Button_default, {
							type: "button",
							variant: "ghost",
							class: "sm:w-1/3 !h-20 !rounded-[2.5rem] !text-[10px] font-black uppercase tracking-[0.3em] italic",
							onClick: ($event) => showManageModal.value = false
						}, {
							default: withCtx((_, _push, _parent, _scopeId) => {
								if (_push) _push(` Cerrar Monitor `);
								else return [createTextVNode(" Cerrar Monitor ")];
							}),
							_: 1
						}, _parent, _scopeId));
						_push(ssrRenderComponent(Button_default, {
							type: "submit",
							variant: "secondary",
							size: "lg",
							class: "sm:w-2/3 !h-20 !rounded-[2.5rem] !text-xs font-black uppercase italic shadow-2xl shadow-secondary/20",
							disabled: unref(form).processing,
							icon: "verified"
						}, {
							default: withCtx((_, _push, _parent, _scopeId) => {
								if (_push) _push(`${ssrInterpolate(unref(form).processing ? "ACTUALIZANDO NÚCLEO..." : "Sincronizar Resolución Técnica")}`);
								else return [createTextVNode(toDisplayString(unref(form).processing ? "ACTUALIZANDO NÚCLEO..." : "Sincronizar Resolución Técnica"), 1)];
							}),
							_: 1
						}, _parent, _scopeId));
						_push(`</div></form></div>`);
					} else _push(`<!---->`);
					else return [selectedTicket.value ? (openBlock(), createBlock("div", {
						key: 0,
						class: "space-y-12"
					}, [createVNode("div", { class: "grid grid-cols-1 lg:grid-cols-3 gap-10" }, [createVNode("div", { class: "lg:col-span-2 bg-white/[0.02] p-10 rounded-[3.5rem] border border-outline-variant/10 dark:border-white/5 relative italic shadow-inner" }, [createVNode("p", { class: "text-[10px] font-black text-secondary uppercase tracking-[0.4em] mb-6 leading-none italic" }, "Reporte del Usuario:"), createVNode("p", { class: "text-[15px] font-medium text-on-surface-variant dark:text-white/70 leading-relaxed" }, toDisplayString(selectedTicket.value.description), 1)]), createVNode("div", { class: "flex flex-col gap-6" }, [createVNode("div", { class: "p-8 rounded-[2rem] bg-secondary/5 border border-secondary/10 flex flex-col justify-center" }, [createVNode("span", { class: "text-[9px] font-black text-on-surface/40 uppercase tracking-widest mb-1" }, "RADICADO #"), createVNode("span", { class: "text-xl font-black text-secondary tracking-tighter tabular-nums" }, toDisplayString(selectedTicket.value.id.slice(0, 12).toUpperCase()), 1)]), createVNode("div", { class: "p-8 rounded-[2rem] bg-white/[0.01] border border-white/5 flex flex-col justify-center" }, [createVNode("span", { class: "text-[9px] font-black text-on-surface/40 uppercase tracking-widest mb-1" }, "EMISOR"), createVNode("span", { class: "text-xs font-black text-on-surface dark:text-white uppercase tracking-tighter truncate" }, toDisplayString(selectedTicket.value.user.name), 1)])])]), createVNode("form", {
						onSubmit: withModifiers(submitUpdate, ["prevent"]),
						class: "space-y-12 px-1"
					}, [
						createVNode("div", { class: "grid grid-cols-1 md:grid-cols-2 gap-10" }, [createVNode(Select_default, {
							modelValue: unref(form).priority,
							"onUpdate:modelValue": ($event) => unref(form).priority = $event,
							label: "Re-calibrar Impacto Real",
							options: priorityOptions,
							icon: "shield_with_heart"
						}, null, 8, ["modelValue", "onUpdate:modelValue"]), createVNode(Select_default, {
							modelValue: unref(form).status,
							"onUpdate:modelValue": ($event) => unref(form).status = $event,
							label: "Estado de la Operación",
							options: statusOptions,
							icon: "query_stats"
						}, null, 8, ["modelValue", "onUpdate:modelValue"])]),
						createVNode("div", { class: "space-y-4" }, [createVNode("div", { class: "flex items-center gap-3 ml-2" }, [createVNode("div", { class: "w-1.5 h-1.5 rounded-full bg-secondary animate-pulse" }), createVNode("label", { class: "text-[10px] font-black text-on-surface-variant/50 dark:text-white/30 uppercase tracking-[0.3em] italic" }, "Bitácora de Ingeniería (Feed de Usuario)")]), withDirectives(createVNode("textarea", {
							"onUpdate:modelValue": ($event) => unref(form).resolution_notes = $event,
							rows: "6",
							class: "w-full bg-surface-container-low dark:bg-white/[0.03] border-2 border-outline-variant/10 dark:border-white/5 rounded-[3.5rem] p-10 text-[14px] font-medium text-on-surface dark:text-white/90 focus:ring-8 focus:ring-secondary/5 outline-none transition-all placeholder:text-on-surface-variant/20 italic shadow-2xl resize-none no-scrollbar",
							placeholder: "Escriba el diagnóstico final o el estado actual de la resolución..."
						}, null, 8, ["onUpdate:modelValue"]), [[vModelText, unref(form).resolution_notes]])]),
						createVNode("div", { class: "flex flex-col sm:flex-row gap-6 border-t border-outline-variant/10 dark:border-white/5 pt-12" }, [createVNode(Button_default, {
							type: "button",
							variant: "ghost",
							class: "sm:w-1/3 !h-20 !rounded-[2.5rem] !text-[10px] font-black uppercase tracking-[0.3em] italic",
							onClick: ($event) => showManageModal.value = false
						}, {
							default: withCtx(() => [createTextVNode(" Cerrar Monitor ")]),
							_: 1
						}, 8, ["onClick"]), createVNode(Button_default, {
							type: "submit",
							variant: "secondary",
							size: "lg",
							class: "sm:w-2/3 !h-20 !rounded-[2.5rem] !text-xs font-black uppercase italic shadow-2xl shadow-secondary/20",
							disabled: unref(form).processing,
							icon: "verified"
						}, {
							default: withCtx(() => [createTextVNode(toDisplayString(unref(form).processing ? "ACTUALIZANDO NÚCLEO..." : "Sincronizar Resolución Técnica"), 1)]),
							_: 1
						}, 8, ["disabled"])])
					], 32)])) : createCommentVNode("", true)];
				}),
				_: 1
			}, _parent));
			_push(`</div><!--]-->`);
		};
	}
});
//#endregion
//#region resources/js/Pages/SuperAdmin/Support/Index.vue
var _sfc_setup = Index_vue_vue_type_script_setup_true_lang_default.setup;
Index_vue_vue_type_script_setup_true_lang_default.setup = (props, ctx) => {
	const ssrContext = useSSRContext();
	(ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/SuperAdmin/Support/Index.vue");
	return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
var Index_default = /* @__PURE__ */ _plugin_vue_export_helper_default(Index_vue_vue_type_script_setup_true_lang_default, [["__scopeId", "data-v-100ce04a"]]);
//#endregion
export { Index_default as default };

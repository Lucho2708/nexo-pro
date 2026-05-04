import { t as _plugin_vue_export_helper_default } from "./_plugin-vue_export-helper-BjJHhBxR.js";
import { t as AuthenticatedLayout_default } from "./AuthenticatedLayout-BYv1HA3l.js";
import { t as Button_default } from "./Button-5V-KYwxi.js";
import { t as Modal_default } from "./Modal-CeRVVYhy.js";
import { t as Badge_default } from "./Badge-DWJ2qZRx.js";
import { t as Tooltip_default } from "./Tooltip-Ba7P3Kw3.js";
import { t as Select_default } from "./Select-D3C81tr4.js";
import { t as Pagination_default } from "./Pagination-COb8YCaQ.js";
import { createBlock, createCommentVNode, createTextVNode, createVNode, defineComponent, openBlock, ref, toDisplayString, unref, useSSRContext, watch, withCtx } from "vue";
import { Head, router } from "@inertiajs/vue3";
import { ssrIncludeBooleanAttr, ssrInterpolate, ssrRenderAttr, ssrRenderClass, ssrRenderComponent, ssrRenderList } from "vue/server-renderer";
//#region resources/js/Pages/Admin/Logs/Index.vue?vue&type=script&setup=true&lang.ts
var Index_vue_vue_type_script_setup_true_lang_default = /* @__PURE__ */ defineComponent({
	layout: AuthenticatedLayout_default,
	__name: "Index",
	__ssrInlineRender: true,
	props: {
		logs: {},
		filters: {},
		metrics: {}
	},
	setup(__props) {
		const props = __props;
		const search = ref(props.filters.search || "");
		const levelName = ref(props.filters.level_name || "");
		const copropiedadId = ref(props.filters.copropiedad_id || "");
		const isRefreshing = ref(false);
		const debounce = (fn, delay) => {
			let timeoutId;
			return (...args) => {
				clearTimeout(timeoutId);
				timeoutId = setTimeout(() => fn(...args), delay);
			};
		};
		const handleSearch = (manual = false) => {
			if (manual) isRefreshing.value = true;
			router.get(route("admin.logs"), {
				search: search.value,
				level_name: levelName.value
			}, {
				preserveState: true,
				replace: true,
				onFinish: () => {
					if (manual) setTimeout(() => isRefreshing.value = false, 600);
				}
			});
		};
		const debouncedSearch = debounce(() => handleSearch(), 400);
		watch([
			search,
			levelName,
			copropiedadId
		], debouncedSearch);
		const levelConfigs = {
			INFO: {
				color: "text-blue-500",
				bg: "bg-blue-500/10",
				border: "border-blue-500/20",
				icon: "info"
			},
			NOTICE: {
				color: "text-emerald-500",
				bg: "bg-emerald-500/10",
				border: "border-emerald-500/20",
				icon: "notifications"
			},
			WARNING: {
				color: "text-amber-500",
				bg: "bg-amber-500/10",
				border: "border-amber-500/20",
				icon: "warning"
			},
			ERROR: {
				color: "text-red-500",
				bg: "bg-red-500/10",
				border: "border-red-500/20",
				icon: "chat_error"
			},
			CRITICAL: {
				color: "text-pink-500",
				bg: "bg-pink-500/10",
				border: "border-pink-500/20",
				icon: "dangerous"
			},
			ALERT: {
				color: "text-primary",
				bg: "bg-primary/10",
				border: "border-primary/20",
				icon: "report"
			},
			EMERGENCY: {
				color: "text-primary",
				bg: "bg-primary/10",
				border: "border-primary/20",
				icon: "campaign"
			}
		};
		const getLevelConfig = (level) => levelConfigs[level] || {
			color: "text-on-surface-variant",
			bg: "bg-surface-container",
			border: "border-outline-variant/10",
			icon: "terminal"
		};
		const formatDate = (dateString) => {
			return new Date(dateString).toLocaleDateString("es-CO", {
				day: "2-digit",
				month: "short",
				year: "numeric",
				hour: "2-digit",
				minute: "2-digit"
			}).toUpperCase();
		};
		const truncateMessage = (msg, limit = 80) => {
			if (!msg) return "SIN_MENSAJE";
			return msg.length > limit ? msg.substring(0, limit) + "..." : msg;
		};
		const showModal = ref(false);
		const activeLog = ref(null);
		const viewDetail = (log) => {
			activeLog.value = log;
			showModal.value = true;
		};
		const showPurgeModal = ref(false);
		const isPurging = ref(false);
		const purgeLogs = () => {
			isPurging.value = true;
			router.post(route("admin.logs.purge"), {}, {
				onSuccess: () => {
					showPurgeModal.value = false;
					toast.add("Buffer de logs locales purgado correctamente", "success");
				},
				onFinish: () => {
					isPurging.value = false;
				}
			});
		};
		return (_ctx, _push, _parent, _attrs) => {
			_push(`<!--[-->`);
			_push(ssrRenderComponent(unref(Head), { title: "Diagnóstico de Sistema — NEXO-PRO" }, null, _parent));
			_push(`<div class="space-y-8 pb-20 animate-in fade-in duration-700" data-v-a39c2a70><div class="flex flex-col md:flex-row md:items-center justify-between gap-6 px-1" data-v-a39c2a70><div class="flex items-center gap-6" data-v-a39c2a70><div class="w-14 h-14 rounded-2xl bg-secondary flex items-center justify-center shadow-2xl shadow-secondary/20" data-v-a39c2a70><span class="material-symbols-rounded text-2xl text-white" data-v-a39c2a70>developer_board</span></div><div data-v-a39c2a70><h2 class="text-3xl font-black text-on-surface dark:text-white tracking-tighter uppercase italic leading-none" data-v-a39c2a70>TERMINAL <span class="text-secondary italic" data-v-a39c2a70>SISTEMA</span></h2><div class="flex items-center gap-2 mt-2" data-v-a39c2a70><span class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-pulse" data-v-a39c2a70></span><p class="text-[8px] font-black text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.3em]" data-v-a39c2a70>Monitoreo de Salud de Infraestructura</p></div></div></div><div class="flex items-center gap-4" data-v-a39c2a70><div class="flex items-center gap-2 bg-surface-container-low dark:bg-white/5 p-1.5 rounded-2xl border border-outline-variant/10 dark:border-white/5 shadow-sm mr-2" data-v-a39c2a70>`);
			_push(ssrRenderComponent(Button_default, {
				variant: "ghost",
				icon: "refresh",
				onClick: ($event) => handleSearch(true),
				class: ["!w-10 !h-10 !p-0 !rounded-xl transition-all dark:!text-white/60", { "rotate-180 opacity-50": isRefreshing.value }]
			}, null, _parent));
			_push(`</div><div class="hidden lg:flex items-center gap-3 mr-4" data-v-a39c2a70><!--[-->`);
			ssrRenderList(__props.metrics, (count, level) => {
				_push(`<div class="px-4 py-2 rounded-xl bg-surface-container-low dark:bg-white/5 border border-outline-variant/10 dark:border-white/5 flex items-center gap-3 transition-all hover:bg-surface-container" data-v-a39c2a70><span class="${ssrRenderClass([getLevelConfig(level).color, "material-symbols-rounded text-sm"])}" data-v-a39c2a70>${ssrInterpolate(getLevelConfig(level).icon)}</span><span class="text-[10px] font-black text-on-surface dark:text-white" data-v-a39c2a70>${ssrInterpolate(count)}</span></div>`);
			});
			_push(`<!--]--></div>`);
			_push(ssrRenderComponent(Button_default, {
				variant: "primary",
				icon: "history_toggle_off",
				onClick: ($event) => showPurgeModal.value = true,
				class: "!rounded-xl !h-10 shadow-lg shadow-primary/10 !text-[9px] font-black uppercase px-6"
			}, {
				default: withCtx((_, _push, _parent, _scopeId) => {
					if (_push) _push(`Configurar Retención`);
					else return [createTextVNode("Configurar Retención")];
				}),
				_: 1
			}, _parent));
			_push(`</div></div><div class="bg-surface-container-low/50 dark:bg-white/[0.02] backdrop-blur-xl border border-outline-variant/10 dark:border-white/5 rounded-[2.5rem] p-8 shadow-xl" data-v-a39c2a70><div class="grid grid-cols-1 md:grid-cols-12 gap-8" data-v-a39c2a70><div class="md:col-span-8 space-y-2" data-v-a39c2a70><label class="text-[9px] font-black text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.2em] ml-2" data-v-a39c2a70>Análisis de Eventos (Mensaje, Clase, Traza)</label><div class="relative group" data-v-a39c2a70><span class="material-symbols-rounded absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant/40 group-focus-within:text-primary transition-colors text-lg" data-v-a39c2a70>search</span><input${ssrRenderAttr("value", search.value)} placeholder="Ejecutar escaneo de registros..." class="w-full h-14 pl-12 pr-4 bg-white dark:bg-white/[0.03] border border-outline-variant/10 dark:border-white/5 rounded-2xl text-xs font-bold text-on-surface dark:text-white focus:ring-4 focus:ring-primary/5 dark:focus:ring-primary/10 outline-none transition-all placeholder:text-on-surface-variant/20 shadow-sm" data-v-a39c2a70></div></div><div class="md:col-span-4 space-y-2" data-v-a39c2a70><label class="text-[9px] font-black text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.2em] ml-2" data-v-a39c2a70>Gravedad del Evento</label>`);
			_push(ssrRenderComponent(Select_default, {
				modelValue: levelName.value,
				"onUpdate:modelValue": ($event) => levelName.value = $event,
				options: [
					{
						value: "",
						label: "TODOS LOS NIVELES"
					},
					{
						value: "INFO",
						label: "INFORMATIVO"
					},
					{
						value: "WARNING",
						label: "ADVERTENCIAS"
					},
					{
						value: "ERROR",
						label: "FALLOS CRÍTICOS"
					}
				],
				class: "!h-14 !rounded-2xl !bg-white dark:!bg-white/[0.03] !text-[10px] !font-black dark:!text-white/80 !shadow-sm !border-outline-variant/10 dark:!border-white/5"
			}, null, _parent));
			_push(`</div></div></div><div class="bg-white dark:bg-[#0b0e14] border border-outline-variant/10 dark:border-white/5 rounded-[3rem] overflow-hidden shadow-2xl relative" data-v-a39c2a70><div class="overflow-x-auto" data-v-a39c2a70><table class="w-full text-left" data-v-a39c2a70><thead data-v-a39c2a70><tr class="bg-surface-container dark:bg-white/[0.01] border-b border-outline-variant/10 dark:border-white/5" data-v-a39c2a70><th class="px-8 py-5 text-[9px] font-black text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.2em]" data-v-a39c2a70>Severidad</th><th class="px-8 py-5 text-[9px] font-black text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.2em]" data-v-a39c2a70>Timestamp UTC</th><th class="px-8 py-5 text-[9px] font-black text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.2em]" data-v-a39c2a70>Mensaje / Resumen de Diagnóstico</th><th class="px-8 py-5 text-[9px] font-black text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.2em]" data-v-a39c2a70>Módulo / Tenant</th><th class="px-8 py-5 text-[9px] font-black text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.2em] text-center" data-v-a39c2a70>Acción</th></tr></thead><tbody class="divide-y divide-outline-variant/5 dark:divide-white/[0.02]" data-v-a39c2a70><!--[-->`);
			ssrRenderList(__props.logs.data, (log) => {
				_push(`<tr class="group hover:bg-primary/[0.008] dark:hover:bg-primary/[0.03] transition-all" data-v-a39c2a70><td class="px-8 py-6 whitespace-nowrap" data-v-a39c2a70><div class="${ssrRenderClass([[getLevelConfig(log.level_name).bg, getLevelConfig(log.level_name).border], "flex items-center gap-3 px-3 py-1.5 rounded-xl border transition-all"])}" data-v-a39c2a70><span class="${ssrRenderClass([getLevelConfig(log.level_name).color, "material-symbols-rounded text-sm"])}" data-v-a39c2a70>${ssrInterpolate(getLevelConfig(log.level_name).icon)}</span><span class="text-[9px] font-black uppercase tracking-widest text-on-surface dark:text-white" data-v-a39c2a70>${ssrInterpolate(log.level_name)}</span></div></td><td class="px-8 py-6 whitespace-nowrap" data-v-a39c2a70><p class="text-[10px] font-black text-on-surface dark:text-white/80 italic" data-v-a39c2a70>${ssrInterpolate(formatDate(log.created_at))}</p><p class="text-[8px] font-bold text-on-surface-variant/30 uppercase mt-1 tracking-widest" data-v-a39c2a70>Servidor Local</p></td><td class="px-8 py-6 max-w-sm" data-v-a39c2a70><p class="text-xs font-bold text-on-surface dark:text-white leading-relaxed group-hover:text-primary transition-colors" data-v-a39c2a70>${ssrInterpolate(truncateMessage(log.message))}</p></td><td class="px-8 py-6" data-v-a39c2a70>`);
				if (log.copropiedad) _push(`<div class="flex flex-col" data-v-a39c2a70><span class="text-[10px] font-black text-primary uppercase leading-none tracking-tight" data-v-a39c2a70>${ssrInterpolate(log.copropiedad.nombre)}</span><span class="text-[8px] font-bold text-on-surface-variant/30 uppercase mt-1 tracking-widest" data-v-a39c2a70>${ssrInterpolate(log.user?.name || "DAEMON")}</span></div>`);
				else _push(`<div data-v-a39c2a70><span class="text-[9px] font-black text-on-surface-variant/20 dark:text-white/10 uppercase tracking-[0.2em]" data-v-a39c2a70>SISTEMA_CORE</span></div>`);
				_push(`</td><td class="px-8 py-6 text-center" data-v-a39c2a70>`);
				_push(ssrRenderComponent(Tooltip_default, { text: "Ver Stack Trace" }, {
					default: withCtx((_, _push, _parent, _scopeId) => {
						if (_push) _push(`<button class="w-10 h-10 rounded-xl bg-surface-container dark:bg-white/5 border border-outline-variant/10 dark:border-white/5 flex items-center justify-center text-on-surface-variant dark:text-white/40 hover:bg-primary dark:hover:bg-primary hover:text-white dark:hover:text-white transition-all shadow-sm active:scale-95 mx-auto" data-v-a39c2a70${_scopeId}><span class="material-symbols-rounded text-lg font-variation-settings-fill" data-v-a39c2a70${_scopeId}>terminal</span></button>`);
						else return [createVNode("button", {
							onClick: ($event) => viewDetail(log),
							class: "w-10 h-10 rounded-xl bg-surface-container dark:bg-white/5 border border-outline-variant/10 dark:border-white/5 flex items-center justify-center text-on-surface-variant dark:text-white/40 hover:bg-primary dark:hover:bg-primary hover:text-white dark:hover:text-white transition-all shadow-sm active:scale-95 mx-auto"
						}, [createVNode("span", { class: "material-symbols-rounded text-lg font-variation-settings-fill" }, "terminal")], 8, ["onClick"])];
					}),
					_: 2
				}, _parent));
				_push(`</td></tr>`);
			});
			_push(`<!--]-->`);
			if (__props.logs.data.length === 0) _push(`<tr data-v-a39c2a70><td colspan="5" class="px-8 py-24 text-center" data-v-a39c2a70><div class="flex flex-col items-center opacity-30" data-v-a39c2a70><span class="material-symbols-rounded text-6xl mb-4" data-v-a39c2a70>analytics</span><p class="text-xs font-black uppercase tracking-[0.3em]" data-v-a39c2a70>Cero anomalías detectadas en este sector</p></div></td></tr>`);
			else _push(`<!---->`);
			_push(`</tbody></table></div><div class="px-10 py-12 bg-surface-container/5 dark:bg-white/[0.01] border-t border-outline-variant/5 dark:border-white/5 flex justify-center" data-v-a39c2a70>`);
			_push(ssrRenderComponent(Pagination_default, { links: __props.logs.links }, null, _parent));
			_push(`</div></div>`);
			_push(ssrRenderComponent(Modal_default, {
				show: showModal.value,
				onClose: ($event) => showModal.value = false,
				"max-width": "3xl"
			}, {
				default: withCtx((_, _push, _parent, _scopeId) => {
					if (_push) {
						_push(`<div class="p-10 space-y-10 bg-[#0b0e14] text-white" data-v-a39c2a70${_scopeId}><header class="flex items-start justify-between border-b border-white/5 pb-8" data-v-a39c2a70${_scopeId}><div class="flex items-center gap-6" data-v-a39c2a70${_scopeId}><div class="${ssrRenderClass([activeLog.value ? getLevelConfig(activeLog.value.level_name).bg : "", "w-16 h-16 rounded-[1.8rem] flex items-center justify-center text-white shadow-2xl rotate-3"])}" data-v-a39c2a70${_scopeId}><span class="${ssrRenderClass([activeLog.value ? getLevelConfig(activeLog.value.level_name).color : "", "material-symbols-rounded text-3xl"])}" data-v-a39c2a70${_scopeId}>${ssrInterpolate(activeLog.value ? getLevelConfig(activeLog.value.level_name).icon : "terminal")}</span></div><div data-v-a39c2a70${_scopeId}><h3 class="text-3xl font-black tracking-tighter uppercase leading-none italic" data-v-a39c2a70${_scopeId}>Diagnóstico <span class="text-secondary font-black" data-v-a39c2a70${_scopeId}>Profundo</span></h3><p class="text-[10px] font-bold text-white/40 uppercase tracking-[0.3em] mt-2 italic" data-v-a39c2a70${_scopeId}>Análisis estructural de evento de infraestructura</p></div></div><button class="text-white/20 hover:text-white transition-colors" data-v-a39c2a70${_scopeId}><span class="material-symbols-rounded text-3xl" data-v-a39c2a70${_scopeId}>close</span></button></header>`);
						if (activeLog.value) {
							_push(`<div class="space-y-10" data-v-a39c2a70${_scopeId}><div class="grid grid-cols-1 md:grid-cols-3 gap-6" data-v-a39c2a70${_scopeId}><div class="bg-white/5 p-6 rounded-2xl border border-white/5" data-v-a39c2a70${_scopeId}><span class="text-[9px] font-black text-white/30 uppercase tracking-[0.2em] block mb-2" data-v-a39c2a70${_scopeId}>Timestamp</span><span class="text-xs font-black text-blue-400 font-mono" data-v-a39c2a70${_scopeId}>${ssrInterpolate(formatDate(activeLog.value.created_at))}</span></div><div class="bg-white/5 p-6 rounded-2xl border border-white/5" data-v-a39c2a70${_scopeId}><span class="text-[9px] font-black text-white/30 uppercase tracking-[0.2em] block mb-2" data-v-a39c2a70${_scopeId}>Environment</span>`);
							_push(ssrRenderComponent(Badge_default, {
								variant: "primary",
								class: "!px-4 !py-1 !font-black !text-[10px] uppercase tracking-widest"
							}, {
								default: withCtx((_, _push, _parent, _scopeId) => {
									if (_push) _push(`${ssrInterpolate(activeLog.value.env || "LOCAL")}`);
									else return [createTextVNode(toDisplayString(activeLog.value.env || "LOCAL"), 1)];
								}),
								_: 1
							}, _parent, _scopeId));
							_push(`</div><div class="bg-white/5 p-6 rounded-2xl border border-white/5" data-v-a39c2a70${_scopeId}><span class="text-[9px] font-black text-white/30 uppercase tracking-[0.2em] block mb-2" data-v-a39c2a70${_scopeId}>Log ID</span><span class="text-xs font-black text-white/80 font-mono" data-v-a39c2a70${_scopeId}>#${ssrInterpolate(activeLog.value.id)}</span></div></div><div class="space-y-4" data-v-a39c2a70${_scopeId}><h5 class="text-[11px] font-black text-secondary uppercase tracking-[0.3em]" data-v-a39c2a70${_scopeId}>Cuerpo del Evento</h5><div class="bg-white/[0.03] p-8 rounded-[2.5rem] border border-white/5" data-v-a39c2a70${_scopeId}><p class="text-sm font-bold text-white leading-relaxed italic border-l-4 border-secondary pl-6" data-v-a39c2a70${_scopeId}>${ssrInterpolate(activeLog.value.message)}</p></div></div><div class="space-y-4" data-v-a39c2a70${_scopeId}><h5 class="text-[11px] font-black text-secondary uppercase tracking-[0.3em]" data-v-a39c2a70${_scopeId}>Stack Trace / Contexto JSON</h5><div class="bg-[#05070a] rounded-[2.5rem] p-8 border border-white/5 shadow-inner relative group overflow-hidden" data-v-a39c2a70${_scopeId}><div class="absolute top-4 right-6 text-[8px] font-black text-white/10 uppercase tracking-widest" data-v-a39c2a70${_scopeId}>application/json</div><pre class="text-emerald-400/80 font-mono text-[11px] leading-relaxed overflow-x-auto whitespace-pre-wrap max-h-60 custom-scrollbar" data-v-a39c2a70${_scopeId}>${ssrInterpolate(JSON.stringify(activeLog.value.context || {}, null, 4))}
                            </pre></div></div></div>`);
						} else _push(`<!---->`);
						_push(`<div class="flex justify-end pt-4 gap-4" data-v-a39c2a70${_scopeId}><button class="px-12 py-4 bg-secondary text-white font-black text-[11px] uppercase tracking-[0.3em] rounded-[1.5rem] shadow-2xl shadow-secondary/30 hover:scale-105 transition-all outline-none" data-v-a39c2a70${_scopeId}>CERRAR DIAGNÓSTICO</button></div></div>`);
					} else return [createVNode("div", { class: "p-10 space-y-10 bg-[#0b0e14] text-white" }, [
						createVNode("header", { class: "flex items-start justify-between border-b border-white/5 pb-8" }, [createVNode("div", { class: "flex items-center gap-6" }, [createVNode("div", { class: ["w-16 h-16 rounded-[1.8rem] flex items-center justify-center text-white shadow-2xl rotate-3", activeLog.value ? getLevelConfig(activeLog.value.level_name).bg : ""] }, [createVNode("span", { class: ["material-symbols-rounded text-3xl", activeLog.value ? getLevelConfig(activeLog.value.level_name).color : ""] }, toDisplayString(activeLog.value ? getLevelConfig(activeLog.value.level_name).icon : "terminal"), 3)], 2), createVNode("div", null, [createVNode("h3", { class: "text-3xl font-black tracking-tighter uppercase leading-none italic" }, [createTextVNode("Diagnóstico "), createVNode("span", { class: "text-secondary font-black" }, "Profundo")]), createVNode("p", { class: "text-[10px] font-bold text-white/40 uppercase tracking-[0.3em] mt-2 italic" }, "Análisis estructural de evento de infraestructura")])]), createVNode("button", {
							onClick: ($event) => showModal.value = false,
							class: "text-white/20 hover:text-white transition-colors"
						}, [createVNode("span", { class: "material-symbols-rounded text-3xl" }, "close")], 8, ["onClick"])]),
						activeLog.value ? (openBlock(), createBlock("div", {
							key: 0,
							class: "space-y-10"
						}, [
							createVNode("div", { class: "grid grid-cols-1 md:grid-cols-3 gap-6" }, [
								createVNode("div", { class: "bg-white/5 p-6 rounded-2xl border border-white/5" }, [createVNode("span", { class: "text-[9px] font-black text-white/30 uppercase tracking-[0.2em] block mb-2" }, "Timestamp"), createVNode("span", { class: "text-xs font-black text-blue-400 font-mono" }, toDisplayString(formatDate(activeLog.value.created_at)), 1)]),
								createVNode("div", { class: "bg-white/5 p-6 rounded-2xl border border-white/5" }, [createVNode("span", { class: "text-[9px] font-black text-white/30 uppercase tracking-[0.2em] block mb-2" }, "Environment"), createVNode(Badge_default, {
									variant: "primary",
									class: "!px-4 !py-1 !font-black !text-[10px] uppercase tracking-widest"
								}, {
									default: withCtx(() => [createTextVNode(toDisplayString(activeLog.value.env || "LOCAL"), 1)]),
									_: 1
								})]),
								createVNode("div", { class: "bg-white/5 p-6 rounded-2xl border border-white/5" }, [createVNode("span", { class: "text-[9px] font-black text-white/30 uppercase tracking-[0.2em] block mb-2" }, "Log ID"), createVNode("span", { class: "text-xs font-black text-white/80 font-mono" }, "#" + toDisplayString(activeLog.value.id), 1)])
							]),
							createVNode("div", { class: "space-y-4" }, [createVNode("h5", { class: "text-[11px] font-black text-secondary uppercase tracking-[0.3em]" }, "Cuerpo del Evento"), createVNode("div", { class: "bg-white/[0.03] p-8 rounded-[2.5rem] border border-white/5" }, [createVNode("p", { class: "text-sm font-bold text-white leading-relaxed italic border-l-4 border-secondary pl-6" }, toDisplayString(activeLog.value.message), 1)])]),
							createVNode("div", { class: "space-y-4" }, [createVNode("h5", { class: "text-[11px] font-black text-secondary uppercase tracking-[0.3em]" }, "Stack Trace / Contexto JSON"), createVNode("div", { class: "bg-[#05070a] rounded-[2.5rem] p-8 border border-white/5 shadow-inner relative group overflow-hidden" }, [createVNode("div", { class: "absolute top-4 right-6 text-[8px] font-black text-white/10 uppercase tracking-widest" }, "application/json"), createVNode("pre", { class: "text-emerald-400/80 font-mono text-[11px] leading-relaxed overflow-x-auto whitespace-pre-wrap max-h-60 custom-scrollbar" }, "" + toDisplayString(JSON.stringify(activeLog.value.context || {}, null, 4)) + "\n                            ", 1)])])
						])) : createCommentVNode("", true),
						createVNode("div", { class: "flex justify-end pt-4 gap-4" }, [createVNode("button", {
							onClick: ($event) => showModal.value = false,
							class: "px-12 py-4 bg-secondary text-white font-black text-[11px] uppercase tracking-[0.3em] rounded-[1.5rem] shadow-2xl shadow-secondary/30 hover:scale-105 transition-all outline-none"
						}, "CERRAR DIAGNÓSTICO", 8, ["onClick"])])
					])];
				}),
				_: 1
			}, _parent));
			_push(ssrRenderComponent(Modal_default, {
				show: showPurgeModal.value,
				onClose: ($event) => showPurgeModal.value = false,
				"max-width": "md"
			}, {
				default: withCtx((_, _push, _parent, _scopeId) => {
					if (_push) _push(`<div class="p-10 bg-[#0b0e14] text-white" data-v-a39c2a70${_scopeId}><div class="w-20 h-20 bg-primary/20 rounded-[2rem] flex items-center justify-center mx-auto mb-8 animate-pulse" data-v-a39c2a70${_scopeId}><span class="material-symbols-rounded text-4xl text-primary" data-v-a39c2a70${_scopeId}>auto_delete</span></div><h3 class="text-2xl font-black text-center uppercase tracking-tighter italic" data-v-a39c2a70${_scopeId}>Limpieza de <span class="text-primary italic" data-v-a39c2a70${_scopeId}>Registros</span></h3><p class="text-[11px] text-white/40 text-center uppercase tracking-[0.2em] mt-4 leading-relaxed" data-v-a39c2a70${_scopeId}> Estás a punto de eliminar todos los logs con más de <span class="text-white font-black" data-v-a39c2a70${_scopeId}>30 días de antigüedad</span>. Esta acción es irreversible. </p><div class="flex flex-col gap-4 mt-10" data-v-a39c2a70${_scopeId}><button${ssrIncludeBooleanAttr(isPurging.value) ? " disabled" : ""} class="w-full py-4 bg-primary text-white font-black text-[11px] uppercase tracking-[0.3em] rounded-2xl shadow-2xl shadow-primary/20 hover:scale-[1.02] active:scale-95 transition-all disabled:opacity-50" data-v-a39c2a70${_scopeId}>${ssrInterpolate(isPurging.value ? "EJECUTANDO LIMPIEZA..." : "CONFIRMAR PURGA")}</button><button class="w-full py-4 bg-white/5 text-white/40 font-black text-[11px] uppercase tracking-[0.3em] rounded-2xl hover:text-white transition-all" data-v-a39c2a70${_scopeId}> CANCELAR OPERACIÓN </button></div></div>`);
					else return [createVNode("div", { class: "p-10 bg-[#0b0e14] text-white" }, [
						createVNode("div", { class: "w-20 h-20 bg-primary/20 rounded-[2rem] flex items-center justify-center mx-auto mb-8 animate-pulse" }, [createVNode("span", { class: "material-symbols-rounded text-4xl text-primary" }, "auto_delete")]),
						createVNode("h3", { class: "text-2xl font-black text-center uppercase tracking-tighter italic" }, [createTextVNode("Limpieza de "), createVNode("span", { class: "text-primary italic" }, "Registros")]),
						createVNode("p", { class: "text-[11px] text-white/40 text-center uppercase tracking-[0.2em] mt-4 leading-relaxed" }, [
							createTextVNode(" Estás a punto de eliminar todos los logs con más de "),
							createVNode("span", { class: "text-white font-black" }, "30 días de antigüedad"),
							createTextVNode(". Esta acción es irreversible. ")
						]),
						createVNode("div", { class: "flex flex-col gap-4 mt-10" }, [createVNode("button", {
							onClick: purgeLogs,
							disabled: isPurging.value,
							class: "w-full py-4 bg-primary text-white font-black text-[11px] uppercase tracking-[0.3em] rounded-2xl shadow-2xl shadow-primary/20 hover:scale-[1.02] active:scale-95 transition-all disabled:opacity-50"
						}, toDisplayString(isPurging.value ? "EJECUTANDO LIMPIEZA..." : "CONFIRMAR PURGA"), 9, ["disabled"]), createVNode("button", {
							onClick: ($event) => showPurgeModal.value = false,
							class: "w-full py-4 bg-white/5 text-white/40 font-black text-[11px] uppercase tracking-[0.3em] rounded-2xl hover:text-white transition-all"
						}, " CANCELAR OPERACIÓN ", 8, ["onClick"])])
					])];
				}),
				_: 1
			}, _parent));
			_push(`</div><!--]-->`);
		};
	}
});
//#endregion
//#region resources/js/Pages/Admin/Logs/Index.vue
var _sfc_setup = Index_vue_vue_type_script_setup_true_lang_default.setup;
Index_vue_vue_type_script_setup_true_lang_default.setup = (props, ctx) => {
	const ssrContext = useSSRContext();
	(ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Admin/Logs/Index.vue");
	return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
var Index_default = /* @__PURE__ */ _plugin_vue_export_helper_default(Index_vue_vue_type_script_setup_true_lang_default, [["__scopeId", "data-v-a39c2a70"]]);
//#endregion
export { Index_default as default };

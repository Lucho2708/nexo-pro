import { t as _plugin_vue_export_helper_default } from "./_plugin-vue_export-helper-BjJHhBxR.js";
import { t as AuthenticatedLayout_default } from "./AuthenticatedLayout-vxulH9w9.js";
import { t as Button_default } from "./Button-C-byZbTR.js";
import { t as Modal_default } from "./Modal-DfwT9E3X.js";
import { t as Badge_default } from "./Badge-DsYztQFV.js";
import { t as Tooltip_default } from "./Tooltip-IAP-zsdE.js";
import { t as Select_default } from "./Select-DRXhACf5.js";
import { t as Pagination_default } from "./Pagination-Cbz_SoNa.js";
import { t as DatePicker_default } from "./DatePicker-DD5d8BKS.js";
import { computed, createBlock, createCommentVNode, createTextVNode, createVNode, defineComponent, openBlock, ref, toDisplayString, unref, useSSRContext, watch, withCtx } from "vue";
import { Head, router } from "@inertiajs/vue3";
import { ssrInterpolate, ssrRenderAttr, ssrRenderClass, ssrRenderComponent, ssrRenderList } from "vue/server-renderer";
import VueApexCharts from "vue3-apexcharts";
//#region resources/js/Pages/SuperAdmin/Audit/Index.vue?vue&type=script&setup=true&lang.ts
var Index_vue_vue_type_script_setup_true_lang_default = /* @__PURE__ */ defineComponent({
	layout: AuthenticatedLayout_default,
	__name: "Index",
	__ssrInlineRender: true,
	props: {
		logs: {},
		features: {},
		copropiedades: {},
		filters: {},
		chartData: {}
	},
	setup(__props) {
		const props = __props;
		const form = ref({
			search: props.filters.search || "",
			feature: props.filters.feature || "",
			copropiedad_id: props.filters.copropiedad_id || "",
			date_from: props.filters.date_from || "",
			date_to: props.filters.date_to || ""
		});
		const isRefreshing = ref(false);
		const debounce = (fn, delay) => {
			let timeoutId;
			return (...args) => {
				clearTimeout(timeoutId);
				timeoutId = setTimeout(() => fn(...args), delay);
			};
		};
		const applyFilters = (isManual = false) => {
			if (isManual) isRefreshing.value = true;
			router.get(route("superadmin.audit"), { ...form.value }, {
				preserveState: true,
				replace: true,
				only: [
					"logs",
					"filters",
					"chartData"
				],
				onFinish: () => {
					if (isManual) setTimeout(() => isRefreshing.value = false, 600);
				}
			});
		};
		const debouncedApply = debounce(() => applyFilters(), 400);
		watch(form, () => debouncedApply(), { deep: true });
		const exportAudit = () => {
			window.location.href = route("superadmin.audit.export", form.value);
		};
		const showDetailModal = ref(false);
		const activeLog = ref(null);
		const viewDetail = (log) => {
			activeLog.value = log;
			showDetailModal.value = true;
		};
		const chartOptions = computed(() => ({
			chart: {
				type: "area",
				toolbar: { show: false },
				sparkline: { enabled: true }
			},
			stroke: {
				curve: "smooth",
				width: 2
			},
			fill: {
				type: "gradient",
				gradient: {
					shadeIntensity: 1,
					opacityFrom: .3,
					opacityTo: .05,
					stops: [
						0,
						90,
						100
					]
				}
			},
			colors: ["#3b82f6"]
		}));
		const featureConfigs = {
			dashboard: {
				icon: "monitoring",
				color: "text-blue-500 bg-blue-500/10",
				border: "border-blue-500/20"
			},
			cartera: {
				icon: "account_balance_wallet",
				color: "text-emerald-500 bg-emerald-500/10",
				border: "border-emerald-500/20"
			},
			pqrs: {
				icon: "forum",
				color: "text-pink-500 bg-pink-500/10",
				border: "border-pink-500/20"
			},
			reservas: {
				icon: "calendar_today",
				color: "text-amber-500 bg-amber-500/10",
				border: "border-amber-500/20"
			},
			pagos: {
				icon: "payments",
				color: "text-indigo-500 bg-indigo-500/10",
				border: "border-indigo-500/20"
			},
			seguridad: {
				icon: "security",
				color: "text-red-500 bg-red-500/10",
				border: "border-red-500/20"
			},
			usuarios: {
				icon: "group",
				color: "text-purple-500 bg-purple-500/10",
				border: "border-purple-500/20"
			},
			licencias: {
				icon: "verified",
				color: "text-primary bg-primary/10",
				border: "border-primary/20"
			},
			configuracion: {
				icon: "settings",
				color: "text-orange-500 bg-orange-500/10",
				border: "border-orange-500/20"
			},
			comunicacion: {
				icon: "campaign",
				color: "text-cyan-500 bg-cyan-500/10",
				border: "border-cyan-500/20"
			},
			notificaciones: {
				icon: "notifications_active",
				color: "text-yellow-500 bg-yellow-500/10",
				border: "border-yellow-500/20"
			},
			conjuntos: {
				icon: "business",
				color: "text-teal-500 bg-teal-500/10",
				border: "border-teal-500/20"
			},
			transacciones: {
				icon: "receipt_long",
				color: "text-lime-500 bg-lime-500/10",
				border: "border-lime-500/20"
			}
		};
		const getFeatureConfig = (feature) => {
			if (!feature) return {
				icon: "settings_slow_motion",
				color: "text-on-surface-variant/40 bg-surface-container",
				border: "border-outline-variant/10"
			};
			return featureConfigs[feature.toLowerCase()] || {
				icon: "rocket_launch",
				color: "text-primary bg-primary/10",
				border: "border-primary/20"
			};
		};
		const resetFilters = () => {
			form.value = {
				search: "",
				feature: "",
				copropiedad_id: "",
				date_from: "",
				date_to: ""
			};
		};
		return (_ctx, _push, _parent, _attrs) => {
			_push(`<!--[-->`);
			_push(ssrRenderComponent(unref(Head), { title: "Auditoría Forense — NEXO-PRO" }, null, _parent));
			_push(`<div class="space-y-8 pb-20 animate-in fade-in duration-700" data-v-15856f77><div class="flex flex-col md:flex-row md:items-center justify-between gap-6 px-1" data-v-15856f77><div class="flex items-center gap-6" data-v-15856f77><div class="w-14 h-14 rounded-2xl bg-primary flex items-center justify-center shadow-2xl shadow-primary/20" data-v-15856f77><span class="material-symbols-rounded text-2xl text-white" data-v-15856f77>security_update_good</span></div><div data-v-15856f77><h2 class="text-3xl font-black text-primary tracking-tighter uppercase italic leading-none" data-v-15856f77>BITÁCORA <span class="text-on-surface-variant/20 dark:text-white/10 italic" data-v-15856f77>NIVEL 4</span></h2><div class="flex items-center gap-2 mt-2" data-v-15856f77><span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse" data-v-15856f77></span><p class="text-[8px] font-black text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.3em]" data-v-15856f77>Monitoreo Persistente — En línea</p></div></div></div><div class="flex items-center gap-2 bg-surface-container-low dark:bg-white/5 p-1.5 rounded-2xl border border-outline-variant/10 dark:border-white/5 shadow-sm" data-v-15856f77>`);
			_push(ssrRenderComponent(Button_default, {
				variant: "ghost",
				icon: "refresh",
				onClick: ($event) => applyFilters(true),
				class: ["!w-10 !h-10 !p-0 !rounded-xl transition-all dark:!text-white/60", { "rotate-180 opacity-50": isRefreshing.value }]
			}, null, _parent));
			_push(`<div class="h-6 w-px bg-outline-variant/20 dark:bg-white/10 mx-1" data-v-15856f77></div>`);
			_push(ssrRenderComponent(Button_default, {
				variant: "outline",
				icon: "filter_list_off",
				onClick: resetFilters,
				class: "!border-transparent !text-[9px] uppercase font-black px-4 h-10 dark:!text-white/60"
			}, {
				default: withCtx((_, _push, _parent, _scopeId) => {
					if (_push) _push(`Limpiar`);
					else return [createTextVNode("Limpiar")];
				}),
				_: 1
			}, _parent));
			_push(ssrRenderComponent(Button_default, {
				variant: "primary",
				icon: "description",
				onClick: exportAudit,
				class: "!rounded-xl !h-10 shadow-lg shadow-primary/10 !text-[9px] font-black uppercase px-6"
			}, {
				default: withCtx((_, _push, _parent, _scopeId) => {
					if (_push) _push(`Exportar`);
					else return [createTextVNode("Exportar")];
				}),
				_: 1
			}, _parent));
			_push(`</div></div><div class="grid grid-cols-1 lg:grid-cols-4 gap-6" data-v-15856f77><div class="lg:col-span-3 bg-[#0b0e14] rounded-[2.5rem] p-8 relative overflow-hidden group border border-white/5 shadow-2xl" data-v-15856f77><div class="absolute top-0 right-0 p-6 text-white/5 opacity-40" data-v-15856f77><span class="material-symbols-rounded text-6xl" data-v-15856f77>insights</span></div><div class="flex items-end justify-between relative z-10" data-v-15856f77><div data-v-15856f77><h4 class="text-[9px] font-black text-primary uppercase tracking-[0.3em] mb-3" data-v-15856f77>Reporte de frecuencia de eventos</h4><p class="text-4xl font-black text-white tracking-tighter italic" data-v-15856f77>99.9% <span class="text-xs text-white/20 font-bold tracking-[0.2em] uppercase not-italic ml-2 italic" data-v-15856f77>Integridad Total</span></p></div><div class="flex flex-col items-end" data-v-15856f77><span class="text-[7px] font-black text-white/20 uppercase tracking-[0.3em] mb-2" data-v-15856f77>Actividad Consolidada</span><div class="w-40 h-10" data-v-15856f77>`);
			_push(ssrRenderComponent(unref(VueApexCharts), {
				type: "area",
				height: "100%",
				options: chartOptions.value,
				series: __props.chartData.series
			}, null, _parent));
			_push(`</div></div></div></div><div class="bg-primary rounded-[2.5rem] p-8 text-on-primary shadow-2xl shadow-primary/20 flex flex-col justify-between" data-v-15856f77><div class="flex justify-between items-start" data-v-15856f77><h4 class="text-[10px] font-black uppercase tracking-[0.3em] opacity-60 text-white" data-v-15856f77>Eventos Validados</h4><span class="material-symbols-rounded text-2xl opacity-40 text-white" data-v-15856f77>hub</span></div><div class="mt-4" data-v-15856f77><p class="text-5xl font-black tracking-tighter leading-none text-white" data-v-15856f77>${ssrInterpolate(props.logs.total.toLocaleString())}</p><p class="text-[8px] font-bold uppercase tracking-widest mt-3 opacity-40 text-white" data-v-15856f77>Registros en el periodo actual</p></div></div></div><div class="bg-surface-container-low/50 dark:bg-white/[0.02] backdrop-blur-xl border border-outline-variant/10 dark:border-white/5 rounded-[2.5rem] p-8 shadow-xl space-y-8" data-v-15856f77><div class="grid grid-cols-1 md:grid-cols-12 gap-6" data-v-15856f77><div class="md:col-span-4 space-y-2" data-v-15856f77><label class="text-[9px] font-black text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.2em] ml-2" data-v-15856f77>Búsqueda de Usuario</label><div class="relative group" data-v-15856f77><span class="material-symbols-rounded absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant/40 group-focus-within:text-primary transition-colors text-lg" data-v-15856f77>manage_search</span><input${ssrRenderAttr("value", form.value.search)} placeholder="Email o Nombre..." class="w-full h-12 pl-12 pr-4 bg-white dark:bg-white/[0.03] border border-outline-variant/10 dark:border-white/5 rounded-2xl text-xs font-bold text-on-surface dark:text-white focus:ring-4 focus:ring-primary/5 dark:focus:ring-primary/10 outline-none transition-all placeholder:text-on-surface-variant/20 shadow-sm" data-v-15856f77></div></div><div class="md:col-span-3 space-y-2" data-v-15856f77><label class="text-[9px] font-black text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.2em] ml-2" data-v-15856f77>Asociación de Conjunto</label>`);
			_push(ssrRenderComponent(Select_default, {
				modelValue: form.value.copropiedad_id,
				"onUpdate:modelValue": ($event) => form.value.copropiedad_id = $event,
				options: [{
					value: "",
					label: "Todas las Copropiedades"
				}, ...__props.copropiedades.map((c) => ({
					value: c.id,
					label: c.nombre.toUpperCase()
				}))],
				class: "!h-12 !rounded-2xl !bg-white dark:!bg-white/[0.03] !text-[11px] !font-black dark:!text-white/80 !shadow-sm !border-outline-variant/10 dark:!border-white/5"
			}, null, _parent));
			_push(`</div><div class="md:col-span-5 grid grid-cols-2 gap-4 items-end" data-v-15856f77><div class="space-y-2" data-v-15856f77><label class="text-[9px] font-black text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.2em] ml-2" data-v-15856f77>Desde (Fecha)</label>`);
			_push(ssrRenderComponent(DatePicker_default, {
				modelValue: form.value.date_from,
				"onUpdate:modelValue": ($event) => form.value.date_from = $event,
				icon: "event_available",
				class: "!h-12 !bg-white dark:!bg-white/[0.03] !rounded-2xl !border-outline-variant/10 dark:!border-white/5 dark:!text-white/80"
			}, null, _parent));
			_push(`</div><div class="space-y-2" data-v-15856f77><label class="text-[9px] font-black text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.2em] ml-2" data-v-15856f77>Hasta (Fecha)</label>`);
			_push(ssrRenderComponent(DatePicker_default, {
				modelValue: form.value.date_to,
				"onUpdate:modelValue": ($event) => form.value.date_to = $event,
				icon: "event_busy",
				class: "!h-12 !bg-white dark:!bg-white/[0.03] !rounded-2xl !border-outline-variant/10 dark:!border-white/5 dark:!text-white/80"
			}, null, _parent));
			_push(`</div></div></div><div class="flex flex-wrap items-center gap-2 pt-6 border-t border-outline-variant/5 dark:border-white/5" data-v-15856f77><span class="text-[9px] font-black text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.3em] mr-4" data-v-15856f77>Filtro por Categoría:</span><button class="${ssrRenderClass([form.value.feature === "" ? "bg-primary text-white shadow-xl shadow-primary/20" : "bg-white dark:bg-white/[0.03] text-on-surface-variant/60 dark:text-white/40 border border-outline-variant/10 dark:border-white/5 hover:bg-surface-container dark:hover:bg-white/10 hover:text-primary", "px-5 h-8 rounded-xl text-[9px] font-black uppercase tracking-widest transition-all"])}" data-v-15856f77> Universal </button><!--[-->`);
			ssrRenderList(__props.features, (feature) => {
				_push(`<button class="${ssrRenderClass([form.value.feature === feature.value ? "bg-primary text-white shadow-xl shadow-primary/20 font-black" : "bg-white dark:bg-white/[0.03] text-on-surface-variant/60 dark:text-white/40 border border-outline-variant/10 dark:border-white/5 hover:bg-surface-container dark:hover:bg-white/10 hover:text-primary", "px-5 h-8 rounded-xl text-[9px] font-black uppercase tracking-widest transition-all flex items-center gap-2"])}" data-v-15856f77><span class="material-symbols-rounded text-[14px]" data-v-15856f77>${ssrInterpolate(getFeatureConfig(feature.value).icon)}</span> ${ssrInterpolate(feature.label)}</button>`);
			});
			_push(`<!--]--></div></div><div class="bg-white dark:bg-[#0b0e14] border border-outline-variant/10 dark:border-white/5 rounded-[3rem] overflow-hidden shadow-2xl relative" data-v-15856f77><div class="absolute left-[5rem] top-0 bottom-0 w-px bg-gradient-to-b from-transparent via-outline-variant/10 dark:via-white/5 to-transparent hidden md:block" data-v-15856f77></div><div class="divide-y divide-outline-variant/5 dark:divide-white/[0.02]" data-v-15856f77><!--[-->`);
			ssrRenderList(__props.logs.data, (log) => {
				_push(`<div class="group flex flex-col md:flex-row md:items-center gap-10 p-10 transition-all hover:bg-primary/[0.008] dark:hover:bg-primary/[0.03] relative" data-v-15856f77><div class="relative md:w-36 flex flex-col md:pl-16" data-v-15856f77><div class="hidden md:block absolute left-[1.38rem] top-[0.6rem] w-3 h-3 rounded-full bg-white dark:bg-[#0b0e14] border-2 border-outline-variant dark:border-white/10 group-hover:border-primary transition-all z-10" data-v-15856f77></div><p class="text-[10px] font-black text-primary uppercase tracking-tighter leading-none italic" data-v-15856f77>${ssrInterpolate(log.used_at_human)}</p><p class="text-[8px] font-bold text-on-surface-variant/30 dark:text-white/10 uppercase mt-2 tracking-widest" data-v-15856f77>${ssrInterpolate(new Date(log.used_at).toLocaleTimeString([], {
					hour: "2-digit",
					minute: "2-digit"
				}))}</p></div><div class="md:w-60 flex items-center gap-5" data-v-15856f77><div class="${ssrRenderClass([[getFeatureConfig(log.feature).color, getFeatureConfig(log.feature).border], "w-12 h-12 rounded-2xl flex items-center justify-center shrink-0 border border-outline-variant/10 dark:border-white/5 transition-all duration-500 group-hover:bg-primary group-hover:text-white"])}" data-v-15856f77><span class="material-symbols-rounded text-xl leading-none font-variation-settings-fill" data-v-15856f77>${ssrInterpolate(getFeatureConfig(log.feature).icon)}</span></div><div class="flex flex-col" data-v-15856f77><span class="text-[8px] font-black text-on-surface-variant/20 dark:text-white/10 uppercase tracking-[0.3em] leading-none mb-2" data-v-15856f77>${ssrInterpolate(log.feature)}</span><span class="text-[12px] font-black text-on-surface dark:text-white uppercase leading-none tracking-tight group-hover:text-primary transition-colors" data-v-15856f77>${ssrInterpolate(log.action ? log.action.replace(/_/g, " ") : "ACTO_CORE")}</span></div></div><div class="flex-1 flex items-center gap-5 border-l border-outline-variant/10 dark:border-white/5 pl-10" data-v-15856f77><img${ssrRenderAttr("src", log.user?.avatar)} class="w-10 h-10 rounded-[1.2rem] object-cover border border-outline-variant/10 dark:border-white/10 shadow-sm" data-v-15856f77><div class="flex flex-col" data-v-15856f77><div class="flex items-center gap-2" data-v-15856f77><span class="text-xs font-black text-on-surface dark:text-white uppercase tracking-tight" data-v-15856f77>${ssrInterpolate(log.user?.name || "Kernel")}</span>`);
				if (log.copropiedad) _push(ssrRenderComponent(Badge_default, {
					variant: "neutral",
					class: "!px-3 !py-0.5 !text-[8.5px] !font-black !bg-primary/5 !text-primary !border-primary/10 tracking-widest uppercase"
				}, {
					default: withCtx((_, _push, _parent, _scopeId) => {
						if (_push) _push(`${ssrInterpolate(log.copropiedad.nombre)}`);
						else return [createTextVNode(toDisplayString(log.copropiedad.nombre), 1)];
					}),
					_: 2
				}, _parent));
				else _push(`<!---->`);
				_push(`</div><p class="text-[9px] font-bold text-on-surface-variant/30 dark:text-white/20 uppercase tracking-[0.2em] mt-1" data-v-15856f77>${ssrInterpolate(log.user?.email || "AUTH_DRIVEN")}</p></div></div><div class="md:w-32 flex justify-end" data-v-15856f77>`);
				_push(ssrRenderComponent(Tooltip_default, { text: "Inspecionar Payload" }, {
					default: withCtx((_, _push, _parent, _scopeId) => {
						if (_push) _push(`<button class="w-10 h-10 rounded-xl bg-surface-container dark:bg-white/5 border border-outline-variant/10 dark:border-white/5 flex items-center justify-center text-on-surface-variant dark:text-white/40 hover:bg-primary dark:hover:bg-primary hover:text-white dark:hover:text-white transition-all shadow-sm active:scale-95" data-v-15856f77${_scopeId}><span class="material-symbols-rounded text-lg font-variation-settings-fill" data-v-15856f77${_scopeId}>terminal</span></button>`);
						else return [createVNode("button", {
							onClick: ($event) => viewDetail(log),
							class: "w-10 h-10 rounded-xl bg-surface-container dark:bg-white/5 border border-outline-variant/10 dark:border-white/5 flex items-center justify-center text-on-surface-variant dark:text-white/40 hover:bg-primary dark:hover:bg-primary hover:text-white dark:hover:text-white transition-all shadow-sm active:scale-95"
						}, [createVNode("span", { class: "material-symbols-rounded text-lg font-variation-settings-fill" }, "terminal")], 8, ["onClick"])];
					}),
					_: 2
				}, _parent));
				_push(`</div></div>`);
			});
			_push(`<!--]--></div><div class="px-10 py-12 bg-surface-container/5 dark:bg-white/[0.01] border-t border-outline-variant/5 dark:border-white/5 flex justify-center" data-v-15856f77>`);
			_push(ssrRenderComponent(Pagination_default, { links: __props.logs.links }, null, _parent));
			_push(`</div></div>`);
			_push(ssrRenderComponent(Modal_default, {
				show: showDetailModal.value,
				onClose: ($event) => showDetailModal.value = false,
				"max-width": "2xl"
			}, {
				default: withCtx((_, _push, _parent, _scopeId) => {
					if (_push) {
						_push(`<div class="p-10 space-y-10 bg-[#0b0e14] text-white" data-v-15856f77${_scopeId}><header class="flex items-start justify-between border-b border-white/5 pb-8" data-v-15856f77${_scopeId}><div class="flex items-center gap-6" data-v-15856f77${_scopeId}><div class="w-16 h-16 rounded-[1.8rem] flex items-center justify-center text-white bg-primary shadow-2xl shadow-primary/40 rotate-12" data-v-15856f77${_scopeId}><span class="material-symbols-rounded text-3xl" data-v-15856f77${_scopeId}>terminal</span></div><div data-v-15856f77${_scopeId}><h3 class="text-3xl font-black tracking-tighter uppercase leading-none italic" data-v-15856f77${_scopeId}>Análisis <span class="text-primary font-black" data-v-15856f77${_scopeId}>Forense</span></h3><p class="text-[10px] font-bold text-white/40 uppercase tracking-[0.3em] mt-2" data-v-15856f77${_scopeId}>Detección de rastro técnico del núcleo</p></div></div><button class="text-white/20 hover:text-white transition-colors" data-v-15856f77${_scopeId}><span class="material-symbols-rounded text-3xl" data-v-15856f77${_scopeId}>close</span></button></header>`);
						if (activeLog.value) _push(`<div class="grid grid-cols-1 md:grid-cols-2 gap-10" data-v-15856f77${_scopeId}><div class="space-y-6" data-v-15856f77${_scopeId}><h5 class="text-[11px] font-black text-primary uppercase tracking-[0.3em]" data-v-15856f77${_scopeId}>Metadata Primaria</h5><div class="space-y-4" data-v-15856f77${_scopeId}><div class="flex justify-between items-center py-3 border-b border-white/5 px-2 hover:bg-white/[0.02]" data-v-15856f77${_scopeId}><span class="text-[10px] font-bold text-white/40 uppercase tracking-widest" data-v-15856f77${_scopeId}>ID_EVENT</span><span class="text-xs font-black text-primary font-mono select-all" data-v-15856f77${_scopeId}>#${ssrInterpolate(activeLog.value.id)}</span></div><div class="flex justify-between items-center py-3 border-b border-white/5 px-2 hover:bg-white/[0.02]" data-v-15856f77${_scopeId}><span class="text-[10px] font-bold text-white/40 uppercase tracking-widest" data-v-15856f77${_scopeId}>IP_ORIGEN</span><span class="text-xs font-black text-emerald-400 select-all" data-v-15856f77${_scopeId}>${ssrInterpolate(activeLog.value.metadata?.ip || "SECURE_VPN")}</span></div></div></div><div class="space-y-6" data-v-15856f77${_scopeId}><h5 class="text-[11px] font-black text-primary uppercase tracking-[0.3em]" data-v-15856f77${_scopeId}>Actor Operativo</h5><div class="bg-white/5 rounded-[2rem] p-6 flex flex-col items-center text-center border border-white/5" data-v-15856f77${_scopeId}><img${ssrRenderAttr("src", activeLog.value.user?.avatar)} class="w-16 h-16 rounded-[1.5rem] bg-white/10 border-4 border-white/5 shadow-2xl mb-4" data-v-15856f77${_scopeId}><p class="text-base font-black text-white uppercase tracking-tight leading-none" data-v-15856f77${_scopeId}>${ssrInterpolate(activeLog.value.user?.name || "Kernel")}</p><p class="text-[10px] font-bold text-white/30 uppercase tracking-[0.2em] mt-2 italic" data-v-15856f77${_scopeId}>${ssrInterpolate(activeLog.value.user?.email || "ROOT_ACCESS")}</p></div></div></div>`);
						else _push(`<!---->`);
						if (activeLog.value) _push(`<div class="space-y-6" data-v-15856f77${_scopeId}><h5 class="text-[11px] font-black text-primary uppercase tracking-[0.3em]" data-v-15856f77${_scopeId}>Payload Final</h5><div class="bg-[#05070a] rounded-[2.5rem] p-8 border border-white/5 shadow-inner relative group" data-v-15856f77${_scopeId}><pre class="text-emerald-400/90 font-mono text-[11px] leading-relaxed overflow-x-auto whitespace-pre-wrap max-h-48 custom-scrollbar" data-v-15856f77${_scopeId}>${ssrInterpolate(JSON.stringify(activeLog.value.metadata, null, 4))}
                        </pre></div></div>`);
						else _push(`<!---->`);
						_push(`<div class="flex justify-end pt-4" data-v-15856f77${_scopeId}><button class="px-12 py-4 bg-primary text-white font-black text-[11px] uppercase tracking-[0.3em] rounded-[1.5rem] shadow-2xl shadow-primary/30 hover:scale-105 transition-all outline-none" data-v-15856f77${_scopeId}>CERRAR INSPECCIÓN</button></div></div>`);
					} else return [createVNode("div", { class: "p-10 space-y-10 bg-[#0b0e14] text-white" }, [
						createVNode("header", { class: "flex items-start justify-between border-b border-white/5 pb-8" }, [createVNode("div", { class: "flex items-center gap-6" }, [createVNode("div", { class: "w-16 h-16 rounded-[1.8rem] flex items-center justify-center text-white bg-primary shadow-2xl shadow-primary/40 rotate-12" }, [createVNode("span", { class: "material-symbols-rounded text-3xl" }, "terminal")]), createVNode("div", null, [createVNode("h3", { class: "text-3xl font-black tracking-tighter uppercase leading-none italic" }, [createTextVNode("Análisis "), createVNode("span", { class: "text-primary font-black" }, "Forense")]), createVNode("p", { class: "text-[10px] font-bold text-white/40 uppercase tracking-[0.3em] mt-2" }, "Detección de rastro técnico del núcleo")])]), createVNode("button", {
							onClick: ($event) => showDetailModal.value = false,
							class: "text-white/20 hover:text-white transition-colors"
						}, [createVNode("span", { class: "material-symbols-rounded text-3xl" }, "close")], 8, ["onClick"])]),
						activeLog.value ? (openBlock(), createBlock("div", {
							key: 0,
							class: "grid grid-cols-1 md:grid-cols-2 gap-10"
						}, [createVNode("div", { class: "space-y-6" }, [createVNode("h5", { class: "text-[11px] font-black text-primary uppercase tracking-[0.3em]" }, "Metadata Primaria"), createVNode("div", { class: "space-y-4" }, [createVNode("div", { class: "flex justify-between items-center py-3 border-b border-white/5 px-2 hover:bg-white/[0.02]" }, [createVNode("span", { class: "text-[10px] font-bold text-white/40 uppercase tracking-widest" }, "ID_EVENT"), createVNode("span", { class: "text-xs font-black text-primary font-mono select-all" }, "#" + toDisplayString(activeLog.value.id), 1)]), createVNode("div", { class: "flex justify-between items-center py-3 border-b border-white/5 px-2 hover:bg-white/[0.02]" }, [createVNode("span", { class: "text-[10px] font-bold text-white/40 uppercase tracking-widest" }, "IP_ORIGEN"), createVNode("span", { class: "text-xs font-black text-emerald-400 select-all" }, toDisplayString(activeLog.value.metadata?.ip || "SECURE_VPN"), 1)])])]), createVNode("div", { class: "space-y-6" }, [createVNode("h5", { class: "text-[11px] font-black text-primary uppercase tracking-[0.3em]" }, "Actor Operativo"), createVNode("div", { class: "bg-white/5 rounded-[2rem] p-6 flex flex-col items-center text-center border border-white/5" }, [
							createVNode("img", {
								src: activeLog.value.user?.avatar,
								class: "w-16 h-16 rounded-[1.5rem] bg-white/10 border-4 border-white/5 shadow-2xl mb-4"
							}, null, 8, ["src"]),
							createVNode("p", { class: "text-base font-black text-white uppercase tracking-tight leading-none" }, toDisplayString(activeLog.value.user?.name || "Kernel"), 1),
							createVNode("p", { class: "text-[10px] font-bold text-white/30 uppercase tracking-[0.2em] mt-2 italic" }, toDisplayString(activeLog.value.user?.email || "ROOT_ACCESS"), 1)
						])])])) : createCommentVNode("", true),
						activeLog.value ? (openBlock(), createBlock("div", {
							key: 1,
							class: "space-y-6"
						}, [createVNode("h5", { class: "text-[11px] font-black text-primary uppercase tracking-[0.3em]" }, "Payload Final"), createVNode("div", { class: "bg-[#05070a] rounded-[2.5rem] p-8 border border-white/5 shadow-inner relative group" }, [createVNode("pre", { class: "text-emerald-400/90 font-mono text-[11px] leading-relaxed overflow-x-auto whitespace-pre-wrap max-h-48 custom-scrollbar" }, "" + toDisplayString(JSON.stringify(activeLog.value.metadata, null, 4)) + "\n                        ", 1)])])) : createCommentVNode("", true),
						createVNode("div", { class: "flex justify-end pt-4" }, [createVNode("button", {
							onClick: ($event) => showDetailModal.value = false,
							class: "px-12 py-4 bg-primary text-white font-black text-[11px] uppercase tracking-[0.3em] rounded-[1.5rem] shadow-2xl shadow-primary/30 hover:scale-105 transition-all outline-none"
						}, "CERRAR INSPECCIÓN", 8, ["onClick"])])
					])];
				}),
				_: 1
			}, _parent));
			_push(`</div><!--]-->`);
		};
	}
});
//#endregion
//#region resources/js/Pages/SuperAdmin/Audit/Index.vue
var _sfc_setup = Index_vue_vue_type_script_setup_true_lang_default.setup;
Index_vue_vue_type_script_setup_true_lang_default.setup = (props, ctx) => {
	const ssrContext = useSSRContext();
	(ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/SuperAdmin/Audit/Index.vue");
	return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
var Index_default = /* @__PURE__ */ _plugin_vue_export_helper_default(Index_vue_vue_type_script_setup_true_lang_default, [["__scopeId", "data-v-15856f77"]]);
//#endregion
export { Index_default as default };

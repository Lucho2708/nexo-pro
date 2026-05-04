import { t as StatCard_default } from "./StatCard-B3MO01gK.js";
import { t as _plugin_vue_export_helper_default } from "./_plugin-vue_export-helper-BjJHhBxR.js";
import { t as AuthenticatedLayout_default } from "./AuthenticatedLayout-BYv1HA3l.js";
import { t as Card_default } from "./Card-C2-I3_la.js";
import { t as Button_default } from "./Button-5V-KYwxi.js";
import { t as Badge_default } from "./Badge-DWJ2qZRx.js";
import { t as useToast } from "./useToast-Dcf8ak3V.js";
import { Fragment, createBlock, createTextVNode, createVNode, defineAsyncComponent, defineComponent, openBlock, renderList, toDisplayString, unref, useSSRContext, withCtx } from "vue";
import { Head, router } from "@inertiajs/vue3";
import { ssrInterpolate, ssrRenderClass, ssrRenderComponent, ssrRenderList, ssrRenderStyle } from "vue/server-renderer";
//#region resources/js/Pages/SuperAdmin/Dashboard.vue?vue&type=script&setup=true&lang.ts
var Dashboard_vue_vue_type_script_setup_true_lang_default = /* @__PURE__ */ defineComponent({
	layout: AuthenticatedLayout_default,
	__name: "Dashboard",
	__ssrInlineRender: true,
	props: { metrics: {} },
	setup(__props) {
		const VueApexCharts = defineAsyncComponent(() => import("vue3-apexcharts"));
		const props = __props;
		const toast = useToast();
		const chartOptions = {
			chart: {
				type: "area",
				height: 350,
				toolbar: { show: false },
				animations: {
					enabled: true,
					easing: "easeinout",
					speed: 800
				},
				background: "transparent"
			},
			colors: [
				"#00173c",
				"#3b82f6",
				"#ef4444",
				"#f59e0b"
			],
			dataLabels: { enabled: false },
			stroke: {
				curve: "smooth",
				width: [
					3,
					2,
					2,
					1
				],
				dashArray: [
					0,
					5,
					0,
					0
				]
			},
			fill: {
				type: "gradient",
				gradient: {
					shadeIntensity: 1,
					opacityFrom: .45,
					opacityTo: .05,
					stops: [
						20,
						100,
						100,
						100
					]
				}
			},
			grid: { show: false },
			xaxis: {
				categories: props.metrics.telemetry.map((t) => t.time),
				labels: { show: false },
				axisBorder: { show: false },
				axisTicks: { show: false }
			},
			yaxis: { show: false },
			tooltip: {
				theme: "dark",
				x: { show: true },
				y: { formatter: (val) => val.toFixed(0) }
			},
			legend: { show: false }
		};
		const chartSeries = [
			{
				name: "Latencia (ms)",
				data: props.metrics.telemetry.map((t) => t.latency)
			},
			{
				name: "Carga (Req/s)",
				data: props.metrics.telemetry.map((t) => t.requests / 10)
			},
			{
				name: "Críticos (500)",
				data: props.metrics.telemetry.map((t) => t.criticals)
			},
			{
				name: "Controlados",
				data: props.metrics.telemetry.map((t) => t.controlled)
			}
		];
		const featureIcons = {
			dashboard: {
				icon: "monitoring",
				color: "text-blue-500 bg-blue-500/10"
			},
			cartera: {
				icon: "account_balance_wallet",
				color: "text-emerald-500 bg-emerald-500/10"
			},
			pqrs: {
				icon: "forum",
				color: "text-pink-500 bg-pink-500/10"
			},
			reservas: {
				icon: "calendar_today",
				color: "text-amber-500 bg-amber-500/10"
			},
			pagos: {
				icon: "payments",
				color: "text-indigo-500 bg-indigo-500/10"
			}
		};
		return (_ctx, _push, _parent, _attrs) => {
			_push(`<!--[-->`);
			_push(ssrRenderComponent(unref(Head), { title: "Mando Central — NEXO-PRO" }, null, _parent));
			_push(`<div class="space-y-10 pb-20 animate-in fade-in duration-700" data-v-e7378df9><div class="flex flex-col md:flex-row md:items-end justify-between gap-6 px-1" data-v-e7378df9><div data-v-e7378df9><h2 class="text-4xl font-black text-primary tracking-tighter uppercase leading-tight" data-v-e7378df9>Mando Central</h2><div class="flex items-center gap-3 mt-2" data-v-e7378df9><div class="flex items-center gap-1.5 px-3 py-1 bg-emerald-500/10 rounded-full" data-v-e7378df9><div class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse" data-v-e7378df9></div><span class="text-[9px] font-black text-emerald-600 uppercase tracking-widest" data-v-e7378df9>Plataforma Normal</span></div><p class="text-[10px] font-bold text-on-surface-variant/40 uppercase tracking-[0.2em]" data-v-e7378df9>Inteligencia y monitoreo global</p></div></div><div class="flex items-center gap-3" data-v-e7378df9>`);
			_push(ssrRenderComponent(Button_default, {
				variant: "ghost",
				icon: "history",
				onClick: ($event) => unref(router).get(_ctx.route("superadmin.audit"))
			}, {
				default: withCtx((_, _push, _parent, _scopeId) => {
					if (_push) _push(`Auditoría`);
					else return [createTextVNode("Auditoría")];
				}),
				_: 1
			}, _parent));
			_push(ssrRenderComponent(Button_default, {
				variant: "primary",
				icon: "add_business",
				href: _ctx.route("register"),
				class: "shadow-brand/25 shadow-xl"
			}, {
				default: withCtx((_, _push, _parent, _scopeId) => {
					if (_push) _push(`Nuevo Conjunto`);
					else return [createTextVNode("Nuevo Conjunto")];
				}),
				_: 1
			}, _parent));
			_push(`</div></div><section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6" data-v-e7378df9>`);
			_push(ssrRenderComponent(StatCard_default, {
				label: "Usuarios Activos 24h",
				value: __props.metrics.active_24h.toString(),
				subtext: "Impacto real del día",
				icon: "bolt",
				"color-class": "bg-brand-gradient text-white"
			}, null, _parent));
			_push(ssrRenderComponent(StatCard_default, {
				label: "Cuentas Totales",
				value: __props.metrics.total_users.toString(),
				trend: `+${__props.metrics.user_growth}%`,
				"trend-up": __props.metrics.user_growth >= 0,
				subtext: "Crecimiento neto",
				icon: "group"
			}, null, _parent));
			_push(ssrRenderComponent(StatCard_default, {
				label: "Copropiedades",
				value: __props.metrics.total_conjuntos.toString(),
				subtext: "Licencias activas",
				icon: "business",
				"color-class": "bg-secondary text-white"
			}, null, _parent));
			_push(`<div class="bg-surface-container-highest/50 backdrop-blur-md p-8 rounded-[2.5rem] border border-outline-variant/10 flex flex-col justify-between group overflow-hidden relative shadow-sm" data-v-e7378df9><div class="absolute -right-4 -top-4 w-20 h-20 bg-primary/5 rounded-full group-hover:scale-150 transition-transform duration-700" data-v-e7378df9></div><div class="relative z-10 space-y-4" data-v-e7378df9><p class="text-[9px] font-black uppercase tracking-[0.2em] text-on-surface-variant/40 leading-none" data-v-e7378df9>Salud de Red</p><div class="grid grid-cols-2 gap-4 mt-2" data-v-e7378df9><div data-v-e7378df9><p class="text-[10px] font-black text-primary/40 uppercase" data-v-e7378df9>Uptime</p><p class="text-sm font-black text-primary tracking-tight" data-v-e7378df9>${ssrInterpolate(__props.metrics.system_health.uptime)}</p></div><div data-v-e7378df9><p class="text-[10px] font-black text-primary/40 uppercase" data-v-e7378df9>Ping</p><p class="text-sm font-black text-primary tracking-tight" data-v-e7378df9>${ssrInterpolate(__props.metrics.system_health.latency)}</p></div></div></div></div></section><div class="bg-surface-container-low border border-outline-variant/10 rounded-[3rem] p-10 relative overflow-hidden group shadow-sm" data-v-e7378df9><div class="flex flex-col md:flex-row md:items-center justify-between mb-8 relative z-10 gap-6" data-v-e7378df9><div data-v-e7378df9><h3 class="text-lg font-black text-primary uppercase tracking-tighter" data-v-e7378df9>Performance de Infraestructura</h3><p class="text-[10px] font-bold text-on-surface-variant/40 uppercase tracking-[0.2em] mt-1" data-v-e7378df9>Telemetría de las últimas 12 horas</p></div><div class="flex flex-wrap items-center gap-x-6 gap-y-2" data-v-e7378df9><div class="flex items-center gap-2" data-v-e7378df9><div class="w-2 h-2 rounded bg-[#00173c]" data-v-e7378df9></div><span class="text-[9px] font-black text-on-surface-variant uppercase tracking-widest" data-v-e7378df9>Latencia</span></div><div class="flex items-center gap-2" data-v-e7378df9><div class="w-2 h-2 rounded border border-dashed border-blue-500" data-v-e7378df9></div><span class="text-[9px] font-black text-on-surface-variant uppercase tracking-widest" data-v-e7378df9>Carga</span></div><div class="flex items-center gap-2" data-v-e7378df9><div class="w-2 h-2 rounded bg-error shadow-[0_0_8px_rgba(239,68,68,0.4)]" data-v-e7378df9></div><span class="text-[9px] font-black text-error uppercase tracking-widest" data-v-e7378df9>Críticos</span></div><div class="flex items-center gap-2" data-v-e7378df9><div class="w-2 h-2 rounded bg-amber-500" data-v-e7378df9></div><span class="text-[9px] font-black text-amber-600 uppercase tracking-widest" data-v-e7378df9>Controlados</span></div></div></div><div class="h-72 relative z-10" data-v-e7378df9>`);
			_push(ssrRenderComponent(unref(VueApexCharts), {
				width: "100%",
				height: "100%",
				options: chartOptions,
				series: chartSeries
			}, null, _parent));
			_push(`</div></div><div class="grid grid-cols-1 lg:grid-cols-12 gap-8" data-v-e7378df9><div class="lg:col-span-8 space-y-8" data-v-e7378df9><div class="grid md:grid-cols-4 gap-4" data-v-e7378df9><!--[-->`);
			ssrRenderList(__props.metrics.license_stats, (val, key) => {
				_push(`<div class="bg-surface-container-low p-6 rounded-3xl border border-outline-variant/5 flex flex-col gap-1 transition-all hover:border-primary/20 shadow-sm" data-v-e7378df9><p class="text-[9px] font-black text-on-surface-variant/40 uppercase tracking-widest" data-v-e7378df9>${ssrInterpolate(key === "expiring" ? "Vencimientos" : key)}</p><div class="flex items-end justify-between" data-v-e7378df9><p class="text-2xl font-black text-primary leading-none" data-v-e7378df9>${ssrInterpolate(val)}</p><div class="${ssrRenderClass(["w-2 h-2 rounded-full", key === "active" ? "bg-emerald-500" : key === "expiring" ? "bg-amber-500 animate-pulse" : key === "suspended" ? "bg-error" : "bg-primary"])}" data-v-e7378df9></div></div></div>`);
			});
			_push(`<!--]--></div>`);
			_push(ssrRenderComponent(Card_default, {
				title: "Auditoría de Actividad",
				subtitle: "Registro transaccional de la plataforma",
				icon: "list_alt"
			}, {
				header: withCtx((_, _push, _parent, _scopeId) => {
					if (_push) _push(ssrRenderComponent(Button_default, {
						variant: "ghost",
						size: "sm",
						onClick: ($event) => unref(router).get(_ctx.route("superadmin.audit")),
						class: "!text-[10px] font-black uppercase"
					}, {
						default: withCtx((_, _push, _parent, _scopeId) => {
							if (_push) _push(`Ver Bitácora`);
							else return [createTextVNode("Ver Bitácora")];
						}),
						_: 1
					}, _parent, _scopeId));
					else return [createVNode(Button_default, {
						variant: "ghost",
						size: "sm",
						onClick: ($event) => unref(router).get(_ctx.route("superadmin.audit")),
						class: "!text-[10px] font-black uppercase"
					}, {
						default: withCtx(() => [createTextVNode("Ver Bitácora")]),
						_: 1
					}, 8, ["onClick"])];
				}),
				default: withCtx((_, _push, _parent, _scopeId) => {
					if (_push) {
						_push(`<div class="mt-4 overflow-hidden border border-outline-variant/5 rounded-2xl" data-v-e7378df9${_scopeId}><!--[-->`);
						ssrRenderList(__props.metrics.recent_audit, (log, idx) => {
							_push(`<div class="flex items-center gap-5 p-5 transition-colors border-b border-outline-variant/5 last:border-none hover:bg-primary/[0.02]" data-v-e7378df9${_scopeId}><div class="${ssrRenderClass([featureIcons[log.feature]?.color || "bg-surface-container-high text-on-surface-variant", "w-11 h-11 rounded-2xl flex items-center justify-center shrink-0 shadow-sm"])}" data-v-e7378df9${_scopeId}><span class="material-symbols-rounded text-xl" data-v-e7378df9${_scopeId}>${ssrInterpolate(featureIcons[log.feature]?.icon || "rocket_launch")}</span></div><div class="flex-1 min-w-0" data-v-e7378df9${_scopeId}><div class="flex items-center gap-2" data-v-e7378df9${_scopeId}><span class="text-xs font-black text-primary uppercase tracking-tight" data-v-e7378df9${_scopeId}>${ssrInterpolate(log.user?.name)}</span><span class="text-[9px] font-bold text-on-surface-variant/30 uppercase tracking-tighter" data-v-e7378df9${_scopeId}>en</span><span class="text-[10px] font-black text-on-surface uppercase" data-v-e7378df9${_scopeId}>${ssrInterpolate(log.copropiedad?.nombre)}</span></div><p class="text-[11px] font-medium text-on-surface-variant mt-0.5" data-v-e7378df9${_scopeId}>Ejecutó acción en módulo <span class="uppercase font-bold" data-v-e7378df9${_scopeId}>${ssrInterpolate(log.feature)}</span></p></div><div class="text-right shrink-0" data-v-e7378df9${_scopeId}><p class="text-[9px] font-black text-on-surface-variant/40 uppercase tracking-widest" data-v-e7378df9${_scopeId}>${ssrInterpolate(log.used_at_human || "Ahora")}</p></div></div>`);
						});
						_push(`<!--]--></div>`);
					} else return [createVNode("div", { class: "mt-4 overflow-hidden border border-outline-variant/5 rounded-2xl" }, [(openBlock(true), createBlock(Fragment, null, renderList(__props.metrics.recent_audit, (log, idx) => {
						return openBlock(), createBlock("div", {
							key: log.id,
							class: "flex items-center gap-5 p-5 transition-colors border-b border-outline-variant/5 last:border-none hover:bg-primary/[0.02]"
						}, [
							createVNode("div", { class: ["w-11 h-11 rounded-2xl flex items-center justify-center shrink-0 shadow-sm", featureIcons[log.feature]?.color || "bg-surface-container-high text-on-surface-variant"] }, [createVNode("span", { class: "material-symbols-rounded text-xl" }, toDisplayString(featureIcons[log.feature]?.icon || "rocket_launch"), 1)], 2),
							createVNode("div", { class: "flex-1 min-w-0" }, [createVNode("div", { class: "flex items-center gap-2" }, [
								createVNode("span", { class: "text-xs font-black text-primary uppercase tracking-tight" }, toDisplayString(log.user?.name), 1),
								createVNode("span", { class: "text-[9px] font-bold text-on-surface-variant/30 uppercase tracking-tighter" }, "en"),
								createVNode("span", { class: "text-[10px] font-black text-on-surface uppercase" }, toDisplayString(log.copropiedad?.nombre), 1)
							]), createVNode("p", { class: "text-[11px] font-medium text-on-surface-variant mt-0.5" }, [createTextVNode("Ejecutó acción en módulo "), createVNode("span", { class: "uppercase font-bold" }, toDisplayString(log.feature), 1)])]),
							createVNode("div", { class: "text-right shrink-0" }, [createVNode("p", { class: "text-[9px] font-black text-on-surface-variant/40 uppercase tracking-widest" }, toDisplayString(log.used_at_human || "Ahora"), 1)])
						]);
					}), 128))])];
				}),
				_: 1
			}, _parent));
			_push(`</div><div class="lg:col-span-4 space-y-8" data-v-e7378df9>`);
			_push(ssrRenderComponent(Card_default, {
				title: "Impacto Técnico",
				subtitle: "Demanda de recursos por módulo",
				icon: "analytics"
			}, {
				default: withCtx((_, _push, _parent, _scopeId) => {
					if (_push) {
						_push(`<div class="space-y-6 mt-6" data-v-e7378df9${_scopeId}><!--[-->`);
						ssrRenderList(__props.metrics.module_ranking, (item, index) => {
							_push(`<div class="space-y-2" data-v-e7378df9${_scopeId}><div class="flex justify-between items-center px-1" data-v-e7378df9${_scopeId}><div class="flex items-center gap-3" data-v-e7378df9${_scopeId}><span class="material-symbols-rounded text-[16px] text-primary" data-v-e7378df9${_scopeId}>${ssrInterpolate(featureIcons[item.feature]?.icon || "stars")}</span><p class="text-[10px] font-black text-on-surface uppercase tracking-widest" data-v-e7378df9${_scopeId}>${ssrInterpolate(item.feature)}</p></div>`);
							_push(ssrRenderComponent(Badge_default, {
								variant: "secondary",
								class: "!text-[9px] font-black tracking-tighter"
							}, {
								default: withCtx((_, _push, _parent, _scopeId) => {
									if (_push) _push(`${ssrInterpolate(item.total)} <span class="ml-1 opacity-50" data-v-e7378df9${_scopeId}>OPS</span>`);
									else return [createTextVNode(toDisplayString(item.total) + " ", 1), createVNode("span", { class: "ml-1 opacity-50" }, "OPS")];
								}),
								_: 2
							}, _parent, _scopeId));
							_push(`</div><div class="h-2 w-full bg-surface-container-low rounded-full overflow-hidden p-[2px] border border-outline-variant/10 shadow-inner" data-v-e7378df9${_scopeId}><div class="h-full bg-brand-gradient rounded-full transition-all duration-1000" style="${ssrRenderStyle(`width: ${Math.min(100, item.total / (__props.metrics.module_ranking[0]?.total || 1) * 100)}%`)}" data-v-e7378df9${_scopeId}></div></div></div>`);
						});
						_push(`<!--]--></div>`);
					} else return [createVNode("div", { class: "space-y-6 mt-6" }, [(openBlock(true), createBlock(Fragment, null, renderList(__props.metrics.module_ranking, (item, index) => {
						return openBlock(), createBlock("div", {
							key: item.feature,
							class: "space-y-2"
						}, [createVNode("div", { class: "flex justify-between items-center px-1" }, [createVNode("div", { class: "flex items-center gap-3" }, [createVNode("span", { class: "material-symbols-rounded text-[16px] text-primary" }, toDisplayString(featureIcons[item.feature]?.icon || "stars"), 1), createVNode("p", { class: "text-[10px] font-black text-on-surface uppercase tracking-widest" }, toDisplayString(item.feature), 1)]), createVNode(Badge_default, {
							variant: "secondary",
							class: "!text-[9px] font-black tracking-tighter"
						}, {
							default: withCtx(() => [createTextVNode(toDisplayString(item.total) + " ", 1), createVNode("span", { class: "ml-1 opacity-50" }, "OPS")]),
							_: 2
						}, 1024)]), createVNode("div", { class: "h-2 w-full bg-surface-container-low rounded-full overflow-hidden p-[2px] border border-outline-variant/10 shadow-inner" }, [createVNode("div", {
							class: "h-full bg-brand-gradient rounded-full transition-all duration-1000",
							style: `width: ${Math.min(100, item.total / (__props.metrics.module_ranking[0]?.total || 1) * 100)}%`
						}, null, 4)])]);
					}), 128))])];
				}),
				_: 1
			}, _parent));
			_push(`<div class="premium-elevated rounded-[2.5rem] p-8 border border-primary/5 bg-gradient-to-br from-surface-container-low to-surface-container-highest shadow-sm" data-v-e7378df9><h4 class="text-xs font-black text-primary uppercase tracking-[0.2em] mb-6 flex items-center gap-2" data-v-e7378df9><span class="material-symbols-rounded text-sm" data-v-e7378df9>terminal</span> Estadísticas de Servidor </h4><div class="space-y-5" data-v-e7378df9><div class="flex items-center justify-between" data-v-e7378df9><span class="text-[10px] font-bold text-on-surface-variant/60 uppercase" data-v-e7378df9>Uso de Almacenamiento</span><span class="text-xs font-black text-primary" data-v-e7378df9>${ssrInterpolate(__props.metrics.system_health.storage)}</span></div><div class="h-1.5 w-full bg-primary/5 rounded-full overflow-hidden" data-v-e7378df9><div class="h-full bg-primary rounded-full" style="${ssrRenderStyle(`width: ${__props.metrics.system_health.storage}`)}" data-v-e7378df9></div></div><div class="flex items-center justify-between pt-2" data-v-e7378df9><span class="text-[10px] font-bold text-on-surface-variant/60 uppercase" data-v-e7378df9>Errores de Sistema (24h)</span>`);
			_push(ssrRenderComponent(Badge_default, {
				variant: __props.metrics.system_health.errors === 0 ? "success" : "error",
				class: "!text-[8px]"
			}, {
				default: withCtx((_, _push, _parent, _scopeId) => {
					if (_push) _push(`${ssrInterpolate(__props.metrics.system_health.errors)} DETECTADOS`);
					else return [createTextVNode(toDisplayString(__props.metrics.system_health.errors) + " DETECTADOS", 1)];
				}),
				_: 1
			}, _parent));
			_push(`</div></div><div class="mt-8 grid grid-cols-2 gap-3" data-v-e7378df9>`);
			_push(ssrRenderComponent(Button_default, {
				variant: "outline",
				size: "sm",
				class: "!rounded-2xl !text-[9px] uppercase font-black",
				onClick: ($event) => unref(toast).add("Backup programado", "primary")
			}, {
				default: withCtx((_, _push, _parent, _scopeId) => {
					if (_push) _push(`Backup`);
					else return [createTextVNode("Backup")];
				}),
				_: 1
			}, _parent));
			_push(ssrRenderComponent(Button_default, {
				variant: "outline",
				size: "sm",
				class: "!rounded-2xl !text-[9px] uppercase font-black",
				onClick: ($event) => unref(toast).add("Caché limpia", "success")
			}, {
				default: withCtx((_, _push, _parent, _scopeId) => {
					if (_push) _push(`Clear Cache`);
					else return [createTextVNode("Clear Cache")];
				}),
				_: 1
			}, _parent));
			_push(`</div></div></div></div></div><!--]-->`);
		};
	}
});
//#endregion
//#region resources/js/Pages/SuperAdmin/Dashboard.vue
var _sfc_setup = Dashboard_vue_vue_type_script_setup_true_lang_default.setup;
Dashboard_vue_vue_type_script_setup_true_lang_default.setup = (props, ctx) => {
	const ssrContext = useSSRContext();
	(ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/SuperAdmin/Dashboard.vue");
	return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
var Dashboard_default = /* @__PURE__ */ _plugin_vue_export_helper_default(Dashboard_vue_vue_type_script_setup_true_lang_default, [["__scopeId", "data-v-e7378df9"]]);
//#endregion
export { Dashboard_default as default };

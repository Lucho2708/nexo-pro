import { t as _plugin_vue_export_helper_default } from "./_plugin-vue_export-helper-BjJHhBxR.js";
import { t as useToast } from "./useToast-E_Hdzmc5.js";
import { t as AuthenticatedLayout_default } from "./AuthenticatedLayout-vxulH9w9.js";
import { t as Card_default } from "./Card-D4IwBRMd.js";
import { t as Button_default } from "./Button-C-byZbTR.js";
import { t as Badge_default } from "./Badge-DsYztQFV.js";
import { t as Tooltip_default } from "./Tooltip-IAP-zsdE.js";
import { computed, createTextVNode, createVNode, defineAsyncComponent, defineComponent, resolveComponent, toDisplayString, unref, useSSRContext, withCtx } from "vue";
import { Head } from "@inertiajs/vue3";
import { ssrInterpolate, ssrRenderClass, ssrRenderComponent, ssrRenderList } from "vue/server-renderer";
//#region resources/js/Pages/Dashboard.vue?vue&type=script&setup=true&lang.ts
var Dashboard_vue_vue_type_script_setup_true_lang_default = /* @__PURE__ */ defineComponent({
	layout: AuthenticatedLayout_default,
	__name: "Dashboard",
	__ssrInlineRender: true,
	props: {
		stats: {},
		chartData: {},
		overduePayments: {}
	},
	setup(__props) {
		const StatCard = defineAsyncComponent(() => import("./StatCard-B3MO01gK.js").then((n) => n.n));
		const Table = defineAsyncComponent(() => import("./Table-DmKwoFFU.js").then((n) => n.n));
		const VueApexCharts = defineAsyncComponent(() => import("vue3-apexcharts"));
		const props = __props;
		const toast = useToast();
		const tableColumns = [
			{
				key: "unit",
				label: "ID UNIDAD",
				sortable: true
			},
			{
				key: "owner",
				label: "RESPONSABLE",
				sortable: true
			},
			{
				key: "amount",
				label: "DEUDA TOTAL",
				sortable: true
			},
			{
				key: "last_payment",
				label: "FECHA CORTE",
				sortable: true
			},
			{
				key: "status",
				label: "ESTADO TÉCNICO",
				sortable: true
			},
			{
				key: "actions",
				label: "",
				sortable: false
			}
		];
		const chartOptions = computed(() => ({
			chart: {
				type: "area",
				toolbar: { show: false },
				zoom: { enabled: false },
				fontFamily: "Manrope, sans-serif"
			},
			colors: ["#0061FF", "#FF4E5E"],
			dataLabels: { enabled: false },
			stroke: {
				curve: "smooth",
				width: 4,
				lineCap: "round"
			},
			fill: {
				type: "gradient",
				gradient: {
					shadeIntensity: 1,
					opacityFrom: .35,
					opacityTo: .05,
					stops: [
						0,
						90,
						100
					]
				}
			},
			xaxis: {
				categories: props.chartData.categories,
				axisBorder: { show: false },
				axisTicks: { show: false },
				labels: { style: {
					colors: "#747780",
					fontSize: "9px",
					fontWeight: 800
				} }
			},
			yaxis: { labels: { show: false } },
			grid: {
				borderColor: "rgba(var(--outline-variant), 0.05)",
				strokeDashArray: 6,
				padding: {
					left: 0,
					right: 0
				}
			},
			tooltip: {
				theme: "dark",
				x: { show: true }
			},
			legend: { show: false }
		}));
		const formatCurrency = (value) => {
			return new Intl.NumberFormat("es-CO", {
				style: "currency",
				currency: "COP",
				maximumFractionDigits: 0
			}).format(value);
		};
		const runLiquidation = () => {
			toast.add("Iniciando ciclo de liquidación masiva...", "primary");
			setTimeout(() => toast.add("¡Liquidación completada!", "success"), 2500);
		};
		return (_ctx, _push, _parent, _attrs) => {
			const _component_Dropdown = resolveComponent("Dropdown");
			_push(`<!--[-->`);
			_push(ssrRenderComponent(unref(Head), { title: "Mando Central — NEXO-PRO" }, null, _parent));
			_push(`<div class="space-y-12 pb-32 animate-in fade-in slide-in-from-bottom-4 duration-700" data-v-92b7e945><div class="flex flex-col lg:flex-row lg:items-end justify-between gap-8" data-v-92b7e945><div class="space-y-2" data-v-92b7e945><div class="flex items-center gap-3" data-v-92b7e945><div class="w-1.5 h-6 bg-primary rounded-full" data-v-92b7e945></div><h2 class="text-[10px] font-black text-on-surface-variant/40 uppercase tracking-[0.4em] italic" data-v-92b7e945>Módulo de Administración Operativa</h2></div><h1 class="text-5xl font-black text-on-surface dark:text-white tracking-tighter uppercase italic leading-none" data-v-92b7e945> Centro de <span class="text-primary italic" data-v-92b7e945>Control</span></h1></div><div class="flex flex-wrap items-center gap-3 bg-white dark:bg-white/[0.02] p-2 rounded-[2rem] border border-outline-variant/10 shadow-xl" data-v-92b7e945>`);
			_push(ssrRenderComponent(Button_default, {
				variant: "ghost",
				size: "md",
				icon: "sync",
				class: "!rounded-2xl",
				onClick: ($event) => unref(toast).add("Sincronizando con base de datos...", "primary")
			}, {
				default: withCtx((_, _push, _parent, _scopeId) => {
					if (_push) _push(`Sincronizar`);
					else return [createTextVNode("Sincronizar")];
				}),
				_: 1
			}, _parent));
			_push(ssrRenderComponent(Button_default, {
				variant: "outline",
				size: "md",
				icon: "receipt_long",
				class: "!rounded-2xl",
				href: "#"
			}, {
				default: withCtx((_, _push, _parent, _scopeId) => {
					if (_push) _push(`Reportes`);
					else return [createTextVNode("Reportes")];
				}),
				_: 1
			}, _parent));
			_push(ssrRenderComponent(Button_default, {
				variant: "primary",
				size: "md",
				icon: "auto_awesome",
				class: "!rounded-2xl shadow-lg shadow-primary/20",
				onClick: runLiquidation
			}, {
				default: withCtx((_, _push, _parent, _scopeId) => {
					if (_push) _push(`Liquidación Masiva`);
					else return [createTextVNode("Liquidación Masiva")];
				}),
				_: 1
			}, _parent));
			_push(`</div></div><section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6" data-v-92b7e945><!--[-->`);
			ssrRenderList(__props.stats, (stat, index) => {
				_push(ssrRenderComponent(unref(StatCard), {
					key: index,
					label: stat.label,
					value: stat.value,
					trend: stat.trend,
					"trend-up": stat.trend_up,
					progress: stat.progress,
					"color-class": stat.color,
					class: "!rounded-[2.5rem] border border-outline-variant/5 dark:border-white/5 shadow-2xl dark:bg-[#0b0e14]"
				}, null, _parent));
			});
			_push(`<!--]--></section><div class="grid grid-cols-1 lg:grid-cols-12 gap-8" data-v-92b7e945>`);
			_push(ssrRenderComponent(Card_default, { class: "lg:col-span-8 !p-10 !rounded-[3.5rem] border border-outline-variant/10 dark:border-white/5 shadow-2xl dark:bg-[#0b0e14]" }, {
				default: withCtx((_, _push, _parent, _scopeId) => {
					if (_push) {
						_push(`<div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10" data-v-92b7e945${_scopeId}><div data-v-92b7e945${_scopeId}><h3 class="text-2xl font-black text-on-surface dark:text-white uppercase tracking-tighter italic leading-none" data-v-92b7e945${_scopeId}>Flujo de <span class="text-primary" data-v-92b7e945${_scopeId}>Capital</span></h3><p class="text-[10px] font-bold text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.3em] mt-3 italic" data-v-92b7e945${_scopeId}>Indicadores de recaudo mensual vs facturación</p></div><div class="flex items-center gap-6 bg-surface-container-low dark:bg-white/5 px-6 py-3 rounded-2xl border border-outline-variant/10" data-v-92b7e945${_scopeId}><div class="flex items-center gap-2" data-v-92b7e945${_scopeId}><div class="w-2.5 h-2.5 rounded-full bg-primary shadow-[0_0_10px_rgba(0,97,255,0.5)]" data-v-92b7e945${_scopeId}></div><span class="text-[9px] font-black text-on-surface-variant dark:text-white/40 uppercase tracking-widest" data-v-92b7e945${_scopeId}>Recaudo</span></div><div class="flex items-center gap-2" data-v-92b7e945${_scopeId}><div class="w-2.5 h-2.5 rounded-full bg-error shadow-[0_0_10px_rgba(255,78,94,0.5)]" data-v-92b7e945${_scopeId}></div><span class="text-[9px] font-black text-on-surface-variant dark:text-white/40 uppercase tracking-widest" data-v-92b7e945${_scopeId}>Cartera</span></div></div></div><div class="h-80 -mx-4 group" data-v-92b7e945${_scopeId}>`);
						_push(ssrRenderComponent(unref(VueApexCharts), {
							type: "area",
							height: "100%",
							options: chartOptions.value,
							series: __props.chartData.series
						}, null, _parent, _scopeId));
						_push(`</div>`);
					} else return [createVNode("div", { class: "flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10" }, [createVNode("div", null, [createVNode("h3", { class: "text-2xl font-black text-on-surface dark:text-white uppercase tracking-tighter italic leading-none" }, [createTextVNode("Flujo de "), createVNode("span", { class: "text-primary" }, "Capital")]), createVNode("p", { class: "text-[10px] font-bold text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.3em] mt-3 italic" }, "Indicadores de recaudo mensual vs facturación")]), createVNode("div", { class: "flex items-center gap-6 bg-surface-container-low dark:bg-white/5 px-6 py-3 rounded-2xl border border-outline-variant/10" }, [createVNode("div", { class: "flex items-center gap-2" }, [createVNode("div", { class: "w-2.5 h-2.5 rounded-full bg-primary shadow-[0_0_10px_rgba(0,97,255,0.5)]" }), createVNode("span", { class: "text-[9px] font-black text-on-surface-variant dark:text-white/40 uppercase tracking-widest" }, "Recaudo")]), createVNode("div", { class: "flex items-center gap-2" }, [createVNode("div", { class: "w-2.5 h-2.5 rounded-full bg-error shadow-[0_0_10px_rgba(255,78,94,0.5)]" }), createVNode("span", { class: "text-[9px] font-black text-on-surface-variant dark:text-white/40 uppercase tracking-widest" }, "Cartera")])])]), createVNode("div", { class: "h-80 -mx-4 group" }, [createVNode(unref(VueApexCharts), {
						type: "area",
						height: "100%",
						options: chartOptions.value,
						series: __props.chartData.series
					}, null, 8, ["options", "series"])])];
				}),
				_: 1
			}, _parent));
			_push(`<div class="lg:col-span-4 flex flex-col gap-8" data-v-92b7e945><div class="flex-1 bg-primary p-12 rounded-[3.5rem] relative overflow-hidden flex flex-col justify-between group cursor-pointer shadow-3xl shadow-primary/20 transition-all hover:scale-[1.02] border border-white/10" data-v-92b7e945><img class="absolute inset-0 w-full h-full object-cover opacity-20 group-hover:scale-110 transition-transform duration-[3s]" src="https://images.unsplash.com/photo-1579546929518-9e396f3cc809?q=80&amp;w=2070" data-v-92b7e945><div class="relative z-10 space-y-4" data-v-92b7e945>`);
			_push(ssrRenderComponent(Badge_default, {
				variant: "neutral",
				class: "!bg-white/20 !text-white !border-white/30 backdrop-blur-md !text-[9px] font-black tracking-widest"
			}, {
				default: withCtx((_, _push, _parent, _scopeId) => {
					if (_push) _push(`SISTEMA INTELIGENTE`);
					else return [createTextVNode("SISTEMA INTELIGENTE")];
				}),
				_: 1
			}, _parent));
			_push(`<h4 class="text-white text-3xl font-black tracking-tighter leading-tight italic uppercase" data-v-92b7e945>Optimización de <br data-v-92b7e945> Carteras</h4></div><div class="relative z-10 pt-8 border-t border-white/10 mt-8" data-v-92b7e945><p class="text-white/60 text-[10px] font-bold uppercase tracking-widest leading-relaxed" data-v-92b7e945>El recaudo aumentó un 22% este mes mediante cobros automáticos.</p>`);
			_push(ssrRenderComponent(Button_default, {
				variant: "ghost",
				class: "mt-8 w-full !bg-white/10 !text-white border border-white/20 hover:!bg-white/20 !text-[11px] font-black uppercase italic !rounded-2xl"
			}, {
				default: withCtx((_, _push, _parent, _scopeId) => {
					if (_push) _push(`Dashboard de Cobro`);
					else return [createTextVNode("Dashboard de Cobro")];
				}),
				_: 1
			}, _parent));
			_push(`</div></div></div></div>`);
			_push(ssrRenderComponent(Card_default, {
				title: "Saldos Críticos",
				subtitle: "Monitor de unidades con mora superior a 60 días",
				icon: "warning",
				class: "!p-0 !rounded-[3.5rem] border border-outline-variant/10 dark:border-white/5 shadow-2xl dark:bg-[#0b0e14] overflow-hidden"
			}, {
				header: withCtx((_, _push, _parent, _scopeId) => {
					if (_push) {
						_push(`<div class="p-10 flex flex-col md:flex-row md:items-center justify-between gap-6" data-v-92b7e945${_scopeId}><div data-v-92b7e945${_scopeId}><h3 class="text-2xl font-black text-on-surface dark:text-white uppercase tracking-tighter italic leading-none" data-v-92b7e945${_scopeId}>Monitoreo de <span class="text-error" data-v-92b7e945${_scopeId}>Impagos</span></h3><p class="text-[10px] font-bold text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.3em] mt-3 italic" data-v-92b7e945${_scopeId}>Registro técnico de unidades en estado de mora</p></div>`);
						_push(ssrRenderComponent(Button_default, {
							variant: "ghost",
							size: "sm",
							icon: "description",
							class: "!rounded-xl text-[10px] uppercase font-black tracking-widest bg-error/5 text-error hover:bg-error/10"
						}, {
							default: withCtx((_, _push, _parent, _scopeId) => {
								if (_push) _push(`Generar Cobros Jurídicos`);
								else return [createTextVNode("Generar Cobros Jurídicos")];
							}),
							_: 1
						}, _parent, _scopeId));
						_push(`</div>`);
					} else return [createVNode("div", { class: "p-10 flex flex-col md:flex-row md:items-center justify-between gap-6" }, [createVNode("div", null, [createVNode("h3", { class: "text-2xl font-black text-on-surface dark:text-white uppercase tracking-tighter italic leading-none" }, [createTextVNode("Monitoreo de "), createVNode("span", { class: "text-error" }, "Impagos")]), createVNode("p", { class: "text-[10px] font-bold text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.3em] mt-3 italic" }, "Registro técnico de unidades en estado de mora")]), createVNode(Button_default, {
						variant: "ghost",
						size: "sm",
						icon: "description",
						class: "!rounded-xl text-[10px] uppercase font-black tracking-widest bg-error/5 text-error hover:bg-error/10"
					}, {
						default: withCtx(() => [createTextVNode("Generar Cobros Jurídicos")]),
						_: 1
					})])];
				}),
				default: withCtx((_, _push, _parent, _scopeId) => {
					if (_push) _push(ssrRenderComponent(unref(Table), {
						columns: tableColumns,
						data: __props.overduePayments,
						class: "border-t border-outline-variant/5 dark:border-white/5"
					}, {
						"cell-unit": withCtx(({ row }, _push, _parent, _scopeId) => {
							if (_push) _push(`<span class="text-lg font-black text-primary tracking-tighter italic" data-v-92b7e945${_scopeId}>Lote ${ssrInterpolate(row.unit)}</span>`);
							else return [createVNode("span", { class: "text-lg font-black text-primary tracking-tighter italic" }, "Lote " + toDisplayString(row.unit), 1)];
						}),
						"cell-owner": withCtx(({ row }, _push, _parent, _scopeId) => {
							if (_push) _push(`<div class="flex items-center gap-4 py-2" data-v-92b7e945${_scopeId}><div class="w-12 h-12 rounded-2xl bg-surface-container-high dark:bg-white/5 flex items-center justify-center text-xs font-black text-primary border border-outline-variant/10 shadow-sm relative overflow-hidden group" data-v-92b7e945${_scopeId}><div class="absolute inset-0 bg-primary/10 translate-y-full group-hover:translate-y-0 transition-transform duration-500" data-v-92b7e945${_scopeId}></div><span class="relative z-10" data-v-92b7e945${_scopeId}>${ssrInterpolate(row.initials)}</span></div><div class="flex flex-col" data-v-92b7e945${_scopeId}><span class="text-xs font-black text-on-surface dark:text-white uppercase italic tracking-tighter" data-v-92b7e945${_scopeId}>${ssrInterpolate(row.owner)}</span><span class="text-[9px] font-bold text-on-surface-variant/40 dark:text-white/20 uppercase tracking-widest" data-v-92b7e945${_scopeId}>PROPIETARIO VÁLIDO</span></div></div>`);
							else return [createVNode("div", { class: "flex items-center gap-4 py-2" }, [createVNode("div", { class: "w-12 h-12 rounded-2xl bg-surface-container-high dark:bg-white/5 flex items-center justify-center text-xs font-black text-primary border border-outline-variant/10 shadow-sm relative overflow-hidden group" }, [createVNode("div", { class: "absolute inset-0 bg-primary/10 translate-y-full group-hover:translate-y-0 transition-transform duration-500" }), createVNode("span", { class: "relative z-10" }, toDisplayString(row.initials), 1)]), createVNode("div", { class: "flex flex-col" }, [createVNode("span", { class: "text-xs font-black text-on-surface dark:text-white uppercase italic tracking-tighter" }, toDisplayString(row.owner), 1), createVNode("span", { class: "text-[9px] font-bold text-on-surface-variant/40 dark:text-white/20 uppercase tracking-widest" }, "PROPIETARIO VÁLIDO")])])];
						}),
						"cell-amount": withCtx(({ row }, _push, _parent, _scopeId) => {
							if (_push) _push(`<div class="flex flex-col" data-v-92b7e945${_scopeId}><span class="${ssrRenderClass([row.status_type === "error" ? "text-error" : "text-orange-500", "text-lg font-black tracking-tighter italic"])}" data-v-92b7e945${_scopeId}>${ssrInterpolate(formatCurrency(row.amount))}</span><span class="text-[8px] font-black text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.2em] mt-1 italic" data-v-92b7e945${_scopeId}>SALDO VENCIDO</span></div>`);
							else return [createVNode("div", { class: "flex flex-col" }, [createVNode("span", { class: ["text-lg font-black tracking-tighter italic", row.status_type === "error" ? "text-error" : "text-orange-500"] }, toDisplayString(formatCurrency(row.amount)), 3), createVNode("span", { class: "text-[8px] font-black text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.2em] mt-1 italic" }, "SALDO VENCIDO")])];
						}),
						"cell-status": withCtx(({ row }, _push, _parent, _scopeId) => {
							if (_push) _push(ssrRenderComponent(Badge_default, {
								variant: row.status_type === "error" ? "error" : "warning",
								class: "!px-5 !py-1 !font-black !text-[10px] tracking-widest uppercase italic"
							}, {
								default: withCtx((_, _push, _parent, _scopeId) => {
									if (_push) _push(`${ssrInterpolate(row.status)}`);
									else return [createTextVNode(toDisplayString(row.status), 1)];
								}),
								_: 2
							}, _parent, _scopeId));
							else return [createVNode(Badge_default, {
								variant: row.status_type === "error" ? "error" : "warning",
								class: "!px-5 !py-1 !font-black !text-[10px] tracking-widest uppercase italic"
							}, {
								default: withCtx(() => [createTextVNode(toDisplayString(row.status), 1)]),
								_: 2
							}, 1032, ["variant"])];
						}),
						"cell-actions": withCtx(({ row }, _push, _parent, _scopeId) => {
							if (_push) {
								_push(`<div class="flex justify-end gap-2 pr-6" data-v-92b7e945${_scopeId}>`);
								_push(ssrRenderComponent(Tooltip_default, { text: "Ver Estado de Cuenta" }, {
									default: withCtx((_, _push, _parent, _scopeId) => {
										if (_push) _push(ssrRenderComponent(Button_default, {
											variant: "ghost",
											size: "sm",
											icon: "visibility",
											class: "!w-10 !h-10 !p-0 !rounded-xl",
											onClick: ($event) => unref(toast).add("Cargando hoja de vida...", "primary")
										}, null, _parent, _scopeId));
										else return [createVNode(Button_default, {
											variant: "ghost",
											size: "sm",
											icon: "visibility",
											class: "!w-10 !h-10 !p-0 !rounded-xl",
											onClick: ($event) => unref(toast).add("Cargando hoja de vida...", "primary")
										}, null, 8, ["onClick"])];
									}),
									_: 2
								}, _parent, _scopeId));
								_push(ssrRenderComponent(Tooltip_default, { text: "Notificar Cobro" }, {
									default: withCtx((_, _push, _parent, _scopeId) => {
										if (_push) _push(ssrRenderComponent(Button_default, {
											variant: "ghost",
											size: "sm",
											icon: "mail",
											class: "!w-10 !h-10 !p-0 !rounded-xl text-primary",
											onClick: ($event) => unref(toast).add("Notificación enviada vía Email/SMS", "success")
										}, null, _parent, _scopeId));
										else return [createVNode(Button_default, {
											variant: "ghost",
											size: "sm",
											icon: "mail",
											class: "!w-10 !h-10 !p-0 !rounded-xl text-primary",
											onClick: ($event) => unref(toast).add("Notificación enviada vía Email/SMS", "success")
										}, null, 8, ["onClick"])];
									}),
									_: 2
								}, _parent, _scopeId));
								_push(ssrRenderComponent(_component_Dropdown, {
									label: "",
									icon: "more_horiz",
									variant: "ghost",
									class: "!p-0 !w-10 !h-10 border border-outline-variant/10 !rounded-xl",
									items: [{
										label: "Editar Propietario",
										icon: "manage_accounts",
										action: () => {}
									}, {
										label: "Excluir de mora",
										icon: "verified",
										action: () => {}
									}]
								}, null, _parent, _scopeId));
								_push(`</div>`);
							} else return [createVNode("div", { class: "flex justify-end gap-2 pr-6" }, [
								createVNode(Tooltip_default, { text: "Ver Estado de Cuenta" }, {
									default: withCtx(() => [createVNode(Button_default, {
										variant: "ghost",
										size: "sm",
										icon: "visibility",
										class: "!w-10 !h-10 !p-0 !rounded-xl",
										onClick: ($event) => unref(toast).add("Cargando hoja de vida...", "primary")
									}, null, 8, ["onClick"])]),
									_: 1
								}),
								createVNode(Tooltip_default, { text: "Notificar Cobro" }, {
									default: withCtx(() => [createVNode(Button_default, {
										variant: "ghost",
										size: "sm",
										icon: "mail",
										class: "!w-10 !h-10 !p-0 !rounded-xl text-primary",
										onClick: ($event) => unref(toast).add("Notificación enviada vía Email/SMS", "success")
									}, null, 8, ["onClick"])]),
									_: 1
								}),
								createVNode(_component_Dropdown, {
									label: "",
									icon: "more_horiz",
									variant: "ghost",
									class: "!p-0 !w-10 !h-10 border border-outline-variant/10 !rounded-xl",
									items: [{
										label: "Editar Propietario",
										icon: "manage_accounts",
										action: () => {}
									}, {
										label: "Excluir de mora",
										icon: "verified",
										action: () => {}
									}]
								})
							])];
						}),
						_: 1
					}, _parent, _scopeId));
					else return [createVNode(unref(Table), {
						columns: tableColumns,
						data: __props.overduePayments,
						class: "border-t border-outline-variant/5 dark:border-white/5"
					}, {
						"cell-unit": withCtx(({ row }) => [createVNode("span", { class: "text-lg font-black text-primary tracking-tighter italic" }, "Lote " + toDisplayString(row.unit), 1)]),
						"cell-owner": withCtx(({ row }) => [createVNode("div", { class: "flex items-center gap-4 py-2" }, [createVNode("div", { class: "w-12 h-12 rounded-2xl bg-surface-container-high dark:bg-white/5 flex items-center justify-center text-xs font-black text-primary border border-outline-variant/10 shadow-sm relative overflow-hidden group" }, [createVNode("div", { class: "absolute inset-0 bg-primary/10 translate-y-full group-hover:translate-y-0 transition-transform duration-500" }), createVNode("span", { class: "relative z-10" }, toDisplayString(row.initials), 1)]), createVNode("div", { class: "flex flex-col" }, [createVNode("span", { class: "text-xs font-black text-on-surface dark:text-white uppercase italic tracking-tighter" }, toDisplayString(row.owner), 1), createVNode("span", { class: "text-[9px] font-bold text-on-surface-variant/40 dark:text-white/20 uppercase tracking-widest" }, "PROPIETARIO VÁLIDO")])])]),
						"cell-amount": withCtx(({ row }) => [createVNode("div", { class: "flex flex-col" }, [createVNode("span", { class: ["text-lg font-black tracking-tighter italic", row.status_type === "error" ? "text-error" : "text-orange-500"] }, toDisplayString(formatCurrency(row.amount)), 3), createVNode("span", { class: "text-[8px] font-black text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.2em] mt-1 italic" }, "SALDO VENCIDO")])]),
						"cell-status": withCtx(({ row }) => [createVNode(Badge_default, {
							variant: row.status_type === "error" ? "error" : "warning",
							class: "!px-5 !py-1 !font-black !text-[10px] tracking-widest uppercase italic"
						}, {
							default: withCtx(() => [createTextVNode(toDisplayString(row.status), 1)]),
							_: 2
						}, 1032, ["variant"])]),
						"cell-actions": withCtx(({ row }) => [createVNode("div", { class: "flex justify-end gap-2 pr-6" }, [
							createVNode(Tooltip_default, { text: "Ver Estado de Cuenta" }, {
								default: withCtx(() => [createVNode(Button_default, {
									variant: "ghost",
									size: "sm",
									icon: "visibility",
									class: "!w-10 !h-10 !p-0 !rounded-xl",
									onClick: ($event) => unref(toast).add("Cargando hoja de vida...", "primary")
								}, null, 8, ["onClick"])]),
								_: 1
							}),
							createVNode(Tooltip_default, { text: "Notificar Cobro" }, {
								default: withCtx(() => [createVNode(Button_default, {
									variant: "ghost",
									size: "sm",
									icon: "mail",
									class: "!w-10 !h-10 !p-0 !rounded-xl text-primary",
									onClick: ($event) => unref(toast).add("Notificación enviada vía Email/SMS", "success")
								}, null, 8, ["onClick"])]),
								_: 1
							}),
							createVNode(_component_Dropdown, {
								label: "",
								icon: "more_horiz",
								variant: "ghost",
								class: "!p-0 !w-10 !h-10 border border-outline-variant/10 !rounded-xl",
								items: [{
									label: "Editar Propietario",
									icon: "manage_accounts",
									action: () => {}
								}, {
									label: "Excluir de mora",
									icon: "verified",
									action: () => {}
								}]
							})
						])]),
						_: 1
					}, 8, ["data"])];
				}),
				_: 1
			}, _parent));
			_push(`</div><!--]-->`);
		};
	}
});
//#endregion
//#region resources/js/Pages/Dashboard.vue
var _sfc_setup = Dashboard_vue_vue_type_script_setup_true_lang_default.setup;
Dashboard_vue_vue_type_script_setup_true_lang_default.setup = (props, ctx) => {
	const ssrContext = useSSRContext();
	(ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Dashboard.vue");
	return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
var Dashboard_default = /* @__PURE__ */ _plugin_vue_export_helper_default(Dashboard_vue_vue_type_script_setup_true_lang_default, [["__scopeId", "data-v-92b7e945"]]);
//#endregion
export { Dashboard_default as default };

import { t as _plugin_vue_export_helper_default } from "./_plugin-vue_export-helper-BjJHhBxR.js";
import { t as useToast } from "./useToast-E_Hdzmc5.js";
import { t as Card_default } from "./Card-D4IwBRMd.js";
import { t as Button_default } from "./Button-C-byZbTR.js";
import { t as Badge_default } from "./Badge-DsYztQFV.js";
import { t as Tooltip_default } from "./Tooltip-IAP-zsdE.js";
import { t as OwnerLayout_default } from "./OwnerLayout-B6B61cyO.js";
import { t as Accordion_default } from "./Accordion-BQDo8aDX.js";
import { n as PaymentModule_default, t as PaymentMethodModal_default } from "./PaymentMethodModal-DKdnwIY-.js";
import { Fragment, createBlock, createCommentVNode, createTextVNode, createVNode, defineComponent, mergeProps, onMounted, onUnmounted, openBlock, ref, renderList, toDisplayString, unref, useSSRContext, withCtx } from "vue";
import { Head, Link } from "@inertiajs/vue3";
import { ssrInterpolate, ssrRenderAttr, ssrRenderAttrs, ssrRenderClass, ssrRenderComponent, ssrRenderList, ssrRenderStyle } from "vue/server-renderer";
//#region resources/js/Components/UI/Carousel.vue?vue&type=script&setup=true&lang.ts
var Carousel_vue_vue_type_script_setup_true_lang_default = /* @__PURE__ */ defineComponent({
	__name: "Carousel",
	__ssrInlineRender: true,
	props: {
		images: {},
		autoPlay: {
			type: Boolean,
			default: true
		},
		interval: { default: 5e3 }
	},
	setup(__props) {
		const props = __props;
		const current = ref(0);
		let timer = null;
		const next = () => {
			current.value = (current.value + 1) % props.images.length;
		};
		const startTimer = () => {
			if (props.autoPlay) timer = setInterval(next, props.interval);
		};
		const stopTimer = () => {
			if (timer) {
				clearInterval(timer);
				timer = null;
			}
		};
		onMounted(startTimer);
		onUnmounted(stopTimer);
		return (_ctx, _push, _parent, _attrs) => {
			_push(`<div${ssrRenderAttrs(mergeProps({ class: "relative rounded-2xl overflow-hidden group aspect-video bg-surface-container-low" }, _attrs))}><div class="flex transition-transform duration-700 ease-in-out h-full" style="${ssrRenderStyle({ transform: `translateX(-${current.value * 100}%)` })}"><!--[-->`);
			ssrRenderList(__props.images, (img, index) => {
				_push(`<div class="w-full h-full shrink-0 relative"><img${ssrRenderAttr("src", img)} class="w-full h-full object-cover"${ssrRenderAttr("alt", "Slide " + (index + 1))}><div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent opacity-60"></div></div>`);
			});
			_push(`<!--]--></div><button class="absolute left-4 top-1/2 -translate-y-1/2 w-12 h-12 bg-white/20 hover:bg-white/40 text-white rounded-xl flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-300 backdrop-blur-md border border-white/20 active:scale-95" aria-label="Previous slide"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path></svg></button><button class="absolute right-4 top-1/2 -translate-y-1/2 w-12 h-12 bg-white/20 hover:bg-white/40 text-white rounded-xl flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-300 backdrop-blur-md border border-white/20 active:scale-95" aria-label="Next slide"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path></svg></button><div class="absolute bottom-6 left-1/2 -translate-x-1/2 flex gap-2"><!--[-->`);
			ssrRenderList(__props.images, (_, index) => {
				_push(`<button class="${ssrRenderClass([current.value === index ? "w-8 bg-secondary shadow-[0_0_10px_rgba(0,212,255,0.6)]" : "w-2 bg-white/40 hover:bg-white/60", "h-1.5 transition-all duration-300 rounded-full"])}"${ssrRenderAttr("aria-label", "Go to slide " + (index + 1))}></button>`);
			});
			_push(`<!--]--></div></div>`);
		};
	}
});
//#endregion
//#region resources/js/Components/UI/Carousel.vue
var _sfc_setup$1 = Carousel_vue_vue_type_script_setup_true_lang_default.setup;
Carousel_vue_vue_type_script_setup_true_lang_default.setup = (props, ctx) => {
	const ssrContext = useSSRContext();
	(ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/UI/Carousel.vue");
	return _sfc_setup$1 ? _sfc_setup$1(props, ctx) : void 0;
};
var Carousel_default = Carousel_vue_vue_type_script_setup_true_lang_default;
//#endregion
//#region resources/js/Pages/Owner/Dashboard.vue?vue&type=script&setup=true&lang.ts
var Dashboard_vue_vue_type_script_setup_true_lang_default = /* @__PURE__ */ defineComponent({
	layout: OwnerLayout_default,
	__name: "Dashboard",
	__ssrInlineRender: true,
	props: {
		unidades: {},
		total_saldo: {},
		transacciones: {},
		asambleas: {},
		user: {},
		features: {}
	},
	setup(__props) {
		const props = __props;
		const toast = useToast();
		const isLoaded = ref(false);
		const payModule = ref(null);
		const showMethodModal = ref(false);
		const handlePayment = () => {
			if (props.total_saldo <= 0) {
				toast.add("No tienes saldos pendientes para pagar.", "success");
				return;
			}
			showMethodModal.value = true;
		};
		const processPayment = (gatewayKey) => {
			showMethodModal.value = false;
			if (gatewayKey === "wompi" && payModule.value) payModule.value.pay();
			else if (gatewayKey === "aval") {
				const avalUrl = props.features.gateways.aval?.url;
				if (avalUrl) window.open(avalUrl, "_blank");
				else toast.add("La URL de Aval Pay Center no está configurada.", "error");
			} else toast.add("Instrucciones para pago manual: Consigna a la cuenta de la copropiedad.", "info");
		};
		onMounted(() => {
			isLoaded.value = true;
		});
		const announcements = ["https://images.unsplash.com/photo-1560518883-ce09059eeffa?w=800&q=80", "https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?w=800&q=80"];
		const formatCurrency = (value) => {
			return new Intl.NumberFormat("es-CO", {
				style: "currency",
				currency: "COP",
				maximumFractionDigits: 0
			}).format(value);
		};
		const formatDate = (dateString) => {
			return new Date(dateString).toLocaleDateString("es-CO", {
				day: "2-digit",
				month: "short",
				year: "numeric"
			});
		};
		return (_ctx, _push, _parent, _attrs) => {
			_push(`<!--[-->`);
			_push(ssrRenderComponent(unref(Head), { title: "Mi Cuenta — NEXO-PRO" }, null, _parent));
			if (__props.unidades.length > 0) _push(ssrRenderComponent(PaymentModule_default, {
				ref_key: "payModule",
				ref: payModule,
				"unidad-id": __props.unidades.find((u) => u.saldo_actual > 0)?.id || __props.unidades[0]?.id,
				amount: __props.total_saldo,
				email: __props.user.email,
				"full-name": __props.user.name
			}, null, _parent));
			else _push(`<!---->`);
			_push(ssrRenderComponent(PaymentMethodModal_default, {
				show: showMethodModal.value,
				gateways: __props.features.gateways,
				amount: __props.total_saldo,
				onClose: ($event) => showMethodModal.value = false,
				onSelect: processPayment
			}, null, _parent));
			_push(`<div class="flex items-center justify-between mb-8 hidden md:flex animate-fade-in" data-v-ea0f738f><div data-v-ea0f738f><h2 class="text-3xl font-black text-primary tracking-tighter uppercase" data-v-ea0f738f>Panel de Propietario</h2><p class="text-xs text-on-surface-variant/60 font-bold uppercase tracking-widest mt-1" data-v-ea0f738f>Resumen consolidado de tus inversiones</p></div></div>`);
			if (!isLoaded.value) _push(`<div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-start" data-v-ea0f738f><div class="md:col-span-7 flex flex-col gap-6 w-full" data-v-ea0f738f><div class="h-28 bg-surface-container-high/50 animate-pulse rounded-[2rem] w-full" data-v-ea0f738f></div><div class="h-80 bg-surface-container-high/30 animate-pulse rounded-2xl w-full" data-v-ea0f738f></div></div><div class="md:col-span-5 flex flex-col gap-6 w-full" data-v-ea0f738f><div class="h-48 bg-surface-container-high/30 animate-pulse rounded-2xl w-full" data-v-ea0f738f></div><div class="h-96 bg-surface-container-high/30 animate-pulse rounded-2xl w-full" data-v-ea0f738f></div><div class="h-32 bg-surface-container-high/50 animate-pulse rounded-[2.5rem] w-full" data-v-ea0f738f></div></div></div>`);
			else {
				_push(`<div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-start animate-fade-in-up" data-v-ea0f738f><div class="md:col-span-7 flex flex-col gap-6 w-full" data-v-ea0f738f><div class="banner-gradient text-white p-6 rounded-[2rem] flex items-center gap-6 shadow-2xl shadow-primary/20 relative overflow-hidden group" data-v-ea0f738f><div class="absolute -right-8 -bottom-8 w-32 h-32 bg-white/10 blur-2xl rounded-full group-hover:scale-150 transition-transform duration-1000 ease-out" data-v-ea0f738f></div><div class="absolute right-20 -top-10 w-24 h-24 bg-secondary/30 blur-xl rounded-full group-hover:translate-x-4 transition-transform duration-1000" data-v-ea0f738f></div><div class="w-14 h-14 rounded-2xl bg-white/10 backdrop-blur-sm flex items-center justify-center text-white shrink-0 border border-white/20 shadow-inner" data-v-ea0f738f><span class="material-symbols-outlined text-3xl" style="${ssrRenderStyle({ "font-variation-settings": "'FILL' 1" })}" data-v-ea0f738f>confirmation_number</span></div><div class="flex-1 z-10" data-v-ea0f738f><p class="text-[10px] font-black uppercase tracking-[0.2em] text-white/70 mb-1" data-v-ea0f738f>Facturación Vigente</p><p class="text-lg font-black uppercase tracking-tight text-white drop-shadow-md" data-v-ea0f738f>Administración ${ssrInterpolate((/* @__PURE__ */ new Date()).toLocaleString("es-CO", { month: "long" }))}</p></div>`);
				_push(ssrRenderComponent(Button_default, {
					variant: "ghost",
					size: "sm",
					icon: "arrow_forward",
					class: "z-10 !bg-white/10 !text-white hover:!bg-white/20 !border-white/20 !rounded-xl backdrop-blur-md transition-all group-hover:translate-x-1"
				}, null, _parent));
				_push(`</div>`);
				_push(ssrRenderComponent(Card_default, { class: "relative" }, {
					header: withCtx((_, _push, _parent, _scopeId) => {
						if (_push) {
							_push(`<div class="w-full flex justify-between items-start" data-v-ea0f738f${_scopeId}><div data-v-ea0f738f${_scopeId}><h2 class="text-on-surface-variant/50 text-[10px] font-black uppercase tracking-[0.2em] mb-2" data-v-ea0f738f${_scopeId}>Saldo Total Consolidado</h2><p class="text-5xl sm:text-6xl font-black text-primary leading-none tracking-tighter" data-v-ea0f738f${_scopeId}>${ssrInterpolate(formatCurrency(__props.total_saldo))} <span class="text-sm sm:text-base font-bold opacity-30 ml-1" data-v-ea0f738f${_scopeId}>COP</span></p></div>`);
							_push(ssrRenderComponent(Badge_default, {
								variant: __props.total_saldo > 0 ? "danger" : "success",
								class: "!px-4 !py-1.5 shadow-sm mt-1"
							}, {
								default: withCtx((_, _push, _parent, _scopeId) => {
									if (_push) _push(`${ssrInterpolate(__props.total_saldo > 0 ? "En Mora" : "Al Día")}`);
									else return [createTextVNode(toDisplayString(__props.total_saldo > 0 ? "En Mora" : "Al Día"), 1)];
								}),
								_: 1
							}, _parent, _scopeId));
							_push(`</div>`);
						} else return [createVNode("div", { class: "w-full flex justify-between items-start" }, [createVNode("div", null, [createVNode("h2", { class: "text-on-surface-variant/50 text-[10px] font-black uppercase tracking-[0.2em] mb-2" }, "Saldo Total Consolidado"), createVNode("p", { class: "text-5xl sm:text-6xl font-black text-primary leading-none tracking-tighter" }, [createTextVNode(toDisplayString(formatCurrency(__props.total_saldo)) + " ", 1), createVNode("span", { class: "text-sm sm:text-base font-bold opacity-30 ml-1" }, "COP")])]), createVNode(Badge_default, {
							variant: __props.total_saldo > 0 ? "danger" : "success",
							class: "!px-4 !py-1.5 shadow-sm mt-1"
						}, {
							default: withCtx(() => [createTextVNode(toDisplayString(__props.total_saldo > 0 ? "En Mora" : "Al Día"), 1)]),
							_: 1
						}, 8, ["variant"])])];
					}),
					footer: withCtx((_, _push, _parent, _scopeId) => {
						if (_push) {
							_push(`<div class="flex flex-col sm:flex-row gap-4 w-full" data-v-ea0f738f${_scopeId}>`);
							if (__props.features.payments_enabled) _push(ssrRenderComponent(Button_default, {
								variant: "primary",
								size: "lg",
								icon: "payments",
								class: "flex-1 shadow-lg shadow-primary/20 hover:shadow-primary/30",
								onClick: handlePayment
							}, {
								default: withCtx((_, _push, _parent, _scopeId) => {
									if (_push) _push(` Pagar ahora `);
									else return [createTextVNode(" Pagar ahora ")];
								}),
								_: 1
							}, _parent, _scopeId));
							else _push(`<div class="flex-1 bg-surface-container-high/40 p-4 rounded-xl border border-outline-variant/20 flex items-center justify-center" data-v-ea0f738f${_scopeId}><p class="text-[10px] font-black text-on-surface-variant/40 uppercase tracking-[0.2em] text-center" data-v-ea0f738f${_scopeId}> Pagos en línea no habilitados </p></div>`);
							_push(ssrRenderComponent(Tooltip_default, { text: "Descargar certificado de paz y salvo (solo si el saldo es $0)" }, {
								default: withCtx((_, _push, _parent, _scopeId) => {
									if (_push) _push(ssrRenderComponent(Button_default, {
										variant: "outline",
										size: "lg",
										icon: "download",
										class: "sm:flex-none bg-surface",
										onClick: ($event) => unref(toast).add("Verificando estado de cuenta...", "primary")
									}, {
										default: withCtx((_, _push, _parent, _scopeId) => {
											if (_push) _push(`<span class="hidden sm:inline" data-v-ea0f738f${_scopeId}>Paz y Salvo</span>`);
											else return [createVNode("span", { class: "hidden sm:inline" }, "Paz y Salvo")];
										}),
										_: 1
									}, _parent, _scopeId));
									else return [createVNode(Button_default, {
										variant: "outline",
										size: "lg",
										icon: "download",
										class: "sm:flex-none bg-surface",
										onClick: ($event) => unref(toast).add("Verificando estado de cuenta...", "primary")
									}, {
										default: withCtx(() => [createVNode("span", { class: "hidden sm:inline" }, "Paz y Salvo")]),
										_: 1
									}, 8, ["onClick"])];
								}),
								_: 1
							}, _parent, _scopeId));
							_push(`</div>`);
						} else return [createVNode("div", { class: "flex flex-col sm:flex-row gap-4 w-full" }, [__props.features.payments_enabled ? (openBlock(), createBlock(Button_default, {
							key: 0,
							variant: "primary",
							size: "lg",
							icon: "payments",
							class: "flex-1 shadow-lg shadow-primary/20 hover:shadow-primary/30",
							onClick: handlePayment
						}, {
							default: withCtx(() => [createTextVNode(" Pagar ahora ")]),
							_: 1
						})) : (openBlock(), createBlock("div", {
							key: 1,
							class: "flex-1 bg-surface-container-high/40 p-4 rounded-xl border border-outline-variant/20 flex items-center justify-center"
						}, [createVNode("p", { class: "text-[10px] font-black text-on-surface-variant/40 uppercase tracking-[0.2em] text-center" }, " Pagos en línea no habilitados ")])), createVNode(Tooltip_default, { text: "Descargar certificado de paz y salvo (solo si el saldo es $0)" }, {
							default: withCtx(() => [createVNode(Button_default, {
								variant: "outline",
								size: "lg",
								icon: "download",
								class: "sm:flex-none bg-surface",
								onClick: ($event) => unref(toast).add("Verificando estado de cuenta...", "primary")
							}, {
								default: withCtx(() => [createVNode("span", { class: "hidden sm:inline" }, "Paz y Salvo")]),
								_: 1
							}, 8, ["onClick"])]),
							_: 1
						})])];
					}),
					default: withCtx((_, _push, _parent, _scopeId) => {
						if (_push) {
							_push(`<div class="pt-4" data-v-ea0f738f${_scopeId}>`);
							if (__props.unidades.length > 1) {
								_push(`<div class="mb-4" data-v-ea0f738f${_scopeId}>`);
								_push(ssrRenderComponent(Accordion_default, { items: [{
									title: "Desglose por unidad",
									content: ""
								}] }, {
									"content-0": withCtx((_, _push, _parent, _scopeId) => {
										if (_push) {
											_push(`<div class="space-y-2 pt-3" data-v-ea0f738f${_scopeId}><!--[-->`);
											ssrRenderList(__props.unidades, (unidad) => {
												_push(`<div class="flex justify-between items-center pb-3 border-b border-outline-variant/10 last:border-0 last:pb-0" data-v-ea0f738f${_scopeId}><span class="flex items-center gap-3 text-xs font-bold text-on-surface" data-v-ea0f738f${_scopeId}><span class="material-symbols-outlined text-lg text-primary/40" data-v-ea0f738f${_scopeId}>apartment</span> ${ssrInterpolate(unidad.torre)} ${ssrInterpolate(unidad.nombre)}</span><span class="text-sm font-black text-primary tracking-tight" data-v-ea0f738f${_scopeId}>${ssrInterpolate(formatCurrency(unidad.saldo_actual))}</span></div>`);
											});
											_push(`<!--]--></div>`);
										} else return [createVNode("div", { class: "space-y-2 pt-3" }, [(openBlock(true), createBlock(Fragment, null, renderList(__props.unidades, (unidad) => {
											return openBlock(), createBlock("div", {
												key: unidad.id,
												class: "flex justify-between items-center pb-3 border-b border-outline-variant/10 last:border-0 last:pb-0"
											}, [createVNode("span", { class: "flex items-center gap-3 text-xs font-bold text-on-surface" }, [createVNode("span", { class: "material-symbols-outlined text-lg text-primary/40" }, "apartment"), createTextVNode(" " + toDisplayString(unidad.torre) + " " + toDisplayString(unidad.nombre), 1)]), createVNode("span", { class: "text-sm font-black text-primary tracking-tight" }, toDisplayString(formatCurrency(unidad.saldo_actual)), 1)]);
										}), 128))])];
									}),
									_: 1
								}, _parent, _scopeId));
								_push(`</div>`);
							} else _push(`<!---->`);
							_push(`</div>`);
						} else return [createVNode("div", { class: "pt-4" }, [__props.unidades.length > 1 ? (openBlock(), createBlock("div", {
							key: 0,
							class: "mb-4"
						}, [createVNode(Accordion_default, { items: [{
							title: "Desglose por unidad",
							content: ""
						}] }, {
							"content-0": withCtx(() => [createVNode("div", { class: "space-y-2 pt-3" }, [(openBlock(true), createBlock(Fragment, null, renderList(__props.unidades, (unidad) => {
								return openBlock(), createBlock("div", {
									key: unidad.id,
									class: "flex justify-between items-center pb-3 border-b border-outline-variant/10 last:border-0 last:pb-0"
								}, [createVNode("span", { class: "flex items-center gap-3 text-xs font-bold text-on-surface" }, [createVNode("span", { class: "material-symbols-outlined text-lg text-primary/40" }, "apartment"), createTextVNode(" " + toDisplayString(unidad.torre) + " " + toDisplayString(unidad.nombre), 1)]), createVNode("span", { class: "text-sm font-black text-primary tracking-tight" }, toDisplayString(formatCurrency(unidad.saldo_actual)), 1)]);
							}), 128))])]),
							_: 1
						})])) : createCommentVNode("", true)])];
					}),
					_: 1
				}, _parent));
				_push(`</div><div class="md:col-span-5 flex flex-col gap-6 w-full" data-v-ea0f738f>`);
				if (__props.asambleas && __props.asambleas.length > 0) {
					_push(`<div class="flex flex-col gap-3" data-v-ea0f738f><p class="text-[10px] font-black text-on-surface-variant/50 uppercase tracking-[0.2em] pl-2 flex items-center gap-2" data-v-ea0f738f><span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-ping" data-v-ea0f738f></span> Eventos en Vivo </p><!--[-->`);
					ssrRenderList(__props.asambleas, (asamblea) => {
						_push(`<div class="bg-[#00173c] text-white p-6 rounded-[2.5rem] shadow-xl relative overflow-hidden group border border-white/5" data-v-ea0f738f><div class="absolute -right-4 -top-4 w-24 h-24 bg-emerald-500/10 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700" data-v-ea0f738f></div><div class="flex items-start justify-between mb-4 relative z-10" data-v-ea0f738f><div class="w-12 h-12 rounded-2xl bg-white/10 flex items-center justify-center backdrop-blur-md border border-white/10" data-v-ea0f738f><span class="material-symbols-rounded text-emerald-400" data-v-ea0f738f>gavel</span></div>`);
						_push(ssrRenderComponent(Badge_default, {
							variant: "success",
							class: "!bg-emerald-500 !text-white !border-0 shadow-lg shadow-emerald-500/20"
						}, {
							default: withCtx((_, _push, _parent, _scopeId) => {
								if (_push) _push(`${ssrInterpolate(asamblea.status === "in_progress" ? "EN VIVO" : "PROGRAMADA")}`);
								else return [createTextVNode(toDisplayString(asamblea.status === "in_progress" ? "EN VIVO" : "PROGRAMADA"), 1)];
							}),
							_: 2
						}, _parent));
						_push(`</div><div class="relative z-10" data-v-ea0f738f><h3 class="text-lg font-black uppercase tracking-tighter leading-tight mb-2" data-v-ea0f738f>${ssrInterpolate(asamblea.titulo)}</h3><div class="flex items-center gap-4 mb-6 opacity-60" data-v-ea0f738f><div class="flex items-center gap-1.5" data-v-ea0f738f><span class="material-symbols-rounded text-xs" data-v-ea0f738f>calendar_today</span><span class="text-[10px] font-bold uppercase tracking-wider" data-v-ea0f738f>${ssrInterpolate(formatDate(asamblea.fecha))}</span></div></div>`);
						_push(ssrRenderComponent(unref(Link), {
							href: _ctx.route("asambleas.show", asamblea.id),
							target: "_blank"
						}, {
							default: withCtx((_, _push, _parent, _scopeId) => {
								if (_push) _push(ssrRenderComponent(Button_default, {
									variant: "primary",
									size: "lg",
									icon: "login",
									class: "w-full !bg-emerald-500 hover:!bg-emerald-600 !text-white !border-0 shadow-lg shadow-emerald-500/30"
								}, {
									default: withCtx((_, _push, _parent, _scopeId) => {
										if (_push) _push(` Ingresar a la Asamblea `);
										else return [createTextVNode(" Ingresar a la Asamblea ")];
									}),
									_: 2
								}, _parent, _scopeId));
								else return [createVNode(Button_default, {
									variant: "primary",
									size: "lg",
									icon: "login",
									class: "w-full !bg-emerald-500 hover:!bg-emerald-600 !text-white !border-0 shadow-lg shadow-emerald-500/30"
								}, {
									default: withCtx(() => [createTextVNode(" Ingresar a la Asamblea ")]),
									_: 1
								})];
							}),
							_: 2
						}, _parent));
						_push(`</div></div>`);
					});
					_push(`<!--]--></div>`);
				} else _push(`<!---->`);
				_push(`<div class="space-y-3" data-v-ea0f738f><p class="text-[10px] font-black text-on-surface-variant/50 uppercase tracking-[0.2em] pl-2 flex items-center gap-2" data-v-ea0f738f><span class="w-1.5 h-1.5 rounded-full bg-primary animate-pulse" data-v-ea0f738f></span> Anuncios de la Comunidad </p>`);
				_push(ssrRenderComponent(Carousel_default, {
					images: announcements,
					class: "rounded-2xl overflow-hidden shadow-md"
				}, null, _parent));
				_push(`</div>`);
				_push(ssrRenderComponent(Card_default, {
					icon: "history",
					title: "Historial de Pagos",
					subtitle: "Últimos movimientos registrados",
					"content-class": "!p-0 !pb-0",
					class: "overflow-hidden"
				}, {
					header: withCtx((_, _push, _parent, _scopeId) => {
						if (_push) _push(ssrRenderComponent(Button_default, {
							variant: "ghost",
							size: "sm",
							class: "!text-secondary hover:bg-secondary/10"
						}, {
							default: withCtx((_, _push, _parent, _scopeId) => {
								if (_push) _push(`Ver todo`);
								else return [createTextVNode("Ver todo")];
							}),
							_: 1
						}, _parent, _scopeId));
						else return [createVNode(Button_default, {
							variant: "ghost",
							size: "sm",
							class: "!text-secondary hover:bg-secondary/10"
						}, {
							default: withCtx(() => [createTextVNode("Ver todo")]),
							_: 1
						})];
					}),
					footer: withCtx((_, _push, _parent, _scopeId) => {
						if (_push) _push(ssrRenderComponent(Button_default, {
							variant: "ghost",
							size: "sm",
							class: "w-full !text-primary hover:bg-primary/5",
							icon: "description"
						}, {
							default: withCtx((_, _push, _parent, _scopeId) => {
								if (_push) _push(`Estado de cuenta completo`);
								else return [createTextVNode("Estado de cuenta completo")];
							}),
							_: 1
						}, _parent, _scopeId));
						else return [createVNode(Button_default, {
							variant: "ghost",
							size: "sm",
							class: "w-full !text-primary hover:bg-primary/5",
							icon: "description"
						}, {
							default: withCtx(() => [createTextVNode("Estado de cuenta completo")]),
							_: 1
						})];
					}),
					default: withCtx((_, _push, _parent, _scopeId) => {
						if (_push) {
							_push(`<div class="flex flex-col w-full" data-v-ea0f738f${_scopeId}><!--[-->`);
							ssrRenderList(__props.transacciones, (tx) => {
								_push(`<div class="px-6 py-4 flex items-center gap-4 hover:bg-surface-container-highest/30 transition-colors group cursor-pointer border-b border-outline-variant/5 last:border-0" data-v-ea0f738f${_scopeId}><div class="w-10 h-10 rounded-xl bg-surface-container-high flex items-center justify-center shrink-0 group-hover:bg-secondary group-hover:text-on-secondary transition-all shadow-sm border border-outline-variant/10" data-v-ea0f738f${_scopeId}><span class="material-symbols-outlined text-secondary group-hover:text-on-secondary text-xl" style="${ssrRenderStyle({ "font-variation-settings": "'FILL' 1" })}" data-v-ea0f738f${_scopeId}>${ssrInterpolate(tx.tipo === "abono" ? "check_circle" : "receipt")}</span></div><div class="flex-1 min-w-0 flex flex-col justify-center" data-v-ea0f738f${_scopeId}><div class="flex items-center gap-2 mb-0.5" data-v-ea0f738f${_scopeId}><p class="text-sm font-black text-on-surface tracking-tight leading-snug truncate" data-v-ea0f738f${_scopeId}>${ssrInterpolate(tx.concepto?.nombre || "Cuota de Administración")}</p></div><div class="flex items-center gap-2" data-v-ea0f738f${_scopeId}><p class="text-[10px] font-bold text-on-surface-variant/50 uppercase tracking-widest" data-v-ea0f738f${_scopeId}>${ssrInterpolate(formatDate(tx.fecha))}</p>`);
								if (__props.unidades.length > 1) _push(ssrRenderComponent(Badge_default, {
									variant: "neutral",
									class: "!text-[8px] !px-1.5 opacity-70 group-hover:opacity-100 transition-opacity"
								}, {
									default: withCtx((_, _push, _parent, _scopeId) => {
										if (_push) _push(`${ssrInterpolate(tx.unidad?.torre)} ${ssrInterpolate(tx.unidad?.nombre)}`);
										else return [createTextVNode(toDisplayString(tx.unidad?.torre) + " " + toDisplayString(tx.unidad?.nombre), 1)];
									}),
									_: 2
								}, _parent, _scopeId));
								else _push(`<!---->`);
								_push(`</div></div><div class="text-right shrink-0" data-v-ea0f738f${_scopeId}><p class="text-base font-black text-primary tracking-tighter leading-none mb-1" data-v-ea0f738f${_scopeId}>${ssrInterpolate(formatCurrency(tx.monto))}</p><p class="text-[9px] text-emerald-600 font-black uppercase tracking-widest" data-v-ea0f738f${_scopeId}>Exitoso</p></div></div>`);
							});
							_push(`<!--]-->`);
							if (__props.transacciones.length === 0) _push(`<div class="py-16 text-center text-on-surface-variant/30 flex flex-col items-center gap-3" data-v-ea0f738f${_scopeId}><div class="w-16 h-16 rounded-full bg-surface-container-high flex items-center justify-center mb-2" data-v-ea0f738f${_scopeId}><span class="material-symbols-outlined text-4xl" data-v-ea0f738f${_scopeId}>history</span></div><p class="text-[10px] font-black uppercase tracking-[0.2em]" data-v-ea0f738f${_scopeId}>Sin transacciones recientes</p></div>`);
							else _push(`<!---->`);
							_push(`</div>`);
						} else return [createVNode("div", { class: "flex flex-col w-full" }, [(openBlock(true), createBlock(Fragment, null, renderList(__props.transacciones, (tx) => {
							return openBlock(), createBlock("div", {
								key: tx.id,
								class: "px-6 py-4 flex items-center gap-4 hover:bg-surface-container-highest/30 transition-colors group cursor-pointer border-b border-outline-variant/5 last:border-0"
							}, [
								createVNode("div", { class: "w-10 h-10 rounded-xl bg-surface-container-high flex items-center justify-center shrink-0 group-hover:bg-secondary group-hover:text-on-secondary transition-all shadow-sm border border-outline-variant/10" }, [createVNode("span", {
									class: "material-symbols-outlined text-secondary group-hover:text-on-secondary text-xl",
									style: { "font-variation-settings": "'FILL' 1" }
								}, toDisplayString(tx.tipo === "abono" ? "check_circle" : "receipt"), 1)]),
								createVNode("div", { class: "flex-1 min-w-0 flex flex-col justify-center" }, [createVNode("div", { class: "flex items-center gap-2 mb-0.5" }, [createVNode("p", { class: "text-sm font-black text-on-surface tracking-tight leading-snug truncate" }, toDisplayString(tx.concepto?.nombre || "Cuota de Administración"), 1)]), createVNode("div", { class: "flex items-center gap-2" }, [createVNode("p", { class: "text-[10px] font-bold text-on-surface-variant/50 uppercase tracking-widest" }, toDisplayString(formatDate(tx.fecha)), 1), __props.unidades.length > 1 ? (openBlock(), createBlock(Badge_default, {
									key: 0,
									variant: "neutral",
									class: "!text-[8px] !px-1.5 opacity-70 group-hover:opacity-100 transition-opacity"
								}, {
									default: withCtx(() => [createTextVNode(toDisplayString(tx.unidad?.torre) + " " + toDisplayString(tx.unidad?.nombre), 1)]),
									_: 2
								}, 1024)) : createCommentVNode("", true)])]),
								createVNode("div", { class: "text-right shrink-0" }, [createVNode("p", { class: "text-base font-black text-primary tracking-tighter leading-none mb-1" }, toDisplayString(formatCurrency(tx.monto)), 1), createVNode("p", { class: "text-[9px] text-emerald-600 font-black uppercase tracking-widest" }, "Exitoso")])
							]);
						}), 128)), __props.transacciones.length === 0 ? (openBlock(), createBlock("div", {
							key: 0,
							class: "py-16 text-center text-on-surface-variant/30 flex flex-col items-center gap-3"
						}, [createVNode("div", { class: "w-16 h-16 rounded-full bg-surface-container-high flex items-center justify-center mb-2" }, [createVNode("span", { class: "material-symbols-outlined text-4xl" }, "history")]), createVNode("p", { class: "text-[10px] font-black uppercase tracking-[0.2em]" }, "Sin transacciones recientes")])) : createCommentVNode("", true)])];
					}),
					_: 1
				}, _parent));
				_push(`<div class="bg-primary-container text-on-primary-container rounded-[2.5rem] p-7 flex items-center gap-5 shadow-xl relative overflow-hidden group border border-primary/10 mt-auto" data-v-ea0f738f><div class="absolute -left-6 -top-6 w-32 h-32 bg-primary/10 rounded-full group-hover:scale-[1.8] transition-transform duration-700 ease-out" data-v-ea0f738f></div><div class="absolute right-0 bottom-0 w-24 h-24 bg-secondary/10 rounded-tl-[100%] group-hover:scale-110 transition-transform duration-700" data-v-ea0f738f></div><div class="w-14 h-14 bg-primary text-on-primary rounded-2xl flex items-center justify-center shrink-0 z-10 shadow-lg shadow-primary/30 group-hover:rotate-12 transition-transform duration-300" data-v-ea0f738f><span class="material-symbols-outlined text-2xl" data-v-ea0f738f>support_agent</span></div><div class="flex-1 z-10" data-v-ea0f738f><h4 class="text-base font-black uppercase tracking-tighter mb-1 leading-none" data-v-ea0f738f>¿Dudas o Quejas?</h4><p class="text-[11px] font-medium opacity-80 leading-snug mb-3" data-v-ea0f738f>Radica un ticket y recibe respuesta en &lt; 24h hábiles.</p>`);
				_push(ssrRenderComponent(unref(Link), { href: _ctx.route("pqrs.index") }, {
					default: withCtx((_, _push, _parent, _scopeId) => {
						if (_push) _push(ssrRenderComponent(Button_default, {
							variant: "primary",
							size: "sm",
							icon: "forum",
							class: "!rounded-xl !bg-primary !text-on-primary shadow-md hover:shadow-lg"
						}, {
							default: withCtx((_, _push, _parent, _scopeId) => {
								if (_push) _push(`Ir a PQRS`);
								else return [createTextVNode("Ir a PQRS")];
							}),
							_: 1
						}, _parent, _scopeId));
						else return [createVNode(Button_default, {
							variant: "primary",
							size: "sm",
							icon: "forum",
							class: "!rounded-xl !bg-primary !text-on-primary shadow-md hover:shadow-lg"
						}, {
							default: withCtx(() => [createTextVNode("Ir a PQRS")]),
							_: 1
						})];
					}),
					_: 1
				}, _parent));
				_push(`</div></div></div></div>`);
			}
			_push(`<!--]-->`);
		};
	}
});
//#endregion
//#region resources/js/Pages/Owner/Dashboard.vue
var _sfc_setup = Dashboard_vue_vue_type_script_setup_true_lang_default.setup;
Dashboard_vue_vue_type_script_setup_true_lang_default.setup = (props, ctx) => {
	const ssrContext = useSSRContext();
	(ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Owner/Dashboard.vue");
	return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
var Dashboard_default = /* @__PURE__ */ _plugin_vue_export_helper_default(Dashboard_vue_vue_type_script_setup_true_lang_default, [["__scopeId", "data-v-ea0f738f"]]);
//#endregion
export { Dashboard_default as default };

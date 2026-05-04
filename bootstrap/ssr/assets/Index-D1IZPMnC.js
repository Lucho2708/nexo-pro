import { t as _plugin_vue_export_helper_default } from "./_plugin-vue_export-helper-BjJHhBxR.js";
import { t as Card_default } from "./Card-C2-I3_la.js";
import { t as Button_default } from "./Button-5V-KYwxi.js";
import { t as Badge_default } from "./Badge-DWJ2qZRx.js";
import { t as useToast } from "./useToast-Dcf8ak3V.js";
import { t as OwnerLayout_default } from "./OwnerLayout-DdbYh73s.js";
import { n as PaymentModule_default, t as PaymentMethodModal_default } from "./PaymentMethodModal-CNAE6CV1.js";
import { Fragment, createBlock, createCommentVNode, createTextVNode, createVNode, defineComponent, openBlock, ref, renderList, toDisplayString, unref, useSSRContext, withCtx } from "vue";
import { Head } from "@inertiajs/vue3";
import { ssrInterpolate, ssrRenderClass, ssrRenderComponent, ssrRenderList } from "vue/server-renderer";
//#region resources/js/Pages/Owner/Payments/Index.vue?vue&type=script&setup=true&lang.ts
var Index_vue_vue_type_script_setup_true_lang_default = /* @__PURE__ */ defineComponent({
	layout: OwnerLayout_default,
	__name: "Index",
	__ssrInlineRender: true,
	props: {
		unidades: {},
		total_saldo: {},
		transacciones: {},
		features: {}
	},
	setup(__props) {
		const props = __props;
		const toast = useToast();
		const payModule = ref(null);
		const showMethodModal = ref(false);
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
				month: "long",
				year: "numeric"
			});
		};
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
		return (_ctx, _push, _parent, _attrs) => {
			_push(`<!--[-->`);
			_push(ssrRenderComponent(unref(Head), { title: "Mis Pagos — NEXO-PRO" }, null, _parent));
			if (__props.unidades.length > 0) _push(ssrRenderComponent(PaymentModule_default, {
				ref_key: "payModule",
				ref: payModule,
				"unidad-id": __props.unidades[0]?.id,
				amount: __props.total_saldo,
				email: _ctx.$page.props.auth.user.email,
				"full-name": _ctx.$page.props.auth.user.name
			}, null, _parent));
			else _push(`<!---->`);
			_push(ssrRenderComponent(PaymentMethodModal_default, {
				show: showMethodModal.value,
				gateways: __props.features.gateways,
				amount: __props.total_saldo,
				onClose: ($event) => showMethodModal.value = false,
				onSelect: processPayment
			}, null, _parent));
			_push(`<div class="space-y-8 animate-fade-in" data-v-36924c37><div class="flex flex-col md:flex-row md:items-end justify-between gap-4" data-v-36924c37><div data-v-36924c37><h2 class="text-3xl font-black text-primary tracking-tighter uppercase leading-none" data-v-36924c37>Mi Estado de Cuenta</h2><p class="text-xs text-on-surface-variant/60 font-bold uppercase tracking-widest mt-2" data-v-36924c37>Consulta tus saldos y pagos realizados</p></div><div class="flex items-center gap-3 bg-surface-container-high/50 p-2 rounded-2xl border border-outline-variant/10" data-v-36924c37><div class="px-4 py-2 text-right" data-v-36924c37><p class="text-[9px] font-black uppercase tracking-widest text-on-surface-variant/50" data-v-36924c37>Saldo Total</p><p class="text-xl font-black text-primary tracking-tighter" data-v-36924c37>${ssrInterpolate(formatCurrency(__props.total_saldo))}</p></div>`);
			if (__props.features.payments_enabled && __props.total_saldo > 0) _push(ssrRenderComponent(Button_default, {
				variant: "primary",
				size: "sm",
				icon: "payments",
				onClick: handlePayment
			}, {
				default: withCtx((_, _push, _parent, _scopeId) => {
					if (_push) _push(`Pagar`);
					else return [createTextVNode("Pagar")];
				}),
				_: 1
			}, _parent));
			else _push(`<!---->`);
			_push(`</div></div><div class="grid grid-cols-1 lg:grid-cols-12 gap-8" data-v-36924c37><div class="lg:col-span-4 space-y-6" data-v-36924c37><h3 class="text-[10px] font-black text-on-surface-variant/50 uppercase tracking-[0.2em] px-2" data-v-36924c37>Unidades Vinculadas</h3><!--[-->`);
			ssrRenderList(__props.unidades, (unidad) => {
				_push(`<div class="group" data-v-36924c37>`);
				_push(ssrRenderComponent(Card_default, { class: ["!p-0 overflow-hidden hover:premium-elevated transition-all border-l-4", unidad.saldo_actual > 0 ? "border-l-error" : "border-l-success"] }, {
					default: withCtx((_, _push, _parent, _scopeId) => {
						if (_push) {
							_push(`<div class="p-5 flex justify-between items-center" data-v-36924c37${_scopeId}><div class="flex items-center gap-4" data-v-36924c37${_scopeId}><div class="w-10 h-10 rounded-xl bg-surface-container-high flex items-center justify-center text-primary group-hover:scale-110 transition-transform" data-v-36924c37${_scopeId}><span class="material-symbols-outlined" data-v-36924c37${_scopeId}>apartment</span></div><div data-v-36924c37${_scopeId}><p class="text-xs font-black text-on-surface tracking-tight" data-v-36924c37${_scopeId}>${ssrInterpolate(unidad.torre)} - ${ssrInterpolate(unidad.nombre)}</p><p class="text-[10px] text-on-surface-variant/60 font-bold" data-v-36924c37${_scopeId}>${ssrInterpolate(unidad.copropiedad?.nombre)}</p></div></div><div class="text-right" data-v-36924c37${_scopeId}><p class="text-sm font-black text-primary" data-v-36924c37${_scopeId}>${ssrInterpolate(formatCurrency(unidad.saldo_actual))}</p>`);
							_push(ssrRenderComponent(Badge_default, {
								variant: unidad.saldo_actual > 0 ? "danger" : "success",
								class: "!text-[8px] !px-1.5 mt-0.5"
							}, {
								default: withCtx((_, _push, _parent, _scopeId) => {
									if (_push) _push(`${ssrInterpolate(unidad.saldo_actual > 0 ? "Mora" : "Al día")}`);
									else return [createTextVNode(toDisplayString(unidad.saldo_actual > 0 ? "Mora" : "Al día"), 1)];
								}),
								_: 2
							}, _parent, _scopeId));
							_push(`</div></div>`);
						} else return [createVNode("div", { class: "p-5 flex justify-between items-center" }, [createVNode("div", { class: "flex items-center gap-4" }, [createVNode("div", { class: "w-10 h-10 rounded-xl bg-surface-container-high flex items-center justify-center text-primary group-hover:scale-110 transition-transform" }, [createVNode("span", { class: "material-symbols-outlined" }, "apartment")]), createVNode("div", null, [createVNode("p", { class: "text-xs font-black text-on-surface tracking-tight" }, toDisplayString(unidad.torre) + " - " + toDisplayString(unidad.nombre), 1), createVNode("p", { class: "text-[10px] text-on-surface-variant/60 font-bold" }, toDisplayString(unidad.copropiedad?.nombre), 1)])]), createVNode("div", { class: "text-right" }, [createVNode("p", { class: "text-sm font-black text-primary" }, toDisplayString(formatCurrency(unidad.saldo_actual)), 1), createVNode(Badge_default, {
							variant: unidad.saldo_actual > 0 ? "danger" : "success",
							class: "!text-[8px] !px-1.5 mt-0.5"
						}, {
							default: withCtx(() => [createTextVNode(toDisplayString(unidad.saldo_actual > 0 ? "Mora" : "Al día"), 1)]),
							_: 2
						}, 1032, ["variant"])])])];
					}),
					_: 2
				}, _parent));
				_push(`</div>`);
			});
			_push(`<!--]--></div><div class="lg:col-span-8 space-y-6" data-v-36924c37><div class="flex items-center justify-between px-2" data-v-36924c37><h3 class="text-[10px] font-black text-on-surface-variant/50 uppercase tracking-[0.2em]" data-v-36924c37>Historial Completo de Transacciones</h3><div class="flex gap-2" data-v-36924c37>`);
			_push(ssrRenderComponent(Button_default, {
				variant: "ghost",
				size: "sm",
				icon: "filter_list",
				class: "!px-2"
			}, null, _parent));
			_push(ssrRenderComponent(Button_default, {
				variant: "ghost",
				size: "sm",
				icon: "download",
				class: "!px-2"
			}, null, _parent));
			_push(`</div></div>`);
			_push(ssrRenderComponent(Card_default, { class: "!p-0 overflow-hidden" }, {
				default: withCtx((_, _push, _parent, _scopeId) => {
					if (_push) {
						_push(`<div class="overflow-x-auto" data-v-36924c37${_scopeId}><table class="w-full text-left border-collapse" data-v-36924c37${_scopeId}><thead class="bg-surface-container-high/30 border-b border-outline-variant/10" data-v-36924c37${_scopeId}><tr data-v-36924c37${_scopeId}><th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-on-surface-variant/60" data-v-36924c37${_scopeId}>Fecha</th><th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-on-surface-variant/60" data-v-36924c37${_scopeId}>Concepto</th><th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-on-surface-variant/60" data-v-36924c37${_scopeId}>Unidad</th><th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-on-surface-variant/60" data-v-36924c37${_scopeId}>Referencia</th><th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-on-surface-variant/60 text-right" data-v-36924c37${_scopeId}>Monto</th></tr></thead><tbody class="divide-y divide-outline-variant/5" data-v-36924c37${_scopeId}><!--[-->`);
						ssrRenderList(__props.transacciones, (tx) => {
							_push(`<tr class="hover:bg-primary/5 transition-colors group" data-v-36924c37${_scopeId}><td class="px-6 py-5" data-v-36924c37${_scopeId}><p class="text-xs font-bold text-on-surface" data-v-36924c37${_scopeId}>${ssrInterpolate(formatDate(tx.fecha))}</p></td><td class="px-6 py-5" data-v-36924c37${_scopeId}><div class="flex items-center gap-3" data-v-36924c37${_scopeId}><span class="${ssrRenderClass([tx.tipo === "abono" ? "text-success" : "text-error", "material-symbols-outlined text-sm"])}" data-v-36924c37${_scopeId}>${ssrInterpolate(tx.tipo === "abono" ? "arrow_downward" : "arrow_upward")}</span><p class="text-xs font-black text-on-surface tracking-tight" data-v-36924c37${_scopeId}>${ssrInterpolate(tx.concepto?.nombre || "Administración")}</p></div></td><td class="px-6 py-5" data-v-36924c37${_scopeId}><p class="text-[10px] font-bold text-on-surface-variant" data-v-36924c37${_scopeId}>${ssrInterpolate(tx.unidad?.torre)} ${ssrInterpolate(tx.unidad?.nombre)}</p></td><td class="px-6 py-5" data-v-36924c37${_scopeId}><p class="text-[10px] font-mono text-on-surface-variant/50" data-v-36924c37${_scopeId}>${ssrInterpolate(tx.referencia || "N/A")}</p></td><td class="px-6 py-5 text-right" data-v-36924c37${_scopeId}><p class="text-sm font-black text-primary tracking-tighter" data-v-36924c37${_scopeId}>${ssrInterpolate(formatCurrency(tx.monto))}</p></td></tr>`);
						});
						_push(`<!--]-->`);
						if (__props.transacciones.length === 0) _push(`<tr data-v-36924c37${_scopeId}><td colspan="5" class="px-6 py-20 text-center" data-v-36924c37${_scopeId}><div class="flex flex-col items-center gap-4 opacity-30" data-v-36924c37${_scopeId}><span class="material-symbols-outlined text-5xl" data-v-36924c37${_scopeId}>receipt_long</span><p class="text-xs font-black uppercase tracking-[0.2em]" data-v-36924c37${_scopeId}>No se registran pagos en el sistema</p></div></td></tr>`);
						else _push(`<!---->`);
						_push(`</tbody></table></div>`);
					} else return [createVNode("div", { class: "overflow-x-auto" }, [createVNode("table", { class: "w-full text-left border-collapse" }, [createVNode("thead", { class: "bg-surface-container-high/30 border-b border-outline-variant/10" }, [createVNode("tr", null, [
						createVNode("th", { class: "px-6 py-4 text-[10px] font-black uppercase tracking-widest text-on-surface-variant/60" }, "Fecha"),
						createVNode("th", { class: "px-6 py-4 text-[10px] font-black uppercase tracking-widest text-on-surface-variant/60" }, "Concepto"),
						createVNode("th", { class: "px-6 py-4 text-[10px] font-black uppercase tracking-widest text-on-surface-variant/60" }, "Unidad"),
						createVNode("th", { class: "px-6 py-4 text-[10px] font-black uppercase tracking-widest text-on-surface-variant/60" }, "Referencia"),
						createVNode("th", { class: "px-6 py-4 text-[10px] font-black uppercase tracking-widest text-on-surface-variant/60 text-right" }, "Monto")
					])]), createVNode("tbody", { class: "divide-y divide-outline-variant/5" }, [(openBlock(true), createBlock(Fragment, null, renderList(__props.transacciones, (tx) => {
						return openBlock(), createBlock("tr", {
							key: tx.id,
							class: "hover:bg-primary/5 transition-colors group"
						}, [
							createVNode("td", { class: "px-6 py-5" }, [createVNode("p", { class: "text-xs font-bold text-on-surface" }, toDisplayString(formatDate(tx.fecha)), 1)]),
							createVNode("td", { class: "px-6 py-5" }, [createVNode("div", { class: "flex items-center gap-3" }, [createVNode("span", { class: ["material-symbols-outlined text-sm", tx.tipo === "abono" ? "text-success" : "text-error"] }, toDisplayString(tx.tipo === "abono" ? "arrow_downward" : "arrow_upward"), 3), createVNode("p", { class: "text-xs font-black text-on-surface tracking-tight" }, toDisplayString(tx.concepto?.nombre || "Administración"), 1)])]),
							createVNode("td", { class: "px-6 py-5" }, [createVNode("p", { class: "text-[10px] font-bold text-on-surface-variant" }, toDisplayString(tx.unidad?.torre) + " " + toDisplayString(tx.unidad?.nombre), 1)]),
							createVNode("td", { class: "px-6 py-5" }, [createVNode("p", { class: "text-[10px] font-mono text-on-surface-variant/50" }, toDisplayString(tx.referencia || "N/A"), 1)]),
							createVNode("td", { class: "px-6 py-5 text-right" }, [createVNode("p", { class: "text-sm font-black text-primary tracking-tighter" }, toDisplayString(formatCurrency(tx.monto)), 1)])
						]);
					}), 128)), __props.transacciones.length === 0 ? (openBlock(), createBlock("tr", { key: 0 }, [createVNode("td", {
						colspan: "5",
						class: "px-6 py-20 text-center"
					}, [createVNode("div", { class: "flex flex-col items-center gap-4 opacity-30" }, [createVNode("span", { class: "material-symbols-outlined text-5xl" }, "receipt_long"), createVNode("p", { class: "text-xs font-black uppercase tracking-[0.2em]" }, "No se registran pagos en el sistema")])])])) : createCommentVNode("", true)])])])];
				}),
				_: 1
			}, _parent));
			_push(`</div></div></div><!--]-->`);
		};
	}
});
//#endregion
//#region resources/js/Pages/Owner/Payments/Index.vue
var _sfc_setup = Index_vue_vue_type_script_setup_true_lang_default.setup;
Index_vue_vue_type_script_setup_true_lang_default.setup = (props, ctx) => {
	const ssrContext = useSSRContext();
	(ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Owner/Payments/Index.vue");
	return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
var Index_default = /* @__PURE__ */ _plugin_vue_export_helper_default(Index_vue_vue_type_script_setup_true_lang_default, [["__scopeId", "data-v-36924c37"]]);
//#endregion
export { Index_default as default };

import { t as useToast } from "./useToast-E_Hdzmc5.js";
import { t as Modal_default } from "./Modal-DfwT9E3X.js";
import axios from "axios";
import { Fragment, createBlock, createVNode, defineComponent, mergeProps, openBlock, ref, renderList, toDisplayString, useSSRContext, withCtx } from "vue";
import { ssrInterpolate, ssrRenderAttrs, ssrRenderClass, ssrRenderComponent, ssrRenderList, ssrRenderStyle } from "vue/server-renderer";
//#region resources/js/Components/Payments/PaymentModule.vue?vue&type=script&setup=true&lang.ts
var PaymentModule_vue_vue_type_script_setup_true_lang_default = /* @__PURE__ */ defineComponent({
	__name: "PaymentModule",
	__ssrInlineRender: true,
	props: {
		unidadId: {},
		amount: {},
		email: {},
		fullName: {}
	},
	setup(__props, { expose: __expose }) {
		const props = __props;
		const toast = useToast();
		const isLoading = ref(false);
		const loadWompiScript = () => {
			return new Promise((resolve, reject) => {
				if (window.WidgetCheckout) {
					resolve(window.WidgetCheckout);
					return;
				}
				const script = document.createElement("script");
				script.src = "https://checkout.wompi.co/widget.js";
				script.onload = resolve;
				script.onerror = reject;
				document.head.appendChild(script);
			});
		};
		const initiatePayment = async () => {
			if (isLoading.value) return;
			isLoading.value = true;
			try {
				await loadWompiScript();
				const data = (await axios.post(route("payments.initiate"), {
					unidad_id: props.unidadId,
					amount: props.amount
				})).data;
				new window.WidgetCheckout({
					currency: "COP",
					amountInCents: props.amount * 100,
					reference: data.reference,
					publicKey: data.public_key,
					customerEmail: props.email,
					fullName: props.fullName,
					signature: { integrity: data.signature }
				}).open((result) => {
					const transaction = result.transaction;
					if (transaction.status === "APPROVED") {
						toast.add("¡Pago aprobado con éxito!", "success");
						router.reload();
					} else toast.add(`Estado del pago: ${transaction.status}`, "info");
					isLoading.value = false;
				});
			} catch (error) {
				console.error("Error initiating Wompi:", error);
				toast.add(error.response?.data?.message || "No se pudo iniciar la pasarela de pago.", "error");
				isLoading.value = false;
			}
		};
		__expose({ pay: initiatePayment });
		return (_ctx, _push, _parent, _attrs) => {
			if (isLoading.value) _push(`<div${ssrRenderAttrs(mergeProps({ class: "fixed inset-0 z-[200] bg-surface/60 backdrop-blur-sm flex items-center justify-center" }, _attrs))}><div class="flex flex-col items-center gap-4"><div class="w-12 h-12 border-4 border-primary border-t-transparent rounded-full animate-spin"></div><p class="text-[10px] font-black uppercase tracking-[0.2em] text-primary">Conectando con Wompi...</p></div></div>`);
			else _push(`<!---->`);
		};
	}
});
//#endregion
//#region resources/js/Components/Payments/PaymentModule.vue
var _sfc_setup$1 = PaymentModule_vue_vue_type_script_setup_true_lang_default.setup;
PaymentModule_vue_vue_type_script_setup_true_lang_default.setup = (props, ctx) => {
	const ssrContext = useSSRContext();
	(ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/Payments/PaymentModule.vue");
	return _sfc_setup$1 ? _sfc_setup$1(props, ctx) : void 0;
};
var PaymentModule_default = PaymentModule_vue_vue_type_script_setup_true_lang_default;
//#endregion
//#region resources/js/Components/Payments/PaymentMethodModal.vue?vue&type=script&setup=true&lang.ts
var PaymentMethodModal_vue_vue_type_script_setup_true_lang_default = /* @__PURE__ */ defineComponent({
	__name: "PaymentMethodModal",
	__ssrInlineRender: true,
	props: {
		show: { type: Boolean },
		gateways: {},
		amount: {}
	},
	emits: ["close", "select"],
	setup(__props, { emit: __emit }) {
		const emit = __emit;
		const formatCurrency = (value) => {
			return new Intl.NumberFormat("es-CO", {
				style: "currency",
				currency: "COP",
				maximumFractionDigits: 0
			}).format(value);
		};
		const selectGateway = (key) => {
			emit("select", key);
		};
		const getIcon = (key) => {
			return {
				wompi: "account_balance",
				aval: "account_balance_wallet",
				manual: "payments"
			}[key] || "credit_card";
		};
		const getCardStyle = (key) => {
			return {
				wompi: "hover:border-primary/50 hover:bg-primary/5",
				aval: "hover:border-secondary/50 hover:bg-secondary/5",
				manual: "hover:border-emerald-500/50 hover:bg-emerald-500/5"
			}[key] || "hover:border-primary/50 hover:bg-primary/5";
		};
		return (_ctx, _push, _parent, _attrs) => {
			_push(ssrRenderComponent(Modal_default, mergeProps({
				show: __props.show,
				onClose: ($event) => emit("close"),
				"max-width": "md"
			}, _attrs), {
				default: withCtx((_, _push, _parent, _scopeId) => {
					if (_push) {
						_push(`<div class="p-8"${_scopeId}><header class="mb-8 text-center"${_scopeId}><div class="w-16 h-16 bg-primary/10 text-primary rounded-2xl flex items-center justify-center mx-auto mb-4 border border-primary/10"${_scopeId}><span class="material-symbols-outlined text-3xl"${_scopeId}>shopping_cart_checkout</span></div><h3 class="text-2xl font-black text-on-surface tracking-tighter uppercase leading-none"${_scopeId}>Método de Pago</h3><p class="text-xs text-on-surface-variant/60 font-bold uppercase tracking-widest mt-2"${_scopeId}>Vas a pagar ${ssrInterpolate(formatCurrency(__props.amount))}</p></header><div class="space-y-4"${_scopeId}><!--[-->`);
						ssrRenderList(__props.gateways, (config, key) => {
							_push(`<div class="${ssrRenderClass([getCardStyle(key), "group p-5 rounded-2xl border-2 border-outline-variant/30 cursor-pointer transition-all duration-300 flex items-center gap-5"])}"${_scopeId}><div class="w-12 h-12 rounded-xl bg-surface-container-high flex items-center justify-center text-primary group-hover:scale-110 transition-transform shadow-inner"${_scopeId}><span class="material-symbols-outlined text-2xl" style="${ssrRenderStyle({ "font-variation-settings": "'FILL' 1" })}"${_scopeId}>${ssrInterpolate(getIcon(key))}</span></div><div class="flex-1"${_scopeId}><p class="text-sm font-black text-on-surface tracking-tight"${_scopeId}>${ssrInterpolate(config.label)}</p><p class="text-[10px] text-on-surface-variant/60 font-bold uppercase tracking-widest"${_scopeId}>${ssrInterpolate(config.type === "redirect" ? "Redirección Bancaria" : "Pasarela de Pago")}</p></div><span class="material-symbols-outlined text-on-surface-variant/30 group-hover:translate-x-1 group-hover:text-primary transition-all"${_scopeId}>chevron_right</span></div>`);
						});
						_push(`<!--]--></div><footer class="mt-8 pt-6 border-t border-outline-variant/20"${_scopeId}><p class="text-[10px] text-center text-on-surface-variant/40 font-medium leading-relaxed"${_scopeId}> Al proceder con el pago, aceptas nuestros términos y condiciones de servicio. </p></footer></div>`);
					} else return [createVNode("div", { class: "p-8" }, [
						createVNode("header", { class: "mb-8 text-center" }, [
							createVNode("div", { class: "w-16 h-16 bg-primary/10 text-primary rounded-2xl flex items-center justify-center mx-auto mb-4 border border-primary/10" }, [createVNode("span", { class: "material-symbols-outlined text-3xl" }, "shopping_cart_checkout")]),
							createVNode("h3", { class: "text-2xl font-black text-on-surface tracking-tighter uppercase leading-none" }, "Método de Pago"),
							createVNode("p", { class: "text-xs text-on-surface-variant/60 font-bold uppercase tracking-widest mt-2" }, "Vas a pagar " + toDisplayString(formatCurrency(__props.amount)), 1)
						]),
						createVNode("div", { class: "space-y-4" }, [(openBlock(true), createBlock(Fragment, null, renderList(__props.gateways, (config, key) => {
							return openBlock(), createBlock("div", {
								key,
								onClick: ($event) => selectGateway(key),
								class: ["group p-5 rounded-2xl border-2 border-outline-variant/30 cursor-pointer transition-all duration-300 flex items-center gap-5", getCardStyle(key)]
							}, [
								createVNode("div", { class: "w-12 h-12 rounded-xl bg-surface-container-high flex items-center justify-center text-primary group-hover:scale-110 transition-transform shadow-inner" }, [createVNode("span", {
									class: "material-symbols-outlined text-2xl",
									style: { "font-variation-settings": "'FILL' 1" }
								}, toDisplayString(getIcon(key)), 1)]),
								createVNode("div", { class: "flex-1" }, [createVNode("p", { class: "text-sm font-black text-on-surface tracking-tight" }, toDisplayString(config.label), 1), createVNode("p", { class: "text-[10px] text-on-surface-variant/60 font-bold uppercase tracking-widest" }, toDisplayString(config.type === "redirect" ? "Redirección Bancaria" : "Pasarela de Pago"), 1)]),
								createVNode("span", { class: "material-symbols-outlined text-on-surface-variant/30 group-hover:translate-x-1 group-hover:text-primary transition-all" }, "chevron_right")
							], 10, ["onClick"]);
						}), 128))]),
						createVNode("footer", { class: "mt-8 pt-6 border-t border-outline-variant/20" }, [createVNode("p", { class: "text-[10px] text-center text-on-surface-variant/40 font-medium leading-relaxed" }, " Al proceder con el pago, aceptas nuestros términos y condiciones de servicio. ")])
					])];
				}),
				_: 1
			}, _parent));
		};
	}
});
//#endregion
//#region resources/js/Components/Payments/PaymentMethodModal.vue
var _sfc_setup = PaymentMethodModal_vue_vue_type_script_setup_true_lang_default.setup;
PaymentMethodModal_vue_vue_type_script_setup_true_lang_default.setup = (props, ctx) => {
	const ssrContext = useSSRContext();
	(ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/Payments/PaymentMethodModal.vue");
	return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
var PaymentMethodModal_default = PaymentMethodModal_vue_vue_type_script_setup_true_lang_default;
//#endregion
export { PaymentModule_default as n, PaymentMethodModal_default as t };

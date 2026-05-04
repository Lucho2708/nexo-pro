import { t as _plugin_vue_export_helper_default } from "./_plugin-vue_export-helper-BjJHhBxR.js";
import { t as AuthenticatedLayout_default } from "./AuthenticatedLayout-BYv1HA3l.js";
import { t as Card_default } from "./Card-C2-I3_la.js";
import { t as Button_default } from "./Button-5V-KYwxi.js";
import { t as Badge_default } from "./Badge-DWJ2qZRx.js";
import { t as useToast } from "./useToast-Dcf8ak3V.js";
import { t as Input_default } from "./Input-DWocsxNw.js";
import { t as Checkbox_default } from "./Checkbox-CgLIRqSZ.js";
import { Fragment, createBlock, createCommentVNode, createTextVNode, createVNode, defineComponent, openBlock, renderList, toDisplayString, unref, useSSRContext, withCtx } from "vue";
import { Head, useForm } from "@inertiajs/vue3";
import { ssrInterpolate, ssrRenderClass, ssrRenderComponent, ssrRenderList } from "vue/server-renderer";
//#region resources/js/Pages/Admin/Settings/Index.vue?vue&type=script&setup=true&lang.ts
var Index_vue_vue_type_script_setup_true_lang_default = /* @__PURE__ */ defineComponent({
	layout: AuthenticatedLayout_default,
	__name: "Index",
	__ssrInlineRender: true,
	props: {
		copropiedad: {},
		settings: {}
	},
	setup(__props) {
		const props = __props;
		useToast();
		const form = useForm({ settings: {
			payments_enabled: props.settings.payments_enabled ?? false,
			wompi_public_key: props.settings.wompi_public_key ?? "",
			wompi_private_key: props.settings.wompi_private_key ?? "",
			wompi_integrity_key: props.settings.wompi_integrity_key ?? "",
			wompi_webhook_secret: props.settings.wompi_webhook_secret ?? "",
			pqrs_enabled: props.settings.pqrs_enabled ?? true,
			reservas_enabled: props.settings.reservas_enabled ?? true,
			interes_mora: props.settings.interes_mora ?? 1.5,
			gateways: props.settings.gateways ?? {
				wompi: {
					enabled: false,
					label: "Wompi (PSE, Tarjetas, Nequi)",
					type: "gateway"
				},
				aval: {
					enabled: false,
					label: "Aval Pay Center",
					type: "redirect",
					url: ""
				},
				manual: {
					enabled: true,
					label: "Efectivo / Consignación",
					type: "instruction"
				}
			}
		} });
		return (_ctx, _push, _parent, _attrs) => {
			_push(`<!--[-->`);
			_push(ssrRenderComponent(unref(Head), { title: "Configuración — NEXO-PRO" }, null, _parent));
			_push(`<div class="space-y-12 pb-32 animate-in fade-in slide-in-from-bottom-4 duration-700" data-v-e7552da9><div class="flex flex-col md:flex-row md:items-end justify-between gap-8 px-1" data-v-e7552da9><div class="space-y-3" data-v-e7552da9><div class="flex items-center gap-3" data-v-e7552da9><div class="w-2 h-6 bg-primary rounded-full shadow-[0_0_10px_rgba(var(--primary),0.5)]" data-v-e7552da9></div><p class="text-[10px] font-black text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.4em] italic leading-none" data-v-e7552da9>Dispatcher de Parámetros Globales</p></div><h2 class="text-5xl font-black text-on-surface dark:text-white tracking-tighter uppercase italic leading-none" data-v-e7552da9>Sala de <span class="text-primary italic" data-v-e7552da9>Máquinas</span></h2><p class="text-[11px] font-bold text-on-surface-variant/40 dark:text-white/10 uppercase tracking-[0.2em] mt-2 leading-relaxed italic" data-v-e7552da9>Gestión de llaves de integración, módulos y reglas financieras del conjunto</p></div>`);
			_push(ssrRenderComponent(Badge_default, {
				variant: "primary",
				class: "!px-6 !py-2 !rounded-xl !text-[10px] font-black uppercase tracking-widest italic border-2"
			}, {
				default: withCtx((_, _push, _parent, _scopeId) => {
					if (_push) _push(`Copropiedad ID: ${ssrInterpolate(__props.copropiedad.id.split("-")[0])}`);
					else return [createTextVNode("Copropiedad ID: " + toDisplayString(__props.copropiedad.id.split("-")[0]), 1)];
				}),
				_: 1
			}, _parent));
			_push(`</div><form class="grid grid-cols-1 xl:grid-cols-12 gap-10 items-start" data-v-e7552da9><div class="xl:col-span-8 space-y-10" data-v-e7552da9>`);
			_push(ssrRenderComponent(Card_default, { class: "!p-10 !rounded-[3.5rem] border border-outline-variant/10 dark:border-white/5 shadow-3xl dark:bg-[#0b0e14]" }, {
				default: withCtx((_, _push, _parent, _scopeId) => {
					if (_push) {
						_push(`<div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10" data-v-e7552da9${_scopeId}><div class="flex items-center gap-5" data-v-e7552da9${_scopeId}><div class="w-14 h-14 bg-emerald-500/10 rounded-2xl flex items-center justify-center text-emerald-500 shadow-inner" data-v-e7552da9${_scopeId}><span class="material-symbols-rounded text-2xl" data-v-e7552da9${_scopeId}>payments</span></div><div data-v-e7552da9${_scopeId}><h3 class="text-2xl font-black text-on-surface dark:text-white uppercase tracking-tighter italic leading-none" data-v-e7552da9${_scopeId}>Pasarela <span class="text-emerald-500" data-v-e7552da9${_scopeId}>Wompi</span></h3><p class="text-[10px] font-bold text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.3em] mt-3 italic" data-v-e7552da9${_scopeId}>Integración oficial para recaudo automatizado</p></div></div><div class="flex gap-2" data-v-e7552da9${_scopeId}><div class="px-6 py-3 bg-emerald-500/10 rounded-2xl flex items-center gap-3 border border-emerald-500/20" data-v-e7552da9${_scopeId}>`);
						_push(ssrRenderComponent(Checkbox_default, {
							checked: unref(form).settings.payments_enabled,
							"onUpdate:checked": ($event) => unref(form).settings.payments_enabled = $event,
							class: "!w-6 !h-6"
						}, null, _parent, _scopeId));
						_push(`<span class="text-[10px] font-black text-emerald-500 uppercase tracking-widest" data-v-e7552da9${_scopeId}>SISTEMA ACTIVO</span></div></div></div>`);
						if (unref(form).settings.payments_enabled) {
							_push(`<div class="grid grid-cols-1 md:grid-cols-2 gap-8 pt-6 border-t border-outline-variant/5 dark:border-white/5 animate-in slide-in-from-top-4 duration-500" data-v-e7552da9${_scopeId}>`);
							_push(ssrRenderComponent(Input_default, {
								modelValue: unref(form).settings.wompi_public_key,
								"onUpdate:modelValue": ($event) => unref(form).settings.wompi_public_key = $event,
								label: "LLAVE PÚBLICA (PUB_TEST_...)",
								placeholder: "Ingrese su public key oficial",
								error: unref(form).errors["settings.wompi_public_key"],
								class: "!rounded-2xl"
							}, null, _parent, _scopeId));
							_push(ssrRenderComponent(Input_default, {
								modelValue: unref(form).settings.wompi_integrity_key,
								"onUpdate:modelValue": ($event) => unref(form).settings.wompi_integrity_key = $event,
								label: "LLAVE DE INTEGRIDAD",
								placeholder: "Ingrese su integrity key",
								error: unref(form).errors["settings.wompi_integrity_key"],
								class: "!rounded-2xl"
							}, null, _parent, _scopeId));
							_push(ssrRenderComponent(Input_default, {
								modelValue: unref(form).settings.wompi_private_key,
								"onUpdate:modelValue": ($event) => unref(form).settings.wompi_private_key = $event,
								label: "LLAVE PRIVADA (PRV_...)",
								type: "password",
								placeholder: "Ingrese su private key",
								error: unref(form).errors["settings.wompi_private_key"],
								class: "!rounded-2xl"
							}, null, _parent, _scopeId));
							_push(ssrRenderComponent(Input_default, {
								modelValue: unref(form).settings.wompi_webhook_secret,
								"onUpdate:modelValue": ($event) => unref(form).settings.wompi_webhook_secret = $event,
								label: "WEBHOOK SECRET",
								type: "password",
								placeholder: "Ingrese su webhook secret",
								error: unref(form).errors["settings.wompi_webhook_secret"],
								class: "!rounded-2xl"
							}, null, _parent, _scopeId));
							_push(`<div class="col-span-2 p-6 bg-primary/5 rounded-[2rem] border border-primary/20 flex gap-4 text-primary relative overflow-hidden group" data-v-e7552da9${_scopeId}><div class="absolute inset-0 bg-primary/5 translate-x-full group-hover:translate-x-0 transition-transform duration-[2s]" data-v-e7552da9${_scopeId}></div><span class="material-symbols-rounded shrink-0 relative z-10" data-v-e7552da9${_scopeId}>info</span><div class="relative z-10 space-y-2" data-v-e7552da9${_scopeId}><p class="text-[11px] font-black uppercase tracking-widest leading-none" data-v-e7552da9${_scopeId}>Protocolo de Redirección Webhook</p><p class="text-[9px] font-medium leading-relaxed italic" data-v-e7552da9${_scopeId}> Enlace obligatorio para Dash de Wompi: <span class="bg-primary/10 px-2 py-0.5 rounded italic select-all cursor-pointer" data-v-e7552da9${_scopeId}>https://${ssrInterpolate(_ctx.$page.props.auth.user.current_copropiedad?.domain || "nexo-pro.io")}/payments/webhook</span></p></div></div></div>`);
						} else _push(`<div class="py-12 text-center opacity-30 border-t border-outline-variant/10 dark:border-white/5 mt-6" data-v-e7552da9${_scopeId}><span class="material-symbols-rounded text-6xl" data-v-e7552da9${_scopeId}>lock_open</span><p class="text-[10px] font-black uppercase tracking-[0.4em] mt-4" data-v-e7552da9${_scopeId}>Integración de Pagos Desactivada</p></div>`);
					} else return [createVNode("div", { class: "flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10" }, [createVNode("div", { class: "flex items-center gap-5" }, [createVNode("div", { class: "w-14 h-14 bg-emerald-500/10 rounded-2xl flex items-center justify-center text-emerald-500 shadow-inner" }, [createVNode("span", { class: "material-symbols-rounded text-2xl" }, "payments")]), createVNode("div", null, [createVNode("h3", { class: "text-2xl font-black text-on-surface dark:text-white uppercase tracking-tighter italic leading-none" }, [createTextVNode("Pasarela "), createVNode("span", { class: "text-emerald-500" }, "Wompi")]), createVNode("p", { class: "text-[10px] font-bold text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.3em] mt-3 italic" }, "Integración oficial para recaudo automatizado")])]), createVNode("div", { class: "flex gap-2" }, [createVNode("div", { class: "px-6 py-3 bg-emerald-500/10 rounded-2xl flex items-center gap-3 border border-emerald-500/20" }, [createVNode(Checkbox_default, {
						checked: unref(form).settings.payments_enabled,
						"onUpdate:checked": ($event) => unref(form).settings.payments_enabled = $event,
						class: "!w-6 !h-6"
					}, null, 8, ["checked", "onUpdate:checked"]), createVNode("span", { class: "text-[10px] font-black text-emerald-500 uppercase tracking-widest" }, "SISTEMA ACTIVO")])])]), unref(form).settings.payments_enabled ? (openBlock(), createBlock("div", {
						key: 0,
						class: "grid grid-cols-1 md:grid-cols-2 gap-8 pt-6 border-t border-outline-variant/5 dark:border-white/5 animate-in slide-in-from-top-4 duration-500"
					}, [
						createVNode(Input_default, {
							modelValue: unref(form).settings.wompi_public_key,
							"onUpdate:modelValue": ($event) => unref(form).settings.wompi_public_key = $event,
							label: "LLAVE PÚBLICA (PUB_TEST_...)",
							placeholder: "Ingrese su public key oficial",
							error: unref(form).errors["settings.wompi_public_key"],
							class: "!rounded-2xl"
						}, null, 8, [
							"modelValue",
							"onUpdate:modelValue",
							"error"
						]),
						createVNode(Input_default, {
							modelValue: unref(form).settings.wompi_integrity_key,
							"onUpdate:modelValue": ($event) => unref(form).settings.wompi_integrity_key = $event,
							label: "LLAVE DE INTEGRIDAD",
							placeholder: "Ingrese su integrity key",
							error: unref(form).errors["settings.wompi_integrity_key"],
							class: "!rounded-2xl"
						}, null, 8, [
							"modelValue",
							"onUpdate:modelValue",
							"error"
						]),
						createVNode(Input_default, {
							modelValue: unref(form).settings.wompi_private_key,
							"onUpdate:modelValue": ($event) => unref(form).settings.wompi_private_key = $event,
							label: "LLAVE PRIVADA (PRV_...)",
							type: "password",
							placeholder: "Ingrese su private key",
							error: unref(form).errors["settings.wompi_private_key"],
							class: "!rounded-2xl"
						}, null, 8, [
							"modelValue",
							"onUpdate:modelValue",
							"error"
						]),
						createVNode(Input_default, {
							modelValue: unref(form).settings.wompi_webhook_secret,
							"onUpdate:modelValue": ($event) => unref(form).settings.wompi_webhook_secret = $event,
							label: "WEBHOOK SECRET",
							type: "password",
							placeholder: "Ingrese su webhook secret",
							error: unref(form).errors["settings.wompi_webhook_secret"],
							class: "!rounded-2xl"
						}, null, 8, [
							"modelValue",
							"onUpdate:modelValue",
							"error"
						]),
						createVNode("div", { class: "col-span-2 p-6 bg-primary/5 rounded-[2rem] border border-primary/20 flex gap-4 text-primary relative overflow-hidden group" }, [
							createVNode("div", { class: "absolute inset-0 bg-primary/5 translate-x-full group-hover:translate-x-0 transition-transform duration-[2s]" }),
							createVNode("span", { class: "material-symbols-rounded shrink-0 relative z-10" }, "info"),
							createVNode("div", { class: "relative z-10 space-y-2" }, [createVNode("p", { class: "text-[11px] font-black uppercase tracking-widest leading-none" }, "Protocolo de Redirección Webhook"), createVNode("p", { class: "text-[9px] font-medium leading-relaxed italic" }, [createTextVNode(" Enlace obligatorio para Dash de Wompi: "), createVNode("span", { class: "bg-primary/10 px-2 py-0.5 rounded italic select-all cursor-pointer" }, "https://" + toDisplayString(_ctx.$page.props.auth.user.current_copropiedad?.domain || "nexo-pro.io") + "/payments/webhook", 1)])])
						])
					])) : (openBlock(), createBlock("div", {
						key: 1,
						class: "py-12 text-center opacity-30 border-t border-outline-variant/10 dark:border-white/5 mt-6"
					}, [createVNode("span", { class: "material-symbols-rounded text-6xl" }, "lock_open"), createVNode("p", { class: "text-[10px] font-black uppercase tracking-[0.4em] mt-4" }, "Integración de Pagos Desactivada")]))];
				}),
				_: 1
			}, _parent));
			if (unref(form).settings.payments_enabled) _push(ssrRenderComponent(Card_default, { class: "!rounded-[3.5rem] border border-outline-variant/10 dark:border-white/5 shadow-2xl dark:bg-[#0b0e14] overflow-hidden" }, {
				header: withCtx((_, _push, _parent, _scopeId) => {
					if (_push) _push(`<div class="p-10 border-b border-outline-variant/5 dark:border-white/5 flex items-center justify-between" data-v-e7552da9${_scopeId}><div data-v-e7552da9${_scopeId}><h3 class="text-2xl font-black text-on-surface dark:text-white uppercase tracking-tighter italic leading-none" data-v-e7552da9${_scopeId}>Métodos de <span class="text-primary" data-v-e7552da9${_scopeId}>Fondeo</span></h3><p class="text-[10px] font-bold text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.3em] mt-3 italic" data-v-e7552da9${_scopeId}>Gestión de canales de pago visibles para el residente</p></div></div>`);
					else return [createVNode("div", { class: "p-10 border-b border-outline-variant/5 dark:border-white/5 flex items-center justify-between" }, [createVNode("div", null, [createVNode("h3", { class: "text-2xl font-black text-on-surface dark:text-white uppercase tracking-tighter italic leading-none" }, [createTextVNode("Métodos de "), createVNode("span", { class: "text-primary" }, "Fondeo")]), createVNode("p", { class: "text-[10px] font-bold text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.3em] mt-3 italic" }, "Gestión de canales de pago visibles para el residente")])])];
				}),
				default: withCtx((_, _push, _parent, _scopeId) => {
					if (_push) {
						_push(`<div class="divide-y divide-outline-variant/5 dark:divide-white/5" data-v-e7552da9${_scopeId}><!--[-->`);
						ssrRenderList(unref(form).settings.gateways, (gateway, key) => {
							_push(`<div class="p-8 hover:bg-surface-container-low dark:hover:bg-white/5 transition-all flex flex-col md:flex-row md:items-center justify-between gap-6 group" data-v-e7552da9${_scopeId}><div class="flex items-center gap-6" data-v-e7552da9${_scopeId}><div class="w-14 h-14 rounded-2xl bg-white dark:bg-white/5 flex items-center justify-center text-on-surface-variant border border-outline-variant/10 group-hover:bg-primary group-hover:text-white transition-all shadow-xl shadow-black/5" data-v-e7552da9${_scopeId}><span class="material-symbols-rounded text-2xl" data-v-e7552da9${_scopeId}>${ssrInterpolate(key === "wompi" ? "account_balance" : key === "aval" ? "account_balance_wallet" : "payments")}</span></div><div data-v-e7552da9${_scopeId}><h4 class="text-base font-black text-on-surface dark:text-white uppercase tracking-tighter italic leading-none" data-v-e7552da9${_scopeId}>${ssrInterpolate(gateway.label)}</h4><p class="text-[9px] font-bold text-on-surface-variant/40 dark:text-white/20 uppercase tracking-widest mt-2 italic" data-v-e7552da9${_scopeId}>${ssrInterpolate(key === "manuel" ? "REGISTRO DE SOPORTE FÍSICO" : "CANAL DE PAGO DIGITAL")}</p></div></div><div class="flex items-center gap-8" data-v-e7552da9${_scopeId}>`);
							if (gateway.enabled && key === "aval") {
								_push(`<div class="animate-in slide-in-from-right-4" data-v-e7552da9${_scopeId}>`);
								_push(ssrRenderComponent(Input_default, {
									modelValue: gateway.url,
									"onUpdate:modelValue": ($event) => gateway.url = $event,
									label: "URL POINT-OF-SALE",
									placeholder: "https://...",
									error: unref(form).errors[`settings.gateways.${key}.url`],
									class: "!rounded-xl !h-12 !w-64"
								}, null, _parent, _scopeId));
								_push(`</div>`);
							} else _push(`<!---->`);
							_push(`<div class="flex items-center gap-4 bg-surface-container-high dark:bg-white/5 px-6 py-4 rounded-2xl border border-outline-variant/10 group-hover:border-primary/20 transition-all" data-v-e7552da9${_scopeId}><span class="${ssrRenderClass([gateway.enabled ? "text-primary" : "text-on-surface-variant/40", "text-[9px] font-black uppercase tracking-widest"])}" data-v-e7552da9${_scopeId}>${ssrInterpolate(gateway.enabled ? "HABILITADO" : "INACTIVO")}</span>`);
							_push(ssrRenderComponent(Checkbox_default, {
								checked: gateway.enabled,
								"onUpdate:checked": ($event) => gateway.enabled = $event,
								class: "!w-6 !h-6"
							}, null, _parent, _scopeId));
							_push(`</div></div></div>`);
						});
						_push(`<!--]--></div>`);
					} else return [createVNode("div", { class: "divide-y divide-outline-variant/5 dark:divide-white/5" }, [(openBlock(true), createBlock(Fragment, null, renderList(unref(form).settings.gateways, (gateway, key) => {
						return openBlock(), createBlock("div", {
							key,
							class: "p-8 hover:bg-surface-container-low dark:hover:bg-white/5 transition-all flex flex-col md:flex-row md:items-center justify-between gap-6 group"
						}, [createVNode("div", { class: "flex items-center gap-6" }, [createVNode("div", { class: "w-14 h-14 rounded-2xl bg-white dark:bg-white/5 flex items-center justify-center text-on-surface-variant border border-outline-variant/10 group-hover:bg-primary group-hover:text-white transition-all shadow-xl shadow-black/5" }, [createVNode("span", { class: "material-symbols-rounded text-2xl" }, toDisplayString(key === "wompi" ? "account_balance" : key === "aval" ? "account_balance_wallet" : "payments"), 1)]), createVNode("div", null, [createVNode("h4", { class: "text-base font-black text-on-surface dark:text-white uppercase tracking-tighter italic leading-none" }, toDisplayString(gateway.label), 1), createVNode("p", { class: "text-[9px] font-bold text-on-surface-variant/40 dark:text-white/20 uppercase tracking-widest mt-2 italic" }, toDisplayString(key === "manuel" ? "REGISTRO DE SOPORTE FÍSICO" : "CANAL DE PAGO DIGITAL"), 1)])]), createVNode("div", { class: "flex items-center gap-8" }, [gateway.enabled && key === "aval" ? (openBlock(), createBlock("div", {
							key: 0,
							class: "animate-in slide-in-from-right-4"
						}, [createVNode(Input_default, {
							modelValue: gateway.url,
							"onUpdate:modelValue": ($event) => gateway.url = $event,
							label: "URL POINT-OF-SALE",
							placeholder: "https://...",
							error: unref(form).errors[`settings.gateways.${key}.url`],
							class: "!rounded-xl !h-12 !w-64"
						}, null, 8, [
							"modelValue",
							"onUpdate:modelValue",
							"error"
						])])) : createCommentVNode("", true), createVNode("div", { class: "flex items-center gap-4 bg-surface-container-high dark:bg-white/5 px-6 py-4 rounded-2xl border border-outline-variant/10 group-hover:border-primary/20 transition-all" }, [createVNode("span", { class: ["text-[9px] font-black uppercase tracking-widest", gateway.enabled ? "text-primary" : "text-on-surface-variant/40"] }, toDisplayString(gateway.enabled ? "HABILITADO" : "INACTIVO"), 3), createVNode(Checkbox_default, {
							checked: gateway.enabled,
							"onUpdate:checked": ($event) => gateway.enabled = $event,
							class: "!w-6 !h-6"
						}, null, 8, ["checked", "onUpdate:checked"])])])]);
					}), 128))])];
				}),
				_: 1
			}, _parent));
			else _push(`<!---->`);
			_push(`</div><div class="xl:col-span-4 space-y-10" data-v-e7552da9>`);
			_push(ssrRenderComponent(Card_default, { class: "!p-10 !rounded-[3.5rem] border border-outline-variant/10 dark:border-white/5 shadow-2xl dark:bg-[#0b0e14]" }, {
				default: withCtx((_, _push, _parent, _scopeId) => {
					if (_push) {
						_push(`<h3 class="text-xl font-black text-on-surface dark:text-white uppercase tracking-tighter italic leading-none mb-8" data-v-e7552da9${_scopeId}>Hardware de <span class="text-primary italic" data-v-e7552da9${_scopeId}>Módulos</span></h3><div class="space-y-6" data-v-e7552da9${_scopeId}><div class="flex items-center justify-between p-6 rounded-[2rem] bg-surface-container-low dark:bg-white/[0.02] border border-outline-variant/10 dark:border-white/5 hover:border-primary/30 transition-all group" data-v-e7552da9${_scopeId}><div class="flex items-center gap-4" data-v-e7552da9${_scopeId}><div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center text-primary" data-v-e7552da9${_scopeId}><span class="material-symbols-rounded text-xl" data-v-e7552da9${_scopeId}>forum</span></div><span class="text-[11px] font-black text-on-surface-variant dark:text-white/40 uppercase tracking-widest italic group-hover:text-primary transition-colors" data-v-e7552da9${_scopeId}>Integración PQRS</span></div>`);
						_push(ssrRenderComponent(Checkbox_default, {
							checked: unref(form).settings.pqrs_enabled,
							"onUpdate:checked": ($event) => unref(form).settings.pqrs_enabled = $event
						}, null, _parent, _scopeId));
						_push(`</div><div class="flex items-center justify-between p-6 rounded-[2rem] bg-surface-container-low dark:bg-white/[0.02] border border-outline-variant/10 dark:border-white/5 hover:border-primary/30 transition-all group" data-v-e7552da9${_scopeId}><div class="flex items-center gap-4" data-v-e7552da9${_scopeId}><div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center text-primary" data-v-e7552da9${_scopeId}><span class="material-symbols-rounded text-xl" data-v-e7552da9${_scopeId}>event_available</span></div><span class="text-[11px] font-black text-on-surface-variant dark:text-white/40 uppercase tracking-widest italic group-hover:text-primary transition-colors" data-v-e7552da9${_scopeId}>Sistema Reservas</span></div>`);
						_push(ssrRenderComponent(Checkbox_default, {
							checked: unref(form).settings.reservas_enabled,
							"onUpdate:checked": ($event) => unref(form).settings.reservas_enabled = $event
						}, null, _parent, _scopeId));
						_push(`</div></div><div class="mt-12 pt-8 border-t border-outline-variant/5 dark:border-white/5 space-y-6" data-v-e7552da9${_scopeId}><div class="flex items-end gap-4" data-v-e7552da9${_scopeId}><div class="flex-1" data-v-e7552da9${_scopeId}>`);
						_push(ssrRenderComponent(Input_default, {
							modelValue: unref(form).settings.interes_mora,
							"onUpdate:modelValue": ($event) => unref(form).settings.interes_mora = $event,
							type: "number",
							step: "0.01",
							label: "COEFICIENTE DE MORA (%)",
							suffix: "%",
							class: "!rounded-2xl"
						}, null, _parent, _scopeId));
						_push(`</div><div class="w-12 h-14 bg-surface-container-high dark:bg-white/5 flex items-center justify-center rounded-2xl border border-outline-variant/10" data-v-e7552da9${_scopeId}><span class="material-symbols-rounded text-xl opacity-20" data-v-e7552da9${_scopeId}>trending_up</span></div></div><p class="text-[9px] font-bold text-on-surface-variant/40 dark:text-white/20 uppercase tracking-widest leading-relaxed italic pr-6 italic px-2" data-v-e7552da9${_scopeId}> * Este vector porcentual afectará automáticamente los balances financieros al cierre de cada ciclo. </p></div>`);
					} else return [
						createVNode("h3", { class: "text-xl font-black text-on-surface dark:text-white uppercase tracking-tighter italic leading-none mb-8" }, [createTextVNode("Hardware de "), createVNode("span", { class: "text-primary italic" }, "Módulos")]),
						createVNode("div", { class: "space-y-6" }, [createVNode("div", { class: "flex items-center justify-between p-6 rounded-[2rem] bg-surface-container-low dark:bg-white/[0.02] border border-outline-variant/10 dark:border-white/5 hover:border-primary/30 transition-all group" }, [createVNode("div", { class: "flex items-center gap-4" }, [createVNode("div", { class: "w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center text-primary" }, [createVNode("span", { class: "material-symbols-rounded text-xl" }, "forum")]), createVNode("span", { class: "text-[11px] font-black text-on-surface-variant dark:text-white/40 uppercase tracking-widest italic group-hover:text-primary transition-colors" }, "Integración PQRS")]), createVNode(Checkbox_default, {
							checked: unref(form).settings.pqrs_enabled,
							"onUpdate:checked": ($event) => unref(form).settings.pqrs_enabled = $event
						}, null, 8, ["checked", "onUpdate:checked"])]), createVNode("div", { class: "flex items-center justify-between p-6 rounded-[2rem] bg-surface-container-low dark:bg-white/[0.02] border border-outline-variant/10 dark:border-white/5 hover:border-primary/30 transition-all group" }, [createVNode("div", { class: "flex items-center gap-4" }, [createVNode("div", { class: "w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center text-primary" }, [createVNode("span", { class: "material-symbols-rounded text-xl" }, "event_available")]), createVNode("span", { class: "text-[11px] font-black text-on-surface-variant dark:text-white/40 uppercase tracking-widest italic group-hover:text-primary transition-colors" }, "Sistema Reservas")]), createVNode(Checkbox_default, {
							checked: unref(form).settings.reservas_enabled,
							"onUpdate:checked": ($event) => unref(form).settings.reservas_enabled = $event
						}, null, 8, ["checked", "onUpdate:checked"])])]),
						createVNode("div", { class: "mt-12 pt-8 border-t border-outline-variant/5 dark:border-white/5 space-y-6" }, [createVNode("div", { class: "flex items-end gap-4" }, [createVNode("div", { class: "flex-1" }, [createVNode(Input_default, {
							modelValue: unref(form).settings.interes_mora,
							"onUpdate:modelValue": ($event) => unref(form).settings.interes_mora = $event,
							type: "number",
							step: "0.01",
							label: "COEFICIENTE DE MORA (%)",
							suffix: "%",
							class: "!rounded-2xl"
						}, null, 8, ["modelValue", "onUpdate:modelValue"])]), createVNode("div", { class: "w-12 h-14 bg-surface-container-high dark:bg-white/5 flex items-center justify-center rounded-2xl border border-outline-variant/10" }, [createVNode("span", { class: "material-symbols-rounded text-xl opacity-20" }, "trending_up")])]), createVNode("p", { class: "text-[9px] font-bold text-on-surface-variant/40 dark:text-white/20 uppercase tracking-widest leading-relaxed italic pr-6 italic px-2" }, " * Este vector porcentual afectará automáticamente los balances financieros al cierre de cada ciclo. ")])
					];
				}),
				_: 1
			}, _parent));
			_push(`<div class="bg-surface-container-highest dark:bg-white/5 p-10 rounded-[3.5rem] border border-outline-variant/10 flex flex-col gap-6" data-v-e7552da9><h4 class="text-xs font-black text-on-surface dark:text-white uppercase tracking-[0.3em] text-center italic" data-v-e7552da9>Despachador Maestro</h4>`);
			_push(ssrRenderComponent(Button_default, {
				type: "submit",
				variant: "primary",
				size: "lg",
				class: "w-full !h-16 !rounded-2xl !text-[12px] font-black uppercase italic shadow-2xl shadow-primary/20",
				loading: unref(form).processing,
				icon: "save"
			}, {
				default: withCtx((_, _push, _parent, _scopeId) => {
					if (_push) _push(`${ssrInterpolate(unref(form).processing ? "SINCRONIZANDO..." : "Sincronizar Kernels")}`);
					else return [createTextVNode(toDisplayString(unref(form).processing ? "SINCRONIZANDO..." : "Sincronizar Kernels"), 1)];
				}),
				_: 1
			}, _parent));
			_push(`<p class="text-[8px] font-bold text-on-surface-variant/30 dark:text-white/10 uppercase tracking-widest text-center mt-2 leading-relaxed" data-v-e7552da9> LOS CAMBIOS SON APLICADOS DE FORMA PERMANENTE EN EL NÚCLEO DE LA COPROPIEDAD. VERIFIQUE CADA LLAVE DE API. </p></div></div></form></div><!--]-->`);
		};
	}
});
//#endregion
//#region resources/js/Pages/Admin/Settings/Index.vue
var _sfc_setup = Index_vue_vue_type_script_setup_true_lang_default.setup;
Index_vue_vue_type_script_setup_true_lang_default.setup = (props, ctx) => {
	const ssrContext = useSSRContext();
	(ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Admin/Settings/Index.vue");
	return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
var Index_default = /* @__PURE__ */ _plugin_vue_export_helper_default(Index_vue_vue_type_script_setup_true_lang_default, [["__scopeId", "data-v-e7552da9"]]);
//#endregion
export { Index_default as default };

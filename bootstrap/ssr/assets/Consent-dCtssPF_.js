import { t as _plugin_vue_export_helper_default } from "./_plugin-vue_export-helper-BjJHhBxR.js";
import { t as Logo_default } from "./Logo-BJuTBUmx.js";
import { t as Card_default } from "./Card-C2-I3_la.js";
import { t as Button_default } from "./Button-5V-KYwxi.js";
import { createTextVNode, createVNode, defineComponent, toDisplayString, unref, useSSRContext, withCtx } from "vue";
import { Head, useForm } from "@inertiajs/vue3";
import { ssrInterpolate, ssrRenderComponent } from "vue/server-renderer";
//#region resources/js/Pages/Legal/Consent.vue?vue&type=script&setup=true&lang.ts
var Consent_vue_vue_type_script_setup_true_lang_default = /* @__PURE__ */ defineComponent({
	__name: "Consent",
	__ssrInlineRender: true,
	props: {
		document: {},
		type: {}
	},
	setup(__props) {
		const props = __props;
		const form = useForm({});
		const accept = () => {
			form.post(route("legal.accept", props.document.id));
		};
		return (_ctx, _push, _parent, _attrs) => {
			_push(`<!--[-->`);
			_push(ssrRenderComponent(unref(Head), { title: __props.document.title }, null, _parent));
			_push(`<div class="min-h-screen bg-slate-50 dark:bg-slate-950 flex flex-col items-center justify-center p-4 md:p-8" data-v-e0fa4bfe><div class="w-full max-w-3xl space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-700" data-v-e0fa4bfe><div class="flex flex-col items-center text-center space-y-4" data-v-e0fa4bfe>`);
			_push(ssrRenderComponent(Logo_default, { class: "h-12 w-auto" }, null, _parent));
			_push(`<h1 class="text-2xl md:text-3xl font-display font-bold text-slate-900 dark:text-white leading-tight" data-v-e0fa4bfe>${ssrInterpolate(__props.document.title)}</h1><p class="text-slate-500 max-w-md text-sm md:text-base" data-v-e0fa4bfe> Para continuar utilizando NEXO-PRO, por favor revisa y acepta la actualización de nuestra <span class="font-bold text-brand-600 dark:text-brand-400" data-v-e0fa4bfe>${ssrInterpolate(__props.type === "terms" ? "Términos y Condiciones" : "Política de Privacidad")}</span>. </p></div>`);
			_push(ssrRenderComponent(Card_default, {
				variant: "flat",
				class: "overflow-hidden border-slate-200 dark:border-slate-800 !rounded-3xl shadow-xl shadow-brand-500/5"
			}, {
				default: withCtx((_, _push, _parent, _scopeId) => {
					if (_push) {
						_push(`<div class="max-h-[50vh] overflow-y-auto p-6 md:p-10 text-slate-700 dark:text-slate-300 font-sans leading-relaxed text-sm md:text-base" data-v-e0fa4bfe${_scopeId}><div class="whitespace-pre-wrap" data-v-e0fa4bfe${_scopeId}>${ssrInterpolate(__props.document.body)}</div></div><div class="p-6 md:p-8 bg-slate-50/50 dark:bg-slate-900/50 border-t border-slate-200 dark:border-slate-800 flex flex-col md:flex-row items-center justify-between gap-6" data-v-e0fa4bfe${_scopeId}><div class="flex flex-col" data-v-e0fa4bfe${_scopeId}><span class="text-[10px] uppercase tracking-widest font-black text-slate-400" data-v-e0fa4bfe${_scopeId}>Estado de cumplimiento</span><div class="text-sm text-slate-600 dark:text-slate-400 font-medium" data-v-e0fa4bfe${_scopeId}> Versión ${ssrInterpolate(__props.document.version)} · <span class="text-brand-500" data-v-e0fa4bfe${_scopeId}>Habeas Data Activo</span></div></div>`);
						_push(ssrRenderComponent(Button_default, {
							onClick: accept,
							variant: "primary",
							size: "lg",
							class: "w-full md:w-auto px-10 !rounded-2xl",
							loading: unref(form).processing,
							icon: "check_circle"
						}, {
							default: withCtx((_, _push, _parent, _scopeId) => {
								if (_push) _push(` Acepto y deseo continuar `);
								else return [createTextVNode(" Acepto y deseo continuar ")];
							}),
							_: 1
						}, _parent, _scopeId));
						_push(`</div>`);
					} else return [createVNode("div", { class: "max-h-[50vh] overflow-y-auto p-6 md:p-10 text-slate-700 dark:text-slate-300 font-sans leading-relaxed text-sm md:text-base" }, [createVNode("div", { class: "whitespace-pre-wrap" }, toDisplayString(__props.document.body), 1)]), createVNode("div", { class: "p-6 md:p-8 bg-slate-50/50 dark:bg-slate-900/50 border-t border-slate-200 dark:border-slate-800 flex flex-col md:flex-row items-center justify-between gap-6" }, [createVNode("div", { class: "flex flex-col" }, [createVNode("span", { class: "text-[10px] uppercase tracking-widest font-black text-slate-400" }, "Estado de cumplimiento"), createVNode("div", { class: "text-sm text-slate-600 dark:text-slate-400 font-medium" }, [createTextVNode(" Versión " + toDisplayString(__props.document.version) + " · ", 1), createVNode("span", { class: "text-brand-500" }, "Habeas Data Activo")])]), createVNode(Button_default, {
						onClick: accept,
						variant: "primary",
						size: "lg",
						class: "w-full md:w-auto px-10 !rounded-2xl",
						loading: unref(form).processing,
						icon: "check_circle"
					}, {
						default: withCtx(() => [createTextVNode(" Acepto y deseo continuar ")]),
						_: 1
					}, 8, ["loading"])])];
				}),
				_: 1
			}, _parent));
			_push(`<p class="text-center text-[10px] md:text-xs text-slate-400 max-w-md mx-auto leading-relaxed" data-v-e0fa4bfe> Al hacer clic en aceptar, confirmas que has leído y comprendes el documento legal arriba mencionado. Tus datos están protegidos bajo la <span class="font-bold" data-v-e0fa4bfe>Ley 1581 de 2012</span> de la República de Colombia. </p></div></div><!--]-->`);
		};
	}
});
//#endregion
//#region resources/js/Pages/Legal/Consent.vue
var _sfc_setup = Consent_vue_vue_type_script_setup_true_lang_default.setup;
Consent_vue_vue_type_script_setup_true_lang_default.setup = (props, ctx) => {
	const ssrContext = useSSRContext();
	(ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Legal/Consent.vue");
	return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
var Consent_default = /* @__PURE__ */ _plugin_vue_export_helper_default(Consent_vue_vue_type_script_setup_true_lang_default, [["__scopeId", "data-v-e0fa4bfe"]]);
//#endregion
export { Consent_default as default };

import { t as _plugin_vue_export_helper_default } from "./_plugin-vue_export-helper-BjJHhBxR.js";
import { t as Button_default } from "./Button-C-byZbTR.js";
import { createTextVNode, defineComponent, unref, useSSRContext, withCtx } from "vue";
import { Head, Link } from "@inertiajs/vue3";
import { ssrInterpolate, ssrRenderComponent, ssrRenderStyle } from "vue/server-renderer";
//#region resources/js/Pages/Error/LicenseSuspended.vue?vue&type=script&setup=true&lang.ts
var LicenseSuspended_vue_vue_type_script_setup_true_lang_default = /* @__PURE__ */ defineComponent({
	__name: "LicenseSuspended",
	__ssrInlineRender: true,
	props: { message: {} },
	setup(__props) {
		return (_ctx, _push, _parent, _attrs) => {
			_push(`<!--[-->`);
			_push(ssrRenderComponent(unref(Head), { title: "Servicio Suspendido — PH360" }, null, _parent));
			_push(`<div class="min-h-screen bg-surface flex flex-col items-center justify-center p-6 text-center" data-v-2079f367><div class="max-w-md space-y-8 animate-in fade-in zoom-in-95 duration-700" data-v-2079f367><div class="w-24 h-24 bg-error/10 text-error rounded-[2.5rem] flex items-center justify-center mx-auto shadow-xl shadow-error/10 border border-error/10" data-v-2079f367><span class="material-symbols-outlined text-5xl" style="${ssrRenderStyle({ "font-variation-settings": "'FILL' 1" })}" data-v-2079f367>gavel</span></div><div class="space-y-3" data-v-2079f367><h1 class="text-3xl font-black text-primary tracking-tighter uppercase" data-v-2079f367>Servicio Restringido</h1><p class="text-sm font-medium text-on-surface-variant/70 leading-relaxed" data-v-2079f367>${ssrInterpolate(__props.message)}</p></div><div class="bg-surface-container-low p-6 rounded-3xl border border-outline-variant/10 space-y-4" data-v-2079f367><p class="text-[10px] font-black text-on-surface-variant/40 uppercase tracking-widest leading-tight" data-v-2079f367> Si eres el administrador, puedes contactar a nuestro equipo comercial para regularizar tu situación. </p><div class="flex flex-col gap-3" data-v-2079f367>`);
			_push(ssrRenderComponent(Button_default, {
				variant: "primary",
				class: "w-full",
				icon: "support_agent"
			}, {
				default: withCtx((_, _push, _parent, _scopeId) => {
					if (_push) _push(`Contactar Soporte`);
					else return [createTextVNode("Contactar Soporte")];
				}),
				_: 1
			}, _parent));
			_push(ssrRenderComponent(unref(Link), {
				href: _ctx.route("logout"),
				method: "post",
				as: "button",
				class: "text-[10px] font-black text-primary uppercase tracking-widest hover:underline"
			}, {
				default: withCtx((_, _push, _parent, _scopeId) => {
					if (_push) _push(`Cerrar Sesión`);
					else return [createTextVNode("Cerrar Sesión")];
				}),
				_: 1
			}, _parent));
			_push(`</div></div><div class="pt-8 opacity-20" data-v-2079f367><p class="text-[9px] font-black uppercase tracking-[0.3em]" data-v-2079f367>Nexo-Pro Platform · Security Shield</p></div></div></div><!--]-->`);
		};
	}
});
//#endregion
//#region resources/js/Pages/Error/LicenseSuspended.vue
var _sfc_setup = LicenseSuspended_vue_vue_type_script_setup_true_lang_default.setup;
LicenseSuspended_vue_vue_type_script_setup_true_lang_default.setup = (props, ctx) => {
	const ssrContext = useSSRContext();
	(ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Error/LicenseSuspended.vue");
	return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
var LicenseSuspended_default = /* @__PURE__ */ _plugin_vue_export_helper_default(LicenseSuspended_vue_vue_type_script_setup_true_lang_default, [["__scopeId", "data-v-2079f367"]]);
//#endregion
export { LicenseSuspended_default as default };

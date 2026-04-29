import { t as _plugin_vue_export_helper_default } from "./_plugin-vue_export-helper-BjJHhBxR.js";
import { t as Button_default } from "./Button-C-byZbTR.js";
import { createTextVNode, createVNode, defineComponent, unref, useSSRContext, withCtx } from "vue";
import { Head, Link, router } from "@inertiajs/vue3";
import { ssrInterpolate, ssrRenderComponent } from "vue/server-renderer";
//#region resources/js/Pages/Asamblea/AccessDenied.vue?vue&type=script&setup=true&lang.ts
var AccessDenied_vue_vue_type_script_setup_true_lang_default = /* @__PURE__ */ defineComponent({
	__name: "AccessDenied",
	__ssrInlineRender: true,
	props: {
		asamblea: {},
		unidad: {},
		message: {},
		can_reset: { type: Boolean }
	},
	setup(__props) {
		const props = __props;
		const resetConnection = () => {
			router.post(route("asambleas.reset-connection", props.asamblea.id), {}, { onSuccess: () => {
				window.location.href = route("asambleas.show", props.asamblea.id);
			} });
		};
		return (_ctx, _push, _parent, _attrs) => {
			_push(`<!--[-->`);
			_push(ssrRenderComponent(unref(Head), { title: "Acceso Denegado — Nexo-Pro" }, null, _parent));
			_push(`<div class="min-h-screen bg-[#00173c] flex items-center justify-center p-6 relative overflow-hidden" data-v-8390faed><div class="absolute top-0 left-0 w-full h-full opacity-10" data-v-8390faed><div class="absolute -top-24 -left-24 w-96 h-96 bg-primary rounded-full blur-[120px]" data-v-8390faed></div><div class="absolute -bottom-24 -right-24 w-96 h-96 bg-secondary rounded-full blur-[120px]" data-v-8390faed></div></div><div class="max-w-md w-full relative z-10 animate-fade-in-up" data-v-8390faed><div class="text-center mb-8" data-v-8390faed><div class="w-20 h-20 bg-white/10 backdrop-blur-xl border border-white/20 rounded-[2rem] flex items-center justify-center mx-auto mb-6 shadow-2xl" data-v-8390faed><span class="material-symbols-rounded text-4xl text-amber-400 animate-pulse" data-v-8390faed>devices_off</span></div><h1 class="text-2xl font-black text-white uppercase tracking-tighter mb-2" data-v-8390faed>Conexión Duplicada</h1><p class="text-white/60 text-sm font-medium leading-relaxed px-4" data-v-8390faed>${ssrInterpolate(__props.message)}</p></div><div class="bg-white/5 backdrop-blur-2xl border border-white/10 rounded-[2.5rem] p-8 shadow-3xl" data-v-8390faed><div class="flex items-center gap-4 p-4 bg-white/5 rounded-2xl border border-white/10 mb-8" data-v-8390faed><div class="w-10 h-10 rounded-xl bg-primary/20 flex items-center justify-center" data-v-8390faed><span class="material-symbols-rounded text-primary" data-v-8390faed>apartment</span></div><div class="text-left" data-v-8390faed><p class="text-[10px] font-black text-white/40 uppercase tracking-widest" data-v-8390faed>Unidad Detectada</p><p class="text-sm font-bold text-white uppercase" data-v-8390faed>${ssrInterpolate(__props.unidad.torre)} - ${ssrInterpolate(__props.unidad.nombre)}</p></div></div><div class="space-y-4" data-v-8390faed>`);
			if (__props.can_reset) {
				_push(`<!--[-->`);
				_push(ssrRenderComponent(Button_default, {
					variant: "primary",
					size: "lg",
					icon: "sync_alt",
					class: "w-full !bg-amber-500 hover:!bg-amber-600 !text-white border-0 shadow-lg shadow-amber-500/20",
					onClick: resetConnection
				}, {
					default: withCtx((_, _push, _parent, _scopeId) => {
						if (_push) _push(` Ingresar aquí (Cerrar otra sesión) `);
						else return [createTextVNode(" Ingresar aquí (Cerrar otra sesión) ")];
					}),
					_: 1
				}, _parent));
				_push(`<p class="text-[10px] text-center text-white/30 font-bold uppercase tracking-wider" data-v-8390faed> Esto desconectará cualquier otro dispositivo de esta unidad. </p><!--]-->`);
			} else _push(`<!---->`);
			_push(ssrRenderComponent(unref(Link), {
				href: _ctx.route("owner.dashboard"),
				class: "block"
			}, {
				default: withCtx((_, _push, _parent, _scopeId) => {
					if (_push) _push(ssrRenderComponent(Button_default, {
						variant: "ghost",
						size: "lg",
						icon: "arrow_back",
						class: "w-full !text-white/60 hover:!bg-white/10"
					}, {
						default: withCtx((_, _push, _parent, _scopeId) => {
							if (_push) _push(` Regresar al Panel `);
							else return [createTextVNode(" Regresar al Panel ")];
						}),
						_: 1
					}, _parent, _scopeId));
					else return [createVNode(Button_default, {
						variant: "ghost",
						size: "lg",
						icon: "arrow_back",
						class: "w-full !text-white/60 hover:!bg-white/10"
					}, {
						default: withCtx(() => [createTextVNode(" Regresar al Panel ")]),
						_: 1
					})];
				}),
				_: 1
			}, _parent));
			_push(`</div></div><div class="mt-12 text-center" data-v-8390faed><p class="text-[10px] font-black text-white/20 uppercase tracking-[0.3em]" data-v-8390faed>Nexo-Pro Assembly Guard 2026</p></div></div></div><!--]-->`);
		};
	}
});
//#endregion
//#region resources/js/Pages/Asamblea/AccessDenied.vue
var _sfc_setup = AccessDenied_vue_vue_type_script_setup_true_lang_default.setup;
AccessDenied_vue_vue_type_script_setup_true_lang_default.setup = (props, ctx) => {
	const ssrContext = useSSRContext();
	(ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Asamblea/AccessDenied.vue");
	return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
var AccessDenied_default = /* @__PURE__ */ _plugin_vue_export_helper_default(AccessDenied_vue_vue_type_script_setup_true_lang_default, [["__scopeId", "data-v-8390faed"]]);
//#endregion
export { AccessDenied_default as default };

import { t as _plugin_vue_export_helper_default } from "./_plugin-vue_export-helper-BjJHhBxR.js";
import { t as ThemeToggle_default } from "./ThemeToggle-CTS2VDKf.js";
import { t as Logo_default } from "./Logo-BJuTBUmx.js";
import { createTextVNode, createVNode, defineComponent, mergeProps, unref, useSSRContext, withCtx } from "vue";
import { Link } from "@inertiajs/vue3";
import { ssrInterpolate, ssrRenderAttrs, ssrRenderComponent, ssrRenderList, ssrRenderSlot } from "vue/server-renderer";
//#region resources/js/Components/Landing/Header.vue?vue&type=script&setup=true&lang.ts
var Header_vue_vue_type_script_setup_true_lang_default = /* @__PURE__ */ defineComponent({
	__name: "Header",
	__ssrInlineRender: true,
	setup(__props) {
		return (_ctx, _push, _parent, _attrs) => {
			_push(`<header${ssrRenderAttrs(mergeProps({ class: "fixed top-0 w-full z-50 transition-all duration-500 bg-white/70 dark:bg-[#0b0e14]/70 backdrop-blur-2xl border-b border-outline-variant/5 dark:border-white/5" }, _attrs))} data-v-c4aea1c0><div class="flex justify-between items-center w-full px-8 py-5 max-w-7xl mx-auto" data-v-c4aea1c0><div class="flex items-center gap-12" data-v-c4aea1c0>`);
			_push(ssrRenderComponent(unref(Link), {
				href: "/",
				class: "hover:opacity-80 transition-all active:scale-95"
			}, {
				default: withCtx((_, _push, _parent, _scopeId) => {
					if (_push) _push(ssrRenderComponent(Logo_default, {
						width: "48px",
						height: "48px"
					}, null, _parent, _scopeId));
					else return [createVNode(Logo_default, {
						width: "48px",
						height: "48px"
					})];
				}),
				_: 1
			}, _parent));
			_push(`<nav class="hidden lg:flex items-center space-x-10" data-v-c4aea1c0><a class="text-[10px] font-black uppercase tracking-[0.3em] text-on-surface-variant/60 hover:text-primary transition-all italic underline-offset-8 hover:underline" href="/#soluciones" data-v-c4aea1c0>Soluciones</a><a class="text-[10px] font-black uppercase tracking-[0.3em] text-on-surface-variant/60 hover:text-primary transition-all italic underline-offset-8 hover:underline" href="/#roles" data-v-c4aea1c0>Roles</a><a class="text-[10px] font-black uppercase tracking-[0.3em] text-on-surface-variant/60 hover:text-primary transition-all italic underline-offset-8 hover:underline" href="/#precios" data-v-c4aea1c0>Precios</a><a class="text-[10px] font-black uppercase tracking-[0.3em] text-on-surface-variant/60 hover:text-primary transition-all italic underline-offset-8 hover:underline" href="/#contacto" data-v-c4aea1c0>Contacto</a></nav></div><div class="flex items-center gap-8" data-v-c4aea1c0>`);
			_push(ssrRenderComponent(ThemeToggle_default, { class: "hidden sm:flex" }, null, _parent));
			_push(`<div class="flex items-center gap-4" data-v-c4aea1c0>`);
			if (_ctx.$page.props.auth?.user) _push(ssrRenderComponent(unref(Link), {
				href: _ctx.route("dashboard"),
				class: "bg-secondary text-white px-8 py-3 rounded-2xl text-[10px] font-black uppercase tracking-widest italic hover:scale-105 transition-all shadow-lg shadow-secondary/20"
			}, {
				default: withCtx((_, _push, _parent, _scopeId) => {
					if (_push) _push(` Mando Central `);
					else return [createTextVNode(" Mando Central ")];
				}),
				_: 1
			}, _parent));
			else _push(ssrRenderComponent(unref(Link), {
				href: _ctx.route("login"),
				class: "bg-primary text-white px-8 py-3 rounded-2xl text-[10px] font-black uppercase tracking-widest italic hover:scale-105 transition-all shadow-lg shadow-primary/20"
			}, {
				default: withCtx((_, _push, _parent, _scopeId) => {
					if (_push) _push(` Portal Clientes `);
					else return [createTextVNode(" Portal Clientes ")];
				}),
				_: 1
			}, _parent));
			_push(`</div></div></div></header>`);
		};
	}
});
//#endregion
//#region resources/js/Components/Landing/Header.vue
var _sfc_setup$2 = Header_vue_vue_type_script_setup_true_lang_default.setup;
Header_vue_vue_type_script_setup_true_lang_default.setup = (props, ctx) => {
	const ssrContext = useSSRContext();
	(ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/Landing/Header.vue");
	return _sfc_setup$2 ? _sfc_setup$2(props, ctx) : void 0;
};
var Header_default = /* @__PURE__ */ _plugin_vue_export_helper_default(Header_vue_vue_type_script_setup_true_lang_default, [["__scopeId", "data-v-c4aea1c0"]]);
//#endregion
//#region resources/js/Components/Landing/Footer.vue?vue&type=script&setup=true&lang.ts
var Footer_vue_vue_type_script_setup_true_lang_default = /* @__PURE__ */ defineComponent({
	__name: "Footer",
	__ssrInlineRender: true,
	setup(__props) {
		return (_ctx, _push, _parent, _attrs) => {
			_push(`<footer${ssrRenderAttrs(mergeProps({ class: "bg-surface-container-lowest dark:bg-[#080a0f] py-24 px-8 border-t border-outline-variant/10 dark:border-white/5 relative overflow-hidden" }, _attrs))} data-v-ae402e66><div class="absolute bottom-0 right-0 w-64 h-64 bg-primary/5 blur-[100px] rounded-full -mr-32 -mb-32" data-v-ae402e66></div><div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-start gap-16 relative z-10" data-v-ae402e66><div class="space-y-6 max-w-sm" data-v-ae402e66>`);
			_push(ssrRenderComponent(Logo_default, {
				width: "50px",
				height: "50px"
			}, null, _parent));
			_push(`<p class="text-[11px] font-bold text-on-surface-variant/60 dark:text-white/30 uppercase tracking-widest leading-relaxed italic" data-v-ae402e66> Elevando la gestión de copropiedades al estándar de ingeniería PRO. La plataforma definitiva para el futuro de la PropTech. </p><div class="flex gap-4" data-v-ae402e66><!--[-->`);
			ssrRenderList(3, (i) => {
				_push(`<div class="w-10 h-10 rounded-xl bg-surface-container-high dark:bg-white/5 flex items-center justify-center hover:bg-primary/10 hover:text-primary transition-all cursor-pointer border border-outline-variant/5" data-v-ae402e66><span class="material-symbols-rounded text-lg" data-v-ae402e66>public</span></div>`);
			});
			_push(`<!--]--></div></div><div class="grid grid-cols-2 md:grid-cols-3 gap-16" data-v-ae402e66><div class="space-y-6" data-v-ae402e66><h4 class="text-[10px] font-black uppercase tracking-[0.4em] text-primary italic" data-v-ae402e66>Plataforma</h4><ul class="space-y-4" data-v-ae402e66><li data-v-ae402e66><a href="#soluciones" class="text-[10px] font-black uppercase tracking-widest text-on-surface-variant/40 hover:text-primary transition-all underline-offset-4 hover:underline" data-v-ae402e66>Soluciones</a></li><li data-v-ae402e66><a href="#roles" class="text-[10px] font-black uppercase tracking-widest text-on-surface-variant/40 hover:text-primary transition-all underline-offset-4 hover:underline" data-v-ae402e66>Roles</a></li><li data-v-ae402e66><a href="#precios" class="text-[10px] font-black uppercase tracking-widest text-on-surface-variant/40 hover:text-primary transition-all underline-offset-4 hover:underline" data-v-ae402e66>Planes</a></li></ul></div><div class="space-y-6" data-v-ae402e66><h4 class="text-[10px] font-black uppercase tracking-[0.4em] text-primary italic" data-v-ae402e66>Legal</h4><ul class="space-y-4" data-v-ae402e66><li data-v-ae402e66>`);
			_push(ssrRenderComponent(unref(Link), {
				href: "#",
				class: "text-[10px] font-black uppercase tracking-widest text-on-surface-variant/40 hover:text-primary transition-all underline-offset-4 hover:underline"
			}, {
				default: withCtx((_, _push, _parent, _scopeId) => {
					if (_push) _push(`Privacidad`);
					else return [createTextVNode("Privacidad")];
				}),
				_: 1
			}, _parent));
			_push(`</li><li data-v-ae402e66>`);
			_push(ssrRenderComponent(unref(Link), {
				href: "#",
				class: "text-[10px] font-black uppercase tracking-widest text-on-surface-variant/40 hover:text-primary transition-all underline-offset-4 hover:underline"
			}, {
				default: withCtx((_, _push, _parent, _scopeId) => {
					if (_push) _push(`Términos`);
					else return [createTextVNode("Términos")];
				}),
				_: 1
			}, _parent));
			_push(`</li><li data-v-ae402e66>`);
			_push(ssrRenderComponent(unref(Link), {
				href: "#",
				class: "text-[10px] font-black uppercase tracking-widest text-on-surface-variant/40 hover:text-primary transition-all underline-offset-4 hover:underline"
			}, {
				default: withCtx((_, _push, _parent, _scopeId) => {
					if (_push) _push(`Cookies`);
					else return [createTextVNode("Cookies")];
				}),
				_: 1
			}, _parent));
			_push(`</li></ul></div><div class="space-y-6 col-span-2 md:col-span-1" data-v-ae402e66><h4 class="text-[10px] font-black uppercase tracking-[0.4em] text-primary italic" data-v-ae402e66>Soporte</h4><ul class="space-y-4" data-v-ae402e66><li data-v-ae402e66><a href="#" class="text-[10px] font-black uppercase tracking-widest text-on-surface-variant/40 hover:text-primary transition-all underline-offset-4 hover:underline" data-v-ae402e66>Centro de Ayuda</a></li><li data-v-ae402e66><a href="/soporte" class="text-[10px] font-black uppercase tracking-widest text-on-surface-variant/40 hover:text-primary transition-all underline-offset-4 hover:underline" data-v-ae402e66>Ticket de Sistema</a></li></ul></div></div></div><div class="max-w-7xl mx-auto mt-24 pt-8 border-t border-outline-variant/10 dark:border-white/5 flex flex-col md:flex-row justify-between items-center gap-6" data-v-ae402e66><p class="text-[9px] font-black text-on-surface-variant/30 dark:text-white/10 uppercase tracking-[0.3em]" data-v-ae402e66> © ${ssrInterpolate((/* @__PURE__ */ new Date()).getFullYear())} NEXO-PRO / HIGH-FIDELITY PROPTECH SOLUTIONS. </p><div class="flex items-center gap-2" data-v-ae402e66><div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse" data-v-ae402e66></div><span class="text-[9px] font-black text-emerald-500/40 uppercase tracking-widest italic" data-v-ae402e66>Sistemas Operativos 100%</span></div></div></footer>`);
		};
	}
});
//#endregion
//#region resources/js/Components/Landing/Footer.vue
var _sfc_setup$1 = Footer_vue_vue_type_script_setup_true_lang_default.setup;
Footer_vue_vue_type_script_setup_true_lang_default.setup = (props, ctx) => {
	const ssrContext = useSSRContext();
	(ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/Landing/Footer.vue");
	return _sfc_setup$1 ? _sfc_setup$1(props, ctx) : void 0;
};
var Footer_default = /* @__PURE__ */ _plugin_vue_export_helper_default(Footer_vue_vue_type_script_setup_true_lang_default, [["__scopeId", "data-v-ae402e66"]]);
//#endregion
//#region resources/js/Layouts/GuestLayout.vue?vue&type=script&setup=true&lang.ts
var GuestLayout_vue_vue_type_script_setup_true_lang_default = /* @__PURE__ */ defineComponent({
	__name: "GuestLayout",
	__ssrInlineRender: true,
	setup(__props) {
		return (_ctx, _push, _parent, _attrs) => {
			_push(`<div${ssrRenderAttrs(mergeProps({ class: "min-h-screen bg-surface flex flex-col font-sans text-on-surface antialiased" }, _attrs))}>`);
			_push(ssrRenderComponent(Header_default, null, null, _parent));
			_push(`<main class="flex-grow flex flex-col">`);
			ssrRenderSlot(_ctx.$slots, "default", {}, null, _push, _parent);
			_push(`</main>`);
			_push(ssrRenderComponent(Footer_default, null, null, _parent));
			_push(`</div>`);
		};
	}
});
//#endregion
//#region resources/js/Layouts/GuestLayout.vue
var _sfc_setup = GuestLayout_vue_vue_type_script_setup_true_lang_default.setup;
GuestLayout_vue_vue_type_script_setup_true_lang_default.setup = (props, ctx) => {
	const ssrContext = useSSRContext();
	(ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Layouts/GuestLayout.vue");
	return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
var GuestLayout_default = GuestLayout_vue_vue_type_script_setup_true_lang_default;
//#endregion
export { GuestLayout_default as t };

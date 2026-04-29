import { t as _plugin_vue_export_helper_default } from "./_plugin-vue_export-helper-BjJHhBxR.js";
import { computed, createBlock, createCommentVNode, defineComponent, mergeProps, openBlock, renderSlot, toDisplayString, unref, useSSRContext, withCtx } from "vue";
import { Link } from "@inertiajs/vue3";
import { ssrInterpolate, ssrRenderAttrs, ssrRenderComponent, ssrRenderSlot } from "vue/server-renderer";
//#region resources/js/Components/UI/Button.vue?vue&type=script&setup=true&lang.ts
var baseClasses = "inline-flex items-center justify-center font-bold uppercase tracking-widest transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-primary/20 active:scale-[0.98] disabled:opacity-50 disabled:cursor-not-allowed disabled:pointer-events-none rounded-xl border";
var Button_vue_vue_type_script_setup_true_lang_default = /* @__PURE__ */ defineComponent({
	__name: "Button",
	__ssrInlineRender: true,
	props: {
		href: {},
		type: { default: "button" },
		variant: { default: "primary" },
		size: { default: "md" },
		disabled: {
			type: Boolean,
			default: false
		},
		loading: {
			type: Boolean,
			default: false
		},
		icon: {}
	},
	emits: ["click"],
	setup(__props) {
		const props = __props;
		const variantClasses = {
			primary: "bg-primary text-on-primary border-primary shadow-lg shadow-primary/20 hover:shadow-primary/40 hover:-translate-y-0.5",
			secondary: "bg-surface-container text-on-surface border-outline-variant/30 hover:bg-surface-container-high",
			outline: "bg-transparent border-2 border-primary text-primary hover:bg-primary hover:text-on-primary",
			ghost: "bg-transparent border-transparent text-on-surface-variant hover:text-primary hover:bg-primary/5",
			error: "bg-error text-white border-error shadow-lg shadow-error/20 hover:shadow-error/40 hover:-translate-y-0.5",
			gradient: "bg-brand-gradient text-white border-transparent shadow-lg shadow-primary/20 hover:-translate-y-[2px]"
		};
		const sizeClasses = {
			sm: "px-4 py-2 text-[10px] gap-2",
			md: "px-6 py-2.5 text-[11px] gap-2.5",
			lg: "px-8 py-3.5 text-xs gap-3"
		};
		const classes = computed(() => [
			baseClasses,
			variantClasses[props.variant],
			sizeClasses[props.size]
		]);
		return (_ctx, _push, _parent, _attrs) => {
			if (__props.href) _push(ssrRenderComponent(unref(Link), mergeProps({
				href: __props.href,
				class: [classes.value, "no-underline"]
			}, _attrs), {
				default: withCtx((_, _push, _parent, _scopeId) => {
					if (_push) {
						if (__props.loading) _push(`<span class="animate-spin material-symbols-outlined text-[1.2em]" data-v-af64e21e${_scopeId}>sync</span>`);
						else if (__props.icon) _push(`<span class="material-symbols-outlined text-[1.2em]" data-v-af64e21e${_scopeId}>${ssrInterpolate(__props.icon)}</span>`);
						else _push(`<!---->`);
						ssrRenderSlot(_ctx.$slots, "default", {}, null, _push, _parent, _scopeId);
					} else return [__props.loading ? (openBlock(), createBlock("span", {
						key: 0,
						class: "animate-spin material-symbols-outlined text-[1.2em]"
					}, "sync")) : __props.icon ? (openBlock(), createBlock("span", {
						key: 1,
						class: "material-symbols-outlined text-[1.2em]"
					}, toDisplayString(__props.icon), 1)) : createCommentVNode("", true), renderSlot(_ctx.$slots, "default", {}, void 0, true)];
				}),
				_: 3
			}, _parent));
			else {
				_push(`<button${ssrRenderAttrs(mergeProps({
					type: __props.type,
					disabled: __props.disabled || __props.loading,
					class: classes.value
				}, _attrs))} data-v-af64e21e>`);
				if (__props.loading) _push(`<span class="animate-spin material-symbols-outlined text-[1.2em]" data-v-af64e21e>sync</span>`);
				else if (__props.icon) _push(`<span class="material-symbols-outlined text-[1.2em]" data-v-af64e21e>${ssrInterpolate(__props.icon)}</span>`);
				else _push(`<!---->`);
				ssrRenderSlot(_ctx.$slots, "default", {}, null, _push, _parent);
				_push(`</button>`);
			}
		};
	}
});
//#endregion
//#region resources/js/Components/UI/Button.vue
var _sfc_setup = Button_vue_vue_type_script_setup_true_lang_default.setup;
Button_vue_vue_type_script_setup_true_lang_default.setup = (props, ctx) => {
	const ssrContext = useSSRContext();
	(ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/UI/Button.vue");
	return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
var Button_default = /* @__PURE__ */ _plugin_vue_export_helper_default(Button_vue_vue_type_script_setup_true_lang_default, [["__scopeId", "data-v-af64e21e"]]);
//#endregion
export { Button_default as t };

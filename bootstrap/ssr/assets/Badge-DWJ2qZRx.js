import { computed, defineComponent, mergeProps, useSSRContext } from "vue";
import { ssrRenderAttrs, ssrRenderSlot } from "vue/server-renderer";
//#region resources/js/Components/UI/Badge.vue?vue&type=script&setup=true&lang.ts
var Badge_vue_vue_type_script_setup_true_lang_default = /* @__PURE__ */ defineComponent({
	__name: "Badge",
	__ssrInlineRender: true,
	props: {
		variant: { default: "neutral" },
		class: {}
	},
	setup(__props) {
		const props = __props;
		const variants = {
			primary: "bg-primary text-on-primary",
			secondary: "bg-secondary text-on-secondary shadow-sm",
			success: "bg-success/10 text-success border border-success/20 dark:text-success",
			warning: "bg-warning/10 text-warning border border-warning/20 dark:text-warning",
			error: "bg-error/10 text-error border border-error/20 dark:text-error",
			neutral: "bg-surface-container-high text-on-surface border border-outline-variant/30",
			gradient: "bg-brand-gradient text-white shadow-md",
			outline: "bg-transparent border border-surface-container-highest text-on-surface dark:border-outline-variant dark:text-white"
		};
		const classes = computed(() => [
			"inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider transition-all",
			variants[props.variant],
			props.class
		]);
		return (_ctx, _push, _parent, _attrs) => {
			_push(`<span${ssrRenderAttrs(mergeProps({ class: classes.value }, _attrs))}>`);
			ssrRenderSlot(_ctx.$slots, "default", {}, null, _push, _parent);
			_push(`</span>`);
		};
	}
});
//#endregion
//#region resources/js/Components/UI/Badge.vue
var _sfc_setup = Badge_vue_vue_type_script_setup_true_lang_default.setup;
Badge_vue_vue_type_script_setup_true_lang_default.setup = (props, ctx) => {
	const ssrContext = useSSRContext();
	(ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/UI/Badge.vue");
	return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
var Badge_default = Badge_vue_vue_type_script_setup_true_lang_default;
//#endregion
export { Badge_default as t };

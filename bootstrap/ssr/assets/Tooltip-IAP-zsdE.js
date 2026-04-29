import { defineComponent, mergeProps, ref, useSSRContext } from "vue";
import { ssrInterpolate, ssrRenderAttrs, ssrRenderClass, ssrRenderSlot } from "vue/server-renderer";
//#region resources/js/Components/UI/Tooltip.vue?vue&type=script&setup=true&lang.ts
var Tooltip_vue_vue_type_script_setup_true_lang_default = /* @__PURE__ */ defineComponent({
	__name: "Tooltip",
	__ssrInlineRender: true,
	props: {
		text: {},
		position: { default: "top" }
	},
	setup(__props) {
		const isVisible = ref(false);
		const positionClasses = {
			top: "bottom-full left-1/2 -translate-x-1/2 mb-2",
			bottom: "top-full left-1/2 -translate-x-1/2 mt-2",
			left: "right-full top-1/2 -translate-y-1/2 mr-2",
			right: "left-full top-1/2 -translate-y-1/2 ml-2"
		};
		const arrowClasses = {
			top: "-bottom-1 left-1/2 -translate-x-1/2 border-b border-r",
			bottom: "-top-1 left-1/2 -translate-x-1/2 border-t border-l",
			left: "-right-1 top-1/2 -translate-y-1/2 border-t border-r",
			right: "-left-1 top-1/2 -translate-y-1/2 border-b border-l"
		};
		return (_ctx, _push, _parent, _attrs) => {
			_push(`<div${ssrRenderAttrs(mergeProps({ class: "relative inline-block" }, _attrs))}>`);
			ssrRenderSlot(_ctx.$slots, "default", {}, null, _push, _parent);
			if (isVisible.value) _push(`<div class="${ssrRenderClass([positionClasses[__props.position], "absolute z-[110] px-3 py-1.5 bg-primary text-on-primary text-[9px] font-black uppercase tracking-tight rounded-xl shadow-2xl pointer-events-none w-max max-w-[200px] text-center leading-tight"])}">${ssrInterpolate(__props.text)} <div class="${ssrRenderClass([arrowClasses[__props.position], "absolute w-2 h-2 bg-primary rotate-45"])}"></div></div>`);
			else _push(`<!---->`);
			_push(`</div>`);
		};
	}
});
//#endregion
//#region resources/js/Components/UI/Tooltip.vue
var _sfc_setup = Tooltip_vue_vue_type_script_setup_true_lang_default.setup;
Tooltip_vue_vue_type_script_setup_true_lang_default.setup = (props, ctx) => {
	const ssrContext = useSSRContext();
	(ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/UI/Tooltip.vue");
	return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
var Tooltip_default = Tooltip_vue_vue_type_script_setup_true_lang_default;
//#endregion
export { Tooltip_default as t };

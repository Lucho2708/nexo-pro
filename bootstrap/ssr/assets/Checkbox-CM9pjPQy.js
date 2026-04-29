import { t as _plugin_vue_export_helper_default } from "./_plugin-vue_export-helper-BjJHhBxR.js";
import { computed, defineComponent, mergeProps, useSSRContext } from "vue";
import { ssrIncludeBooleanAttr, ssrInterpolate, ssrRenderAttr, ssrRenderAttrs, ssrRenderClass, ssrRenderSlot } from "vue/server-renderer";
//#region resources/js/Components/UI/Checkbox.vue?vue&type=script&setup=true&lang.ts
var Checkbox_vue_vue_type_script_setup_true_lang_default = /* @__PURE__ */ defineComponent({
	__name: "Checkbox",
	__ssrInlineRender: true,
	props: {
		modelValue: {
			type: Boolean,
			default: false
		},
		label: {},
		description: {},
		error: {},
		state: { default: "default" },
		disabled: {
			type: Boolean,
			default: false
		},
		required: {
			type: Boolean,
			default: false
		}
	},
	emits: ["update:modelValue"],
	setup(__props, { emit: __emit }) {
		const props = __props;
		const inputId = `checkbox-${Math.random().toString(36).substring(2, 9)}`;
		const currentState = computed(() => {
			if (props.error) return "error";
			return props.state;
		});
		const stateConfig = {
			error: "border-error/50 hover:border-error focus:ring-error/20 bg-error/5",
			success: "border-success/50 hover:border-success focus:ring-success/20 bg-success/5",
			warning: "border-warning/50 hover:border-warning focus:ring-warning/20 bg-warning/5",
			default: "border-outline-variant/30 hover:border-primary/50 focus:ring-primary/20 bg-surface-container-low/30"
		};
		const checkboxStateConfig = {
			error: "border-error text-error focus:ring-error/20 accent-error",
			success: "border-success text-success focus:ring-success/20 accent-success",
			warning: "border-warning text-warning focus:ring-warning/20 accent-warning",
			default: "border-outline-variant/30 text-primary focus:ring-primary/20 accent-primary"
		};
		return (_ctx, _push, _parent, _attrs) => {
			_push(`<div${ssrRenderAttrs(mergeProps({ class: "flex flex-col gap-2" }, _attrs))} data-v-66e1c81b><label${ssrRenderAttr("for", inputId)} class="${ssrRenderClass([[stateConfig[currentState.value], __props.disabled ? "opacity-50 cursor-not-allowed" : ""], "flex items-start gap-4 p-4 rounded-xl border cursor-pointer transition-colors"])}" data-v-66e1c81b><div class="pt-0.5 relative flex items-center justify-center" data-v-66e1c81b><input${ssrRenderAttr("id", inputId)} type="checkbox"${ssrIncludeBooleanAttr(__props.modelValue) ? " checked" : ""}${ssrIncludeBooleanAttr(__props.disabled) ? " disabled" : ""}${ssrIncludeBooleanAttr(__props.required) ? " required" : ""} class="${ssrRenderClass([checkboxStateConfig[currentState.value], "peer w-5 h-5 rounded outline-none transition-all cursor-pointer appearance-none border-2 checked:bg-current"])}" data-v-66e1c81b><span class="material-symbols-outlined text-white text-[14px] absolute pointer-events-none opacity-0 peer-checked:opacity-100 transition-opacity" data-v-66e1c81b>check</span></div><div class="flex flex-col gap-1" data-v-66e1c81b>`);
			if (__props.label || _ctx.$slots.label) {
				_push(`<div class="text-sm font-bold text-on-surface leading-snug" data-v-66e1c81b>`);
				ssrRenderSlot(_ctx.$slots, "label", {}, () => {
					_push(`${ssrInterpolate(__props.label)}`);
				}, _push, _parent);
				if (__props.required) _push(`<span class="text-error ml-0.5" data-v-66e1c81b>*</span>`);
				else _push(`<!---->`);
				_push(`</div>`);
			} else _push(`<!---->`);
			if (__props.description || _ctx.$slots.description) {
				_push(`<div class="text-xs text-on-surface-variant font-medium leading-relaxed" data-v-66e1c81b>`);
				ssrRenderSlot(_ctx.$slots, "description", {}, () => {
					_push(`${ssrInterpolate(__props.description)}`);
				}, _push, _parent);
				_push(`</div>`);
			} else _push(`<!---->`);
			_push(`</div></label>`);
			if (__props.error) _push(`<p class="text-error text-xs font-bold px-2 mt-1 flex items-center gap-1 animate-in fade-in slide-in-from-top-1" data-v-66e1c81b><span class="material-symbols-outlined text-[14px]" aria-hidden="true" data-v-66e1c81b>error</span> ${ssrInterpolate(__props.error)}</p>`);
			else _push(`<!---->`);
			_push(`</div>`);
		};
	}
});
//#endregion
//#region resources/js/Components/UI/Checkbox.vue
var _sfc_setup = Checkbox_vue_vue_type_script_setup_true_lang_default.setup;
Checkbox_vue_vue_type_script_setup_true_lang_default.setup = (props, ctx) => {
	const ssrContext = useSSRContext();
	(ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/UI/Checkbox.vue");
	return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
var Checkbox_default = /* @__PURE__ */ _plugin_vue_export_helper_default(Checkbox_vue_vue_type_script_setup_true_lang_default, [["__scopeId", "data-v-66e1c81b"]]);
//#endregion
export { Checkbox_default as t };

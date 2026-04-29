import { t as _plugin_vue_export_helper_default } from "./_plugin-vue_export-helper-BjJHhBxR.js";
import { computed, defineComponent, mergeProps, useSSRContext } from "vue";
import { ssrIncludeBooleanAttr, ssrInterpolate, ssrRenderAttr, ssrRenderAttrs, ssrRenderClass, ssrRenderStyle } from "vue/server-renderer";
//#region resources/js/Components/UI/Input.vue?vue&type=script&setup=true&lang.ts
var Input_vue_vue_type_script_setup_true_lang_default = /* @__PURE__ */ defineComponent({
	__name: "Input",
	__ssrInlineRender: true,
	props: {
		modelValue: { default: "" },
		label: {},
		type: { default: "text" },
		placeholder: {},
		error: {},
		state: { default: "default" },
		icon: {},
		disabled: {
			type: Boolean,
			default: false
		},
		required: {
			type: Boolean,
			default: false
		},
		class: {},
		inputClass: {}
	},
	emits: ["update:modelValue"],
	setup(__props, { emit: __emit }) {
		const props = __props;
		const inputId = `input-${Math.random().toString(36).substring(2, 9)}`;
		const currentState = computed(() => {
			if (props.error) return "error";
			return props.state;
		});
		const stateConfig = {
			error: "border-error/50 focus:border-error focus:ring-error/10 text-error",
			success: "border-success/50 focus:border-success focus:ring-success/10 text-success",
			warning: "border-warning/50 focus:border-warning focus:ring-warning/10 text-warning",
			default: "border-outline-variant/10 hover:border-outline-variant/30 focus:border-primary focus:ring-primary/10"
		};
		const labelStateConfig = {
			error: "text-error",
			success: "text-success",
			warning: "text-warning",
			default: "text-on-surface-variant/60 peer-focus:text-primary"
		};
		return (_ctx, _push, _parent, _attrs) => {
			_push(`<div${ssrRenderAttrs(mergeProps({ class: ["relative w-full group", props.class] }, _attrs))} data-v-4cc345e8><div class="relative flex items-center" data-v-4cc345e8>`);
			if (__props.icon) _push(`<span class="material-symbols-outlined absolute left-4 text-on-surface-variant/40 group-focus-within:text-primary transition-colors z-10" style="${ssrRenderStyle({ "font-variation-settings": "'opsz' 20, 'wght' 500" })}" aria-hidden="true" data-v-4cc345e8>${ssrInterpolate(__props.icon)}</span>`);
			else _push(`<!---->`);
			_push(`<input${ssrRenderAttr("id", inputId)}${ssrRenderAttr("type", __props.type)}${ssrRenderAttr("value", __props.modelValue)}${ssrRenderAttr("placeholder", __props.placeholder || " ")}${ssrIncludeBooleanAttr(__props.disabled) ? " disabled" : ""}${ssrIncludeBooleanAttr(__props.required) ? " required" : ""} class="${ssrRenderClass([
				"peer w-full rounded-2xl border-2 bg-surface-container-low px-4 pt-6 pb-2 text-sm font-medium transition-all outline-none uppercase tracking-wider",
				"focus:ring-4 focus:ring-offset-0 disabled:opacity-50 disabled:cursor-not-allowed text-on-surface",
				__props.icon ? "pl-12" : "",
				stateConfig[currentState.value],
				__props.inputClass
			])}"${ssrRenderAttr("aria-invalid", !!__props.error)}${ssrRenderAttr("aria-describedby", __props.error ? `${inputId}-error` : void 0)} data-v-4cc345e8>`);
			if (__props.label) {
				_push(`<label${ssrRenderAttr("for", inputId)} class="${ssrRenderClass([
					"absolute font-black uppercase tracking-widest transition-all duration-200 ease-in-out cursor-text pointer-events-none z-10",
					__props.icon ? "left-12" : "left-4",
					"top-4 -translate-y-2.5 text-[9px]",
					"peer-placeholder-shown:translate-y-0 peer-placeholder-shown:text-[11px]",
					"peer-focus:-translate-y-2.5 peer-focus:text-[9px]",
					labelStateConfig[currentState.value]
				])}" data-v-4cc345e8>${ssrInterpolate(__props.label)} `);
				if (__props.required) _push(`<span class="text-error ml-0.5" data-v-4cc345e8>*</span>`);
				else _push(`<!---->`);
				_push(`</label>`);
			} else _push(`<!---->`);
			_push(`</div>`);
			if (__props.error) _push(`<p${ssrRenderAttr("id", `${inputId}-error`)} class="mt-1.5 text-[10px] font-black uppercase tracking-wider text-error flex items-center gap-1 animate-in fade-in slide-in-from-top-1" data-v-4cc345e8><span class="material-symbols-outlined text-[14px]" aria-hidden="true" data-v-4cc345e8>error</span> ${ssrInterpolate(__props.error)}</p>`);
			else _push(`<!---->`);
			_push(`</div>`);
		};
	}
});
//#endregion
//#region resources/js/Components/UI/Input.vue
var _sfc_setup = Input_vue_vue_type_script_setup_true_lang_default.setup;
Input_vue_vue_type_script_setup_true_lang_default.setup = (props, ctx) => {
	const ssrContext = useSSRContext();
	(ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/UI/Input.vue");
	return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
var Input_default = /* @__PURE__ */ _plugin_vue_export_helper_default(Input_vue_vue_type_script_setup_true_lang_default, [["__scopeId", "data-v-4cc345e8"]]);
//#endregion
export { Input_default as t };

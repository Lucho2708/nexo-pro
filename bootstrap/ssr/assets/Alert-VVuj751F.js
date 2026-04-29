import { t as _plugin_vue_export_helper_default } from "./_plugin-vue_export-helper-BjJHhBxR.js";
import { computed, defineComponent, mergeProps, useSSRContext } from "vue";
import { ssrInterpolate, ssrRenderAttrs, ssrRenderSlot } from "vue/server-renderer";
//#region resources/js/Components/UI/Alert.vue?vue&type=script&setup=true&lang.ts
var Alert_vue_vue_type_script_setup_true_lang_default = /* @__PURE__ */ defineComponent({
	__name: "Alert",
	__ssrInlineRender: true,
	props: {
		variant: { default: "info" },
		title: {},
		message: {},
		closeable: {
			type: Boolean,
			default: false
		},
		show: {
			type: Boolean,
			default: true
		}
	},
	emits: ["close"],
	setup(__props, { emit: __emit }) {
		const props = __props;
		const config = {
			success: {
				icon: "check_circle",
				classes: "bg-success/10 border-success/50 text-success dark:text-success"
			},
			info: {
				icon: "info",
				classes: "bg-info/10 border-info/50 text-info dark:text-info"
			},
			primary: {
				icon: "notifications",
				classes: "bg-primary/10 border-primary/50 text-primary dark:text-secondary"
			},
			warning: {
				icon: "warning",
				classes: "bg-warning/10 border-warning/50 text-warning dark:text-warning"
			},
			error: {
				icon: "error",
				classes: "bg-error/10 border-error/50 text-error dark:text-error"
			}
		};
		const currentConfig = computed(() => config[props.variant]);
		return (_ctx, _push, _parent, _attrs) => {
			if (__props.show) {
				_push(`<div${ssrRenderAttrs(mergeProps({ class: ["p-4 rounded-xl border flex gap-3 items-start relative overflow-hidden backdrop-blur-sm", currentConfig.value.classes] }, _attrs))} data-v-312a1650><div class="shrink-0 pt-0.5" data-v-312a1650><span class="material-symbols-outlined text-[20px]" data-v-312a1650>${ssrInterpolate(currentConfig.value.icon)}</span></div><div class="flex-1" data-v-312a1650>`);
				if (__props.title) _push(`<h4 class="text-sm font-bold tracking-tight leading-none mb-1" data-v-312a1650>${ssrInterpolate(__props.title)}</h4>`);
				else _push(`<!---->`);
				if (__props.message || _ctx.$slots.default) {
					_push(`<p class="text-sm font-medium opacity-90 leading-relaxed" data-v-312a1650>`);
					ssrRenderSlot(_ctx.$slots, "default", {}, () => {
						_push(`${ssrInterpolate(__props.message)}`);
					}, _push, _parent);
					_push(`</p>`);
				} else _push(`<!---->`);
				_push(`</div>`);
				if (__props.closeable) _push(`<button class="shrink-0 p-1 hover:bg-black/10 dark:hover:bg-white/10 rounded-lg transition-colors" data-v-312a1650><span class="material-symbols-outlined text-sm" data-v-312a1650>close</span></button>`);
				else _push(`<!---->`);
				_push(`</div>`);
			} else _push(`<!---->`);
		};
	}
});
//#endregion
//#region resources/js/Components/UI/Alert.vue
var _sfc_setup = Alert_vue_vue_type_script_setup_true_lang_default.setup;
Alert_vue_vue_type_script_setup_true_lang_default.setup = (props, ctx) => {
	const ssrContext = useSSRContext();
	(ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/UI/Alert.vue");
	return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
var Alert_default = /* @__PURE__ */ _plugin_vue_export_helper_default(Alert_vue_vue_type_script_setup_true_lang_default, [["__scopeId", "data-v-312a1650"]]);
//#endregion
export { Alert_default as t };

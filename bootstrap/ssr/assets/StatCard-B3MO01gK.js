import { t as __exportAll } from "./rolldown-runtime-rSIU-vHC.js";
import { t as _plugin_vue_export_helper_default } from "./_plugin-vue_export-helper-BjJHhBxR.js";
import { defineComponent, mergeProps, onMounted, ref, useSSRContext } from "vue";
import { ssrInterpolate, ssrRenderAttrs, ssrRenderClass, ssrRenderStyle } from "vue/server-renderer";
//#region resources/js/Components/Dashboard/StatCard.vue?vue&type=script&setup=true&lang.ts
var StatCard_vue_vue_type_script_setup_true_lang_default = /* @__PURE__ */ defineComponent({
	__name: "StatCard",
	__ssrInlineRender: true,
	props: {
		label: {},
		value: {},
		trend: {},
		trendUp: { type: Boolean },
		subtext: {},
		progress: {},
		colorClass: {}
	},
	setup(__props) {
		const isVisible = ref(false);
		onMounted(() => {
			setTimeout(() => {
				isVisible.value = true;
			}, 100);
		});
		return (_ctx, _push, _parent, _attrs) => {
			_push(`<div${ssrRenderAttrs(mergeProps({ class: "premium-card p-8 !rounded-[2rem] relative overflow-hidden group transition-all duration-500 hover:-translate-y-1 hover:shadow-primary/10" }, _attrs))} data-v-7fbd9094><div class="absolute top-0 left-0 w-2 h-16 bg-primary/10 rounded-full mt-10 -ml-1 group-hover:h-20 transition-all duration-500" data-v-7fbd9094></div><p class="text-[10px] font-black text-on-surface-variant/50 uppercase tracking-[0.2em] mb-4" data-v-7fbd9094>${ssrInterpolate(__props.label)}</p><div class="flex items-end justify-between" data-v-7fbd9094><h3 class="${ssrRenderClass([[isVisible.value ? "translate-y-0 opacity-100" : "translate-y-4 opacity-0"], "text-4xl font-black text-primary tracking-tighter leading-none transition-all duration-700 transform"])}" data-v-7fbd9094>${ssrInterpolate(__props.value)}</h3>`);
			if (__props.trend) _push(`<div class="${ssrRenderClass([[__props.trendUp ? "bg-secondary/10 text-secondary" : "bg-error/10 text-error", isVisible.value ? "scale-100 opacity-100" : "scale-90 opacity-0"], "px-2.5 py-1 rounded-xl text-[10px] font-black flex items-center gap-1 transition-all duration-700"])}" data-v-7fbd9094><span class="material-symbols-outlined text-sm" style="${ssrRenderStyle({ "font-variation-settings": "'FILL' 1" })}" data-v-7fbd9094>${ssrInterpolate(__props.trendUp ? "trending_up" : "trending_down")}</span> ${ssrInterpolate(__props.trend)}</div>`);
			else _push(`<!---->`);
			_push(`</div>`);
			if (__props.progress !== void 0) _push(`<div class="mt-6 w-full bg-surface-container-low h-2 rounded-full overflow-hidden" data-v-7fbd9094><div class="${ssrRenderClass([__props.colorClass, "h-full rounded-full transition-all duration-[1.5s] ease-out shadow-sm"])}" style="${ssrRenderStyle({ width: isVisible.value ? `${__props.progress}%` : "0%" })}" data-v-7fbd9094></div></div>`);
			else _push(`<!---->`);
			if (__props.subtext) _push(`<p class="text-[10px] text-on-surface-variant/40 font-bold uppercase tracking-widest mt-5 flex items-center gap-1.5" data-v-7fbd9094><span class="w-1 h-1 rounded-full bg-outline-variant/50" data-v-7fbd9094></span> ${ssrInterpolate(__props.subtext)}</p>`);
			else _push(`<!---->`);
			_push(`</div>`);
		};
	}
});
//#endregion
//#region resources/js/Components/Dashboard/StatCard.vue
var StatCard_exports = /* @__PURE__ */ __exportAll({ default: () => StatCard_default });
var _sfc_setup = StatCard_vue_vue_type_script_setup_true_lang_default.setup;
StatCard_vue_vue_type_script_setup_true_lang_default.setup = (props, ctx) => {
	const ssrContext = useSSRContext();
	(ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/Dashboard/StatCard.vue");
	return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
var StatCard_default = /* @__PURE__ */ _plugin_vue_export_helper_default(StatCard_vue_vue_type_script_setup_true_lang_default, [["__scopeId", "data-v-7fbd9094"]]);
//#endregion
export { StatCard_exports as n, StatCard_default as t };

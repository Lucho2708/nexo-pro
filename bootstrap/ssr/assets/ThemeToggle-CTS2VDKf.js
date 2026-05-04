import { t as _plugin_vue_export_helper_default } from "./_plugin-vue_export-helper-BjJHhBxR.js";
import { defineComponent, mergeProps, onMounted, ref, useSSRContext } from "vue";
import { ssrRenderAttrs } from "vue/server-renderer";
//#region resources/js/Components/UI/ThemeToggle.vue?vue&type=script&setup=true&lang.ts
var ThemeToggle_vue_vue_type_script_setup_true_lang_default = /* @__PURE__ */ defineComponent({
	__name: "ThemeToggle",
	__ssrInlineRender: true,
	setup(__props) {
		const isDark = ref(false);
		onMounted(() => {
			const savedTheme = localStorage.getItem("theme");
			if (savedTheme === "dark" || !savedTheme && window.matchMedia("(prefers-color-scheme: dark)").matches) {
				isDark.value = true;
				document.documentElement.classList.add("dark");
			}
		});
		return (_ctx, _push, _parent, _attrs) => {
			_push(`<button${ssrRenderAttrs(mergeProps({
				class: "w-10 h-10 rounded-xl bg-surface-container border border-outline-variant/30 flex items-center justify-center text-on-surface hover:scale-110 active:scale-95 transition-all duration-300 shadow-sm",
				title: isDark.value ? "Cambiar a modo claro" : "Cambiar a modo oscuro",
				"aria-label": isDark.value ? "Cambiar a modo claro" : "Cambiar a modo oscuro"
			}, _attrs))} data-v-e1588055>`);
			if (isDark.value) _push(`<span class="material-symbols-rounded text-[20px] text-primary" data-v-e1588055>light_mode</span>`);
			else _push(`<span class="material-symbols-rounded text-[20px] text-primary" data-v-e1588055>dark_mode</span>`);
			_push(`</button>`);
		};
	}
});
//#endregion
//#region resources/js/Components/UI/ThemeToggle.vue
var _sfc_setup = ThemeToggle_vue_vue_type_script_setup_true_lang_default.setup;
ThemeToggle_vue_vue_type_script_setup_true_lang_default.setup = (props, ctx) => {
	const ssrContext = useSSRContext();
	(ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/UI/ThemeToggle.vue");
	return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
var ThemeToggle_default = /* @__PURE__ */ _plugin_vue_export_helper_default(ThemeToggle_vue_vue_type_script_setup_true_lang_default, [["__scopeId", "data-v-e1588055"]]);
//#endregion
export { ThemeToggle_default as t };

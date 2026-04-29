import { t as _plugin_vue_export_helper_default } from "./_plugin-vue_export-helper-BjJHhBxR.js";
import { t as useToast } from "./useToast-E_Hdzmc5.js";
import { defineComponent, mergeProps, unref, useSSRContext } from "vue";
import { ssrInterpolate, ssrRenderAttrs, ssrRenderClass, ssrRenderList } from "vue/server-renderer";
//#region resources/js/Components/UI/ToastContainer.vue?vue&type=script&setup=true&lang.ts
var ToastContainer_vue_vue_type_script_setup_true_lang_default = /* @__PURE__ */ defineComponent({
	__name: "ToastContainer",
	__ssrInlineRender: true,
	setup(__props) {
		const { toasts, remove } = useToast();
		return (_ctx, _push, _parent, _attrs) => {
			_push(`<div${ssrRenderAttrs(mergeProps({ class: "fixed bottom-6 right-6 z-[100] flex flex-col gap-4 pointer-events-none" }, _attrs))} data-v-651e183a><!--[-->`);
			ssrRenderList(unref(toasts), (toast) => {
				_push(`<div class="${ssrRenderClass([{
					"bg-white/95 dark:bg-slate-900/90 border-primary/20 dark:border-primary/30": !toast.variant || toast.variant === "primary" || toast.variant === "info",
					"bg-emerald-50/95 dark:bg-emerald-950/40 border-emerald-500/30": toast.variant === "success",
					"bg-red-50/95 dark:bg-red-950/40 border-red-500/30": toast.variant === "danger" || toast.variant === "error",
					"bg-amber-50/95 dark:bg-amber-950/40 border-amber-500/30": toast.variant === "warning"
				}, "pointer-events-auto w-[320px] md:w-[400px] overflow-hidden rounded-[1.5rem] border shadow-[0_25px_60px_rgba(0,0,0,0.2)] backdrop-blur-2xl transition-all duration-300"])}" data-v-651e183a><div class="p-5" data-v-651e183a><div class="flex items-center gap-4" data-v-651e183a><div class="flex-shrink-0" data-v-651e183a><div class="${ssrRenderClass([{
					"bg-primary text-on-primary": !toast.variant || toast.variant === "primary" || toast.variant === "info",
					"bg-emerald-500 text-white shadow-emerald-500/20": toast.variant === "success",
					"bg-red-500 text-white shadow-red-500/20": toast.variant === "danger" || toast.variant === "error",
					"bg-amber-500 text-white shadow-amber-500/20": toast.variant === "warning"
				}, "flex h-11 w-11 items-center justify-center rounded-2xl shadow-sm transition-all duration-500"])}" data-v-651e183a>`);
				if (toast.variant === "success") _push(`<svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" data-v-651e183a><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" data-v-651e183a></path></svg>`);
				else if (toast.variant === "danger" || toast.variant === "error") _push(`<svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" data-v-651e183a><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" data-v-651e183a></path></svg>`);
				else if (toast.variant === "warning") _push(`<svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" data-v-651e183a><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" data-v-651e183a></path></svg>`);
				else _push(`<svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" data-v-651e183a><path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12v-.008z" data-v-651e183a></path></svg>`);
				_push(`</div></div><div class="flex-1 min-w-0" data-v-651e183a><p class="${ssrRenderClass([{
					"text-primary dark:text-primary-light": !toast.variant || toast.variant === "primary" || toast.variant === "info",
					"text-emerald-700 dark:text-emerald-400": toast.variant === "success",
					"text-red-700 dark:text-red-400": toast.variant === "danger" || toast.variant === "error",
					"text-amber-700 dark:text-amber-400": toast.variant === "warning"
				}, "text-[10px] font-black uppercase tracking-[0.15em] mb-0.5 opacity-50"])}" data-v-651e183a>${ssrInterpolate(toast.variant === "danger" || toast.variant === "error" ? "Atención" : toast.variant || "Notificación")}</p><p class="text-sm font-bold text-on-surface dark:text-white leading-tight" data-v-651e183a>${ssrInterpolate(toast.message)}</p></div><div class="flex-shrink-0 self-start" data-v-651e183a><button type="button" class="inline-flex rounded-xl p-1.5 transition-all active:scale-90 hover:bg-black/5 dark:hover:bg-white/10 text-on-surface-variant/40 hover:text-on-surface" data-v-651e183a><span class="sr-only" data-v-651e183a>Cerrar</span><svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" data-v-651e183a><path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z" data-v-651e183a></path></svg></button></div></div></div><div class="${ssrRenderClass([{
					"bg-primary": !toast.variant || toast.variant === "primary" || toast.variant === "info",
					"bg-emerald-500": toast.variant === "success",
					"bg-red-500": toast.variant === "danger" || toast.variant === "error",
					"bg-amber-500": toast.variant === "warning"
				}, "h-1 w-full opacity-40 transition-all duration-300"])}" data-v-651e183a></div></div>`);
			});
			_push(`<!--]--></div>`);
		};
	}
});
//#endregion
//#region resources/js/Components/UI/ToastContainer.vue
var _sfc_setup = ToastContainer_vue_vue_type_script_setup_true_lang_default.setup;
ToastContainer_vue_vue_type_script_setup_true_lang_default.setup = (props, ctx) => {
	const ssrContext = useSSRContext();
	(ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/UI/ToastContainer.vue");
	return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
var ToastContainer_default = /* @__PURE__ */ _plugin_vue_export_helper_default(ToastContainer_vue_vue_type_script_setup_true_lang_default, [["__scopeId", "data-v-651e183a"]]);
//#endregion
export { ToastContainer_default as default };

import { computed, defineComponent, mergeProps, useSSRContext } from "vue";
import { ssrIncludeBooleanAttr, ssrInterpolate, ssrRenderAttr, ssrRenderAttrs, ssrRenderClass, ssrRenderList } from "vue/server-renderer";
//#region resources/js/Components/UI/Pagination.vue?vue&type=script&setup=true&lang.ts
var Pagination_vue_vue_type_script_setup_true_lang_default = /* @__PURE__ */ defineComponent({
	__name: "Pagination",
	__ssrInlineRender: true,
	props: {
		currentPage: {},
		totalPages: {},
		maxVisible: { default: 5 }
	},
	emits: ["update:currentPage"],
	setup(__props, { emit: __emit }) {
		const props = __props;
		const pages = computed(() => {
			const range = [];
			const start = Math.max(1, props.currentPage - Math.floor(props.maxVisible / 2));
			const end = Math.min(props.totalPages, start + props.maxVisible - 1);
			if (start > 1) {
				range.push(1);
				if (start > 2) range.push("...");
			}
			for (let i = start; i <= end; i++) range.push(i);
			if (end < props.totalPages) {
				if (end < props.totalPages - 1) range.push("...");
				range.push(props.totalPages);
			}
			return range;
		});
		return (_ctx, _push, _parent, _attrs) => {
			_push(`<nav${ssrRenderAttrs(mergeProps({
				class: "flex items-center justify-center space-x-2 py-4",
				"aria-label": "Pagination"
			}, _attrs))}><button${ssrIncludeBooleanAttr(__props.currentPage === 1) ? " disabled" : ""} class="w-10 h-10 flex items-center justify-center rounded-xl bg-surface-low dark:bg-surface-container-low border border-surface-container-high dark:border-outline-variant/30 text-on-surface dark:text-white hover:bg-surface-container-high dark:hover:bg-outline-variant/20 disabled:opacity-30 disabled:cursor-not-allowed transition-all active:scale-90" aria-label="Anterior"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path></svg></button><div class="flex items-center gap-1.5"><!--[-->`);
			ssrRenderList(pages.value, (page, index) => {
				_push(`<!--[-->`);
				if (page === "...") _push(`<span class="w-8 text-center text-on-surface/40 dark:text-white/40 font-bold">${ssrInterpolate(page)}</span>`);
				else _push(`<button class="${ssrRenderClass([[page === __props.currentPage ? "bg-primary text-on-primary shadow-lg dark:bg-secondary dark:text-primary" : "bg-surface-low dark:bg-surface-container-low border border-surface-container-high dark:border-outline-variant/30 text-on-surface dark:text-white hover:bg-surface-container-high dark:hover:bg-outline-variant/20"], "w-10 h-10 rounded-xl text-sm font-black transition-all duration-300 active:scale-95"])}"${ssrRenderAttr("aria-current", page === __props.currentPage ? "page" : void 0)}>${ssrInterpolate(page)}</button>`);
				_push(`<!--]-->`);
			});
			_push(`<!--]--></div><button${ssrIncludeBooleanAttr(__props.currentPage === __props.totalPages) ? " disabled" : ""} class="w-10 h-10 flex items-center justify-center rounded-xl bg-surface-low dark:bg-surface-container-low border border-surface-container-high dark:border-outline-variant/30 text-on-surface dark:text-white hover:bg-surface-container-high dark:hover:bg-outline-variant/20 disabled:opacity-30 disabled:cursor-not-allowed transition-all active:scale-90" aria-label="Siguiente"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path></svg></button></nav>`);
		};
	}
});
//#endregion
//#region resources/js/Components/UI/Pagination.vue
var _sfc_setup = Pagination_vue_vue_type_script_setup_true_lang_default.setup;
Pagination_vue_vue_type_script_setup_true_lang_default.setup = (props, ctx) => {
	const ssrContext = useSSRContext();
	(ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/UI/Pagination.vue");
	return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
var Pagination_default = Pagination_vue_vue_type_script_setup_true_lang_default;
//#endregion
export { Pagination_default as t };

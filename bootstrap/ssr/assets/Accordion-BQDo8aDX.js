import { defineComponent, mergeProps, ref, useSSRContext } from "vue";
import { ssrInterpolate, ssrRenderAttr, ssrRenderAttrs, ssrRenderClass, ssrRenderList, ssrRenderSlot, ssrRenderStyle } from "vue/server-renderer";
//#region resources/js/Components/UI/Accordion.vue?vue&type=script&setup=true&lang.ts
var Accordion_vue_vue_type_script_setup_true_lang_default = /* @__PURE__ */ defineComponent({
	__name: "Accordion",
	__ssrInlineRender: true,
	props: {
		items: {},
		allowMultiple: {
			type: Boolean,
			default: false
		}
	},
	setup(__props) {
		const activeItems = ref([]);
		const isActive = (id) => activeItems.value.includes(id);
		return (_ctx, _push, _parent, _attrs) => {
			_push(`<div${ssrRenderAttrs(mergeProps({ class: "flex flex-col gap-3 w-full" }, _attrs))}><!--[-->`);
			ssrRenderList(__props.items, (item, index) => {
				_push(`<div class="${ssrRenderClass([{ "shadow-md border-secondary/30 dark:border-accent/30": isActive(item.id || index) }, "bg-surface-low dark:bg-surface-container-low border border-surface-container-high dark:border-outline-variant/30 rounded-2xl overflow-hidden transition-all duration-300"])}"><button class="w-full px-6 py-4 flex justify-between items-center text-on-surface dark:text-white focus:outline-none group transition-colors"${ssrRenderAttr("aria-expanded", isActive(item.id || index))}><span class="font-bold text-sm tracking-tight group-hover:text-secondary transition-colors">${ssrInterpolate(item.title)}</span><div class="${ssrRenderClass([{ "rotate-180 bg-secondary/10 text-secondary": isActive(item.id || index) }, "w-8 h-8 rounded-lg bg-surface-container-high dark:bg-outline-variant/20 flex items-center justify-center transition-all duration-300"])}"><svg class="w-5 h-5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path></svg></div></button><div style="${ssrRenderStyle(isActive(item.id || index) ? null : { display: "none" })}"><div class="px-6 pb-5 pt-1 text-on-surface/80 dark:text-on-surface opacity-90 text-sm leading-relaxed border-t border-surface-container-high/50 dark:border-outline-variant/10">`);
				ssrRenderSlot(_ctx.$slots, "item-" + (item.id || index), {}, () => {
					_push(`${ssrInterpolate(item.content)}`);
				}, _push, _parent);
				_push(`</div></div></div>`);
			});
			_push(`<!--]--></div>`);
		};
	}
});
//#endregion
//#region resources/js/Components/UI/Accordion.vue
var _sfc_setup = Accordion_vue_vue_type_script_setup_true_lang_default.setup;
Accordion_vue_vue_type_script_setup_true_lang_default.setup = (props, ctx) => {
	const ssrContext = useSSRContext();
	(ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/UI/Accordion.vue");
	return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
var Accordion_default = Accordion_vue_vue_type_script_setup_true_lang_default;
//#endregion
export { Accordion_default as t };

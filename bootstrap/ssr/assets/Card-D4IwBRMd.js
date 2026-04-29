import { t as _plugin_vue_export_helper_default } from "./_plugin-vue_export-helper-BjJHhBxR.js";
import { computed, defineComponent, mergeProps, useSSRContext, useSlots } from "vue";
import { ssrInterpolate, ssrRenderAttr, ssrRenderAttrs, ssrRenderClass, ssrRenderSlot } from "vue/server-renderer";
//#region resources/js/Components/UI/Card.vue?vue&type=script&setup=true&lang.ts
var Card_vue_vue_type_script_setup_true_lang_default = /* @__PURE__ */ defineComponent({
	__name: "Card",
	__ssrInlineRender: true,
	props: {
		title: {},
		subtitle: {},
		icon: {},
		hover: {
			type: Boolean,
			default: false
		},
		class: {},
		contentClass: {},
		img: {}
	},
	setup(__props) {
		const props = __props;
		const slots = useSlots();
		const classes = computed(() => [
			"group premium-card overflow-hidden relative flex flex-col transition-all duration-300",
			props.hover ? "hover:-translate-y-1 hover:premium-elevated" : "",
			props.class
		]);
		const hasHeader = computed(() => !!props.title || !!props.icon || !!props.img || !!slots.header);
		return (_ctx, _push, _parent, _attrs) => {
			_push(`<div${ssrRenderAttrs(mergeProps({ class: classes.value }, _attrs))} data-v-430056d0>`);
			if (__props.img) _push(`<div class="w-full h-48 overflow-hidden relative" data-v-430056d0><div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent z-10 pointer-events-none" data-v-430056d0></div><img${ssrRenderAttr("src", __props.img)} class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"${ssrRenderAttr("alt", __props.title || "Card image")} data-v-430056d0></div>`);
			else _push(`<!---->`);
			if (__props.icon && !__props.img) _push(`<div class="absolute top-0 left-0 w-1.5 h-full bg-brand-gradient z-10" data-v-430056d0></div>`);
			else _push(`<!---->`);
			if (hasHeader.value) {
				_push(`<header class="px-6 pt-6 pb-4 flex justify-between items-start relative z-20" data-v-430056d0><div class="flex items-center gap-4" data-v-430056d0>`);
				if (__props.icon) _push(`<div class="w-12 h-12 bg-primary/5 dark:bg-white/5 text-primary dark:text-secondary flex items-center justify-center rounded-xl border border-outline-variant/20 shadow-inner transition-transform duration-300 group-hover:scale-110 group-hover:rotate-3" data-v-430056d0><span class="material-symbols-outlined text-[24px]" aria-hidden="true" data-v-430056d0>${ssrInterpolate(__props.icon)}</span></div>`);
				else _push(`<!---->`);
				_push(`<div data-v-430056d0>`);
				if (__props.title) _push(`<h3 class="text-lg font-bold text-on-surface leading-tight tracking-tight" data-v-430056d0>${ssrInterpolate(__props.title)}</h3>`);
				else _push(`<!---->`);
				if (__props.subtitle) _push(`<p class="text-xs text-on-surface-variant font-bold uppercase tracking-widest mt-1" data-v-430056d0>${ssrInterpolate(__props.subtitle)}</p>`);
				else _push(`<!---->`);
				_push(`</div></div>`);
				if (_ctx.$slots.header) {
					_push(`<div class="flex items-center gap-2" data-v-430056d0>`);
					ssrRenderSlot(_ctx.$slots, "header", {}, null, _push, _parent);
					_push(`</div>`);
				} else _push(`<!---->`);
				_push(`</header>`);
			} else _push(`<!---->`);
			_push(`<div class="${ssrRenderClass([
				"px-6 pb-6 flex-grow",
				!hasHeader.value ? "pt-6" : "",
				props.contentClass
			])}" data-v-430056d0><div class="text-sm text-on-surface/80 dark:text-on-surface opacity-90 leading-relaxed" data-v-430056d0>`);
			ssrRenderSlot(_ctx.$slots, "default", {}, null, _push, _parent);
			_push(`</div></div>`);
			if (_ctx.$slots.footer) {
				_push(`<footer class="px-6 py-4 bg-surface-container-low border-t border-outline-variant/30 mt-auto" data-v-430056d0>`);
				ssrRenderSlot(_ctx.$slots, "footer", {}, null, _push, _parent);
				_push(`</footer>`);
			} else _push(`<!---->`);
			_push(`</div>`);
		};
	}
});
//#endregion
//#region resources/js/Components/UI/Card.vue
var _sfc_setup = Card_vue_vue_type_script_setup_true_lang_default.setup;
Card_vue_vue_type_script_setup_true_lang_default.setup = (props, ctx) => {
	const ssrContext = useSSRContext();
	(ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/UI/Card.vue");
	return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
var Card_default = /* @__PURE__ */ _plugin_vue_export_helper_default(Card_vue_vue_type_script_setup_true_lang_default, [["__scopeId", "data-v-430056d0"]]);
//#endregion
export { Card_default as t };

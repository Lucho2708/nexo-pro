import { t as _plugin_vue_export_helper_default } from "./_plugin-vue_export-helper-BjJHhBxR.js";
import { createBlock, createCommentVNode, createTextVNode, defineComponent, mergeProps, onMounted, onUnmounted, openBlock, ref, toDisplayString, unref, useSSRContext, withCtx } from "vue";
import { Link } from "@inertiajs/vue3";
import { ssrInterpolate, ssrRenderAttr, ssrRenderAttrs, ssrRenderClass, ssrRenderComponent, ssrRenderList, ssrRenderSlot } from "vue/server-renderer";
//#region resources/js/Components/UI/Dropdown.vue?vue&type=script&setup=true&lang.ts
var Dropdown_vue_vue_type_script_setup_true_lang_default = /* @__PURE__ */ defineComponent({
	__name: "Dropdown",
	__ssrInlineRender: true,
	props: {
		label: {},
		items: {},
		align: { default: "right" },
		width: { default: "w-56" }
	},
	setup(__props) {
		const isOpen = ref(false);
		const dropdownRef = ref(null);
		const toggle = () => isOpen.value = !isOpen.value;
		const close = () => isOpen.value = false;
		const handleClickOutside = (event) => {
			if (dropdownRef.value && !dropdownRef.value.contains(event.target)) close();
		};
		onMounted(() => {
			document.addEventListener("click", handleClickOutside);
		});
		onUnmounted(() => {
			document.removeEventListener("click", handleClickOutside);
		});
		return (_ctx, _push, _parent, _attrs) => {
			_push(`<div${ssrRenderAttrs(mergeProps({
				class: "relative inline-block text-left",
				ref_key: "dropdownRef",
				ref: dropdownRef
			}, _attrs))} data-v-205302bc>`);
			ssrRenderSlot(_ctx.$slots, "trigger", {
				toggle,
				isOpen: isOpen.value
			}, () => {
				_push(`<button class="inline-flex items-center px-5 py-2.5 bg-surface-low dark:bg-surface-container-low border border-surface-container-high dark:border-outline-variant/30 rounded-xl font-bold text-sm text-on-surface dark:text-white hover:bg-surface-container-high dark:hover:bg-outline-variant/20 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-secondary/50"${ssrRenderAttr("aria-expanded", isOpen.value)} data-v-205302bc>${ssrInterpolate(__props.label)} <svg class="${ssrRenderClass([{ "rotate-180": isOpen.value }, "w-4 h-4 ml-2 transition-transform duration-300"])}" fill="none" stroke="currentColor" viewBox="0 0 24 24" data-v-205302bc><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" data-v-205302bc></path></svg></button>`);
			}, _push, _parent);
			if (isOpen.value) {
				_push(`<div class="${ssrRenderClass([[__props.width, __props.align === "right" ? "right-0" : "left-0"], "absolute z-50 mt-2 premium-elevated rounded-2xl overflow-hidden py-2 backdrop-blur-xl"])}" data-v-205302bc>`);
				ssrRenderSlot(_ctx.$slots, "default", {}, () => {
					_push(`<!--[-->`);
					ssrRenderList(__props.items, (item, index) => {
						_push(`<!--[-->`);
						if (item.separator) _push(`<div class="my-1 border-t border-surface-container-high dark:border-outline-variant/10" data-v-205302bc></div>`);
						else _push(`<!---->`);
						if (item.href) _push(ssrRenderComponent(unref(Link), {
							href: item.href,
							class: ["flex items-center px-4 py-2.5 text-sm font-medium transition-colors duration-200 group", [item.variant === "error" ? "text-error hover:bg-error/10" : "text-on-surface-variant hover:bg-surface-container-low hover:text-on-surface"]],
							onClick: close
						}, {
							default: withCtx((_, _push, _parent, _scopeId) => {
								if (_push) {
									if (item.icon) _push(`<span class="material-symbols-outlined mr-3 text-[18px] opacity-70 group-hover:opacity-100" data-v-205302bc${_scopeId}>${ssrInterpolate(item.icon)}</span>`);
									else _push(`<!---->`);
									_push(` ${ssrInterpolate(item.label)}`);
								} else return [item.icon ? (openBlock(), createBlock("span", {
									key: 0,
									class: "material-symbols-outlined mr-3 text-[18px] opacity-70 group-hover:opacity-100"
								}, toDisplayString(item.icon), 1)) : createCommentVNode("", true), createTextVNode(" " + toDisplayString(item.label), 1)];
							}),
							_: 2
						}, _parent));
						else {
							_push(`<button class="${ssrRenderClass([[item.variant === "error" ? "text-error hover:bg-error/10" : "text-on-surface-variant hover:bg-surface-container-low hover:text-on-surface"], "w-full flex items-center px-4 py-2.5 text-sm text-left font-medium transition-colors duration-200 group"])}" data-v-205302bc>`);
							if (item.icon) _push(`<span class="material-symbols-outlined mr-3 text-[18px] opacity-70 group-hover:opacity-100" data-v-205302bc>${ssrInterpolate(item.icon)}</span>`);
							else _push(`<!---->`);
							_push(` ${ssrInterpolate(item.label)}</button>`);
						}
						_push(`<!--]-->`);
					});
					_push(`<!--]-->`);
				}, _push, _parent);
				_push(`</div>`);
			} else _push(`<!---->`);
			_push(`</div>`);
		};
	}
});
//#endregion
//#region resources/js/Components/UI/Dropdown.vue
var _sfc_setup = Dropdown_vue_vue_type_script_setup_true_lang_default.setup;
Dropdown_vue_vue_type_script_setup_true_lang_default.setup = (props, ctx) => {
	const ssrContext = useSSRContext();
	(ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/UI/Dropdown.vue");
	return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
var Dropdown_default = /* @__PURE__ */ _plugin_vue_export_helper_default(Dropdown_vue_vue_type_script_setup_true_lang_default, [["__scopeId", "data-v-205302bc"]]);
//#endregion
export { Dropdown_default as t };

import { t as _plugin_vue_export_helper_default } from "./_plugin-vue_export-helper-BjJHhBxR.js";
import { defineComponent, mergeProps, onUnmounted, useSSRContext, watch } from "vue";
import { ssrInterpolate, ssrRenderAttrs, ssrRenderClass, ssrRenderComponent, ssrRenderSlot, ssrRenderStyle, ssrRenderTeleport } from "vue/server-renderer";
//#region resources/js/Components/UI/CloseButton.vue?vue&type=script&setup=true&lang.ts
var CloseButton_vue_vue_type_script_setup_true_lang_default = /* @__PURE__ */ defineComponent({
	__name: "CloseButton",
	__ssrInlineRender: true,
	props: { size: { default: "md" } },
	emits: ["click"],
	setup(__props, { emit: __emit }) {
		return (_ctx, _push, _parent, _attrs) => {
			_push(`<button${ssrRenderAttrs(mergeProps({
				type: "button",
				class: ["inline-flex items-center justify-center rounded-xl text-on-surface/50 hover:text-on-surface hover:bg-surface-container-high dark:text-white/50 dark:hover:text-white dark:hover:bg-outline-variant/30 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-secondary/50 active:scale-90", {
					"w-8 h-8": __props.size === "sm",
					"w-10 h-10": __props.size === "md",
					"w-12 h-12": __props.size === "lg"
				}],
				"aria-label": "Cerrar"
			}, _attrs))}><svg class="${ssrRenderClass([{
				"w-4 h-4": __props.size === "sm",
				"w-5 h-5": __props.size === "md",
				"w-6 h-6": __props.size === "lg"
			}, "transition-transform duration-300 group-hover:rotate-90"])}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg></button>`);
		};
	}
});
//#endregion
//#region resources/js/Components/UI/CloseButton.vue
var _sfc_setup$1 = CloseButton_vue_vue_type_script_setup_true_lang_default.setup;
CloseButton_vue_vue_type_script_setup_true_lang_default.setup = (props, ctx) => {
	const ssrContext = useSSRContext();
	(ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/UI/CloseButton.vue");
	return _sfc_setup$1 ? _sfc_setup$1(props, ctx) : void 0;
};
var CloseButton_default = CloseButton_vue_vue_type_script_setup_true_lang_default;
//#endregion
//#region resources/js/Components/UI/Modal.vue?vue&type=script&setup=true&lang.ts
var Modal_vue_vue_type_script_setup_true_lang_default = /* @__PURE__ */ defineComponent({
	__name: "Modal",
	__ssrInlineRender: true,
	props: {
		show: {
			type: Boolean,
			default: false
		},
		title: {},
		showClose: {
			type: Boolean,
			default: true
		},
		maxWidth: { default: "md" },
		closeable: {
			type: Boolean,
			default: true
		}
	},
	emits: ["close"],
	setup(__props, { emit: __emit }) {
		const props = __props;
		const emit = __emit;
		const close = () => {
			if (props.closeable) emit("close");
		};
		const maxWidthClasses = {
			sm: "sm:max-w-sm",
			md: "sm:max-w-md",
			lg: "sm:max-w-lg",
			xl: "sm:max-w-xl",
			"2xl": "sm:max-w-2xl",
			"4xl": "sm:max-w-4xl",
			full: "sm:max-w-full sm:m-4"
		};
		watch(() => props.show, (value) => {
			if (value) document.body.style.overflow = "hidden";
			else document.body.style.overflow = "";
		}, { immediate: true });
		onUnmounted(() => {
			document.body.style.overflow = "";
		});
		const handleKeydown = (e) => {
			if (e.key === "Escape" && props.show) close();
		};
		window.addEventListener("keydown", handleKeydown);
		onUnmounted(() => window.removeEventListener("keydown", handleKeydown));
		return (_ctx, _push, _parent, _attrs) => {
			ssrRenderTeleport(_push, (_push) => {
				_push(`<div class="fixed inset-0 z-[100] flex flex-col justify-end sm:justify-center overflow-hidden" style="${ssrRenderStyle(__props.show ? null : { display: "none" })}" data-v-b75d54c6><template>`);
				if (__props.show) _push(`<div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" data-v-b75d54c6></div>`);
				else _push(`<!---->`);
				_push(`</template><template>`);
				if (__props.show) {
					_push(`<div class="${ssrRenderClass([[maxWidthClasses[__props.maxWidth]], "relative w-full transform overflow-hidden bg-white text-left transition-all rounded-t-[3rem] sm:rounded-[3rem] shadow-2xl sm:mx-auto max-h-[90vh] sm:max-h-[85vh] flex flex-col"])}" data-v-b75d54c6><div class="sm:hidden w-12 h-1 bg-slate-200 rounded-full mx-auto mt-4 shrink-0" data-v-b75d54c6></div>`);
					if (__props.title || __props.showClose) {
						_push(`<div class="px-6 sm:px-12 pt-6 sm:pt-12 pb-2 flex items-center justify-between shrink-0" data-v-b75d54c6><div class="flex-1" data-v-b75d54c6>`);
						if (__props.title) _push(`<h3 class="text-2xl sm:text-3xl font-black text-on-surface uppercase tracking-tighter leading-tight" data-v-b75d54c6>${ssrInterpolate(__props.title)}</h3>`);
						else _push(`<!---->`);
						_push(`<div class="w-12 h-1.5 bg-primary mt-2 rounded-full hidden sm:block" data-v-b75d54c6></div></div><div class="ml-4" data-v-b75d54c6>`);
						if (__props.showClose) _push(ssrRenderComponent(CloseButton_default, {
							onClick: close,
							class: "!bg-surface-container-high hover:!bg-error/10 hover:!text-error"
						}, null, _parent));
						else _push(`<!---->`);
						_push(`</div></div>`);
					} else _push(`<!---->`);
					_push(`<div class="px-6 sm:px-12 pb-10 sm:pb-12 pt-4 overflow-y-auto custom-scrollbar flex-1" data-v-b75d54c6>`);
					ssrRenderSlot(_ctx.$slots, "default", {}, null, _push, _parent);
					_push(`</div></div>`);
				} else _push(`<!---->`);
				_push(`</template></div>`);
			}, "body", false, _parent);
		};
	}
});
//#endregion
//#region resources/js/Components/UI/Modal.vue
var _sfc_setup = Modal_vue_vue_type_script_setup_true_lang_default.setup;
Modal_vue_vue_type_script_setup_true_lang_default.setup = (props, ctx) => {
	const ssrContext = useSSRContext();
	(ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/UI/Modal.vue");
	return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
var Modal_default = /* @__PURE__ */ _plugin_vue_export_helper_default(Modal_vue_vue_type_script_setup_true_lang_default, [["__scopeId", "data-v-b75d54c6"]]);
//#endregion
export { Modal_default as t };

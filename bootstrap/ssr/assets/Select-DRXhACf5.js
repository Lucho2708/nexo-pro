import { computed, defineComponent, mergeProps, onMounted, onUnmounted, ref, useSSRContext, watch } from "vue";
import { ssrIncludeBooleanAttr, ssrInterpolate, ssrRenderAttrs, ssrRenderClass, ssrRenderList, ssrRenderStyle, ssrRenderTeleport } from "vue/server-renderer";
//#region resources/js/Components/UI/Select.vue?vue&type=script&setup=true&lang.ts
var Select_vue_vue_type_script_setup_true_lang_default = /* @__PURE__ */ defineComponent({
	__name: "Select",
	__ssrInlineRender: true,
	props: {
		modelValue: {},
		options: {},
		label: {},
		placeholder: { default: "Seleccionar..." },
		icon: {},
		error: {},
		state: { default: "default" },
		disabled: {
			type: Boolean,
			default: false
		}
	},
	emits: ["update:modelValue", "change"],
	setup(__props, { emit: __emit }) {
		const props = __props;
		const isOpen = ref(false);
		const triggerRef = ref(null);
		const menuRef = ref(null);
		const dropdownStyle = ref({
			top: "0px",
			left: "0px",
			width: "auto"
		});
		const localValue = ref(props.modelValue);
		watch(() => props.modelValue, (newVal) => {
			localValue.value = newVal;
		});
		const selectedOption = computed(() => {
			return props.options.find((opt) => opt.value === localValue.value) || null;
		});
		const currentState = computed(() => {
			if (props.error) return "error";
			return props.state;
		});
		const stateConfig = {
			error: "border-error/50",
			success: "border-success/50",
			warning: "border-warning/50",
			default: "border-outline-variant/10 focus:ring-4 focus:ring-primary/10 hover:border-primary/30"
		};
		const updatePosition = () => {
			if (!triggerRef.value) return;
			const rect = triggerRef.value.getBoundingClientRect();
			let left = rect.left;
			const width = Math.max(rect.width, 200);
			if (left + width > window.innerWidth) left = window.innerWidth - width - 20;
			dropdownStyle.value = {
				top: `${rect.bottom + 4}px`,
				left: `${left}px`,
				width: `${width}px`
			};
		};
		const closeMenu = () => {
			isOpen.value = false;
			window.removeEventListener("scroll", updatePosition, true);
			window.removeEventListener("resize", updatePosition);
		};
		const closeOutside = (e) => {
			if (isOpen.value && triggerRef.value && !triggerRef.value.contains(e.target) && menuRef.value && !menuRef.value.contains(e.target)) closeMenu();
		};
		onMounted(() => document.addEventListener("mousedown", closeOutside));
		onUnmounted(() => {
			document.removeEventListener("mousedown", closeOutside);
			closeMenu();
		});
		return (_ctx, _push, _parent, _attrs) => {
			_push(`<div${ssrRenderAttrs(mergeProps({ class: "space-y-1.5 w-full relative" }, _attrs))}>`);
			if (__props.label) _push(`<label class="text-[10px] font-black text-on-surface-variant/60 uppercase tracking-widest pl-1">${ssrInterpolate(__props.label)}</label>`);
			else _push(`<!---->`);
			_push(`<div class="relative"><button type="button"${ssrIncludeBooleanAttr(__props.disabled) ? " disabled" : ""} class="${ssrRenderClass([[stateConfig[currentState.value], __props.disabled ? "opacity-50 cursor-not-allowed" : "cursor-pointer"], "w-full bg-surface-container-low border-2 rounded-2xl p-4 text-sm font-medium transition-all outline-none flex items-center justify-between group"])}"><div class="flex items-center gap-3 pointer-events-none">`);
			if (__props.icon) _push(`<span class="material-symbols-outlined text-on-surface-variant/40 group-hover:text-primary transition-colors">${ssrInterpolate(__props.icon)}</span>`);
			else _push(`<!---->`);
			_push(`<span class="${ssrRenderClass([selectedOption.value ? "text-on-surface" : "text-on-surface-variant/40", "whitespace-nowrap truncate max-w-[180px] sm:max-w-none"])}">${ssrInterpolate(selectedOption.value ? selectedOption.value.label : __props.placeholder)}</span></div><span class="${ssrRenderClass([{ "rotate-180 text-primary": isOpen.value }, "material-symbols-outlined text-on-surface-variant/40 transition-transform duration-300 pointer-events-none"])}"> expand_more </span></button></div>`);
			ssrRenderTeleport(_push, (_push) => {
				if (isOpen.value) {
					_push(`<div style="${ssrRenderStyle(dropdownStyle.value)}" class="fixed z-[9999] bg-surface border border-outline-variant/20 rounded-2xl shadow-2xl overflow-hidden py-2 max-h-60 overflow-y-auto no-scrollbar"><!--[-->`);
					ssrRenderList(__props.options, (option) => {
						_push(`<div class="${ssrRenderClass([[localValue.value === option.value ? "bg-primary/10 text-primary" : "text-on-surface-variant hover:bg-surface-container-low"], "px-5 py-3 text-sm font-bold cursor-pointer transition-colors"])}">${ssrInterpolate(option.label)}</div>`);
					});
					_push(`<!--]-->`);
					if (__props.options.length === 0) _push(`<div class="px-5 py-3 text-xs text-on-surface-variant/40 text-center italic"> No hay opciones disponibles </div>`);
					else _push(`<!---->`);
					_push(`</div>`);
				} else _push(`<!---->`);
			}, "body", false, _parent);
			if (__props.error) _push(`<p class="text-[9px] font-bold text-error uppercase tracking-widest pl-1">${ssrInterpolate(__props.error)}</p>`);
			else _push(`<!---->`);
			_push(`</div>`);
		};
	}
});
//#endregion
//#region resources/js/Components/UI/Select.vue
var _sfc_setup = Select_vue_vue_type_script_setup_true_lang_default.setup;
Select_vue_vue_type_script_setup_true_lang_default.setup = (props, ctx) => {
	const ssrContext = useSSRContext();
	(ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/UI/Select.vue");
	return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
var Select_default = Select_vue_vue_type_script_setup_true_lang_default;
//#endregion
export { Select_default as t };

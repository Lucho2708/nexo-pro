import { computed, defineComponent, mergeProps, onMounted, onUnmounted, ref, useSSRContext, watch } from "vue";
import { ssrIncludeBooleanAttr, ssrInterpolate, ssrRenderAttrs, ssrRenderClass, ssrRenderList, ssrRenderStyle, ssrRenderTeleport } from "vue/server-renderer";
//#region resources/js/Components/UI/DatePicker.vue?vue&type=script&setup=true&lang.ts
var DatePicker_vue_vue_type_script_setup_true_lang_default = /* @__PURE__ */ defineComponent({
	__name: "DatePicker",
	__ssrInlineRender: true,
	props: {
		modelValue: {},
		label: {},
		icon: { default: "calendar_today" },
		error: {},
		disabled: {
			type: Boolean,
			default: false
		}
	},
	emits: ["update:modelValue"],
	setup(__props, { emit: __emit }) {
		const props = __props;
		const isOpen = ref(false);
		const triggerRef = ref(null);
		const calendarRef = ref(null);
		const viewDate = ref(/* @__PURE__ */ new Date());
		const dropdownStyle = ref({
			top: "0px",
			left: "0px",
			width: "auto"
		});
		const localValue = ref(props.modelValue);
		watch(() => props.modelValue, (newVal) => {
			localValue.value = newVal;
		});
		const months = [
			"Enero",
			"Febrero",
			"Marzo",
			"Abril",
			"Mayo",
			"Junio",
			"Julio",
			"Agosto",
			"Septiembre",
			"Octubre",
			"Noviembre",
			"Diciembre"
		];
		const days = [
			"Do",
			"Lu",
			"Ma",
			"Mi",
			"Ju",
			"Vi",
			"Sa"
		];
		const calendarDays = computed(() => {
			const year = viewDate.value.getFullYear();
			const month = viewDate.value.getMonth();
			const startDay = new Date(year, month, 1).getDay();
			const daysInMonth = new Date(year, month + 1, 0).getDate();
			const daysInPrevMonth = new Date(year, month, 0).getDate();
			const prev = [];
			for (let i = startDay - 1; i >= 0; i--) prev.push({
				day: daysInPrevMonth - i,
				current: false,
				date: new Date(year, month - 1, daysInPrevMonth - i)
			});
			const current = [];
			for (let i = 1; i <= daysInMonth; i++) current.push({
				day: i,
				current: true,
				date: new Date(year, month, i)
			});
			const next = [];
			const remaining = 42 - (prev.length + current.length);
			for (let i = 1; i <= remaining; i++) next.push({
				day: i,
				current: false,
				date: new Date(year, month + 1, i)
			});
			return [
				...prev,
				...current,
				...next
			];
		});
		const updatePosition = () => {
			if (!triggerRef.value) return;
			const rect = triggerRef.value.getBoundingClientRect();
			let left = rect.left;
			const width = 280;
			if (left + width > window.innerWidth) left = window.innerWidth - width - 20;
			dropdownStyle.value = {
				top: `${rect.bottom + 8}px`,
				left: `${left}px`,
				width: `${width}px`
			};
		};
		const closeCalendar = () => {
			isOpen.value = false;
			window.removeEventListener("scroll", updatePosition, true);
			window.removeEventListener("resize", updatePosition);
		};
		const isSelected = (date) => {
			if (!localValue.value) return false;
			const d = /* @__PURE__ */ new Date(localValue.value + "T00:00:00");
			return date.toDateString() === d.toDateString();
		};
		const isToday = (date) => date.toDateString() === (/* @__PURE__ */ new Date()).toDateString();
		const formatDateDisplay = (dateString) => {
			if (!dateString) return "Seleccionar fecha";
			const date = /* @__PURE__ */ new Date(dateString + "T00:00:00");
			if (isNaN(date.getTime())) return "Seleccionar fecha";
			return date.toLocaleDateString("es-ES", {
				day: "2-digit",
				month: "long",
				year: "numeric"
			});
		};
		const handleOutsideClick = (e) => {
			if (isOpen.value && triggerRef.value && !triggerRef.value.contains(e.target) && calendarRef.value && !calendarRef.value.contains(e.target)) closeCalendar();
		};
		onMounted(() => document.addEventListener("mousedown", handleOutsideClick));
		onUnmounted(() => {
			document.removeEventListener("mousedown", handleOutsideClick);
			closeCalendar();
		});
		return (_ctx, _push, _parent, _attrs) => {
			_push(`<div${ssrRenderAttrs(mergeProps({ class: "space-y-1.5 w-full relative" }, _attrs))}>`);
			if (__props.label) _push(`<label class="text-[10px] font-black text-on-surface-variant/60 uppercase tracking-widest pl-1">${ssrInterpolate(__props.label)}</label>`);
			else _push(`<!---->`);
			_push(`<div class="relative"><button type="button"${ssrIncludeBooleanAttr(__props.disabled) ? " disabled" : ""} class="${ssrRenderClass([[__props.error ? "border-error/50" : "border-outline-variant/10 focus:ring-4 focus:ring-primary/10", __props.disabled ? "opacity-50 cursor-not-allowed" : "cursor-pointer hover:border-primary/30"], "w-full bg-surface-container-low border-2 rounded-2xl p-4 text-sm font-medium transition-all outline-none flex items-center justify-between group"])}"><div class="flex items-center gap-3 pointer-events-none"><span class="material-symbols-outlined text-on-surface-variant/40 group-hover:text-primary transition-colors">${ssrInterpolate(__props.icon)}</span><span class="${ssrRenderClass(localValue.value ? "text-on-surface" : "text-on-surface-variant/40")}">${ssrInterpolate(formatDateDisplay(localValue.value))}</span></div><span class="${ssrRenderClass([{ "rotate-180 text-primary": isOpen.value }, "material-symbols-outlined text-on-surface-variant/40 transition-transform duration-300 pointer-events-none"])}"> calendar_month </span></button></div>`);
			ssrRenderTeleport(_push, (_push) => {
				if (isOpen.value) {
					_push(`<div style="${ssrRenderStyle(dropdownStyle.value)}" class="fixed z-[9999] bg-surface border border-outline-variant/20 rounded-[2rem] shadow-[0_20px_50px_rgba(0,0,0,0.3)] overflow-hidden p-5 select-none no-scrollbar"><div class="flex items-center justify-between mb-6"><button type="button" class="w-10 h-10 rounded-xl hover:bg-surface-container-low flex items-center justify-center text-on-surface-variant transition-colors"><span class="material-symbols-outlined">chevron_left</span></button><div class="text-center"><p class="text-xs font-black text-primary uppercase tracking-widest">${ssrInterpolate(months[viewDate.value.getMonth()])}</p><p class="text-[10px] font-bold text-on-surface-variant/40">${ssrInterpolate(viewDate.value.getFullYear())}</p></div><button type="button" class="w-10 h-10 rounded-xl hover:bg-surface-container-low flex items-center justify-center text-on-surface-variant transition-colors"><span class="material-symbols-outlined">chevron_right</span></button></div><div class="grid grid-cols-7 gap-1 mb-2"><!--[-->`);
					ssrRenderList(days, (day) => {
						_push(`<div class="text-[9px] font-black text-center text-on-surface-variant/30 uppercase py-2">${ssrInterpolate(day)}</div>`);
					});
					_push(`<!--]--></div><div class="grid grid-cols-7 gap-1"><!--[-->`);
					ssrRenderList(calendarDays.value, (dateObj, i) => {
						_push(`<button type="button" class="${ssrRenderClass([[
							!dateObj.current ? "text-on-surface-variant/20 opacity-50" : "text-on-surface",
							isSelected(dateObj.date) ? "bg-primary text-on-primary shadow-lg scale-110 z-10" : "hover:bg-primary/10 hover:text-primary",
							isToday(dateObj.date) && !isSelected(dateObj.date) ? "text-secondary" : ""
						], "aspect-square rounded-xl flex items-center justify-center text-xs font-bold transition-all relative"])}">${ssrInterpolate(dateObj.day)} `);
						if (isToday(dateObj.date)) _push(`<div class="absolute bottom-1.5 w-1 h-1 rounded-full bg-secondary"></div>`);
						else _push(`<!---->`);
						_push(`</button>`);
					});
					_push(`<!--]--></div><div class="mt-6 pt-4 border-t border-outline-variant/10 flex justify-between"><button type="button" class="text-[10px] font-black text-secondary uppercase tracking-widest hover:underline">Hoy</button><button type="button" class="text-[10px] font-black text-on-surface-variant/40 uppercase tracking-widest hover:text-on-surface transition-colors">Cerrar</button></div></div>`);
				} else _push(`<!---->`);
			}, "body", false, _parent);
			if (__props.error) _push(`<p class="text-[9px] font-bold text-error uppercase tracking-widest pl-1">${ssrInterpolate(__props.error)}</p>`);
			else _push(`<!---->`);
			_push(`</div>`);
		};
	}
});
//#endregion
//#region resources/js/Components/UI/DatePicker.vue
var _sfc_setup = DatePicker_vue_vue_type_script_setup_true_lang_default.setup;
DatePicker_vue_vue_type_script_setup_true_lang_default.setup = (props, ctx) => {
	const ssrContext = useSSRContext();
	(ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/UI/DatePicker.vue");
	return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
var DatePicker_default = DatePicker_vue_vue_type_script_setup_true_lang_default;
//#endregion
export { DatePicker_default as t };

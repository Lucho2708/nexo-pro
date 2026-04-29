import { t as _plugin_vue_export_helper_default } from "./_plugin-vue_export-helper-BjJHhBxR.js";
import { t as Table_default } from "./Table-DmKwoFFU.js";
import { t as useToast } from "./useToast-E_Hdzmc5.js";
import { t as AuthenticatedLayout_default } from "./AuthenticatedLayout-vxulH9w9.js";
import { t as Card_default } from "./Card-D4IwBRMd.js";
import { t as Button_default } from "./Button-C-byZbTR.js";
import { t as Modal_default } from "./Modal-DfwT9E3X.js";
import { t as Badge_default } from "./Badge-DsYztQFV.js";
import { t as Tooltip_default } from "./Tooltip-IAP-zsdE.js";
import { t as Select_default } from "./Select-DRXhACf5.js";
import { t as Input_default } from "./Input-CbVZZMpc.js";
import { t as Dropdown_default } from "./Dropdown-BzV9SsLU.js";
import { t as DatePicker_default } from "./DatePicker-DD5d8BKS.js";
import { createTextVNode, createVNode, defineComponent, mergeProps, onMounted, ref, toDisplayString, unref, useSSRContext, watch, withCtx, withModifiers } from "vue";
import { Head, router, useForm } from "@inertiajs/vue3";
import { ssrIncludeBooleanAttr, ssrInterpolate, ssrRenderAttrs, ssrRenderClass, ssrRenderComponent, ssrRenderStyle } from "vue/server-renderer";
//#region resources/js/Components/UI/Progress.vue?vue&type=script&setup=true&lang.ts
var Progress_vue_vue_type_script_setup_true_lang_default = /* @__PURE__ */ defineComponent({
	__name: "Progress",
	__ssrInlineRender: true,
	props: {
		percentage: {},
		variant: { default: "gradient" },
		showLabel: {
			type: Boolean,
			default: false
		},
		height: { default: "h-2.5" }
	},
	setup(__props) {
		return (_ctx, _push, _parent, _attrs) => {
			_push(`<div${ssrRenderAttrs(mergeProps({ class: "w-full" }, _attrs))}>`);
			if (__props.showLabel) _push(`<div class="flex justify-between mb-1"><span class="text-[10px] font-black uppercase tracking-widest text-on-surface-variant/60">Progreso</span><span class="text-[10px] font-black text-primary">${ssrInterpolate(__props.percentage)}%</span></div>`);
			else _push(`<!---->`);
			_push(`<div class="${ssrRenderClass([__props.height, "w-full bg-surface-container-high rounded-full overflow-hidden"])}"><div class="${ssrRenderClass([[__props.variant === "success" ? "bg-green-500" : __props.variant === "primary" ? "bg-primary" : "bg-[image:var(--bg-brand-gradient)] shadow-[0_0_15px_rgba(0,212,255,0.3)]"], "h-full rounded-full transition-all duration-1000 ease-out"])}" style="${ssrRenderStyle({ width: __props.percentage + "%" })}"></div></div></div>`);
		};
	}
});
//#endregion
//#region resources/js/Components/UI/Progress.vue
var _sfc_setup$3 = Progress_vue_vue_type_script_setup_true_lang_default.setup;
Progress_vue_vue_type_script_setup_true_lang_default.setup = (props, ctx) => {
	const ssrContext = useSSRContext();
	(ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/UI/Progress.vue");
	return _sfc_setup$3 ? _sfc_setup$3(props, ctx) : void 0;
};
var Progress_default = Progress_vue_vue_type_script_setup_true_lang_default;
//#endregion
//#region resources/js/Components/Cartera/PaymentModal.vue?vue&type=script&setup=true&lang.ts
var PaymentModal_vue_vue_type_script_setup_true_lang_default = /* @__PURE__ */ defineComponent({
	__name: "PaymentModal",
	__ssrInlineRender: true,
	props: {
		unidad: {},
		conceptos: {}
	},
	emits: ["close"],
	setup(__props, { emit: __emit }) {
		const props = __props;
		const emit = __emit;
		const fileInput = ref(null);
		const form = useForm({
			unidad_id: props.unidad?.id || "",
			concepto_id: "",
			monto: "",
			fecha: (/* @__PURE__ */ new Date()).toISOString().split("T")[0],
			referencia: "",
			soporte: null
		});
		const submit = () => {
			form.post(route("cartera.payment"), {
				onSuccess: () => emit("close"),
				forceFormData: true
			});
		};
		const handleFileChange = (e) => {
			const target = e.target;
			if (target.files && target.files.length > 0) form.soporte = target.files[0];
		};
		return (_ctx, _push, _parent, _attrs) => {
			_push(ssrRenderComponent(Modal_default, mergeProps({
				show: true,
				"max-width": "xl",
				onClose: ($event) => emit("close")
			}, _attrs), {
				default: withCtx((_, _push, _parent, _scopeId) => {
					if (_push) {
						_push(`<div class="mb-10" data-v-2ae856b5${_scopeId}><h2 class="text-3xl font-black text-primary tracking-tighter uppercase leading-none" data-v-2ae856b5${_scopeId}>Registrar Recaudo</h2><p class="text-on-surface-variant/60 text-sm font-medium mt-2" data-v-2ae856b5${_scopeId}>${ssrInterpolate(__props.unidad ? `Abono para: ${__props.unidad.torre} ${__props.unidad.nombre}` : "Ingrese los detalles del pago manual para la unidad.")}</p></div><form class="space-y-8" data-v-2ae856b5${_scopeId}><div class="grid grid-cols-1 md:grid-cols-2 gap-6" data-v-2ae856b5${_scopeId}><div class="col-span-1 md:col-span-2" data-v-2ae856b5${_scopeId}>`);
						_push(ssrRenderComponent(Select_default, {
							modelValue: unref(form).concepto_id,
							"onUpdate:modelValue": ($event) => unref(form).concepto_id = $event,
							label: "Concepto de Cobro",
							placeholder: "Seleccione el concepto...",
							icon: "receipt_long",
							options: __props.conceptos.map((c) => ({
								value: c.id,
								label: c.nombre
							})),
							error: unref(form).errors.concepto_id
						}, null, _parent, _scopeId));
						_push(`</div>`);
						_push(ssrRenderComponent(Input_default, {
							modelValue: unref(form).monto,
							"onUpdate:modelValue": ($event) => unref(form).monto = $event,
							label: "Valor Recibido",
							type: "number",
							icon: "payments",
							placeholder: "0.00",
							required: "",
							error: unref(form).errors.monto
						}, null, _parent, _scopeId));
						_push(ssrRenderComponent(DatePicker_default, {
							modelValue: unref(form).fecha,
							"onUpdate:modelValue": ($event) => unref(form).fecha = $event,
							label: "Fecha de Transacción",
							icon: "calendar_month",
							error: unref(form).errors.fecha
						}, null, _parent, _scopeId));
						_push(ssrRenderComponent(Input_default, {
							modelValue: unref(form).referencia,
							"onUpdate:modelValue": ($event) => unref(form).referencia = $event,
							label: "Referencia / Comprobante",
							placeholder: "No. de consignación",
							icon: "confirmation_number",
							class: "md:col-span-2",
							error: unref(form).errors.referencia
						}, null, _parent, _scopeId));
						_push(`</div><div class="space-y-2" data-v-2ae856b5${_scopeId}><label class="text-[10px] font-black text-on-surface-variant/60 uppercase tracking-widest pl-1" data-v-2ae856b5${_scopeId}>Adjuntar Soporte Digital</label><div class="border-2 border-dashed border-outline-variant/20 rounded-[2rem] p-10 text-center bg-surface-container-low/30 hover:bg-surface-container-low hover:border-primary/30 transition-all cursor-pointer group relative" data-v-2ae856b5${_scopeId}><input type="file" class="hidden" accept=".pdf,.jpg,.jpeg,.png" data-v-2ae856b5${_scopeId}><div class="flex flex-col items-center gap-3" data-v-2ae856b5${_scopeId}><div class="w-16 h-16 rounded-2xl bg-primary/5 text-primary flex items-center justify-center group-hover:scale-110 transition-transform" data-v-2ae856b5${_scopeId}><span class="material-symbols-outlined text-3xl" style="${ssrRenderStyle({ "font-variation-settings": "'FILL' 1" })}" data-v-2ae856b5${_scopeId}>cloud_upload</span></div><div data-v-2ae856b5${_scopeId}><p class="text-sm font-black text-primary uppercase tracking-tight" data-v-2ae856b5${_scopeId}>${ssrInterpolate(unref(form).soporte ? unref(form).soporte.name : "Subir comprobante")}</p><p class="text-[10px] text-on-surface-variant/40 mt-1 font-bold uppercase tracking-widest" data-v-2ae856b5${_scopeId}>PDF, JPG o PNG • Máximo 5MB</p></div></div></div></div><div class="flex flex-col sm:flex-row gap-4 pt-4" data-v-2ae856b5${_scopeId}>`);
						_push(ssrRenderComponent(Button_default, {
							variant: "ghost",
							class: "flex-1 order-2 sm:order-1",
							onClick: ($event) => emit("close")
						}, {
							default: withCtx((_, _push, _parent, _scopeId) => {
								if (_push) _push(` Cancelar `);
								else return [createTextVNode(" Cancelar ")];
							}),
							_: 1
						}, _parent, _scopeId));
						_push(ssrRenderComponent(Button_default, {
							type: "submit",
							variant: "secondary",
							class: "flex-1 order-1 sm:order-2",
							icon: "check_circle",
							loading: unref(form).processing
						}, {
							default: withCtx((_, _push, _parent, _scopeId) => {
								if (_push) _push(` Registrar Pago `);
								else return [createTextVNode(" Registrar Pago ")];
							}),
							_: 1
						}, _parent, _scopeId));
						_push(`</div><div class="flex items-center justify-center gap-2 py-4 border-t border-outline-variant/5" data-v-2ae856b5${_scopeId}><span class="material-symbols-outlined text-on-surface-variant/30 text-sm" data-v-2ae856b5${_scopeId}>verified_user</span><span class="text-[9px] font-black text-on-surface-variant/30 uppercase tracking-[0.2em]" data-v-2ae856b5${_scopeId}>Registro financiero auditado por NEXO-PRO</span></div></form>`);
					} else return [createVNode("div", { class: "mb-10" }, [createVNode("h2", { class: "text-3xl font-black text-primary tracking-tighter uppercase leading-none" }, "Registrar Recaudo"), createVNode("p", { class: "text-on-surface-variant/60 text-sm font-medium mt-2" }, toDisplayString(__props.unidad ? `Abono para: ${__props.unidad.torre} ${__props.unidad.nombre}` : "Ingrese los detalles del pago manual para la unidad."), 1)]), createVNode("form", {
						onSubmit: withModifiers(submit, ["prevent"]),
						class: "space-y-8"
					}, [
						createVNode("div", { class: "grid grid-cols-1 md:grid-cols-2 gap-6" }, [
							createVNode("div", { class: "col-span-1 md:col-span-2" }, [createVNode(Select_default, {
								modelValue: unref(form).concepto_id,
								"onUpdate:modelValue": ($event) => unref(form).concepto_id = $event,
								label: "Concepto de Cobro",
								placeholder: "Seleccione el concepto...",
								icon: "receipt_long",
								options: __props.conceptos.map((c) => ({
									value: c.id,
									label: c.nombre
								})),
								error: unref(form).errors.concepto_id
							}, null, 8, [
								"modelValue",
								"onUpdate:modelValue",
								"options",
								"error"
							])]),
							createVNode(Input_default, {
								modelValue: unref(form).monto,
								"onUpdate:modelValue": ($event) => unref(form).monto = $event,
								label: "Valor Recibido",
								type: "number",
								icon: "payments",
								placeholder: "0.00",
								required: "",
								error: unref(form).errors.monto
							}, null, 8, [
								"modelValue",
								"onUpdate:modelValue",
								"error"
							]),
							createVNode(DatePicker_default, {
								modelValue: unref(form).fecha,
								"onUpdate:modelValue": ($event) => unref(form).fecha = $event,
								label: "Fecha de Transacción",
								icon: "calendar_month",
								error: unref(form).errors.fecha
							}, null, 8, [
								"modelValue",
								"onUpdate:modelValue",
								"error"
							]),
							createVNode(Input_default, {
								modelValue: unref(form).referencia,
								"onUpdate:modelValue": ($event) => unref(form).referencia = $event,
								label: "Referencia / Comprobante",
								placeholder: "No. de consignación",
								icon: "confirmation_number",
								class: "md:col-span-2",
								error: unref(form).errors.referencia
							}, null, 8, [
								"modelValue",
								"onUpdate:modelValue",
								"error"
							])
						]),
						createVNode("div", { class: "space-y-2" }, [createVNode("label", { class: "text-[10px] font-black text-on-surface-variant/60 uppercase tracking-widest pl-1" }, "Adjuntar Soporte Digital"), createVNode("div", {
							class: "border-2 border-dashed border-outline-variant/20 rounded-[2rem] p-10 text-center bg-surface-container-low/30 hover:bg-surface-container-low hover:border-primary/30 transition-all cursor-pointer group relative",
							onClick: ($event) => fileInput.value?.click()
						}, [createVNode("input", {
							type: "file",
							ref_key: "fileInput",
							ref: fileInput,
							class: "hidden",
							onChange: handleFileChange,
							accept: ".pdf,.jpg,.jpeg,.png"
						}, null, 544), createVNode("div", { class: "flex flex-col items-center gap-3" }, [createVNode("div", { class: "w-16 h-16 rounded-2xl bg-primary/5 text-primary flex items-center justify-center group-hover:scale-110 transition-transform" }, [createVNode("span", {
							class: "material-symbols-outlined text-3xl",
							style: { "font-variation-settings": "'FILL' 1" }
						}, "cloud_upload")]), createVNode("div", null, [createVNode("p", { class: "text-sm font-black text-primary uppercase tracking-tight" }, toDisplayString(unref(form).soporte ? unref(form).soporte.name : "Subir comprobante"), 1), createVNode("p", { class: "text-[10px] text-on-surface-variant/40 mt-1 font-bold uppercase tracking-widest" }, "PDF, JPG o PNG • Máximo 5MB")])])], 8, ["onClick"])]),
						createVNode("div", { class: "flex flex-col sm:flex-row gap-4 pt-4" }, [createVNode(Button_default, {
							variant: "ghost",
							class: "flex-1 order-2 sm:order-1",
							onClick: ($event) => emit("close")
						}, {
							default: withCtx(() => [createTextVNode(" Cancelar ")]),
							_: 1
						}, 8, ["onClick"]), createVNode(Button_default, {
							type: "submit",
							variant: "secondary",
							class: "flex-1 order-1 sm:order-2",
							icon: "check_circle",
							loading: unref(form).processing
						}, {
							default: withCtx(() => [createTextVNode(" Registrar Pago ")]),
							_: 1
						}, 8, ["loading"])]),
						createVNode("div", { class: "flex items-center justify-center gap-2 py-4 border-t border-outline-variant/5" }, [createVNode("span", { class: "material-symbols-outlined text-on-surface-variant/30 text-sm" }, "verified_user"), createVNode("span", { class: "text-[9px] font-black text-on-surface-variant/30 uppercase tracking-[0.2em]" }, "Registro financiero auditado por NEXO-PRO")])
					], 32)];
				}),
				_: 1
			}, _parent));
		};
	}
});
//#endregion
//#region resources/js/Components/Cartera/PaymentModal.vue
var _sfc_setup$2 = PaymentModal_vue_vue_type_script_setup_true_lang_default.setup;
PaymentModal_vue_vue_type_script_setup_true_lang_default.setup = (props, ctx) => {
	const ssrContext = useSSRContext();
	(ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/Cartera/PaymentModal.vue");
	return _sfc_setup$2 ? _sfc_setup$2(props, ctx) : void 0;
};
var PaymentModal_default = /* @__PURE__ */ _plugin_vue_export_helper_default(PaymentModal_vue_vue_type_script_setup_true_lang_default, [["__scopeId", "data-v-2ae856b5"]]);
//#endregion
//#region resources/js/Components/Cartera/ImportModal.vue?vue&type=script&setup=true&lang.ts
var ImportModal_vue_vue_type_script_setup_true_lang_default = /* @__PURE__ */ defineComponent({
	__name: "ImportModal",
	__ssrInlineRender: true,
	emits: ["close"],
	setup(__props, { emit: __emit }) {
		const form = useForm({ file: null });
		return (_ctx, _push, _parent, _attrs) => {
			_push(`<div${ssrRenderAttrs(mergeProps({ class: "fixed inset-0 z-[100] flex items-center justify-center p-4 bg-primary/40 backdrop-blur-[10px] animate-in fade-in duration-300" }, _attrs))}><div class="bg-surface-container-lowest w-full max-w-lg rounded-[2.5rem] shadow-2xl border border-outline-variant/10 overflow-hidden animate-in zoom-in-95 duration-300"><div class="p-10"><div class="flex justify-between items-start mb-10"><div><h2 class="text-2xl font-black text-primary tracking-tighter uppercase">Importar Unidades</h2><p class="text-on-surface-variant/70 text-sm font-medium mt-1">Sube el archivo CSV con la lista de propiedades</p></div><button class="w-10 h-10 rounded-full hover:bg-surface-container-high transition-colors flex items-center justify-center"><span class="material-symbols-outlined text-on-surface-variant">close</span></button></div><form class="space-y-8"><div class="space-y-4"><div class="bg-primary/5 p-6 rounded-2xl border border-primary/10"><h3 class="text-xs font-black text-primary uppercase tracking-widest mb-2">Formato Requerido (Orden de columnas):</h3><p class="text-[10px] font-bold text-on-surface-variant/70 leading-relaxed font-mono"> Torre, Nombre, Coeficiente, Propietario, Identificacion, Email, Saldo </p><p class="text-[9px] text-on-surface-variant/40 mt-2 italic">Ejemplo: Torre A, 402, 0.85, Juan Perez, 10293, juan@test.com, 0</p></div><div class="border-2 border-dashed border-outline-variant/30 rounded-[1.5rem] p-10 text-center bg-surface-container-low/30 hover:bg-surface-container-low transition-colors cursor-pointer group"><input type="file" class="hidden" accept=".csv,.txt"><span class="material-symbols-outlined text-5xl text-primary/30 group-hover:scale-110 transition-transform mb-3">file_upload</span><p class="text-sm font-black text-primary">${ssrInterpolate(unref(form).file ? unref(form).file.name : "Selecciona tu archivo CSV")}</p><p class="text-[10px] text-on-surface-variant/40 mt-1 font-medium italic">Solo archivos .csv o .txt</p></div></div><div class="flex gap-4 pt-4"><button type="button" class="flex-1 py-4 text-sm font-black text-primary hover:bg-surface-container transition-all rounded-2xl uppercase tracking-widest"> Cerrar </button><button type="submit"${ssrIncludeBooleanAttr(unref(form).processing || !unref(form).file) ? " disabled" : ""} class="flex-1 py-4 text-sm font-black bg-primary text-on-primary rounded-2xl shadow-xl shadow-primary/20 hover:scale-[1.02] active:scale-95 transition-all uppercase tracking-widest flex items-center justify-center gap-2">`);
			if (unref(form).processing) _push(`<span class="animate-spin w-4 h-4 border-2 border-white/30 border-t-white rounded-full"></span>`);
			else _push(`<!---->`);
			_push(` Procesar Importación </button></div></form></div></div></div>`);
		};
	}
});
//#endregion
//#region resources/js/Components/Cartera/ImportModal.vue
var _sfc_setup$1 = ImportModal_vue_vue_type_script_setup_true_lang_default.setup;
ImportModal_vue_vue_type_script_setup_true_lang_default.setup = (props, ctx) => {
	const ssrContext = useSSRContext();
	(ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/Cartera/ImportModal.vue");
	return _sfc_setup$1 ? _sfc_setup$1(props, ctx) : void 0;
};
var ImportModal_default = ImportModal_vue_vue_type_script_setup_true_lang_default;
//#endregion
//#region resources/js/Pages/Cartera/Index.vue?vue&type=script&setup=true&lang.ts
var Index_vue_vue_type_script_setup_true_lang_default = /* @__PURE__ */ defineComponent({
	layout: AuthenticatedLayout_default,
	__name: "Index",
	__ssrInlineRender: true,
	props: {
		unidades: {},
		conceptos: {},
		stats: {},
		filters: {},
		torres: {}
	},
	setup(__props) {
		const props = __props;
		const isLoaded = ref(false);
		onMounted(() => {
			setTimeout(() => {
				isLoaded.value = true;
			}, 400);
		});
		const tableColumns = [
			{
				key: "unidad",
				label: "ACTIVO / LOTE",
				sortable: false
			},
			{
				key: "propietario",
				label: "RESPONSABLE LEGAL",
				sortable: false
			},
			{
				key: "coeficiente",
				label: "COEF.",
				sortable: false
			},
			{
				key: "estado",
				label: "INDICE DE MORA",
				sortable: false
			},
			{
				key: "saldo",
				label: "DEUDA CONSOLIDADA",
				sortable: false
			},
			{
				key: "acciones",
				label: "",
				sortable: false
			}
		];
		const toast = useToast();
		const showPaymentModal = ref(false);
		const showImportModal = ref(false);
		const showBillingModal = ref(false);
		const billingAmount = ref(15e4);
		const selectedUnidad = ref(null);
		const search = ref(props.filters.search || "");
		const torre = ref(props.filters.torre || "");
		watch([search, torre], ([newSearch, newTorre]) => {
			router.get(route("cartera.index"), {
				search: newSearch,
				torre: newTorre
			}, {
				preserveState: true,
				replace: true
			});
		});
		const openPaymentModal = (unidad = null) => {
			selectedUnidad.value = unidad;
			showPaymentModal.value = true;
		};
		const triggerBilling = () => {
			router.post(route("cartera.billing.trigger"), { monto: billingAmount.value }, { onSuccess: () => {
				showBillingModal.value = false;
				toast.add("Ciclo de facturación lanzado con éxito", "success");
			} });
		};
		const getStatusVariant = (saldo) => {
			if (saldo <= 0) return "success";
			if (saldo < 5e5) return "warning";
			return "error";
		};
		const getStatusLabel = (saldo) => {
			if (saldo <= 0) return "SISTEMA AL DÍA";
			if (saldo < 5e5) return "RETRASO LEVE";
			return "RIESGO CRÍTICO";
		};
		const formatCurrency = (value) => {
			return new Intl.NumberFormat("es-CO", {
				style: "currency",
				currency: "COP",
				maximumFractionDigits: 0
			}).format(value);
		};
		return (_ctx, _push, _parent, _attrs) => {
			_push(`<!--[-->`);
			_push(ssrRenderComponent(unref(Head), { title: "Ingeniería de Cartera — NEXO-PRO" }, null, _parent));
			_push(`<div class="space-y-12 pb-32 animate-in fade-in slide-in-from-bottom-4 duration-700" data-v-bba3edea><div class="flex flex-col lg:flex-row lg:items-end justify-between gap-8 px-1" data-v-bba3edea><div class="space-y-3" data-v-bba3edea><div class="flex items-center gap-3" data-v-bba3edea><div class="w-1.5 h-6 bg-primary rounded-full shadow-[0_0_10px_rgba(var(--primary),0.3)]" data-v-bba3edea></div><p class="text-[10px] font-black text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.4em] italic leading-none" data-v-bba3edea>Módulo de Conciliación Financiera</p></div><h2 class="text-5xl font-black text-on-surface dark:text-white tracking-tighter uppercase italic leading-none" data-v-bba3edea>Gestión de <span class="text-primary italic" data-v-bba3edea>Cartera</span></h2><p class="text-[11px] font-bold text-on-surface-variant/40 dark:text-white/10 uppercase tracking-[0.2em] mt-2 leading-relaxed italic" data-v-bba3edea>Monitor técnico de recaudos, saldos pendientes y cierres administrativos</p></div><div class="flex flex-wrap items-center gap-3 bg-white dark:bg-white/[0.02] p-2 rounded-[2rem] border border-outline-variant/10 shadow-xl" data-v-bba3edea>`);
			_push(ssrRenderComponent(Button_default, {
				variant: "ghost",
				size: "md",
				icon: "upload_file",
				class: "!rounded-2xl italic font-black uppercase !text-[10px]",
				onClick: ($event) => showImportModal.value = true
			}, {
				default: withCtx((_, _push, _parent, _scopeId) => {
					if (_push) _push(`Importación`);
					else return [createTextVNode("Importación")];
				}),
				_: 1
			}, _parent));
			_push(ssrRenderComponent(Button_default, {
				variant: "outline",
				size: "md",
				icon: "auto_awesome",
				class: "!rounded-2xl italic font-black uppercase !text-[10px]",
				onClick: ($event) => showBillingModal.value = true
			}, {
				default: withCtx((_, _push, _parent, _scopeId) => {
					if (_push) _push(`Cierre de Mes`);
					else return [createTextVNode("Cierre de Mes")];
				}),
				_: 1
			}, _parent));
			_push(ssrRenderComponent(Button_default, {
				variant: "primary",
				size: "md",
				icon: "add_card",
				class: "!rounded-2xl italic font-black uppercase !text-[10px] shadow-lg shadow-primary/20",
				onClick: ($event) => openPaymentModal()
			}, {
				default: withCtx((_, _push, _parent, _scopeId) => {
					if (_push) _push(`Registrar Recaudo`);
					else return [createTextVNode("Registrar Recaudo")];
				}),
				_: 1
			}, _parent));
			_push(`</div></div><div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6" data-v-bba3edea>`);
			_push(ssrRenderComponent(Card_default, { class: "!p-8 !rounded-[2.5rem] border border-outline-variant/5 dark:border-white/5 shadow-2xl dark:bg-[#0b0e14] group hover:scale-[1.02] transition-all" }, {
				default: withCtx((_, _push, _parent, _scopeId) => {
					if (_push) _push(`<p class="text-[10px] font-black text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.3em] mb-6 italic leading-none" data-v-bba3edea${_scopeId}>Recaudo Estimado (Mensual)</p><div class="flex items-center justify-between" data-v-bba3edea${_scopeId}><h3 class="text-4xl font-black text-primary tracking-tighter leading-none italic" data-v-bba3edea${_scopeId}>\$142.5M</h3><div class="px-3 py-1.5 rounded-xl text-[9px] font-black flex items-center gap-1 bg-emerald-500/10 text-emerald-500 border border-emerald-500/20" data-v-bba3edea${_scopeId}><span class="material-symbols-rounded text-sm" data-v-bba3edea${_scopeId}>trending_up</span> +12% </div></div><div class="mt-8 flex items-center gap-2" data-v-bba3edea${_scopeId}><div class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse" data-v-bba3edea${_scopeId}></div><p class="text-[9px] text-on-surface-variant/40 dark:text-white/20 font-black uppercase tracking-widest italic leading-none" data-v-bba3edea${_scopeId}>Meta: 95% OPERATIVA</p></div>`);
					else return [
						createVNode("p", { class: "text-[10px] font-black text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.3em] mb-6 italic leading-none" }, "Recaudo Estimado (Mensual)"),
						createVNode("div", { class: "flex items-center justify-between" }, [createVNode("h3", { class: "text-4xl font-black text-primary tracking-tighter leading-none italic" }, "$142.5M"), createVNode("div", { class: "px-3 py-1.5 rounded-xl text-[9px] font-black flex items-center gap-1 bg-emerald-500/10 text-emerald-500 border border-emerald-500/20" }, [createVNode("span", { class: "material-symbols-rounded text-sm" }, "trending_up"), createTextVNode(" +12% ")])]),
						createVNode("div", { class: "mt-8 flex items-center gap-2" }, [createVNode("div", { class: "w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse" }), createVNode("p", { class: "text-[9px] text-on-surface-variant/40 dark:text-white/20 font-black uppercase tracking-widest italic leading-none" }, "Meta: 95% OPERATIVA")])
					];
				}),
				_: 1
			}, _parent));
			_push(ssrRenderComponent(Card_default, { class: "!p-8 !rounded-[2.5rem] border border-outline-variant/5 dark:border-white/5 shadow-2xl dark:bg-[#0b0e14] group hover:scale-[1.02] transition-all" }, {
				default: withCtx((_, _push, _parent, _scopeId) => {
					if (_push) _push(`<p class="text-[10px] font-black text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.3em] mb-6 italic leading-none" data-v-bba3edea${_scopeId}>Cartera en Riesgo</p><div class="flex items-center justify-between" data-v-bba3edea${_scopeId}><h3 class="text-4xl font-black text-error tracking-tighter leading-none italic" data-v-bba3edea${_scopeId}>${ssrInterpolate(formatCurrency(__props.stats.cartera_vencida))}</h3><div class="w-12 h-12 bg-error/10 rounded-2xl flex items-center justify-center text-error border border-error/20" data-v-bba3edea${_scopeId}><span class="material-symbols-rounded text-2xl" data-v-bba3edea${_scopeId}>warning</span></div></div><div class="mt-8 flex items-center gap-2" data-v-bba3edea${_scopeId}><div class="w-1.5 h-1.5 rounded-full bg-error" data-v-bba3edea${_scopeId}></div><p class="text-[9px] text-on-surface-variant/40 dark:text-white/20 font-black uppercase tracking-widest italic leading-none" data-v-bba3edea${_scopeId}>${ssrInterpolate(__props.stats.unidades_mora)} UNIDADES EN ESTADO CRÍTICO</p></div>`);
					else return [
						createVNode("p", { class: "text-[10px] font-black text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.3em] mb-6 italic leading-none" }, "Cartera en Riesgo"),
						createVNode("div", { class: "flex items-center justify-between" }, [createVNode("h3", { class: "text-4xl font-black text-error tracking-tighter leading-none italic" }, toDisplayString(formatCurrency(__props.stats.cartera_vencida)), 1), createVNode("div", { class: "w-12 h-12 bg-error/10 rounded-2xl flex items-center justify-center text-error border border-error/20" }, [createVNode("span", { class: "material-symbols-rounded text-2xl" }, "warning")])]),
						createVNode("div", { class: "mt-8 flex items-center gap-2" }, [createVNode("div", { class: "w-1.5 h-1.5 rounded-full bg-error" }), createVNode("p", { class: "text-[9px] text-on-surface-variant/40 dark:text-white/20 font-black uppercase tracking-widest italic leading-none" }, toDisplayString(__props.stats.unidades_mora) + " UNIDADES EN ESTADO CRÍTICO", 1)])
					];
				}),
				_: 1
			}, _parent));
			_push(ssrRenderComponent(Card_default, { class: "!p-8 !rounded-[2.5rem] border border-outline-variant/5 dark:border-white/5 shadow-2xl dark:bg-[#0b0e14] lg:col-span-2" }, {
				default: withCtx((_, _push, _parent, _scopeId) => {
					if (_push) {
						_push(`<div class="flex items-center justify-between mb-6" data-v-bba3edea${_scopeId}><p class="text-[10px] font-black text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.3em] italic leading-none" data-v-bba3edea${_scopeId}>Indice de Solvencia General</p><span class="text-2xl font-black text-primary tracking-tighter italic" data-v-bba3edea${_scopeId}>82.4%</span></div><div class="mt-4 py-3" data-v-bba3edea${_scopeId}>`);
						_push(ssrRenderComponent(Progress_default, {
							percentage: 82.4,
							variant: "gradient",
							height: "h-3",
							class: "!rounded-full shadow-inner"
						}, null, _parent, _scopeId));
						_push(`</div><div class="flex justify-between mt-6" data-v-bba3edea${_scopeId}><p class="text-[9px] font-black text-on-surface-variant/30 dark:text-white/10 uppercase tracking-widest italic" data-v-bba3edea${_scopeId}>Cierre anterior: 78.2%</p><p class="text-[9px] font-black text-primary uppercase tracking-widest italic flex items-center gap-1" data-v-bba3edea${_scopeId}> DESEMPEÑO ÓPTIMO <span class="material-symbols-rounded text-xs" data-v-bba3edea${_scopeId}>bolt</span></p></div>`);
					} else return [
						createVNode("div", { class: "flex items-center justify-between mb-6" }, [createVNode("p", { class: "text-[10px] font-black text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.3em] italic leading-none" }, "Indice de Solvencia General"), createVNode("span", { class: "text-2xl font-black text-primary tracking-tighter italic" }, "82.4%")]),
						createVNode("div", { class: "mt-4 py-3" }, [createVNode(Progress_default, {
							percentage: 82.4,
							variant: "gradient",
							height: "h-3",
							class: "!rounded-full shadow-inner"
						})]),
						createVNode("div", { class: "flex justify-between mt-6" }, [createVNode("p", { class: "text-[9px] font-black text-on-surface-variant/30 dark:text-white/10 uppercase tracking-widest italic" }, "Cierre anterior: 78.2%"), createVNode("p", { class: "text-[9px] font-black text-primary uppercase tracking-widest italic flex items-center gap-1" }, [createTextVNode(" DESEMPEÑO ÓPTIMO "), createVNode("span", { class: "material-symbols-rounded text-xs" }, "bolt")])])
					];
				}),
				_: 1
			}, _parent));
			_push(`</div>`);
			_push(ssrRenderComponent(Card_default, { class: "!p-8 !rounded-[3rem] border border-outline-variant/10 dark:border-white/5 shadow-3xl dark:bg-[#0b0e14]" }, {
				default: withCtx((_, _push, _parent, _scopeId) => {
					if (_push) {
						_push(`<div class="grid grid-cols-1 md:grid-cols-12 gap-8 items-end" data-v-bba3edea${_scopeId}><div class="md:col-span-5" data-v-bba3edea${_scopeId}>`);
						_push(ssrRenderComponent(Input_default, {
							modelValue: search.value,
							"onUpdate:modelValue": ($event) => search.value = $event,
							label: "IDENTIFICADOR DE RESPONSABLE / UNIDAD",
							placeholder: "Nombre, ID o nomenclatura...",
							icon: "search",
							class: "!bg-surface-container-low !rounded-2xl !h-14 font-black italic text-xs uppercase"
						}, null, _parent, _scopeId));
						_push(`</div><div class="md:col-span-3" data-v-bba3edea${_scopeId}>`);
						_push(ssrRenderComponent(Select_default, {
							modelValue: torre.value,
							"onUpdate:modelValue": ($event) => torre.value = $event,
							label: "ESTRUCTURA / TORRE",
							placeholder: "GLOBAL",
							icon: "apartment",
							options: [{
								value: "",
								label: "GLOBAL"
							}, ...__props.torres.map((t) => ({
								value: t,
								label: t.toUpperCase()
							}))],
							class: "!bg-surface-container-low !rounded-2xl !h-14 font-black italic text-xs uppercase"
						}, null, _parent, _scopeId));
						_push(`</div><div class="md:col-span-4 flex gap-3" data-v-bba3edea${_scopeId}>`);
						_push(ssrRenderComponent(Button_default, {
							variant: "secondary",
							class: "flex-1 !h-14 !rounded-2xl !text-[11px] font-black uppercase italic shadow-sm",
							icon: "filter_list"
						}, {
							default: withCtx((_, _push, _parent, _scopeId) => {
								if (_push) _push(`Aplicar Capas`);
								else return [createTextVNode("Aplicar Capas")];
							}),
							_: 1
						}, _parent, _scopeId));
						_push(ssrRenderComponent(Button_default, {
							variant: "ghost",
							class: "!w-14 !h-14 !rounded-2xl border border-outline-variant/10 hover:bg-error/5 hover:text-error transition-all",
							icon: "restart_alt",
							onClick: ($event) => {
								search.value = "";
								torre.value = "";
							}
						}, null, _parent, _scopeId));
						_push(`</div></div>`);
					} else return [createVNode("div", { class: "grid grid-cols-1 md:grid-cols-12 gap-8 items-end" }, [
						createVNode("div", { class: "md:col-span-5" }, [createVNode(Input_default, {
							modelValue: search.value,
							"onUpdate:modelValue": ($event) => search.value = $event,
							label: "IDENTIFICADOR DE RESPONSABLE / UNIDAD",
							placeholder: "Nombre, ID o nomenclatura...",
							icon: "search",
							class: "!bg-surface-container-low !rounded-2xl !h-14 font-black italic text-xs uppercase"
						}, null, 8, ["modelValue", "onUpdate:modelValue"])]),
						createVNode("div", { class: "md:col-span-3" }, [createVNode(Select_default, {
							modelValue: torre.value,
							"onUpdate:modelValue": ($event) => torre.value = $event,
							label: "ESTRUCTURA / TORRE",
							placeholder: "GLOBAL",
							icon: "apartment",
							options: [{
								value: "",
								label: "GLOBAL"
							}, ...__props.torres.map((t) => ({
								value: t,
								label: t.toUpperCase()
							}))],
							class: "!bg-surface-container-low !rounded-2xl !h-14 font-black italic text-xs uppercase"
						}, null, 8, [
							"modelValue",
							"onUpdate:modelValue",
							"options"
						])]),
						createVNode("div", { class: "md:col-span-4 flex gap-3" }, [createVNode(Button_default, {
							variant: "secondary",
							class: "flex-1 !h-14 !rounded-2xl !text-[11px] font-black uppercase italic shadow-sm",
							icon: "filter_list"
						}, {
							default: withCtx(() => [createTextVNode("Aplicar Capas")]),
							_: 1
						}), createVNode(Button_default, {
							variant: "ghost",
							class: "!w-14 !h-14 !rounded-2xl border border-outline-variant/10 hover:bg-error/5 hover:text-error transition-all",
							icon: "restart_alt",
							onClick: ($event) => {
								search.value = "";
								torre.value = "";
							}
						}, null, 8, ["onClick"])])
					])];
				}),
				_: 1
			}, _parent));
			_push(ssrRenderComponent(Card_default, {
				title: "Monitor de Saldos",
				subtitle: "Desglose técnico pro unidad",
				icon: "receipt_long",
				class: "!p-0 !rounded-[3.5rem] border border-outline-variant/10 dark:border-white/5 shadow-3xl dark:bg-[#0b0e14] overflow-hidden"
			}, {
				header: withCtx((_, _push, _parent, _scopeId) => {
					if (_push) _push(`<div class="p-10 flex items-center justify-between" data-v-bba3edea${_scopeId}><div data-v-bba3edea${_scopeId}><h3 class="text-2xl font-black text-on-surface dark:text-white uppercase tracking-tighter italic leading-none" data-v-bba3edea${_scopeId}>Monitor de <span class="text-primary italic" data-v-bba3edea${_scopeId}>Saldos</span></h3><p class="text-[10px] font-bold text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.3em] mt-3 italic" data-v-bba3edea${_scopeId}>Registro cronológico de obligaciones por unidad</p></div></div>`);
					else return [createVNode("div", { class: "p-10 flex items-center justify-between" }, [createVNode("div", null, [createVNode("h3", { class: "text-2xl font-black text-on-surface dark:text-white uppercase tracking-tighter italic leading-none" }, [createTextVNode("Monitor de "), createVNode("span", { class: "text-primary italic" }, "Saldos")]), createVNode("p", { class: "text-[10px] font-bold text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.3em] mt-3 italic" }, "Registro cronológico de obligaciones por unidad")])])];
				}),
				default: withCtx((_, _push, _parent, _scopeId) => {
					if (_push) {
						_push(ssrRenderComponent(Table_default, {
							columns: tableColumns,
							data: __props.unidades.data,
							class: "border-t border-outline-variant/5 dark:border-white/5"
						}, {
							"cell-unidad": withCtx(({ row }, _push, _parent, _scopeId) => {
								if (_push) _push(`<div class="flex items-center gap-4 py-2" data-v-bba3edea${_scopeId}><div class="w-12 h-12 rounded-2xl bg-primary/5 dark:bg-primary/10 flex items-center justify-center border border-primary/20 shadow-inner" data-v-bba3edea${_scopeId}><span class="material-symbols-rounded text-2xl text-primary" data-v-bba3edea${_scopeId}>analytics</span></div><div class="flex flex-col" data-v-bba3edea${_scopeId}><span class="text-lg font-black text-primary tracking-tighter italic leading-none" data-v-bba3edea${_scopeId}>TORRE ${ssrInterpolate(row.torre || "N/A")}</span><span class="text-[11px] font-black text-on-surface dark:text-white uppercase italic tracking-widest mt-1.5" data-v-bba3edea${_scopeId}>${ssrInterpolate(row.nombre)}</span></div></div>`);
								else return [createVNode("div", { class: "flex items-center gap-4 py-2" }, [createVNode("div", { class: "w-12 h-12 rounded-2xl bg-primary/5 dark:bg-primary/10 flex items-center justify-center border border-primary/20 shadow-inner" }, [createVNode("span", { class: "material-symbols-rounded text-2xl text-primary" }, "analytics")]), createVNode("div", { class: "flex flex-col" }, [createVNode("span", { class: "text-lg font-black text-primary tracking-tighter italic leading-none" }, "TORRE " + toDisplayString(row.torre || "N/A"), 1), createVNode("span", { class: "text-[11px] font-black text-on-surface dark:text-white uppercase italic tracking-widest mt-1.5" }, toDisplayString(row.nombre), 1)])])];
							}),
							"cell-propietario": withCtx(({ row }, _push, _parent, _scopeId) => {
								if (_push) _push(`<div class="flex flex-col py-1" data-v-bba3edea${_scopeId}><span class="text-[12px] font-black text-on-surface dark:text-white uppercase italic tracking-tighter leading-tight mb-1" data-v-bba3edea${_scopeId}>${ssrInterpolate(row.propietario_nombre || "RESPONSABLE NO VINCULADO")}</span><span class="text-[9px] font-bold text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.2em]" data-v-bba3edea${_scopeId}>${ssrInterpolate(row.propietario_identificacion || "DOCUMENTO PENDIENTE")}</span></div>`);
								else return [createVNode("div", { class: "flex flex-col py-1" }, [createVNode("span", { class: "text-[12px] font-black text-on-surface dark:text-white uppercase italic tracking-tighter leading-tight mb-1" }, toDisplayString(row.propietario_nombre || "RESPONSABLE NO VINCULADO"), 1), createVNode("span", { class: "text-[9px] font-bold text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.2em]" }, toDisplayString(row.propietario_identificacion || "DOCUMENTO PENDIENTE"), 1)])];
							}),
							"cell-coeficiente": withCtx(({ row }, _push, _parent, _scopeId) => {
								if (_push) _push(`<div class="px-4 py-2 bg-surface-container dark:bg-white/5 rounded-xl border border-outline-variant/5 w-fit" data-v-bba3edea${_scopeId}><span class="text-[11px] font-black text-primary tracking-widest italic" data-v-bba3edea${_scopeId}>${ssrInterpolate(row.coeficiente)}%</span></div>`);
								else return [createVNode("div", { class: "px-4 py-2 bg-surface-container dark:bg-white/5 rounded-xl border border-outline-variant/5 w-fit" }, [createVNode("span", { class: "text-[11px] font-black text-primary tracking-widest italic" }, toDisplayString(row.coeficiente) + "%", 1)])];
							}),
							"cell-estado": withCtx(({ row }, _push, _parent, _scopeId) => {
								if (_push) {
									_push(`<div class="flex items-center gap-3" data-v-bba3edea${_scopeId}>`);
									_push(ssrRenderComponent(Badge_default, {
										variant: getStatusVariant(row.saldo_actual),
										class: "!rounded-xl !px-5 !py-1 !text-[9px] font-black uppercase tracking-widest border-2"
									}, {
										default: withCtx((_, _push, _parent, _scopeId) => {
											if (_push) _push(`${ssrInterpolate(getStatusLabel(row.saldo_actual))}`);
											else return [createTextVNode(toDisplayString(getStatusLabel(row.saldo_actual)), 1)];
										}),
										_: 2
									}, _parent, _scopeId));
									_push(`</div>`);
								} else return [createVNode("div", { class: "flex items-center gap-3" }, [createVNode(Badge_default, {
									variant: getStatusVariant(row.saldo_actual),
									class: "!rounded-xl !px-5 !py-1 !text-[9px] font-black uppercase tracking-widest border-2"
								}, {
									default: withCtx(() => [createTextVNode(toDisplayString(getStatusLabel(row.saldo_actual)), 1)]),
									_: 2
								}, 1032, ["variant"])])];
							}),
							"cell-saldo": withCtx(({ row }, _push, _parent, _scopeId) => {
								if (_push) _push(`<div class="flex flex-col items-end pr-8" data-v-bba3edea${_scopeId}><span class="${ssrRenderClass(["text-xl font-black tracking-tighter italic tabular-nums leading-none", row.saldo_actual > 0 ? "text-error" : "text-emerald-500"])}" data-v-bba3edea${_scopeId}>${ssrInterpolate(formatCurrency(row.saldo_actual))}</span><span class="text-[8px] font-black text-on-surface-variant/40 dark:text-white/20 uppercase tracking-widest mt-2" data-v-bba3edea${_scopeId}>DIFERENCIAL DE CIERRE</span></div>`);
								else return [createVNode("div", { class: "flex flex-col items-end pr-8" }, [createVNode("span", { class: ["text-xl font-black tracking-tighter italic tabular-nums leading-none", row.saldo_actual > 0 ? "text-error" : "text-emerald-500"] }, toDisplayString(formatCurrency(row.saldo_actual)), 3), createVNode("span", { class: "text-[8px] font-black text-on-surface-variant/40 dark:text-white/20 uppercase tracking-widest mt-2" }, "DIFERENCIAL DE CIERRE")])];
							}),
							"cell-acciones": withCtx(({ row }, _push, _parent, _scopeId) => {
								if (_push) {
									_push(`<div class="flex justify-end pr-8 gap-2" data-v-bba3edea${_scopeId}>`);
									_push(ssrRenderComponent(Tooltip_default, { text: "Registrar Pago" }, {
										default: withCtx((_, _push, _parent, _scopeId) => {
											if (_push) _push(ssrRenderComponent(Button_default, {
												variant: "ghost",
												icon: "add_card",
												class: "!w-10 !h-10 !p-0 !rounded-xl !text-primary hover:bg-primary/5",
												onClick: ($event) => openPaymentModal(row)
											}, null, _parent, _scopeId));
											else return [createVNode(Button_default, {
												variant: "ghost",
												icon: "add_card",
												class: "!w-10 !h-10 !p-0 !rounded-xl !text-primary hover:bg-primary/5",
												onClick: ($event) => openPaymentModal(row)
											}, null, 8, ["onClick"])];
										}),
										_: 2
									}, _parent, _scopeId));
									_push(ssrRenderComponent(Tooltip_default, { text: "Hoja de Vida" }, {
										default: withCtx((_, _push, _parent, _scopeId) => {
											if (_push) _push(ssrRenderComponent(Button_default, {
												variant: "ghost",
												icon: "visibility",
												class: "!w-10 !h-10 !p-0 !rounded-xl hover:bg-surface-variant/10",
												onClick: ($event) => unref(router).get(_ctx.route("admin.units.show", row.id))
											}, null, _parent, _scopeId));
											else return [createVNode(Button_default, {
												variant: "ghost",
												icon: "visibility",
												class: "!w-10 !h-10 !p-0 !rounded-xl hover:bg-surface-variant/10",
												onClick: ($event) => unref(router).get(_ctx.route("admin.units.show", row.id))
											}, null, 8, ["onClick"])];
										}),
										_: 2
									}, _parent, _scopeId));
									_push(ssrRenderComponent(Dropdown_default, {
										label: "",
										icon: "more_vert",
										variant: "ghost",
										class: "!p-0 !w-10 !h-10 !rounded-xl border border-outline-variant/10",
										items: [{
											label: "Descargar PDF",
											icon: "picture_as_pdf",
											action: () => _ctx.window.open(_ctx.route("cartera.statement.download", row.id))
										}, {
											label: "Editar Responsable",
											icon: "manage_accounts",
											action: () => {}
										}]
									}, null, _parent, _scopeId));
									_push(`</div>`);
								} else return [createVNode("div", { class: "flex justify-end pr-8 gap-2" }, [
									createVNode(Tooltip_default, { text: "Registrar Pago" }, {
										default: withCtx(() => [createVNode(Button_default, {
											variant: "ghost",
											icon: "add_card",
											class: "!w-10 !h-10 !p-0 !rounded-xl !text-primary hover:bg-primary/5",
											onClick: ($event) => openPaymentModal(row)
										}, null, 8, ["onClick"])]),
										_: 2
									}, 1024),
									createVNode(Tooltip_default, { text: "Hoja de Vida" }, {
										default: withCtx(() => [createVNode(Button_default, {
											variant: "ghost",
											icon: "visibility",
											class: "!w-10 !h-10 !p-0 !rounded-xl hover:bg-surface-variant/10",
											onClick: ($event) => unref(router).get(_ctx.route("admin.units.show", row.id))
										}, null, 8, ["onClick"])]),
										_: 2
									}, 1024),
									createVNode(Dropdown_default, {
										label: "",
										icon: "more_vert",
										variant: "ghost",
										class: "!p-0 !w-10 !h-10 !rounded-xl border border-outline-variant/10",
										items: [{
											label: "Descargar PDF",
											icon: "picture_as_pdf",
											action: () => _ctx.window.open(_ctx.route("cartera.statement.download", row.id))
										}, {
											label: "Editar Responsable",
											icon: "manage_accounts",
											action: () => {}
										}]
									}, null, 8, ["items"])
								])];
							}),
							_: 1
						}, _parent, _scopeId));
						_push(`<div class="p-10 bg-surface-container-low/30 dark:bg-white/[0.02] flex items-center justify-between border-t border-outline-variant/5 dark:border-white/5" data-v-bba3edea${_scopeId}><p class="text-[9px] font-black text-on-surface-variant/40 dark:text-white/40 uppercase tracking-[0.3em] italic" data-v-bba3edea${_scopeId}>REGISTRO TOTAL: ${ssrInterpolate(__props.unidades.total)} ACTIVOS MONITOREADOS</p></div>`);
					} else return [createVNode(Table_default, {
						columns: tableColumns,
						data: __props.unidades.data,
						class: "border-t border-outline-variant/5 dark:border-white/5"
					}, {
						"cell-unidad": withCtx(({ row }) => [createVNode("div", { class: "flex items-center gap-4 py-2" }, [createVNode("div", { class: "w-12 h-12 rounded-2xl bg-primary/5 dark:bg-primary/10 flex items-center justify-center border border-primary/20 shadow-inner" }, [createVNode("span", { class: "material-symbols-rounded text-2xl text-primary" }, "analytics")]), createVNode("div", { class: "flex flex-col" }, [createVNode("span", { class: "text-lg font-black text-primary tracking-tighter italic leading-none" }, "TORRE " + toDisplayString(row.torre || "N/A"), 1), createVNode("span", { class: "text-[11px] font-black text-on-surface dark:text-white uppercase italic tracking-widest mt-1.5" }, toDisplayString(row.nombre), 1)])])]),
						"cell-propietario": withCtx(({ row }) => [createVNode("div", { class: "flex flex-col py-1" }, [createVNode("span", { class: "text-[12px] font-black text-on-surface dark:text-white uppercase italic tracking-tighter leading-tight mb-1" }, toDisplayString(row.propietario_nombre || "RESPONSABLE NO VINCULADO"), 1), createVNode("span", { class: "text-[9px] font-bold text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.2em]" }, toDisplayString(row.propietario_identificacion || "DOCUMENTO PENDIENTE"), 1)])]),
						"cell-coeficiente": withCtx(({ row }) => [createVNode("div", { class: "px-4 py-2 bg-surface-container dark:bg-white/5 rounded-xl border border-outline-variant/5 w-fit" }, [createVNode("span", { class: "text-[11px] font-black text-primary tracking-widest italic" }, toDisplayString(row.coeficiente) + "%", 1)])]),
						"cell-estado": withCtx(({ row }) => [createVNode("div", { class: "flex items-center gap-3" }, [createVNode(Badge_default, {
							variant: getStatusVariant(row.saldo_actual),
							class: "!rounded-xl !px-5 !py-1 !text-[9px] font-black uppercase tracking-widest border-2"
						}, {
							default: withCtx(() => [createTextVNode(toDisplayString(getStatusLabel(row.saldo_actual)), 1)]),
							_: 2
						}, 1032, ["variant"])])]),
						"cell-saldo": withCtx(({ row }) => [createVNode("div", { class: "flex flex-col items-end pr-8" }, [createVNode("span", { class: ["text-xl font-black tracking-tighter italic tabular-nums leading-none", row.saldo_actual > 0 ? "text-error" : "text-emerald-500"] }, toDisplayString(formatCurrency(row.saldo_actual)), 3), createVNode("span", { class: "text-[8px] font-black text-on-surface-variant/40 dark:text-white/20 uppercase tracking-widest mt-2" }, "DIFERENCIAL DE CIERRE")])]),
						"cell-acciones": withCtx(({ row }) => [createVNode("div", { class: "flex justify-end pr-8 gap-2" }, [
							createVNode(Tooltip_default, { text: "Registrar Pago" }, {
								default: withCtx(() => [createVNode(Button_default, {
									variant: "ghost",
									icon: "add_card",
									class: "!w-10 !h-10 !p-0 !rounded-xl !text-primary hover:bg-primary/5",
									onClick: ($event) => openPaymentModal(row)
								}, null, 8, ["onClick"])]),
								_: 2
							}, 1024),
							createVNode(Tooltip_default, { text: "Hoja de Vida" }, {
								default: withCtx(() => [createVNode(Button_default, {
									variant: "ghost",
									icon: "visibility",
									class: "!w-10 !h-10 !p-0 !rounded-xl hover:bg-surface-variant/10",
									onClick: ($event) => unref(router).get(_ctx.route("admin.units.show", row.id))
								}, null, 8, ["onClick"])]),
								_: 2
							}, 1024),
							createVNode(Dropdown_default, {
								label: "",
								icon: "more_vert",
								variant: "ghost",
								class: "!p-0 !w-10 !h-10 !rounded-xl border border-outline-variant/10",
								items: [{
									label: "Descargar PDF",
									icon: "picture_as_pdf",
									action: () => _ctx.window.open(_ctx.route("cartera.statement.download", row.id))
								}, {
									label: "Editar Responsable",
									icon: "manage_accounts",
									action: () => {}
								}]
							}, null, 8, ["items"])
						])]),
						_: 1
					}, 8, ["data"]), createVNode("div", { class: "p-10 bg-surface-container-low/30 dark:bg-white/[0.02] flex items-center justify-between border-t border-outline-variant/5 dark:border-white/5" }, [createVNode("p", { class: "text-[9px] font-black text-on-surface-variant/40 dark:text-white/40 uppercase tracking-[0.3em] italic" }, "REGISTRO TOTAL: " + toDisplayString(__props.unidades.total) + " ACTIVOS MONITOREADOS", 1)])];
				}),
				_: 1
			}, _parent));
			_push(`</div>`);
			if (showPaymentModal.value) _push(ssrRenderComponent(PaymentModal_default, {
				unidad: selectedUnidad.value,
				conceptos: __props.conceptos,
				onClose: ($event) => showPaymentModal.value = false
			}, null, _parent));
			else _push(`<!---->`);
			if (showImportModal.value) _push(ssrRenderComponent(ImportModal_default, { onClose: ($event) => showImportModal.value = false }, null, _parent));
			else _push(`<!---->`);
			if (showBillingModal.value) _push(ssrRenderComponent(Modal_default, {
				onClose: ($event) => showBillingModal.value = false,
				title: "DISPATCHER DE FACTURACIÓN",
				class: "!max-w-xl"
			}, {
				default: withCtx((_, _push, _parent, _scopeId) => {
					if (_push) {
						_push(`<div class="space-y-10 mt-6" data-v-bba3edea${_scopeId}><div class="bg-primary/5 p-8 rounded-[2.5rem] border border-primary/20 flex gap-5" data-v-bba3edea${_scopeId}><div class="w-12 h-12 bg-primary/10 rounded-2xl flex items-center justify-center text-primary shrink-0" data-v-bba3edea${_scopeId}><span class="material-symbols-rounded" data-v-bba3edea${_scopeId}>info</span></div><p class="text-sm font-medium text-on-surface-variant dark:text-white/60 leading-relaxed italic pr-4" data-v-bba3edea${_scopeId}> Este proceso ejecutará el cierre de ciclo contable, generará obligaciones para todas las unidades y recalculará intereses de mora según el coeficiente configurado. </p></div><div class="space-y-4 px-2" data-v-bba3edea${_scopeId}>`);
						_push(ssrRenderComponent(Input_default, {
							modelValue: billingAmount.value,
							"onUpdate:modelValue": ($event) => billingAmount.value = $event,
							label: "VALOR CANON ADMINISTRATIVO",
							type: "number",
							icon: "payments",
							placeholder: "0.00",
							class: "!rounded-2xl !h-16 font-black italic text-lg"
						}, null, _parent, _scopeId));
						_push(`<p class="text-[9px] font-black text-on-surface-variant/40 uppercase tracking-[0.3em] ml-4" data-v-bba3edea${_scopeId}>ESTE VALOR SERÁ EL VECTOR BASE DEL COBRO MENSUAL</p></div><div class="flex flex-col gap-4 pt-6 border-t border-outline-variant/10 dark:border-white/5" data-v-bba3edea${_scopeId}>`);
						_push(ssrRenderComponent(Button_default, {
							variant: "primary",
							size: "lg",
							class: "w-full !h-16 !rounded-2xl !text-[11px] font-black uppercase italic shadow-2xl shadow-primary/20",
							onClick: triggerBilling,
							icon: "cloud_sync"
						}, {
							default: withCtx((_, _push, _parent, _scopeId) => {
								if (_push) _push(` Lanzar Facturación Global `);
								else return [createTextVNode(" Lanzar Facturación Global ")];
							}),
							_: 1
						}, _parent, _scopeId));
						_push(ssrRenderComponent(Button_default, {
							variant: "ghost",
							class: "w-full !text-[9px] font-black uppercase tracking-widest text-on-surface-variant/40",
							onClick: ($event) => showBillingModal.value = false
						}, {
							default: withCtx((_, _push, _parent, _scopeId) => {
								if (_push) _push(` Abortar Proceso `);
								else return [createTextVNode(" Abortar Proceso ")];
							}),
							_: 1
						}, _parent, _scopeId));
						_push(`</div></div>`);
					} else return [createVNode("div", { class: "space-y-10 mt-6" }, [
						createVNode("div", { class: "bg-primary/5 p-8 rounded-[2.5rem] border border-primary/20 flex gap-5" }, [createVNode("div", { class: "w-12 h-12 bg-primary/10 rounded-2xl flex items-center justify-center text-primary shrink-0" }, [createVNode("span", { class: "material-symbols-rounded" }, "info")]), createVNode("p", { class: "text-sm font-medium text-on-surface-variant dark:text-white/60 leading-relaxed italic pr-4" }, " Este proceso ejecutará el cierre de ciclo contable, generará obligaciones para todas las unidades y recalculará intereses de mora según el coeficiente configurado. ")]),
						createVNode("div", { class: "space-y-4 px-2" }, [createVNode(Input_default, {
							modelValue: billingAmount.value,
							"onUpdate:modelValue": ($event) => billingAmount.value = $event,
							label: "VALOR CANON ADMINISTRATIVO",
							type: "number",
							icon: "payments",
							placeholder: "0.00",
							class: "!rounded-2xl !h-16 font-black italic text-lg"
						}, null, 8, ["modelValue", "onUpdate:modelValue"]), createVNode("p", { class: "text-[9px] font-black text-on-surface-variant/40 uppercase tracking-[0.3em] ml-4" }, "ESTE VALOR SERÁ EL VECTOR BASE DEL COBRO MENSUAL")]),
						createVNode("div", { class: "flex flex-col gap-4 pt-6 border-t border-outline-variant/10 dark:border-white/5" }, [createVNode(Button_default, {
							variant: "primary",
							size: "lg",
							class: "w-full !h-16 !rounded-2xl !text-[11px] font-black uppercase italic shadow-2xl shadow-primary/20",
							onClick: triggerBilling,
							icon: "cloud_sync"
						}, {
							default: withCtx(() => [createTextVNode(" Lanzar Facturación Global ")]),
							_: 1
						}), createVNode(Button_default, {
							variant: "ghost",
							class: "w-full !text-[9px] font-black uppercase tracking-widest text-on-surface-variant/40",
							onClick: ($event) => showBillingModal.value = false
						}, {
							default: withCtx(() => [createTextVNode(" Abortar Proceso ")]),
							_: 1
						}, 8, ["onClick"])])
					])];
				}),
				_: 1
			}, _parent));
			else _push(`<!---->`);
			_push(`<!--]-->`);
		};
	}
});
//#endregion
//#region resources/js/Pages/Cartera/Index.vue
var _sfc_setup = Index_vue_vue_type_script_setup_true_lang_default.setup;
Index_vue_vue_type_script_setup_true_lang_default.setup = (props, ctx) => {
	const ssrContext = useSSRContext();
	(ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Cartera/Index.vue");
	return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
var Index_default = /* @__PURE__ */ _plugin_vue_export_helper_default(Index_vue_vue_type_script_setup_true_lang_default, [["__scopeId", "data-v-bba3edea"]]);
//#endregion
export { Index_default as default };

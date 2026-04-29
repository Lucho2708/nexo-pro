import { t as _plugin_vue_export_helper_default } from "./_plugin-vue_export-helper-BjJHhBxR.js";
import { t as useToast } from "./useToast-E_Hdzmc5.js";
import { t as AuthenticatedLayout_default } from "./AuthenticatedLayout-vxulH9w9.js";
import { t as Card_default } from "./Card-D4IwBRMd.js";
import { t as Button_default } from "./Button-C-byZbTR.js";
import { t as Badge_default } from "./Badge-DsYztQFV.js";
import { createBlock, createCommentVNode, createTextVNode, createVNode, defineComponent, mergeProps, openBlock, toDisplayString, unref, useSSRContext, vModelText, withCtx, withDirectives } from "vue";
import { Head, useForm } from "@inertiajs/vue3";
import { ssrInterpolate, ssrRenderAttr, ssrRenderAttrs, ssrRenderClass, ssrRenderComponent } from "vue/server-renderer";
//#region resources/js/Components/UI/Switch.vue?vue&type=script&setup=true&lang.ts
var Switch_vue_vue_type_script_setup_true_lang_default = /* @__PURE__ */ defineComponent({
	__name: "Switch",
	__ssrInlineRender: true,
	props: {
		modelValue: {
			type: Boolean,
			default: false
		},
		disabled: {
			type: Boolean,
			default: false
		}
	},
	emits: ["update:modelValue"],
	setup(__props, { emit: __emit }) {
		return (_ctx, _push, _parent, _attrs) => {
			_push(`<button${ssrRenderAttrs(mergeProps({
				type: "button",
				class: ["relative inline-flex h-7 w-12 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-primary/20", [__props.modelValue ? "bg-primary" : "bg-surface-container-high", __props.disabled ? "opacity-50 cursor-not-allowed" : ""]]
			}, _attrs))}><span aria-hidden="true" class="${ssrRenderClass([[__props.modelValue ? "translate-x-[20px]" : "translate-x-0"], "pointer-events-none inline-block h-6 w-6 transform rounded-full bg-white shadow-lg ring-0 transition duration-200 ease-in-out"])}"></span></button>`);
		};
	}
});
//#endregion
//#region resources/js/Components/UI/Switch.vue
var _sfc_setup$1 = Switch_vue_vue_type_script_setup_true_lang_default.setup;
Switch_vue_vue_type_script_setup_true_lang_default.setup = (props, ctx) => {
	const ssrContext = useSSRContext();
	(ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/UI/Switch.vue");
	return _sfc_setup$1 ? _sfc_setup$1(props, ctx) : void 0;
};
var Switch_default = Switch_vue_vue_type_script_setup_true_lang_default;
//#endregion
//#region resources/js/Pages/SuperAdmin/Settings/Index.vue?vue&type=script&setup=true&lang.ts
var Index_vue_vue_type_script_setup_true_lang_default = /* @__PURE__ */ defineComponent({
	layout: AuthenticatedLayout_default,
	__name: "Index",
	__ssrInlineRender: true,
	props: { settings: {} },
	setup(__props) {
		const props = __props;
		const toast = useToast();
		const form = useForm({
			"2fa_enabled": props.settings["2fa_enabled"],
			"maintenance_mode": props.settings["maintenance_mode"],
			"log_retention_days": props.settings["log_retention_days"],
			"audit_retention_days": props.settings["audit_retention_days"],
			"allow_new_registrations": props.settings["allow_new_registrations"],
			"system_announcements": props.settings["system_announcements"]
		});
		const submit = () => {
			form.patch(route("superadmin.settings.update"), {
				preserveScroll: true,
				onSuccess: () => {
					toast.add("Núcleo de configuración actualizado exitosamente", "success");
				}
			});
		};
		return (_ctx, _push, _parent, _attrs) => {
			_push(`<!--[-->`);
			_push(ssrRenderComponent(unref(Head), { title: "Panel de Control Global — NEXO-PRO" }, null, _parent));
			_push(`<div class="max-w-6xl mx-auto space-y-12 pb-32 animate-in fade-in slide-in-from-bottom-6 duration-700" data-v-841a7765><header class="flex flex-col md:flex-row md:items-center justify-between gap-8 pt-4 px-2" data-v-841a7765><div class="flex items-center gap-6" data-v-841a7765><div class="w-16 h-16 rounded-[2rem] bg-secondary flex items-center justify-center shadow-[0_0_40px_-10px_rgba(var(--secondary),0.3)]" data-v-841a7765><span class="material-symbols-rounded text-3xl text-white" data-v-841a7765>settings_input_component</span></div><div data-v-841a7765><h2 class="text-4xl font-black text-on-surface dark:text-white tracking-tighter uppercase italic leading-none" data-v-841a7765>CEREBRO <span class="text-secondary italic" data-v-841a7765>GLOBAL</span></h2><div class="flex items-center gap-2 mt-3" data-v-841a7765><span class="w-1.5 h-1.5 rounded-full bg-secondary animate-pulse" data-v-841a7765></span><p class="text-[9px] font-black text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.4em]" data-v-841a7765>Gestión de Parámetros Maestros del Ecosistema</p></div></div></div><div class="bg-surface-container-low dark:bg-white/5 p-2 rounded-[1.8rem] border border-outline-variant/10 dark:border-white/5 flex items-center gap-4" data-v-841a7765>`);
			_push(ssrRenderComponent(Badge_default, {
				variant: "neutral",
				class: "!px-6 !py-1.5 !text-[9px] !font-black uppercase tracking-[0.2em] !bg-white dark:!bg-white/[0.03]"
			}, {
				default: withCtx((_, _push, _parent, _scopeId) => {
					if (_push) _push(`${ssrInterpolate((/* @__PURE__ */ new Date()).getFullYear())} — STABLE RELEASE`);
					else return [createTextVNode(toDisplayString((/* @__PURE__ */ new Date()).getFullYear()) + " — STABLE RELEASE", 1)];
				}),
				_: 1
			}, _parent));
			_push(`<div class="h-6 w-px bg-outline-variant/10 dark:bg-white/10 mx-1" data-v-841a7765></div>`);
			_push(ssrRenderComponent(Button_default, {
				onClick: submit,
				variant: "primary",
				icon: "save",
				loading: unref(form).processing,
				class: "!rounded-2xl !h-12 shadow-xl shadow-primary/20 !text-[10px] font-black uppercase px-8"
			}, {
				default: withCtx((_, _push, _parent, _scopeId) => {
					if (_push) _push(` Aplicar Cambios Globales `);
					else return [createTextVNode(" Aplicar Cambios Globales ")];
				}),
				_: 1
			}, _parent));
			_push(`</div></header><form class="grid grid-cols-1 md:grid-cols-2 gap-8" data-v-841a7765><div class="space-y-8" data-v-841a7765>`);
			_push(ssrRenderComponent(Card_default, { class: "!p-8 !rounded-[3rem] border border-outline-variant/10 dark:border-white/5 hover:border-primary/20 transition-all shadow-xl dark:bg-[#0b0e14]" }, {
				default: withCtx((_, _push, _parent, _scopeId) => {
					if (_push) {
						_push(`<div class="flex justify-between items-start mb-10" data-v-841a7765${_scopeId}><div data-v-841a7765${_scopeId}><h3 class="text-xl font-black text-on-surface dark:text-white uppercase tracking-tighter italic" data-v-841a7765${_scopeId}>Blindaje &amp; <span class="text-primary italic font-black" data-v-841a7765${_scopeId}>Identidad</span></h3><p class="text-[10px] text-on-surface-variant/40 dark:text-white/30 font-black uppercase tracking-widest mt-2" data-v-841a7765${_scopeId}>Protocolos de acceso global</p></div><div class="w-12 h-12 bg-primary/10 rounded-2xl flex items-center justify-center" data-v-841a7765${_scopeId}><span class="material-symbols-rounded text-primary" data-v-841a7765${_scopeId}>verified_user</span></div></div><div class="space-y-6" data-v-841a7765${_scopeId}><div class="flex items-center justify-between p-6 bg-surface-container dark:bg-white/[0.02] rounded-[2rem] border border-outline-variant/5 dark:border-white/5 group hover:border-primary/20 transition-all" data-v-841a7765${_scopeId}><div class="space-y-1 pr-4" data-v-841a7765${_scopeId}><h4 class="text-[11px] font-black text-on-surface dark:text-white uppercase tracking-wider" data-v-841a7765${_scopeId}>Multi-Factor (2FA) Obligatorio</h4><p class="text-[9px] text-on-surface-variant/60 dark:text-white/30 font-medium leading-relaxed" data-v-841a7765${_scopeId}> Fuerza a todos los usuarios a usar autenticación de dos pasos. </p></div>`);
						_push(ssrRenderComponent(Switch_default, {
							modelValue: unref(form)["2fa_enabled"],
							"onUpdate:modelValue": ($event) => unref(form)["2fa_enabled"] = $event
						}, null, _parent, _scopeId));
						_push(`</div><div class="flex items-center justify-between p-6 bg-surface-container dark:bg-white/[0.02] rounded-[2rem] border border-outline-variant/5 dark:border-white/5 group hover:border-primary/20 transition-all" data-v-841a7765${_scopeId}><div class="space-y-1 pr-4" data-v-841a7765${_scopeId}><h4 class="text-[11px] font-black text-on-surface dark:text-white uppercase tracking-wider" data-v-841a7765${_scopeId}>Registro de Nuevos Clientes</h4><p class="text-[9px] text-on-surface-variant/60 dark:text-white/30 font-medium leading-relaxed" data-v-841a7765${_scopeId}> Habilita o deshabilita el formulario de Onboarding público. </p></div>`);
						_push(ssrRenderComponent(Switch_default, {
							modelValue: unref(form)["allow_new_registrations"],
							"onUpdate:modelValue": ($event) => unref(form)["allow_new_registrations"] = $event
						}, null, _parent, _scopeId));
						_push(`</div></div>`);
					} else return [createVNode("div", { class: "flex justify-between items-start mb-10" }, [createVNode("div", null, [createVNode("h3", { class: "text-xl font-black text-on-surface dark:text-white uppercase tracking-tighter italic" }, [createTextVNode("Blindaje & "), createVNode("span", { class: "text-primary italic font-black" }, "Identidad")]), createVNode("p", { class: "text-[10px] text-on-surface-variant/40 dark:text-white/30 font-black uppercase tracking-widest mt-2" }, "Protocolos de acceso global")]), createVNode("div", { class: "w-12 h-12 bg-primary/10 rounded-2xl flex items-center justify-center" }, [createVNode("span", { class: "material-symbols-rounded text-primary" }, "verified_user")])]), createVNode("div", { class: "space-y-6" }, [createVNode("div", { class: "flex items-center justify-between p-6 bg-surface-container dark:bg-white/[0.02] rounded-[2rem] border border-outline-variant/5 dark:border-white/5 group hover:border-primary/20 transition-all" }, [createVNode("div", { class: "space-y-1 pr-4" }, [createVNode("h4", { class: "text-[11px] font-black text-on-surface dark:text-white uppercase tracking-wider" }, "Multi-Factor (2FA) Obligatorio"), createVNode("p", { class: "text-[9px] text-on-surface-variant/60 dark:text-white/30 font-medium leading-relaxed" }, " Fuerza a todos los usuarios a usar autenticación de dos pasos. ")]), createVNode(Switch_default, {
						modelValue: unref(form)["2fa_enabled"],
						"onUpdate:modelValue": ($event) => unref(form)["2fa_enabled"] = $event
					}, null, 8, ["modelValue", "onUpdate:modelValue"])]), createVNode("div", { class: "flex items-center justify-between p-6 bg-surface-container dark:bg-white/[0.02] rounded-[2rem] border border-outline-variant/5 dark:border-white/5 group hover:border-primary/20 transition-all" }, [createVNode("div", { class: "space-y-1 pr-4" }, [createVNode("h4", { class: "text-[11px] font-black text-on-surface dark:text-white uppercase tracking-wider" }, "Registro de Nuevos Clientes"), createVNode("p", { class: "text-[9px] text-on-surface-variant/60 dark:text-white/30 font-medium leading-relaxed" }, " Habilita o deshabilita el formulario de Onboarding público. ")]), createVNode(Switch_default, {
						modelValue: unref(form)["allow_new_registrations"],
						"onUpdate:modelValue": ($event) => unref(form)["allow_new_registrations"] = $event
					}, null, 8, ["modelValue", "onUpdate:modelValue"])])])];
				}),
				_: 1
			}, _parent));
			_push(ssrRenderComponent(Card_default, { class: "!p-8 !rounded-[3rem] border border-outline-variant/10 dark:border-white/5 hover:border-secondary/20 transition-all shadow-xl dark:bg-[#0b0e14]" }, {
				default: withCtx((_, _push, _parent, _scopeId) => {
					if (_push) {
						_push(`<div class="flex justify-between items-start mb-10" data-v-841a7765${_scopeId}><div data-v-841a7765${_scopeId}><h3 class="text-xl font-black text-on-surface dark:text-white uppercase tracking-tighter italic" data-v-841a7765${_scopeId}>Operaciones <span class="text-secondary italic font-black" data-v-841a7765${_scopeId}>Críticas</span></h3><p class="text-[10px] text-on-surface-variant/40 dark:text-white/30 font-black uppercase tracking-widest mt-2" data-v-841a7765${_scopeId}>Acciones de infraestructura masiva</p></div><div class="w-12 h-12 bg-secondary/10 rounded-2xl flex items-center justify-center" data-v-841a7765${_scopeId}><span class="material-symbols-rounded text-secondary" data-v-841a7765${_scopeId}>power_settings_new</span></div></div><div class="p-6 bg-secondary/10 dark:bg-secondary/5 rounded-[2rem] border border-secondary/20 space-y-4" data-v-841a7765${_scopeId}><div class="flex items-center justify-between" data-v-841a7765${_scopeId}><div class="space-y-1 pr-4" data-v-841a7765${_scopeId}><h4 class="text-[11px] font-black text-secondary uppercase tracking-wider italic" data-v-841a7765${_scopeId}>Modo Mantenimiento Maestro</h4><p class="text-[9px] text-secondary/60 dark:text-secondary/40 font-bold leading-relaxed uppercase tracking-widest" data-v-841a7765${_scopeId}> Bloquea el acceso a todas las interfaces excepto SuperAdmin. </p></div>`);
						_push(ssrRenderComponent(Switch_default, {
							modelValue: unref(form)["maintenance_mode"],
							"onUpdate:modelValue": ($event) => unref(form)["maintenance_mode"] = $event,
							variant: "secondary"
						}, null, _parent, _scopeId));
						_push(`</div>`);
						if (unref(form)["maintenance_mode"]) _push(`<div class="p-4 bg-white/10 rounded-xl border border-white/10 animate-pulse" data-v-841a7765${_scopeId}><p class="text-[8px] font-black text-secondary text-center uppercase tracking-[0.2em]" data-v-841a7765${_scopeId}>EL SISTEMA SE ENCUENTRA EN AISLAMIENTO</p></div>`);
						else _push(`<!---->`);
						_push(`</div>`);
					} else return [createVNode("div", { class: "flex justify-between items-start mb-10" }, [createVNode("div", null, [createVNode("h3", { class: "text-xl font-black text-on-surface dark:text-white uppercase tracking-tighter italic" }, [createTextVNode("Operaciones "), createVNode("span", { class: "text-secondary italic font-black" }, "Críticas")]), createVNode("p", { class: "text-[10px] text-on-surface-variant/40 dark:text-white/30 font-black uppercase tracking-widest mt-2" }, "Acciones de infraestructura masiva")]), createVNode("div", { class: "w-12 h-12 bg-secondary/10 rounded-2xl flex items-center justify-center" }, [createVNode("span", { class: "material-symbols-rounded text-secondary" }, "power_settings_new")])]), createVNode("div", { class: "p-6 bg-secondary/10 dark:bg-secondary/5 rounded-[2rem] border border-secondary/20 space-y-4" }, [createVNode("div", { class: "flex items-center justify-between" }, [createVNode("div", { class: "space-y-1 pr-4" }, [createVNode("h4", { class: "text-[11px] font-black text-secondary uppercase tracking-wider italic" }, "Modo Mantenimiento Maestro"), createVNode("p", { class: "text-[9px] text-secondary/60 dark:text-secondary/40 font-bold leading-relaxed uppercase tracking-widest" }, " Bloquea el acceso a todas las interfaces excepto SuperAdmin. ")]), createVNode(Switch_default, {
						modelValue: unref(form)["maintenance_mode"],
						"onUpdate:modelValue": ($event) => unref(form)["maintenance_mode"] = $event,
						variant: "secondary"
					}, null, 8, ["modelValue", "onUpdate:modelValue"])]), unref(form)["maintenance_mode"] ? (openBlock(), createBlock("div", {
						key: 0,
						class: "p-4 bg-white/10 rounded-xl border border-white/10 animate-pulse"
					}, [createVNode("p", { class: "text-[8px] font-black text-secondary text-center uppercase tracking-[0.2em]" }, "EL SISTEMA SE ENCUENTRA EN AISLAMIENTO")])) : createCommentVNode("", true)])];
				}),
				_: 1
			}, _parent));
			_push(`</div><div class="space-y-8" data-v-841a7765>`);
			_push(ssrRenderComponent(Card_default, { class: "!p-8 !rounded-[3rem] border border-outline-variant/10 dark:border-white/5 hover:border-emerald-500/20 transition-all shadow-xl dark:bg-[#0b0e14]" }, {
				default: withCtx((_, _push, _parent, _scopeId) => {
					if (_push) _push(`<div class="flex justify-between items-start mb-10" data-v-841a7765${_scopeId}><div data-v-841a7765${_scopeId}><h3 class="text-xl font-black text-on-surface dark:text-white uppercase tracking-tighter italic" data-v-841a7765${_scopeId}>Kernel <span class="text-emerald-500 italic font-black" data-v-841a7765${_scopeId}>Retention</span></h3><p class="text-[10px] text-on-surface-variant/40 dark:text-white/30 font-black uppercase tracking-widest mt-2" data-v-841a7765${_scopeId}>Ciclo de vida de registros técnicos</p></div><div class="w-12 h-12 bg-emerald-500/10 rounded-2xl flex items-center justify-center" data-v-841a7765${_scopeId}><span class="material-symbols-rounded text-emerald-500" data-v-841a7765${_scopeId}>history_toggle_off</span></div></div><div class="space-y-8" data-v-841a7765${_scopeId}><div class="space-y-4" data-v-841a7765${_scopeId}><div class="flex justify-between items-center" data-v-841a7765${_scopeId}><label class="text-[10px] font-black text-on-surface-variant dark:text-white/40 uppercase tracking-widest" data-v-841a7765${_scopeId}>Retención de Logs de Sistema</label><span class="text-[10px] font-black text-emerald-500 bg-emerald-500/10 px-3 py-1 rounded-lg" data-v-841a7765${_scopeId}>${ssrInterpolate(unref(form)["log_retention_days"])} DÍAS</span></div><input type="range"${ssrRenderAttr("value", unref(form)["log_retention_days"])} min="7" max="365" step="1" class="w-full h-1.5 bg-surface-container-high dark:bg-white/10 rounded-lg appearance-none cursor-pointer accent-emerald-500" data-v-841a7765${_scopeId}><p class="text-[8px] font-bold text-on-surface-variant/30 uppercase text-right tracking-widest" data-v-841a7765${_scopeId}>Mínimo 7 días — Máximo 1 año</p></div><div class="space-y-4" data-v-841a7765${_scopeId}><div class="flex justify-between items-center" data-v-841a7765${_scopeId}><label class="text-[10px] font-black text-on-surface-variant dark:text-white/40 uppercase tracking-widest" data-v-841a7765${_scopeId}>Retención de Auditoría Forense</label><span class="text-[10px] font-black text-emerald-500 bg-emerald-500/10 px-3 py-1 rounded-lg" data-v-841a7765${_scopeId}>${ssrInterpolate(unref(form)["audit_retention_days"])} DÍAS</span></div><input type="range"${ssrRenderAttr("value", unref(form)["audit_retention_days"])} min="30" max="1000" step="30" class="w-full h-1.5 bg-surface-container-high dark:bg-white/10 rounded-lg appearance-none cursor-pointer accent-emerald-500" data-v-841a7765${_scopeId}><p class="text-[8px] font-bold text-on-surface-variant/30 uppercase text-right tracking-widest" data-v-841a7765${_scopeId}>Sección Forense — Hasta 3 años</p></div></div>`);
					else return [createVNode("div", { class: "flex justify-between items-start mb-10" }, [createVNode("div", null, [createVNode("h3", { class: "text-xl font-black text-on-surface dark:text-white uppercase tracking-tighter italic" }, [createTextVNode("Kernel "), createVNode("span", { class: "text-emerald-500 italic font-black" }, "Retention")]), createVNode("p", { class: "text-[10px] text-on-surface-variant/40 dark:text-white/30 font-black uppercase tracking-widest mt-2" }, "Ciclo de vida de registros técnicos")]), createVNode("div", { class: "w-12 h-12 bg-emerald-500/10 rounded-2xl flex items-center justify-center" }, [createVNode("span", { class: "material-symbols-rounded text-emerald-500" }, "history_toggle_off")])]), createVNode("div", { class: "space-y-8" }, [createVNode("div", { class: "space-y-4" }, [
						createVNode("div", { class: "flex justify-between items-center" }, [createVNode("label", { class: "text-[10px] font-black text-on-surface-variant dark:text-white/40 uppercase tracking-widest" }, "Retención de Logs de Sistema"), createVNode("span", { class: "text-[10px] font-black text-emerald-500 bg-emerald-500/10 px-3 py-1 rounded-lg" }, toDisplayString(unref(form)["log_retention_days"]) + " DÍAS", 1)]),
						withDirectives(createVNode("input", {
							type: "range",
							"onUpdate:modelValue": ($event) => unref(form)["log_retention_days"] = $event,
							min: "7",
							max: "365",
							step: "1",
							class: "w-full h-1.5 bg-surface-container-high dark:bg-white/10 rounded-lg appearance-none cursor-pointer accent-emerald-500"
						}, null, 8, ["onUpdate:modelValue"]), [[vModelText, unref(form)["log_retention_days"]]]),
						createVNode("p", { class: "text-[8px] font-bold text-on-surface-variant/30 uppercase text-right tracking-widest" }, "Mínimo 7 días — Máximo 1 año")
					]), createVNode("div", { class: "space-y-4" }, [
						createVNode("div", { class: "flex justify-between items-center" }, [createVNode("label", { class: "text-[10px] font-black text-on-surface-variant dark:text-white/40 uppercase tracking-widest" }, "Retención de Auditoría Forense"), createVNode("span", { class: "text-[10px] font-black text-emerald-500 bg-emerald-500/10 px-3 py-1 rounded-lg" }, toDisplayString(unref(form)["audit_retention_days"]) + " DÍAS", 1)]),
						withDirectives(createVNode("input", {
							type: "range",
							"onUpdate:modelValue": ($event) => unref(form)["audit_retention_days"] = $event,
							min: "30",
							max: "1000",
							step: "30",
							class: "w-full h-1.5 bg-surface-container-high dark:bg-white/10 rounded-lg appearance-none cursor-pointer accent-emerald-500"
						}, null, 8, ["onUpdate:modelValue"]), [[vModelText, unref(form)["audit_retention_days"]]]),
						createVNode("p", { class: "text-[8px] font-bold text-on-surface-variant/30 uppercase text-right tracking-widest" }, "Sección Forense — Hasta 3 años")
					])])];
				}),
				_: 1
			}, _parent));
			_push(ssrRenderComponent(Card_default, { class: "!p-8 !rounded-[3rem] border border-outline-variant/10 dark:border-white/5 hover:border-blue-500/20 transition-all shadow-xl dark:bg-[#0b0e14]" }, {
				default: withCtx((_, _push, _parent, _scopeId) => {
					if (_push) {
						_push(`<div class="flex justify-between items-start mb-10" data-v-841a7765${_scopeId}><div data-v-841a7765${_scopeId}><h3 class="text-xl font-black text-on-surface dark:text-white uppercase tracking-tighter italic" data-v-841a7765${_scopeId}>Global <span class="text-blue-500 italic font-black" data-v-841a7765${_scopeId}>Broadcast</span></h3><p class="text-[10px] text-on-surface-variant/40 dark:text-white/30 font-black uppercase tracking-widest mt-2" data-v-841a7765${_scopeId}>Gestión de notificaciones núcleo</p></div><div class="w-12 h-12 bg-blue-500/10 rounded-2xl flex items-center justify-center" data-v-841a7765${_scopeId}><span class="material-symbols-rounded text-blue-500" data-v-841a7765${_scopeId}>campaign</span></div></div><div class="flex items-center justify-between p-6 bg-blue-500/5 rounded-[2rem] border border-blue-500/10" data-v-841a7765${_scopeId}><div class="space-y-1 pr-4" data-v-841a7765${_scopeId}><h4 class="text-[11px] font-black text-blue-500 uppercase tracking-wider" data-v-841a7765${_scopeId}>Servicio de Difusión Activo</h4><p class="text-[9px] text-blue-500/60 font-medium leading-relaxed" data-v-841a7765${_scopeId}> Habilita los anuncios globales en todos los dashboards. </p></div>`);
						_push(ssrRenderComponent(Switch_default, {
							modelValue: unref(form)["system_announcements"],
							"onUpdate:modelValue": ($event) => unref(form)["system_announcements"] = $event
						}, null, _parent, _scopeId));
						_push(`</div>`);
					} else return [createVNode("div", { class: "flex justify-between items-start mb-10" }, [createVNode("div", null, [createVNode("h3", { class: "text-xl font-black text-on-surface dark:text-white uppercase tracking-tighter italic" }, [createTextVNode("Global "), createVNode("span", { class: "text-blue-500 italic font-black" }, "Broadcast")]), createVNode("p", { class: "text-[10px] text-on-surface-variant/40 dark:text-white/30 font-black uppercase tracking-widest mt-2" }, "Gestión de notificaciones núcleo")]), createVNode("div", { class: "w-12 h-12 bg-blue-500/10 rounded-2xl flex items-center justify-center" }, [createVNode("span", { class: "material-symbols-rounded text-blue-500" }, "campaign")])]), createVNode("div", { class: "flex items-center justify-between p-6 bg-blue-500/5 rounded-[2rem] border border-blue-500/10" }, [createVNode("div", { class: "space-y-1 pr-4" }, [createVNode("h4", { class: "text-[11px] font-black text-blue-500 uppercase tracking-wider" }, "Servicio de Difusión Activo"), createVNode("p", { class: "text-[9px] text-blue-500/60 font-medium leading-relaxed" }, " Habilita los anuncios globales en todos los dashboards. ")]), createVNode(Switch_default, {
						modelValue: unref(form)["system_announcements"],
						"onUpdate:modelValue": ($event) => unref(form)["system_announcements"] = $event
					}, null, 8, ["modelValue", "onUpdate:modelValue"])])];
				}),
				_: 1
			}, _parent));
			_push(`</div></form><div class="md:hidden fixed bottom-10 left-6 right-6 z-50" data-v-841a7765>`);
			_push(ssrRenderComponent(Button_default, {
				onClick: submit,
				variant: "primary",
				icon: "save",
				loading: unref(form).processing,
				class: "w-full !h-16 !rounded-3xl shadow-[0_20px_50px_rgba(var(--primary),0.3)] !text-[12px] font-black uppercase"
			}, {
				default: withCtx((_, _push, _parent, _scopeId) => {
					if (_push) _push(` Guardar Nucleo `);
					else return [createTextVNode(" Guardar Nucleo ")];
				}),
				_: 1
			}, _parent));
			_push(`</div></div><!--]-->`);
		};
	}
});
//#endregion
//#region resources/js/Pages/SuperAdmin/Settings/Index.vue
var _sfc_setup = Index_vue_vue_type_script_setup_true_lang_default.setup;
Index_vue_vue_type_script_setup_true_lang_default.setup = (props, ctx) => {
	const ssrContext = useSSRContext();
	(ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/SuperAdmin/Settings/Index.vue");
	return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
var Index_default = /* @__PURE__ */ _plugin_vue_export_helper_default(Index_vue_vue_type_script_setup_true_lang_default, [["__scopeId", "data-v-841a7765"]]);
//#endregion
export { Index_default as default };

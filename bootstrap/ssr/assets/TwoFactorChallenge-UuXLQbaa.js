import { t as Logo_default } from "./Logo-D89dXaWr.js";
import { t as Card_default } from "./Card-D4IwBRMd.js";
import { t as Button_default } from "./Button-C-byZbTR.js";
import { t as Input_default } from "./Input-CbVZZMpc.js";
import { t as GuestLayout_default } from "./GuestLayout-BiN2s9qc.js";
import { createBlock, createTextVNode, createVNode, defineComponent, nextTick, openBlock, ref, toDisplayString, unref, useSSRContext, withCtx, withModifiers } from "vue";
import { Head, useForm } from "@inertiajs/vue3";
import { ssrInterpolate, ssrRenderComponent } from "vue/server-renderer";
//#region resources/js/Pages/Auth/TwoFactorChallenge.vue?vue&type=script&setup=true&lang.ts
var TwoFactorChallenge_vue_vue_type_script_setup_true_lang_default = /* @__PURE__ */ defineComponent({
	__name: "TwoFactorChallenge",
	__ssrInlineRender: true,
	setup(__props) {
		const recovery = ref(false);
		const form = useForm({
			code: "",
			recovery_code: ""
		});
		const recoveryCodeInput = ref(null);
		const codeInput = ref(null);
		const toggleRecovery = async () => {
			recovery.value = !recovery.value;
			await nextTick();
			if (recovery.value) {
				recoveryCodeInput.value?.focus();
				form.code = "";
			} else {
				codeInput.value?.focus();
				form.recovery_code = "";
			}
		};
		const submit = () => {
			form.post("/two-factor-challenge");
		};
		return (_ctx, _push, _parent, _attrs) => {
			_push(`<!--[-->`);
			_push(ssrRenderComponent(unref(Head), { title: "Verificación de Dos Pasos" }, null, _parent));
			_push(ssrRenderComponent(GuestLayout_default, null, {
				default: withCtx((_, _push, _parent, _scopeId) => {
					if (_push) {
						_push(`<div class="max-w-md mx-auto w-full px-6 py-12 flex flex-col justify-center min-h-screen"${_scopeId}><div class="text-center mb-10 space-y-4"${_scopeId}><div class="flex justify-center mb-4"${_scopeId}>`);
						_push(ssrRenderComponent(Logo_default, {
							width: "64px",
							height: "64px",
							"show-text": false
						}, null, _parent, _scopeId));
						_push(`</div><h1 class="text-2xl font-black text-primary tracking-tight uppercase"${_scopeId}>Verificación</h1><p class="text-sm text-on-surface-variant/70 font-medium"${_scopeId}>${ssrInterpolate(recovery.value ? "Ingresa uno de tus códigos de recuperación de emergencia." : "Ingresa el código generado por tu aplicación de autenticación.")}</p></div>`);
						_push(ssrRenderComponent(Card_default, { class: "!p-8 shadow-xl" }, {
							default: withCtx((_, _push, _parent, _scopeId) => {
								if (_push) {
									_push(`<form class="space-y-6"${_scopeId}>`);
									if (!recovery.value) {
										_push(`<div${_scopeId}>`);
										_push(ssrRenderComponent(Input_default, {
											ref_key: "codeInput",
											ref: codeInput,
											modelValue: unref(form).code,
											"onUpdate:modelValue": ($event) => unref(form).code = $event,
											label: "Código de Autenticación",
											type: "text",
											inputmode: "numeric",
											placeholder: "000 000",
											icon: "pin",
											maxlength: "6",
											autofocus: "",
											error: unref(form).errors.code
										}, null, _parent, _scopeId));
										_push(`</div>`);
									} else {
										_push(`<div${_scopeId}>`);
										_push(ssrRenderComponent(Input_default, {
											ref_key: "recoveryCodeInput",
											ref: recoveryCodeInput,
											modelValue: unref(form).recovery_code,
											"onUpdate:modelValue": ($event) => unref(form).recovery_code = $event,
											label: "Código de Recuperación",
											type: "text",
											placeholder: "XXXXX-XXXXX",
											icon: "key",
											error: unref(form).errors.recovery_code
										}, null, _parent, _scopeId));
										_push(`</div>`);
									}
									_push(`<div class="flex flex-col gap-4"${_scopeId}>`);
									_push(ssrRenderComponent(Button_default, {
										variant: "primary",
										size: "lg",
										class: "w-full shadow-lg shadow-primary/10",
										loading: unref(form).processing
									}, {
										default: withCtx((_, _push, _parent, _scopeId) => {
											if (_push) _push(` Verificar Acceso `);
											else return [createTextVNode(" Verificar Acceso ")];
										}),
										_: 1
									}, _parent, _scopeId));
									_push(`<button type="button" class="text-xs font-bold text-primary hover:underline underline-offset-4"${_scopeId}>${ssrInterpolate(recovery.value ? "Usar código de autenticación" : "Usar un código de recuperación")}</button></div></form>`);
								} else return [createVNode("form", {
									onSubmit: withModifiers(submit, ["prevent"]),
									class: "space-y-6"
								}, [!recovery.value ? (openBlock(), createBlock("div", { key: 0 }, [createVNode(Input_default, {
									ref_key: "codeInput",
									ref: codeInput,
									modelValue: unref(form).code,
									"onUpdate:modelValue": ($event) => unref(form).code = $event,
									label: "Código de Autenticación",
									type: "text",
									inputmode: "numeric",
									placeholder: "000 000",
									icon: "pin",
									maxlength: "6",
									autofocus: "",
									error: unref(form).errors.code
								}, null, 8, [
									"modelValue",
									"onUpdate:modelValue",
									"error"
								])])) : (openBlock(), createBlock("div", { key: 1 }, [createVNode(Input_default, {
									ref_key: "recoveryCodeInput",
									ref: recoveryCodeInput,
									modelValue: unref(form).recovery_code,
									"onUpdate:modelValue": ($event) => unref(form).recovery_code = $event,
									label: "Código de Recuperación",
									type: "text",
									placeholder: "XXXXX-XXXXX",
									icon: "key",
									error: unref(form).errors.recovery_code
								}, null, 8, [
									"modelValue",
									"onUpdate:modelValue",
									"error"
								])])), createVNode("div", { class: "flex flex-col gap-4" }, [createVNode(Button_default, {
									variant: "primary",
									size: "lg",
									class: "w-full shadow-lg shadow-primary/10",
									loading: unref(form).processing
								}, {
									default: withCtx(() => [createTextVNode(" Verificar Acceso ")]),
									_: 1
								}, 8, ["loading"]), createVNode("button", {
									type: "button",
									onClick: toggleRecovery,
									class: "text-xs font-bold text-primary hover:underline underline-offset-4"
								}, toDisplayString(recovery.value ? "Usar código de autenticación" : "Usar un código de recuperación"), 1)])], 32)];
							}),
							_: 1
						}, _parent, _scopeId));
						_push(`<p class="mt-8 text-center text-[10px] text-on-surface-variant/40 font-bold uppercase tracking-widest leading-relaxed"${_scopeId}> NEXO-PRO — Secure Gateway<br${_scopeId}> Two-Factor Challenge Component </p></div>`);
					} else return [createVNode("div", { class: "max-w-md mx-auto w-full px-6 py-12 flex flex-col justify-center min-h-screen" }, [
						createVNode("div", { class: "text-center mb-10 space-y-4" }, [
							createVNode("div", { class: "flex justify-center mb-4" }, [createVNode(Logo_default, {
								width: "64px",
								height: "64px",
								"show-text": false
							})]),
							createVNode("h1", { class: "text-2xl font-black text-primary tracking-tight uppercase" }, "Verificación"),
							createVNode("p", { class: "text-sm text-on-surface-variant/70 font-medium" }, toDisplayString(recovery.value ? "Ingresa uno de tus códigos de recuperación de emergencia." : "Ingresa el código generado por tu aplicación de autenticación."), 1)
						]),
						createVNode(Card_default, { class: "!p-8 shadow-xl" }, {
							default: withCtx(() => [createVNode("form", {
								onSubmit: withModifiers(submit, ["prevent"]),
								class: "space-y-6"
							}, [!recovery.value ? (openBlock(), createBlock("div", { key: 0 }, [createVNode(Input_default, {
								ref_key: "codeInput",
								ref: codeInput,
								modelValue: unref(form).code,
								"onUpdate:modelValue": ($event) => unref(form).code = $event,
								label: "Código de Autenticación",
								type: "text",
								inputmode: "numeric",
								placeholder: "000 000",
								icon: "pin",
								maxlength: "6",
								autofocus: "",
								error: unref(form).errors.code
							}, null, 8, [
								"modelValue",
								"onUpdate:modelValue",
								"error"
							])])) : (openBlock(), createBlock("div", { key: 1 }, [createVNode(Input_default, {
								ref_key: "recoveryCodeInput",
								ref: recoveryCodeInput,
								modelValue: unref(form).recovery_code,
								"onUpdate:modelValue": ($event) => unref(form).recovery_code = $event,
								label: "Código de Recuperación",
								type: "text",
								placeholder: "XXXXX-XXXXX",
								icon: "key",
								error: unref(form).errors.recovery_code
							}, null, 8, [
								"modelValue",
								"onUpdate:modelValue",
								"error"
							])])), createVNode("div", { class: "flex flex-col gap-4" }, [createVNode(Button_default, {
								variant: "primary",
								size: "lg",
								class: "w-full shadow-lg shadow-primary/10",
								loading: unref(form).processing
							}, {
								default: withCtx(() => [createTextVNode(" Verificar Acceso ")]),
								_: 1
							}, 8, ["loading"]), createVNode("button", {
								type: "button",
								onClick: toggleRecovery,
								class: "text-xs font-bold text-primary hover:underline underline-offset-4"
							}, toDisplayString(recovery.value ? "Usar código de autenticación" : "Usar un código de recuperación"), 1)])], 32)]),
							_: 1
						}),
						createVNode("p", { class: "mt-8 text-center text-[10px] text-on-surface-variant/40 font-bold uppercase tracking-widest leading-relaxed" }, [
							createTextVNode(" NEXO-PRO — Secure Gateway"),
							createVNode("br"),
							createTextVNode(" Two-Factor Challenge Component ")
						])
					])];
				}),
				_: 1
			}, _parent));
			_push(`<!--]-->`);
		};
	}
});
//#endregion
//#region resources/js/Pages/Auth/TwoFactorChallenge.vue
var _sfc_setup = TwoFactorChallenge_vue_vue_type_script_setup_true_lang_default.setup;
TwoFactorChallenge_vue_vue_type_script_setup_true_lang_default.setup = (props, ctx) => {
	const ssrContext = useSSRContext();
	(ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Auth/TwoFactorChallenge.vue");
	return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
var TwoFactorChallenge_default = TwoFactorChallenge_vue_vue_type_script_setup_true_lang_default;
//#endregion
export { TwoFactorChallenge_default as default };

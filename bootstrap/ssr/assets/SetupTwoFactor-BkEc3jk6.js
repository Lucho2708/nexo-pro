import { t as _plugin_vue_export_helper_default } from "./_plugin-vue_export-helper-BjJHhBxR.js";
import { t as Logo_default } from "./Logo-BJuTBUmx.js";
import { t as Card_default } from "./Card-C2-I3_la.js";
import { t as Button_default } from "./Button-5V-KYwxi.js";
import { t as Input_default } from "./Input-DWocsxNw.js";
import { t as GuestLayout_default } from "./GuestLayout-CdsdxXMK.js";
import { Fragment, Transition, computed, createBlock, createTextVNode, createVNode, defineComponent, openBlock, ref, renderList, toDisplayString, unref, useSSRContext, withCtx, withKeys } from "vue";
import { Head, router, useForm, usePage } from "@inertiajs/vue3";
import { ssrInterpolate, ssrRenderComponent, ssrRenderList } from "vue/server-renderer";
import axios from "axios";
//#region resources/js/Pages/Auth/SetupTwoFactor.vue?vue&type=script&setup=true&lang.ts
var SetupTwoFactor_vue_vue_type_script_setup_true_lang_default = /* @__PURE__ */ defineComponent({
	__name: "SetupTwoFactor",
	__ssrInlineRender: true,
	setup(__props) {
		const page = usePage();
		const user = computed(() => page.props.auth.user);
		const enabling = ref(false);
		const confirming = ref(false);
		const qrCode = ref("");
		const recoveryCodes = ref([]);
		const setupStep = ref(1);
		const confirmationForm = useForm({ code: "" });
		const enableTwoFactorAuthentication = () => {
			enabling.value = true;
			axios.post("/user/two-factor-authentication").then(() => {
				return Promise.all([showQrCode(), showRecoveryCodes()]);
			}).then(() => {
				enabling.value = false;
				setupStep.value = 2;
			}).catch((error) => {
				console.error("Error enabling 2FA:", error);
				enabling.value = false;
			});
		};
		const showQrCode = () => {
			return axios.get("/user/two-factor-qr-code").then((response) => {
				qrCode.value = response.data.svg;
			});
		};
		const showRecoveryCodes = () => {
			return axios.get("/user/two-factor-recovery-codes").then((response) => {
				recoveryCodes.value = response.data;
			});
		};
		const confirmTwoFactorAuthentication = () => {
			confirming.value = true;
			confirmationForm.post("/user/confirmed-two-factor-authentication", {
				preserveScroll: true,
				preserveState: true,
				onSuccess: () => {
					confirming.value = false;
					setupStep.value = 3;
				},
				onError: () => {
					confirming.value = false;
				}
			});
		};
		const finishSetup = () => {
			const role = user.value?.role;
			let home = route("dashboard");
			if (role === "super_admin") home = route("superadmin.dashboard");
			else if (role === "owner") home = route("owner.dashboard");
			router.visit(home);
		};
		const logout = () => {
			router.post(route("logout"));
		};
		return (_ctx, _push, _parent, _attrs) => {
			_push(`<!--[-->`);
			_push(ssrRenderComponent(unref(Head), { title: "Configuración de Seguridad" }, null, _parent));
			_push(ssrRenderComponent(GuestLayout_default, null, {
				default: withCtx((_, _push, _parent, _scopeId) => {
					if (_push) {
						_push(`<div class="max-w-2xl mx-auto w-full px-6 py-12 flex flex-col justify-center min-h-screen" data-v-02794c9a${_scopeId}><div class="text-center mb-10 space-y-4" data-v-02794c9a${_scopeId}><div class="flex justify-center mb-4" data-v-02794c9a${_scopeId}>`);
						_push(ssrRenderComponent(Logo_default, {
							width: "64px",
							height: "64px",
							"show-text": false
						}, null, _parent, _scopeId));
						_push(`</div><h1 class="text-3xl font-black text-primary tracking-tight uppercase" data-v-02794c9a${_scopeId}>Protege tu Cuenta</h1><p class="text-sm text-on-surface-variant/70 font-medium" data-v-02794c9a${_scopeId}> NEXO-PRO te ofrece Autenticación de Dos Factores (2FA) para mayor seguridad de tu cuenta. </p></div>`);
						_push(ssrRenderComponent(Card_default, { class: "!p-8 md:!p-12 shadow-2xl border-primary/10 overflow-hidden relative" }, {
							default: withCtx((_, _push, _parent, _scopeId) => {
								if (_push) {
									_push(`<div class="absolute -top-12 -right-12 w-32 h-32 bg-primary/5 rounded-full blur-3xl" data-v-02794c9a${_scopeId}></div>`);
									if (setupStep.value === 1) {
										_push(`<div class="text-center space-y-6" data-v-02794c9a${_scopeId}><div class="w-20 h-20 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-6" data-v-02794c9a${_scopeId}><span class="material-symbols-outlined text-4xl text-primary" data-v-02794c9a${_scopeId}>security</span></div><div class="space-y-4" data-v-02794c9a${_scopeId}><h2 class="text-xl font-bold text-on-surface" data-v-02794c9a${_scopeId}>¿Qué es el 2FA?</h2><p class="text-sm text-on-surface-variant" data-v-02794c9a${_scopeId}> Es una capa extra de seguridad. Además de tu contraseña, necesitarás un código generado en tu celular para ingresar. Esto evita accesos no autorizados incluso si alguien descubre tu clave. </p></div><div class="pt-6 flex flex-col gap-4" data-v-02794c9a${_scopeId}>`);
										_push(ssrRenderComponent(Button_default, {
											variant: "primary",
											size: "lg",
											class: "w-full",
											loading: enabling.value,
											onClick: enableTwoFactorAuthentication
										}, {
											default: withCtx((_, _push, _parent, _scopeId) => {
												if (_push) _push(` Configurar ahora `);
												else return [createTextVNode(" Configurar ahora ")];
											}),
											_: 1
										}, _parent, _scopeId));
										_push(`<button class="text-sm font-bold text-primary/80 hover:text-primary transition-colors uppercase tracking-widest" data-v-02794c9a${_scopeId}> Omitir </button><button class="text-[10px] font-bold text-on-surface-variant/30 uppercase tracking-[0.2em] hover:text-on-surface-variant transition-colors" data-v-02794c9a${_scopeId}> Cerrar sesión </button></div></div>`);
									} else if (setupStep.value === 2) {
										_push(`<div class="space-y-8" data-v-02794c9a${_scopeId}><div class="text-center space-y-2" data-v-02794c9a${_scopeId}><h2 class="text-xl font-bold text-on-surface" data-v-02794c9a${_scopeId}>Escanea el código QR</h2><p class="text-sm text-on-surface-variant" data-v-02794c9a${_scopeId}>Usa Google Authenticator, Authy o tu app favorita.</p></div><div class="flex justify-center p-4 bg-white rounded-2xl border border-outline-variant/30 w-fit mx-auto shadow-inner" data-v-02794c9a${_scopeId}><div class="w-48 h-48 flex items-center justify-center" data-v-02794c9a${_scopeId}>${qrCode.value ?? ""}</div></div><div class="space-y-4" data-v-02794c9a${_scopeId}><label class="text-[10px] font-black text-on-surface-variant/60 uppercase tracking-[0.2em] pl-1" data-v-02794c9a${_scopeId}> Ingresa el código de 6 dígitos </label><div class="flex flex-col gap-4" data-v-02794c9a${_scopeId}>`);
										_push(ssrRenderComponent(Input_default, {
											modelValue: unref(confirmationForm).code,
											"onUpdate:modelValue": ($event) => unref(confirmationForm).code = $event,
											type: "text",
											placeholder: "000 000",
											icon: "pin",
											maxlength: "6",
											autofocus: "",
											error: unref(confirmationForm).errors.code,
											onKeyup: confirmTwoFactorAuthentication
										}, null, _parent, _scopeId));
										_push(ssrRenderComponent(Button_default, {
											variant: "secondary",
											size: "lg",
											class: "w-full",
											loading: confirming.value,
											onClick: confirmTwoFactorAuthentication
										}, {
											default: withCtx((_, _push, _parent, _scopeId) => {
												if (_push) _push(` Verificar y activar `);
												else return [createTextVNode(" Verificar y activar ")];
											}),
											_: 1
										}, _parent, _scopeId));
										_push(`</div></div></div>`);
									} else {
										_push(`<div class="space-y-8" data-v-02794c9a${_scopeId}><div class="text-center space-y-4" data-v-02794c9a${_scopeId}><div class="w-16 h-16 bg-secondary/10 rounded-full flex items-center justify-center mx-auto" data-v-02794c9a${_scopeId}><span class="material-symbols-outlined text-3xl text-secondary" data-v-02794c9a${_scopeId}>verified</span></div><h2 class="text-xl font-bold text-on-surface" data-v-02794c9a${_scopeId}>¡Seguridad activada!</h2><p class="text-sm text-on-surface-variant" data-v-02794c9a${_scopeId}> Tu cuenta ahora está protegida. Guarda estos códigos de recuperación en un lugar seguro. Los necesitarás si pierdes tu celular. </p></div><div class="grid grid-cols-2 gap-2 bg-surface-container-low p-4 rounded-xl border border-outline-variant/20 font-mono text-xs" data-v-02794c9a${_scopeId}><!--[-->`);
										ssrRenderList(recoveryCodes.value, (code) => {
											_push(`<div class="p-1 text-center select-all" data-v-02794c9a${_scopeId}>${ssrInterpolate(code)}</div>`);
										});
										_push(`<!--]--></div><div class="pt-4" data-v-02794c9a${_scopeId}>`);
										_push(ssrRenderComponent(Button_default, {
											variant: "primary",
											size: "lg",
											class: "w-full shadow-lg shadow-primary/20",
											onClick: finishSetup
										}, {
											default: withCtx((_, _push, _parent, _scopeId) => {
												if (_push) _push(` Ir al Dashboard `);
												else return [createTextVNode(" Ir al Dashboard ")];
											}),
											_: 1
										}, _parent, _scopeId));
										_push(`</div></div>`);
									}
								} else return [createVNode("div", { class: "absolute -top-12 -right-12 w-32 h-32 bg-primary/5 rounded-full blur-3xl" }), createVNode(Transition, {
									mode: "out-in",
									"enter-active-class": "transition duration-300 ease-out",
									"enter-from-class": "opacity-0 translate-y-4",
									"enter-to-class": "opacity-100 translate-y-0",
									"leave-active-class": "transition duration-200 ease-in",
									"leave-from-class": "opacity-100 translate-y-0",
									"leave-to-class": "opacity-0 -translate-y-4"
								}, {
									default: withCtx(() => [setupStep.value === 1 ? (openBlock(), createBlock("div", {
										key: 1,
										class: "text-center space-y-6"
									}, [
										createVNode("div", { class: "w-20 h-20 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-6" }, [createVNode("span", { class: "material-symbols-outlined text-4xl text-primary" }, "security")]),
										createVNode("div", { class: "space-y-4" }, [createVNode("h2", { class: "text-xl font-bold text-on-surface" }, "¿Qué es el 2FA?"), createVNode("p", { class: "text-sm text-on-surface-variant" }, " Es una capa extra de seguridad. Además de tu contraseña, necesitarás un código generado en tu celular para ingresar. Esto evita accesos no autorizados incluso si alguien descubre tu clave. ")]),
										createVNode("div", { class: "pt-6 flex flex-col gap-4" }, [
											createVNode(Button_default, {
												variant: "primary",
												size: "lg",
												class: "w-full",
												loading: enabling.value,
												onClick: enableTwoFactorAuthentication
											}, {
												default: withCtx(() => [createTextVNode(" Configurar ahora ")]),
												_: 1
											}, 8, ["loading"]),
											createVNode("button", {
												onClick: finishSetup,
												class: "text-sm font-bold text-primary/80 hover:text-primary transition-colors uppercase tracking-widest"
											}, " Omitir "),
											createVNode("button", {
												onClick: logout,
												class: "text-[10px] font-bold text-on-surface-variant/30 uppercase tracking-[0.2em] hover:text-on-surface-variant transition-colors"
											}, " Cerrar sesión ")
										])
									])) : setupStep.value === 2 ? (openBlock(), createBlock("div", {
										key: 2,
										class: "space-y-8"
									}, [
										createVNode("div", { class: "text-center space-y-2" }, [createVNode("h2", { class: "text-xl font-bold text-on-surface" }, "Escanea el código QR"), createVNode("p", { class: "text-sm text-on-surface-variant" }, "Usa Google Authenticator, Authy o tu app favorita.")]),
										createVNode("div", { class: "flex justify-center p-4 bg-white rounded-2xl border border-outline-variant/30 w-fit mx-auto shadow-inner" }, [createVNode("div", {
											innerHTML: qrCode.value,
											class: "w-48 h-48 flex items-center justify-center"
										}, null, 8, ["innerHTML"])]),
										createVNode("div", { class: "space-y-4" }, [createVNode("label", { class: "text-[10px] font-black text-on-surface-variant/60 uppercase tracking-[0.2em] pl-1" }, " Ingresa el código de 6 dígitos "), createVNode("div", { class: "flex flex-col gap-4" }, [createVNode(Input_default, {
											modelValue: unref(confirmationForm).code,
											"onUpdate:modelValue": ($event) => unref(confirmationForm).code = $event,
											type: "text",
											placeholder: "000 000",
											icon: "pin",
											maxlength: "6",
											autofocus: "",
											error: unref(confirmationForm).errors.code,
											onKeyup: withKeys(confirmTwoFactorAuthentication, ["enter"])
										}, null, 8, [
											"modelValue",
											"onUpdate:modelValue",
											"error"
										]), createVNode(Button_default, {
											variant: "secondary",
											size: "lg",
											class: "w-full",
											loading: confirming.value,
											onClick: confirmTwoFactorAuthentication
										}, {
											default: withCtx(() => [createTextVNode(" Verificar y activar ")]),
											_: 1
										}, 8, ["loading"])])])
									])) : (openBlock(), createBlock("div", {
										key: 3,
										class: "space-y-8"
									}, [
										createVNode("div", { class: "text-center space-y-4" }, [
											createVNode("div", { class: "w-16 h-16 bg-secondary/10 rounded-full flex items-center justify-center mx-auto" }, [createVNode("span", { class: "material-symbols-outlined text-3xl text-secondary" }, "verified")]),
											createVNode("h2", { class: "text-xl font-bold text-on-surface" }, "¡Seguridad activada!"),
											createVNode("p", { class: "text-sm text-on-surface-variant" }, " Tu cuenta ahora está protegida. Guarda estos códigos de recuperación en un lugar seguro. Los necesitarás si pierdes tu celular. ")
										]),
										createVNode("div", { class: "grid grid-cols-2 gap-2 bg-surface-container-low p-4 rounded-xl border border-outline-variant/20 font-mono text-xs" }, [(openBlock(true), createBlock(Fragment, null, renderList(recoveryCodes.value, (code) => {
											return openBlock(), createBlock("div", {
												key: code,
												class: "p-1 text-center select-all"
											}, toDisplayString(code), 1);
										}), 128))]),
										createVNode("div", { class: "pt-4" }, [createVNode(Button_default, {
											variant: "primary",
											size: "lg",
											class: "w-full shadow-lg shadow-primary/20",
											onClick: finishSetup
										}, {
											default: withCtx(() => [createTextVNode(" Ir al Dashboard ")]),
											_: 1
										})])
									]))]),
									_: 1
								})];
							}),
							_: 1
						}, _parent, _scopeId));
						_push(`<p class="mt-8 text-center text-[10px] text-on-surface-variant/40 font-bold uppercase tracking-widest leading-relaxed" data-v-02794c9a${_scopeId}> NEXO-PRO — Secure Multi-Tenant Architecture<br data-v-02794c9a${_scopeId}> Compliance Phase 1: Identity &amp; Access Management </p></div>`);
					} else return [createVNode("div", { class: "max-w-2xl mx-auto w-full px-6 py-12 flex flex-col justify-center min-h-screen" }, [
						createVNode("div", { class: "text-center mb-10 space-y-4" }, [
							createVNode("div", { class: "flex justify-center mb-4" }, [createVNode(Logo_default, {
								width: "64px",
								height: "64px",
								"show-text": false
							})]),
							createVNode("h1", { class: "text-3xl font-black text-primary tracking-tight uppercase" }, "Protege tu Cuenta"),
							createVNode("p", { class: "text-sm text-on-surface-variant/70 font-medium" }, " NEXO-PRO te ofrece Autenticación de Dos Factores (2FA) para mayor seguridad de tu cuenta. ")
						]),
						createVNode(Card_default, { class: "!p-8 md:!p-12 shadow-2xl border-primary/10 overflow-hidden relative" }, {
							default: withCtx(() => [createVNode("div", { class: "absolute -top-12 -right-12 w-32 h-32 bg-primary/5 rounded-full blur-3xl" }), createVNode(Transition, {
								mode: "out-in",
								"enter-active-class": "transition duration-300 ease-out",
								"enter-from-class": "opacity-0 translate-y-4",
								"enter-to-class": "opacity-100 translate-y-0",
								"leave-active-class": "transition duration-200 ease-in",
								"leave-from-class": "opacity-100 translate-y-0",
								"leave-to-class": "opacity-0 -translate-y-4"
							}, {
								default: withCtx(() => [setupStep.value === 1 ? (openBlock(), createBlock("div", {
									key: 1,
									class: "text-center space-y-6"
								}, [
									createVNode("div", { class: "w-20 h-20 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-6" }, [createVNode("span", { class: "material-symbols-outlined text-4xl text-primary" }, "security")]),
									createVNode("div", { class: "space-y-4" }, [createVNode("h2", { class: "text-xl font-bold text-on-surface" }, "¿Qué es el 2FA?"), createVNode("p", { class: "text-sm text-on-surface-variant" }, " Es una capa extra de seguridad. Además de tu contraseña, necesitarás un código generado en tu celular para ingresar. Esto evita accesos no autorizados incluso si alguien descubre tu clave. ")]),
									createVNode("div", { class: "pt-6 flex flex-col gap-4" }, [
										createVNode(Button_default, {
											variant: "primary",
											size: "lg",
											class: "w-full",
											loading: enabling.value,
											onClick: enableTwoFactorAuthentication
										}, {
											default: withCtx(() => [createTextVNode(" Configurar ahora ")]),
											_: 1
										}, 8, ["loading"]),
										createVNode("button", {
											onClick: finishSetup,
											class: "text-sm font-bold text-primary/80 hover:text-primary transition-colors uppercase tracking-widest"
										}, " Omitir "),
										createVNode("button", {
											onClick: logout,
											class: "text-[10px] font-bold text-on-surface-variant/30 uppercase tracking-[0.2em] hover:text-on-surface-variant transition-colors"
										}, " Cerrar sesión ")
									])
								])) : setupStep.value === 2 ? (openBlock(), createBlock("div", {
									key: 2,
									class: "space-y-8"
								}, [
									createVNode("div", { class: "text-center space-y-2" }, [createVNode("h2", { class: "text-xl font-bold text-on-surface" }, "Escanea el código QR"), createVNode("p", { class: "text-sm text-on-surface-variant" }, "Usa Google Authenticator, Authy o tu app favorita.")]),
									createVNode("div", { class: "flex justify-center p-4 bg-white rounded-2xl border border-outline-variant/30 w-fit mx-auto shadow-inner" }, [createVNode("div", {
										innerHTML: qrCode.value,
										class: "w-48 h-48 flex items-center justify-center"
									}, null, 8, ["innerHTML"])]),
									createVNode("div", { class: "space-y-4" }, [createVNode("label", { class: "text-[10px] font-black text-on-surface-variant/60 uppercase tracking-[0.2em] pl-1" }, " Ingresa el código de 6 dígitos "), createVNode("div", { class: "flex flex-col gap-4" }, [createVNode(Input_default, {
										modelValue: unref(confirmationForm).code,
										"onUpdate:modelValue": ($event) => unref(confirmationForm).code = $event,
										type: "text",
										placeholder: "000 000",
										icon: "pin",
										maxlength: "6",
										autofocus: "",
										error: unref(confirmationForm).errors.code,
										onKeyup: withKeys(confirmTwoFactorAuthentication, ["enter"])
									}, null, 8, [
										"modelValue",
										"onUpdate:modelValue",
										"error"
									]), createVNode(Button_default, {
										variant: "secondary",
										size: "lg",
										class: "w-full",
										loading: confirming.value,
										onClick: confirmTwoFactorAuthentication
									}, {
										default: withCtx(() => [createTextVNode(" Verificar y activar ")]),
										_: 1
									}, 8, ["loading"])])])
								])) : (openBlock(), createBlock("div", {
									key: 3,
									class: "space-y-8"
								}, [
									createVNode("div", { class: "text-center space-y-4" }, [
										createVNode("div", { class: "w-16 h-16 bg-secondary/10 rounded-full flex items-center justify-center mx-auto" }, [createVNode("span", { class: "material-symbols-outlined text-3xl text-secondary" }, "verified")]),
										createVNode("h2", { class: "text-xl font-bold text-on-surface" }, "¡Seguridad activada!"),
										createVNode("p", { class: "text-sm text-on-surface-variant" }, " Tu cuenta ahora está protegida. Guarda estos códigos de recuperación en un lugar seguro. Los necesitarás si pierdes tu celular. ")
									]),
									createVNode("div", { class: "grid grid-cols-2 gap-2 bg-surface-container-low p-4 rounded-xl border border-outline-variant/20 font-mono text-xs" }, [(openBlock(true), createBlock(Fragment, null, renderList(recoveryCodes.value, (code) => {
										return openBlock(), createBlock("div", {
											key: code,
											class: "p-1 text-center select-all"
										}, toDisplayString(code), 1);
									}), 128))]),
									createVNode("div", { class: "pt-4" }, [createVNode(Button_default, {
										variant: "primary",
										size: "lg",
										class: "w-full shadow-lg shadow-primary/20",
										onClick: finishSetup
									}, {
										default: withCtx(() => [createTextVNode(" Ir al Dashboard ")]),
										_: 1
									})])
								]))]),
								_: 1
							})]),
							_: 1
						}),
						createVNode("p", { class: "mt-8 text-center text-[10px] text-on-surface-variant/40 font-bold uppercase tracking-widest leading-relaxed" }, [
							createTextVNode(" NEXO-PRO — Secure Multi-Tenant Architecture"),
							createVNode("br"),
							createTextVNode(" Compliance Phase 1: Identity & Access Management ")
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
//#region resources/js/Pages/Auth/SetupTwoFactor.vue
var _sfc_setup = SetupTwoFactor_vue_vue_type_script_setup_true_lang_default.setup;
SetupTwoFactor_vue_vue_type_script_setup_true_lang_default.setup = (props, ctx) => {
	const ssrContext = useSSRContext();
	(ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Auth/SetupTwoFactor.vue");
	return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
var SetupTwoFactor_default = /* @__PURE__ */ _plugin_vue_export_helper_default(SetupTwoFactor_vue_vue_type_script_setup_true_lang_default, [["__scopeId", "data-v-02794c9a"]]);
//#endregion
export { SetupTwoFactor_default as default };

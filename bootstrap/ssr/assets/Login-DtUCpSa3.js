import { t as _plugin_vue_export_helper_default } from "./_plugin-vue_export-helper-BjJHhBxR.js";
import { t as Logo_default } from "./Logo-D89dXaWr.js";
import { t as Button_default } from "./Button-C-byZbTR.js";
import { t as Input_default } from "./Input-CbVZZMpc.js";
import { t as GuestLayout_default } from "./GuestLayout-BiN2s9qc.js";
import { t as Alert_default } from "./Alert-VVuj751F.js";
import { createBlock, createCommentVNode, createTextVNode, createVNode, defineComponent, openBlock, unref, useSSRContext, vModelCheckbox, withCtx, withDirectives, withModifiers } from "vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import { ssrIncludeBooleanAttr, ssrLooseContain, ssrRenderComponent } from "vue/server-renderer";
//#region resources/js/Pages/Auth/Login.vue?vue&type=script&setup=true&lang.ts
var Login_vue_vue_type_script_setup_true_lang_default = /* @__PURE__ */ defineComponent({
	__name: "Login",
	__ssrInlineRender: true,
	setup(__props) {
		const form = useForm({
			email: "",
			password: "",
			remember: false
		});
		const submit = () => {
			form.post(route("login"), { onFinish: () => form.reset("password") });
		};
		return (_ctx, _push, _parent, _attrs) => {
			_push(`<!--[-->`);
			_push(ssrRenderComponent(unref(Head), { title: "Acceso Seguro — NEXO-PRO" }, null, _parent));
			_push(ssrRenderComponent(GuestLayout_default, null, {
				default: withCtx((_, _push, _parent, _scopeId) => {
					if (_push) {
						_push(`<div class="min-h-screen w-full flex items-center justify-center p-6 md:p-12 relative overflow-hidden bg-surface" data-v-2d6269f4${_scopeId}><div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] rounded-full bg-primary/20 blur-[120px] pointer-events-none" data-v-2d6269f4${_scopeId}></div><div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] rounded-full bg-secondary/20 blur-[120px] pointer-events-none" data-v-2d6269f4${_scopeId}></div><div class="max-w-md w-full relative z-10" data-v-2d6269f4${_scopeId}><div class="premium-card p-10 md:p-12 space-y-10" data-v-2d6269f4${_scopeId}><div class="text-center space-y-4" data-v-2d6269f4${_scopeId}><div class="flex items-center justify-center mb-6 scale-110" data-v-2d6269f4${_scopeId}>`);
						_push(ssrRenderComponent(Logo_default, {
							width: "100px",
							height: "100px",
							"show-text": false
						}, null, _parent, _scopeId));
						_push(`</div><div class="space-y-2" data-v-2d6269f4${_scopeId}><h1 class="text-3xl font-extrabold text-on-surface tracking-tight" data-v-2d6269f4${_scopeId}>Bienvenido a NEXO-PRO</h1><p class="text-sm text-on-surface-variant font-medium" data-v-2d6269f4${_scopeId}>Inicia sesión en tu cuenta para gestionar tus propiedades.</p></div></div>`);
						if (Object.keys(unref(form).errors).length > 0) _push(ssrRenderComponent(Alert_default, {
							variant: "error",
							title: "Error de acceso",
							class: "mb-6"
						}, {
							default: withCtx((_, _push, _parent, _scopeId) => {
								if (_push) _push(` Las credenciales ingresadas no son correctas o el usuario no existe. `);
								else return [createTextVNode(" Las credenciales ingresadas no son correctas o el usuario no existe. ")];
							}),
							_: 1
						}, _parent, _scopeId));
						else _push(`<!---->`);
						_push(`<form class="space-y-6" data-v-2d6269f4${_scopeId}><div class="space-y-5" data-v-2d6269f4${_scopeId}>`);
						_push(ssrRenderComponent(Input_default, {
							modelValue: unref(form).email,
							"onUpdate:modelValue": ($event) => unref(form).email = $event,
							label: "Correo Electrónico",
							type: "email",
							placeholder: "nombre@ejemplo.com",
							icon: "alternate_email",
							required: "",
							autofocus: "",
							error: unref(form).errors.email,
							class: "min-h-[44px]"
						}, null, _parent, _scopeId));
						_push(`<div class="space-y-2" data-v-2d6269f4${_scopeId}>`);
						_push(ssrRenderComponent(Input_default, {
							modelValue: unref(form).password,
							"onUpdate:modelValue": ($event) => unref(form).password = $event,
							label: "Contraseña",
							type: "password",
							placeholder: "••••••••",
							icon: "lock",
							required: "",
							error: unref(form).errors.password,
							class: "min-h-[44px]"
						}, null, _parent, _scopeId));
						_push(`<div class="flex justify-end pt-1" data-v-2d6269f4${_scopeId}>`);
						_push(ssrRenderComponent(unref(Link), {
							href: "#",
							class: "text-xs font-bold text-primary hover:text-primary/80 transition-colors"
						}, {
							default: withCtx((_, _push, _parent, _scopeId) => {
								if (_push) _push(`¿Olvidaste tu contraseña?`);
								else return [createTextVNode("¿Olvidaste tu contraseña?")];
							}),
							_: 1
						}, _parent, _scopeId));
						_push(`</div></div></div><div class="flex items-center gap-3 pt-2" data-v-2d6269f4${_scopeId}><div class="relative flex items-center justify-center" data-v-2d6269f4${_scopeId}><input${ssrIncludeBooleanAttr(Array.isArray(unref(form).remember) ? ssrLooseContain(unref(form).remember, null) : unref(form).remember) ? " checked" : ""} type="checkbox" id="remember" class="peer w-5 h-5 rounded-md border-2 border-outline-variant text-primary focus:ring-primary/20 transition-all cursor-pointer appearance-none checked:bg-primary checked:border-primary" data-v-2d6269f4${_scopeId}><span class="material-symbols-rounded text-white text-[14px] absolute pointer-events-none opacity-0 peer-checked:opacity-100 transition-opacity" data-v-2d6269f4${_scopeId}>check</span></div><label for="remember" class="text-sm font-medium text-on-surface-variant cursor-pointer select-none" data-v-2d6269f4${_scopeId}>Mantener sesión activa</label></div><div class="space-y-6 pt-4" data-v-2d6269f4${_scopeId}>`);
						_push(ssrRenderComponent(Button_default, {
							type: "submit",
							variant: "primary",
							size: "lg",
							class: "w-full !rounded-xl !py-4 shadow-lg shadow-primary/20 hover:shadow-primary/40 font-bold",
							loading: unref(form).processing,
							icon: "login"
						}, {
							default: withCtx((_, _push, _parent, _scopeId) => {
								if (_push) _push(` Ingresar al Sistema `);
								else return [createTextVNode(" Ingresar al Sistema ")];
							}),
							_: 1
						}, _parent, _scopeId));
						_push(`<div class="text-center" data-v-2d6269f4${_scopeId}><p class="text-sm text-on-surface-variant font-medium" data-v-2d6269f4${_scopeId}> ¿Aún no eres parte? `);
						_push(ssrRenderComponent(unref(Link), {
							href: _ctx.route("register"),
							class: "text-primary font-bold ml-1 hover:underline underline-offset-4"
						}, {
							default: withCtx((_, _push, _parent, _scopeId) => {
								if (_push) _push(`Regístrate ahora`);
								else return [createTextVNode("Regístrate ahora")];
							}),
							_: 1
						}, _parent, _scopeId));
						_push(`</p></div></div></form></div><div class="pt-10 flex justify-center items-center gap-8 opacity-40" data-v-2d6269f4${_scopeId}><div class="flex items-center gap-2" data-v-2d6269f4${_scopeId}><span class="material-symbols-rounded text-sm" data-v-2d6269f4${_scopeId}>verified_user</span><span class="text-xs font-bold uppercase tracking-widest" data-v-2d6269f4${_scopeId}>Seguro</span></div><div class="w-1.5 h-1.5 rounded-full bg-on-surface-variant/30" data-v-2d6269f4${_scopeId}></div><div class="flex items-center gap-2" data-v-2d6269f4${_scopeId}><span class="material-symbols-rounded text-sm" data-v-2d6269f4${_scopeId}>shield_with_heart</span><span class="text-xs font-bold uppercase tracking-widest" data-v-2d6269f4${_scopeId}>Privado</span></div></div></div></div>`);
					} else return [createVNode("div", { class: "min-h-screen w-full flex items-center justify-center p-6 md:p-12 relative overflow-hidden bg-surface" }, [
						createVNode("div", { class: "absolute top-[-10%] left-[-10%] w-[40%] h-[40%] rounded-full bg-primary/20 blur-[120px] pointer-events-none" }),
						createVNode("div", { class: "absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] rounded-full bg-secondary/20 blur-[120px] pointer-events-none" }),
						createVNode("div", { class: "max-w-md w-full relative z-10" }, [createVNode("div", { class: "premium-card p-10 md:p-12 space-y-10" }, [
							createVNode("div", { class: "text-center space-y-4" }, [createVNode("div", { class: "flex items-center justify-center mb-6 scale-110" }, [createVNode(Logo_default, {
								width: "100px",
								height: "100px",
								"show-text": false
							})]), createVNode("div", { class: "space-y-2" }, [createVNode("h1", { class: "text-3xl font-extrabold text-on-surface tracking-tight" }, "Bienvenido a NEXO-PRO"), createVNode("p", { class: "text-sm text-on-surface-variant font-medium" }, "Inicia sesión en tu cuenta para gestionar tus propiedades.")])]),
							Object.keys(unref(form).errors).length > 0 ? (openBlock(), createBlock(Alert_default, {
								key: 0,
								variant: "error",
								title: "Error de acceso",
								class: "mb-6"
							}, {
								default: withCtx(() => [createTextVNode(" Las credenciales ingresadas no son correctas o el usuario no existe. ")]),
								_: 1
							})) : createCommentVNode("", true),
							createVNode("form", {
								onSubmit: withModifiers(submit, ["prevent"]),
								class: "space-y-6"
							}, [
								createVNode("div", { class: "space-y-5" }, [createVNode(Input_default, {
									modelValue: unref(form).email,
									"onUpdate:modelValue": ($event) => unref(form).email = $event,
									label: "Correo Electrónico",
									type: "email",
									placeholder: "nombre@ejemplo.com",
									icon: "alternate_email",
									required: "",
									autofocus: "",
									error: unref(form).errors.email,
									class: "min-h-[44px]"
								}, null, 8, [
									"modelValue",
									"onUpdate:modelValue",
									"error"
								]), createVNode("div", { class: "space-y-2" }, [createVNode(Input_default, {
									modelValue: unref(form).password,
									"onUpdate:modelValue": ($event) => unref(form).password = $event,
									label: "Contraseña",
									type: "password",
									placeholder: "••••••••",
									icon: "lock",
									required: "",
									error: unref(form).errors.password,
									class: "min-h-[44px]"
								}, null, 8, [
									"modelValue",
									"onUpdate:modelValue",
									"error"
								]), createVNode("div", { class: "flex justify-end pt-1" }, [createVNode(unref(Link), {
									href: "#",
									class: "text-xs font-bold text-primary hover:text-primary/80 transition-colors"
								}, {
									default: withCtx(() => [createTextVNode("¿Olvidaste tu contraseña?")]),
									_: 1
								})])])]),
								createVNode("div", { class: "flex items-center gap-3 pt-2" }, [createVNode("div", { class: "relative flex items-center justify-center" }, [withDirectives(createVNode("input", {
									"onUpdate:modelValue": ($event) => unref(form).remember = $event,
									type: "checkbox",
									id: "remember",
									class: "peer w-5 h-5 rounded-md border-2 border-outline-variant text-primary focus:ring-primary/20 transition-all cursor-pointer appearance-none checked:bg-primary checked:border-primary"
								}, null, 8, ["onUpdate:modelValue"]), [[vModelCheckbox, unref(form).remember]]), createVNode("span", { class: "material-symbols-rounded text-white text-[14px] absolute pointer-events-none opacity-0 peer-checked:opacity-100 transition-opacity" }, "check")]), createVNode("label", {
									for: "remember",
									class: "text-sm font-medium text-on-surface-variant cursor-pointer select-none"
								}, "Mantener sesión activa")]),
								createVNode("div", { class: "space-y-6 pt-4" }, [createVNode(Button_default, {
									type: "submit",
									variant: "primary",
									size: "lg",
									class: "w-full !rounded-xl !py-4 shadow-lg shadow-primary/20 hover:shadow-primary/40 font-bold",
									loading: unref(form).processing,
									icon: "login"
								}, {
									default: withCtx(() => [createTextVNode(" Ingresar al Sistema ")]),
									_: 1
								}, 8, ["loading"]), createVNode("div", { class: "text-center" }, [createVNode("p", { class: "text-sm text-on-surface-variant font-medium" }, [createTextVNode(" ¿Aún no eres parte? "), createVNode(unref(Link), {
									href: _ctx.route("register"),
									class: "text-primary font-bold ml-1 hover:underline underline-offset-4"
								}, {
									default: withCtx(() => [createTextVNode("Regístrate ahora")]),
									_: 1
								}, 8, ["href"])])])])
							], 32)
						]), createVNode("div", { class: "pt-10 flex justify-center items-center gap-8 opacity-40" }, [
							createVNode("div", { class: "flex items-center gap-2" }, [createVNode("span", { class: "material-symbols-rounded text-sm" }, "verified_user"), createVNode("span", { class: "text-xs font-bold uppercase tracking-widest" }, "Seguro")]),
							createVNode("div", { class: "w-1.5 h-1.5 rounded-full bg-on-surface-variant/30" }),
							createVNode("div", { class: "flex items-center gap-2" }, [createVNode("span", { class: "material-symbols-rounded text-sm" }, "shield_with_heart"), createVNode("span", { class: "text-xs font-bold uppercase tracking-widest" }, "Privado")])
						])])
					])];
				}),
				_: 1
			}, _parent));
			_push(`<!--]-->`);
		};
	}
});
//#endregion
//#region resources/js/Pages/Auth/Login.vue
var _sfc_setup = Login_vue_vue_type_script_setup_true_lang_default.setup;
Login_vue_vue_type_script_setup_true_lang_default.setup = (props, ctx) => {
	const ssrContext = useSSRContext();
	(ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Auth/Login.vue");
	return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
var Login_default = /* @__PURE__ */ _plugin_vue_export_helper_default(Login_vue_vue_type_script_setup_true_lang_default, [["__scopeId", "data-v-2d6269f4"]]);
//#endregion
export { Login_default as default };

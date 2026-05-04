import { t as _plugin_vue_export_helper_default } from "./_plugin-vue_export-helper-BjJHhBxR.js";
import { t as Logo_default } from "./Logo-BJuTBUmx.js";
import { t as Card_default } from "./Card-C2-I3_la.js";
import { t as Button_default } from "./Button-5V-KYwxi.js";
import { t as Modal_default } from "./Modal-CeRVVYhy.js";
import { t as Input_default } from "./Input-DWocsxNw.js";
import { t as Checkbox_default } from "./Checkbox-CgLIRqSZ.js";
import { t as GuestLayout_default } from "./GuestLayout-CdsdxXMK.js";
import { t as Alert_default } from "./Alert-CEZmLLZB.js";
import { t as LegalContent_default } from "./LegalContent-FJqdngVc.js";
import { Fragment, Transition, computed, createBlock, createCommentVNode, createTextVNode, createVNode, defineComponent, openBlock, ref, renderList, toDisplayString, unref, useSSRContext, withCtx, withModifiers } from "vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import { ssrInterpolate, ssrRenderClass, ssrRenderComponent, ssrRenderList, ssrRenderStyle } from "vue/server-renderer";
//#region resources/js/Pages/Auth/Onboarding.vue?vue&type=script&setup=true&lang.ts
var totalSteps = 3;
var Onboarding_vue_vue_type_script_setup_true_lang_default = /* @__PURE__ */ defineComponent({
	__name: "Onboarding",
	__ssrInlineRender: true,
	setup(__props) {
		const showLegalModal = ref(false);
		const legalModalType = ref("privacy");
		const openLegalModal = (type) => {
			legalModalType.value = type;
			showLegalModal.value = true;
		};
		const acceptLegalModal = () => {
			form.terms = true;
			showLegalModal.value = false;
		};
		const step = ref(1);
		const form = useForm({
			nit: "",
			nombre_copropiedad: "",
			direccion: "",
			ciudad: "",
			plan: "pro",
			unidades_totales: null,
			torres: null,
			name: "",
			email: "",
			password: "",
			password_confirmation: "",
			terms: false
		});
		const nextStep = () => {
			if (step.value < totalSteps) step.value++;
		};
		const prevStep = () => {
			if (step.value > 1) step.value--;
		};
		const submit = () => {
			form.post(route("register"), { onFinish: () => form.reset("password", "password_confirmation") });
		};
		const stepInfo = computed(() => {
			switch (step.value) {
				case 1: return {
					title: "Identificación",
					subtitle: "Datos legales del conjunto"
				};
				case 2: return {
					title: "Configuración",
					subtitle: "Capacidad y plan de servicio"
				};
				case 3: return {
					title: "Acceso",
					subtitle: "Tu cuenta de administrador"
				};
				default: return {
					title: "",
					subtitle: ""
				};
			}
		});
		return (_ctx, _push, _parent, _attrs) => {
			_push(`<!--[-->`);
			_push(ssrRenderComponent(unref(Head), { title: "Registro de Copropiedad — NEXO-PRO" }, null, _parent));
			_push(ssrRenderComponent(GuestLayout_default, null, {
				default: withCtx((_, _push, _parent, _scopeId) => {
					if (_push) {
						_push(`<div class="max-w-4xl mx-auto w-full px-6 py-12 md:py-20 min-h-screen flex flex-col justify-center" data-v-a44f3098${_scopeId}><div class="text-center mb-12 space-y-3" data-v-a44f3098${_scopeId}><div class="flex justify-center mb-6" data-v-a44f3098${_scopeId}>`);
						_push(ssrRenderComponent(Logo_default, {
							width: "80px",
							height: "80px",
							"show-text": false
						}, null, _parent, _scopeId));
						_push(`</div><h1 class="text-4xl font-black text-primary tracking-tighter uppercase leading-none" data-v-a44f3098${_scopeId}>Únete a NEXO-PRO</h1><p class="text-xs text-on-surface-variant/60 font-bold uppercase tracking-[0.2em]" data-v-a44f3098${_scopeId}>Estás a pocos pasos de digitalizar tu administración</p></div><div class="mb-12 max-w-lg mx-auto w-full" data-v-a44f3098${_scopeId}><div class="flex items-center justify-between mb-6" data-v-a44f3098${_scopeId}><!--[-->`);
						ssrRenderList(totalSteps, (i) => {
							_push(`<div class="flex items-center flex-1 last:flex-none" data-v-a44f3098${_scopeId}><div class="${ssrRenderClass([[i < step.value ? "bg-secondary border-secondary text-on-secondary shadow-lg" : i === step.value ? "bg-primary border-primary text-on-primary shadow-xl scale-110" : "bg-surface-container-low border-outline-variant/20 text-on-surface-variant/40"], "w-10 h-10 rounded-2xl flex items-center justify-center text-xs font-black transition-all duration-500 border-2"])}" data-v-a44f3098${_scopeId}>`);
							if (i < step.value) _push(`<span class="material-symbols-outlined text-xl" data-v-a44f3098${_scopeId}>check</span>`);
							else _push(`<span data-v-a44f3098${_scopeId}>${ssrInterpolate(i)}</span>`);
							_push(`</div>`);
							if (i < totalSteps) _push(`<div class="flex-1 h-0.5 mx-2 bg-outline-variant/10 rounded-full overflow-hidden" data-v-a44f3098${_scopeId}><div class="h-full bg-secondary transition-all duration-700" style="${ssrRenderStyle({ width: i < step.value ? "100%" : "0%" })}" data-v-a44f3098${_scopeId}></div></div>`);
							else _push(`<!---->`);
							_push(`</div>`);
						});
						_push(`<!--]--></div><div class="text-center" data-v-a44f3098${_scopeId}><h3 class="text-sm font-black text-primary uppercase tracking-widest leading-none" data-v-a44f3098${_scopeId}>${ssrInterpolate(stepInfo.value.title)}</h3><p class="text-[10px] text-on-surface-variant/50 font-bold uppercase tracking-widest mt-1.5" data-v-a44f3098${_scopeId}>${ssrInterpolate(stepInfo.value.subtitle)}</p></div></div>`);
						if (Object.keys(unref(form).errors).length > 0) _push(ssrRenderComponent(Alert_default, {
							variant: "error",
							title: "Atención",
							class: "mb-8"
						}, {
							default: withCtx((_, _push, _parent, _scopeId) => {
								if (_push) _push(` Por favor revisa los campos marcados en rojo para continuar. `);
								else return [createTextVNode(" Por favor revisa los campos marcados en rojo para continuar. ")];
							}),
							_: 1
						}, _parent, _scopeId));
						else _push(`<!---->`);
						_push(`<form class="relative" data-v-a44f3098${_scopeId}>`);
						if (step.value === 1) {
							_push(`<div data-v-a44f3098${_scopeId}>`);
							_push(ssrRenderComponent(Card_default, { class: "!p-8 md:!p-12" }, {
								default: withCtx((_, _push, _parent, _scopeId) => {
									if (_push) {
										_push(`<div class="grid grid-cols-1 md:grid-cols-2 gap-10" data-v-a44f3098${_scopeId}>`);
										_push(ssrRenderComponent(Input_default, {
											modelValue: unref(form).nit,
											"onUpdate:modelValue": ($event) => unref(form).nit = $event,
											label: "NIT de la Copropiedad",
											placeholder: "900.XXX.XXX-X",
											icon: "badge",
											required: "",
											error: unref(form).errors.nit
										}, null, _parent, _scopeId));
										_push(ssrRenderComponent(Input_default, {
											modelValue: unref(form).nombre_copropiedad,
											"onUpdate:modelValue": ($event) => unref(form).nombre_copropiedad = $event,
											label: "Nombre del Edificio / Conjunto",
											placeholder: "Ej: Residencial El Sol",
											icon: "corporate_fare",
											required: "",
											error: unref(form).errors.nombre_copropiedad
										}, null, _parent, _scopeId));
										_push(ssrRenderComponent(Input_default, {
											modelValue: unref(form).direccion,
											"onUpdate:modelValue": ($event) => unref(form).direccion = $event,
											label: "Dirección Física",
											placeholder: "Calle 123 # 45-67",
											icon: "location_on",
											required: "",
											error: unref(form).errors.direccion
										}, null, _parent, _scopeId));
										_push(ssrRenderComponent(Input_default, {
											modelValue: unref(form).ciudad,
											"onUpdate:modelValue": ($event) => unref(form).ciudad = $event,
											label: "Ciudad / Municipio",
											placeholder: "Ej: Medellín",
											icon: "map",
											required: "",
											error: unref(form).errors.ciudad
										}, null, _parent, _scopeId));
										_push(`</div>`);
									} else return [createVNode("div", { class: "grid grid-cols-1 md:grid-cols-2 gap-10" }, [
										createVNode(Input_default, {
											modelValue: unref(form).nit,
											"onUpdate:modelValue": ($event) => unref(form).nit = $event,
											label: "NIT de la Copropiedad",
											placeholder: "900.XXX.XXX-X",
											icon: "badge",
											required: "",
											error: unref(form).errors.nit
										}, null, 8, [
											"modelValue",
											"onUpdate:modelValue",
											"error"
										]),
										createVNode(Input_default, {
											modelValue: unref(form).nombre_copropiedad,
											"onUpdate:modelValue": ($event) => unref(form).nombre_copropiedad = $event,
											label: "Nombre del Edificio / Conjunto",
											placeholder: "Ej: Residencial El Sol",
											icon: "corporate_fare",
											required: "",
											error: unref(form).errors.nombre_copropiedad
										}, null, 8, [
											"modelValue",
											"onUpdate:modelValue",
											"error"
										]),
										createVNode(Input_default, {
											modelValue: unref(form).direccion,
											"onUpdate:modelValue": ($event) => unref(form).direccion = $event,
											label: "Dirección Física",
											placeholder: "Calle 123 # 45-67",
											icon: "location_on",
											required: "",
											error: unref(form).errors.direccion
										}, null, 8, [
											"modelValue",
											"onUpdate:modelValue",
											"error"
										]),
										createVNode(Input_default, {
											modelValue: unref(form).ciudad,
											"onUpdate:modelValue": ($event) => unref(form).ciudad = $event,
											label: "Ciudad / Municipio",
											placeholder: "Ej: Medellín",
											icon: "map",
											required: "",
											error: unref(form).errors.ciudad
										}, null, 8, [
											"modelValue",
											"onUpdate:modelValue",
											"error"
										])
									])];
								}),
								_: 1
							}, _parent, _scopeId));
							_push(`</div>`);
						} else if (step.value === 2) {
							_push(`<div data-v-a44f3098${_scopeId}>`);
							_push(ssrRenderComponent(Card_default, { class: "!p-8 md:!p-12" }, {
								default: withCtx((_, _push, _parent, _scopeId) => {
									if (_push) {
										_push(`<div class="space-y-10" data-v-a44f3098${_scopeId}><div class="space-y-4" data-v-a44f3098${_scopeId}><label class="text-[10px] font-black text-on-surface-variant/60 uppercase tracking-[0.2em] pl-1" data-v-a44f3098${_scopeId}>Selecciona un Plan de Servicio</label><div class="grid grid-cols-1 md:grid-cols-3 gap-4" data-v-a44f3098${_scopeId}><!--[-->`);
										ssrRenderList([
											"basic",
											"pro",
											"enterprise"
										], (p) => {
											_push(`<div class="${ssrRenderClass([unref(form).plan === p ? "border-primary bg-primary/5 shadow-xl scale-[1.02]" : "border-outline-variant/10 hover:border-primary/30", "p-6 rounded-[2rem] border-2 cursor-pointer transition-all duration-300 group relative overflow-hidden"])}" data-v-a44f3098${_scopeId}>`);
											if (unref(form).plan === p) _push(`<div class="absolute top-4 right-4" data-v-a44f3098${_scopeId}><span class="material-symbols-outlined text-primary text-xl" data-v-a44f3098${_scopeId}>check_circle</span></div>`);
											else _push(`<!---->`);
											_push(`<p class="text-xs font-black uppercase tracking-widest text-primary mb-1" data-v-a44f3098${_scopeId}>${ssrInterpolate(p)}</p><p class="text-[10px] font-bold text-on-surface-variant/60" data-v-a44f3098${_scopeId}>${ssrInterpolate(p === "basic" ? "Hasta 50 unidades" : p === "pro" ? "Hasta 200 unidades" : "Unidades ilimitadas")}</p></div>`);
										});
										_push(`<!--]--></div></div><div class="grid grid-cols-1 md:grid-cols-2 gap-8 pt-4 border-t border-outline-variant/5" data-v-a44f3098${_scopeId}>`);
										_push(ssrRenderComponent(Input_default, {
											modelValue: unref(form).unidades_totales,
											"onUpdate:modelValue": ($event) => unref(form).unidades_totales = $event,
											type: "number",
											label: "Unidades Totales",
											placeholder: "0",
											icon: "apartment",
											required: "",
											error: unref(form).errors.unidades_totales
										}, null, _parent, _scopeId));
										_push(ssrRenderComponent(Input_default, {
											modelValue: unref(form).torres,
											"onUpdate:modelValue": ($event) => unref(form).torres = $event,
											type: "number",
											label: "Número de Torres / Bloques",
											placeholder: "0",
											icon: "domain",
											required: "",
											error: unref(form).errors.torres
										}, null, _parent, _scopeId));
										_push(`</div></div>`);
									} else return [createVNode("div", { class: "space-y-10" }, [createVNode("div", { class: "space-y-4" }, [createVNode("label", { class: "text-[10px] font-black text-on-surface-variant/60 uppercase tracking-[0.2em] pl-1" }, "Selecciona un Plan de Servicio"), createVNode("div", { class: "grid grid-cols-1 md:grid-cols-3 gap-4" }, [(openBlock(), createBlock(Fragment, null, renderList([
										"basic",
										"pro",
										"enterprise"
									], (p) => {
										return createVNode("div", {
											key: p,
											onClick: ($event) => unref(form).plan = p,
											class: ["p-6 rounded-[2rem] border-2 cursor-pointer transition-all duration-300 group relative overflow-hidden", unref(form).plan === p ? "border-primary bg-primary/5 shadow-xl scale-[1.02]" : "border-outline-variant/10 hover:border-primary/30"]
										}, [
											unref(form).plan === p ? (openBlock(), createBlock("div", {
												key: 0,
												class: "absolute top-4 right-4"
											}, [createVNode("span", { class: "material-symbols-outlined text-primary text-xl" }, "check_circle")])) : createCommentVNode("", true),
											createVNode("p", { class: "text-xs font-black uppercase tracking-widest text-primary mb-1" }, toDisplayString(p), 1),
											createVNode("p", { class: "text-[10px] font-bold text-on-surface-variant/60" }, toDisplayString(p === "basic" ? "Hasta 50 unidades" : p === "pro" ? "Hasta 200 unidades" : "Unidades ilimitadas"), 1)
										], 10, ["onClick"]);
									}), 64))])]), createVNode("div", { class: "grid grid-cols-1 md:grid-cols-2 gap-8 pt-4 border-t border-outline-variant/5" }, [createVNode(Input_default, {
										modelValue: unref(form).unidades_totales,
										"onUpdate:modelValue": ($event) => unref(form).unidades_totales = $event,
										type: "number",
										label: "Unidades Totales",
										placeholder: "0",
										icon: "apartment",
										required: "",
										error: unref(form).errors.unidades_totales
									}, null, 8, [
										"modelValue",
										"onUpdate:modelValue",
										"error"
									]), createVNode(Input_default, {
										modelValue: unref(form).torres,
										"onUpdate:modelValue": ($event) => unref(form).torres = $event,
										type: "number",
										label: "Número de Torres / Bloques",
										placeholder: "0",
										icon: "domain",
										required: "",
										error: unref(form).errors.torres
									}, null, 8, [
										"modelValue",
										"onUpdate:modelValue",
										"error"
									])])])];
								}),
								_: 1
							}, _parent, _scopeId));
							_push(`</div>`);
						} else {
							_push(`<div data-v-a44f3098${_scopeId}>`);
							_push(ssrRenderComponent(Card_default, { class: "!p-8 md:!p-12" }, {
								default: withCtx((_, _push, _parent, _scopeId) => {
									if (_push) {
										_push(`<div class="space-y-8" data-v-a44f3098${_scopeId}><div class="grid grid-cols-1 md:grid-cols-2 gap-10" data-v-a44f3098${_scopeId}>`);
										_push(ssrRenderComponent(Input_default, {
											modelValue: unref(form).name,
											"onUpdate:modelValue": ($event) => unref(form).name = $event,
											label: "Nombre del Administrador",
											placeholder: "Nombre completo",
											icon: "person",
											required: "",
											error: unref(form).errors.name
										}, null, _parent, _scopeId));
										_push(ssrRenderComponent(Input_default, {
											modelValue: unref(form).email,
											"onUpdate:modelValue": ($event) => unref(form).email = $event,
											type: "email",
											label: "Correo Electrónico Corporativo",
											placeholder: "admin@conjunto.com",
											icon: "alternate_email",
											required: "",
											error: unref(form).errors.email
										}, null, _parent, _scopeId));
										_push(`</div><div class="grid grid-cols-1 md:grid-cols-2 gap-10" data-v-a44f3098${_scopeId}>`);
										_push(ssrRenderComponent(Input_default, {
											modelValue: unref(form).password,
											"onUpdate:modelValue": ($event) => unref(form).password = $event,
											type: "password",
											label: "Contraseña de Acceso",
											placeholder: "••••••••",
											icon: "lock",
											required: "",
											error: unref(form).errors.password
										}, null, _parent, _scopeId));
										_push(ssrRenderComponent(Input_default, {
											modelValue: unref(form).password_confirmation,
											"onUpdate:modelValue": ($event) => unref(form).password_confirmation = $event,
											type: "password",
											label: "Confirmar Contraseña",
											placeholder: "••••••••",
											icon: "verified_user",
											required: ""
										}, null, _parent, _scopeId));
										_push(`</div><div class="pt-4 border-t border-outline-variant/5" data-v-a44f3098${_scopeId}>`);
										_push(ssrRenderComponent(Checkbox_default, {
											modelValue: unref(form).terms,
											"onUpdate:modelValue": ($event) => unref(form).terms = $event,
											required: "",
											error: unref(form).errors.terms
										}, {
											description: withCtx((_, _push, _parent, _scopeId) => {
												if (_push) _push(` Autorizo de forma previa, expresa e informada a NEXO-PRO para el tratamiento de mis datos personales según la <button type="button" class="text-primary font-bold hover:underline" data-v-a44f3098${_scopeId}>Política de Privacidad</button> y la Ley 1581 de 2012, y acepto los <button type="button" class="text-primary font-bold hover:underline" data-v-a44f3098${_scopeId}>Términos y Condiciones</button>. `);
												else return [
													createTextVNode(" Autorizo de forma previa, expresa e informada a NEXO-PRO para el tratamiento de mis datos personales según la "),
													createVNode("button", {
														type: "button",
														onClick: withModifiers(($event) => openLegalModal("privacy"), ["prevent", "stop"]),
														class: "text-primary font-bold hover:underline"
													}, "Política de Privacidad", 8, ["onClick"]),
													createTextVNode(" y la Ley 1581 de 2012, y acepto los "),
													createVNode("button", {
														type: "button",
														onClick: withModifiers(($event) => openLegalModal("terms"), ["prevent", "stop"]),
														class: "text-primary font-bold hover:underline"
													}, "Términos y Condiciones", 8, ["onClick"]),
													createTextVNode(". ")
												];
											}),
											_: 1
										}, _parent, _scopeId));
										_push(`</div></div>`);
									} else return [createVNode("div", { class: "space-y-8" }, [
										createVNode("div", { class: "grid grid-cols-1 md:grid-cols-2 gap-10" }, [createVNode(Input_default, {
											modelValue: unref(form).name,
											"onUpdate:modelValue": ($event) => unref(form).name = $event,
											label: "Nombre del Administrador",
											placeholder: "Nombre completo",
											icon: "person",
											required: "",
											error: unref(form).errors.name
										}, null, 8, [
											"modelValue",
											"onUpdate:modelValue",
											"error"
										]), createVNode(Input_default, {
											modelValue: unref(form).email,
											"onUpdate:modelValue": ($event) => unref(form).email = $event,
											type: "email",
											label: "Correo Electrónico Corporativo",
											placeholder: "admin@conjunto.com",
											icon: "alternate_email",
											required: "",
											error: unref(form).errors.email
										}, null, 8, [
											"modelValue",
											"onUpdate:modelValue",
											"error"
										])]),
										createVNode("div", { class: "grid grid-cols-1 md:grid-cols-2 gap-10" }, [createVNode(Input_default, {
											modelValue: unref(form).password,
											"onUpdate:modelValue": ($event) => unref(form).password = $event,
											type: "password",
											label: "Contraseña de Acceso",
											placeholder: "••••••••",
											icon: "lock",
											required: "",
											error: unref(form).errors.password
										}, null, 8, [
											"modelValue",
											"onUpdate:modelValue",
											"error"
										]), createVNode(Input_default, {
											modelValue: unref(form).password_confirmation,
											"onUpdate:modelValue": ($event) => unref(form).password_confirmation = $event,
											type: "password",
											label: "Confirmar Contraseña",
											placeholder: "••••••••",
											icon: "verified_user",
											required: ""
										}, null, 8, ["modelValue", "onUpdate:modelValue"])]),
										createVNode("div", { class: "pt-4 border-t border-outline-variant/5" }, [createVNode(Checkbox_default, {
											modelValue: unref(form).terms,
											"onUpdate:modelValue": ($event) => unref(form).terms = $event,
											required: "",
											error: unref(form).errors.terms
										}, {
											description: withCtx(() => [
												createTextVNode(" Autorizo de forma previa, expresa e informada a NEXO-PRO para el tratamiento de mis datos personales según la "),
												createVNode("button", {
													type: "button",
													onClick: withModifiers(($event) => openLegalModal("privacy"), ["prevent", "stop"]),
													class: "text-primary font-bold hover:underline"
												}, "Política de Privacidad", 8, ["onClick"]),
												createTextVNode(" y la Ley 1581 de 2012, y acepto los "),
												createVNode("button", {
													type: "button",
													onClick: withModifiers(($event) => openLegalModal("terms"), ["prevent", "stop"]),
													class: "text-primary font-bold hover:underline"
												}, "Términos y Condiciones", 8, ["onClick"]),
												createTextVNode(". ")
											]),
											_: 1
										}, 8, [
											"modelValue",
											"onUpdate:modelValue",
											"error"
										])])
									])];
								}),
								_: 1
							}, _parent, _scopeId));
							_push(`</div>`);
						}
						_push(`<div class="mt-12 flex flex-col sm:flex-row justify-between items-center gap-4 px-2" data-v-a44f3098${_scopeId}>`);
						if (step.value > 1) _push(ssrRenderComponent(Button_default, {
							variant: "ghost",
							icon: "arrow_back",
							onClick: prevStep
						}, {
							default: withCtx((_, _push, _parent, _scopeId) => {
								if (_push) _push(` Volver `);
								else return [createTextVNode(" Volver ")];
							}),
							_: 1
						}, _parent, _scopeId));
						else _push(`<div data-v-a44f3098${_scopeId}></div>`);
						_push(`<div class="flex gap-4 w-full sm:w-auto" data-v-a44f3098${_scopeId}>`);
						if (step.value < totalSteps) _push(ssrRenderComponent(Button_default, {
							type: "button",
							variant: "primary",
							size: "lg",
							class: "w-full sm:w-auto !px-12 shadow-xl shadow-primary/20",
							icon: "arrow_forward",
							onClick: nextStep
						}, {
							default: withCtx((_, _push, _parent, _scopeId) => {
								if (_push) _push(` Continuar `);
								else return [createTextVNode(" Continuar ")];
							}),
							_: 1
						}, _parent, _scopeId));
						else _push(ssrRenderComponent(Button_default, {
							type: "submit",
							variant: "secondary",
							size: "lg",
							class: "w-full sm:w-auto !px-12 shadow-xl shadow-secondary/20",
							icon: "how_to_reg",
							loading: unref(form).processing
						}, {
							default: withCtx((_, _push, _parent, _scopeId) => {
								if (_push) _push(` Finalizar Registro `);
								else return [createTextVNode(" Finalizar Registro ")];
							}),
							_: 1
						}, _parent, _scopeId));
						_push(`</div></div></form><div class="mt-12 text-center" data-v-a44f3098${_scopeId}><p class="text-[10px] text-on-surface-variant/50 font-bold uppercase tracking-widest" data-v-a44f3098${_scopeId}> ¿Ya gestionas con nosotros? `);
						_push(ssrRenderComponent(unref(Link), {
							href: _ctx.route("login"),
							class: "text-primary font-black ml-1 hover:underline underline-offset-4"
						}, {
							default: withCtx((_, _push, _parent, _scopeId) => {
								if (_push) _push(`Inicia Sesión`);
								else return [createTextVNode("Inicia Sesión")];
							}),
							_: 1
						}, _parent, _scopeId));
						_push(`</p></div></div>`);
					} else return [createVNode("div", { class: "max-w-4xl mx-auto w-full px-6 py-12 md:py-20 min-h-screen flex flex-col justify-center" }, [
						createVNode("div", { class: "text-center mb-12 space-y-3" }, [
							createVNode("div", { class: "flex justify-center mb-6" }, [createVNode(Logo_default, {
								width: "80px",
								height: "80px",
								"show-text": false
							})]),
							createVNode("h1", { class: "text-4xl font-black text-primary tracking-tighter uppercase leading-none" }, "Únete a NEXO-PRO"),
							createVNode("p", { class: "text-xs text-on-surface-variant/60 font-bold uppercase tracking-[0.2em]" }, "Estás a pocos pasos de digitalizar tu administración")
						]),
						createVNode("div", { class: "mb-12 max-w-lg mx-auto w-full" }, [createVNode("div", { class: "flex items-center justify-between mb-6" }, [(openBlock(), createBlock(Fragment, null, renderList(totalSteps, (i) => {
							return createVNode("div", {
								key: i,
								class: "flex items-center flex-1 last:flex-none"
							}, [createVNode("div", { class: ["w-10 h-10 rounded-2xl flex items-center justify-center text-xs font-black transition-all duration-500 border-2", [i < step.value ? "bg-secondary border-secondary text-on-secondary shadow-lg" : i === step.value ? "bg-primary border-primary text-on-primary shadow-xl scale-110" : "bg-surface-container-low border-outline-variant/20 text-on-surface-variant/40"]] }, [i < step.value ? (openBlock(), createBlock("span", {
								key: 0,
								class: "material-symbols-outlined text-xl"
							}, "check")) : (openBlock(), createBlock("span", { key: 1 }, toDisplayString(i), 1))], 2), i < totalSteps ? (openBlock(), createBlock("div", {
								key: 0,
								class: "flex-1 h-0.5 mx-2 bg-outline-variant/10 rounded-full overflow-hidden"
							}, [createVNode("div", {
								class: "h-full bg-secondary transition-all duration-700",
								style: { width: i < step.value ? "100%" : "0%" }
							}, null, 4)])) : createCommentVNode("", true)]);
						}), 64))]), createVNode("div", { class: "text-center" }, [createVNode("h3", { class: "text-sm font-black text-primary uppercase tracking-widest leading-none" }, toDisplayString(stepInfo.value.title), 1), createVNode("p", { class: "text-[10px] text-on-surface-variant/50 font-bold uppercase tracking-widest mt-1.5" }, toDisplayString(stepInfo.value.subtitle), 1)])]),
						Object.keys(unref(form).errors).length > 0 ? (openBlock(), createBlock(Alert_default, {
							key: 0,
							variant: "error",
							title: "Atención",
							class: "mb-8"
						}, {
							default: withCtx(() => [createTextVNode(" Por favor revisa los campos marcados en rojo para continuar. ")]),
							_: 1
						})) : createCommentVNode("", true),
						createVNode("form", {
							onSubmit: withModifiers(submit, ["prevent"]),
							class: "relative"
						}, [createVNode(Transition, {
							"enter-active-class": "transition duration-500 ease-out",
							"enter-from-class": "opacity-0 translate-x-12",
							"enter-to-class": "opacity-100 translate-x-0",
							"leave-active-class": "transition duration-300 ease-in absolute w-full",
							"leave-from-class": "opacity-100 translate-x-0",
							"leave-to-class": "opacity-0 -translate-x-12",
							mode: "out-in"
						}, {
							default: withCtx(() => [step.value === 1 ? (openBlock(), createBlock("div", { key: 1 }, [createVNode(Card_default, { class: "!p-8 md:!p-12" }, {
								default: withCtx(() => [createVNode("div", { class: "grid grid-cols-1 md:grid-cols-2 gap-10" }, [
									createVNode(Input_default, {
										modelValue: unref(form).nit,
										"onUpdate:modelValue": ($event) => unref(form).nit = $event,
										label: "NIT de la Copropiedad",
										placeholder: "900.XXX.XXX-X",
										icon: "badge",
										required: "",
										error: unref(form).errors.nit
									}, null, 8, [
										"modelValue",
										"onUpdate:modelValue",
										"error"
									]),
									createVNode(Input_default, {
										modelValue: unref(form).nombre_copropiedad,
										"onUpdate:modelValue": ($event) => unref(form).nombre_copropiedad = $event,
										label: "Nombre del Edificio / Conjunto",
										placeholder: "Ej: Residencial El Sol",
										icon: "corporate_fare",
										required: "",
										error: unref(form).errors.nombre_copropiedad
									}, null, 8, [
										"modelValue",
										"onUpdate:modelValue",
										"error"
									]),
									createVNode(Input_default, {
										modelValue: unref(form).direccion,
										"onUpdate:modelValue": ($event) => unref(form).direccion = $event,
										label: "Dirección Física",
										placeholder: "Calle 123 # 45-67",
										icon: "location_on",
										required: "",
										error: unref(form).errors.direccion
									}, null, 8, [
										"modelValue",
										"onUpdate:modelValue",
										"error"
									]),
									createVNode(Input_default, {
										modelValue: unref(form).ciudad,
										"onUpdate:modelValue": ($event) => unref(form).ciudad = $event,
										label: "Ciudad / Municipio",
										placeholder: "Ej: Medellín",
										icon: "map",
										required: "",
										error: unref(form).errors.ciudad
									}, null, 8, [
										"modelValue",
										"onUpdate:modelValue",
										"error"
									])
								])]),
								_: 1
							})])) : step.value === 2 ? (openBlock(), createBlock("div", { key: 2 }, [createVNode(Card_default, { class: "!p-8 md:!p-12" }, {
								default: withCtx(() => [createVNode("div", { class: "space-y-10" }, [createVNode("div", { class: "space-y-4" }, [createVNode("label", { class: "text-[10px] font-black text-on-surface-variant/60 uppercase tracking-[0.2em] pl-1" }, "Selecciona un Plan de Servicio"), createVNode("div", { class: "grid grid-cols-1 md:grid-cols-3 gap-4" }, [(openBlock(), createBlock(Fragment, null, renderList([
									"basic",
									"pro",
									"enterprise"
								], (p) => {
									return createVNode("div", {
										key: p,
										onClick: ($event) => unref(form).plan = p,
										class: ["p-6 rounded-[2rem] border-2 cursor-pointer transition-all duration-300 group relative overflow-hidden", unref(form).plan === p ? "border-primary bg-primary/5 shadow-xl scale-[1.02]" : "border-outline-variant/10 hover:border-primary/30"]
									}, [
										unref(form).plan === p ? (openBlock(), createBlock("div", {
											key: 0,
											class: "absolute top-4 right-4"
										}, [createVNode("span", { class: "material-symbols-outlined text-primary text-xl" }, "check_circle")])) : createCommentVNode("", true),
										createVNode("p", { class: "text-xs font-black uppercase tracking-widest text-primary mb-1" }, toDisplayString(p), 1),
										createVNode("p", { class: "text-[10px] font-bold text-on-surface-variant/60" }, toDisplayString(p === "basic" ? "Hasta 50 unidades" : p === "pro" ? "Hasta 200 unidades" : "Unidades ilimitadas"), 1)
									], 10, ["onClick"]);
								}), 64))])]), createVNode("div", { class: "grid grid-cols-1 md:grid-cols-2 gap-8 pt-4 border-t border-outline-variant/5" }, [createVNode(Input_default, {
									modelValue: unref(form).unidades_totales,
									"onUpdate:modelValue": ($event) => unref(form).unidades_totales = $event,
									type: "number",
									label: "Unidades Totales",
									placeholder: "0",
									icon: "apartment",
									required: "",
									error: unref(form).errors.unidades_totales
								}, null, 8, [
									"modelValue",
									"onUpdate:modelValue",
									"error"
								]), createVNode(Input_default, {
									modelValue: unref(form).torres,
									"onUpdate:modelValue": ($event) => unref(form).torres = $event,
									type: "number",
									label: "Número de Torres / Bloques",
									placeholder: "0",
									icon: "domain",
									required: "",
									error: unref(form).errors.torres
								}, null, 8, [
									"modelValue",
									"onUpdate:modelValue",
									"error"
								])])])]),
								_: 1
							})])) : (openBlock(), createBlock("div", { key: 3 }, [createVNode(Card_default, { class: "!p-8 md:!p-12" }, {
								default: withCtx(() => [createVNode("div", { class: "space-y-8" }, [
									createVNode("div", { class: "grid grid-cols-1 md:grid-cols-2 gap-10" }, [createVNode(Input_default, {
										modelValue: unref(form).name,
										"onUpdate:modelValue": ($event) => unref(form).name = $event,
										label: "Nombre del Administrador",
										placeholder: "Nombre completo",
										icon: "person",
										required: "",
										error: unref(form).errors.name
									}, null, 8, [
										"modelValue",
										"onUpdate:modelValue",
										"error"
									]), createVNode(Input_default, {
										modelValue: unref(form).email,
										"onUpdate:modelValue": ($event) => unref(form).email = $event,
										type: "email",
										label: "Correo Electrónico Corporativo",
										placeholder: "admin@conjunto.com",
										icon: "alternate_email",
										required: "",
										error: unref(form).errors.email
									}, null, 8, [
										"modelValue",
										"onUpdate:modelValue",
										"error"
									])]),
									createVNode("div", { class: "grid grid-cols-1 md:grid-cols-2 gap-10" }, [createVNode(Input_default, {
										modelValue: unref(form).password,
										"onUpdate:modelValue": ($event) => unref(form).password = $event,
										type: "password",
										label: "Contraseña de Acceso",
										placeholder: "••••••••",
										icon: "lock",
										required: "",
										error: unref(form).errors.password
									}, null, 8, [
										"modelValue",
										"onUpdate:modelValue",
										"error"
									]), createVNode(Input_default, {
										modelValue: unref(form).password_confirmation,
										"onUpdate:modelValue": ($event) => unref(form).password_confirmation = $event,
										type: "password",
										label: "Confirmar Contraseña",
										placeholder: "••••••••",
										icon: "verified_user",
										required: ""
									}, null, 8, ["modelValue", "onUpdate:modelValue"])]),
									createVNode("div", { class: "pt-4 border-t border-outline-variant/5" }, [createVNode(Checkbox_default, {
										modelValue: unref(form).terms,
										"onUpdate:modelValue": ($event) => unref(form).terms = $event,
										required: "",
										error: unref(form).errors.terms
									}, {
										description: withCtx(() => [
											createTextVNode(" Autorizo de forma previa, expresa e informada a NEXO-PRO para el tratamiento de mis datos personales según la "),
											createVNode("button", {
												type: "button",
												onClick: withModifiers(($event) => openLegalModal("privacy"), ["prevent", "stop"]),
												class: "text-primary font-bold hover:underline"
											}, "Política de Privacidad", 8, ["onClick"]),
											createTextVNode(" y la Ley 1581 de 2012, y acepto los "),
											createVNode("button", {
												type: "button",
												onClick: withModifiers(($event) => openLegalModal("terms"), ["prevent", "stop"]),
												class: "text-primary font-bold hover:underline"
											}, "Términos y Condiciones", 8, ["onClick"]),
											createTextVNode(". ")
										]),
										_: 1
									}, 8, [
										"modelValue",
										"onUpdate:modelValue",
										"error"
									])])
								])]),
								_: 1
							})]))]),
							_: 1
						}), createVNode("div", { class: "mt-12 flex flex-col sm:flex-row justify-between items-center gap-4 px-2" }, [step.value > 1 ? (openBlock(), createBlock(Button_default, {
							key: 0,
							variant: "ghost",
							icon: "arrow_back",
							onClick: prevStep
						}, {
							default: withCtx(() => [createTextVNode(" Volver ")]),
							_: 1
						})) : (openBlock(), createBlock("div", { key: 1 })), createVNode("div", { class: "flex gap-4 w-full sm:w-auto" }, [step.value < totalSteps ? (openBlock(), createBlock(Button_default, {
							key: 0,
							type: "button",
							variant: "primary",
							size: "lg",
							class: "w-full sm:w-auto !px-12 shadow-xl shadow-primary/20",
							icon: "arrow_forward",
							onClick: nextStep
						}, {
							default: withCtx(() => [createTextVNode(" Continuar ")]),
							_: 1
						})) : (openBlock(), createBlock(Button_default, {
							key: 1,
							type: "submit",
							variant: "secondary",
							size: "lg",
							class: "w-full sm:w-auto !px-12 shadow-xl shadow-secondary/20",
							icon: "how_to_reg",
							loading: unref(form).processing
						}, {
							default: withCtx(() => [createTextVNode(" Finalizar Registro ")]),
							_: 1
						}, 8, ["loading"]))])])], 32),
						createVNode("div", { class: "mt-12 text-center" }, [createVNode("p", { class: "text-[10px] text-on-surface-variant/50 font-bold uppercase tracking-widest" }, [createTextVNode(" ¿Ya gestionas con nosotros? "), createVNode(unref(Link), {
							href: _ctx.route("login"),
							class: "text-primary font-black ml-1 hover:underline underline-offset-4"
						}, {
							default: withCtx(() => [createTextVNode("Inicia Sesión")]),
							_: 1
						}, 8, ["href"])])])
					])];
				}),
				_: 1
			}, _parent));
			if (showLegalModal.value) _push(ssrRenderComponent(Modal_default, {
				onClose: ($event) => showLegalModal.value = false,
				maxWidth: "4xl"
			}, {
				default: withCtx((_, _push, _parent, _scopeId) => {
					if (_push) {
						_push(`<div class="px-6 py-6 md:px-8 md:py-8 border-b border-outline-variant/10 flex justify-between items-center sticky top-0 bg-surface/80 backdrop-blur-xl z-20" data-v-a44f3098${_scopeId}><h3 class="text-sm font-black text-on-surface uppercase tracking-widest" data-v-a44f3098${_scopeId}> Información Legal </h3><button class="p-2 rounded-xl text-on-surface-variant hover:bg-surface-container-high transition-colors" data-v-a44f3098${_scopeId}><span class="material-symbols-outlined text-lg" data-v-a44f3098${_scopeId}>close</span></button></div><div class="p-6 md:p-8 max-h-[60vh] overflow-y-auto custom-scrollbar bg-surface" data-v-a44f3098${_scopeId}>`);
						_push(ssrRenderComponent(LegalContent_default, { type: legalModalType.value }, null, _parent, _scopeId));
						_push(`</div><div class="px-6 py-4 md:px-8 bg-surface-container-low border-t border-outline-variant/10 flex justify-end gap-3" data-v-a44f3098${_scopeId}>`);
						_push(ssrRenderComponent(Button_default, {
							variant: "ghost",
							onClick: ($event) => showLegalModal.value = false
						}, {
							default: withCtx((_, _push, _parent, _scopeId) => {
								if (_push) _push(`Cerrar Visualización`);
								else return [createTextVNode("Cerrar Visualización")];
							}),
							_: 1
						}, _parent, _scopeId));
						_push(ssrRenderComponent(Button_default, {
							variant: "primary",
							onClick: acceptLegalModal,
							icon: "check_circle"
						}, {
							default: withCtx((_, _push, _parent, _scopeId) => {
								if (_push) _push(`He leído y Acepto`);
								else return [createTextVNode("He leído y Acepto")];
							}),
							_: 1
						}, _parent, _scopeId));
						_push(`</div>`);
					} else return [
						createVNode("div", { class: "px-6 py-6 md:px-8 md:py-8 border-b border-outline-variant/10 flex justify-between items-center sticky top-0 bg-surface/80 backdrop-blur-xl z-20" }, [createVNode("h3", { class: "text-sm font-black text-on-surface uppercase tracking-widest" }, " Información Legal "), createVNode("button", {
							onClick: ($event) => showLegalModal.value = false,
							class: "p-2 rounded-xl text-on-surface-variant hover:bg-surface-container-high transition-colors"
						}, [createVNode("span", { class: "material-symbols-outlined text-lg" }, "close")], 8, ["onClick"])]),
						createVNode("div", { class: "p-6 md:p-8 max-h-[60vh] overflow-y-auto custom-scrollbar bg-surface" }, [createVNode(LegalContent_default, { type: legalModalType.value }, null, 8, ["type"])]),
						createVNode("div", { class: "px-6 py-4 md:px-8 bg-surface-container-low border-t border-outline-variant/10 flex justify-end gap-3" }, [createVNode(Button_default, {
							variant: "ghost",
							onClick: ($event) => showLegalModal.value = false
						}, {
							default: withCtx(() => [createTextVNode("Cerrar Visualización")]),
							_: 1
						}, 8, ["onClick"]), createVNode(Button_default, {
							variant: "primary",
							onClick: acceptLegalModal,
							icon: "check_circle"
						}, {
							default: withCtx(() => [createTextVNode("He leído y Acepto")]),
							_: 1
						})])
					];
				}),
				_: 1
			}, _parent));
			else _push(`<!---->`);
			_push(`<!--]-->`);
		};
	}
});
//#endregion
//#region resources/js/Pages/Auth/Onboarding.vue
var _sfc_setup = Onboarding_vue_vue_type_script_setup_true_lang_default.setup;
Onboarding_vue_vue_type_script_setup_true_lang_default.setup = (props, ctx) => {
	const ssrContext = useSSRContext();
	(ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Auth/Onboarding.vue");
	return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
var Onboarding_default = /* @__PURE__ */ _plugin_vue_export_helper_default(Onboarding_vue_vue_type_script_setup_true_lang_default, [["__scopeId", "data-v-a44f3098"]]);
//#endregion
export { Onboarding_default as default };

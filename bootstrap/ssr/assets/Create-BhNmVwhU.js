import { t as AuthenticatedLayout_default } from "./AuthenticatedLayout-vxulH9w9.js";
import { t as Card_default } from "./Card-D4IwBRMd.js";
import { t as Button_default } from "./Button-C-byZbTR.js";
import { t as Select_default } from "./Select-DRXhACf5.js";
import { t as Input_default } from "./Input-CbVZZMpc.js";
import { t as Checkbox_default } from "./Checkbox-CM9pjPQy.js";
import { createBlock, createCommentVNode, createTextVNode, createVNode, defineComponent, openBlock, toDisplayString, unref, useSSRContext, vModelText, withCtx, withDirectives, withModifiers } from "vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import { ssrInterpolate, ssrRenderComponent } from "vue/server-renderer";
//#region resources/js/Pages/SuperAdmin/Legal/Create.vue?vue&type=script&setup=true&lang.ts
var Create_vue_vue_type_script_setup_true_lang_default = /* @__PURE__ */ defineComponent({
	layout: AuthenticatedLayout_default,
	__name: "Create",
	__ssrInlineRender: true,
	setup(__props) {
		const form = useForm({
			type: "terms",
			title: "",
			body: "",
			version: "1.0.0",
			activate: true
		});
		const submit = () => {
			form.post(route("superadmin.legal.store"));
		};
		const types = [
			{
				value: "terms",
				label: "Términos y Condiciones"
			},
			{
				value: "privacy",
				label: "Política de Privacidad"
			},
			{
				value: "cookies",
				label: "Política de Cookies"
			}
		];
		return (_ctx, _push, _parent, _attrs) => {
			_push(`<!--[-->`);
			_push(ssrRenderComponent(unref(Head), { title: "Nuevo Documento Legal" }, null, _parent));
			_push(`<div class="max-w-5xl mx-auto p-4 md:p-8 space-y-6"><div class="flex items-center gap-4">`);
			_push(ssrRenderComponent(unref(Link), { href: _ctx.route("superadmin.legal.index") }, {
				default: withCtx((_, _push, _parent, _scopeId) => {
					if (_push) _push(ssrRenderComponent(Button_default, {
						variant: "secondary",
						icon: "arrow_back",
						size: "sm",
						class: "!rounded-full"
					}, null, _parent, _scopeId));
					else return [createVNode(Button_default, {
						variant: "secondary",
						icon: "arrow_back",
						size: "sm",
						class: "!rounded-full"
					})];
				}),
				_: 1
			}, _parent));
			_push(`<div><h1 class="text-2xl font-bold text-slate-900 dark:text-white font-display">Nuevo Documento Legal</h1><p class="text-xs text-slate-500 uppercase tracking-widest font-bold">Configuración de Cumplimiento Normativo</p></div></div>`);
			_push(ssrRenderComponent(Card_default, {
				variant: "flat",
				class: "p-6 md:p-10 border-slate-200 dark:border-slate-700"
			}, {
				default: withCtx((_, _push, _parent, _scopeId) => {
					if (_push) {
						_push(`<form class="space-y-8"${_scopeId}><div class="grid grid-cols-1 md:grid-cols-2 gap-8"${_scopeId}>`);
						_push(ssrRenderComponent(Select_default, {
							modelValue: unref(form).type,
							"onUpdate:modelValue": ($event) => unref(form).type = $event,
							options: types,
							label: "Tipo de Documento",
							error: unref(form).errors.type,
							icon: "gavel"
						}, null, _parent, _scopeId));
						_push(ssrRenderComponent(Input_default, {
							modelValue: unref(form).version,
							"onUpdate:modelValue": ($event) => unref(form).version = $event,
							label: "Versión del Documento",
							placeholder: "Ej: 1.0.0",
							error: unref(form).errors.version,
							icon: "schema"
						}, null, _parent, _scopeId));
						_push(`</div>`);
						_push(ssrRenderComponent(Input_default, {
							modelValue: unref(form).title,
							"onUpdate:modelValue": ($event) => unref(form).title = $event,
							label: "Título Público",
							placeholder: "Ej: Términos y Condiciones Generales 2026",
							error: unref(form).errors.title,
							icon: "title"
						}, null, _parent, _scopeId));
						_push(`<div class="space-y-2"${_scopeId}><label class="text-[10px] font-black text-slate-500 uppercase tracking-widest pl-1"${_scopeId}> Cuerpo del Documento (Contenido Legal) </label><textarea class="w-full min-h-[400px] rounded-2xl border-2 border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900 focus:ring-4 focus:ring-brand-500/10 focus:border-brand-500 transition-all p-6 text-slate-700 dark:text-slate-200 font-sans leading-relaxed" placeholder="Escribe aquí el texto legal completo..."${_scopeId}>${ssrInterpolate(unref(form).body)}</textarea>`);
						if (unref(form).errors.body) _push(`<p class="text-[10px] font-bold text-danger uppercase tracking-widest pl-1"${_scopeId}>${ssrInterpolate(unref(form).errors.body)}</p>`);
						else _push(`<!---->`);
						_push(`</div><div class="flex flex-col md:flex-row md:items-center justify-between gap-6 pt-6 border-t border-slate-100 dark:border-slate-800"${_scopeId}><div class="flex items-center gap-2"${_scopeId}>`);
						_push(ssrRenderComponent(Checkbox_default, {
							modelValue: unref(form).activate,
							"onUpdate:modelValue": ($event) => unref(form).activate = $event,
							id: "activate-checkbox"
						}, null, _parent, _scopeId));
						_push(`<label for="activate-checkbox" class="text-sm font-medium text-slate-600 dark:text-slate-400 cursor-pointer"${_scopeId}> Activar inmediatamente (esta versión reemplazará a la anterior de forma global) </label></div>`);
						_push(ssrRenderComponent(Button_default, {
							type: "submit",
							variant: "primary",
							loading: unref(form).processing,
							class: "w-full md:w-auto",
							icon: "save"
						}, {
							default: withCtx((_, _push, _parent, _scopeId) => {
								if (_push) _push(` Publicar Documento `);
								else return [createTextVNode(" Publicar Documento ")];
							}),
							_: 1
						}, _parent, _scopeId));
						_push(`</div></form>`);
					} else return [createVNode("form", {
						onSubmit: withModifiers(submit, ["prevent"]),
						class: "space-y-8"
					}, [
						createVNode("div", { class: "grid grid-cols-1 md:grid-cols-2 gap-8" }, [createVNode(Select_default, {
							modelValue: unref(form).type,
							"onUpdate:modelValue": ($event) => unref(form).type = $event,
							options: types,
							label: "Tipo de Documento",
							error: unref(form).errors.type,
							icon: "gavel"
						}, null, 8, [
							"modelValue",
							"onUpdate:modelValue",
							"error"
						]), createVNode(Input_default, {
							modelValue: unref(form).version,
							"onUpdate:modelValue": ($event) => unref(form).version = $event,
							label: "Versión del Documento",
							placeholder: "Ej: 1.0.0",
							error: unref(form).errors.version,
							icon: "schema"
						}, null, 8, [
							"modelValue",
							"onUpdate:modelValue",
							"error"
						])]),
						createVNode(Input_default, {
							modelValue: unref(form).title,
							"onUpdate:modelValue": ($event) => unref(form).title = $event,
							label: "Título Público",
							placeholder: "Ej: Términos y Condiciones Generales 2026",
							error: unref(form).errors.title,
							icon: "title"
						}, null, 8, [
							"modelValue",
							"onUpdate:modelValue",
							"error"
						]),
						createVNode("div", { class: "space-y-2" }, [
							createVNode("label", { class: "text-[10px] font-black text-slate-500 uppercase tracking-widest pl-1" }, " Cuerpo del Documento (Contenido Legal) "),
							withDirectives(createVNode("textarea", {
								"onUpdate:modelValue": ($event) => unref(form).body = $event,
								class: "w-full min-h-[400px] rounded-2xl border-2 border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900 focus:ring-4 focus:ring-brand-500/10 focus:border-brand-500 transition-all p-6 text-slate-700 dark:text-slate-200 font-sans leading-relaxed",
								placeholder: "Escribe aquí el texto legal completo..."
							}, null, 8, ["onUpdate:modelValue"]), [[vModelText, unref(form).body]]),
							unref(form).errors.body ? (openBlock(), createBlock("p", {
								key: 0,
								class: "text-[10px] font-bold text-danger uppercase tracking-widest pl-1"
							}, toDisplayString(unref(form).errors.body), 1)) : createCommentVNode("", true)
						]),
						createVNode("div", { class: "flex flex-col md:flex-row md:items-center justify-between gap-6 pt-6 border-t border-slate-100 dark:border-slate-800" }, [createVNode("div", { class: "flex items-center gap-2" }, [createVNode(Checkbox_default, {
							modelValue: unref(form).activate,
							"onUpdate:modelValue": ($event) => unref(form).activate = $event,
							id: "activate-checkbox"
						}, null, 8, ["modelValue", "onUpdate:modelValue"]), createVNode("label", {
							for: "activate-checkbox",
							class: "text-sm font-medium text-slate-600 dark:text-slate-400 cursor-pointer"
						}, " Activar inmediatamente (esta versión reemplazará a la anterior de forma global) ")]), createVNode(Button_default, {
							type: "submit",
							variant: "primary",
							loading: unref(form).processing,
							class: "w-full md:w-auto",
							icon: "save"
						}, {
							default: withCtx(() => [createTextVNode(" Publicar Documento ")]),
							_: 1
						}, 8, ["loading"])])
					], 32)];
				}),
				_: 1
			}, _parent));
			_push(`</div><!--]-->`);
		};
	}
});
//#endregion
//#region resources/js/Pages/SuperAdmin/Legal/Create.vue
var _sfc_setup = Create_vue_vue_type_script_setup_true_lang_default.setup;
Create_vue_vue_type_script_setup_true_lang_default.setup = (props, ctx) => {
	const ssrContext = useSSRContext();
	(ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/SuperAdmin/Legal/Create.vue");
	return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
var Create_default = Create_vue_vue_type_script_setup_true_lang_default;
//#endregion
export { Create_default as default };

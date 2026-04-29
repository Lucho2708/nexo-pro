import { t as Logo_default } from "./Logo-D89dXaWr.js";
import { t as GuestLayout_default } from "./GuestLayout-BiN2s9qc.js";
import { createTextVNode, createVNode, defineComponent, unref, useSSRContext, withCtx } from "vue";
import { Head, Link } from "@inertiajs/vue3";
import { ssrRenderComponent } from "vue/server-renderer";
//#region resources/js/Pages/Legal/Privacy.vue?vue&type=script&setup=true&lang.ts
var Privacy_vue_vue_type_script_setup_true_lang_default = /* @__PURE__ */ defineComponent({
	__name: "Privacy",
	__ssrInlineRender: true,
	setup(__props) {
		return (_ctx, _push, _parent, _attrs) => {
			_push(`<!--[-->`);
			_push(ssrRenderComponent(unref(Head), { title: "Política de Privacidad — NEXO-PRO" }, null, _parent));
			_push(ssrRenderComponent(GuestLayout_default, null, {
				default: withCtx((_, _push, _parent, _scopeId) => {
					if (_push) {
						_push(`<div class="max-w-3xl mx-auto w-full px-6 py-20 min-h-screen"${_scopeId}><div class="mb-12 text-center"${_scopeId}>`);
						_push(ssrRenderComponent(unref(Link), {
							href: "/",
							class: "inline-block mb-8"
						}, {
							default: withCtx((_, _push, _parent, _scopeId) => {
								if (_push) _push(ssrRenderComponent(Logo_default, {
									width: "60px",
									height: "60px",
									"show-text": false
								}, null, _parent, _scopeId));
								else return [createVNode(Logo_default, {
									width: "60px",
									height: "60px",
									"show-text": false
								})];
							}),
							_: 1
						}, _parent, _scopeId));
						_push(`<h1 class="text-4xl font-black text-primary tracking-tighter uppercase leading-none mb-4"${_scopeId}>Política de Tratamiento de Datos</h1><p class="text-xs text-on-surface-variant/60 font-bold uppercase tracking-[0.2em]"${_scopeId}>Última actualización: Abril 2026</p></div><div class="prose prose-sm prose-primary prose-headings:font-black prose-headings:uppercase prose-p:text-on-surface-variant prose-a:text-primary max-w-none bg-surface-container-low p-8 md:p-12 rounded-3xl border border-outline-variant/10 shadow-xl"${_scopeId}><h3${_scopeId}>1. Introducción y Marco Legal</h3><p${_scopeId}> De conformidad con la Ley 1581 de 2012 y sus decretos reglamentarios (Colombia), la presente Política de Tratamiento de Datos establece las finalidades, medidas y procedimientos mediante los cuales NEXO-PRO captura, almacena y procesa información personal. </p><h3${_scopeId}>2. Responsable del Tratamiento</h3><p${_scopeId}> [AQUÍ DEBES INGRESAR LA RAZÓN SOCIAL DE LA EMPRESA, NIT, DIRECCIÓN, CORREO ELECTRÓNICO Y TELÉFONO DEL OFICIAL DE PRIVACIDAD] </p><h3${_scopeId}>3. Finalidad de la Recolección</h3><p${_scopeId}> Los datos personales proporcionados serán utilizados exclusivamente para la gestión de acceso a la plataforma Multi-tenant, facturación automatizada, notificaciones de novedades y seguridad transaccional, asegurando un uso estrictamente vinculado al servicio tecnológico prestado. </p><h3${_scopeId}>4. Derechos de los Titulares</h3><p${_scopeId}> Todo titular de la información tiene derecho a conocer, actualizar, rectificar y suprimir sus datos personales (Habeas Data). Este derecho se podrá ejercer a través del correo [CORREO_SOPORTE]. </p><h3${_scopeId}>5. Uso de Cookies</h3><p${_scopeId}> Cumpliendo con la guía de la Superintendencia de Industria y Comercio (SIC), utilizamos cookies técnicas absolutamente necesarias. Las analíticas solo se instalan previo consentimiento expreso en nuestro portal. </p><hr class="my-8 border-outline-variant/20"${_scopeId}><p class="text-xs italic text-center"${_scopeId}> **AVISO:** Este es un documento plantilla generado para estructura base. Debe ser reemplazado por los lineamientos de tu abogado o equipo legal corporativo. </p></div><div class="mt-12 text-center"${_scopeId}>`);
						_push(ssrRenderComponent(unref(Link), {
							href: "/",
							class: "text-xs text-primary font-black uppercase tracking-widest hover:underline underline-offset-4"
						}, {
							default: withCtx((_, _push, _parent, _scopeId) => {
								if (_push) _push(` ← Volver al inicio `);
								else return [createTextVNode(" ← Volver al inicio ")];
							}),
							_: 1
						}, _parent, _scopeId));
						_push(`</div></div>`);
					} else return [createVNode("div", { class: "max-w-3xl mx-auto w-full px-6 py-20 min-h-screen" }, [
						createVNode("div", { class: "mb-12 text-center" }, [
							createVNode(unref(Link), {
								href: "/",
								class: "inline-block mb-8"
							}, {
								default: withCtx(() => [createVNode(Logo_default, {
									width: "60px",
									height: "60px",
									"show-text": false
								})]),
								_: 1
							}),
							createVNode("h1", { class: "text-4xl font-black text-primary tracking-tighter uppercase leading-none mb-4" }, "Política de Tratamiento de Datos"),
							createVNode("p", { class: "text-xs text-on-surface-variant/60 font-bold uppercase tracking-[0.2em]" }, "Última actualización: Abril 2026")
						]),
						createVNode("div", { class: "prose prose-sm prose-primary prose-headings:font-black prose-headings:uppercase prose-p:text-on-surface-variant prose-a:text-primary max-w-none bg-surface-container-low p-8 md:p-12 rounded-3xl border border-outline-variant/10 shadow-xl" }, [
							createVNode("h3", null, "1. Introducción y Marco Legal"),
							createVNode("p", null, " De conformidad con la Ley 1581 de 2012 y sus decretos reglamentarios (Colombia), la presente Política de Tratamiento de Datos establece las finalidades, medidas y procedimientos mediante los cuales NEXO-PRO captura, almacena y procesa información personal. "),
							createVNode("h3", null, "2. Responsable del Tratamiento"),
							createVNode("p", null, " [AQUÍ DEBES INGRESAR LA RAZÓN SOCIAL DE LA EMPRESA, NIT, DIRECCIÓN, CORREO ELECTRÓNICO Y TELÉFONO DEL OFICIAL DE PRIVACIDAD] "),
							createVNode("h3", null, "3. Finalidad de la Recolección"),
							createVNode("p", null, " Los datos personales proporcionados serán utilizados exclusivamente para la gestión de acceso a la plataforma Multi-tenant, facturación automatizada, notificaciones de novedades y seguridad transaccional, asegurando un uso estrictamente vinculado al servicio tecnológico prestado. "),
							createVNode("h3", null, "4. Derechos de los Titulares"),
							createVNode("p", null, " Todo titular de la información tiene derecho a conocer, actualizar, rectificar y suprimir sus datos personales (Habeas Data). Este derecho se podrá ejercer a través del correo [CORREO_SOPORTE]. "),
							createVNode("h3", null, "5. Uso de Cookies"),
							createVNode("p", null, " Cumpliendo con la guía de la Superintendencia de Industria y Comercio (SIC), utilizamos cookies técnicas absolutamente necesarias. Las analíticas solo se instalan previo consentimiento expreso en nuestro portal. "),
							createVNode("hr", { class: "my-8 border-outline-variant/20" }),
							createVNode("p", { class: "text-xs italic text-center" }, " **AVISO:** Este es un documento plantilla generado para estructura base. Debe ser reemplazado por los lineamientos de tu abogado o equipo legal corporativo. ")
						]),
						createVNode("div", { class: "mt-12 text-center" }, [createVNode(unref(Link), {
							href: "/",
							class: "text-xs text-primary font-black uppercase tracking-widest hover:underline underline-offset-4"
						}, {
							default: withCtx(() => [createTextVNode(" ← Volver al inicio ")]),
							_: 1
						})])
					])];
				}),
				_: 1
			}, _parent));
			_push(`<!--]-->`);
		};
	}
});
//#endregion
//#region resources/js/Pages/Legal/Privacy.vue
var _sfc_setup = Privacy_vue_vue_type_script_setup_true_lang_default.setup;
Privacy_vue_vue_type_script_setup_true_lang_default.setup = (props, ctx) => {
	const ssrContext = useSSRContext();
	(ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Legal/Privacy.vue");
	return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
var Privacy_default = Privacy_vue_vue_type_script_setup_true_lang_default;
//#endregion
export { Privacy_default as default };

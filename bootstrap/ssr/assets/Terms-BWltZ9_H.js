import { t as Logo_default } from "./Logo-BJuTBUmx.js";
import { t as GuestLayout_default } from "./GuestLayout-CdsdxXMK.js";
import { createTextVNode, createVNode, defineComponent, unref, useSSRContext, withCtx } from "vue";
import { Head, Link } from "@inertiajs/vue3";
import { ssrRenderComponent } from "vue/server-renderer";
//#region resources/js/Pages/Legal/Terms.vue?vue&type=script&setup=true&lang.ts
var Terms_vue_vue_type_script_setup_true_lang_default = /* @__PURE__ */ defineComponent({
	__name: "Terms",
	__ssrInlineRender: true,
	setup(__props) {
		return (_ctx, _push, _parent, _attrs) => {
			_push(`<!--[-->`);
			_push(ssrRenderComponent(unref(Head), { title: "Términos y Condiciones — NEXO-PRO" }, null, _parent));
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
						_push(`<h1 class="text-4xl font-black text-primary tracking-tighter uppercase leading-none mb-4"${_scopeId}>Términos y Condiciones</h1><p class="text-xs text-on-surface-variant/60 font-bold uppercase tracking-[0.2em]"${_scopeId}>Última actualización: Abril 2026</p></div><div class="prose prose-sm prose-primary prose-headings:font-black prose-headings:uppercase prose-p:text-on-surface-variant prose-a:text-primary max-w-none bg-surface-container-low p-8 md:p-12 rounded-3xl border border-outline-variant/10 shadow-xl"${_scopeId}><h3${_scopeId}>1. Aceptación de los Términos</h3><p${_scopeId}> Al acceder y utilizar la plataforma NEXO-PRO, usted acepta sujetarse a estos Términos y Condiciones Generales de Uso. Si usted no está de acuerdo con el contenido legal, por favor absténgase de usar el servicio. </p><h3${_scopeId}>2. Licencia de Uso</h3><p${_scopeId}> La plataforma se otorga bajo un esquema SaaS (Software as a Service). La suscripción pagada otorga a la Copropiedad el derecho intransferible, no exclusivo e internacional para usar el software mediante los navegadores web. </p><h3${_scopeId}>3. Responsabilidad del Usuario</h3><p${_scopeId}> El administrador de la copropiedad es el único responsable de la precisión, legalidad y veracidad de toda la información (incluidos los PQRS y los datos de cartera) cargados en la base de datos de manera voluntaria. </p><h3${_scopeId}>4. Nivel de Servicio (SLA)</h3><p${_scopeId}> Ofrecemos un Uptime garantizado del 99.9%. El mantenimiento preventivo se realizará, en la medida de lo posible, fuera de horarios de carga y bajo anuncio generalizado. </p><h3${_scopeId}>5. Seguridad y Auditoría</h3><p${_scopeId}> Con el fin de garantizar la integridad de los datos, la trazabilidad de las operaciones comerciales y la seguridad técnica ante incidentes, NEXO-PRO realiza un registro automático (Log de Auditoría) de las interacciones críticas. Este registro incluye metadatos técnicos de navegación pero omite estrictamente cualquier dato sensible o privado, cumpliendo con los estándares de Habeas Data y protección de la privacidad. </p><hr class="my-8 border-outline-variant/20"${_scopeId}><p class="text-[10px] uppercase font-bold text-on-surface-variant/40 tracking-widest text-center leading-relaxed"${_scopeId}> Al usar este sistema, usted reconoce que sus interacciones técnicas son registradas para fines de soporte y auditoría forense. </p></div><div class="mt-12 text-center"${_scopeId}>`);
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
							createVNode("h1", { class: "text-4xl font-black text-primary tracking-tighter uppercase leading-none mb-4" }, "Términos y Condiciones"),
							createVNode("p", { class: "text-xs text-on-surface-variant/60 font-bold uppercase tracking-[0.2em]" }, "Última actualización: Abril 2026")
						]),
						createVNode("div", { class: "prose prose-sm prose-primary prose-headings:font-black prose-headings:uppercase prose-p:text-on-surface-variant prose-a:text-primary max-w-none bg-surface-container-low p-8 md:p-12 rounded-3xl border border-outline-variant/10 shadow-xl" }, [
							createVNode("h3", null, "1. Aceptación de los Términos"),
							createVNode("p", null, " Al acceder y utilizar la plataforma NEXO-PRO, usted acepta sujetarse a estos Términos y Condiciones Generales de Uso. Si usted no está de acuerdo con el contenido legal, por favor absténgase de usar el servicio. "),
							createVNode("h3", null, "2. Licencia de Uso"),
							createVNode("p", null, " La plataforma se otorga bajo un esquema SaaS (Software as a Service). La suscripción pagada otorga a la Copropiedad el derecho intransferible, no exclusivo e internacional para usar el software mediante los navegadores web. "),
							createVNode("h3", null, "3. Responsabilidad del Usuario"),
							createVNode("p", null, " El administrador de la copropiedad es el único responsable de la precisión, legalidad y veracidad de toda la información (incluidos los PQRS y los datos de cartera) cargados en la base de datos de manera voluntaria. "),
							createVNode("h3", null, "4. Nivel de Servicio (SLA)"),
							createVNode("p", null, " Ofrecemos un Uptime garantizado del 99.9%. El mantenimiento preventivo se realizará, en la medida de lo posible, fuera de horarios de carga y bajo anuncio generalizado. "),
							createVNode("h3", null, "5. Seguridad y Auditoría"),
							createVNode("p", null, " Con el fin de garantizar la integridad de los datos, la trazabilidad de las operaciones comerciales y la seguridad técnica ante incidentes, NEXO-PRO realiza un registro automático (Log de Auditoría) de las interacciones críticas. Este registro incluye metadatos técnicos de navegación pero omite estrictamente cualquier dato sensible o privado, cumpliendo con los estándares de Habeas Data y protección de la privacidad. "),
							createVNode("hr", { class: "my-8 border-outline-variant/20" }),
							createVNode("p", { class: "text-[10px] uppercase font-bold text-on-surface-variant/40 tracking-widest text-center leading-relaxed" }, " Al usar este sistema, usted reconoce que sus interacciones técnicas son registradas para fines de soporte y auditoría forense. ")
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
//#region resources/js/Pages/Legal/Terms.vue
var _sfc_setup = Terms_vue_vue_type_script_setup_true_lang_default.setup;
Terms_vue_vue_type_script_setup_true_lang_default.setup = (props, ctx) => {
	const ssrContext = useSSRContext();
	(ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Legal/Terms.vue");
	return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
var Terms_default = Terms_vue_vue_type_script_setup_true_lang_default;
//#endregion
export { Terms_default as default };

import { t as useToast } from "./useToast-E_Hdzmc5.js";
import { t as AuthenticatedLayout_default } from "./AuthenticatedLayout-vxulH9w9.js";
import { t as Card_default } from "./Card-D4IwBRMd.js";
import { t as Button_default } from "./Button-C-byZbTR.js";
import { t as Badge_default } from "./Badge-DsYztQFV.js";
import { Fragment, createBlock, createCommentVNode, createTextVNode, createVNode, defineComponent, openBlock, renderList, toDisplayString, unref, useSSRContext, withCtx } from "vue";
import { Head, Link, router } from "@inertiajs/vue3";
import { ssrInterpolate, ssrRenderComponent, ssrRenderList } from "vue/server-renderer";
//#region resources/js/Pages/SuperAdmin/Legal/Index.vue?vue&type=script&setup=true&lang.ts
var Index_vue_vue_type_script_setup_true_lang_default = /* @__PURE__ */ defineComponent({
	layout: AuthenticatedLayout_default,
	__name: "Index",
	__ssrInlineRender: true,
	props: { documents: {} },
	setup(__props) {
		const toast = useToast();
		const toggleStatus = (id) => {
			router.patch(route("superadmin.legal.toggle", id), {}, {
				preserveScroll: true,
				onSuccess: () => toast.success("Estado actualizado correctamente")
			});
		};
		const getBadgeColor = (type) => {
			switch (type) {
				case "terms": return "info";
				case "privacy": return "success";
				case "cookies": return "warning";
				default: return "info";
			}
		};
		const getTypeName = (type) => {
			switch (type) {
				case "terms": return "Términos y Condiciones";
				case "privacy": return "Política de Privacidad";
				case "cookies": return "Política de Cookies";
				default: return type;
			}
		};
		return (_ctx, _push, _parent, _attrs) => {
			_push(`<!--[-->`);
			_push(ssrRenderComponent(unref(Head), { title: "Gestión Legal" }, null, _parent));
			_push(`<div class="space-y-6 max-w-7xl mx-auto p-4 md:p-8"><div class="flex flex-col md:flex-row md:items-center justify-between gap-4"><div><h1 class="text-2xl font-bold text-slate-900 dark:text-white">Gestión Legal y Habeas Data</h1><p class="text-slate-500 dark:text-slate-400">Administra los documentos legales y el cumplimiento normativo de la plataforma.</p></div>`);
			_push(ssrRenderComponent(unref(Link), { href: _ctx.route("superadmin.legal.create") }, {
				default: withCtx((_, _push, _parent, _scopeId) => {
					if (_push) _push(ssrRenderComponent(Button_default, {
						variant: "primary",
						icon: "add"
					}, {
						default: withCtx((_, _push, _parent, _scopeId) => {
							if (_push) _push(` Nuevo Documento `);
							else return [createTextVNode(" Nuevo Documento ")];
						}),
						_: 1
					}, _parent, _scopeId));
					else return [createVNode(Button_default, {
						variant: "primary",
						icon: "add"
					}, {
						default: withCtx(() => [createTextVNode(" Nuevo Documento ")]),
						_: 1
					})];
				}),
				_: 1
			}, _parent));
			_push(`</div>`);
			_push(ssrRenderComponent(Card_default, {
				variant: "flat",
				class: "overflow-hidden border-slate-200 dark:border-slate-700"
			}, {
				default: withCtx((_, _push, _parent, _scopeId) => {
					if (_push) {
						_push(`<div class="overflow-x-auto"${_scopeId}><table class="w-full text-left border-collapse"${_scopeId}><thead class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-slate-700"${_scopeId}><tr${_scopeId}><th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider"${_scopeId}>Documento</th><th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider"${_scopeId}>Versión</th><th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider"${_scopeId}>Estado</th><th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider"${_scopeId}>Fecha</th><th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider text-right"${_scopeId}>Acciones</th></tr></thead><tbody class="divide-y divide-slate-200 dark:divide-slate-700"${_scopeId}><!--[-->`);
						ssrRenderList(__props.documents, (doc) => {
							_push(`<tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors"${_scopeId}><td class="px-6 py-4"${_scopeId}><div class="flex flex-col"${_scopeId}><span class="font-medium text-slate-900 dark:text-white"${_scopeId}>${ssrInterpolate(doc.title)}</span><div class="flex gap-2 mt-1"${_scopeId}>`);
							_push(ssrRenderComponent(Badge_default, {
								variant: getBadgeColor(doc.type),
								size: "sm",
								class: "uppercase text-[10px]"
							}, {
								default: withCtx((_, _push, _parent, _scopeId) => {
									if (_push) _push(`${ssrInterpolate(getTypeName(doc.type))}`);
									else return [createTextVNode(toDisplayString(getTypeName(doc.type)), 1)];
								}),
								_: 2
							}, _parent, _scopeId));
							_push(`</div></div></td><td class="px-6 py-4"${_scopeId}><span class="font-mono text-sm text-slate-600 dark:text-slate-400"${_scopeId}>v${ssrInterpolate(doc.version)}</span></td><td class="px-6 py-4"${_scopeId}>`);
							_push(ssrRenderComponent(Badge_default, {
								variant: doc.is_active ? "success" : "danger",
								size: "sm"
							}, {
								default: withCtx((_, _push, _parent, _scopeId) => {
									if (_push) _push(`${ssrInterpolate(doc.is_active ? "Activo" : "Inactivo")}`);
									else return [createTextVNode(toDisplayString(doc.is_active ? "Activo" : "Inactivo"), 1)];
								}),
								_: 2
							}, _parent, _scopeId));
							_push(`</td><td class="px-6 py-4 text-sm text-slate-500 dark:text-slate-400"${_scopeId}>${ssrInterpolate(new Date(doc.created_at).toLocaleDateString())}</td><td class="px-6 py-4 text-right"${_scopeId}>`);
							_push(ssrRenderComponent(Button_default, {
								onClick: ($event) => toggleStatus(doc.id),
								variant: doc.is_active ? "secondary" : "primary",
								size: "sm",
								class: "!px-3"
							}, {
								default: withCtx((_, _push, _parent, _scopeId) => {
									if (_push) _push(`${ssrInterpolate(doc.is_active ? "Desactivar" : "Activar")}`);
									else return [createTextVNode(toDisplayString(doc.is_active ? "Desactivar" : "Activar"), 1)];
								}),
								_: 2
							}, _parent, _scopeId));
							_push(`</td></tr>`);
						});
						_push(`<!--]-->`);
						if (__props.documents.length === 0) _push(`<tr${_scopeId}><td colspan="5" class="px-6 py-12 text-center text-slate-500 dark:text-slate-400"${_scopeId}> No hay documentos legales registrados. Comienza creando uno nuevo. </td></tr>`);
						else _push(`<!---->`);
						_push(`</tbody></table></div>`);
					} else return [createVNode("div", { class: "overflow-x-auto" }, [createVNode("table", { class: "w-full text-left border-collapse" }, [createVNode("thead", { class: "bg-slate-50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-slate-700" }, [createVNode("tr", null, [
						createVNode("th", { class: "px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider" }, "Documento"),
						createVNode("th", { class: "px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider" }, "Versión"),
						createVNode("th", { class: "px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider" }, "Estado"),
						createVNode("th", { class: "px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider" }, "Fecha"),
						createVNode("th", { class: "px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider text-right" }, "Acciones")
					])]), createVNode("tbody", { class: "divide-y divide-slate-200 dark:divide-slate-700" }, [(openBlock(true), createBlock(Fragment, null, renderList(__props.documents, (doc) => {
						return openBlock(), createBlock("tr", {
							key: doc.id,
							class: "hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors"
						}, [
							createVNode("td", { class: "px-6 py-4" }, [createVNode("div", { class: "flex flex-col" }, [createVNode("span", { class: "font-medium text-slate-900 dark:text-white" }, toDisplayString(doc.title), 1), createVNode("div", { class: "flex gap-2 mt-1" }, [createVNode(Badge_default, {
								variant: getBadgeColor(doc.type),
								size: "sm",
								class: "uppercase text-[10px]"
							}, {
								default: withCtx(() => [createTextVNode(toDisplayString(getTypeName(doc.type)), 1)]),
								_: 2
							}, 1032, ["variant"])])])]),
							createVNode("td", { class: "px-6 py-4" }, [createVNode("span", { class: "font-mono text-sm text-slate-600 dark:text-slate-400" }, "v" + toDisplayString(doc.version), 1)]),
							createVNode("td", { class: "px-6 py-4" }, [createVNode(Badge_default, {
								variant: doc.is_active ? "success" : "danger",
								size: "sm"
							}, {
								default: withCtx(() => [createTextVNode(toDisplayString(doc.is_active ? "Activo" : "Inactivo"), 1)]),
								_: 2
							}, 1032, ["variant"])]),
							createVNode("td", { class: "px-6 py-4 text-sm text-slate-500 dark:text-slate-400" }, toDisplayString(new Date(doc.created_at).toLocaleDateString()), 1),
							createVNode("td", { class: "px-6 py-4 text-right" }, [createVNode(Button_default, {
								onClick: ($event) => toggleStatus(doc.id),
								variant: doc.is_active ? "secondary" : "primary",
								size: "sm",
								class: "!px-3"
							}, {
								default: withCtx(() => [createTextVNode(toDisplayString(doc.is_active ? "Desactivar" : "Activar"), 1)]),
								_: 2
							}, 1032, ["onClick", "variant"])])
						]);
					}), 128)), __props.documents.length === 0 ? (openBlock(), createBlock("tr", { key: 0 }, [createVNode("td", {
						colspan: "5",
						class: "px-6 py-12 text-center text-slate-500 dark:text-slate-400"
					}, " No hay documentos legales registrados. Comienza creando uno nuevo. ")])) : createCommentVNode("", true)])])])];
				}),
				_: 1
			}, _parent));
			_push(`</div><!--]-->`);
		};
	}
});
//#endregion
//#region resources/js/Pages/SuperAdmin/Legal/Index.vue
var _sfc_setup = Index_vue_vue_type_script_setup_true_lang_default.setup;
Index_vue_vue_type_script_setup_true_lang_default.setup = (props, ctx) => {
	const ssrContext = useSSRContext();
	(ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/SuperAdmin/Legal/Index.vue");
	return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
var Index_default = Index_vue_vue_type_script_setup_true_lang_default;
//#endregion
export { Index_default as default };

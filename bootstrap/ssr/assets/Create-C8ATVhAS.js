import { t as AuthenticatedLayout_default } from "./AuthenticatedLayout-vxulH9w9.js";
import { defineComponent, unref, useSSRContext } from "vue";
import { Head, useForm } from "@inertiajs/vue3";
import { ssrIncludeBooleanAttr, ssrInterpolate, ssrLooseContain, ssrLooseEqual, ssrRenderAttr, ssrRenderComponent } from "vue/server-renderer";
//#region resources/js/Pages/Admin/Copropiedades/Create.vue?vue&type=script&setup=true&lang.ts
var Create_vue_vue_type_script_setup_true_lang_default = /* @__PURE__ */ defineComponent({
	layout: AuthenticatedLayout_default,
	__name: "Create",
	__ssrInlineRender: true,
	setup(__props) {
		const form = useForm({
			nit: "",
			nombre: "",
			direccion: "",
			ciudad: "",
			plan: "pro",
			unidades_totales: 0,
			torres: 0
		});
		return (_ctx, _push, _parent, _attrs) => {
			_push(`<!--[-->`);
			_push(ssrRenderComponent(unref(Head), { title: "Añadir Nuevo Conjunto" }, null, _parent));
			_push(`<div class="max-w-4xl mx-auto"><div class="mb-8"><h1 class="text-3xl font-black text-primary tracking-tight">AÑADIR NUEVO CONJUNTO</h1><p class="text-on-surface-variant font-medium mt-1">Expande tu gestión registrando una nueva copropiedad en PH360.</p></div><form class="grid grid-cols-1 md:grid-cols-2 gap-8"><div class="bg-surface-container-lowest p-8 rounded-3xl border border-outline-variant/10 shadow-sm space-y-6"><div class="flex items-center gap-3 mb-2"><span class="material-symbols-outlined text-primary p-2 bg-primary-container rounded-xl">corporate_fare</span><h2 class="text-lg font-bold">Información General</h2></div><div class="space-y-4"><div class="space-y-1.5"><label for="nombre" class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Nombre del Conjunto / Edificio</label><input id="nombre"${ssrRenderAttr("value", unref(form).nombre)} type="text" class="w-full bg-surface-container-low border-outline-variant/20 rounded-2xl p-4 text-sm focus:ring-2 focus:ring-primary/20 transition-all" placeholder="Ej: Residencial Las Palmeras" required>`);
			if (unref(form).errors.nombre) _push(`<div class="text-error text-[10px] font-bold uppercase mt-1">${ssrInterpolate(unref(form).errors.nombre)}</div>`);
			else _push(`<!---->`);
			_push(`</div><div class="space-y-1.5"><label for="nit" class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">NIT</label><input id="nit"${ssrRenderAttr("value", unref(form).nit)} type="text" class="w-full bg-surface-container-low border-outline-variant/20 rounded-2xl p-4 text-sm focus:ring-2 focus:ring-primary/20 transition-all" placeholder="900.XXX.XXX-X" required>`);
			if (unref(form).errors.nit) _push(`<div class="text-error text-[10px] font-bold uppercase mt-1">${ssrInterpolate(unref(form).errors.nit)}</div>`);
			else _push(`<!---->`);
			_push(`</div></div></div><div class="bg-surface-container-lowest p-8 rounded-3xl border border-outline-variant/10 shadow-sm space-y-6"><div class="flex items-center gap-3 mb-2"><span class="material-symbols-outlined text-secondary p-2 bg-secondary-container rounded-xl">location_on</span><h2 class="text-lg font-bold">Ubicación y Datos</h2></div><div class="space-y-4"><div class="space-y-1.5"><label for="direccion" class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Dirección</label><input id="direccion"${ssrRenderAttr("value", unref(form).direccion)} type="text" class="w-full bg-surface-container-low border-outline-variant/20 rounded-2xl p-4 text-sm focus:ring-2 focus:ring-primary/20 transition-all" placeholder="Calle 123 #45-67" required>`);
			if (unref(form).errors.direccion) _push(`<div class="text-error text-[10px] font-bold uppercase mt-1">${ssrInterpolate(unref(form).errors.direccion)}</div>`);
			else _push(`<!---->`);
			_push(`</div><div class="space-y-1.5"><label for="ciudad" class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Ciudad</label><input id="ciudad"${ssrRenderAttr("value", unref(form).ciudad)} type="text" class="w-full bg-surface-container-low border-outline-variant/20 rounded-2xl p-4 text-sm focus:ring-2 focus:ring-primary/20 transition-all" placeholder="Ej: Medellín" required>`);
			if (unref(form).errors.ciudad) _push(`<div class="text-error text-[10px] font-bold uppercase mt-1">${ssrInterpolate(unref(form).errors.ciudad)}</div>`);
			else _push(`<!---->`);
			_push(`</div></div></div><div class="md:col-span-2 bg-surface-container-lowest p-8 rounded-3xl border border-outline-variant/10 shadow-sm space-y-6"><div class="flex items-center gap-3 mb-2"><span class="material-symbols-outlined text-primary p-2 bg-primary-container rounded-xl">settings</span><h2 class="text-lg font-bold">Configuración de la Copropiedad</h2></div><div class="grid grid-cols-1 md:grid-cols-3 gap-6"><div class="space-y-1.5"><label for="unidades" class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Unidades Totales</label><input id="unidades"${ssrRenderAttr("value", unref(form).unidades_totales)} type="number" class="w-full bg-surface-container-low border-outline-variant/20 rounded-2xl p-4 text-sm focus:ring-2 focus:ring-primary/20 transition-all" required></div><div class="space-y-1.5"><label for="torres" class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Nº de Torres / Bloques</label><input id="torres"${ssrRenderAttr("value", unref(form).torres)} type="number" class="w-full bg-surface-container-low border-outline-variant/20 rounded-2xl p-4 text-sm focus:ring-2 focus:ring-primary/20 transition-all" required></div><div class="space-y-1.5"><label for="plan" class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Plan Seleccionado</label><select id="plan" class="w-full bg-surface-container-low border-outline-variant/20 rounded-2xl p-4 text-sm focus:ring-2 focus:ring-primary/20 transition-all"><option value="basic"${ssrIncludeBooleanAttr(Array.isArray(unref(form).plan) ? ssrLooseContain(unref(form).plan, "basic") : ssrLooseEqual(unref(form).plan, "basic")) ? " selected" : ""}>BASIC (Hasta 50 unidades)</option><option value="pro"${ssrIncludeBooleanAttr(Array.isArray(unref(form).plan) ? ssrLooseContain(unref(form).plan, "pro") : ssrLooseEqual(unref(form).plan, "pro")) ? " selected" : ""}>PRO (Hasta 200 unidades)</option><option value="enterprise"${ssrIncludeBooleanAttr(Array.isArray(unref(form).plan) ? ssrLooseContain(unref(form).plan, "enterprise") : ssrLooseEqual(unref(form).plan, "enterprise")) ? " selected" : ""}>ENTERPRISE (Ilimitado)</option></select></div></div></div><div class="md:col-span-2 flex items-center justify-end gap-4 mt-4"><button type="button" class="px-8 py-4 text-sm font-bold text-on-surface-variant hover:bg-surface-container-high rounded-2xl transition-all"> CANCELAR </button><button type="submit"${ssrIncludeBooleanAttr(unref(form).processing) ? " disabled" : ""} class="px-10 py-4 bg-primary text-on-primary text-sm font-black rounded-2xl shadow-lg shadow-primary/20 hover:scale-105 active:scale-95 transition-all flex items-center gap-2 disabled:opacity-50"><span class="material-symbols-outlined text-sm">save</span> GUARDAR Y ACTIVAR </button></div></form></div><!--]-->`);
		};
	}
});
//#endregion
//#region resources/js/Pages/Admin/Copropiedades/Create.vue
var _sfc_setup = Create_vue_vue_type_script_setup_true_lang_default.setup;
Create_vue_vue_type_script_setup_true_lang_default.setup = (props, ctx) => {
	const ssrContext = useSSRContext();
	(ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Admin/Copropiedades/Create.vue");
	return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
var Create_default = Create_vue_vue_type_script_setup_true_lang_default;
//#endregion
export { Create_default as default };

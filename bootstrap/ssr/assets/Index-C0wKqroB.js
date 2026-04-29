import { t as _plugin_vue_export_helper_default } from "./_plugin-vue_export-helper-BjJHhBxR.js";
import { t as useToast } from "./useToast-E_Hdzmc5.js";
import { t as AuthenticatedLayout_default } from "./AuthenticatedLayout-vxulH9w9.js";
import { t as Card_default } from "./Card-D4IwBRMd.js";
import { t as Button_default } from "./Button-C-byZbTR.js";
import { t as Modal_default } from "./Modal-DfwT9E3X.js";
import { t as Badge_default } from "./Badge-DsYztQFV.js";
import { t as Input_default } from "./Input-CbVZZMpc.js";
import { createBlock, createTextVNode, createVNode, defineComponent, openBlock, ref, toDisplayString, unref, useSSRContext, vModelText, withCtx, withDirectives, withModifiers } from "vue";
import { Head, router, useForm } from "@inertiajs/vue3";
import { ssrInterpolate, ssrRenderAttr, ssrRenderClass, ssrRenderComponent, ssrRenderList } from "vue/server-renderer";
//#region resources/js/Pages/Admin/Zonas/Index.vue?vue&type=script&setup=true&lang.ts
var Index_vue_vue_type_script_setup_true_lang_default = /* @__PURE__ */ defineComponent({
	layout: AuthenticatedLayout_default,
	__name: "Index",
	__ssrInlineRender: true,
	props: { zonas: {} },
	setup(__props) {
		const toast = useToast();
		const showModal = ref(false);
		const editingZona = ref(null);
		const form = useForm({
			nombre: "",
			descripcion: "",
			capacidad_maxima: 10,
			costo: 0
		});
		const openCreateModal = () => {
			editingZona.value = null;
			form.reset();
			showModal.value = true;
		};
		const openEditModal = (zona) => {
			editingZona.value = zona;
			form.nombre = zona.nombre;
			form.descripcion = zona.descripcion;
			form.capacidad_maxima = zona.capacidad_maxima;
			form.costo = zona.costo;
			showModal.value = true;
		};
		const submitForm = () => {
			if (!form.nombre) {
				toast.add("Identificación de activo obligatoria", "danger");
				return;
			}
			if (editingZona.value) form.patch(route("admin.zonas.update", editingZona.value.id), { onSuccess: () => {
				showModal.value = false;
				toast.add("Software de gestión actualizado", "success");
			} });
			else form.post(route("admin.zonas.store"), { onSuccess: () => {
				showModal.value = false;
				toast.add("Nueva integración de activo completada", "success");
			} });
		};
		const toggleStatus = (id) => {
			router.patch(route("admin.zonas.toggle", id), {}, { onSuccess: () => toast.add("Estado de disponibilidad modificado", "primary") });
		};
		const formatCurrency = (value) => {
			return new Intl.NumberFormat("es-CO", {
				style: "currency",
				currency: "COP",
				maximumFractionDigits: 0
			}).format(value);
		};
		return (_ctx, _push, _parent, _attrs) => {
			_push(`<!--[-->`);
			_push(ssrRenderComponent(unref(Head), { title: "Activos — NEXO-PRO" }, null, _parent));
			_push(`<div class="space-y-12 pb-32 animate-in fade-in slide-in-from-bottom-6 duration-700" data-v-7a2a94fa><div class="flex flex-col md:flex-row md:items-end justify-between gap-8 px-1" data-v-7a2a94fa><div class="space-y-3" data-v-7a2a94fa><div class="flex items-center gap-3" data-v-7a2a94fa><div class="w-2 h-6 bg-secondary rounded-full" data-v-7a2a94fa></div><p class="text-[10px] font-black text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.4em] italic leading-none" data-v-7a2a94fa>Gestión de Infraestructura Común</p></div><h2 class="text-5xl font-black text-on-surface dark:text-white tracking-tighter uppercase italic leading-none" data-v-7a2a94fa>Zonas <span class="text-secondary italic" data-v-7a2a94fa>Comunes</span></h2><p class="text-[11px] font-bold text-on-surface-variant/40 dark:text-white/10 uppercase tracking-[0.2em] mt-2 leading-relaxed" data-v-7a2a94fa>Configuración de parámetros operativos y modelos de reserva</p></div>`);
			_push(ssrRenderComponent(Button_default, {
				variant: "primary",
				size: "lg",
				icon: "add_home_work",
				class: "!h-16 !px-10 !rounded-2xl shadow-xl shadow-primary/20 !text-[11px] font-black uppercase italic",
				onClick: openCreateModal
			}, {
				default: withCtx((_, _push, _parent, _scopeId) => {
					if (_push) _push(` Registrar Activo `);
					else return [createTextVNode(" Registrar Activo ")];
				}),
				_: 1
			}, _parent));
			_push(`</div><div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10" data-v-7a2a94fa><!--[-->`);
			ssrRenderList(__props.zonas, (zona) => {
				_push(ssrRenderComponent(Card_default, {
					key: zona.id,
					class: "!p-0 !rounded-[3.5rem] overflow-hidden border border-outline-variant/10 dark:border-white/5 transition-all duration-500 hover:-translate-y-2 hover:shadow-3xl hover:shadow-primary/5 dark:bg-[#0b0e14] group"
				}, {
					default: withCtx((_, _push, _parent, _scopeId) => {
						if (_push) {
							_push(`<div class="relative h-60 bg-surface-container-high dark:bg-white/[0.03] overflow-hidden" data-v-7a2a94fa${_scopeId}><div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent z-10 opacity-0 group-hover:opacity-100 transition-opacity duration-700" data-v-7a2a94fa${_scopeId}></div>`);
							if (zona.imagen_path) _push(`<img${ssrRenderAttr("src", zona.imagen_path)} class="w-full h-full object-cover transition-transform duration-[2s] group-hover:scale-110" data-v-7a2a94fa${_scopeId}>`);
							else _push(`<div class="w-full h-full flex flex-col items-center justify-center opacity-20 text-on-surface-variant gap-4 group-hover:bg-primary/5 transition-colors" data-v-7a2a94fa${_scopeId}><span class="material-symbols-rounded text-6xl" data-v-7a2a94fa${_scopeId}>apartment</span><p class="text-[9px] font-black uppercase tracking-[0.3em]" data-v-7a2a94fa${_scopeId}>Cámara no disponible</p></div>`);
							_push(`<div class="absolute top-6 left-6 z-20" data-v-7a2a94fa${_scopeId}><div class="flex items-center gap-3 bg-white/10 backdrop-blur-md px-4 py-2 rounded-xl border border-white/20" data-v-7a2a94fa${_scopeId}><div class="${ssrRenderClass([zona.activa ? "bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.8)]" : "bg-error shadow-[0_0_8px_rgba(239,68,68,0.8)]", "w-2 h-2 rounded-full animate-pulse"])}" data-v-7a2a94fa${_scopeId}></div><span class="text-[9px] font-black text-white uppercase tracking-widest leading-none" data-v-7a2a94fa${_scopeId}>${ssrInterpolate(zona.activa ? "SISTEMA ONLINE" : "ZONA RESTRINGIDA")}</span></div></div><div class="absolute bottom-6 right-6 z-20" data-v-7a2a94fa${_scopeId}>`);
							_push(ssrRenderComponent(Badge_default, {
								variant: "neutral",
								class: "!bg-white !text-black !font-black !text-[11px] !px-5 !py-1 !rounded-xl shadow-2xl tracking-tighter italic"
							}, {
								default: withCtx((_, _push, _parent, _scopeId) => {
									if (_push) _push(`${ssrInterpolate(zona.costo > 0 ? formatCurrency(zona.costo) : "USO GRATUITO")}`);
									else return [createTextVNode(toDisplayString(zona.costo > 0 ? formatCurrency(zona.costo) : "USO GRATUITO"), 1)];
								}),
								_: 2
							}, _parent, _scopeId));
							_push(`</div></div><div class="p-10 space-y-6" data-v-7a2a94fa${_scopeId}><div class="flex justify-between items-start" data-v-7a2a94fa${_scopeId}><div data-v-7a2a94fa${_scopeId}><h3 class="text-2xl font-black text-on-surface dark:text-white uppercase tracking-tighter italic leading-none group-hover:text-primary transition-colors" data-v-7a2a94fa${_scopeId}>${ssrInterpolate(zona.nombre)}</h3><div class="flex items-center gap-2 mt-4" data-v-7a2a94fa${_scopeId}><span class="material-symbols-rounded text-base text-on-surface-variant/40 dark:text-white/20" data-v-7a2a94fa${_scopeId}>groups</span><p class="text-[10px] font-bold text-on-surface-variant/40 dark:text-white/20 uppercase tracking-widest italic" data-v-7a2a94fa${_scopeId}>Capacidad Máx: <span class="text-on-surface dark:text-white" data-v-7a2a94fa${_scopeId}>${ssrInterpolate(zona.capacidad_maxima)} PAX</span></p></div></div></div><p class="text-sm font-medium text-on-surface-variant/70 dark:text-white/40 leading-relaxed line-clamp-3 italic min-h-[4.5rem]" data-v-7a2a94fa${_scopeId}>${ssrInterpolate(zona.descripcion || "Sin protocolos de uso documentados en la base de datos.")}</p><div class="pt-8 border-t border-outline-variant/10 dark:border-white/5 flex gap-4" data-v-7a2a94fa${_scopeId}>`);
							_push(ssrRenderComponent(Button_default, {
								variant: "outline",
								class: "flex-1 !h-14 !rounded-2xl !text-[10px] font-black uppercase italic group-hover:bg-primary/5",
								icon: "tune",
								onClick: ($event) => openEditModal(zona)
							}, {
								default: withCtx((_, _push, _parent, _scopeId) => {
									if (_push) _push(`Calibrar`);
									else return [createTextVNode("Calibrar")];
								}),
								_: 2
							}, _parent, _scopeId));
							_push(ssrRenderComponent(Button_default, {
								variant: zona.activa ? "ghost" : "primary",
								class: "flex-1 !h-14 !rounded-2xl !text-[10px] font-black uppercase italic",
								icon: zona.activa ? "power_settings_new" : "bolt",
								onClick: ($event) => toggleStatus(zona.id)
							}, {
								default: withCtx((_, _push, _parent, _scopeId) => {
									if (_push) _push(`${ssrInterpolate(zona.activa ? "Desconectar" : "Vincular")}`);
									else return [createTextVNode(toDisplayString(zona.activa ? "Desconectar" : "Vincular"), 1)];
								}),
								_: 2
							}, _parent, _scopeId));
							_push(`</div></div>`);
						} else return [createVNode("div", { class: "relative h-60 bg-surface-container-high dark:bg-white/[0.03] overflow-hidden" }, [
							createVNode("div", { class: "absolute inset-0 bg-gradient-to-t from-black/60 to-transparent z-10 opacity-0 group-hover:opacity-100 transition-opacity duration-700" }),
							zona.imagen_path ? (openBlock(), createBlock("img", {
								key: 0,
								src: zona.imagen_path,
								class: "w-full h-full object-cover transition-transform duration-[2s] group-hover:scale-110"
							}, null, 8, ["src"])) : (openBlock(), createBlock("div", {
								key: 1,
								class: "w-full h-full flex flex-col items-center justify-center opacity-20 text-on-surface-variant gap-4 group-hover:bg-primary/5 transition-colors"
							}, [createVNode("span", { class: "material-symbols-rounded text-6xl" }, "apartment"), createVNode("p", { class: "text-[9px] font-black uppercase tracking-[0.3em]" }, "Cámara no disponible")])),
							createVNode("div", { class: "absolute top-6 left-6 z-20" }, [createVNode("div", { class: "flex items-center gap-3 bg-white/10 backdrop-blur-md px-4 py-2 rounded-xl border border-white/20" }, [createVNode("div", { class: ["w-2 h-2 rounded-full animate-pulse", zona.activa ? "bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.8)]" : "bg-error shadow-[0_0_8px_rgba(239,68,68,0.8)]"] }, null, 2), createVNode("span", { class: "text-[9px] font-black text-white uppercase tracking-widest leading-none" }, toDisplayString(zona.activa ? "SISTEMA ONLINE" : "ZONA RESTRINGIDA"), 1)])]),
							createVNode("div", { class: "absolute bottom-6 right-6 z-20" }, [createVNode(Badge_default, {
								variant: "neutral",
								class: "!bg-white !text-black !font-black !text-[11px] !px-5 !py-1 !rounded-xl shadow-2xl tracking-tighter italic"
							}, {
								default: withCtx(() => [createTextVNode(toDisplayString(zona.costo > 0 ? formatCurrency(zona.costo) : "USO GRATUITO"), 1)]),
								_: 2
							}, 1024)])
						]), createVNode("div", { class: "p-10 space-y-6" }, [
							createVNode("div", { class: "flex justify-between items-start" }, [createVNode("div", null, [createVNode("h3", { class: "text-2xl font-black text-on-surface dark:text-white uppercase tracking-tighter italic leading-none group-hover:text-primary transition-colors" }, toDisplayString(zona.nombre), 1), createVNode("div", { class: "flex items-center gap-2 mt-4" }, [createVNode("span", { class: "material-symbols-rounded text-base text-on-surface-variant/40 dark:text-white/20" }, "groups"), createVNode("p", { class: "text-[10px] font-bold text-on-surface-variant/40 dark:text-white/20 uppercase tracking-widest italic" }, [createTextVNode("Capacidad Máx: "), createVNode("span", { class: "text-on-surface dark:text-white" }, toDisplayString(zona.capacidad_maxima) + " PAX", 1)])])])]),
							createVNode("p", { class: "text-sm font-medium text-on-surface-variant/70 dark:text-white/40 leading-relaxed line-clamp-3 italic min-h-[4.5rem]" }, toDisplayString(zona.descripcion || "Sin protocolos de uso documentados en la base de datos."), 1),
							createVNode("div", { class: "pt-8 border-t border-outline-variant/10 dark:border-white/5 flex gap-4" }, [createVNode(Button_default, {
								variant: "outline",
								class: "flex-1 !h-14 !rounded-2xl !text-[10px] font-black uppercase italic group-hover:bg-primary/5",
								icon: "tune",
								onClick: ($event) => openEditModal(zona)
							}, {
								default: withCtx(() => [createTextVNode("Calibrar")]),
								_: 1
							}, 8, ["onClick"]), createVNode(Button_default, {
								variant: zona.activa ? "ghost" : "primary",
								class: "flex-1 !h-14 !rounded-2xl !text-[10px] font-black uppercase italic",
								icon: zona.activa ? "power_settings_new" : "bolt",
								onClick: ($event) => toggleStatus(zona.id)
							}, {
								default: withCtx(() => [createTextVNode(toDisplayString(zona.activa ? "Desconectar" : "Vincular"), 1)]),
								_: 2
							}, 1032, [
								"variant",
								"icon",
								"onClick"
							])])
						])];
					}),
					_: 2
				}, _parent));
			});
			_push(`<!--]--><button class="relative h-full min-h-[35rem] border-4 border-dashed border-outline-variant/10 dark:border-white/5 rounded-[3.5rem] flex flex-col items-center justify-center p-12 hover:border-primary/40 hover:bg-primary/5 transition-all group overflow-hidden" data-v-7a2a94fa><div class="absolute inset-0 bg-primary opacity-0 group-hover:opacity-[0.02] transition-opacity duration-700" data-v-7a2a94fa></div><div class="w-20 h-20 rounded-3xl bg-surface-container-high dark:bg-white/5 flex items-center justify-center text-on-surface-variant/40 group-hover:scale-110 group-hover:bg-primary group-hover:text-white transition-all shadow-2xl relative z-10" data-v-7a2a94fa><span class="material-symbols-rounded text-4xl" data-v-7a2a94fa>add</span></div><p class="text-[11px] font-black text-on-surface-variant/40 uppercase tracking-[0.4em] mt-8 group-hover:text-primary transition-colors relative z-10 italic" data-v-7a2a94fa>Integrar Nuevo Activo</p><div class="mt-4 flex gap-1 relative z-10" data-v-7a2a94fa><!--[-->`);
			ssrRenderList(3, (i) => {
				_push(`<div class="w-1 h-1 rounded-full bg-on-surface-variant/20 dark:bg-white/10 group-hover:bg-primary/40 transition-colors" data-v-7a2a94fa></div>`);
			});
			_push(`<!--]--></div></button></div>`);
			if (showModal.value) _push(ssrRenderComponent(Modal_default, {
				onClose: ($event) => showModal.value = false,
				class: "!max-w-xl"
			}, {
				title: withCtx((_, _push, _parent, _scopeId) => {
					if (_push) _push(`<div class="flex items-center gap-4" data-v-7a2a94fa${_scopeId}><div class="w-12 h-12 bg-primary/10 rounded-2xl flex items-center justify-center text-primary" data-v-7a2a94fa${_scopeId}><span class="material-symbols-rounded text-2xl" data-v-7a2a94fa${_scopeId}>${ssrInterpolate(editingZona.value ? "edit_square" : "add_circle")}</span></div><div data-v-7a2a94fa${_scopeId}><h3 class="text-2xl font-black text-on-surface dark:text-white uppercase tracking-tighter italic leading-none" data-v-7a2a94fa${_scopeId}>${ssrInterpolate(editingZona.value ? "Ajustes Técnicos" : "Registro de Activo")}</h3><p class="text-[9px] font-bold text-on-surface-variant/40 dark:text-white/20 uppercase tracking-widest mt-1" data-v-7a2a94fa${_scopeId}>Configuración de parámetros operativos</p></div></div>`);
					else return [createVNode("div", { class: "flex items-center gap-4" }, [createVNode("div", { class: "w-12 h-12 bg-primary/10 rounded-2xl flex items-center justify-center text-primary" }, [createVNode("span", { class: "material-symbols-rounded text-2xl" }, toDisplayString(editingZona.value ? "edit_square" : "add_circle"), 1)]), createVNode("div", null, [createVNode("h3", { class: "text-2xl font-black text-on-surface dark:text-white uppercase tracking-tighter italic leading-none" }, toDisplayString(editingZona.value ? "Ajustes Técnicos" : "Registro de Activo"), 1), createVNode("p", { class: "text-[9px] font-bold text-on-surface-variant/40 dark:text-white/20 uppercase tracking-widest mt-1" }, "Configuración de parámetros operativos")])])];
				}),
				default: withCtx((_, _push, _parent, _scopeId) => {
					if (_push) {
						_push(`<form class="space-y-8 mt-10" data-v-7a2a94fa${_scopeId}>`);
						_push(ssrRenderComponent(Input_default, {
							modelValue: unref(form).nombre,
							"onUpdate:modelValue": ($event) => unref(form).nombre = $event,
							label: "IDENTIFICACIÓN DEL ACTIVO",
							placeholder: "Ej: SALÓN TÁCTICO, FITNESS CENTER...",
							icon: "apartment",
							required: "",
							class: "!rounded-2xl"
						}, null, _parent, _scopeId));
						_push(`<div class="grid grid-cols-1 md:grid-cols-2 gap-6" data-v-7a2a94fa${_scopeId}>`);
						_push(ssrRenderComponent(Input_default, {
							modelValue: unref(form).capacidad_maxima,
							"onUpdate:modelValue": ($event) => unref(form).capacidad_maxima = $event,
							label: "CAPACIDAD OPERATIVA (PAX)",
							type: "number",
							icon: "groups",
							required: "",
							class: "!rounded-2xl"
						}, null, _parent, _scopeId));
						_push(ssrRenderComponent(Input_default, {
							modelValue: unref(form).costo,
							"onUpdate:modelValue": ($event) => unref(form).costo = $event,
							label: "CANON DE USO (COP)",
							type: "number",
							icon: "payments",
							required: "",
							class: "!rounded-2xl"
						}, null, _parent, _scopeId));
						_push(`</div><div class="space-y-3" data-v-7a2a94fa${_scopeId}><label class="text-[10px] font-black text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.3em] ml-2 italic" data-v-7a2a94fa${_scopeId}>Protocolos y Descripción</label><textarea rows="4" class="w-full bg-surface-container-low dark:bg-white/[0.03] border border-outline-variant/10 dark:border-white/5 rounded-3xl p-6 text-sm font-medium text-on-surface dark:text-white focus:ring-4 focus:ring-primary/5 outline-none transition-all placeholder:text-on-surface-variant/20 italic" placeholder="Define los vectores de uso y normativas locales..." data-v-7a2a94fa${_scopeId}>${ssrInterpolate(unref(form).descripcion)}</textarea></div><div class="flex flex-col gap-4 pt-6 border-t border-outline-variant/10 dark:border-white/5" data-v-7a2a94fa${_scopeId}>`);
						_push(ssrRenderComponent(Button_default, {
							type: "submit",
							variant: "primary",
							size: "lg",
							class: "w-full !h-16 !rounded-2xl !text-[11px] font-black uppercase italic shadow-2xl shadow-primary/20",
							disabled: unref(form).processing,
							icon: editingZona.value ? "save" : "deployment_unit"
						}, {
							default: withCtx((_, _push, _parent, _scopeId) => {
								if (_push) _push(`${ssrInterpolate(unref(form).processing ? "PROCESANDO..." : editingZona.value ? "ACTUALIZAR PARÁMETROS" : "VINCULAR ACTIVO")}`);
								else return [createTextVNode(toDisplayString(unref(form).processing ? "PROCESANDO..." : editingZona.value ? "ACTUALIZAR PARÁMETROS" : "VINCULAR ACTIVO"), 1)];
							}),
							_: 1
						}, _parent, _scopeId));
						_push(ssrRenderComponent(Button_default, {
							variant: "ghost",
							class: "w-full !text-[9px] font-black uppercase tracking-widest text-on-surface-variant/40",
							onClick: ($event) => showModal.value = false
						}, {
							default: withCtx((_, _push, _parent, _scopeId) => {
								if (_push) _push(`Abortar Operación`);
								else return [createTextVNode("Abortar Operación")];
							}),
							_: 1
						}, _parent, _scopeId));
						_push(`</div></form>`);
					} else return [createVNode("form", {
						onSubmit: withModifiers(submitForm, ["prevent"]),
						class: "space-y-8 mt-10"
					}, [
						createVNode(Input_default, {
							modelValue: unref(form).nombre,
							"onUpdate:modelValue": ($event) => unref(form).nombre = $event,
							label: "IDENTIFICACIÓN DEL ACTIVO",
							placeholder: "Ej: SALÓN TÁCTICO, FITNESS CENTER...",
							icon: "apartment",
							required: "",
							class: "!rounded-2xl"
						}, null, 8, ["modelValue", "onUpdate:modelValue"]),
						createVNode("div", { class: "grid grid-cols-1 md:grid-cols-2 gap-6" }, [createVNode(Input_default, {
							modelValue: unref(form).capacidad_maxima,
							"onUpdate:modelValue": ($event) => unref(form).capacidad_maxima = $event,
							label: "CAPACIDAD OPERATIVA (PAX)",
							type: "number",
							icon: "groups",
							required: "",
							class: "!rounded-2xl"
						}, null, 8, ["modelValue", "onUpdate:modelValue"]), createVNode(Input_default, {
							modelValue: unref(form).costo,
							"onUpdate:modelValue": ($event) => unref(form).costo = $event,
							label: "CANON DE USO (COP)",
							type: "number",
							icon: "payments",
							required: "",
							class: "!rounded-2xl"
						}, null, 8, ["modelValue", "onUpdate:modelValue"])]),
						createVNode("div", { class: "space-y-3" }, [createVNode("label", { class: "text-[10px] font-black text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.3em] ml-2 italic" }, "Protocolos y Descripción"), withDirectives(createVNode("textarea", {
							"onUpdate:modelValue": ($event) => unref(form).descripcion = $event,
							rows: "4",
							class: "w-full bg-surface-container-low dark:bg-white/[0.03] border border-outline-variant/10 dark:border-white/5 rounded-3xl p-6 text-sm font-medium text-on-surface dark:text-white focus:ring-4 focus:ring-primary/5 outline-none transition-all placeholder:text-on-surface-variant/20 italic",
							placeholder: "Define los vectores de uso y normativas locales..."
						}, null, 8, ["onUpdate:modelValue"]), [[vModelText, unref(form).descripcion]])]),
						createVNode("div", { class: "flex flex-col gap-4 pt-6 border-t border-outline-variant/10 dark:border-white/5" }, [createVNode(Button_default, {
							type: "submit",
							variant: "primary",
							size: "lg",
							class: "w-full !h-16 !rounded-2xl !text-[11px] font-black uppercase italic shadow-2xl shadow-primary/20",
							disabled: unref(form).processing,
							icon: editingZona.value ? "save" : "deployment_unit"
						}, {
							default: withCtx(() => [createTextVNode(toDisplayString(unref(form).processing ? "PROCESANDO..." : editingZona.value ? "ACTUALIZAR PARÁMETROS" : "VINCULAR ACTIVO"), 1)]),
							_: 1
						}, 8, ["disabled", "icon"]), createVNode(Button_default, {
							variant: "ghost",
							class: "w-full !text-[9px] font-black uppercase tracking-widest text-on-surface-variant/40",
							onClick: ($event) => showModal.value = false
						}, {
							default: withCtx(() => [createTextVNode("Abortar Operación")]),
							_: 1
						}, 8, ["onClick"])])
					], 32)];
				}),
				_: 1
			}, _parent));
			else _push(`<!---->`);
			_push(`</div><!--]-->`);
		};
	}
});
//#endregion
//#region resources/js/Pages/Admin/Zonas/Index.vue
var _sfc_setup = Index_vue_vue_type_script_setup_true_lang_default.setup;
Index_vue_vue_type_script_setup_true_lang_default.setup = (props, ctx) => {
	const ssrContext = useSSRContext();
	(ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Admin/Zonas/Index.vue");
	return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
var Index_default = /* @__PURE__ */ _plugin_vue_export_helper_default(Index_vue_vue_type_script_setup_true_lang_default, [["__scopeId", "data-v-7a2a94fa"]]);
//#endregion
export { Index_default as default };

import { t as _plugin_vue_export_helper_default } from "./_plugin-vue_export-helper-BjJHhBxR.js";
import { t as Card_default } from "./Card-C2-I3_la.js";
import { t as Button_default } from "./Button-5V-KYwxi.js";
import { t as Modal_default } from "./Modal-CeRVVYhy.js";
import { t as Badge_default } from "./Badge-DWJ2qZRx.js";
import { t as Select_default } from "./Select-D3C81tr4.js";
import { t as useToast } from "./useToast-Dcf8ak3V.js";
import { t as Input_default } from "./Input-DWocsxNw.js";
import { t as OwnerLayout_default } from "./OwnerLayout-DdbYh73s.js";
import { t as Accordion_default } from "./Accordion-Bv7OnrtL.js";
import { createBlock, createCommentVNode, createTextVNode, createVNode, defineComponent, openBlock, ref, toDisplayString, unref, useSSRContext, vModelText, withCtx, withDirectives, withModifiers } from "vue";
import { Head, router, useForm } from "@inertiajs/vue3";
import { ssrInterpolate, ssrRenderComponent, ssrRenderList } from "vue/server-renderer";
//#region resources/js/Pages/Owner/Pqrs/Index.vue?vue&type=script&setup=true&lang.ts
var Index_vue_vue_type_script_setup_true_lang_default = /* @__PURE__ */ defineComponent({
	layout: OwnerLayout_default,
	__name: "Index",
	__ssrInlineRender: true,
	props: {
		pqrs: {},
		unidades: {}
	},
	setup(__props) {
		const props = __props;
		const toast = useToast();
		const showCreateModal = ref(false);
		const form = useForm({
			unidad_id: props.unidades[0]?.id || "",
			tipo: "peticion",
			asunto: "",
			mensaje: "",
			prioridad: "media",
			adjuntos: []
		});
		const submitPqrs = () => {
			form.post(route("pqrs.store"), { onSuccess: () => {
				showCreateModal.value = false;
				form.reset();
				toast.add("PQRS radicada exitosamente", "success");
			} });
		};
		const getStatusVariant = (status) => {
			switch (status) {
				case "abierto": return "primary";
				case "en_proceso": return "warning";
				case "cerrado": return "neutral";
				case "reabierto": return "danger";
				default: return "neutral";
			}
		};
		const getTipoLabel = (tipo) => tipo.replace("_", " ").toUpperCase();
		return (_ctx, _push, _parent, _attrs) => {
			_push(`<!--[-->`);
			_push(ssrRenderComponent(unref(Head), { title: "Mis PQRS — NEXO-PRO" }, null, _parent));
			_push(`<div class="min-h-screen" data-v-d59dbdf3><div class="space-y-8 pb-20" data-v-d59dbdf3><div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6" data-v-d59dbdf3><div data-v-d59dbdf3><h2 class="text-3xl font-black text-primary tracking-tighter uppercase" data-v-d59dbdf3>Mis Solicitudes</h2><p class="text-on-surface-variant/60 text-sm font-medium mt-1" data-v-d59dbdf3>Radica y haz seguimiento a tus peticiones, quejas y reclamos.</p></div>`);
			_push(ssrRenderComponent(Button_default, {
				variant: "primary",
				size: "lg",
				icon: "add_comment",
				onClick: ($event) => showCreateModal.value = true
			}, {
				default: withCtx((_, _push, _parent, _scopeId) => {
					if (_push) _push(`Nueva PQRS`);
					else return [createTextVNode("Nueva PQRS")];
				}),
				_: 1
			}, _parent));
			_push(`</div>`);
			if (__props.pqrs.length > 0) {
				_push(`<div class="space-y-4" data-v-d59dbdf3><!--[-->`);
				ssrRenderList(__props.pqrs, (item) => {
					_push(ssrRenderComponent(Card_default, {
						key: item.id,
						class: "!p-0 overflow-hidden"
					}, {
						default: withCtx((_, _push, _parent, _scopeId) => {
							if (_push) _push(ssrRenderComponent(Accordion_default, { items: [{
								title: `#${String(item.id).padStart(5, "0")} - ${item.asunto}`,
								content: ""
							}] }, {
								"title-0": withCtx((_, _push, _parent, _scopeId) => {
									if (_push) {
										_push(`<div class="flex flex-1 items-center justify-between pr-4" data-v-d59dbdf3${_scopeId}><div class="flex flex-col text-left" data-v-d59dbdf3${_scopeId}><span class="text-xs font-black text-primary uppercase tracking-tighter" data-v-d59dbdf3${_scopeId}>#${ssrInterpolate(String(item.id).padStart(5, "0"))} · ${ssrInterpolate(item.asunto)}</span><span class="text-[10px] text-on-surface-variant/40 font-bold uppercase tracking-widest" data-v-d59dbdf3${_scopeId}>${ssrInterpolate(new Date(item.created_at).toLocaleDateString())}</span></div><div class="flex items-center gap-3" data-v-d59dbdf3${_scopeId}>`);
										_push(ssrRenderComponent(Badge_default, { variant: getStatusVariant(item.estado) }, {
											default: withCtx((_, _push, _parent, _scopeId) => {
												if (_push) _push(`${ssrInterpolate(item.estado)}`);
												else return [createTextVNode(toDisplayString(item.estado), 1)];
											}),
											_: 2
										}, _parent, _scopeId));
										_push(`</div></div>`);
									} else return [createVNode("div", { class: "flex flex-1 items-center justify-between pr-4" }, [createVNode("div", { class: "flex flex-col text-left" }, [createVNode("span", { class: "text-xs font-black text-primary uppercase tracking-tighter" }, "#" + toDisplayString(String(item.id).padStart(5, "0")) + " · " + toDisplayString(item.asunto), 1), createVNode("span", { class: "text-[10px] text-on-surface-variant/40 font-bold uppercase tracking-widest" }, toDisplayString(new Date(item.created_at).toLocaleDateString()), 1)]), createVNode("div", { class: "flex items-center gap-3" }, [createVNode(Badge_default, { variant: getStatusVariant(item.estado) }, {
										default: withCtx(() => [createTextVNode(toDisplayString(item.estado), 1)]),
										_: 2
									}, 1032, ["variant"])])])];
								}),
								"content-0": withCtx((_, _push, _parent, _scopeId) => {
									if (_push) {
										_push(`<div class="p-6 space-y-6 bg-surface-container-low/30 rounded-2xl mt-2 border border-outline-variant/10" data-v-d59dbdf3${_scopeId}><div class="grid grid-cols-1 md:grid-cols-2 gap-8" data-v-d59dbdf3${_scopeId}><div class="space-y-2" data-v-d59dbdf3${_scopeId}><p class="text-[10px] font-black text-primary uppercase tracking-widest" data-v-d59dbdf3${_scopeId}>Detalle de la solicitud:</p><p class="text-sm font-medium text-on-surface leading-relaxed" data-v-d59dbdf3${_scopeId}>${ssrInterpolate(item.mensaje)}</p><div class="flex gap-2 mt-4" data-v-d59dbdf3${_scopeId}>`);
										_push(ssrRenderComponent(Badge_default, {
											variant: "neutral",
											class: "!text-[8px]"
										}, {
											default: withCtx((_, _push, _parent, _scopeId) => {
												if (_push) _push(`${ssrInterpolate(getTipoLabel(item.tipo))}`);
												else return [createTextVNode(toDisplayString(getTipoLabel(item.tipo)), 1)];
											}),
											_: 2
										}, _parent, _scopeId));
										_push(ssrRenderComponent(Badge_default, {
											variant: "outline",
											class: "!text-[8px]"
										}, {
											default: withCtx((_, _push, _parent, _scopeId) => {
												if (_push) _push(`TORRE ${ssrInterpolate(item.unidad.torre)} - ${ssrInterpolate(item.unidad.nombre)}`);
												else return [createTextVNode("TORRE " + toDisplayString(item.unidad.torre) + " - " + toDisplayString(item.unidad.nombre), 1)];
											}),
											_: 2
										}, _parent, _scopeId));
										_push(`</div></div><div class="space-y-2 border-l border-outline-variant/10 pl-8" data-v-d59dbdf3${_scopeId}><p class="text-[10px] font-black text-secondary uppercase tracking-widest" data-v-d59dbdf3${_scopeId}>Respuesta de Administración:</p>`);
										if (item.respuesta) _push(`<div class="space-y-3" data-v-d59dbdf3${_scopeId}><p class="text-sm font-medium text-on-surface leading-relaxed italic" data-v-d59dbdf3${_scopeId}>&quot;${ssrInterpolate(item.respuesta)}&quot;</p><p class="text-[9px] text-on-surface-variant/40 font-bold uppercase" data-v-d59dbdf3${_scopeId}>Respondido el ${ssrInterpolate(new Date(item.fecha_respuesta).toLocaleDateString())}</p></div>`);
										else _push(`<div class="flex flex-col items-center py-4 opacity-30" data-v-d59dbdf3${_scopeId}><span class="material-symbols-outlined text-3xl" data-v-d59dbdf3${_scopeId}>pending_actions</span><p class="text-[10px] font-black uppercase tracking-widest mt-2" data-v-d59dbdf3${_scopeId}>En espera de respuesta</p></div>`);
										_push(`</div></div><div class="pt-4 border-t border-outline-variant/10 flex justify-end gap-3" data-v-d59dbdf3${_scopeId}>`);
										_push(ssrRenderComponent(Button_default, {
											variant: "outline",
											size: "sm",
											icon: "picture_as_pdf",
											onClick: ($event) => _ctx.window.open(_ctx.route("pqrs.download", item.id))
										}, {
											default: withCtx((_, _push, _parent, _scopeId) => {
												if (_push) _push(`Descargar PDF`);
												else return [createTextVNode("Descargar PDF")];
											}),
											_: 2
										}, _parent, _scopeId));
										if (item.estado === "cerrado") _push(ssrRenderComponent(Button_default, {
											variant: "ghost",
											size: "sm",
											icon: "replay",
											class: "!text-danger",
											onClick: ($event) => unref(router).patch(_ctx.route("pqrs.update", item.id))
										}, {
											default: withCtx((_, _push, _parent, _scopeId) => {
												if (_push) _push(`Reabrir caso`);
												else return [createTextVNode("Reabrir caso")];
											}),
											_: 2
										}, _parent, _scopeId));
										else _push(`<!---->`);
										_push(`</div></div>`);
									} else return [createVNode("div", { class: "p-6 space-y-6 bg-surface-container-low/30 rounded-2xl mt-2 border border-outline-variant/10" }, [createVNode("div", { class: "grid grid-cols-1 md:grid-cols-2 gap-8" }, [createVNode("div", { class: "space-y-2" }, [
										createVNode("p", { class: "text-[10px] font-black text-primary uppercase tracking-widest" }, "Detalle de la solicitud:"),
										createVNode("p", { class: "text-sm font-medium text-on-surface leading-relaxed" }, toDisplayString(item.mensaje), 1),
										createVNode("div", { class: "flex gap-2 mt-4" }, [createVNode(Badge_default, {
											variant: "neutral",
											class: "!text-[8px]"
										}, {
											default: withCtx(() => [createTextVNode(toDisplayString(getTipoLabel(item.tipo)), 1)]),
											_: 2
										}, 1024), createVNode(Badge_default, {
											variant: "outline",
											class: "!text-[8px]"
										}, {
											default: withCtx(() => [createTextVNode("TORRE " + toDisplayString(item.unidad.torre) + " - " + toDisplayString(item.unidad.nombre), 1)]),
											_: 2
										}, 1024)])
									]), createVNode("div", { class: "space-y-2 border-l border-outline-variant/10 pl-8" }, [createVNode("p", { class: "text-[10px] font-black text-secondary uppercase tracking-widest" }, "Respuesta de Administración:"), item.respuesta ? (openBlock(), createBlock("div", {
										key: 0,
										class: "space-y-3"
									}, [createVNode("p", { class: "text-sm font-medium text-on-surface leading-relaxed italic" }, "\"" + toDisplayString(item.respuesta) + "\"", 1), createVNode("p", { class: "text-[9px] text-on-surface-variant/40 font-bold uppercase" }, "Respondido el " + toDisplayString(new Date(item.fecha_respuesta).toLocaleDateString()), 1)])) : (openBlock(), createBlock("div", {
										key: 1,
										class: "flex flex-col items-center py-4 opacity-30"
									}, [createVNode("span", { class: "material-symbols-outlined text-3xl" }, "pending_actions"), createVNode("p", { class: "text-[10px] font-black uppercase tracking-widest mt-2" }, "En espera de respuesta")]))])]), createVNode("div", { class: "pt-4 border-t border-outline-variant/10 flex justify-end gap-3" }, [createVNode(Button_default, {
										variant: "outline",
										size: "sm",
										icon: "picture_as_pdf",
										onClick: ($event) => _ctx.window.open(_ctx.route("pqrs.download", item.id))
									}, {
										default: withCtx(() => [createTextVNode("Descargar PDF")]),
										_: 1
									}, 8, ["onClick"]), item.estado === "cerrado" ? (openBlock(), createBlock(Button_default, {
										key: 0,
										variant: "ghost",
										size: "sm",
										icon: "replay",
										class: "!text-danger",
										onClick: ($event) => unref(router).patch(_ctx.route("pqrs.update", item.id))
									}, {
										default: withCtx(() => [createTextVNode("Reabrir caso")]),
										_: 1
									}, 8, ["onClick"])) : createCommentVNode("", true)])])];
								}),
								_: 2
							}, _parent, _scopeId));
							else return [createVNode(Accordion_default, { items: [{
								title: `#${String(item.id).padStart(5, "0")} - ${item.asunto}`,
								content: ""
							}] }, {
								"title-0": withCtx(() => [createVNode("div", { class: "flex flex-1 items-center justify-between pr-4" }, [createVNode("div", { class: "flex flex-col text-left" }, [createVNode("span", { class: "text-xs font-black text-primary uppercase tracking-tighter" }, "#" + toDisplayString(String(item.id).padStart(5, "0")) + " · " + toDisplayString(item.asunto), 1), createVNode("span", { class: "text-[10px] text-on-surface-variant/40 font-bold uppercase tracking-widest" }, toDisplayString(new Date(item.created_at).toLocaleDateString()), 1)]), createVNode("div", { class: "flex items-center gap-3" }, [createVNode(Badge_default, { variant: getStatusVariant(item.estado) }, {
									default: withCtx(() => [createTextVNode(toDisplayString(item.estado), 1)]),
									_: 2
								}, 1032, ["variant"])])])]),
								"content-0": withCtx(() => [createVNode("div", { class: "p-6 space-y-6 bg-surface-container-low/30 rounded-2xl mt-2 border border-outline-variant/10" }, [createVNode("div", { class: "grid grid-cols-1 md:grid-cols-2 gap-8" }, [createVNode("div", { class: "space-y-2" }, [
									createVNode("p", { class: "text-[10px] font-black text-primary uppercase tracking-widest" }, "Detalle de la solicitud:"),
									createVNode("p", { class: "text-sm font-medium text-on-surface leading-relaxed" }, toDisplayString(item.mensaje), 1),
									createVNode("div", { class: "flex gap-2 mt-4" }, [createVNode(Badge_default, {
										variant: "neutral",
										class: "!text-[8px]"
									}, {
										default: withCtx(() => [createTextVNode(toDisplayString(getTipoLabel(item.tipo)), 1)]),
										_: 2
									}, 1024), createVNode(Badge_default, {
										variant: "outline",
										class: "!text-[8px]"
									}, {
										default: withCtx(() => [createTextVNode("TORRE " + toDisplayString(item.unidad.torre) + " - " + toDisplayString(item.unidad.nombre), 1)]),
										_: 2
									}, 1024)])
								]), createVNode("div", { class: "space-y-2 border-l border-outline-variant/10 pl-8" }, [createVNode("p", { class: "text-[10px] font-black text-secondary uppercase tracking-widest" }, "Respuesta de Administración:"), item.respuesta ? (openBlock(), createBlock("div", {
									key: 0,
									class: "space-y-3"
								}, [createVNode("p", { class: "text-sm font-medium text-on-surface leading-relaxed italic" }, "\"" + toDisplayString(item.respuesta) + "\"", 1), createVNode("p", { class: "text-[9px] text-on-surface-variant/40 font-bold uppercase" }, "Respondido el " + toDisplayString(new Date(item.fecha_respuesta).toLocaleDateString()), 1)])) : (openBlock(), createBlock("div", {
									key: 1,
									class: "flex flex-col items-center py-4 opacity-30"
								}, [createVNode("span", { class: "material-symbols-outlined text-3xl" }, "pending_actions"), createVNode("p", { class: "text-[10px] font-black uppercase tracking-widest mt-2" }, "En espera de respuesta")]))])]), createVNode("div", { class: "pt-4 border-t border-outline-variant/10 flex justify-end gap-3" }, [createVNode(Button_default, {
									variant: "outline",
									size: "sm",
									icon: "picture_as_pdf",
									onClick: ($event) => _ctx.window.open(_ctx.route("pqrs.download", item.id))
								}, {
									default: withCtx(() => [createTextVNode("Descargar PDF")]),
									_: 1
								}, 8, ["onClick"]), item.estado === "cerrado" ? (openBlock(), createBlock(Button_default, {
									key: 0,
									variant: "ghost",
									size: "sm",
									icon: "replay",
									class: "!text-danger",
									onClick: ($event) => unref(router).patch(_ctx.route("pqrs.update", item.id))
								}, {
									default: withCtx(() => [createTextVNode("Reabrir caso")]),
									_: 1
								}, 8, ["onClick"])) : createCommentVNode("", true)])])]),
								_: 2
							}, 1032, ["items"])];
						}),
						_: 2
					}, _parent));
				});
				_push(`<!--]--></div>`);
			} else {
				_push(`<div class="py-32 text-center opacity-30 flex flex-col items-center gap-6" data-v-d59dbdf3><div class="w-24 h-24 rounded-full bg-surface-container-high flex items-center justify-center" data-v-d59dbdf3><span class="material-symbols-outlined text-6xl" data-v-d59dbdf3>forum</span></div><div class="space-y-2" data-v-d59dbdf3><p class="text-xl font-black uppercase tracking-tighter text-primary" data-v-d59dbdf3>Sin radicados</p><p class="text-xs font-medium uppercase tracking-widest" data-v-d59dbdf3>No has registrado ninguna solicitud todavía</p></div>`);
				_push(ssrRenderComponent(Button_default, {
					variant: "outline",
					class: "mt-4",
					icon: "add_circle",
					onClick: ($event) => showCreateModal.value = true
				}, {
					default: withCtx((_, _push, _parent, _scopeId) => {
						if (_push) _push(`Crear mi primera PQRS`);
						else return [createTextVNode("Crear mi primera PQRS")];
					}),
					_: 1
				}, _parent));
				_push(`</div>`);
			}
			_push(`</div>`);
			if (showCreateModal.value) _push(ssrRenderComponent(Modal_default, {
				onClose: ($event) => showCreateModal.value = false,
				title: "Radicar Nueva PQRS"
			}, {
				default: withCtx((_, _push, _parent, _scopeId) => {
					if (_push) {
						_push(`<form class="space-y-6" data-v-d59dbdf3${_scopeId}>`);
						_push(ssrRenderComponent(Select_default, {
							modelValue: unref(form).unidad_id,
							"onUpdate:modelValue": ($event) => unref(form).unidad_id = $event,
							label: "¿Sobre qué unidad reportas?",
							options: __props.unidades.map((u) => ({
								value: u.id,
								label: `Torre ${u.torre} - ${u.nombre}`
							})),
							icon: "apartment",
							required: ""
						}, null, _parent, _scopeId));
						_push(`<div class="grid grid-cols-1 md:grid-cols-2 gap-6" data-v-d59dbdf3${_scopeId}>`);
						_push(ssrRenderComponent(Select_default, {
							modelValue: unref(form).tipo,
							"onUpdate:modelValue": ($event) => unref(form).tipo = $event,
							label: "Tipo de Solicitud",
							options: [
								{
									value: "peticion",
									label: "PETICIÓN"
								},
								{
									value: "queja",
									label: "QUEJA"
								},
								{
									value: "reclamo",
									label: "RECLAMO"
								},
								{
									value: "sugerencia",
									label: "SUGERENCIA"
								},
								{
									value: "reporte_danos",
									label: "REPORTE DAÑOS"
								}
							],
							icon: "category"
						}, null, _parent, _scopeId));
						_push(ssrRenderComponent(Select_default, {
							modelValue: unref(form).prioridad,
							"onUpdate:modelValue": ($event) => unref(form).prioridad = $event,
							label: "Prioridad",
							options: [
								{
									value: "baja",
									label: "BAJA"
								},
								{
									value: "media",
									label: "MEDIA"
								},
								{
									value: "alta",
									label: "ALTA"
								}
							],
							icon: "priority_high"
						}, null, _parent, _scopeId));
						_push(`</div>`);
						_push(ssrRenderComponent(Input_default, {
							modelValue: unref(form).asunto,
							"onUpdate:modelValue": ($event) => unref(form).asunto = $event,
							label: "Asunto",
							placeholder: "Breve título de tu solicitud...",
							icon: "title",
							required: ""
						}, null, _parent, _scopeId));
						_push(`<div class="space-y-2" data-v-d59dbdf3${_scopeId}><label class="text-[10px] font-black text-on-surface-variant/60 uppercase tracking-widest ml-1" data-v-d59dbdf3${_scopeId}>Mensaje Detallado</label><textarea rows="5" class="w-full bg-surface-container-low border-2 border-outline-variant/10 rounded-[1.5rem] p-6 text-sm font-medium text-on-surface focus:ring-4 focus:ring-primary/10 outline-none transition-all" placeholder="Explica detalladamente tu situación..." required data-v-d59dbdf3${_scopeId}>${ssrInterpolate(unref(form).mensaje)}</textarea></div><div class="flex flex-col gap-3 pt-4" data-v-d59dbdf3${_scopeId}>`);
						_push(ssrRenderComponent(Button_default, {
							type: "submit",
							variant: "primary",
							size: "lg",
							class: "w-full",
							disabled: unref(form).processing,
							icon: "send"
						}, {
							default: withCtx((_, _push, _parent, _scopeId) => {
								if (_push) _push(`${ssrInterpolate(unref(form).processing ? "Radicando..." : "Radicar Solicitud")}`);
								else return [createTextVNode(toDisplayString(unref(form).processing ? "Radicando..." : "Radicar Solicitud"), 1)];
							}),
							_: 1
						}, _parent, _scopeId));
						_push(ssrRenderComponent(Button_default, {
							variant: "ghost",
							class: "w-full",
							onClick: ($event) => showCreateModal.value = false
						}, {
							default: withCtx((_, _push, _parent, _scopeId) => {
								if (_push) _push(`Cancelar`);
								else return [createTextVNode("Cancelar")];
							}),
							_: 1
						}, _parent, _scopeId));
						_push(`</div></form>`);
					} else return [createVNode("form", {
						onSubmit: withModifiers(submitPqrs, ["prevent"]),
						class: "space-y-6"
					}, [
						createVNode(Select_default, {
							modelValue: unref(form).unidad_id,
							"onUpdate:modelValue": ($event) => unref(form).unidad_id = $event,
							label: "¿Sobre qué unidad reportas?",
							options: __props.unidades.map((u) => ({
								value: u.id,
								label: `Torre ${u.torre} - ${u.nombre}`
							})),
							icon: "apartment",
							required: ""
						}, null, 8, [
							"modelValue",
							"onUpdate:modelValue",
							"options"
						]),
						createVNode("div", { class: "grid grid-cols-1 md:grid-cols-2 gap-6" }, [createVNode(Select_default, {
							modelValue: unref(form).tipo,
							"onUpdate:modelValue": ($event) => unref(form).tipo = $event,
							label: "Tipo de Solicitud",
							options: [
								{
									value: "peticion",
									label: "PETICIÓN"
								},
								{
									value: "queja",
									label: "QUEJA"
								},
								{
									value: "reclamo",
									label: "RECLAMO"
								},
								{
									value: "sugerencia",
									label: "SUGERENCIA"
								},
								{
									value: "reporte_danos",
									label: "REPORTE DAÑOS"
								}
							],
							icon: "category"
						}, null, 8, ["modelValue", "onUpdate:modelValue"]), createVNode(Select_default, {
							modelValue: unref(form).prioridad,
							"onUpdate:modelValue": ($event) => unref(form).prioridad = $event,
							label: "Prioridad",
							options: [
								{
									value: "baja",
									label: "BAJA"
								},
								{
									value: "media",
									label: "MEDIA"
								},
								{
									value: "alta",
									label: "ALTA"
								}
							],
							icon: "priority_high"
						}, null, 8, ["modelValue", "onUpdate:modelValue"])]),
						createVNode(Input_default, {
							modelValue: unref(form).asunto,
							"onUpdate:modelValue": ($event) => unref(form).asunto = $event,
							label: "Asunto",
							placeholder: "Breve título de tu solicitud...",
							icon: "title",
							required: ""
						}, null, 8, ["modelValue", "onUpdate:modelValue"]),
						createVNode("div", { class: "space-y-2" }, [createVNode("label", { class: "text-[10px] font-black text-on-surface-variant/60 uppercase tracking-widest ml-1" }, "Mensaje Detallado"), withDirectives(createVNode("textarea", {
							"onUpdate:modelValue": ($event) => unref(form).mensaje = $event,
							rows: "5",
							class: "w-full bg-surface-container-low border-2 border-outline-variant/10 rounded-[1.5rem] p-6 text-sm font-medium text-on-surface focus:ring-4 focus:ring-primary/10 outline-none transition-all",
							placeholder: "Explica detalladamente tu situación...",
							required: ""
						}, null, 8, ["onUpdate:modelValue"]), [[vModelText, unref(form).mensaje]])]),
						createVNode("div", { class: "flex flex-col gap-3 pt-4" }, [createVNode(Button_default, {
							type: "submit",
							variant: "primary",
							size: "lg",
							class: "w-full",
							disabled: unref(form).processing,
							icon: "send"
						}, {
							default: withCtx(() => [createTextVNode(toDisplayString(unref(form).processing ? "Radicando..." : "Radicar Solicitud"), 1)]),
							_: 1
						}, 8, ["disabled"]), createVNode(Button_default, {
							variant: "ghost",
							class: "w-full",
							onClick: ($event) => showCreateModal.value = false
						}, {
							default: withCtx(() => [createTextVNode("Cancelar")]),
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
//#region resources/js/Pages/Owner/Pqrs/Index.vue
var _sfc_setup = Index_vue_vue_type_script_setup_true_lang_default.setup;
Index_vue_vue_type_script_setup_true_lang_default.setup = (props, ctx) => {
	const ssrContext = useSSRContext();
	(ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Owner/Pqrs/Index.vue");
	return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
var Index_default = /* @__PURE__ */ _plugin_vue_export_helper_default(Index_vue_vue_type_script_setup_true_lang_default, [["__scopeId", "data-v-d59dbdf3"]]);
//#endregion
export { Index_default as default };

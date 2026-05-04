import { t as _plugin_vue_export_helper_default } from "./_plugin-vue_export-helper-BjJHhBxR.js";
import { t as AuthenticatedLayout_default } from "./AuthenticatedLayout-BYv1HA3l.js";
import { t as Card_default } from "./Card-C2-I3_la.js";
import { t as Button_default } from "./Button-5V-KYwxi.js";
import { t as Modal_default } from "./Modal-CeRVVYhy.js";
import { t as Badge_default } from "./Badge-DWJ2qZRx.js";
import { t as Select_default } from "./Select-D3C81tr4.js";
import { Fragment, createBlock, createTextVNode, createVNode, defineComponent, openBlock, ref, renderList, toDisplayString, unref, useSSRContext, withCtx } from "vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import { ssrInterpolate, ssrRenderComponent, ssrRenderList } from "vue/server-renderer";
//#region resources/js/Pages/SuperAdmin/Licenses/Management.vue?vue&type=script&setup=true&lang.ts
var Management_vue_vue_type_script_setup_true_lang_default = /* @__PURE__ */ defineComponent({
	layout: AuthenticatedLayout_default,
	__name: "Management",
	__ssrInlineRender: true,
	props: {
		copropiedad: {},
		availableAdmins: {},
		currentAdmins: {}
	},
	setup(__props) {
		const props = __props;
		const form = useForm({
			old_admin_id: "",
			new_admin_id: ""
		});
		const showModal = ref(false);
		const modalTitle = ref("");
		const modalMessage = ref("");
		const startTransfer = (admin) => {
			form.old_admin_id = admin.id;
			modalTitle.value = "Transferencia de Mando";
			modalMessage.value = `¿Estás seguro de que deseas transferir la administración de "${props.copropiedad.nombre}" a un nuevo administrador? El administrador actual perderá el acceso a este conjunto, pero conservará el acceso a sus otros proyectos.`;
			showModal.value = true;
		};
		const executeTransfer = () => {
			if (!form.new_admin_id || form.processing) return;
			form.post(route("superadmin.properties.transfer", props.copropiedad.id), { onSuccess: () => {
				showModal.value = false;
				form.reset();
			} });
		};
		const cancelTransfer = () => {
			showModal.value = false;
			form.reset();
		};
		return (_ctx, _push, _parent, _attrs) => {
			_push(`<!--[-->`);
			_push(ssrRenderComponent(unref(Head), { title: `Gestionar ${__props.copropiedad.nombre} — NEXO-PRO` }, null, _parent));
			_push(`<div class="max-w-4xl mx-auto space-y-10 pb-20" data-v-857c0ffe><div class="flex flex-col gap-4" data-v-857c0ffe>`);
			_push(ssrRenderComponent(unref(Link), {
				href: _ctx.route("superadmin.licenses.index"),
				class: "text-[10px] font-black text-primary/40 flex items-center gap-2 hover:text-primary transition-colors group"
			}, {
				default: withCtx((_, _push, _parent, _scopeId) => {
					if (_push) _push(`<span class="material-symbols-outlined text-sm group-hover:-translate-x-1 transition-transform" data-v-857c0ffe${_scopeId}>arrow_back</span> VOLVER A LICENCIAS `);
					else return [createVNode("span", { class: "material-symbols-outlined text-sm group-hover:-translate-x-1 transition-transform" }, "arrow_back"), createTextVNode(" VOLVER A LICENCIAS ")];
				}),
				_: 1
			}, _parent));
			_push(`<div class="flex items-center justify-between gap-6" data-v-857c0ffe><div data-v-857c0ffe><h2 class="text-3xl font-black text-primary tracking-tighter uppercase leading-none" data-v-857c0ffe>${ssrInterpolate(__props.copropiedad.nombre)}</h2><p class="text-[10px] font-bold text-on-surface-variant/40 uppercase tracking-[0.2em] mt-3" data-v-857c0ffe>Gestión de mando y continuidad de datos</p></div>`);
			_push(ssrRenderComponent(Badge_default, {
				variant: "success",
				class: "h-fit"
			}, {
				default: withCtx((_, _push, _parent, _scopeId) => {
					if (_push) _push(`CONJUNTO ACTIVO`);
					else return [createTextVNode("CONJUNTO ACTIVO")];
				}),
				_: 1
			}, _parent));
			_push(`</div></div><div class="grid md:grid-cols-3 gap-8" data-v-857c0ffe><div class="md:col-span-2 space-y-8" data-v-857c0ffe>`);
			_push(ssrRenderComponent(Card_default, {
				title: "Administración Actual",
				subtitle: "Personas con acceso de mando actual",
				icon: "shield_person"
			}, {
				default: withCtx((_, _push, _parent, _scopeId) => {
					if (_push) {
						_push(`<div class="divide-y divide-surface-container" data-v-857c0ffe${_scopeId}>`);
						if (__props.currentAdmins.length === 0) _push(`<div class="py-10 text-center" data-v-857c0ffe${_scopeId}><span class="material-symbols-outlined text-4xl text-on-surface-variant/20" data-v-857c0ffe${_scopeId}>person_off</span><p class="text-[10px] font-black text-on-surface-variant/40 uppercase mt-4" data-v-857c0ffe${_scopeId}>No hay administradores asignados</p></div>`);
						else {
							_push(`<!--[-->`);
							ssrRenderList(__props.currentAdmins, (admin) => {
								_push(`<div class="py-4 first:pt-0 last:pb-0 flex items-center justify-between" data-v-857c0ffe${_scopeId}><div class="flex items-center gap-4" data-v-857c0ffe${_scopeId}><div class="w-12 h-12 rounded-2xl bg-primary/5 flex items-center justify-center text-primary border border-primary/10" data-v-857c0ffe${_scopeId}><span class="material-symbols-outlined" data-v-857c0ffe${_scopeId}>person</span></div><div data-v-857c0ffe${_scopeId}><p class="text-sm font-black text-primary uppercase leading-none" data-v-857c0ffe${_scopeId}>${ssrInterpolate(admin.name)}</p><p class="text-[10px] text-on-surface-variant/40 font-bold uppercase mt-1 tracking-tight" data-v-857c0ffe${_scopeId}>${ssrInterpolate(admin.email)}</p></div></div>`);
								_push(ssrRenderComponent(Button_default, {
									variant: "outline",
									size: "sm",
									icon: "swap_horiz",
									class: "!px-4",
									onClick: ($event) => startTransfer(admin)
								}, {
									default: withCtx((_, _push, _parent, _scopeId) => {
										if (_push) _push(`TRASPASA MANDO`);
										else return [createTextVNode("TRASPASA MANDO")];
									}),
									_: 2
								}, _parent, _scopeId));
								_push(`</div>`);
							});
							_push(`<!--]-->`);
						}
						_push(`</div>`);
					} else return [createVNode("div", { class: "divide-y divide-surface-container" }, [__props.currentAdmins.length === 0 ? (openBlock(), createBlock("div", {
						key: 0,
						class: "py-10 text-center"
					}, [createVNode("span", { class: "material-symbols-outlined text-4xl text-on-surface-variant/20" }, "person_off"), createVNode("p", { class: "text-[10px] font-black text-on-surface-variant/40 uppercase mt-4" }, "No hay administradores asignados")])) : (openBlock(true), createBlock(Fragment, { key: 1 }, renderList(__props.currentAdmins, (admin) => {
						return openBlock(), createBlock("div", {
							key: admin.id,
							class: "py-4 first:pt-0 last:pb-0 flex items-center justify-between"
						}, [createVNode("div", { class: "flex items-center gap-4" }, [createVNode("div", { class: "w-12 h-12 rounded-2xl bg-primary/5 flex items-center justify-center text-primary border border-primary/10" }, [createVNode("span", { class: "material-symbols-outlined" }, "person")]), createVNode("div", null, [createVNode("p", { class: "text-sm font-black text-primary uppercase leading-none" }, toDisplayString(admin.name), 1), createVNode("p", { class: "text-[10px] text-on-surface-variant/40 font-bold uppercase mt-1 tracking-tight" }, toDisplayString(admin.email), 1)])]), createVNode(Button_default, {
							variant: "outline",
							size: "sm",
							icon: "swap_horiz",
							class: "!px-4",
							onClick: ($event) => startTransfer(admin)
						}, {
							default: withCtx(() => [createTextVNode("TRASPASA MANDO")]),
							_: 1
						}, 8, ["onClick"])]);
					}), 128))])];
				}),
				_: 1
			}, _parent));
			_push(ssrRenderComponent(Card_default, {
				title: "Patrimonio Digital",
				subtitle: "Información persistente vinculada al conjunto",
				icon: "database"
			}, {
				default: withCtx((_, _push, _parent, _scopeId) => {
					if (_push) _push(`<div class="grid grid-cols-2 md:grid-cols-4 gap-6" data-v-857c0ffe${_scopeId}><div class="space-y-1" data-v-857c0ffe${_scopeId}><p class="text-[10px] font-black text-on-surface-variant/40 uppercase tracking-tighter" data-v-857c0ffe${_scopeId}>Unidades</p><p class="text-2xl font-black text-primary leading-none" data-v-857c0ffe${_scopeId}>${ssrInterpolate(__props.copropiedad.unidades_count || 0)}</p></div><div class="space-y-1" data-v-857c0ffe${_scopeId}><p class="text-[10px] font-black text-on-surface-variant/40 uppercase tracking-tighter" data-v-857c0ffe${_scopeId}>Residentes</p><p class="text-2xl font-black text-primary leading-none" data-v-857c0ffe${_scopeId}>${ssrInterpolate(__props.copropiedad.users_count || 0)}</p></div><div class="space-y-1" data-v-857c0ffe${_scopeId}><p class="text-[10px] font-black text-on-surface-variant/40 uppercase tracking-tighter" data-v-857c0ffe${_scopeId}>Plan</p><p class="text-xs font-black text-primary uppercase bg-primary/5 px-2 py-1 rounded inline-block" data-v-857c0ffe${_scopeId}>${ssrInterpolate(__props.copropiedad.plan)}</p></div><div class="space-y-1" data-v-857c0ffe${_scopeId}><p class="text-[10px] font-black text-on-surface-variant/40 uppercase tracking-tighter" data-v-857c0ffe${_scopeId}>NIT</p><p class="text-xs font-black text-primary leading-tight" data-v-857c0ffe${_scopeId}>${ssrInterpolate(__props.copropiedad.nit)}</p></div></div>`);
					else return [createVNode("div", { class: "grid grid-cols-2 md:grid-cols-4 gap-6" }, [
						createVNode("div", { class: "space-y-1" }, [createVNode("p", { class: "text-[10px] font-black text-on-surface-variant/40 uppercase tracking-tighter" }, "Unidades"), createVNode("p", { class: "text-2xl font-black text-primary leading-none" }, toDisplayString(__props.copropiedad.unidades_count || 0), 1)]),
						createVNode("div", { class: "space-y-1" }, [createVNode("p", { class: "text-[10px] font-black text-on-surface-variant/40 uppercase tracking-tighter" }, "Residentes"), createVNode("p", { class: "text-2xl font-black text-primary leading-none" }, toDisplayString(__props.copropiedad.users_count || 0), 1)]),
						createVNode("div", { class: "space-y-1" }, [createVNode("p", { class: "text-[10px] font-black text-on-surface-variant/40 uppercase tracking-tighter" }, "Plan"), createVNode("p", { class: "text-xs font-black text-primary uppercase bg-primary/5 px-2 py-1 rounded inline-block" }, toDisplayString(__props.copropiedad.plan), 1)]),
						createVNode("div", { class: "space-y-1" }, [createVNode("p", { class: "text-[10px] font-black text-on-surface-variant/40 uppercase tracking-tighter" }, "NIT"), createVNode("p", { class: "text-xs font-black text-primary leading-tight" }, toDisplayString(__props.copropiedad.nit), 1)])
					])];
				}),
				_: 1
			}, _parent));
			_push(`</div><div class="space-y-6" data-v-857c0ffe><div class="bg-primary/5 border border-primary/10 rounded-3xl p-8 space-y-6" data-v-857c0ffe><span class="material-symbols-outlined text-primary text-3xl" data-v-857c0ffe>info</span><div class="space-y-4" data-v-857c0ffe><h4 class="text-sm font-black text-primary uppercase leading-tight" data-v-857c0ffe>Garantía de Continuidad</h4><p class="text-[11px] font-bold text-primary/70 leading-relaxed italic" data-v-857c0ffe> &quot;Al realizar una transferencia, todos los datos históricos (Pagos, PQRS, Actas) permanecen vinculados al NIT del conjunto residencial, asegurando que la nueva administración reciba la copropiedad con su memoria intacta.&quot; </p></div></div>`);
			_push(ssrRenderComponent(Card_default, { class: "bg-surface-container-low" }, {
				default: withCtx((_, _push, _parent, _scopeId) => {
					if (_push) _push(`<div class="space-y-4 text-center" data-v-857c0ffe${_scopeId}><span class="material-symbols-outlined text-warning text-3xl" data-v-857c0ffe${_scopeId}>warning</span><p class="text-[10px] font-black text-on-surface-variant/60 uppercase px-4 leading-relaxed" data-v-857c0ffe${_scopeId}> Las transferencias son irreversibles sin autorización de un Super Administrador. </p></div>`);
					else return [createVNode("div", { class: "space-y-4 text-center" }, [createVNode("span", { class: "material-symbols-outlined text-warning text-3xl" }, "warning"), createVNode("p", { class: "text-[10px] font-black text-on-surface-variant/60 uppercase px-4 leading-relaxed" }, " Las transferencias son irreversibles sin autorización de un Super Administrador. ")])];
				}),
				_: 1
			}, _parent));
			_push(`</div></div>`);
			_push(ssrRenderComponent(Modal_default, {
				show: showModal.value,
				title: modalTitle.value,
				onClose: cancelTransfer
			}, {
				default: withCtx((_, _push, _parent, _scopeId) => {
					if (_push) {
						_push(`<div class="space-y-6" data-v-857c0ffe${_scopeId}><p class="text-sm text-on-surface-variant leading-relaxed" data-v-857c0ffe${_scopeId}>${ssrInterpolate(modalMessage.value)}</p><div class="p-6 bg-surface-container rounded-2xl border border-surface-container-high space-y-4" data-v-857c0ffe${_scopeId}><p class="text-[10px] font-black text-on-surface-variant/40 uppercase tracking-[0.1em]" data-v-857c0ffe${_scopeId}>Selecciona al Nuevo Administrador</p>`);
						_push(ssrRenderComponent(Select_default, {
							modelValue: unref(form).new_admin_id,
							"onUpdate:modelValue": ($event) => unref(form).new_admin_id = $event,
							options: __props.availableAdmins.map((a) => ({
								value: a.id,
								label: `${a.name} (${a.email})`
							})),
							placeholder: "BUSCAR ADMINISTRADOR..."
						}, null, _parent, _scopeId));
						_push(`</div><div class="flex flex-col gap-3" data-v-857c0ffe${_scopeId}>`);
						_push(ssrRenderComponent(Button_default, {
							variant: "primary",
							size: "lg",
							class: "w-full !rounded-2xl h-14",
							disabled: !unref(form).new_admin_id || unref(form).processing,
							onClick: executeTransfer
						}, {
							default: withCtx((_, _push, _parent, _scopeId) => {
								if (_push) if (unref(form).processing) _push(`<span data-v-857c0ffe${_scopeId}>PROCESANDO...</span>`);
								else _push(`<span data-v-857c0ffe${_scopeId}>EJECUTAR TRASPASO SEGURO</span>`);
								else return [unref(form).processing ? (openBlock(), createBlock("span", { key: 0 }, "PROCESANDO...")) : (openBlock(), createBlock("span", { key: 1 }, "EJECUTAR TRASPASO SEGURO"))];
							}),
							_: 1
						}, _parent, _scopeId));
						_push(ssrRenderComponent(Button_default, {
							variant: "ghost",
							class: "w-full",
							onClick: cancelTransfer
						}, {
							default: withCtx((_, _push, _parent, _scopeId) => {
								if (_push) _push(` CANCELAR `);
								else return [createTextVNode(" CANCELAR ")];
							}),
							_: 1
						}, _parent, _scopeId));
						_push(`</div></div>`);
					} else return [createVNode("div", { class: "space-y-6" }, [
						createVNode("p", { class: "text-sm text-on-surface-variant leading-relaxed" }, toDisplayString(modalMessage.value), 1),
						createVNode("div", { class: "p-6 bg-surface-container rounded-2xl border border-surface-container-high space-y-4" }, [createVNode("p", { class: "text-[10px] font-black text-on-surface-variant/40 uppercase tracking-[0.1em]" }, "Selecciona al Nuevo Administrador"), createVNode(Select_default, {
							modelValue: unref(form).new_admin_id,
							"onUpdate:modelValue": ($event) => unref(form).new_admin_id = $event,
							options: __props.availableAdmins.map((a) => ({
								value: a.id,
								label: `${a.name} (${a.email})`
							})),
							placeholder: "BUSCAR ADMINISTRADOR..."
						}, null, 8, [
							"modelValue",
							"onUpdate:modelValue",
							"options"
						])]),
						createVNode("div", { class: "flex flex-col gap-3" }, [createVNode(Button_default, {
							variant: "primary",
							size: "lg",
							class: "w-full !rounded-2xl h-14",
							disabled: !unref(form).new_admin_id || unref(form).processing,
							onClick: executeTransfer
						}, {
							default: withCtx(() => [unref(form).processing ? (openBlock(), createBlock("span", { key: 0 }, "PROCESANDO...")) : (openBlock(), createBlock("span", { key: 1 }, "EJECUTAR TRASPASO SEGURO"))]),
							_: 1
						}, 8, ["disabled"]), createVNode(Button_default, {
							variant: "ghost",
							class: "w-full",
							onClick: cancelTransfer
						}, {
							default: withCtx(() => [createTextVNode(" CANCELAR ")]),
							_: 1
						})])
					])];
				}),
				_: 1
			}, _parent));
			_push(`</div><!--]-->`);
		};
	}
});
//#endregion
//#region resources/js/Pages/SuperAdmin/Licenses/Management.vue
var _sfc_setup = Management_vue_vue_type_script_setup_true_lang_default.setup;
Management_vue_vue_type_script_setup_true_lang_default.setup = (props, ctx) => {
	const ssrContext = useSSRContext();
	(ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/SuperAdmin/Licenses/Management.vue");
	return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
var Management_default = /* @__PURE__ */ _plugin_vue_export_helper_default(Management_vue_vue_type_script_setup_true_lang_default, [["__scopeId", "data-v-857c0ffe"]]);
//#endregion
export { Management_default as default };

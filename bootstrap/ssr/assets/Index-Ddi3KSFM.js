import { t as _plugin_vue_export_helper_default } from "./_plugin-vue_export-helper-BjJHhBxR.js";
import { t as AuthenticatedLayout_default } from "./AuthenticatedLayout-vxulH9w9.js";
import { t as Button_default } from "./Button-C-byZbTR.js";
import { t as Badge_default } from "./Badge-DsYztQFV.js";
import { t as Tooltip_default } from "./Tooltip-IAP-zsdE.js";
import { t as Select_default } from "./Select-DRXhACf5.js";
import { t as DatePicker_default } from "./DatePicker-DD5d8BKS.js";
import { t as ConfirmModal_default } from "./ConfirmModal-BH-N6uDF.js";
import { computed, createTextVNode, createVNode, defineComponent, ref, toDisplayString, unref, useSSRContext, withCtx } from "vue";
import { Head, router } from "@inertiajs/vue3";
import { ssrInterpolate, ssrRenderAttr, ssrRenderClass, ssrRenderComponent, ssrRenderList, ssrRenderStyle } from "vue/server-renderer";
//#region resources/js/Pages/SuperAdmin/Licenses/Index.vue?vue&type=script&setup=true&lang.ts
var Index_vue_vue_type_script_setup_true_lang_default = /* @__PURE__ */ defineComponent({
	layout: AuthenticatedLayout_default,
	__name: "Index",
	__ssrInlineRender: true,
	props: { copropiedades: {} },
	setup(__props) {
		const props = __props;
		const searchTerm = ref("");
		const filterStatus = ref("all");
		const filteredCopropiedades = computed(() => {
			return props.copropiedades.filter((c) => {
				const matchesSearch = c.nombre.toLowerCase().includes(searchTerm.value.toLowerCase()) || c.ciudad.toLowerCase().includes(searchTerm.value.toLowerCase());
				const matchesStatus = filterStatus.value === "all" || c.license_status === filterStatus.value;
				return matchesSearch && matchesStatus;
			});
		});
		const confirmState = ref({
			show: false,
			title: "",
			message: "",
			confirmLabel: "Confirmar",
			variant: "primary",
			action: null
		});
		const updateLicense = (copropiedad, data) => {
			router.post(route("superadmin.licenses.update", copropiedad.id), data, { preserveScroll: true });
		};
		const impersonateAdmin = (copropiedad) => {
			const admin = copropiedad.administradores?.[0];
			if (admin) confirmState.value = {
				show: true,
				title: "Soporte Técnico",
				message: `¿Estás seguro de que deseas iniciar sesión como ${admin.name} para la copropiedad ${copropiedad.nombre}? Esta acción quedará registrada en la auditoría.`,
				confirmLabel: "Iniciar Soporte",
				variant: "primary",
				action: () => router.post(route("superadmin.impersonate", admin.id))
			};
		};
		const executeConfirm = () => {
			if (confirmState.value.action) {
				confirmState.value.action();
				confirmState.value.show = false;
			}
		};
		const getStatusConfig = (status) => {
			switch (status) {
				case "active": return {
					label: "ACTIVA",
					variant: "success",
					icon: "check_circle"
				};
				case "suspended": return {
					label: "SUSPENDIDA",
					variant: "error",
					icon: "pause_circle"
				};
				case "expired": return {
					label: "VENCIDA",
					variant: "warning",
					icon: "history"
				};
				default: return {
					label: "ESTADO",
					variant: "neutral",
					icon: "help"
				};
			}
		};
		return (_ctx, _push, _parent, _attrs) => {
			_push(`<!--[-->`);
			_push(ssrRenderComponent(unref(Head), { title: "Propiedades — NEXO-PRO" }, null, _parent));
			_push(`<div class="space-y-10 pb-20 animate-in fade-in slide-in-from-bottom-4 duration-700" data-v-35867bdc><div class="flex flex-col lg:flex-row lg:items-center justify-between gap-8 px-1" data-v-35867bdc><div class="space-y-4" data-v-35867bdc><div class="flex items-center gap-3" data-v-35867bdc><div class="w-12 h-12 rounded-[1.25rem] bg-brand-gradient shadow-xl shadow-primary/20 flex items-center justify-center text-white" data-v-35867bdc><span class="material-symbols-rounded text-2xl" data-v-35867bdc>business_center</span></div><div data-v-35867bdc><h2 class="text-3xl font-black text-primary tracking-tighter uppercase leading-none" data-v-35867bdc>Hub de Copropiedades</h2><p class="text-[10px] font-bold text-on-surface-variant/40 uppercase tracking-[0.2em] mt-2" data-v-35867bdc>Visión global y gestión de activos</p></div></div></div><div class="flex flex-col sm:flex-row items-center gap-3" data-v-35867bdc><div class="relative w-full sm:w-72" data-v-35867bdc><span class="material-symbols-rounded absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant/40" data-v-35867bdc>search</span><input${ssrRenderAttr("value", searchTerm.value)} placeholder="Buscar por nombre o ciudad..." class="w-full pl-12 pr-4 py-3 bg-surface-container-low border border-outline-variant/10 rounded-2xl text-xs font-bold focus:ring-2 focus:ring-primary/20 outline-none transition-all placeholder:text-on-surface-variant/20" data-v-35867bdc></div><div class="flex items-center gap-2 w-full sm:w-auto" data-v-35867bdc>`);
			_push(ssrRenderComponent(Select_default, {
				modelValue: filterStatus.value,
				"onUpdate:modelValue": ($event) => filterStatus.value = $event,
				options: [
					{
						value: "all",
						label: "TODOS LOS ESTADOS"
					},
					{
						value: "active",
						label: "ACTIVAS"
					},
					{
						value: "suspended",
						label: "SUSPENDIDAS"
					},
					{
						value: "expired",
						label: "VENCIDAS"
					}
				],
				class: "!w-full sm:!w-44 !py-3"
			}, null, _parent));
			_push(ssrRenderComponent(Button_default, {
				variant: "primary",
				icon: "add_business",
				href: _ctx.route("register"),
				class: "!py-3 shadow-brand/25 shadow-xl"
			}, {
				default: withCtx((_, _push, _parent, _scopeId) => {
					if (_push) _push(`Nueva`);
					else return [createTextVNode("Nueva")];
				}),
				_: 1
			}, _parent));
			_push(`</div></div></div>`);
			if (filteredCopropiedades.value.length > 0) {
				_push(`<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8" data-v-35867bdc><!--[-->`);
				ssrRenderList(filteredCopropiedades.value, (c) => {
					_push(`<div class="${ssrRenderClass([{ "opacity-75 grayscale-[0.5]": c.license_status === "suspended" }, "group bg-surface-container-low border border-outline-variant/5 rounded-[2.5rem] overflow-hidden transition-all duration-500 hover:shadow-2xl hover:shadow-primary/10 hover:-translate-y-2 relative"])}" data-v-35867bdc><div class="absolute top-6 right-6 z-10" data-v-35867bdc>`);
					_push(ssrRenderComponent(Badge_default, {
						variant: getStatusConfig(c.license_status).variant,
						class: "!px-3 !py-1 !text-[9px] font-black tracking-widest uppercase shadow-sm"
					}, {
						default: withCtx((_, _push, _parent, _scopeId) => {
							if (_push) _push(`${ssrInterpolate(getStatusConfig(c.license_status).label)}`);
							else return [createTextVNode(toDisplayString(getStatusConfig(c.license_status).label), 1)];
						}),
						_: 2
					}, _parent));
					_push(`</div><div class="h-32 bg-surface-container-high relative flex items-end px-8 pb-4 overflow-hidden" data-v-35867bdc><div class="absolute inset-0 bg-gradient-to-t from-surface-container-low/90 to-transparent" data-v-35867bdc></div><div class="absolute right-0 top-0 w-32 h-32 opacity-[0.03] group-hover:scale-125 transition-transform duration-1000" data-v-35867bdc><span class="material-symbols-rounded text-9xl" data-v-35867bdc>apartment</span></div><div class="relative z-10 flex items-center gap-4" data-v-35867bdc><div class="w-14 h-14 rounded-2xl bg-white shadow-sm border border-outline-variant/10 flex items-center justify-center text-primary group-hover:scale-110 transition-transform duration-500" data-v-35867bdc><span class="material-symbols-rounded text-2xl" data-v-35867bdc>location_city</span></div><div class="max-w-[200px]" data-v-35867bdc><h3 class="text-base font-black text-primary tracking-tighter uppercase leading-tight truncate" data-v-35867bdc>${ssrInterpolate(c.nombre)}</h3><p class="text-[9px] font-bold text-on-surface-variant/60 uppercase tracking-widest" data-v-35867bdc>${ssrInterpolate(c.ciudad)}</p></div></div></div><div class="p-8 space-y-8" data-v-35867bdc><div class="grid grid-cols-2 gap-4" data-v-35867bdc><div class="bg-surface-container/50 p-4 rounded-3xl border border-outline-variant/10 flex flex-col items-center justify-center text-center group/kpi hover:bg-primary/[0.03] transition-colors" data-v-35867bdc><p class="text-[8px] font-black text-on-surface-variant/40 uppercase tracking-[0.2em] mb-1" data-v-35867bdc>Unidades</p><span class="text-xl font-black text-primary leading-none" data-v-35867bdc>${ssrInterpolate(c.unidades_count)}</span></div><div class="bg-surface-container/50 p-4 rounded-3xl border border-outline-variant/10 flex flex-col items-center justify-center text-center hover:bg-primary/[0.03] transition-colors" data-v-35867bdc><p class="text-[8px] font-black text-on-surface-variant/40 uppercase tracking-[0.2em] mb-1" data-v-35867bdc>Residentes</p><span class="text-xl font-black text-primary leading-none" data-v-35867bdc>${ssrInterpolate(c.users_count)}</span></div></div><div class="space-y-6" data-v-35867bdc><div class="flex items-center justify-between" data-v-35867bdc><p class="text-[9px] font-black text-on-surface-variant/50 uppercase tracking-widest" data-v-35867bdc>Configuración Técnica</p>`);
					if (c.days_left !== null) _push(`<div class="flex items-center gap-1.5" data-v-35867bdc><span class="${ssrRenderClass([c.days_left < 15 ? "text-error animate-pulse" : "text-emerald-500", "material-symbols-rounded text-xs"])}" data-v-35867bdc>timer</span><span class="${ssrRenderClass([c.days_left < 15 ? "text-error" : "text-on-surface-variant", "text-[9px] font-black uppercase tracking-tighter"])}" data-v-35867bdc>${ssrInterpolate(c.days_left)} días</span></div>`);
					else _push(`<!---->`);
					_push(`</div><div class="grid grid-cols-2 gap-3" data-v-35867bdc><div class="flex flex-col gap-1.5" data-v-35867bdc><span class="text-[8px] font-black text-on-surface-variant/30 uppercase ml-1" data-v-35867bdc>Plan contratado</span>`);
					_push(ssrRenderComponent(Select_default, {
						"model-value": c.plan || "basic",
						"onUpdate:modelValue": (val) => updateLicense(c, { plan: val }),
						options: [
							{
								value: "basic",
								label: "BASIC"
							},
							{
								value: "pro",
								label: "PRO"
							},
							{
								value: "enterprise",
								label: "ENTERPRISE"
							}
						],
						class: "!py-2.5 !rounded-2xl"
					}, null, _parent));
					_push(`</div><div class="flex flex-col gap-1.5" data-v-35867bdc><span class="text-[8px] font-black text-on-surface-variant/30 uppercase ml-1" data-v-35867bdc>Vencimiento</span>`);
					_push(ssrRenderComponent(DatePicker_default, {
						"model-value": c.license_expires_at,
						"onUpdate:modelValue": (val) => updateLicense(c, { license_expires_at: val }),
						class: "!py-2.5 !rounded-2xl"
					}, null, _parent));
					_push(`</div></div><div class="flex items-center justify-center gap-2 p-2 bg-surface-container rounded-[1.25rem] border border-outline-variant/10" data-v-35867bdc>`);
					_push(ssrRenderComponent(Tooltip_default, { text: c.settings.pqrs_enabled ? "Desactivar PQRS" : "Activar PQRS" }, {
						default: withCtx((_, _push, _parent, _scopeId) => {
							if (_push) _push(`<button class="${ssrRenderClass([c.settings.pqrs_enabled ? "bg-white shadow-sm text-primary" : "text-on-surface-variant/20 hover:text-on-surface-variant/40", "flex-1 py-1.5 rounded-xl flex items-center justify-center transition-all group/mod"])}" data-v-35867bdc${_scopeId}><span class="material-symbols-rounded text-lg" data-v-35867bdc${_scopeId}>forum</span></button>`);
							else return [createVNode("button", {
								onClick: ($event) => updateLicense(c, { pqrs_enabled: !c.settings.pqrs_enabled }),
								class: ["flex-1 py-1.5 rounded-xl flex items-center justify-center transition-all group/mod", c.settings.pqrs_enabled ? "bg-white shadow-sm text-primary" : "text-on-surface-variant/20 hover:text-on-surface-variant/40"]
							}, [createVNode("span", { class: "material-symbols-rounded text-lg" }, "forum")], 10, ["onClick"])];
						}),
						_: 2
					}, _parent));
					_push(ssrRenderComponent(Tooltip_default, { text: c.settings.reservas_enabled ? "Desactivar Reservas" : "Activar Reservas" }, {
						default: withCtx((_, _push, _parent, _scopeId) => {
							if (_push) _push(`<button class="${ssrRenderClass([c.settings.reservas_enabled ? "bg-white shadow-sm text-primary" : "text-on-surface-variant/20 hover:text-on-surface-variant/40", "flex-1 py-1.5 rounded-xl flex items-center justify-center transition-all"])}" data-v-35867bdc${_scopeId}><span class="material-symbols-rounded text-lg" data-v-35867bdc${_scopeId}>calendar_today</span></button>`);
							else return [createVNode("button", {
								onClick: ($event) => updateLicense(c, { reservas_enabled: !c.settings.reservas_enabled }),
								class: ["flex-1 py-1.5 rounded-xl flex items-center justify-center transition-all", c.settings.reservas_enabled ? "bg-white shadow-sm text-primary" : "text-on-surface-variant/20 hover:text-on-surface-variant/40"]
							}, [createVNode("span", { class: "material-symbols-rounded text-lg" }, "calendar_today")], 10, ["onClick"])];
						}),
						_: 2
					}, _parent));
					_push(ssrRenderComponent(Tooltip_default, { text: c.settings.payments_enabled ? "Desactivar Pagos" : "Activar Pagos" }, {
						default: withCtx((_, _push, _parent, _scopeId) => {
							if (_push) _push(`<button class="${ssrRenderClass([c.settings.payments_enabled ? "bg-white shadow-sm text-primary" : "text-on-surface-variant/20 hover:text-on-surface-variant/40", "flex-1 py-1.5 rounded-xl flex items-center justify-center transition-all"])}" data-v-35867bdc${_scopeId}><span class="material-symbols-rounded text-lg" data-v-35867bdc${_scopeId}>payments</span></button>`);
							else return [createVNode("button", {
								onClick: ($event) => updateLicense(c, { payments_enabled: !c.settings.payments_enabled }),
								class: ["flex-1 py-1.5 rounded-xl flex items-center justify-center transition-all", c.settings.payments_enabled ? "bg-white shadow-sm text-primary" : "text-on-surface-variant/20 hover:text-on-surface-variant/40"]
							}, [createVNode("span", { class: "material-symbols-rounded text-lg" }, "payments")], 10, ["onClick"])];
						}),
						_: 2
					}, _parent));
					_push(ssrRenderComponent(Tooltip_default, { text: c.settings.asamblea_virtual_enabled ? "Desactivar Asamblea" : "Activar Asamblea" }, {
						default: withCtx((_, _push, _parent, _scopeId) => {
							if (_push) _push(`<button class="${ssrRenderClass([c.settings.asamblea_virtual_enabled ? "bg-white shadow-sm text-primary" : "text-on-surface-variant/20 hover:text-on-surface-variant/40", "flex-1 py-1.5 rounded-xl flex items-center justify-center transition-all"])}" data-v-35867bdc${_scopeId}><span class="material-symbols-rounded text-lg" data-v-35867bdc${_scopeId}>videocam</span></button>`);
							else return [createVNode("button", {
								onClick: ($event) => updateLicense(c, { asamblea_virtual_enabled: !c.settings.asamblea_virtual_enabled }),
								class: ["flex-1 py-1.5 rounded-xl flex items-center justify-center transition-all", c.settings.asamblea_virtual_enabled ? "bg-white shadow-sm text-primary" : "text-on-surface-variant/20 hover:text-on-surface-variant/40"]
							}, [createVNode("span", { class: "material-symbols-rounded text-lg" }, "videocam")], 10, ["onClick"])];
						}),
						_: 2
					}, _parent));
					_push(`</div></div><div class="flex items-center gap-3 pt-4 border-t border-outline-variant/10" data-v-35867bdc>`);
					_push(ssrRenderComponent(Button_default, {
						variant: "primary",
						size: "sm",
						class: "flex-1 !py-3 rounded-2xl shadow-brand/10 shadow-lg group/btn overflow-hidden relative",
						onClick: ($event) => unref(router).get(_ctx.route("superadmin.properties.manage", c.id))
					}, {
						default: withCtx((_, _push, _parent, _scopeId) => {
							if (_push) _push(`<span class="relative z-10" data-v-35867bdc${_scopeId}>Gestionar Propiedad</span><div class="absolute inset-x-0 bottom-0 h-0 group-hover/btn:h-full bg-white/5 transition-all duration-300" data-v-35867bdc${_scopeId}></div>`);
							else return [createVNode("span", { class: "relative z-10" }, "Gestionar Propiedad"), createVNode("div", { class: "absolute inset-x-0 bottom-0 h-0 group-hover/btn:h-full bg-white/5 transition-all duration-300" })];
						}),
						_: 2
					}, _parent));
					_push(`<div class="flex items-center gap-2" data-v-35867bdc>`);
					_push(ssrRenderComponent(Tooltip_default, { text: "Soporte Técnico (Impersonar)" }, {
						default: withCtx((_, _push, _parent, _scopeId) => {
							if (_push) _push(`<button class="w-11 h-11 rounded-2xl bg-surface-container-high border border-outline-variant/10 flex items-center justify-center text-on-surface-variant hover:bg-primary hover:text-white transition-all duration-300" data-v-35867bdc${_scopeId}><span class="material-symbols-rounded text-lg" data-v-35867bdc${_scopeId}>support_agent</span></button>`);
							else return [createVNode("button", {
								onClick: ($event) => impersonateAdmin(c),
								class: "w-11 h-11 rounded-2xl bg-surface-container-high border border-outline-variant/10 flex items-center justify-center text-on-surface-variant hover:bg-primary hover:text-white transition-all duration-300"
							}, [createVNode("span", { class: "material-symbols-rounded text-lg" }, "support_agent")], 8, ["onClick"])];
						}),
						_: 2
					}, _parent));
					_push(ssrRenderComponent(Tooltip_default, { text: "Usuarios" }, {
						default: withCtx((_, _push, _parent, _scopeId) => {
							if (_push) _push(`<button class="w-11 h-11 rounded-2xl bg-surface-container-high border border-outline-variant/10 flex items-center justify-center text-on-surface-variant hover:bg-secondary hover:text-white transition-all duration-300" data-v-35867bdc${_scopeId}><span class="material-symbols-rounded text-lg" data-v-35867bdc${_scopeId}>group</span></button>`);
							else return [createVNode("button", {
								onClick: ($event) => unref(router).get(_ctx.route("superadmin.users.index")),
								class: "w-11 h-11 rounded-2xl bg-surface-container-high border border-outline-variant/10 flex items-center justify-center text-on-surface-variant hover:bg-secondary hover:text-white transition-all duration-300"
							}, [createVNode("span", { class: "material-symbols-rounded text-lg" }, "group")], 8, ["onClick"])];
						}),
						_: 2
					}, _parent));
					_push(`</div></div></div><div class="h-1.5 w-full bg-primary/5 absolute bottom-0 left-0" data-v-35867bdc><div class="h-full bg-primary transition-all duration-1000" style="${ssrRenderStyle(`width: ${Math.min(100, c.health_score || 0)}%`)}" data-v-35867bdc></div></div></div>`);
				});
				_push(`<!--]--></div>`);
			} else {
				_push(`<div class="flex flex-col items-center justify-center py-32 space-y-6" data-v-35867bdc><div class="w-24 h-24 rounded-full bg-surface-container flex items-center justify-center text-on-surface-variant/20" data-v-35867bdc><span class="material-symbols-rounded text-5xl" data-v-35867bdc>search_off</span></div><div class="text-center" data-v-35867bdc><h3 class="text-xl font-black text-primary uppercase tracking-tighter" data-v-35867bdc>Sin resultados</h3><p class="text-xs font-bold text-on-surface-variant/40 uppercase tracking-widest mt-2" data-v-35867bdc>No encontramos copropiedades que coincidan con tu búsqueda</p></div>`);
				_push(ssrRenderComponent(Button_default, {
					variant: "outline",
					onClick: ($event) => {
						searchTerm.value = "";
						filterStatus.value = "all";
					}
				}, {
					default: withCtx((_, _push, _parent, _scopeId) => {
						if (_push) _push(`Limpiar Filtros`);
						else return [createTextVNode("Limpiar Filtros")];
					}),
					_: 1
				}, _parent));
				_push(`</div>`);
			}
			_push(ssrRenderComponent(ConfirmModal_default, {
				show: confirmState.value.show,
				title: confirmState.value.title,
				message: confirmState.value.message,
				"confirm-label": confirmState.value.confirmLabel,
				variant: confirmState.value.variant,
				onConfirm: executeConfirm,
				onCancel: ($event) => confirmState.value.show = false
			}, null, _parent));
			_push(`</div><!--]-->`);
		};
	}
});
//#endregion
//#region resources/js/Pages/SuperAdmin/Licenses/Index.vue
var _sfc_setup = Index_vue_vue_type_script_setup_true_lang_default.setup;
Index_vue_vue_type_script_setup_true_lang_default.setup = (props, ctx) => {
	const ssrContext = useSSRContext();
	(ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/SuperAdmin/Licenses/Index.vue");
	return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
var Index_default = /* @__PURE__ */ _plugin_vue_export_helper_default(Index_vue_vue_type_script_setup_true_lang_default, [["__scopeId", "data-v-35867bdc"]]);
//#endregion
export { Index_default as default };

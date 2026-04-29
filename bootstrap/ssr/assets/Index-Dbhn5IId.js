import { t as _plugin_vue_export_helper_default } from "./_plugin-vue_export-helper-BjJHhBxR.js";
import { t as Table_default } from "./Table-DmKwoFFU.js";
import { t as AuthenticatedLayout_default } from "./AuthenticatedLayout-vxulH9w9.js";
import { t as Card_default } from "./Card-D4IwBRMd.js";
import { t as Button_default } from "./Button-C-byZbTR.js";
import { t as Badge_default } from "./Badge-DsYztQFV.js";
import { t as Tooltip_default } from "./Tooltip-IAP-zsdE.js";
import { t as ConfirmModal_default } from "./ConfirmModal-BH-N6uDF.js";
import { createBlock, createCommentVNode, createTextVNode, createVNode, defineComponent, openBlock, ref, toDisplayString, unref, useSSRContext, watch, withCtx } from "vue";
import { Head, router } from "@inertiajs/vue3";
import { ssrInterpolate, ssrRenderAttr, ssrRenderClass, ssrRenderComponent } from "vue/server-renderer";
//#region resources/js/Pages/SuperAdmin/Users/Index.vue?vue&type=script&setup=true&lang.ts
var Index_vue_vue_type_script_setup_true_lang_default = /* @__PURE__ */ defineComponent({
	layout: AuthenticatedLayout_default,
	__name: "Index",
	__ssrInlineRender: true,
	props: {
		users: {},
		filters: {},
		stats: {}
	},
	setup(__props) {
		const props = __props;
		const tableColumns = [
			{
				key: "user",
				label: "Identidad del Usuario",
				sortable: false
			},
			{
				key: "seguridad",
				label: "Seguridad",
				sortable: false
			},
			{
				key: "role",
				label: "Rol / Cargo",
				sortable: false
			},
			{
				key: "property",
				label: "Contexto Propiedad",
				sortable: false
			},
			{
				key: "activity",
				label: "Última Actividad",
				sortable: false
			},
			{
				key: "actions",
				label: "Comandos",
				sortable: false
			}
		];
		const confirmState = ref({
			show: false,
			title: "",
			message: "",
			confirmLabel: "Confirmar",
			variant: "primary",
			action: null
		});
		const search = ref(props.filters.search || "");
		watch(search, (value) => {
			router.get(route("superadmin.users.index"), { search: value }, {
				preserveState: true,
				replace: true
			});
		});
		const getRoleConfig = (role) => {
			switch (role) {
				case "super_admin": return {
					label: "GLOBAL ADMIN",
					variant: "gradient",
					icon: "shield_person"
				};
				case "admin": return {
					label: "ADMINISTRADOR",
					variant: "primary",
					icon: "admin_panel_settings"
				};
				case "owner": return {
					label: "PROPIETARIO",
					variant: "neutral",
					icon: "home"
				};
				default: return {
					label: "RESIDENTE",
					variant: "neutral",
					icon: "person"
				};
			}
		};
		const impersonate = (user) => {
			confirmState.value = {
				show: true,
				title: "Abrir Sesión de Soporte",
				message: `Estás a punto de entrar en la cuenta de ${user.name}. Esta sesión es estrictamente para soporte y será auditada en los logs del sistema.`,
				confirmLabel: "Iniciar Soporte",
				variant: "primary",
				action: () => router.post(route("superadmin.impersonate", user.id))
			};
		};
		const toggleStatus = (user) => {
			confirmState.value = {
				show: true,
				title: user.is_active ? "Suspender Acceso" : "Activar Acceso",
				message: `¿Deseas ${user.is_active ? "suspender" : "reactivar"} el acceso a la plataforma para ${user.name}?`,
				confirmLabel: user.is_active ? "Suspender" : "Activar ahora",
				variant: user.is_active ? "error" : "primary",
				action: () => router.patch(route("superadmin.users.toggle-status", user.id))
			};
		};
		const reset2fa = (user) => {
			confirmState.value = {
				show: true,
				title: "Resetear Segundo Factor",
				message: `¿Estás seguro de que deseas forzar el reseteo del 2FA para ${user.name}? Perderá su vinculación con la App autenticadora.`,
				confirmLabel: "Resetear 2FA",
				variant: "warning",
				action: () => router.post(route("superadmin.users.reset-2fa", user.id))
			};
		};
		const executeConfirm = () => {
			if (confirmState.value.action) {
				confirmState.value.action();
				confirmState.value.show = false;
			}
		};
		return (_ctx, _push, _parent, _attrs) => {
			_push(`<!--[-->`);
			_push(ssrRenderComponent(unref(Head), { title: "Gestión de Usuarios — NEXO-PRO" }, null, _parent));
			_push(`<div class="space-y-10 pb-20 animate-in fade-in duration-700" data-v-28f52dcc><div class="flex flex-col md:flex-row md:items-end justify-between gap-8 px-1" data-v-28f52dcc><div data-v-28f52dcc><h2 class="text-4xl font-black text-primary tracking-tighter uppercase leading-none" data-v-28f52dcc>Gestión de Usuarios</h2><p class="text-[10px] font-bold text-on-surface-variant/40 uppercase tracking-[0.2em] mt-3" data-v-28f52dcc>Control táctico de identidades y seguridad 2FA</p></div><div class="grid grid-cols-2 lg:grid-cols-4 gap-4 w-full md:w-auto" data-v-28f52dcc><div class="bg-surface-container-low px-5 py-3 rounded-2xl border border-outline-variant/10 flex flex-col" data-v-28f52dcc><span class="text-[8px] font-black text-on-surface-variant/40 uppercase tracking-[0.2em]" data-v-28f52dcc>Total</span><span class="text-lg font-black text-primary" data-v-28f52dcc>— ${ssrInterpolate(__props.stats.total)}</span></div><div class="bg-surface-container-low px-5 py-3 rounded-2xl border border-outline-variant/10 flex flex-col" data-v-28f52dcc><span class="text-[8px] font-black text-emerald-600/40 uppercase tracking-[0.2em]" data-v-28f52dcc>Activos</span><span class="text-lg font-black text-emerald-600" data-v-28f52dcc>— ${ssrInterpolate(__props.stats.active)}</span></div><div class="bg-surface-container-low px-5 py-3 rounded-2xl border border-outline-variant/10 flex flex-col" data-v-28f52dcc><span class="text-[8px] font-black text-indigo-600/40 uppercase tracking-[0.2em]" data-v-28f52dcc>Admins</span><span class="text-lg font-black text-indigo-600" data-v-28f52dcc>— ${ssrInterpolate(__props.stats.admins)}</span></div><div class="bg-surface-container-low px-5 py-3 rounded-2xl border border-outline-variant/10 flex flex-col" data-v-28f52dcc><span class="text-[8px] font-black text-amber-600/40 uppercase tracking-[0.2em]" data-v-28f52dcc>2FA Activo</span><span class="text-lg font-black text-amber-600" data-v-28f52dcc>— ${ssrInterpolate(__props.stats.sec_enabled)}</span></div></div></div><div class="space-y-6" data-v-28f52dcc><div class="bg-surface-container-low p-6 rounded-[2.5rem] border border-outline-variant/10 flex items-center gap-6" data-v-28f52dcc><div class="flex-1 relative" data-v-28f52dcc><span class="material-symbols-rounded absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant/40" data-v-28f52dcc>search</span><input${ssrRenderAttr("value", search.value)} placeholder="Buscar por nombre, email o cargo..." class="w-full pl-12 pr-4 py-3 bg-white/50 border border-outline-variant/10 rounded-2xl text-xs font-bold focus:ring-2 focus:ring-primary/20 outline-none transition-all" data-v-28f52dcc></div>`);
			_push(ssrRenderComponent(Button_default, {
				variant: "primary",
				icon: "person_add",
				class: "!py-3 shadow-brand/10 shadow-lg"
			}, {
				default: withCtx((_, _push, _parent, _scopeId) => {
					if (_push) _push(`Nuevo Usuario`);
					else return [createTextVNode("Nuevo Usuario")];
				}),
				_: 1
			}, _parent));
			_push(`</div>`);
			_push(ssrRenderComponent(Card_default, { "content-class": "!p-0 overflow-hidden !rounded-[2.5rem]" }, {
				default: withCtx((_, _push, _parent, _scopeId) => {
					if (_push) _push(ssrRenderComponent(Table_default, {
						columns: tableColumns,
						data: __props.users.data
					}, {
						"cell-user": withCtx(({ row }, _push, _parent, _scopeId) => {
							if (_push) {
								_push(`<div class="flex items-center gap-4 py-1" data-v-28f52dcc${_scopeId}><div class="relative" data-v-28f52dcc${_scopeId}><img${ssrRenderAttr("src", row.avatar)} class="w-12 h-12 rounded-[1.25rem] bg-surface-container shadow-inner object-cover" data-v-28f52dcc${_scopeId}>`);
								if (row.is_active) _push(`<div class="absolute -bottom-1 -right-1 w-4 h-4 rounded-full bg-white flex items-center justify-center" data-v-28f52dcc${_scopeId}><div class="w-2 h-2 rounded-full bg-emerald-500" data-v-28f52dcc${_scopeId}></div></div>`);
								else _push(`<!---->`);
								_push(`</div><div class="flex flex-col" data-v-28f52dcc${_scopeId}><p class="text-xs font-black text-primary uppercase tracking-tighter leading-none" data-v-28f52dcc${_scopeId}>${ssrInterpolate(row.name)}</p><p class="text-[10px] font-medium text-on-surface-variant mt-1" data-v-28f52dcc${_scopeId}>${ssrInterpolate(row.email)}</p></div></div>`);
							} else return [createVNode("div", { class: "flex items-center gap-4 py-1" }, [createVNode("div", { class: "relative" }, [createVNode("img", {
								src: row.avatar,
								class: "w-12 h-12 rounded-[1.25rem] bg-surface-container shadow-inner object-cover"
							}, null, 8, ["src"]), row.is_active ? (openBlock(), createBlock("div", {
								key: 0,
								class: "absolute -bottom-1 -right-1 w-4 h-4 rounded-full bg-white flex items-center justify-center"
							}, [createVNode("div", { class: "w-2 h-2 rounded-full bg-emerald-500" })])) : createCommentVNode("", true)]), createVNode("div", { class: "flex flex-col" }, [createVNode("p", { class: "text-xs font-black text-primary uppercase tracking-tighter leading-none" }, toDisplayString(row.name), 1), createVNode("p", { class: "text-[10px] font-medium text-on-surface-variant mt-1" }, toDisplayString(row.email), 1)])])];
						}),
						"cell-seguridad": withCtx(({ row }, _push, _parent, _scopeId) => {
							if (_push) {
								_push(`<div class="flex items-center gap-2" data-v-28f52dcc${_scopeId}>`);
								_push(ssrRenderComponent(Tooltip_default, { text: row.has_2fa_enabled ? "Centa blindada con 2FA" : "Acceso vulnerable (Sin 2FA)" }, {
									default: withCtx((_, _push, _parent, _scopeId) => {
										if (_push) _push(`<div class="${ssrRenderClass([row.has_2fa_enabled ? "text-amber-600" : "text-on-surface-variant/20", "w-9 h-9 rounded-xl flex items-center justify-center transition-all bg-surface-container-high"])}" data-v-28f52dcc${_scopeId}><span class="material-symbols-rounded text-[20px]" data-v-28f52dcc${_scopeId}>${ssrInterpolate(row.has_2fa_enabled ? "verified_user" : "no_encryption")}</span></div>`);
										else return [createVNode("div", { class: ["w-9 h-9 rounded-xl flex items-center justify-center transition-all bg-surface-container-high", row.has_2fa_enabled ? "text-amber-600" : "text-on-surface-variant/20"] }, [createVNode("span", { class: "material-symbols-rounded text-[20px]" }, toDisplayString(row.has_2fa_enabled ? "verified_user" : "no_encryption"), 1)], 2)];
									}),
									_: 2
								}, _parent, _scopeId));
								_push(`<div class="flex flex-col" data-v-28f52dcc${_scopeId}><span class="${ssrRenderClass([row.has_2fa_enabled ? "text-amber-600" : "text-on-surface-variant/40", "text-[9px] font-black tracking-widest uppercase"])}" data-v-28f52dcc${_scopeId}>${ssrInterpolate(row.has_2fa_enabled ? "Seguro" : "Vulnerable")}</span></div></div>`);
							} else return [createVNode("div", { class: "flex items-center gap-2" }, [createVNode(Tooltip_default, { text: row.has_2fa_enabled ? "Centa blindada con 2FA" : "Acceso vulnerable (Sin 2FA)" }, {
								default: withCtx(() => [createVNode("div", { class: ["w-9 h-9 rounded-xl flex items-center justify-center transition-all bg-surface-container-high", row.has_2fa_enabled ? "text-amber-600" : "text-on-surface-variant/20"] }, [createVNode("span", { class: "material-symbols-rounded text-[20px]" }, toDisplayString(row.has_2fa_enabled ? "verified_user" : "no_encryption"), 1)], 2)]),
								_: 2
							}, 1032, ["text"]), createVNode("div", { class: "flex flex-col" }, [createVNode("span", { class: ["text-[9px] font-black tracking-widest uppercase", row.has_2fa_enabled ? "text-amber-600" : "text-on-surface-variant/40"] }, toDisplayString(row.has_2fa_enabled ? "Seguro" : "Vulnerable"), 3)])])];
						}),
						"cell-role": withCtx(({ row }, _push, _parent, _scopeId) => {
							if (_push) _push(ssrRenderComponent(Badge_default, {
								variant: getRoleConfig(row.role).variant,
								class: "!px-3 !py-1 !text-[9px] font-black uppercase tracking-widest flex items-center gap-1.5 w-fit"
							}, {
								default: withCtx((_, _push, _parent, _scopeId) => {
									if (_push) _push(`<span class="material-symbols-rounded text-[14px]" data-v-28f52dcc${_scopeId}>${ssrInterpolate(getRoleConfig(row.role).icon)}</span> ${ssrInterpolate(getRoleConfig(row.role).label)}`);
									else return [createVNode("span", { class: "material-symbols-rounded text-[14px]" }, toDisplayString(getRoleConfig(row.role).icon), 1), createTextVNode(" " + toDisplayString(getRoleConfig(row.role).label), 1)];
								}),
								_: 2
							}, _parent, _scopeId));
							else return [createVNode(Badge_default, {
								variant: getRoleConfig(row.role).variant,
								class: "!px-3 !py-1 !text-[9px] font-black uppercase tracking-widest flex items-center gap-1.5 w-fit"
							}, {
								default: withCtx(() => [createVNode("span", { class: "material-symbols-rounded text-[14px]" }, toDisplayString(getRoleConfig(row.role).icon), 1), createTextVNode(" " + toDisplayString(getRoleConfig(row.role).label), 1)]),
								_: 2
							}, 1032, ["variant"])];
						}),
						"cell-property": withCtx(({ row }, _push, _parent, _scopeId) => {
							if (_push) if (row.current_copropiedad) _push(`<div class="flex flex-col group/prop" data-v-28f52dcc${_scopeId}><p class="text-[10px] font-black text-primary uppercase tracking-tighter" data-v-28f52dcc${_scopeId}>${ssrInterpolate(row.current_copropiedad.nombre)}</p><p class="text-[9px] text-on-surface-variant/40 font-bold uppercase mt-0.5" data-v-28f52dcc${_scopeId}>${ssrInterpolate(row.current_copropiedad.ciudad)}</p></div>`);
							else _push(ssrRenderComponent(Badge_default, {
								variant: "neutral",
								class: "!text-[8px] !opacity-30"
							}, {
								default: withCtx((_, _push, _parent, _scopeId) => {
									if (_push) _push(`SIN ASOCIACIÓN`);
									else return [createTextVNode("SIN ASOCIACIÓN")];
								}),
								_: 2
							}, _parent, _scopeId));
							else return [row.current_copropiedad ? (openBlock(), createBlock("div", {
								key: 0,
								class: "flex flex-col group/prop"
							}, [createVNode("p", { class: "text-[10px] font-black text-primary uppercase tracking-tighter" }, toDisplayString(row.current_copropiedad.nombre), 1), createVNode("p", { class: "text-[9px] text-on-surface-variant/40 font-bold uppercase mt-0.5" }, toDisplayString(row.current_copropiedad.ciudad), 1)])) : (openBlock(), createBlock(Badge_default, {
								key: 1,
								variant: "neutral",
								class: "!text-[8px] !opacity-30"
							}, {
								default: withCtx(() => [createTextVNode("SIN ASOCIACIÓN")]),
								_: 1
							}))];
						}),
						"cell-activity": withCtx(({ row }, _push, _parent, _scopeId) => {
							if (_push) _push(`<div class="flex flex-col" data-v-28f52dcc${_scopeId}><p class="text-[10px] font-black text-on-surface tracking-tighter" data-v-28f52dcc${_scopeId}>Último Acceso</p><p class="text-[9px] font-bold text-on-surface-variant/50 uppercase mt-0.5 tracking-widest" data-v-28f52dcc${_scopeId}>${ssrInterpolate(row.last_login_at_human)}</p></div>`);
							else return [createVNode("div", { class: "flex flex-col" }, [createVNode("p", { class: "text-[10px] font-black text-on-surface tracking-tighter" }, "Último Acceso"), createVNode("p", { class: "text-[9px] font-bold text-on-surface-variant/50 uppercase mt-0.5 tracking-widest" }, toDisplayString(row.last_login_at_human), 1)])];
						}),
						"cell-actions": withCtx(({ row }, _push, _parent, _scopeId) => {
							if (_push) {
								_push(`<div class="flex items-center justify-end gap-2 pr-4" data-v-28f52dcc${_scopeId}>`);
								_push(ssrRenderComponent(Tooltip_default, { text: "Soporte (Login como él)" }, {
									default: withCtx((_, _push, _parent, _scopeId) => {
										if (_push) _push(ssrRenderComponent(Button_default, {
											onClick: ($event) => impersonate(row),
											class: "!w-10 !h-10 !rounded-2xl !p-0",
											variant: "secondary",
											icon: "support_agent"
										}, null, _parent, _scopeId));
										else return [createVNode(Button_default, {
											onClick: ($event) => impersonate(row),
											class: "!w-10 !h-10 !rounded-2xl !p-0",
											variant: "secondary",
											icon: "support_agent"
										}, null, 8, ["onClick"])];
									}),
									_: 2
								}, _parent, _scopeId));
								_push(ssrRenderComponent(Tooltip_default, { text: row.is_active ? "Suspender Cuenta" : "Activar Cuenta" }, {
									default: withCtx((_, _push, _parent, _scopeId) => {
										if (_push) _push(ssrRenderComponent(Button_default, {
											onClick: ($event) => toggleStatus(row),
											class: "!w-10 !h-10 !rounded-2xl !p-0",
											variant: row.is_active ? "secondary" : "error",
											icon: row.is_active ? "do_not_disturb_on" : "check_circle"
										}, null, _parent, _scopeId));
										else return [createVNode(Button_default, {
											onClick: ($event) => toggleStatus(row),
											class: "!w-10 !h-10 !rounded-2xl !p-0",
											variant: row.is_active ? "secondary" : "error",
											icon: row.is_active ? "do_not_disturb_on" : "check_circle"
										}, null, 8, [
											"onClick",
											"variant",
											"icon"
										])];
									}),
									_: 2
								}, _parent, _scopeId));
								if (row.has_2fa_enabled) _push(ssrRenderComponent(Tooltip_default, { text: "Forzar Reset de 2FA" }, {
									default: withCtx((_, _push, _parent, _scopeId) => {
										if (_push) _push(ssrRenderComponent(Button_default, {
											onClick: ($event) => reset2fa(row),
											class: "!w-10 !h-10 !rounded-2xl !p-0",
											variant: "secondary",
											icon: "lock_reset"
										}, null, _parent, _scopeId));
										else return [createVNode(Button_default, {
											onClick: ($event) => reset2fa(row),
											class: "!w-10 !h-10 !rounded-2xl !p-0",
											variant: "secondary",
											icon: "lock_reset"
										}, null, 8, ["onClick"])];
									}),
									_: 2
								}, _parent, _scopeId));
								else _push(`<!---->`);
								_push(`</div>`);
							} else return [createVNode("div", { class: "flex items-center justify-end gap-2 pr-4" }, [
								createVNode(Tooltip_default, { text: "Soporte (Login como él)" }, {
									default: withCtx(() => [createVNode(Button_default, {
										onClick: ($event) => impersonate(row),
										class: "!w-10 !h-10 !rounded-2xl !p-0",
										variant: "secondary",
										icon: "support_agent"
									}, null, 8, ["onClick"])]),
									_: 2
								}, 1024),
								createVNode(Tooltip_default, { text: row.is_active ? "Suspender Cuenta" : "Activar Cuenta" }, {
									default: withCtx(() => [createVNode(Button_default, {
										onClick: ($event) => toggleStatus(row),
										class: "!w-10 !h-10 !rounded-2xl !p-0",
										variant: row.is_active ? "secondary" : "error",
										icon: row.is_active ? "do_not_disturb_on" : "check_circle"
									}, null, 8, [
										"onClick",
										"variant",
										"icon"
									])]),
									_: 2
								}, 1032, ["text"]),
								row.has_2fa_enabled ? (openBlock(), createBlock(Tooltip_default, {
									key: 0,
									text: "Forzar Reset de 2FA"
								}, {
									default: withCtx(() => [createVNode(Button_default, {
										onClick: ($event) => reset2fa(row),
										class: "!w-10 !h-10 !rounded-2xl !p-0",
										variant: "secondary",
										icon: "lock_reset"
									}, null, 8, ["onClick"])]),
									_: 2
								}, 1024)) : createCommentVNode("", true)
							])];
						}),
						_: 1
					}, _parent, _scopeId));
					else return [createVNode(Table_default, {
						columns: tableColumns,
						data: __props.users.data
					}, {
						"cell-user": withCtx(({ row }) => [createVNode("div", { class: "flex items-center gap-4 py-1" }, [createVNode("div", { class: "relative" }, [createVNode("img", {
							src: row.avatar,
							class: "w-12 h-12 rounded-[1.25rem] bg-surface-container shadow-inner object-cover"
						}, null, 8, ["src"]), row.is_active ? (openBlock(), createBlock("div", {
							key: 0,
							class: "absolute -bottom-1 -right-1 w-4 h-4 rounded-full bg-white flex items-center justify-center"
						}, [createVNode("div", { class: "w-2 h-2 rounded-full bg-emerald-500" })])) : createCommentVNode("", true)]), createVNode("div", { class: "flex flex-col" }, [createVNode("p", { class: "text-xs font-black text-primary uppercase tracking-tighter leading-none" }, toDisplayString(row.name), 1), createVNode("p", { class: "text-[10px] font-medium text-on-surface-variant mt-1" }, toDisplayString(row.email), 1)])])]),
						"cell-seguridad": withCtx(({ row }) => [createVNode("div", { class: "flex items-center gap-2" }, [createVNode(Tooltip_default, { text: row.has_2fa_enabled ? "Centa blindada con 2FA" : "Acceso vulnerable (Sin 2FA)" }, {
							default: withCtx(() => [createVNode("div", { class: ["w-9 h-9 rounded-xl flex items-center justify-center transition-all bg-surface-container-high", row.has_2fa_enabled ? "text-amber-600" : "text-on-surface-variant/20"] }, [createVNode("span", { class: "material-symbols-rounded text-[20px]" }, toDisplayString(row.has_2fa_enabled ? "verified_user" : "no_encryption"), 1)], 2)]),
							_: 2
						}, 1032, ["text"]), createVNode("div", { class: "flex flex-col" }, [createVNode("span", { class: ["text-[9px] font-black tracking-widest uppercase", row.has_2fa_enabled ? "text-amber-600" : "text-on-surface-variant/40"] }, toDisplayString(row.has_2fa_enabled ? "Seguro" : "Vulnerable"), 3)])])]),
						"cell-role": withCtx(({ row }) => [createVNode(Badge_default, {
							variant: getRoleConfig(row.role).variant,
							class: "!px-3 !py-1 !text-[9px] font-black uppercase tracking-widest flex items-center gap-1.5 w-fit"
						}, {
							default: withCtx(() => [createVNode("span", { class: "material-symbols-rounded text-[14px]" }, toDisplayString(getRoleConfig(row.role).icon), 1), createTextVNode(" " + toDisplayString(getRoleConfig(row.role).label), 1)]),
							_: 2
						}, 1032, ["variant"])]),
						"cell-property": withCtx(({ row }) => [row.current_copropiedad ? (openBlock(), createBlock("div", {
							key: 0,
							class: "flex flex-col group/prop"
						}, [createVNode("p", { class: "text-[10px] font-black text-primary uppercase tracking-tighter" }, toDisplayString(row.current_copropiedad.nombre), 1), createVNode("p", { class: "text-[9px] text-on-surface-variant/40 font-bold uppercase mt-0.5" }, toDisplayString(row.current_copropiedad.ciudad), 1)])) : (openBlock(), createBlock(Badge_default, {
							key: 1,
							variant: "neutral",
							class: "!text-[8px] !opacity-30"
						}, {
							default: withCtx(() => [createTextVNode("SIN ASOCIACIÓN")]),
							_: 1
						}))]),
						"cell-activity": withCtx(({ row }) => [createVNode("div", { class: "flex flex-col" }, [createVNode("p", { class: "text-[10px] font-black text-on-surface tracking-tighter" }, "Último Acceso"), createVNode("p", { class: "text-[9px] font-bold text-on-surface-variant/50 uppercase mt-0.5 tracking-widest" }, toDisplayString(row.last_login_at_human), 1)])]),
						"cell-actions": withCtx(({ row }) => [createVNode("div", { class: "flex items-center justify-end gap-2 pr-4" }, [
							createVNode(Tooltip_default, { text: "Soporte (Login como él)" }, {
								default: withCtx(() => [createVNode(Button_default, {
									onClick: ($event) => impersonate(row),
									class: "!w-10 !h-10 !rounded-2xl !p-0",
									variant: "secondary",
									icon: "support_agent"
								}, null, 8, ["onClick"])]),
								_: 2
							}, 1024),
							createVNode(Tooltip_default, { text: row.is_active ? "Suspender Cuenta" : "Activar Cuenta" }, {
								default: withCtx(() => [createVNode(Button_default, {
									onClick: ($event) => toggleStatus(row),
									class: "!w-10 !h-10 !rounded-2xl !p-0",
									variant: row.is_active ? "secondary" : "error",
									icon: row.is_active ? "do_not_disturb_on" : "check_circle"
								}, null, 8, [
									"onClick",
									"variant",
									"icon"
								])]),
								_: 2
							}, 1032, ["text"]),
							row.has_2fa_enabled ? (openBlock(), createBlock(Tooltip_default, {
								key: 0,
								text: "Forzar Reset de 2FA"
							}, {
								default: withCtx(() => [createVNode(Button_default, {
									onClick: ($event) => reset2fa(row),
									class: "!w-10 !h-10 !rounded-2xl !p-0",
									variant: "secondary",
									icon: "lock_reset"
								}, null, 8, ["onClick"])]),
								_: 2
							}, 1024)) : createCommentVNode("", true)
						])]),
						_: 1
					}, 8, ["data"])];
				}),
				_: 1
			}, _parent));
			_push(`</div></div>`);
			_push(ssrRenderComponent(ConfirmModal_default, {
				show: confirmState.value.show,
				title: confirmState.value.title,
				message: confirmState.value.message,
				"confirm-label": confirmState.value.confirmLabel,
				variant: confirmState.value.variant,
				onConfirm: executeConfirm,
				onCancel: ($event) => confirmState.value.show = false
			}, null, _parent));
			_push(`<!--]-->`);
		};
	}
});
//#endregion
//#region resources/js/Pages/SuperAdmin/Users/Index.vue
var _sfc_setup = Index_vue_vue_type_script_setup_true_lang_default.setup;
Index_vue_vue_type_script_setup_true_lang_default.setup = (props, ctx) => {
	const ssrContext = useSSRContext();
	(ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/SuperAdmin/Users/Index.vue");
	return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
var Index_default = /* @__PURE__ */ _plugin_vue_export_helper_default(Index_vue_vue_type_script_setup_true_lang_default, [["__scopeId", "data-v-28f52dcc"]]);
//#endregion
export { Index_default as default };

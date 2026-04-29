import { t as _plugin_vue_export_helper_default } from "./_plugin-vue_export-helper-BjJHhBxR.js";
import { t as ThemeToggle_default } from "./ThemeToggle-DCsnwAEm.js";
import { t as Logo_default } from "./Logo-D89dXaWr.js";
import "axios";
import { computed, createBlock, createCommentVNode, createTextVNode, createVNode, defineComponent, mergeProps, onMounted, openBlock, ref, toDisplayString, unref, useSSRContext, withCtx } from "vue";
import { Link, router, usePage } from "@inertiajs/vue3";
import { ssrIncludeBooleanAttr, ssrInterpolate, ssrRenderAttr, ssrRenderAttrs, ssrRenderClass, ssrRenderComponent, ssrRenderList, ssrRenderSlot } from "vue/server-renderer";
//#region resources/js/Components/Announcements/AnnouncementModal.vue?vue&type=script&setup=true&lang.ts
var AnnouncementModal_vue_vue_type_script_setup_true_lang_default = /* @__PURE__ */ defineComponent({
	__name: "AnnouncementModal",
	__ssrInlineRender: true,
	setup(__props) {
		const announcements = ref(usePage().props.announcements || []);
		const showModal = ref(false);
		const activeIndex = ref(0);
		onMounted(() => {
			if (announcements.value.filter((a) => !a.is_read).length > 0) showModal.value = true;
		});
		const currentAnnouncement = () => announcements.value[activeIndex.value];
		return (_ctx, _push, _parent, _attrs) => {
			if (showModal.value && currentAnnouncement()) {
				_push(`<div${ssrRenderAttrs(mergeProps({ class: "fixed inset-0 z-[100] flex items-center justify-center p-4 bg-on-surface/40 backdrop-blur-sm" }, _attrs))} data-v-c9821e3d><div class="bg-surface w-full max-w-lg rounded-3xl shadow-2xl overflow-hidden border border-outline-variant/30 animate-in zoom-in-95 duration-300" data-v-c9821e3d><div class="${ssrRenderClass([{
					"bg-primary": currentAnnouncement().type === "info",
					"bg-warning": currentAnnouncement().type === "warning",
					"bg-error": currentAnnouncement().type === "danger" || currentAnnouncement().type === "error"
				}, "h-2 w-full"])}" data-v-c9821e3d></div><div class="p-8" data-v-c9821e3d><div class="flex items-start justify-between mb-6" data-v-c9821e3d><div class="flex items-center gap-3" data-v-c9821e3d><div class="${ssrRenderClass([{
					"bg-primary/10 text-primary": currentAnnouncement().type === "info",
					"bg-warning/10 text-warning": currentAnnouncement().type === "warning",
					"bg-error/10 text-error": currentAnnouncement().type === "danger" || currentAnnouncement().type === "error"
				}, "w-12 h-12 rounded-2xl flex items-center justify-center"])}" data-v-c9821e3d><span class="material-symbols-rounded text-2xl" data-v-c9821e3d>${ssrInterpolate(currentAnnouncement().type === "danger" || currentAnnouncement().type === "error" ? "report" : currentAnnouncement().type === "warning" ? "warning" : "campaign")}</span></div><div data-v-c9821e3d><p class="text-[10px] font-black uppercase tracking-widest text-on-surface-variant/50 leading-none mb-1" data-v-c9821e3d>Notificación Importante</p><h3 class="text-xl font-black text-on-surface leading-tight tracking-tight" data-v-c9821e3d>${ssrInterpolate(currentAnnouncement().title)}</h3></div></div><button class="text-on-surface-variant/40 hover:text-on-surface transition-colors" data-v-c9821e3d><span class="material-symbols-rounded" data-v-c9821e3d>close</span></button></div><div class="bg-surface-container/50 p-6 rounded-2xl mb-8 border border-outline-variant/10" data-v-c9821e3d><p class="text-sm text-on-surface-variant font-medium leading-relaxed whitespace-pre-wrap" data-v-c9821e3d>${ssrInterpolate(currentAnnouncement().message)}</p></div><div class="flex items-center justify-between" data-v-c9821e3d><div class="flex gap-2" data-v-c9821e3d>`);
				if (announcements.value.length > 1) _push(`<div class="flex items-center gap-1" data-v-c9821e3d><button${ssrIncludeBooleanAttr(activeIndex.value === 0) ? " disabled" : ""} class="w-8 h-8 rounded-full flex items-center justify-center border border-outline-variant/30 disabled:opacity-20" data-v-c9821e3d><span class="material-symbols-rounded" data-v-c9821e3d>chevron_left</span></button><span class="text-[10px] font-black uppercase tracking-widest text-on-surface-variant/40" data-v-c9821e3d>${ssrInterpolate(activeIndex.value + 1)} / ${ssrInterpolate(announcements.value.length)}</span><button${ssrIncludeBooleanAttr(activeIndex.value === announcements.value.length - 1) ? " disabled" : ""} class="w-8 h-8 rounded-full flex items-center justify-center border border-outline-variant/30 disabled:opacity-20" data-v-c9821e3d><span class="material-symbols-rounded" data-v-c9821e3d>chevron_right</span></button></div>`);
				else _push(`<!---->`);
				_push(`</div><button class="bg-on-surface text-surface px-6 py-3 rounded-2xl font-black text-xs uppercase tracking-widest hover:scale-105 active:scale-95 transition-all shadow-lg" data-v-c9821e3d> Entendido </button></div></div></div></div>`);
			} else _push(`<!---->`);
		};
	}
});
//#endregion
//#region resources/js/Components/Announcements/AnnouncementModal.vue
var _sfc_setup$2 = AnnouncementModal_vue_vue_type_script_setup_true_lang_default.setup;
AnnouncementModal_vue_vue_type_script_setup_true_lang_default.setup = (props, ctx) => {
	const ssrContext = useSSRContext();
	(ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/Announcements/AnnouncementModal.vue");
	return _sfc_setup$2 ? _sfc_setup$2(props, ctx) : void 0;
};
var AnnouncementModal_default = /* @__PURE__ */ _plugin_vue_export_helper_default(AnnouncementModal_vue_vue_type_script_setup_true_lang_default, [["__scopeId", "data-v-c9821e3d"]]);
//#endregion
//#region resources/js/Components/Nav/NotificationCenter.vue?vue&type=script&setup=true&lang.ts
var NotificationCenter_vue_vue_type_script_setup_true_lang_default = /* @__PURE__ */ defineComponent({
	__name: "NotificationCenter",
	__ssrInlineRender: true,
	setup(__props) {
		const page = usePage();
		const announcements = computed(() => page.props.announcements || []);
		const unreadCount = computed(() => announcements.value.filter((a) => !a.is_read).length);
		const isOpen = ref(false);
		const formatDate = (date) => {
			return new Date(date).toLocaleDateString("es-CO", {
				day: "numeric",
				month: "short"
			});
		};
		return (_ctx, _push, _parent, _attrs) => {
			_push(`<div${ssrRenderAttrs(mergeProps({ class: "relative" }, _attrs))} data-v-a7e20ff4><button class="${ssrRenderClass([{ "ring-2 ring-primary bg-primary/5": isOpen.value }, "w-10 h-10 rounded-2xl flex items-center justify-center transition-all bg-surface hover:bg-surface-container border border-outline-variant/10 relative"])}" data-v-a7e20ff4><span class="material-symbols-rounded text-on-surface-variant" data-v-a7e20ff4>notifications</span>`);
			if (unreadCount.value > 0) _push(`<span class="absolute -top-1 -right-1 w-5 h-5 bg-error text-white text-[10px] font-black rounded-full flex items-center justify-center border-2 border-surface animate-bounce" data-v-a7e20ff4>${ssrInterpolate(unreadCount.value)}</span>`);
			else _push(`<!---->`);
			_push(`</button>`);
			if (isOpen.value) {
				_push(`<div class="absolute right-0 mt-3 w-80 bg-surface rounded-3xl shadow-2xl border border-outline-variant/20 overflow-hidden z-50" data-v-a7e20ff4><div class="p-4 bg-surface-container/30 border-b border-outline-variant/10 flex justify-between items-center" data-v-a7e20ff4><h4 class="text-xs font-black uppercase tracking-widest text-primary" data-v-a7e20ff4>Notificaciones</h4><span class="text-[10px] font-bold text-on-surface-variant/50" data-v-a7e20ff4>${ssrInterpolate(unreadCount.value)} pendientes</span></div><div class="max-h-96 overflow-y-auto no-scrollbar" data-v-a7e20ff4>`);
				if (announcements.value.length === 0) _push(`<div class="p-12 text-center" data-v-a7e20ff4><span class="material-symbols-rounded text-4xl text-on-surface-variant/20 mb-2" data-v-a7e20ff4>notifications_off</span><p class="text-[10px] font-black uppercase tracking-widest text-on-surface-variant/40" data-v-a7e20ff4>Sin avisos recientes</p></div>`);
				else _push(`<!---->`);
				_push(`<!--[-->`);
				ssrRenderList(announcements.value, (ann) => {
					_push(`<div class="${ssrRenderClass([{ "bg-surface-container-low/30": !ann.is_read }, "p-4 border-b border-outline-variant/5 hover:bg-primary/5 transition-colors cursor-pointer group"])}" data-v-a7e20ff4><div class="flex gap-3" data-v-a7e20ff4><div class="${ssrRenderClass([{
						"bg-primary/10 text-primary": ann.type === "info",
						"bg-warning/10 text-warning": ann.type === "warning",
						"bg-error/10 text-error": ann.type === "error" || ann.type === "danger"
					}, "w-8 h-8 rounded-xl flex items-center justify-center flex-shrink-0"])}" data-v-a7e20ff4><span class="material-symbols-rounded text-lg" data-v-a7e20ff4>${ssrInterpolate(ann.type === "error" || ann.type === "danger" ? "report" : ann.type === "warning" ? "warning" : "info")}</span></div><div class="flex-1 overflow-hidden" data-v-a7e20ff4><div class="flex justify-between items-start mb-1" data-v-a7e20ff4><p class="${ssrRenderClass([ann.is_read ? "text-on-surface-variant/50" : "text-on-surface", "text-[10px] font-black uppercase tracking-tighter truncate pr-2"])}" data-v-a7e20ff4>${ssrInterpolate(ann.title)}</p><span class="text-[9px] font-bold text-on-surface-variant/40" data-v-a7e20ff4>${ssrInterpolate(formatDate(ann.created_at))}</span></div><p class="text-xs font-medium text-on-surface-variant line-clamp-2 leading-tight" data-v-a7e20ff4>${ssrInterpolate(ann.message)}</p></div></div></div>`);
				});
				_push(`<!--]--></div><div class="p-3 bg-surface-container/30 text-center" data-v-a7e20ff4><button class="text-[9px] font-black uppercase tracking-widest text-on-surface-variant/60 hover:text-primary transition-colors" data-v-a7e20ff4> Cerrar Panel </button></div></div>`);
			} else _push(`<!---->`);
			_push(`</div>`);
		};
	}
});
//#endregion
//#region resources/js/Components/Nav/NotificationCenter.vue
var _sfc_setup$1 = NotificationCenter_vue_vue_type_script_setup_true_lang_default.setup;
NotificationCenter_vue_vue_type_script_setup_true_lang_default.setup = (props, ctx) => {
	const ssrContext = useSSRContext();
	(ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/Nav/NotificationCenter.vue");
	return _sfc_setup$1 ? _sfc_setup$1(props, ctx) : void 0;
};
var NotificationCenter_default = /* @__PURE__ */ _plugin_vue_export_helper_default(NotificationCenter_vue_vue_type_script_setup_true_lang_default, [["__scopeId", "data-v-a7e20ff4"]]);
//#endregion
//#region resources/js/Layouts/AuthenticatedLayout.vue?vue&type=script&setup=true&lang.ts
var AuthenticatedLayout_vue_vue_type_script_setup_true_lang_default = /* @__PURE__ */ defineComponent({
	__name: "AuthenticatedLayout",
	__ssrInlineRender: true,
	setup(__props) {
		const page = usePage();
		const user = computed(() => page.props.auth?.user);
		const isSidebarOpen = ref(true);
		const showUserMenu = ref(false);
		const isLargeScreen = ref(true);
		onMounted(() => {
			isLargeScreen.value = window.innerWidth >= 1024;
			isSidebarOpen.value = isLargeScreen.value;
			window.addEventListener("resize", () => {
				isLargeScreen.value = window.innerWidth >= 1024;
				if (isLargeScreen.value) isSidebarOpen.value = true;
			});
		});
		router.on("navigate", () => {
			if (!isLargeScreen.value) isSidebarOpen.value = false;
		});
		const isRouteActive = (name) => {
			try {
				return route().current(name);
			} catch (e) {
				return false;
			}
		};
		const navigationItems = computed(() => {
			if (user.value?.role === "super_admin") return [
				{
					name: "Inicio Global",
					icon: "monitoring",
					href: route("superadmin.dashboard"),
					active: isRouteActive("superadmin.dashboard")
				},
				{
					name: "Copropiedades",
					icon: "business",
					href: route("superadmin.licenses.index"),
					active: isRouteActive("superadmin.licenses.*")
				},
				{
					name: "Usuarios",
					icon: "group",
					href: route("superadmin.users.index"),
					active: isRouteActive("superadmin.users.*")
				},
				{
					name: "Auditoría",
					icon: "history",
					href: route("superadmin.audit"),
					active: isRouteActive("superadmin.audit")
				},
				{
					name: "Logs Sistema",
					icon: "monitor_heart",
					href: route("superadmin.logs"),
					active: isRouteActive("superadmin.logs")
				},
				{
					name: "Anuncios",
					icon: "campaign",
					href: route("superadmin.announcements.index"),
					active: isRouteActive("superadmin.announcements.*")
				},
				{
					name: "C. Soporte",
					icon: "support_agent",
					href: route("superadmin.support.index"),
					active: isRouteActive("superadmin.support.*")
				},
				{
					name: "Configuración Global",
					icon: "settings_suggest",
					href: route("superadmin.settings.index"),
					active: isRouteActive("superadmin.settings.*")
				}
			];
			const items = [
				{
					name: "Inicio",
					icon: "dashboard",
					href: route("dashboard"),
					active: isRouteActive("dashboard")
				},
				{
					name: "Cartera",
					icon: "payments",
					href: route("cartera.index"),
					active: isRouteActive("cartera.*")
				},
				{
					name: "Zonas Comunes",
					icon: "home_work",
					href: route("admin.zonas.index"),
					active: isRouteActive("admin.zonas.*")
				},
				{
					name: "PQRS",
					icon: "forum",
					href: route("pqrs.index"),
					active: isRouteActive("pqrs.index")
				},
				{
					name: "Reservas",
					icon: "event_available",
					href: route("admin.reservas.index"),
					active: isRouteActive("admin.reservas.index")
				}
			];
			if (user.value?.role === "admin") {
				const settings = user.value.copropiedad_settings;
				if (settings && (settings.asamblea_virtual_enabled == 1 || settings.asamblea_virtual_enabled === true)) items.push({
					name: "Asambleas",
					icon: "groups",
					href: route("admin.asambleas.index"),
					active: isRouteActive("admin.asambleas.*")
				});
			}
			items.push({
				name: "Configuración",
				icon: "settings",
				href: route("admin.settings"),
				active: isRouteActive("admin.settings")
			});
			return items;
		});
		const configItems = computed(() => {
			const items = [{
				name: "Mi Perfil",
				icon: "manage_accounts",
				href: route("profile.edit"),
				active: isRouteActive("profile.edit")
			}];
			if (user.value?.role !== "super_admin") items.push({
				name: "Soporte",
				icon: "contact_support",
				href: route("support.index"),
				active: isRouteActive("support.index")
			});
			return items;
		});
		const twoFactorStatus = computed(() => {
			return !!user.value?.two_factor_confirmed_at;
		});
		return (_ctx, _push, _parent, _attrs) => {
			_push(`<div${ssrRenderAttrs(mergeProps({ class: "min-h-screen bg-surface flex text-on-surface" }, _attrs))}>`);
			if (user.value?.is_impersonating) {
				_push(`<div class="fixed top-0 inset-x-0 z-[100] bg-error text-on-error px-4 py-2 flex items-center justify-center gap-4 shadow-xl"><span class="material-symbols-rounded text-sm animate-pulse">support_agent</span><p class="text-[10px] font-black uppercase tracking-[0.2em]">Modo Soporte: Navegando como ${ssrInterpolate(user.value.name)}</p>`);
				_push(ssrRenderComponent(unref(Link), {
					href: _ctx.route("superadmin.impersonate.stop"),
					method: "post",
					as: "button",
					class: "bg-white/20 hover:bg-white/30 px-4 py-1 rounded-full text-[9px] font-black uppercase tracking-widest transition-all"
				}, {
					default: withCtx((_, _push, _parent, _scopeId) => {
						if (_push) _push(`Regresar a Super Admin`);
						else return [createTextVNode("Regresar a Super Admin")];
					}),
					_: 1
				}, _parent));
				_push(`</div>`);
			} else _push(`<!---->`);
			_push(`<aside class="${ssrRenderClass([[isSidebarOpen.value ? "w-72 translate-x-0" : "w-24 -translate-x-full lg:translate-x-0", !isSidebarOpen.value && "lg:w-24"], "fixed inset-y-0 left-0 bg-surface-container-low dark:bg-[#0b0e14]/95 backdrop-blur-3xl border-r border-outline-variant/10 flex flex-col transition-all duration-500 z-[70] no-scrollbar shadow-2xl lg:translate-x-0"])}"><div class="px-6 py-12 flex items-center justify-center">`);
			_push(ssrRenderComponent(unref(Link), {
				href: _ctx.route("dashboard"),
				class: "flex items-center gap-3"
			}, {
				default: withCtx((_, _push, _parent, _scopeId) => {
					if (_push) _push(ssrRenderComponent(Logo_default, {
						width: isSidebarOpen.value ? "45px" : "35px",
						height: isSidebarOpen.value ? "45px" : "35px",
						"show-text": isSidebarOpen.value,
						class: "transition-all duration-500"
					}, null, _parent, _scopeId));
					else return [createVNode(Logo_default, {
						width: isSidebarOpen.value ? "45px" : "35px",
						height: isSidebarOpen.value ? "45px" : "35px",
						"show-text": isSidebarOpen.value,
						class: "transition-all duration-500"
					}, null, 8, [
						"width",
						"height",
						"show-text"
					])];
				}),
				_: 1
			}, _parent));
			_push(`</div><nav class="flex-1 px-4 space-y-1"><!--[-->`);
			ssrRenderList(navigationItems.value, (item) => {
				_push(ssrRenderComponent(unref(Link), {
					key: item.name,
					href: item.href,
					class: ["flex items-center gap-4 py-4 px-5 rounded-[1.5rem] transition-all group relative overflow-hidden", [item.active ? "bg-primary text-white shadow-xl shadow-primary/20 translate-x-1" : "text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface"]]
				}, {
					default: withCtx((_, _push, _parent, _scopeId) => {
						if (_push) {
							if (item.active) _push(`<div class="absolute left-0 top-1/4 bottom-1/4 w-1 bg-white rounded-full"${_scopeId}></div>`);
							else _push(`<!---->`);
							_push(`<span class="${ssrRenderClass([[item.active ? "text-white" : "text-on-surface-variant/50 group-hover:text-primary"], "material-symbols-rounded shrink-0 transition-transform group-hover:scale-110"])}"${_scopeId}>${ssrInterpolate(item.icon)}</span>`);
							if (isSidebarOpen.value) _push(`<span class="text-[10px] font-black uppercase tracking-[0.2em] whitespace-nowrap"${_scopeId}>${ssrInterpolate(item.name)}</span>`);
							else _push(`<!---->`);
						} else return [
							item.active ? (openBlock(), createBlock("div", {
								key: 0,
								class: "absolute left-0 top-1/4 bottom-1/4 w-1 bg-white rounded-full"
							})) : createCommentVNode("", true),
							createVNode("span", { class: ["material-symbols-rounded shrink-0 transition-transform group-hover:scale-110", [item.active ? "text-white" : "text-on-surface-variant/50 group-hover:text-primary"]] }, toDisplayString(item.icon), 3),
							isSidebarOpen.value ? (openBlock(), createBlock("span", {
								key: 1,
								class: "text-[10px] font-black uppercase tracking-[0.2em] whitespace-nowrap"
							}, toDisplayString(item.name), 1)) : createCommentVNode("", true)
						];
					}),
					_: 2
				}, _parent));
			});
			_push(`<!--]--></nav><div class="px-4 border-t border-outline-variant/5 py-8 space-y-1"><!--[-->`);
			ssrRenderList(configItems.value, (item) => {
				_push(ssrRenderComponent(unref(Link), {
					key: item.name,
					href: item.href,
					class: ["flex items-center gap-4 py-4 px-5 rounded-2xl transition-all group", [item.active ? "bg-primary/5 text-primary" : "text-on-surface-variant/50 hover:bg-surface-container-high hover:text-on-surface"]]
				}, {
					default: withCtx((_, _push, _parent, _scopeId) => {
						if (_push) {
							_push(`<span class="material-symbols-rounded shrink-0 text-xl group-hover:text-primary transition-colors text-lg"${_scopeId}>${ssrInterpolate(item.icon)}</span>`);
							if (isSidebarOpen.value) _push(`<span class="text-[10px] font-black uppercase tracking-widest"${_scopeId}>${ssrInterpolate(item.name)}</span>`);
							else _push(`<!---->`);
						} else return [createVNode("span", { class: "material-symbols-rounded shrink-0 text-xl group-hover:text-primary transition-colors text-lg" }, toDisplayString(item.icon), 1), isSidebarOpen.value ? (openBlock(), createBlock("span", {
							key: 0,
							class: "text-[10px] font-black uppercase tracking-widest"
						}, toDisplayString(item.name), 1)) : createCommentVNode("", true)];
					}),
					_: 2
				}, _parent));
			});
			_push(`<!--]-->`);
			if (isSidebarOpen.value) _push(`<div class="mt-8 relative group cursor-help"><div class="${ssrRenderClass([twoFactorStatus.value ? "bg-emerald-500" : "bg-rose-500", "absolute inset-0 blur-xl opacity-20 transition-all group-hover:opacity-40"])}"></div><div class="${ssrRenderClass([twoFactorStatus.value ? "bg-emerald-500/5 border-emerald-500/20" : "bg-rose-500/10 border-rose-500/20", "relative p-5 rounded-[2rem] flex items-center gap-4 border transition-all"])}"><div class="${ssrRenderClass([twoFactorStatus.value ? "bg-emerald-500/20" : "bg-rose-500/20", "w-10 h-10 rounded-2xl flex items-center justify-center shadow-inner"])}"><span class="${ssrRenderClass([twoFactorStatus.value ? "text-emerald-500" : "text-rose-500", "material-symbols-rounded text-xl"])}">${ssrInterpolate(twoFactorStatus.value ? "verified_user" : "gpp_maybe")}</span></div><div class="flex flex-col"><span class="${ssrRenderClass([twoFactorStatus.value ? "text-emerald-500" : "text-rose-500", "text-[9px] font-black uppercase tracking-widest"])}">${ssrInterpolate(twoFactorStatus.value ? "Escudo Activo" : "Riesgo Crítico")}</span><span class="text-[7px] font-black text-on-surface-variant/40 dark:text-white/20 uppercase tracking-widest mt-1">${ssrInterpolate(twoFactorStatus.value ? "IDENTIDAD PROTEGIDA" : "CONFIGURA 2FA YA")}</span></div></div></div>`);
			else _push(`<!---->`);
			_push(`</div>`);
			if (isLargeScreen.value) _push(`<button class="absolute bottom-6 -right-3 w-8 h-8 bg-surface dark:bg-[#0b0e14] border border-outline-variant/20 rounded-xl flex items-center justify-center hover:scale-110 active:scale-95 transition-all shadow-2xl z-50 text-on-surface-variant"><span class="${ssrRenderClass([{ "rotate-180": !isSidebarOpen.value }, "material-symbols-rounded text-xs transition-transform duration-500"])}">chevron_left</span></button>`);
			else _push(`<!---->`);
			_push(`</aside><main class="${ssrRenderClass([[isLargeScreen.value ? isSidebarOpen.value ? "ml-72" : "ml-24" : "ml-0"], "flex-1 transition-all duration-500 min-h-screen w-full overflow-x-hidden"])}"><header class="sticky top-0 z-40 bg-surface/60 dark:bg-[#05070a]/60 backdrop-blur-2xl border-b border-outline-variant/10 px-6 md:px-10 py-6 flex justify-between items-center shadow-sm"><div class="flex items-center gap-6"><button class="lg:hidden p-3 rounded-2xl bg-primary/5 text-primary"><span class="material-symbols-rounded">${ssrInterpolate(isSidebarOpen.value ? "menu_open" : "menu")}</span></button><div><h2 class="text-xs md:text-xl font-black text-on-surface dark:text-white uppercase tracking-tighter italic"> HOLA, <span class="text-primary">${ssrInterpolate(user.value?.name.split(" ")[0])}</span></h2><div class="flex items-center gap-2 mt-1"><div class="w-1.5 h-1.5 rounded-full bg-primary animate-pulse"></div><p class="hidden sm:block text-[9px] font-black text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.3em]">${ssrInterpolate((/* @__PURE__ */ new Date()).toLocaleDateString("es-ES", {
				weekday: "long",
				day: "numeric",
				month: "long"
			}).toUpperCase())}</p></div></div></div><div class="flex items-center gap-8">`);
			_push(ssrRenderComponent(ThemeToggle_default, null, null, _parent));
			_push(ssrRenderComponent(NotificationCenter_default, null, null, _parent));
			_push(`<div class="flex items-center gap-4 pl-6 border-l border-outline-variant/20 relative"><div class="hidden lg:block text-right"><p class="text-[11px] font-black text-on-surface dark:text-white uppercase tracking-tighter leading-none">${ssrInterpolate(user.value?.name)}</p><p class="text-[8px] font-black text-primary uppercase tracking-widest mt-1 italic">${ssrInterpolate(user.value?.role?.replace("_", " "))}</p></div><button class="relative group"><div class="w-12 h-12 rounded-2xl bg-primary ring-4 ring-primary/10 overflow-hidden shadow-2xl group-hover:ring-primary/30 transition-all"><img${ssrRenderAttr("src", `https://api.dicebear.com/7.x/avataaars/svg?seed=${user.value?.email}`)} class="w-full h-full object-cover"></div>`);
			if (showUserMenu.value) {
				_push(`<div class="absolute top-full right-0 mt-6 w-64 bg-white dark:bg-[#0b0e14] border border-outline-variant/10 dark:border-white/5 rounded-[2rem] p-4 shadow-2xl z-50"><div class="px-5 py-4 border-b border-outline-variant/5 dark:border-white/5 mb-2"><p class="text-xs font-black text-on-surface dark:text-white uppercase tracking-tighter">${ssrInterpolate(user.value?.name)}</p><p class="text-[9px] text-on-surface-variant/40 dark:text-white/20 uppercase tracking-widest mt-1 italic italic">Nexo-Core Identity</p></div>`);
				_push(ssrRenderComponent(unref(Link), {
					href: _ctx.route("profile.edit"),
					class: "w-full flex items-center gap-3 px-5 py-3.5 rounded-2xl hover:bg-primary/5 text-on-surface-variant hover:text-primary text-[10px] font-black uppercase tracking-widest transition-all"
				}, {
					default: withCtx((_, _push, _parent, _scopeId) => {
						if (_push) _push(`<span class="material-symbols-rounded text-lg"${_scopeId}>person</span> Mi Perfil `);
						else return [createVNode("span", { class: "material-symbols-rounded text-lg" }, "person"), createTextVNode(" Mi Perfil ")];
					}),
					_: 1
				}, _parent));
				_push(ssrRenderComponent(unref(Link), {
					href: _ctx.route("logout"),
					method: "post",
					as: "button",
					class: "w-full flex items-center gap-3 px-5 py-3.5 rounded-2xl hover:bg-error/10 text-on-surface-variant hover:text-error text-[10px] font-black uppercase tracking-widest transition-all mt-1"
				}, {
					default: withCtx((_, _push, _parent, _scopeId) => {
						if (_push) _push(`<span class="material-symbols-rounded text-lg"${_scopeId}>logout</span> Cerrar Sesión `);
						else return [createVNode("span", { class: "material-symbols-rounded text-lg" }, "logout"), createTextVNode(" Cerrar Sesión ")];
					}),
					_: 1
				}, _parent));
				_push(`</div>`);
			} else _push(`<!---->`);
			_push(`</button></div></div></header><div class="p-8 md:p-12 max-w-screen-2xl mx-auto"><template><div>`);
			ssrRenderSlot(_ctx.$slots, "default", {}, null, _push, _parent);
			_push(`</div></template></div></main>`);
			_push(ssrRenderComponent(AnnouncementModal_default, null, null, _parent));
			_push(`</div>`);
		};
	}
});
//#endregion
//#region resources/js/Layouts/AuthenticatedLayout.vue
var _sfc_setup = AuthenticatedLayout_vue_vue_type_script_setup_true_lang_default.setup;
AuthenticatedLayout_vue_vue_type_script_setup_true_lang_default.setup = (props, ctx) => {
	const ssrContext = useSSRContext();
	(ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Layouts/AuthenticatedLayout.vue");
	return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
var AuthenticatedLayout_default = AuthenticatedLayout_vue_vue_type_script_setup_true_lang_default;
//#endregion
export { AuthenticatedLayout_default as t };

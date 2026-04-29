import { t as ThemeToggle_default } from "./ThemeToggle-DCsnwAEm.js";
import { t as Logo_default } from "./Logo-D89dXaWr.js";
import { t as Dropdown_default } from "./Dropdown-BzV9SsLU.js";
import { computed, createTextVNode, createVNode, defineComponent, mergeProps, onMounted, onUnmounted, ref, toDisplayString, unref, useSSRContext, withCtx } from "vue";
import { Link, router, usePage } from "@inertiajs/vue3";
import { ssrInterpolate, ssrRenderAttr, ssrRenderAttrs, ssrRenderClass, ssrRenderComponent, ssrRenderList, ssrRenderSlot } from "vue/server-renderer";
//#region resources/js/Layouts/OwnerLayout.vue?vue&type=script&setup=true&lang.ts
var OwnerLayout_vue_vue_type_script_setup_true_lang_default = /* @__PURE__ */ defineComponent({
	__name: "OwnerLayout",
	__ssrInlineRender: true,
	setup(__props) {
		const page = usePage();
		const user = computed(() => page.props.auth?.user);
		const notifications = computed(() => page.props.notifications || []);
		const unreadCount = computed(() => notifications.value.length);
		const isSidebarExpanded = ref(true);
		const isMobileMenuOpen = ref(false);
		ref(false);
		const showUserMenu = ref(false);
		const windowWidth = ref(window.innerWidth);
		const updateWidth = () => {
			windowWidth.value = window.innerWidth;
			if (windowWidth.value >= 1024) isSidebarExpanded.value = true;
			else if (windowWidth.value >= 768) isSidebarExpanded.value = false;
			else isMobileMenuOpen.value = false;
		};
		onMounted(() => {
			window.addEventListener("resize", updateWidth);
			updateWidth();
		});
		onUnmounted(() => window.removeEventListener("resize", updateWidth));
		router.on("navigate", () => {
			isMobileMenuOpen.value = false;
		});
		const navItems = [
			{
				name: "Inicio",
				icon: "home",
				href: route("owner.dashboard"),
				active: route().current("owner.dashboard")
			},
			{
				name: "Pagos",
				icon: "receipt_long",
				href: route("owner.payments"),
				active: route().current("owner.payments")
			},
			{
				name: "Reservas",
				icon: "event_available",
				href: route("reservas.index"),
				active: route().current("reservas.index")
			},
			{
				name: "PQRS",
				icon: "chat_bubble",
				href: route("pqrs.index"),
				active: route().current("pqrs.index")
			},
			{
				name: "Perfil",
				icon: "person",
				href: route("profile.edit"),
				active: route().current("profile.edit")
			}
		];
		const mainMobileItems = computed(() => navItems.slice(0, 3));
		const moreMobileItems = computed(() => navItems.slice(3));
		return (_ctx, _push, _parent, _attrs) => {
			_push(`<div${ssrRenderAttrs(mergeProps({ class: "min-h-screen bg-surface flex text-on-surface font-sans antialiased" }, _attrs))}><aside class="${ssrRenderClass([[isSidebarExpanded.value ? "w-64" : "w-20"], "hidden md:flex fixed inset-y-0 left-0 bg-surface-container-lowest border-r border-outline-variant/30 flex-col transition-all duration-300 z-40"])}"><div class="p-6 flex justify-center items-center h-20 border-b border-outline-variant/10">`);
			_push(ssrRenderComponent(unref(Link), {
				href: _ctx.route("owner.dashboard"),
				class: "flex items-center overflow-hidden transition-all whitespace-nowrap"
			}, {
				default: withCtx((_, _push, _parent, _scopeId) => {
					if (_push) _push(ssrRenderComponent(Logo_default, {
						width: isSidebarExpanded.value ? "48px" : "32px",
						height: isSidebarExpanded.value ? "48px" : "32px",
						"show-text": isSidebarExpanded.value
					}, null, _parent, _scopeId));
					else return [createVNode(Logo_default, {
						width: isSidebarExpanded.value ? "48px" : "32px",
						height: isSidebarExpanded.value ? "48px" : "32px",
						"show-text": isSidebarExpanded.value
					}, null, 8, [
						"width",
						"height",
						"show-text"
					])];
				}),
				_: 1
			}, _parent));
			_push(`</div><nav class="flex-1 py-6 px-3 space-y-1 overflow-y-auto no-scrollbar"><!--[-->`);
			ssrRenderList(navItems, (item) => {
				_push(ssrRenderComponent(unref(Link), {
					key: item.name,
					href: item.href,
					class: ["flex items-center py-3 px-3 mx-1 rounded-xl transition-all group overflow-hidden border border-transparent", [item.active ? "bg-primary/10 text-primary opacity-100 shadow-sm border-primary/10" : "text-on-surface-variant hover:bg-surface-container-high"]],
					title: item.name
				}, {
					default: withCtx((_, _push, _parent, _scopeId) => {
						if (_push) _push(`<span class="${ssrRenderClass([{ "text-primary": item.active }, "material-symbols-rounded shrink-0"])}"${_scopeId}>${ssrInterpolate(item.icon)}</span><span class="${ssrRenderClass([isSidebarExpanded.value ? "opacity-100" : "opacity-0 hidden", "ml-3 text-sm font-semibold tracking-wide transition-opacity duration-300 whitespace-nowrap"])}"${_scopeId}>${ssrInterpolate(item.name)}</span>`);
						else return [createVNode("span", { class: ["material-symbols-rounded shrink-0", { "text-primary": item.active }] }, toDisplayString(item.icon), 3), createVNode("span", { class: ["ml-3 text-sm font-semibold tracking-wide transition-opacity duration-300 whitespace-nowrap", isSidebarExpanded.value ? "opacity-100" : "opacity-0 hidden"] }, toDisplayString(item.name), 3)];
					}),
					_: 2
				}, _parent));
			});
			_push(`<!--]--></nav><div class="p-4 border-t border-outline-variant/20 space-y-2"><button class="w-full flex justify-center items-center p-2 rounded-xl text-on-surface-variant hover:bg-surface-container-high transition-colors"><span class="material-symbols-rounded">${ssrInterpolate(isSidebarExpanded.value ? "keyboard_double_arrow_left" : "keyboard_double_arrow_right")}</span></button></div></aside><main class="${ssrRenderClass([[windowWidth.value >= 768 ? isSidebarExpanded.value ? "ml-64" : "ml-20" : "ml-0 pb-16"], "flex-1 flex flex-col transition-all duration-300 min-h-screen"])}"><div class="brand-accent-line sticky top-0 z-[50]"></div><header class="sticky top-[3px] z-30 bg-surface/80 backdrop-blur-md border-b border-outline-variant/30 px-4 md:px-8 py-4 flex justify-between items-center transition-shadow"><div class="flex items-center gap-4"><h2 class="text-xl font-extrabold text-on-surface tracking-tight truncate">Portal Propietario</h2></div><div class="flex items-center gap-3 md:gap-5">`);
			_push(ssrRenderComponent(ThemeToggle_default, null, null, _parent));
			if (unref(page).props.auth.user.available_copropiedades?.length > 1) {
				_push(`<div class="hidden lg:block border-l border-outline-variant/30 pl-4">`);
				_push(ssrRenderComponent(Dropdown_default, {
					label: "Cambiar Conjunto",
					variant: "ghost",
					size: "sm",
					icon: "apartment",
					items: unref(page).props.auth.user.available_copropiedades.map((prop) => ({
						label: prop.nombre,
						icon: unref(page).props.auth.user.current_copropiedad?.id === prop.id ? "check_circle" : "home",
						action: () => unref(router).post(_ctx.route("tenant.switch", prop.id))
					}))
				}, null, _parent));
				_push(`</div>`);
			} else _push(`<!---->`);
			_push(`<div class="relative"><button class="p-2 rounded-full hover:bg-surface-container-high relative text-on-surface-variant"><span class="material-symbols-rounded">notifications</span>`);
			if (unreadCount.value > 0) _push(`<span class="absolute top-1 right-1 w-2.5 h-2.5 bg-error rounded-full ring-2 ring-surface"></span>`);
			else _push(`<!---->`);
			_push(`</button></div><div class="relative flex items-center border-l border-outline-variant/30 pl-3 md:pl-5"><button class="w-10 h-10 rounded-full border-2 border-primary/20 overflow-hidden hover:border-primary transition-all"><img${ssrRenderAttr("src", `https://ui-avatars.com/api/?name=${encodeURIComponent(user.value?.name || "U")}&background=random&color=fff`)} class="w-full h-full object-cover"></button>`);
			if (showUserMenu.value) {
				_push(`<div class="absolute top-full right-0 mt-3 w-56 bg-surface-container-lowest rounded-2xl shadow-premium border border-outline-variant/30 py-2 z-50">`);
				_push(ssrRenderComponent(unref(Link), {
					href: _ctx.route("profile.edit"),
					class: "w-full px-4 py-2 hover:bg-surface-container text-sm flex gap-3 text-on-surface-variant hover:text-primary"
				}, {
					default: withCtx((_, _push, _parent, _scopeId) => {
						if (_push) _push(`<span class="material-symbols-rounded"${_scopeId}>person</span> Perfil`);
						else return [createVNode("span", { class: "material-symbols-rounded" }, "person"), createTextVNode(" Perfil")];
					}),
					_: 1
				}, _parent));
				_push(`<button class="w-full px-4 py-2 hover:bg-error/10 text-sm flex gap-3 text-error border-t border-outline-variant/10 pt-3"><span class="material-symbols-rounded">logout</span> Cerrar sesión</button></div>`);
			} else _push(`<!---->`);
			_push(`</div></div></header><div class="px-4 py-6 md:p-8 max-w-[1400px] w-full mx-auto flex-1"><template><div>`);
			ssrRenderSlot(_ctx.$slots, "default", {}, null, _push, _parent);
			_push(`</div></template></div></main>`);
			if (windowWidth.value < 768) {
				_push(`<nav class="fixed bottom-0 inset-x-0 bg-surface-container border-t border-outline-variant/30 flex justify-around items-center h-16 z-50 pb-safe shadow-[0_-4px_20px_rgba(0,0,0,0.05)] dark:shadow-[0_-4px_20px_rgba(0,0,0,0.2)]"><!--[-->`);
				ssrRenderList(mainMobileItems.value, (item) => {
					_push(ssrRenderComponent(unref(Link), {
						key: item.name,
						href: item.href,
						class: ["flex flex-col items-center justify-center w-full h-full text-on-surface-variant hover:text-primary transition-colors", { "text-primary": item.active }]
					}, {
						default: withCtx((_, _push, _parent, _scopeId) => {
							if (_push) _push(`<span class="${ssrRenderClass([{ "fill-current": item.active }, "material-symbols-rounded text-[24px]"])}"${_scopeId}>${ssrInterpolate(item.icon)}</span><span class="text-[10px] font-bold mt-1 tracking-wide"${_scopeId}>${ssrInterpolate(item.name)}</span>`);
							else return [createVNode("span", { class: ["material-symbols-rounded text-[24px]", { "fill-current": item.active }] }, toDisplayString(item.icon), 3), createVNode("span", { class: "text-[10px] font-bold mt-1 tracking-wide" }, toDisplayString(item.name), 1)];
						}),
						_: 2
					}, _parent));
				});
				_push(`<!--]--><button class="flex flex-col items-center justify-center w-full h-full text-on-surface-variant hover:text-primary transition-colors"><span class="material-symbols-rounded text-[24px]">menu</span><span class="text-[10px] font-bold mt-1 tracking-wide">Más</span></button></nav>`);
			} else _push(`<!---->`);
			if (isMobileMenuOpen.value && windowWidth.value < 768) {
				_push(`<div class="fixed inset-0 z-[60] flex flex-col bg-surface"><div class="flex items-center justify-between p-6 border-b border-outline-variant/20">`);
				_push(ssrRenderComponent(Logo_default, {
					width: "40px",
					height: "40px",
					"show-text": true
				}, null, _parent));
				_push(`<button class="p-2 rounded-full bg-surface-container-high text-on-surface"><span class="material-symbols-rounded">close</span></button></div><div class="flex-1 overflow-y-auto p-4 space-y-2"><p class="text-xs font-black text-on-surface-variant uppercase tracking-widest pl-2 mb-2">Más Opciones</p><!--[-->`);
				ssrRenderList(moreMobileItems.value, (item) => {
					_push(ssrRenderComponent(unref(Link), {
						key: item.name,
						href: item.href,
						class: ["flex items-center p-4 bg-surface-container rounded-2xl hover:bg-surface-container-high transition-colors", { "border border-primary/30 bg-primary/5 text-primary": item.active }]
					}, {
						default: withCtx((_, _push, _parent, _scopeId) => {
							if (_push) _push(`<span class="material-symbols-rounded mr-4"${_scopeId}>${ssrInterpolate(item.icon)}</span><span class="font-bold"${_scopeId}>${ssrInterpolate(item.name)}</span>`);
							else return [createVNode("span", { class: "material-symbols-rounded mr-4" }, toDisplayString(item.icon), 1), createVNode("span", { class: "font-bold" }, toDisplayString(item.name), 1)];
						}),
						_: 2
					}, _parent));
				});
				_push(`<!--]--></div></div>`);
			} else _push(`<!---->`);
			_push(`</div>`);
		};
	}
});
//#endregion
//#region resources/js/Layouts/OwnerLayout.vue
var _sfc_setup = OwnerLayout_vue_vue_type_script_setup_true_lang_default.setup;
OwnerLayout_vue_vue_type_script_setup_true_lang_default.setup = (props, ctx) => {
	const ssrContext = useSSRContext();
	(ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Layouts/OwnerLayout.vue");
	return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
var OwnerLayout_default = OwnerLayout_vue_vue_type_script_setup_true_lang_default;
//#endregion
export { OwnerLayout_default as t };

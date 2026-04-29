import { t as _plugin_vue_export_helper_default } from "./_plugin-vue_export-helper-BjJHhBxR.js";
import { t as AuthenticatedLayout_default } from "./AuthenticatedLayout-vxulH9w9.js";
import { t as Card_default } from "./Card-D4IwBRMd.js";
import { t as Button_default } from "./Button-C-byZbTR.js";
import { t as Badge_default } from "./Badge-DsYztQFV.js";
import { t as OwnerLayout_default } from "./OwnerLayout-B6B61cyO.js";
import { computed, createBlock, createCommentVNode, createTextVNode, createVNode, defineComponent, openBlock, resolveDynamicComponent, toDisplayString, unref, useSSRContext, vModelText, withCtx, withDirectives, withModifiers } from "vue";
import { Head, useForm, usePage } from "@inertiajs/vue3";
import { ssrInterpolate, ssrRenderAttr, ssrRenderComponent, ssrRenderVNode } from "vue/server-renderer";
//#region resources/js/Pages/Profile/Edit.vue?vue&type=script&setup=true&lang.ts
var Edit_vue_vue_type_script_setup_true_lang_default = /* @__PURE__ */ defineComponent({
	__name: "Edit",
	__ssrInlineRender: true,
	props: { status: {} },
	setup(__props) {
		const page = usePage();
		const user = computed(() => page.props.auth.user);
		const Layout = computed(() => {
			return user.value.role === "owner" ? OwnerLayout_default : AuthenticatedLayout_default;
		});
		const passwordForm = useForm({
			current_password: "",
			password: "",
			password_confirmation: ""
		});
		const profileForm = useForm({
			name: user.value.name,
			email: user.value.email
		});
		const updateProfile = () => {
			profileForm.patch(route("profile.update"), {
				preserveScroll: true,
				onSuccess: () => {}
			});
		};
		const updatePassword = () => {
			passwordForm.put(route("profile.password"), {
				preserveScroll: true,
				onSuccess: () => passwordForm.reset()
			});
		};
		const getRoleConfig = (role) => {
			const configs = {
				superadmin: {
					label: "SUPER ADMINISTRADOR",
					color: "text-primary",
					bg: "bg-primary/10",
					icon: "verified_user"
				},
				admin: {
					label: "ADMINISTRADOR",
					color: "text-secondary",
					bg: "bg-secondary/10",
					icon: "shield"
				},
				owner: {
					label: "PROPIETARIO / RESIDENTE",
					color: "text-emerald-500",
					bg: "bg-emerald-500/10",
					icon: "home"
				}
			};
			return configs[role] || configs.owner;
		};
		return (_ctx, _push, _parent, _attrs) => {
			_push(`<!--[-->`);
			_push(ssrRenderComponent(unref(Head), { title: "Mi Perfil — NEXO-PRO" }, null, _parent));
			ssrRenderVNode(_push, createVNode(resolveDynamicComponent(Layout.value), { user: user.value }, {
				default: withCtx((_, _push, _parent, _scopeId) => {
					if (_push) {
						_push(`<div class="space-y-12 pb-32 animate-in fade-in slide-in-from-bottom-6 duration-1000" data-v-dffe433f${_scopeId}><div class="relative bg-white dark:bg-[#0b0e14] border border-outline-variant/10 dark:border-white/5 rounded-[3rem] p-10 shadow-2xl overflow-hidden group" data-v-dffe433f${_scopeId}><div class="absolute -right-20 -top-20 w-80 h-80 bg-primary/5 rounded-full blur-[100px] group-hover:bg-primary/10 transition-all duration-1000" data-v-dffe433f${_scopeId}></div><div class="flex flex-col md:flex-row items-center gap-12 relative z-10" data-v-dffe433f${_scopeId}><div class="relative" data-v-dffe433f${_scopeId}><div class="w-40 h-40 rounded-[2.5rem] bg-surface-container dark:bg-white/5 border-2 border-outline-variant/10 dark:border-white/10 p-2 shadow-2xl rotate-3 transition-transform group-hover:rotate-0 duration-500" data-v-dffe433f${_scopeId}><img${ssrRenderAttr("src", `https://api.dicebear.com/7.x/avataaars/svg?seed=${user.value.email}`)} class="w-full h-full rounded-[2rem] object-cover" data-v-dffe433f${_scopeId}></div><div class="absolute -bottom-2 -right-2 w-12 h-12 rounded-2xl bg-emerald-500 border-4 border-white dark:border-[#0b0e14] flex items-center justify-center text-white shadow-xl" data-v-dffe433f${_scopeId}><span class="material-symbols-rounded text-xl" data-v-dffe433f${_scopeId}>check_circle</span></div></div><div class="flex-1 text-center md:text-left space-y-4" data-v-dffe433f${_scopeId}><div class="flex flex-wrap items-center justify-center md:justify-start gap-4" data-v-dffe433f${_scopeId}>`);
						_push(ssrRenderComponent(Badge_default, {
							variant: "neutral",
							class: "!px-5 !py-1 !text-[10px] !font-black !bg-primary/5 !text-primary !border-primary/10 tracking-[0.2em] uppercase"
						}, {
							default: withCtx((_, _push, _parent, _scopeId) => {
								if (_push) _push(`<span class="material-symbols-rounded text-[14px] mr-2" data-v-dffe433f${_scopeId}>${ssrInterpolate(getRoleConfig(user.value.role).icon)}</span> ${ssrInterpolate(getRoleConfig(user.value.role).label)}`);
								else return [createVNode("span", { class: "material-symbols-rounded text-[14px] mr-2" }, toDisplayString(getRoleConfig(user.value.role).icon), 1), createTextVNode(" " + toDisplayString(getRoleConfig(user.value.role).label), 1)];
							}),
							_: 1
						}, _parent, _scopeId));
						_push(ssrRenderComponent(Badge_default, {
							variant: "neutral",
							class: "!px-5 !py-1 !text-[10px] !font-black !bg-white dark:!bg-white/5 !text-on-surface-variant dark:!text-white/40 !border-outline-variant/10 dark:!border-white/5 tracking-[0.2em] uppercase italic"
						}, {
							default: withCtx((_, _push, _parent, _scopeId) => {
								if (_push) _push(` UID: ${ssrInterpolate(user.value.id.split("-")[0])}`);
								else return [createTextVNode(" UID: " + toDisplayString(user.value.id.split("-")[0]), 1)];
							}),
							_: 1
						}, _parent, _scopeId));
						_push(`</div><h1 class="text-5xl font-black text-on-surface dark:text-white tracking-tighter uppercase italic leading-none" data-v-dffe433f${_scopeId}>${ssrInterpolate(user.value.name)}</h1><p class="text-sm font-bold text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.3em] italic" data-v-dffe433f${_scopeId}>${ssrInterpolate(user.value.email)}</p></div><div class="grid grid-cols-2 gap-4" data-v-dffe433f${_scopeId}><div class="px-8 py-6 bg-surface-container-low dark:bg-white/[0.02] rounded-[2rem] border border-outline-variant/10 dark:border-white/5 text-center" data-v-dffe433f${_scopeId}><p class="text-[9px] font-black text-primary uppercase tracking-widest mb-1" data-v-dffe433f${_scopeId}>MIEMBRO DESDE</p><p class="text-lg font-black text-on-surface dark:text-white uppercase tracking-tighter italic" data-v-dffe433f${_scopeId}>${ssrInterpolate(new Date(user.value.created_at).getFullYear())}</p></div><div class="px-8 py-6 bg-surface-container-low dark:bg-white/[0.02] rounded-[2rem] border border-outline-variant/10 dark:border-white/5 text-center" data-v-dffe433f${_scopeId}><p class="text-[9px] font-black text-emerald-500 uppercase tracking-widest mb-1" data-v-dffe433f${_scopeId}>ESTADO CUENTA</p><p class="text-lg font-black text-on-surface dark:text-white uppercase tracking-tighter italic" data-v-dffe433f${_scopeId}>VÁLIDA</p></div></div></div></div><div class="grid grid-cols-1 lg:grid-cols-12 gap-8" data-v-dffe433f${_scopeId}><div class="lg:col-span-12 xl:col-span-7" data-v-dffe433f${_scopeId}>`);
						_push(ssrRenderComponent(Card_default, { class: "!p-10 !rounded-[3.5rem] border border-outline-variant/10 dark:border-white/5 shadow-2xl dark:bg-[#0b0e14]" }, {
							default: withCtx((_, _push, _parent, _scopeId) => {
								if (_push) {
									_push(`<div class="flex justify-between items-start mb-10" data-v-dffe433f${_scopeId}><div data-v-dffe433f${_scopeId}><h3 class="text-2xl font-black text-on-surface dark:text-white uppercase tracking-tighter italic" data-v-dffe433f${_scopeId}>Datos de <span class="text-primary font-black italic" data-v-dffe433f${_scopeId}>Identidad</span></h3><p class="text-[11px] font-bold text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.3em] mt-3 italic" data-v-dffe433f${_scopeId}>Sincronización de parámetros personales del núcleo</p></div><div class="w-14 h-14 bg-primary/10 rounded-2xl flex items-center justify-center text-primary shadow-inner" data-v-dffe433f${_scopeId}><span class="material-symbols-rounded text-2xl" data-v-dffe433f${_scopeId}>person_edit</span></div></div><form class="space-y-8" data-v-dffe433f${_scopeId}><div class="grid grid-cols-1 md:grid-cols-2 gap-8" data-v-dffe433f${_scopeId}><div class="space-y-2" data-v-dffe433f${_scopeId}><label class="text-[10px] font-black text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.3em] ml-4" data-v-dffe433f${_scopeId}>Nombre Completo</label><div class="relative group" data-v-dffe433f${_scopeId}><span class="material-symbols-rounded absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant/40 group-focus-within:text-primary transition-colors text-lg" data-v-dffe433f${_scopeId}>person</span><input${ssrRenderAttr("value", unref(profileForm).name)} class="w-full h-14 pl-12 pr-4 bg-white dark:bg-white/[0.03] border border-outline-variant/10 dark:border-white/5 rounded-2xl text-xs font-bold text-on-surface dark:text-white focus:ring-4 focus:ring-primary/5 outline-none transition-all shadow-sm" required data-v-dffe433f${_scopeId}></div></div><div class="space-y-2" data-v-dffe433f${_scopeId}><label class="text-[10px] font-black text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.3em] ml-4" data-v-dffe433f${_scopeId}>Enlace de Comunicación</label><div class="relative group" data-v-dffe433f${_scopeId}><span class="material-symbols-rounded absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant/40 group-focus-within:text-primary transition-colors text-lg" data-v-dffe433f${_scopeId}>mail</span><input${ssrRenderAttr("value", unref(profileForm).email)} type="email" class="w-full h-14 pl-12 pr-4 bg-white dark:bg-white/[0.03] border border-outline-variant/10 dark:border-white/5 rounded-2xl text-xs font-bold text-on-surface dark:text-white focus:ring-4 focus:ring-primary/5 outline-none transition-all shadow-sm" required data-v-dffe433f${_scopeId}></div></div></div><div class="flex items-center justify-between pt-6 border-t border-outline-variant/5 dark:border-white/5" data-v-dffe433f${_scopeId}><div class="flex items-center gap-4" data-v-dffe433f${_scopeId}>`);
									if (unref(profileForm).recentlySuccessful) _push(`<p class="text-[10px] font-black text-emerald-500 uppercase tracking-widest animate-pulse italic" data-v-dffe433f${_scopeId}>¡NÚCLEO ACTUALIZADO!</p>`);
									else _push(`<!---->`);
									_push(`</div>`);
									_push(ssrRenderComponent(Button_default, {
										type: "submit",
										variant: "primary",
										loading: unref(profileForm).processing,
										icon: "sync_saved_locally",
										class: "!rounded-2xl !h-14 !px-10 !text-[11px] font-black uppercase shadow-xl shadow-primary/10"
									}, {
										default: withCtx((_, _push, _parent, _scopeId) => {
											if (_push) _push(`Sincronizar Perfil`);
											else return [createTextVNode("Sincronizar Perfil")];
										}),
										_: 1
									}, _parent, _scopeId));
									_push(`</div></form>`);
								} else return [createVNode("div", { class: "flex justify-between items-start mb-10" }, [createVNode("div", null, [createVNode("h3", { class: "text-2xl font-black text-on-surface dark:text-white uppercase tracking-tighter italic" }, [createTextVNode("Datos de "), createVNode("span", { class: "text-primary font-black italic" }, "Identidad")]), createVNode("p", { class: "text-[11px] font-bold text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.3em] mt-3 italic" }, "Sincronización de parámetros personales del núcleo")]), createVNode("div", { class: "w-14 h-14 bg-primary/10 rounded-2xl flex items-center justify-center text-primary shadow-inner" }, [createVNode("span", { class: "material-symbols-rounded text-2xl" }, "person_edit")])]), createVNode("form", {
									onSubmit: withModifiers(updateProfile, ["prevent"]),
									class: "space-y-8"
								}, [createVNode("div", { class: "grid grid-cols-1 md:grid-cols-2 gap-8" }, [createVNode("div", { class: "space-y-2" }, [createVNode("label", { class: "text-[10px] font-black text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.3em] ml-4" }, "Nombre Completo"), createVNode("div", { class: "relative group" }, [createVNode("span", { class: "material-symbols-rounded absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant/40 group-focus-within:text-primary transition-colors text-lg" }, "person"), withDirectives(createVNode("input", {
									"onUpdate:modelValue": ($event) => unref(profileForm).name = $event,
									class: "w-full h-14 pl-12 pr-4 bg-white dark:bg-white/[0.03] border border-outline-variant/10 dark:border-white/5 rounded-2xl text-xs font-bold text-on-surface dark:text-white focus:ring-4 focus:ring-primary/5 outline-none transition-all shadow-sm",
									required: ""
								}, null, 8, ["onUpdate:modelValue"]), [[vModelText, unref(profileForm).name]])])]), createVNode("div", { class: "space-y-2" }, [createVNode("label", { class: "text-[10px] font-black text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.3em] ml-4" }, "Enlace de Comunicación"), createVNode("div", { class: "relative group" }, [createVNode("span", { class: "material-symbols-rounded absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant/40 group-focus-within:text-primary transition-colors text-lg" }, "mail"), withDirectives(createVNode("input", {
									"onUpdate:modelValue": ($event) => unref(profileForm).email = $event,
									type: "email",
									class: "w-full h-14 pl-12 pr-4 bg-white dark:bg-white/[0.03] border border-outline-variant/10 dark:border-white/5 rounded-2xl text-xs font-bold text-on-surface dark:text-white focus:ring-4 focus:ring-primary/5 outline-none transition-all shadow-sm",
									required: ""
								}, null, 8, ["onUpdate:modelValue"]), [[vModelText, unref(profileForm).email]])])])]), createVNode("div", { class: "flex items-center justify-between pt-6 border-t border-outline-variant/5 dark:border-white/5" }, [createVNode("div", { class: "flex items-center gap-4" }, [unref(profileForm).recentlySuccessful ? (openBlock(), createBlock("p", {
									key: 0,
									class: "text-[10px] font-black text-emerald-500 uppercase tracking-widest animate-pulse italic"
								}, "¡NÚCLEO ACTUALIZADO!")) : createCommentVNode("", true)]), createVNode(Button_default, {
									type: "submit",
									variant: "primary",
									loading: unref(profileForm).processing,
									icon: "sync_saved_locally",
									class: "!rounded-2xl !h-14 !px-10 !text-[11px] font-black uppercase shadow-xl shadow-primary/10"
								}, {
									default: withCtx(() => [createTextVNode("Sincronizar Perfil")]),
									_: 1
								}, 8, ["loading"])])], 32)];
							}),
							_: 1
						}, _parent, _scopeId));
						_push(`</div><div class="lg:col-span-12 xl:col-span-5" data-v-dffe433f${_scopeId}>`);
						_push(ssrRenderComponent(Card_default, { class: "!p-10 !rounded-[3.5rem] border border-outline-variant/10 dark:border-white/5 shadow-2xl dark:bg-[#0b0e14]" }, {
							default: withCtx((_, _push, _parent, _scopeId) => {
								if (_push) {
									_push(`<div class="flex justify-between items-start mb-10" data-v-dffe433f${_scopeId}><div data-v-dffe433f${_scopeId}><h3 class="text-2xl font-black text-on-surface dark:text-white uppercase tracking-tighter italic" data-v-dffe433f${_scopeId}>Cámara <span class="text-secondary font-black italic" data-v-dffe433f${_scopeId}>Acorazada</span></h3><p class="text-[11px] font-bold text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.3em] mt-3 italic" data-v-dffe433f${_scopeId}>Renovación de llaves de acceso</p></div><div class="w-14 h-14 bg-secondary/10 rounded-2xl flex items-center justify-center text-secondary shadow-inner" data-v-dffe433f${_scopeId}><span class="material-symbols-rounded text-2xl" data-v-dffe433f${_scopeId}>shield_locked</span></div></div><form class="space-y-6" data-v-dffe433f${_scopeId}><div class="space-y-2" data-v-dffe433f${_scopeId}><label class="text-[10px] font-black text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.3em] ml-4" data-v-dffe433f${_scopeId}>Clave Técnica Actual</label><input${ssrRenderAttr("value", unref(passwordForm).current_password)} type="password" class="w-full h-14 px-6 bg-white dark:bg-white/[0.03] border border-outline-variant/10 dark:border-white/5 rounded-2xl text-xs font-bold text-on-surface dark:text-white focus:ring-4 focus:ring-secondary/5 outline-none transition-all" required data-v-dffe433f${_scopeId}></div><div class="space-y-2" data-v-dffe433f${_scopeId}><label class="text-[10px] font-black text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.3em] ml-4" data-v-dffe433f${_scopeId}>Nueva Llave de Acceso</label><input${ssrRenderAttr("value", unref(passwordForm).password)} type="password" class="w-full h-14 px-6 bg-white dark:bg-white/[0.03] border border-outline-variant/10 dark:border-white/5 rounded-2xl text-xs font-bold text-on-surface dark:text-white focus:ring-4 focus:ring-secondary/5 outline-none transition-all" required data-v-dffe433f${_scopeId}></div><div class="space-y-2" data-v-dffe433f${_scopeId}><label class="text-[10px] font-black text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.3em] ml-4" data-v-dffe433f${_scopeId}>Validar Nueva Llave</label><input${ssrRenderAttr("value", unref(passwordForm).password_confirmation)} type="password" class="w-full h-14 px-6 bg-white dark:bg-white/[0.03] border border-outline-variant/10 dark:border-white/5 rounded-2xl text-xs font-bold text-on-surface dark:text-white focus:ring-4 focus:ring-secondary/5 outline-none transition-all" required data-v-dffe433f${_scopeId}></div><div class="pt-6" data-v-dffe433f${_scopeId}>`);
									_push(ssrRenderComponent(Button_default, {
										type: "submit",
										variant: "secondary",
										loading: unref(passwordForm).processing,
										icon: "key_visualizer",
										class: "w-full !rounded-2xl !h-14 !text-[11px] font-black uppercase shadow-xl shadow-secondary/10"
									}, {
										default: withCtx((_, _push, _parent, _scopeId) => {
											if (_push) _push(`Renovar Credenciales`);
											else return [createTextVNode("Renovar Credenciales")];
										}),
										_: 1
									}, _parent, _scopeId));
									_push(`</div></form>`);
								} else return [createVNode("div", { class: "flex justify-between items-start mb-10" }, [createVNode("div", null, [createVNode("h3", { class: "text-2xl font-black text-on-surface dark:text-white uppercase tracking-tighter italic" }, [createTextVNode("Cámara "), createVNode("span", { class: "text-secondary font-black italic" }, "Acorazada")]), createVNode("p", { class: "text-[11px] font-bold text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.3em] mt-3 italic" }, "Renovación de llaves de acceso")]), createVNode("div", { class: "w-14 h-14 bg-secondary/10 rounded-2xl flex items-center justify-center text-secondary shadow-inner" }, [createVNode("span", { class: "material-symbols-rounded text-2xl" }, "shield_locked")])]), createVNode("form", {
									onSubmit: withModifiers(updatePassword, ["prevent"]),
									class: "space-y-6"
								}, [
									createVNode("div", { class: "space-y-2" }, [createVNode("label", { class: "text-[10px] font-black text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.3em] ml-4" }, "Clave Técnica Actual"), withDirectives(createVNode("input", {
										"onUpdate:modelValue": ($event) => unref(passwordForm).current_password = $event,
										type: "password",
										class: "w-full h-14 px-6 bg-white dark:bg-white/[0.03] border border-outline-variant/10 dark:border-white/5 rounded-2xl text-xs font-bold text-on-surface dark:text-white focus:ring-4 focus:ring-secondary/5 outline-none transition-all",
										required: ""
									}, null, 8, ["onUpdate:modelValue"]), [[vModelText, unref(passwordForm).current_password]])]),
									createVNode("div", { class: "space-y-2" }, [createVNode("label", { class: "text-[10px] font-black text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.3em] ml-4" }, "Nueva Llave de Acceso"), withDirectives(createVNode("input", {
										"onUpdate:modelValue": ($event) => unref(passwordForm).password = $event,
										type: "password",
										class: "w-full h-14 px-6 bg-white dark:bg-white/[0.03] border border-outline-variant/10 dark:border-white/5 rounded-2xl text-xs font-bold text-on-surface dark:text-white focus:ring-4 focus:ring-secondary/5 outline-none transition-all",
										required: ""
									}, null, 8, ["onUpdate:modelValue"]), [[vModelText, unref(passwordForm).password]])]),
									createVNode("div", { class: "space-y-2" }, [createVNode("label", { class: "text-[10px] font-black text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.3em] ml-4" }, "Validar Nueva Llave"), withDirectives(createVNode("input", {
										"onUpdate:modelValue": ($event) => unref(passwordForm).password_confirmation = $event,
										type: "password",
										class: "w-full h-14 px-6 bg-white dark:bg-white/[0.03] border border-outline-variant/10 dark:border-white/5 rounded-2xl text-xs font-bold text-on-surface dark:text-white focus:ring-4 focus:ring-secondary/5 outline-none transition-all",
										required: ""
									}, null, 8, ["onUpdate:modelValue"]), [[vModelText, unref(passwordForm).password_confirmation]])]),
									createVNode("div", { class: "pt-6" }, [createVNode(Button_default, {
										type: "submit",
										variant: "secondary",
										loading: unref(passwordForm).processing,
										icon: "key_visualizer",
										class: "w-full !rounded-2xl !h-14 !text-[11px] font-black uppercase shadow-xl shadow-secondary/10"
									}, {
										default: withCtx(() => [createTextVNode("Renovar Credenciales")]),
										_: 1
									}, 8, ["loading"])])
								], 32)];
							}),
							_: 1
						}, _parent, _scopeId));
						_push(`</div></div><div class="bg-surface-container dark:bg-white/[0.02] border border-outline-variant/10 dark:border-white/5 rounded-[3.5rem] p-10 flex flex-col md:flex-row items-center justify-between gap-8" data-v-dffe433f${_scopeId}><div class="flex items-center gap-8 text-center md:text-left" data-v-dffe433f${_scopeId}><div class="w-20 h-20 rounded-[2.5rem] bg-emerald-500/10 flex items-center justify-center shadow-inner relative overflow-hidden" data-v-dffe433f${_scopeId}><div class="absolute inset-0 bg-emerald-500/10 animate-pulse" data-v-dffe433f${_scopeId}></div><span class="material-symbols-rounded text-4xl text-emerald-500 relative z-10" data-v-dffe433f${_scopeId}>health_metrics</span></div><div data-v-dffe433f${_scopeId}><h4 class="text-2xl font-black text-on-surface dark:text-white uppercase tracking-tighter italic leading-none" data-v-dffe433f${_scopeId}>Salud de la Cuenta: <span class="text-emerald-500" data-v-dffe433f${_scopeId}>EXCELENTE</span></h4><p class="text-[11px] font-bold text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.2em] mt-3 leading-relaxed" data-v-dffe433f${_scopeId}>Tu perfil cumple con todos los protocolos de seguridad de Nexo-Core.</p></div></div><div class="flex gap-4" data-v-dffe433f${_scopeId}><div class="flex items-center gap-3 bg-white dark:bg-white/5 px-6 py-4 rounded-2xl border border-outline-variant/10 dark:border-white/5" data-v-dffe433f${_scopeId}><span class="material-symbols-rounded text-emerald-500" data-v-dffe433f${_scopeId}>security</span><span class="text-[10px] font-black text-on-surface dark:text-white uppercase tracking-widest" data-v-dffe433f${_scopeId}>2FA ACTIVO</span></div></div></div></div>`);
					} else return [createVNode("div", { class: "space-y-12 pb-32 animate-in fade-in slide-in-from-bottom-6 duration-1000" }, [
						createVNode("div", { class: "relative bg-white dark:bg-[#0b0e14] border border-outline-variant/10 dark:border-white/5 rounded-[3rem] p-10 shadow-2xl overflow-hidden group" }, [createVNode("div", { class: "absolute -right-20 -top-20 w-80 h-80 bg-primary/5 rounded-full blur-[100px] group-hover:bg-primary/10 transition-all duration-1000" }), createVNode("div", { class: "flex flex-col md:flex-row items-center gap-12 relative z-10" }, [
							createVNode("div", { class: "relative" }, [createVNode("div", { class: "w-40 h-40 rounded-[2.5rem] bg-surface-container dark:bg-white/5 border-2 border-outline-variant/10 dark:border-white/10 p-2 shadow-2xl rotate-3 transition-transform group-hover:rotate-0 duration-500" }, [createVNode("img", {
								src: `https://api.dicebear.com/7.x/avataaars/svg?seed=${user.value.email}`,
								class: "w-full h-full rounded-[2rem] object-cover"
							}, null, 8, ["src"])]), createVNode("div", { class: "absolute -bottom-2 -right-2 w-12 h-12 rounded-2xl bg-emerald-500 border-4 border-white dark:border-[#0b0e14] flex items-center justify-center text-white shadow-xl" }, [createVNode("span", { class: "material-symbols-rounded text-xl" }, "check_circle")])]),
							createVNode("div", { class: "flex-1 text-center md:text-left space-y-4" }, [
								createVNode("div", { class: "flex flex-wrap items-center justify-center md:justify-start gap-4" }, [createVNode(Badge_default, {
									variant: "neutral",
									class: "!px-5 !py-1 !text-[10px] !font-black !bg-primary/5 !text-primary !border-primary/10 tracking-[0.2em] uppercase"
								}, {
									default: withCtx(() => [createVNode("span", { class: "material-symbols-rounded text-[14px] mr-2" }, toDisplayString(getRoleConfig(user.value.role).icon), 1), createTextVNode(" " + toDisplayString(getRoleConfig(user.value.role).label), 1)]),
									_: 1
								}), createVNode(Badge_default, {
									variant: "neutral",
									class: "!px-5 !py-1 !text-[10px] !font-black !bg-white dark:!bg-white/5 !text-on-surface-variant dark:!text-white/40 !border-outline-variant/10 dark:!border-white/5 tracking-[0.2em] uppercase italic"
								}, {
									default: withCtx(() => [createTextVNode(" UID: " + toDisplayString(user.value.id.split("-")[0]), 1)]),
									_: 1
								})]),
								createVNode("h1", { class: "text-5xl font-black text-on-surface dark:text-white tracking-tighter uppercase italic leading-none" }, toDisplayString(user.value.name), 1),
								createVNode("p", { class: "text-sm font-bold text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.3em] italic" }, toDisplayString(user.value.email), 1)
							]),
							createVNode("div", { class: "grid grid-cols-2 gap-4" }, [createVNode("div", { class: "px-8 py-6 bg-surface-container-low dark:bg-white/[0.02] rounded-[2rem] border border-outline-variant/10 dark:border-white/5 text-center" }, [createVNode("p", { class: "text-[9px] font-black text-primary uppercase tracking-widest mb-1" }, "MIEMBRO DESDE"), createVNode("p", { class: "text-lg font-black text-on-surface dark:text-white uppercase tracking-tighter italic" }, toDisplayString(new Date(user.value.created_at).getFullYear()), 1)]), createVNode("div", { class: "px-8 py-6 bg-surface-container-low dark:bg-white/[0.02] rounded-[2rem] border border-outline-variant/10 dark:border-white/5 text-center" }, [createVNode("p", { class: "text-[9px] font-black text-emerald-500 uppercase tracking-widest mb-1" }, "ESTADO CUENTA"), createVNode("p", { class: "text-lg font-black text-on-surface dark:text-white uppercase tracking-tighter italic" }, "VÁLIDA")])])
						])]),
						createVNode("div", { class: "grid grid-cols-1 lg:grid-cols-12 gap-8" }, [createVNode("div", { class: "lg:col-span-12 xl:col-span-7" }, [createVNode(Card_default, { class: "!p-10 !rounded-[3.5rem] border border-outline-variant/10 dark:border-white/5 shadow-2xl dark:bg-[#0b0e14]" }, {
							default: withCtx(() => [createVNode("div", { class: "flex justify-between items-start mb-10" }, [createVNode("div", null, [createVNode("h3", { class: "text-2xl font-black text-on-surface dark:text-white uppercase tracking-tighter italic" }, [createTextVNode("Datos de "), createVNode("span", { class: "text-primary font-black italic" }, "Identidad")]), createVNode("p", { class: "text-[11px] font-bold text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.3em] mt-3 italic" }, "Sincronización de parámetros personales del núcleo")]), createVNode("div", { class: "w-14 h-14 bg-primary/10 rounded-2xl flex items-center justify-center text-primary shadow-inner" }, [createVNode("span", { class: "material-symbols-rounded text-2xl" }, "person_edit")])]), createVNode("form", {
								onSubmit: withModifiers(updateProfile, ["prevent"]),
								class: "space-y-8"
							}, [createVNode("div", { class: "grid grid-cols-1 md:grid-cols-2 gap-8" }, [createVNode("div", { class: "space-y-2" }, [createVNode("label", { class: "text-[10px] font-black text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.3em] ml-4" }, "Nombre Completo"), createVNode("div", { class: "relative group" }, [createVNode("span", { class: "material-symbols-rounded absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant/40 group-focus-within:text-primary transition-colors text-lg" }, "person"), withDirectives(createVNode("input", {
								"onUpdate:modelValue": ($event) => unref(profileForm).name = $event,
								class: "w-full h-14 pl-12 pr-4 bg-white dark:bg-white/[0.03] border border-outline-variant/10 dark:border-white/5 rounded-2xl text-xs font-bold text-on-surface dark:text-white focus:ring-4 focus:ring-primary/5 outline-none transition-all shadow-sm",
								required: ""
							}, null, 8, ["onUpdate:modelValue"]), [[vModelText, unref(profileForm).name]])])]), createVNode("div", { class: "space-y-2" }, [createVNode("label", { class: "text-[10px] font-black text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.3em] ml-4" }, "Enlace de Comunicación"), createVNode("div", { class: "relative group" }, [createVNode("span", { class: "material-symbols-rounded absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant/40 group-focus-within:text-primary transition-colors text-lg" }, "mail"), withDirectives(createVNode("input", {
								"onUpdate:modelValue": ($event) => unref(profileForm).email = $event,
								type: "email",
								class: "w-full h-14 pl-12 pr-4 bg-white dark:bg-white/[0.03] border border-outline-variant/10 dark:border-white/5 rounded-2xl text-xs font-bold text-on-surface dark:text-white focus:ring-4 focus:ring-primary/5 outline-none transition-all shadow-sm",
								required: ""
							}, null, 8, ["onUpdate:modelValue"]), [[vModelText, unref(profileForm).email]])])])]), createVNode("div", { class: "flex items-center justify-between pt-6 border-t border-outline-variant/5 dark:border-white/5" }, [createVNode("div", { class: "flex items-center gap-4" }, [unref(profileForm).recentlySuccessful ? (openBlock(), createBlock("p", {
								key: 0,
								class: "text-[10px] font-black text-emerald-500 uppercase tracking-widest animate-pulse italic"
							}, "¡NÚCLEO ACTUALIZADO!")) : createCommentVNode("", true)]), createVNode(Button_default, {
								type: "submit",
								variant: "primary",
								loading: unref(profileForm).processing,
								icon: "sync_saved_locally",
								class: "!rounded-2xl !h-14 !px-10 !text-[11px] font-black uppercase shadow-xl shadow-primary/10"
							}, {
								default: withCtx(() => [createTextVNode("Sincronizar Perfil")]),
								_: 1
							}, 8, ["loading"])])], 32)]),
							_: 1
						})]), createVNode("div", { class: "lg:col-span-12 xl:col-span-5" }, [createVNode(Card_default, { class: "!p-10 !rounded-[3.5rem] border border-outline-variant/10 dark:border-white/5 shadow-2xl dark:bg-[#0b0e14]" }, {
							default: withCtx(() => [createVNode("div", { class: "flex justify-between items-start mb-10" }, [createVNode("div", null, [createVNode("h3", { class: "text-2xl font-black text-on-surface dark:text-white uppercase tracking-tighter italic" }, [createTextVNode("Cámara "), createVNode("span", { class: "text-secondary font-black italic" }, "Acorazada")]), createVNode("p", { class: "text-[11px] font-bold text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.3em] mt-3 italic" }, "Renovación de llaves de acceso")]), createVNode("div", { class: "w-14 h-14 bg-secondary/10 rounded-2xl flex items-center justify-center text-secondary shadow-inner" }, [createVNode("span", { class: "material-symbols-rounded text-2xl" }, "shield_locked")])]), createVNode("form", {
								onSubmit: withModifiers(updatePassword, ["prevent"]),
								class: "space-y-6"
							}, [
								createVNode("div", { class: "space-y-2" }, [createVNode("label", { class: "text-[10px] font-black text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.3em] ml-4" }, "Clave Técnica Actual"), withDirectives(createVNode("input", {
									"onUpdate:modelValue": ($event) => unref(passwordForm).current_password = $event,
									type: "password",
									class: "w-full h-14 px-6 bg-white dark:bg-white/[0.03] border border-outline-variant/10 dark:border-white/5 rounded-2xl text-xs font-bold text-on-surface dark:text-white focus:ring-4 focus:ring-secondary/5 outline-none transition-all",
									required: ""
								}, null, 8, ["onUpdate:modelValue"]), [[vModelText, unref(passwordForm).current_password]])]),
								createVNode("div", { class: "space-y-2" }, [createVNode("label", { class: "text-[10px] font-black text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.3em] ml-4" }, "Nueva Llave de Acceso"), withDirectives(createVNode("input", {
									"onUpdate:modelValue": ($event) => unref(passwordForm).password = $event,
									type: "password",
									class: "w-full h-14 px-6 bg-white dark:bg-white/[0.03] border border-outline-variant/10 dark:border-white/5 rounded-2xl text-xs font-bold text-on-surface dark:text-white focus:ring-4 focus:ring-secondary/5 outline-none transition-all",
									required: ""
								}, null, 8, ["onUpdate:modelValue"]), [[vModelText, unref(passwordForm).password]])]),
								createVNode("div", { class: "space-y-2" }, [createVNode("label", { class: "text-[10px] font-black text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.3em] ml-4" }, "Validar Nueva Llave"), withDirectives(createVNode("input", {
									"onUpdate:modelValue": ($event) => unref(passwordForm).password_confirmation = $event,
									type: "password",
									class: "w-full h-14 px-6 bg-white dark:bg-white/[0.03] border border-outline-variant/10 dark:border-white/5 rounded-2xl text-xs font-bold text-on-surface dark:text-white focus:ring-4 focus:ring-secondary/5 outline-none transition-all",
									required: ""
								}, null, 8, ["onUpdate:modelValue"]), [[vModelText, unref(passwordForm).password_confirmation]])]),
								createVNode("div", { class: "pt-6" }, [createVNode(Button_default, {
									type: "submit",
									variant: "secondary",
									loading: unref(passwordForm).processing,
									icon: "key_visualizer",
									class: "w-full !rounded-2xl !h-14 !text-[11px] font-black uppercase shadow-xl shadow-secondary/10"
								}, {
									default: withCtx(() => [createTextVNode("Renovar Credenciales")]),
									_: 1
								}, 8, ["loading"])])
							], 32)]),
							_: 1
						})])]),
						createVNode("div", { class: "bg-surface-container dark:bg-white/[0.02] border border-outline-variant/10 dark:border-white/5 rounded-[3.5rem] p-10 flex flex-col md:flex-row items-center justify-between gap-8" }, [createVNode("div", { class: "flex items-center gap-8 text-center md:text-left" }, [createVNode("div", { class: "w-20 h-20 rounded-[2.5rem] bg-emerald-500/10 flex items-center justify-center shadow-inner relative overflow-hidden" }, [createVNode("div", { class: "absolute inset-0 bg-emerald-500/10 animate-pulse" }), createVNode("span", { class: "material-symbols-rounded text-4xl text-emerald-500 relative z-10" }, "health_metrics")]), createVNode("div", null, [createVNode("h4", { class: "text-2xl font-black text-on-surface dark:text-white uppercase tracking-tighter italic leading-none" }, [createTextVNode("Salud de la Cuenta: "), createVNode("span", { class: "text-emerald-500" }, "EXCELENTE")]), createVNode("p", { class: "text-[11px] font-bold text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.2em] mt-3 leading-relaxed" }, "Tu perfil cumple con todos los protocolos de seguridad de Nexo-Core.")])]), createVNode("div", { class: "flex gap-4" }, [createVNode("div", { class: "flex items-center gap-3 bg-white dark:bg-white/5 px-6 py-4 rounded-2xl border border-outline-variant/10 dark:border-white/5" }, [createVNode("span", { class: "material-symbols-rounded text-emerald-500" }, "security"), createVNode("span", { class: "text-[10px] font-black text-on-surface dark:text-white uppercase tracking-widest" }, "2FA ACTIVO")])])])
					])];
				}),
				_: 1
			}), _parent);
			_push(`<!--]-->`);
		};
	}
});
//#endregion
//#region resources/js/Pages/Profile/Edit.vue
var _sfc_setup = Edit_vue_vue_type_script_setup_true_lang_default.setup;
Edit_vue_vue_type_script_setup_true_lang_default.setup = (props, ctx) => {
	const ssrContext = useSSRContext();
	(ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Profile/Edit.vue");
	return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
var Edit_default = /* @__PURE__ */ _plugin_vue_export_helper_default(Edit_vue_vue_type_script_setup_true_lang_default, [["__scopeId", "data-v-dffe433f"]]);
//#endregion
export { Edit_default as default };

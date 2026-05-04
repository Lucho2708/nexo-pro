import { t as _plugin_vue_export_helper_default } from "./_plugin-vue_export-helper-BjJHhBxR.js";
import { t as AuthenticatedLayout_default } from "./AuthenticatedLayout-BYv1HA3l.js";
import { t as Button_default } from "./Button-5V-KYwxi.js";
import { t as Modal_default } from "./Modal-CeRVVYhy.js";
import { t as Badge_default } from "./Badge-DWJ2qZRx.js";
import { t as Tooltip_default } from "./Tooltip-Ba7P3Kw3.js";
import { t as Select_default } from "./Select-D3C81tr4.js";
import { t as Input_default } from "./Input-DWocsxNw.js";
import { t as ConfirmModal_default } from "./ConfirmModal-CoOh0y5g.js";
import { computed, createTextVNode, createVNode, defineComponent, ref, toDisplayString, unref, useSSRContext, vModelText, withCtx, withDirectives, withModifiers } from "vue";
import { Head, router, useForm } from "@inertiajs/vue3";
import { ssrIncludeBooleanAttr, ssrInterpolate, ssrRenderAttr, ssrRenderClass, ssrRenderComponent, ssrRenderList, ssrRenderStyle } from "vue/server-renderer";
//#region resources/js/Pages/SuperAdmin/Announcements/Index.vue?vue&type=script&setup=true&lang.ts
var Index_vue_vue_type_script_setup_true_lang_default = /* @__PURE__ */ defineComponent({
	layout: AuthenticatedLayout_default,
	__name: "Index",
	__ssrInlineRender: true,
	props: { announcements: {} },
	setup(__props) {
		const props = __props;
		const activeTab = ref("active");
		const showCreateModal = ref(false);
		const isEditing = ref(false);
		const confirmDelete = ref({
			show: false,
			id: null
		});
		const form = useForm({
			id: "",
			title: "",
			message: "",
			type: "info",
			target_role: "all",
			starts_at: "",
			expires_at: "",
			is_active: true
		});
		const filteredAnnouncements = computed(() => {
			const now = /* @__PURE__ */ new Date();
			return props.announcements.filter((ann) => {
				const start = ann.starts_at ? new Date(ann.starts_at) : null;
				const end = ann.expires_at ? new Date(ann.expires_at) : null;
				const isActiveAtMoment = (!start || start <= now) && (!end || end >= now) && ann.is_active;
				const isScheduled = start && start > now && ann.is_active;
				const isExpired = end && end < now || !ann.is_active;
				if (activeTab.value === "active") return isActiveAtMoment;
				if (activeTab.value === "scheduled") return isScheduled;
				if (activeTab.value === "expired") return isExpired;
				return true;
			});
		});
		const submit = () => {
			if (isEditing.value) form.patch(route("superadmin.announcements.update", form.id), { onSuccess: () => {
				showCreateModal.value = false;
				form.reset();
			} });
			else form.post(route("superadmin.announcements.store"), { onSuccess: () => {
				showCreateModal.value = false;
				form.reset();
			} });
		};
		const editAnnouncement = (ann) => {
			isEditing.value = true;
			form.id = ann.id;
			form.title = ann.title;
			form.message = ann.message;
			form.type = ann.type;
			form.target_role = ann.target_role;
			form.starts_at = ann.starts_at ? ann.starts_at.substring(0, 16) : "";
			form.expires_at = ann.expires_at ? ann.expires_at.substring(0, 16) : "";
			form.is_active = ann.is_active;
			showCreateModal.value = true;
		};
		const openCreate = () => {
			isEditing.value = false;
			form.reset();
			showCreateModal.value = true;
		};
		const deleteAnnouncement = (id) => {
			confirmDelete.value = {
				show: true,
				id
			};
		};
		const executeDelete = () => {
			if (confirmDelete.value.id) router.delete(route("superadmin.announcements.destroy", confirmDelete.value.id), { onSuccess: () => {
				confirmDelete.value.show = false;
			} });
		};
		const getStatusInfo = (ann) => {
			const now = /* @__PURE__ */ new Date();
			const start = ann.starts_at ? new Date(ann.starts_at) : null;
			const end = ann.expires_at ? new Date(ann.expires_at) : null;
			if (!ann.is_active) return {
				label: "DESACTIVADO",
				color: "text-gray-400",
				dot: "bg-gray-400"
			};
			if (end && end < now) return {
				label: "EXPIRADO",
				color: "text-rose-500",
				dot: "bg-rose-500"
			};
			if (start && start > now) return {
				label: "PROGRAMADO",
				color: "text-amber-500",
				dot: "bg-amber-500"
			};
			return {
				label: "ACTIVO",
				color: "text-emerald-500",
				dot: "bg-emerald-500"
			};
		};
		const getTypeConfig = (type) => {
			const configs = {
				info: {
					icon: "info",
					color: "text-blue-500",
					bg: "bg-blue-500/10",
					border: "border-blue-500/20"
				},
				warning: {
					icon: "warning",
					color: "text-amber-500",
					bg: "bg-amber-500/10",
					border: "border-amber-500/20"
				},
				danger: {
					icon: "report",
					color: "text-rose-500",
					bg: "bg-rose-500/10",
					border: "border-rose-500/20"
				}
			};
			return configs[type] || configs.info;
		};
		const getTimeRemaining = (expiresAt) => {
			if (!expiresAt) return "SIN EXPIRACIÓN";
			const diff = new Date(expiresAt).getTime() - (/* @__PURE__ */ new Date()).getTime();
			if (diff < 0) return "FINALIZADO";
			const days = Math.floor(diff / (1e3 * 60 * 60 * 24));
			const hours = Math.floor(diff % (1e3 * 60 * 60 * 24) / (1e3 * 60 * 60));
			if (days > 0) return `${days}D ${hours}H RESTANTES`;
			return `${hours} HORAS RESTANTES`;
		};
		return (_ctx, _push, _parent, _attrs) => {
			_push(`<!--[-->`);
			_push(ssrRenderComponent(unref(Head), { title: "Central de Difusión — NEXO-PRO" }, null, _parent));
			_push(`<div class="space-y-8 pb-20 animate-in fade-in duration-700" data-v-80779e60><div class="flex flex-col md:flex-row md:items-center justify-between gap-6 px-1" data-v-80779e60><div class="flex items-center gap-6" data-v-80779e60><div class="w-14 h-14 rounded-2xl bg-primary flex items-center justify-center shadow-2xl shadow-primary/20" data-v-80779e60><span class="material-symbols-rounded text-2xl text-white" data-v-80779e60>campaign</span></div><div data-v-80779e60><h2 class="text-3xl font-black text-on-surface dark:text-white tracking-tighter uppercase italic leading-none" data-v-80779e60>CENTRAL DE <span class="text-primary italic" data-v-80779e60>DIFUSIÓN</span></h2><div class="flex items-center gap-2 mt-2" data-v-80779e60><span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse" data-v-80779e60></span><p class="text-[8px] font-black text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.3em]" data-v-80779e60>Gestión de Comunicación Masiva Nivel 4</p></div></div></div>`);
			_push(ssrRenderComponent(Button_default, {
				variant: "primary",
				icon: "add",
				onClick: openCreate,
				class: "!rounded-xl shadow-lg shadow-primary/20 !text-[10px] font-black uppercase px-8"
			}, {
				default: withCtx((_, _push, _parent, _scopeId) => {
					if (_push) _push(`Crear Difusión`);
					else return [createTextVNode("Crear Difusión")];
				}),
				_: 1
			}, _parent));
			_push(`</div><div class="flex items-center gap-2 p-1.5 bg-surface-container-low dark:bg-white/[0.03] rounded-2xl border border-outline-variant/10 dark:border-white/5 w-fit" data-v-80779e60><!--[-->`);
			ssrRenderList([
				"active",
				"scheduled",
				"expired",
				"all"
			], (tab) => {
				_push(`<button class="${ssrRenderClass([activeTab.value === tab ? "bg-primary text-white shadow-xl shadow-primary/20" : "text-on-surface-variant/50 dark:text-white/30 hover:text-primary", "px-6 py-2.5 rounded-xl text-[9px] font-black uppercase tracking-widest transition-all"])}" data-v-80779e60>${ssrInterpolate(tab === "active" ? "En el Aire" : tab === "scheduled" ? "Próximos" : tab === "expired" ? "Historial" : "Todos")}</button>`);
			});
			_push(`<!--]--></div><div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-2 gap-8" data-v-80779e60><!--[-->`);
			ssrRenderList(filteredAnnouncements.value, (ann) => {
				_push(`<div class="${ssrRenderClass([{ "opacity-75 grayscale-[0.5]": !ann.is_active || new Date(ann.expires_at) < /* @__PURE__ */ new Date() }, "group bg-white dark:bg-[#0b0e14] border border-outline-variant/10 dark:border-white/5 rounded-[2.5rem] p-8 shadow-xl hover:shadow-2xl transition-all relative overflow-hidden"])}" data-v-80779e60><div class="${ssrRenderClass([getTypeConfig(ann.type).bg.replace("/10", ""), "absolute top-0 left-0 right-0 h-1.5 transition-all group-hover:h-2"])}" data-v-80779e60></div><div class="flex justify-between items-start mb-6" data-v-80779e60><div class="flex items-center gap-4" data-v-80779e60><div class="${ssrRenderClass([[getTypeConfig(ann.type).bg, getTypeConfig(ann.type).border], "w-10 h-10 rounded-xl flex items-center justify-center border transition-all group-hover:scale-110"])}" data-v-80779e60><span class="${ssrRenderClass([getTypeConfig(ann.type).color, "material-symbols-rounded text-lg"])}" data-v-80779e60>${ssrInterpolate(getTypeConfig(ann.type).icon)}</span></div><div data-v-80779e60><div class="flex items-center gap-2" data-v-80779e60><span class="${ssrRenderClass([getStatusInfo(ann).color, "text-[8px] font-black uppercase tracking-[0.2em]"])}" data-v-80779e60>${ssrInterpolate(getStatusInfo(ann).label)}</span><span class="${ssrRenderClass([getStatusInfo(ann).dot, "w-1 h-1 rounded-full"])}" data-v-80779e60></span></div><h3 class="text-lg font-black text-on-surface dark:text-white uppercase tracking-tighter leading-none mt-1 group-hover:text-primary transition-colors" data-v-80779e60>${ssrInterpolate(ann.title)}</h3></div></div><div class="flex gap-2" data-v-80779e60>`);
				_push(ssrRenderComponent(Tooltip_default, { text: "Editar" }, {
					default: withCtx((_, _push, _parent, _scopeId) => {
						if (_push) _push(`<button class="w-8 h-8 rounded-lg bg-surface-container dark:bg-white/5 border border-outline-variant/10 dark:border-white/5 flex items-center justify-center text-on-surface-variant dark:text-white/20 hover:bg-primary hover:text-white transition-all" data-v-80779e60${_scopeId}><span class="material-symbols-rounded text-base" data-v-80779e60${_scopeId}>edit</span></button>`);
						else return [createVNode("button", {
							onClick: ($event) => editAnnouncement(ann),
							class: "w-8 h-8 rounded-lg bg-surface-container dark:bg-white/5 border border-outline-variant/10 dark:border-white/5 flex items-center justify-center text-on-surface-variant dark:text-white/20 hover:bg-primary hover:text-white transition-all"
						}, [createVNode("span", { class: "material-symbols-rounded text-base" }, "edit")], 8, ["onClick"])];
					}),
					_: 2
				}, _parent));
				_push(ssrRenderComponent(Tooltip_default, { text: "Eliminar" }, {
					default: withCtx((_, _push, _parent, _scopeId) => {
						if (_push) _push(`<button class="w-8 h-8 rounded-lg bg-surface-container dark:bg-white/5 border border-outline-variant/10 dark:border-white/5 flex items-center justify-center text-on-surface-variant dark:text-white/20 hover:bg-error hover:text-white transition-all" data-v-80779e60${_scopeId}><span class="material-symbols-rounded text-base" data-v-80779e60${_scopeId}>delete</span></button>`);
						else return [createVNode("button", {
							onClick: ($event) => deleteAnnouncement(ann.id),
							class: "w-8 h-8 rounded-lg bg-surface-container dark:bg-white/5 border border-outline-variant/10 dark:border-white/5 flex items-center justify-center text-on-surface-variant dark:text-white/20 hover:bg-error hover:text-white transition-all"
						}, [createVNode("span", { class: "material-symbols-rounded text-base" }, "delete")], 8, ["onClick"])];
					}),
					_: 2
				}, _parent));
				_push(`</div></div><p class="text-sm font-medium text-on-surface-variant/70 dark:text-white/50 leading-relaxed mb-8 italic" data-v-80779e60> &quot;${ssrInterpolate(ann.message)}&quot; </p><div class="flex flex-wrap gap-4 items-center justify-between pt-6 border-t border-outline-variant/5 dark:border-white/5" data-v-80779e60><div class="flex gap-4" data-v-80779e60><div class="flex flex-col" data-v-80779e60><span class="text-[7px] font-black text-on-surface-variant/30 uppercase tracking-widest" data-v-80779e60>Publicado para</span>`);
				_push(ssrRenderComponent(Badge_default, {
					variant: "neutral",
					class: "!px-3 !py-0.5 !text-[8.5px] !font-black !bg-primary/5 !text-primary !border-primary/10 tracking-widest uppercase mt-1"
				}, {
					default: withCtx((_, _push, _parent, _scopeId) => {
						if (_push) _push(`${ssrInterpolate(ann.target_role)}`);
						else return [createTextVNode(toDisplayString(ann.target_role), 1)];
					}),
					_: 2
				}, _parent));
				_push(`</div>`);
				if (ann.expires_at) _push(`<div class="flex flex-col border-l border-outline-variant/10 dark:border-white/10 pl-4" data-v-80779e60><span class="text-[7px] font-black text-on-surface-variant/30 uppercase tracking-widest" data-v-80779e60>TTL — Ciclo de Vida</span><span class="text-[9px] font-black text-on-surface dark:text-white/60 uppercase mt-1 tracking-tighter italic" data-v-80779e60>${ssrInterpolate(getTimeRemaining(ann.expires_at))}</span></div>`);
				else _push(`<!---->`);
				_push(`</div><div class="flex items-center gap-3 bg-surface-container-low dark:bg-white/5 px-4 py-2 rounded-2xl border border-outline-variant/10 dark:border-white/5 shadow-inner" data-v-80779e60><img${ssrRenderAttr("src", `https://api.dicebear.com/7.x/avataaars/svg?seed=${ann.user_id}`)} class="w-6 h-6 rounded-lg bg-primary/10" data-v-80779e60><span class="text-[9px] font-black text-on-surface dark:text-white/40 uppercase tracking-widest" data-v-80779e60>Autoría Core</span></div></div>`);
				if (getStatusInfo(ann).label === "ACTIVO" && ann.expires_at) _push(`<div class="absolute bottom-0 left-0 h-0.5 bg-emerald-500/20" style="${ssrRenderStyle({ "width": "100%" })}" data-v-80779e60><div class="h-full bg-emerald-500 transition-all duration-1000" style="${ssrRenderStyle({ width: "65%" })}" data-v-80779e60></div></div>`);
				else _push(`<!---->`);
				_push(`</div>`);
			});
			_push(`<!--]-->`);
			if (filteredAnnouncements.value.length === 0) _push(`<div class="col-span-full py-32 text-center opacity-30 flex flex-col items-center gap-6" data-v-80779e60><div class="w-24 h-24 rounded-[2.5rem] bg-surface-container dark:bg-white/5 flex items-center justify-center" data-v-80779e60><span class="material-symbols-rounded text-5xl" data-v-80779e60>notifications_paused</span></div><div data-v-80779e60><p class="text-sm font-black uppercase tracking-[0.3em]" data-v-80779e60>Silencio en la Red</p><p class="text-[10px] font-bold uppercase tracking-widest mt-2" data-v-80779e60>No hay anuncios en la categoría: <span class="text-primary" data-v-80779e60>${ssrInterpolate(activeTab.value.toUpperCase())}</span></p></div></div>`);
			else _push(`<!---->`);
			_push(`</div>`);
			_push(ssrRenderComponent(Modal_default, {
				show: showCreateModal.value,
				"max-width": "xl",
				onClose: ($event) => showCreateModal.value = false
			}, {
				default: withCtx((_, _push, _parent, _scopeId) => {
					if (_push) {
						_push(`<div class="p-10 bg-[#0b0e14] text-white" data-v-80779e60${_scopeId}><header class="flex items-start justify-between border-b border-white/5 pb-8 mb-10" data-v-80779e60${_scopeId}><div class="flex items-center gap-6" data-v-80779e60${_scopeId}><div class="w-16 h-16 rounded-[1.8rem] flex items-center justify-center text-white bg-primary shadow-2xl rotate-3" data-v-80779e60${_scopeId}><span class="material-symbols-rounded text-3xl" data-v-80779e60${_scopeId}>${ssrInterpolate(isEditing.value ? "edit_notifications" : "add_alert")}</span></div><div data-v-80779e60${_scopeId}><h3 class="text-3xl font-black tracking-tighter uppercase leading-none italic" data-v-80779e60${_scopeId}>${ssrInterpolate(isEditing.value ? "Ajustar" : "Nueva")} <span class="text-primary font-black" data-v-80779e60${_scopeId}>Difusión</span></h3><p class="text-[10px] font-bold text-white/40 uppercase tracking-[0.3em] mt-2 italic" data-v-80779e60${_scopeId}>Configuración de parámetros de comunicación masiva</p></div></div><button class="text-white/20 hover:text-white transition-colors" data-v-80779e60${_scopeId}><span class="material-symbols-rounded text-3xl" data-v-80779e60${_scopeId}>close</span></button></header><form class="space-y-8" data-v-80779e60${_scopeId}>`);
						_push(ssrRenderComponent(Input_default, {
							modelValue: unref(form).title,
							"onUpdate:modelValue": ($event) => unref(form).title = $event,
							label: "Encabezado del Anuncio",
							placeholder: "Ej: ACTUALIZACIÓN DE KERNEL 4.0...",
							required: "",
							class: "!bg-white/5 !border-white/10 !text-white !h-14 !rounded-2xl"
						}, null, _parent, _scopeId));
						_push(`<div class="space-y-2" data-v-80779e60${_scopeId}><label class="text-[10px] font-black text-white/30 uppercase tracking-[0.3em] pl-2" data-v-80779e60${_scopeId}>Cuerpo del Mensaje (Markdown soportado)</label><textarea rows="4" placeholder="Escribe el mensaje aquí..." class="w-full bg-white/5 border-2 border-white/10 rounded-3xl p-6 text-sm font-medium focus:ring-4 focus:ring-primary/10 outline-none resize-none text-white transition-all focus:border-primary/50" data-v-80779e60${_scopeId}>${ssrInterpolate(unref(form).message)}</textarea></div><div class="grid grid-cols-2 gap-6" data-v-80779e60${_scopeId}>`);
						_push(ssrRenderComponent(Select_default, {
							modelValue: unref(form).type,
							"onUpdate:modelValue": ($event) => unref(form).type = $event,
							label: "Nivel de Prioridad",
							options: [
								{
									value: "info",
									label: "INFORMATIVO"
								},
								{
									value: "warning",
									label: "ADVERTENCIA"
								},
								{
									value: "danger",
									label: "CRÍTICO"
								}
							],
							icon: "bolt",
							class: "!bg-white/5 !border-white/10 !text-white !h-14 !rounded-2xl"
						}, null, _parent, _scopeId));
						_push(ssrRenderComponent(Select_default, {
							modelValue: unref(form).target_role,
							"onUpdate:modelValue": ($event) => unref(form).target_role = $event,
							label: "Segmentación",
							options: [
								{
									value: "all",
									label: "TODOS LOS USUARIOS"
								},
								{
									value: "admin",
									label: "SOLO ADMINISTRADORES"
								},
								{
									value: "owner",
									label: "SOLO PROPIETARIOS"
								}
							],
							icon: "hub",
							class: "!bg-white/5 !border-white/10 !text-white !h-14 !rounded-2xl"
						}, null, _parent, _scopeId));
						_push(`</div><div class="grid grid-cols-2 gap-6" data-v-80779e60${_scopeId}>`);
						_push(ssrRenderComponent(Input_default, {
							modelValue: unref(form).starts_at,
							"onUpdate:modelValue": ($event) => unref(form).starts_at = $event,
							type: "datetime-local",
							label: "Ventana de Inicio",
							class: "!bg-white/5 !border-white/10 !text-white !h-14 !rounded-2xl"
						}, null, _parent, _scopeId));
						_push(ssrRenderComponent(Input_default, {
							modelValue: unref(form).expires_at,
							"onUpdate:modelValue": ($event) => unref(form).expires_at = $event,
							type: "datetime-local",
							label: "Fecha de Expiración",
							class: "!bg-white/5 !border-white/10 !text-white !h-14 !rounded-2xl"
						}, null, _parent, _scopeId));
						_push(`</div><div class="flex items-center gap-4 pt-6" data-v-80779e60${_scopeId}><button type="button" class="flex-1 py-4 bg-white/5 text-white/40 font-black text-[11px] uppercase tracking-[0.3em] rounded-2xl hover:text-white transition-all" data-v-80779e60${_scopeId}> ABORTAR </button><button type="submit"${ssrIncludeBooleanAttr(unref(form).processing) ? " disabled" : ""} class="flex-[2] py-4 bg-primary text-white font-black text-[11px] uppercase tracking-[0.3em] rounded-2xl shadow-2xl shadow-primary/30 hover:scale-[1.02] active:scale-95 transition-all" data-v-80779e60${_scopeId}>${ssrInterpolate(isEditing.value ? "ACTUALIZAR DIFUSIÓN" : "LANZAR ANUNCIO AHORA")}</button></div></form></div>`);
					} else return [createVNode("div", { class: "p-10 bg-[#0b0e14] text-white" }, [createVNode("header", { class: "flex items-start justify-between border-b border-white/5 pb-8 mb-10" }, [createVNode("div", { class: "flex items-center gap-6" }, [createVNode("div", { class: "w-16 h-16 rounded-[1.8rem] flex items-center justify-center text-white bg-primary shadow-2xl rotate-3" }, [createVNode("span", { class: "material-symbols-rounded text-3xl" }, toDisplayString(isEditing.value ? "edit_notifications" : "add_alert"), 1)]), createVNode("div", null, [createVNode("h3", { class: "text-3xl font-black tracking-tighter uppercase leading-none italic" }, [createTextVNode(toDisplayString(isEditing.value ? "Ajustar" : "Nueva") + " ", 1), createVNode("span", { class: "text-primary font-black" }, "Difusión")]), createVNode("p", { class: "text-[10px] font-bold text-white/40 uppercase tracking-[0.3em] mt-2 italic" }, "Configuración de parámetros de comunicación masiva")])]), createVNode("button", {
						onClick: ($event) => showCreateModal.value = false,
						class: "text-white/20 hover:text-white transition-colors"
					}, [createVNode("span", { class: "material-symbols-rounded text-3xl" }, "close")], 8, ["onClick"])]), createVNode("form", {
						onSubmit: withModifiers(submit, ["prevent"]),
						class: "space-y-8"
					}, [
						createVNode(Input_default, {
							modelValue: unref(form).title,
							"onUpdate:modelValue": ($event) => unref(form).title = $event,
							label: "Encabezado del Anuncio",
							placeholder: "Ej: ACTUALIZACIÓN DE KERNEL 4.0...",
							required: "",
							class: "!bg-white/5 !border-white/10 !text-white !h-14 !rounded-2xl"
						}, null, 8, ["modelValue", "onUpdate:modelValue"]),
						createVNode("div", { class: "space-y-2" }, [createVNode("label", { class: "text-[10px] font-black text-white/30 uppercase tracking-[0.3em] pl-2" }, "Cuerpo del Mensaje (Markdown soportado)"), withDirectives(createVNode("textarea", {
							"onUpdate:modelValue": ($event) => unref(form).message = $event,
							rows: "4",
							placeholder: "Escribe el mensaje aquí...",
							class: "w-full bg-white/5 border-2 border-white/10 rounded-3xl p-6 text-sm font-medium focus:ring-4 focus:ring-primary/10 outline-none resize-none text-white transition-all focus:border-primary/50"
						}, null, 8, ["onUpdate:modelValue"]), [[vModelText, unref(form).message]])]),
						createVNode("div", { class: "grid grid-cols-2 gap-6" }, [createVNode(Select_default, {
							modelValue: unref(form).type,
							"onUpdate:modelValue": ($event) => unref(form).type = $event,
							label: "Nivel de Prioridad",
							options: [
								{
									value: "info",
									label: "INFORMATIVO"
								},
								{
									value: "warning",
									label: "ADVERTENCIA"
								},
								{
									value: "danger",
									label: "CRÍTICO"
								}
							],
							icon: "bolt",
							class: "!bg-white/5 !border-white/10 !text-white !h-14 !rounded-2xl"
						}, null, 8, ["modelValue", "onUpdate:modelValue"]), createVNode(Select_default, {
							modelValue: unref(form).target_role,
							"onUpdate:modelValue": ($event) => unref(form).target_role = $event,
							label: "Segmentación",
							options: [
								{
									value: "all",
									label: "TODOS LOS USUARIOS"
								},
								{
									value: "admin",
									label: "SOLO ADMINISTRADORES"
								},
								{
									value: "owner",
									label: "SOLO PROPIETARIOS"
								}
							],
							icon: "hub",
							class: "!bg-white/5 !border-white/10 !text-white !h-14 !rounded-2xl"
						}, null, 8, ["modelValue", "onUpdate:modelValue"])]),
						createVNode("div", { class: "grid grid-cols-2 gap-6" }, [createVNode(Input_default, {
							modelValue: unref(form).starts_at,
							"onUpdate:modelValue": ($event) => unref(form).starts_at = $event,
							type: "datetime-local",
							label: "Ventana de Inicio",
							class: "!bg-white/5 !border-white/10 !text-white !h-14 !rounded-2xl"
						}, null, 8, ["modelValue", "onUpdate:modelValue"]), createVNode(Input_default, {
							modelValue: unref(form).expires_at,
							"onUpdate:modelValue": ($event) => unref(form).expires_at = $event,
							type: "datetime-local",
							label: "Fecha de Expiración",
							class: "!bg-white/5 !border-white/10 !text-white !h-14 !rounded-2xl"
						}, null, 8, ["modelValue", "onUpdate:modelValue"])]),
						createVNode("div", { class: "flex items-center gap-4 pt-6" }, [createVNode("button", {
							type: "button",
							onClick: ($event) => showCreateModal.value = false,
							class: "flex-1 py-4 bg-white/5 text-white/40 font-black text-[11px] uppercase tracking-[0.3em] rounded-2xl hover:text-white transition-all"
						}, " ABORTAR ", 8, ["onClick"]), createVNode("button", {
							type: "submit",
							disabled: unref(form).processing,
							class: "flex-[2] py-4 bg-primary text-white font-black text-[11px] uppercase tracking-[0.3em] rounded-2xl shadow-2xl shadow-primary/30 hover:scale-[1.02] active:scale-95 transition-all"
						}, toDisplayString(isEditing.value ? "ACTUALIZAR DIFUSIÓN" : "LANZAR ANUNCIO AHORA"), 9, ["disabled"])])
					], 32)])];
				}),
				_: 1
			}, _parent));
			_push(ssrRenderComponent(ConfirmModal_default, {
				show: confirmDelete.value.show,
				title: "Borrar Difusión",
				message: "¿Estás seguro de que deseas eliminar este anuncio? Esta acción cortará la conexión de este mensaje con todos los usuarios inmediatamente.",
				"confirm-label": "ELIMINAR AHORA",
				variant: "error",
				loading: unref(form).processing,
				onConfirm: executeDelete,
				onCancel: ($event) => confirmDelete.value.show = false
			}, null, _parent));
			_push(`</div><!--]-->`);
		};
	}
});
//#endregion
//#region resources/js/Pages/SuperAdmin/Announcements/Index.vue
var _sfc_setup = Index_vue_vue_type_script_setup_true_lang_default.setup;
Index_vue_vue_type_script_setup_true_lang_default.setup = (props, ctx) => {
	const ssrContext = useSSRContext();
	(ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/SuperAdmin/Announcements/Index.vue");
	return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
var Index_default = /* @__PURE__ */ _plugin_vue_export_helper_default(Index_vue_vue_type_script_setup_true_lang_default, [["__scopeId", "data-v-80779e60"]]);
//#endregion
export { Index_default as default };

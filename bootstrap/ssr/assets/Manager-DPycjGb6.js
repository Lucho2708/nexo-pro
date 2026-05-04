import { t as _plugin_vue_export_helper_default } from "./_plugin-vue_export-helper-BjJHhBxR.js";
import { t as Table_default } from "./Table-DmKwoFFU.js";
import { t as AuthenticatedLayout_default } from "./AuthenticatedLayout-BYv1HA3l.js";
import { t as Card_default } from "./Card-C2-I3_la.js";
import { t as Button_default } from "./Button-5V-KYwxi.js";
import { t as Modal_default } from "./Modal-CeRVVYhy.js";
import { t as Badge_default } from "./Badge-DWJ2qZRx.js";
import { t as useToast } from "./useToast-Dcf8ak3V.js";
import { Fragment, createBlock, createTextVNode, createVNode, defineComponent, openBlock, ref, renderList, toDisplayString, unref, useSSRContext, vModelText, withCtx, withDirectives, withModifiers } from "vue";
import { Head, router, useForm } from "@inertiajs/vue3";
import { ssrInterpolate, ssrRenderClass, ssrRenderComponent, ssrRenderList } from "vue/server-renderer";
//#region resources/js/Pages/Admin/Reservas/Manager.vue?vue&type=script&setup=true&lang.ts
var Manager_vue_vue_type_script_setup_true_lang_default = /* @__PURE__ */ defineComponent({
	layout: AuthenticatedLayout_default,
	__name: "Manager",
	__ssrInlineRender: true,
	props: { reservas: {} },
	setup(__props) {
		const tableColumns = [
			{
				key: "zona_fecha",
				label: "ACTIVO / CRONOGRAMA",
				sortable: false
			},
			{
				key: "residente",
				label: "USUARIO / UBICACIÓN",
				sortable: false
			},
			{
				key: "horario",
				label: "VECTOR TIEMPO",
				sortable: false
			},
			{
				key: "costo",
				label: "TRANSACCIÓN",
				sortable: false
			},
			{
				key: "estado",
				label: "ESTADO OPERATIVO",
				sortable: false
			},
			{
				key: "acciones",
				label: "",
				sortable: false
			}
		];
		const toast = useToast();
		const selectedReserva = ref(null);
		const form = useForm({
			estado: "",
			notas: ""
		});
		const openManageModal = (res) => {
			selectedReserva.value = res;
			form.estado = res.estado;
			form.notas = res.notas_admin || "";
		};
		const submitUpdate = () => {
			form.patch(route("admin.reservas.status", selectedReserva.value.id), { onSuccess: () => {
				selectedReserva.value = null;
				toast.add("Métrica de reserva actualizada y sincronizada", "success");
			} });
		};
		const getStatusVariant = (status) => {
			return {
				"pendiente": "warning",
				"aprobada": "primary",
				"pagada": "success",
				"cancelada": "neutral",
				"rechazada": "error"
			}[status] || "neutral";
		};
		const getStatusLabel = (status) => {
			return {
				"pendiente": "POR VALIDAR",
				"aprobada": "CONFIRMADO",
				"pagada": "LIQUIDADO",
				"cancelada": "ANULADO",
				"rechazada": "RECHAZADO"
			}[status] || status.toUpperCase();
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
			_push(ssrRenderComponent(unref(Head), { title: "Dispatcher Reservas — NEXO-PRO" }, null, _parent));
			_push(`<div class="space-y-12 pb-32 animate-in fade-in slide-in-from-bottom-4 duration-700" data-v-e72ba16a><div class="flex flex-col md:flex-row md:items-end justify-between gap-8 px-1" data-v-e72ba16a><div class="space-y-3" data-v-e72ba16a><div class="flex items-center gap-3" data-v-e72ba16a><div class="w-1.5 h-6 bg-secondary rounded-full shadow-[0_0_10px_rgba(var(--secondary),0.3)]" data-v-e72ba16a></div><p class="text-[10px] font-black text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.4em] italic leading-none" data-v-e72ba16a>Módulo de Ocupación y Comunal</p></div><h2 class="text-5xl font-black text-on-surface dark:text-white tracking-tighter uppercase italic leading-none" data-v-e72ba16a>Gestor de <span class="text-secondary italic" data-v-e72ba16a>Reservas</span></h2><p class="text-[11px] font-bold text-on-surface-variant/40 dark:text-white/10 uppercase tracking-[0.2em] mt-2 leading-relaxed italic" data-v-e72ba16a>Dispatcher de disponibilidad, aprobación de vectores de tiempo y liquidación de activos</p></div><div class="flex items-center gap-3" data-v-e72ba16a>`);
			_push(ssrRenderComponent(Button_default, {
				variant: "outline",
				icon: "calendar_view_day",
				class: "!rounded-2xl !h-14 !px-8 text-[11px] font-black uppercase italic",
				onClick: ($event) => unref(router).get(_ctx.route("admin.zonas.index"))
			}, {
				default: withCtx((_, _push, _parent, _scopeId) => {
					if (_push) _push(`Configurar Espacios`);
					else return [createTextVNode("Configurar Espacios")];
				}),
				_: 1
			}, _parent));
			_push(`</div></div>`);
			_push(ssrRenderComponent(Card_default, { class: "!p-0 !rounded-[3.5rem] border border-outline-variant/10 dark:border-white/5 shadow-3xl dark:bg-[#0b0e14] overflow-hidden" }, {
				header: withCtx((_, _push, _parent, _scopeId) => {
					if (_push) _push(`<div class="p-10 flex items-center gap-5" data-v-e72ba16a${_scopeId}><div class="w-14 h-14 bg-secondary/10 rounded-2xl flex items-center justify-center text-secondary border border-secondary/20 shadow-inner" data-v-e72ba16a${_scopeId}><span class="material-symbols-rounded text-2xl" data-v-e72ba16a${_scopeId}>event_available</span></div><div data-v-e72ba16a${_scopeId}><h3 class="text-2xl font-black text-on-surface dark:text-white uppercase tracking-tighter italic leading-none" data-v-e72ba16a${_scopeId}>Cronograma <span class="text-secondary italic" data-v-e72ba16a${_scopeId}>Tactical</span></h3><p class="text-[10px] font-bold text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.3em] mt-3 italic" data-v-e72ba16a${_scopeId}>Control maestro de ocupación por zona común</p></div></div>`);
					else return [createVNode("div", { class: "p-10 flex items-center gap-5" }, [createVNode("div", { class: "w-14 h-14 bg-secondary/10 rounded-2xl flex items-center justify-center text-secondary border border-secondary/20 shadow-inner" }, [createVNode("span", { class: "material-symbols-rounded text-2xl" }, "event_available")]), createVNode("div", null, [createVNode("h3", { class: "text-2xl font-black text-on-surface dark:text-white uppercase tracking-tighter italic leading-none" }, [createTextVNode("Cronograma "), createVNode("span", { class: "text-secondary italic" }, "Tactical")]), createVNode("p", { class: "text-[10px] font-bold text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.3em] mt-3 italic" }, "Control maestro de ocupación por zona común")])])];
				}),
				default: withCtx((_, _push, _parent, _scopeId) => {
					if (_push) _push(ssrRenderComponent(Table_default, {
						columns: tableColumns,
						data: __props.reservas,
						class: "border-t border-outline-variant/5 dark:border-white/5"
					}, {
						"cell-zona_fecha": withCtx(({ row }, _push, _parent, _scopeId) => {
							if (_push) _push(`<div class="flex flex-col py-2" data-v-e72ba16a${_scopeId}><span class="text-base font-black text-primary uppercase tracking-tighter italic leading-none" data-v-e72ba16a${_scopeId}>${ssrInterpolate(row.zona.nombre)}</span><div class="flex items-center gap-2 mt-2" data-v-e72ba16a${_scopeId}><div class="w-1 h-3 bg-secondary rounded-full" data-v-e72ba16a${_scopeId}></div><span class="text-[10px] text-on-surface-variant/60 dark:text-white/40 font-black uppercase tracking-widest italic" data-v-e72ba16a${_scopeId}>${ssrInterpolate(new Date(row.fecha).toLocaleDateString("es-ES", {
								day: "2-digit",
								month: "short",
								year: "numeric"
							}))}</span></div></div>`);
							else return [createVNode("div", { class: "flex flex-col py-2" }, [createVNode("span", { class: "text-base font-black text-primary uppercase tracking-tighter italic leading-none" }, toDisplayString(row.zona.nombre), 1), createVNode("div", { class: "flex items-center gap-2 mt-2" }, [createVNode("div", { class: "w-1 h-3 bg-secondary rounded-full" }), createVNode("span", { class: "text-[10px] text-on-surface-variant/60 dark:text-white/40 font-black uppercase tracking-widest italic" }, toDisplayString(new Date(row.fecha).toLocaleDateString("es-ES", {
								day: "2-digit",
								month: "short",
								year: "numeric"
							})), 1)])])];
						}),
						"cell-residente": withCtx(({ row }, _push, _parent, _scopeId) => {
							if (_push) _push(`<div class="flex items-center gap-4 py-2" data-v-e72ba16a${_scopeId}><div class="w-12 h-12 rounded-2xl bg-surface-container-high dark:bg-white/5 flex items-center justify-center text-xs font-black text-secondary border border-outline-variant/10 shadow-sm relative group overflow-hidden" data-v-e72ba16a${_scopeId}><div class="absolute inset-0 bg-secondary/10 translate-y-full group-hover:translate-y-0 transition-transform duration-500" data-v-e72ba16a${_scopeId}></div><span class="relative z-10 material-symbols-rounded" data-v-e72ba16a${_scopeId}>person</span></div><div class="flex flex-col" data-v-e72ba16a${_scopeId}><span class="text-[11px] font-black text-on-surface dark:text-white uppercase italic tracking-tighter leading-none" data-v-e72ba16a${_scopeId}>${ssrInterpolate(row.user.name)}</span><span class="text-[9px] font-bold text-on-surface-variant/40 dark:text-white/20 uppercase tracking-widest mt-1.5 leading-none" data-v-e72ba16a${_scopeId}>TORRE ${ssrInterpolate(row.unidad.torre)} - ${ssrInterpolate(row.unidad.nombre)}</span></div></div>`);
							else return [createVNode("div", { class: "flex items-center gap-4 py-2" }, [createVNode("div", { class: "w-12 h-12 rounded-2xl bg-surface-container-high dark:bg-white/5 flex items-center justify-center text-xs font-black text-secondary border border-outline-variant/10 shadow-sm relative group overflow-hidden" }, [createVNode("div", { class: "absolute inset-0 bg-secondary/10 translate-y-full group-hover:translate-y-0 transition-transform duration-500" }), createVNode("span", { class: "relative z-10 material-symbols-rounded" }, "person")]), createVNode("div", { class: "flex flex-col" }, [createVNode("span", { class: "text-[11px] font-black text-on-surface dark:text-white uppercase italic tracking-tighter leading-none" }, toDisplayString(row.user.name), 1), createVNode("span", { class: "text-[9px] font-bold text-on-surface-variant/40 dark:text-white/20 uppercase tracking-widest mt-1.5 leading-none" }, "TORRE " + toDisplayString(row.unidad.torre) + " - " + toDisplayString(row.unidad.nombre), 1)])])];
						}),
						"cell-horario": withCtx(({ row }, _push, _parent, _scopeId) => {
							if (_push) _push(`<div class="flex items-center gap-3 bg-surface-container dark:bg-white/5 px-4 py-2.5 rounded-xl border border-outline-variant/10 w-fit group hover:border-secondary/30 transition-all" data-v-e72ba16a${_scopeId}><span class="material-symbols-rounded text-base text-secondary animate-pulse" data-v-e72ba16a${_scopeId}>timer</span><span class="text-[11px] font-black text-on-surface dark:text-white italic tracking-widest uppercase" data-v-e72ba16a${_scopeId}>${ssrInterpolate(row.hora_inicio.slice(0, 5))} <span class="text-secondary opacity-40 mx-1" data-v-e72ba16a${_scopeId}>/</span> ${ssrInterpolate(row.hora_fin.slice(0, 5))}</span></div>`);
							else return [createVNode("div", { class: "flex items-center gap-3 bg-surface-container dark:bg-white/5 px-4 py-2.5 rounded-xl border border-outline-variant/10 w-fit group hover:border-secondary/30 transition-all" }, [createVNode("span", { class: "material-symbols-rounded text-base text-secondary animate-pulse" }, "timer"), createVNode("span", { class: "text-[11px] font-black text-on-surface dark:text-white italic tracking-widest uppercase" }, [
								createTextVNode(toDisplayString(row.hora_inicio.slice(0, 5)) + " ", 1),
								createVNode("span", { class: "text-secondary opacity-40 mx-1" }, "/"),
								createTextVNode(" " + toDisplayString(row.hora_fin.slice(0, 5)), 1)
							])])];
						}),
						"cell-costo": withCtx(({ row }, _push, _parent, _scopeId) => {
							if (_push) _push(`<div class="flex flex-col" data-v-e72ba16a${_scopeId}><span class="text-[13px] font-black text-on-surface dark:text-white italic tracking-tighter leading-none whitespace-nowrap" data-v-e72ba16a${_scopeId}>${ssrInterpolate(row.monto_pagado > 0 ? formatCurrency(row.monto_pagado) : "COSTO CERO")}</span><span class="text-[8px] font-black text-on-surface-variant/40 dark:text-white/20 uppercase tracking-widest mt-1 italic" data-v-e72ba16a${_scopeId}>${ssrInterpolate(row.monto_pagado > 0 ? "VÍCTOR LIQUIDADO" : "USO CORTESÍA")}</span></div>`);
							else return [createVNode("div", { class: "flex flex-col" }, [createVNode("span", { class: "text-[13px] font-black text-on-surface dark:text-white italic tracking-tighter leading-none whitespace-nowrap" }, toDisplayString(row.monto_pagado > 0 ? formatCurrency(row.monto_pagado) : "COSTO CERO"), 1), createVNode("span", { class: "text-[8px] font-black text-on-surface-variant/40 dark:text-white/20 uppercase tracking-widest mt-1 italic" }, toDisplayString(row.monto_pagado > 0 ? "VÍCTOR LIQUIDADO" : "USO CORTESÍA"), 1)])];
						}),
						"cell-estado": withCtx(({ row }, _push, _parent, _scopeId) => {
							if (_push) {
								_push(`<div class="flex justify-center" data-v-e72ba16a${_scopeId}>`);
								_push(ssrRenderComponent(Badge_default, {
									variant: getStatusVariant(row.estado),
									class: "!px-6 !py-1 !font-black !text-[8.5px] tracking-[0.15em] uppercase italic border-2 tabular-nums"
								}, {
									default: withCtx((_, _push, _parent, _scopeId) => {
										if (_push) _push(`${ssrInterpolate(getStatusLabel(row.estado))}`);
										else return [createTextVNode(toDisplayString(getStatusLabel(row.estado)), 1)];
									}),
									_: 2
								}, _parent, _scopeId));
								_push(`</div>`);
							} else return [createVNode("div", { class: "flex justify-center" }, [createVNode(Badge_default, {
								variant: getStatusVariant(row.estado),
								class: "!px-6 !py-1 !font-black !text-[8.5px] tracking-[0.15em] uppercase italic border-2 tabular-nums"
							}, {
								default: withCtx(() => [createTextVNode(toDisplayString(getStatusLabel(row.estado)), 1)]),
								_: 2
							}, 1032, ["variant"])])];
						}),
						"cell-acciones": withCtx(({ row }, _push, _parent, _scopeId) => {
							if (_push) {
								_push(`<div class="flex justify-end pr-8" data-v-e72ba16a${_scopeId}>`);
								_push(ssrRenderComponent(Button_default, {
									variant: "primary",
									size: "md",
									icon: "settings_input_component",
									class: "!rounded-2xl !h-12 !px-8 !text-[10px] font-black uppercase italic shadow-lg shadow-primary/10",
									onClick: ($event) => openManageModal(row)
								}, {
									default: withCtx((_, _push, _parent, _scopeId) => {
										if (_push) _push(` Gestionar `);
										else return [createTextVNode(" Gestionar ")];
									}),
									_: 2
								}, _parent, _scopeId));
								_push(`</div>`);
							} else return [createVNode("div", { class: "flex justify-end pr-8" }, [createVNode(Button_default, {
								variant: "primary",
								size: "md",
								icon: "settings_input_component",
								class: "!rounded-2xl !h-12 !px-8 !text-[10px] font-black uppercase italic shadow-lg shadow-primary/10",
								onClick: ($event) => openManageModal(row)
							}, {
								default: withCtx(() => [createTextVNode(" Gestionar ")]),
								_: 1
							}, 8, ["onClick"])])];
						}),
						_: 1
					}, _parent, _scopeId));
					else return [createVNode(Table_default, {
						columns: tableColumns,
						data: __props.reservas,
						class: "border-t border-outline-variant/5 dark:border-white/5"
					}, {
						"cell-zona_fecha": withCtx(({ row }) => [createVNode("div", { class: "flex flex-col py-2" }, [createVNode("span", { class: "text-base font-black text-primary uppercase tracking-tighter italic leading-none" }, toDisplayString(row.zona.nombre), 1), createVNode("div", { class: "flex items-center gap-2 mt-2" }, [createVNode("div", { class: "w-1 h-3 bg-secondary rounded-full" }), createVNode("span", { class: "text-[10px] text-on-surface-variant/60 dark:text-white/40 font-black uppercase tracking-widest italic" }, toDisplayString(new Date(row.fecha).toLocaleDateString("es-ES", {
							day: "2-digit",
							month: "short",
							year: "numeric"
						})), 1)])])]),
						"cell-residente": withCtx(({ row }) => [createVNode("div", { class: "flex items-center gap-4 py-2" }, [createVNode("div", { class: "w-12 h-12 rounded-2xl bg-surface-container-high dark:bg-white/5 flex items-center justify-center text-xs font-black text-secondary border border-outline-variant/10 shadow-sm relative group overflow-hidden" }, [createVNode("div", { class: "absolute inset-0 bg-secondary/10 translate-y-full group-hover:translate-y-0 transition-transform duration-500" }), createVNode("span", { class: "relative z-10 material-symbols-rounded" }, "person")]), createVNode("div", { class: "flex flex-col" }, [createVNode("span", { class: "text-[11px] font-black text-on-surface dark:text-white uppercase italic tracking-tighter leading-none" }, toDisplayString(row.user.name), 1), createVNode("span", { class: "text-[9px] font-bold text-on-surface-variant/40 dark:text-white/20 uppercase tracking-widest mt-1.5 leading-none" }, "TORRE " + toDisplayString(row.unidad.torre) + " - " + toDisplayString(row.unidad.nombre), 1)])])]),
						"cell-horario": withCtx(({ row }) => [createVNode("div", { class: "flex items-center gap-3 bg-surface-container dark:bg-white/5 px-4 py-2.5 rounded-xl border border-outline-variant/10 w-fit group hover:border-secondary/30 transition-all" }, [createVNode("span", { class: "material-symbols-rounded text-base text-secondary animate-pulse" }, "timer"), createVNode("span", { class: "text-[11px] font-black text-on-surface dark:text-white italic tracking-widest uppercase" }, [
							createTextVNode(toDisplayString(row.hora_inicio.slice(0, 5)) + " ", 1),
							createVNode("span", { class: "text-secondary opacity-40 mx-1" }, "/"),
							createTextVNode(" " + toDisplayString(row.hora_fin.slice(0, 5)), 1)
						])])]),
						"cell-costo": withCtx(({ row }) => [createVNode("div", { class: "flex flex-col" }, [createVNode("span", { class: "text-[13px] font-black text-on-surface dark:text-white italic tracking-tighter leading-none whitespace-nowrap" }, toDisplayString(row.monto_pagado > 0 ? formatCurrency(row.monto_pagado) : "COSTO CERO"), 1), createVNode("span", { class: "text-[8px] font-black text-on-surface-variant/40 dark:text-white/20 uppercase tracking-widest mt-1 italic" }, toDisplayString(row.monto_pagado > 0 ? "VÍCTOR LIQUIDADO" : "USO CORTESÍA"), 1)])]),
						"cell-estado": withCtx(({ row }) => [createVNode("div", { class: "flex justify-center" }, [createVNode(Badge_default, {
							variant: getStatusVariant(row.estado),
							class: "!px-6 !py-1 !font-black !text-[8.5px] tracking-[0.15em] uppercase italic border-2 tabular-nums"
						}, {
							default: withCtx(() => [createTextVNode(toDisplayString(getStatusLabel(row.estado)), 1)]),
							_: 2
						}, 1032, ["variant"])])]),
						"cell-acciones": withCtx(({ row }) => [createVNode("div", { class: "flex justify-end pr-8" }, [createVNode(Button_default, {
							variant: "primary",
							size: "md",
							icon: "settings_input_component",
							class: "!rounded-2xl !h-12 !px-8 !text-[10px] font-black uppercase italic shadow-lg shadow-primary/10",
							onClick: ($event) => openManageModal(row)
						}, {
							default: withCtx(() => [createTextVNode(" Gestionar ")]),
							_: 1
						}, 8, ["onClick"])])]),
						_: 1
					}, 8, ["data"])];
				}),
				_: 1
			}, _parent));
			if (selectedReserva.value) _push(ssrRenderComponent(Modal_default, {
				onClose: ($event) => selectedReserva.value = null,
				class: "!max-w-2xl"
			}, {
				title: withCtx((_, _push, _parent, _scopeId) => {
					if (_push) _push(`<div class="flex items-center gap-4" data-v-e72ba16a${_scopeId}><div class="w-12 h-12 bg-secondary/10 rounded-2xl flex items-center justify-center text-secondary border border-secondary/20" data-v-e72ba16a${_scopeId}><span class="material-symbols-rounded text-2xl" data-v-e72ba16a${_scopeId}>published_with_changes</span></div><div data-v-e72ba16a${_scopeId}><h3 class="text-2xl font-black text-on-surface dark:text-white uppercase tracking-tighter italic leading-none" data-v-e72ba16a${_scopeId}>Mando de <span class="text-secondary italic" data-v-e72ba16a${_scopeId}>Aprobación</span></h3><p class="text-[9px] font-bold text-on-surface-variant/40 dark:text-white/20 uppercase tracking-widest mt-1" data-v-e72ba16a${_scopeId}>Sincronización de solicitud ID: #${ssrInterpolate(selectedReserva.value.id)}</p></div></div>`);
					else return [createVNode("div", { class: "flex items-center gap-4" }, [createVNode("div", { class: "w-12 h-12 bg-secondary/10 rounded-2xl flex items-center justify-center text-secondary border border-secondary/20" }, [createVNode("span", { class: "material-symbols-rounded text-2xl" }, "published_with_changes")]), createVNode("div", null, [createVNode("h3", { class: "text-2xl font-black text-on-surface dark:text-white uppercase tracking-tighter italic leading-none" }, [createTextVNode("Mando de "), createVNode("span", { class: "text-secondary italic" }, "Aprobación")]), createVNode("p", { class: "text-[9px] font-bold text-on-surface-variant/40 dark:text-white/20 uppercase tracking-widest mt-1" }, "Sincronización de solicitud ID: #" + toDisplayString(selectedReserva.value.id), 1)])])];
				}),
				default: withCtx((_, _push, _parent, _scopeId) => {
					if (_push) {
						_push(`<div class="space-y-12 mt-12 pb-6" data-v-e72ba16a${_scopeId}><div class="grid grid-cols-2 gap-6 bg-surface-container-low dark:bg-white/[0.03] p-8 rounded-[3rem] border border-outline-variant/10 dark:border-white/5 shadow-inner" data-v-e72ba16a${_scopeId}><div class="space-y-2" data-v-e72ba16a${_scopeId}><p class="text-[10px] font-black text-secondary uppercase tracking-[0.3em] mb-3 italic" data-v-e72ba16a${_scopeId}>ACTIVO DESTINO:</p><p class="text-xl font-black text-on-surface dark:text-white uppercase leading-none italic" data-v-e72ba16a${_scopeId}>${ssrInterpolate(selectedReserva.value.zona.nombre)}</p></div><div class="text-right space-y-2" data-v-e72ba16a${_scopeId}><p class="text-[10px] font-black text-secondary uppercase tracking-[0.3em] mb-3 italic" data-v-e72ba16a${_scopeId}>CRONOGRAMA:</p><p class="text-sm font-black text-on-surface-variant dark:text-white/60 uppercase leading-none italic" data-v-e72ba16a${_scopeId}>${ssrInterpolate(new Date(selectedReserva.value.fecha).toLocaleDateString("es-ES", {
							weekday: "long",
							day: "numeric",
							month: "long"
						}))}</p></div></div><form class="space-y-10" data-v-e72ba16a${_scopeId}><div class="space-y-4" data-v-e72ba16a${_scopeId}><label class="text-[10px] font-black text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.3em] ml-2 italic" data-v-e72ba16a${_scopeId}>Vector de Estado</label><div class="grid grid-cols-2 sm:grid-cols-3 gap-4 px-1" data-v-e72ba16a${_scopeId}><!--[-->`);
						ssrRenderList([
							"pendiente",
							"aprobada",
							"pagada",
							"rechazada",
							"cancelada"
						], (st) => {
							_push(`<button type="button" class="${ssrRenderClass([[unref(form).estado === st ? "border-secondary bg-secondary text-white shadow-xl shadow-secondary/20 scale-105" : "border-outline-variant/10 dark:border-white/5 bg-surface dark:bg-white/5 text-on-surface-variant/60 hover:border-secondary/40"], "h-14 px-4 rounded-2xl border-2 text-[10px] font-black uppercase tracking-widest transition-all duration-300 italic shadow-sm"])}" data-v-e72ba16a${_scopeId}>${ssrInterpolate(getStatusLabel(st))}</button>`);
						});
						_push(`<!--]--></div></div><div class="space-y-4" data-v-e72ba16a${_scopeId}><label class="text-[10px] font-black text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.3em] ml-2 italic" data-v-e72ba16a${_scopeId}>Notas de Inspección</label><textarea rows="4" class="w-full bg-white dark:bg-white/[0.02] border-2 border-outline-variant/10 dark:border-white/5 rounded-[2rem] p-8 text-sm font-medium text-on-surface dark:text-white focus:ring-8 focus:ring-secondary/5 outline-none transition-all placeholder:text-on-surface-variant/20 italic shadow-xl" placeholder="Aclaraciones técnicas para el residente o bitácora interna..." data-v-e72ba16a${_scopeId}>${ssrInterpolate(unref(form).notes)}</textarea></div><div class="flex flex-col gap-4 pt-6 border-t border-outline-variant/10 dark:border-white/5" data-v-e72ba16a${_scopeId}>`);
						_push(ssrRenderComponent(Button_default, {
							type: "submit",
							variant: "primary",
							size: "lg",
							class: "w-full !h-16 !rounded-2xl !text-[11px] font-black uppercase italic shadow-2xl shadow-primary/20",
							disabled: unref(form).processing,
							icon: "verified"
						}, {
							default: withCtx((_, _push, _parent, _scopeId) => {
								if (_push) _push(`${ssrInterpolate(unref(form).processing ? "SINCRONIZANDO..." : "EJECUTAR CAMBIO DE ESTADO")}`);
								else return [createTextVNode(toDisplayString(unref(form).processing ? "SINCRONIZANDO..." : "EJECUTAR CAMBIO DE ESTADO"), 1)];
							}),
							_: 1
						}, _parent, _scopeId));
						_push(ssrRenderComponent(Button_default, {
							type: "button",
							variant: "ghost",
							class: "w-full !text-[9px] font-black uppercase tracking-widest text-on-surface-variant/40",
							onClick: ($event) => selectedReserva.value = null
						}, {
							default: withCtx((_, _push, _parent, _scopeId) => {
								if (_push) _push(`Abortar Gestión`);
								else return [createTextVNode("Abortar Gestión")];
							}),
							_: 1
						}, _parent, _scopeId));
						_push(`</div></form></div>`);
					} else return [createVNode("div", { class: "space-y-12 mt-12 pb-6" }, [createVNode("div", { class: "grid grid-cols-2 gap-6 bg-surface-container-low dark:bg-white/[0.03] p-8 rounded-[3rem] border border-outline-variant/10 dark:border-white/5 shadow-inner" }, [createVNode("div", { class: "space-y-2" }, [createVNode("p", { class: "text-[10px] font-black text-secondary uppercase tracking-[0.3em] mb-3 italic" }, "ACTIVO DESTINO:"), createVNode("p", { class: "text-xl font-black text-on-surface dark:text-white uppercase leading-none italic" }, toDisplayString(selectedReserva.value.zona.nombre), 1)]), createVNode("div", { class: "text-right space-y-2" }, [createVNode("p", { class: "text-[10px] font-black text-secondary uppercase tracking-[0.3em] mb-3 italic" }, "CRONOGRAMA:"), createVNode("p", { class: "text-sm font-black text-on-surface-variant dark:text-white/60 uppercase leading-none italic" }, toDisplayString(new Date(selectedReserva.value.fecha).toLocaleDateString("es-ES", {
						weekday: "long",
						day: "numeric",
						month: "long"
					})), 1)])]), createVNode("form", {
						onSubmit: withModifiers(submitUpdate, ["prevent"]),
						class: "space-y-10"
					}, [
						createVNode("div", { class: "space-y-4" }, [createVNode("label", { class: "text-[10px] font-black text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.3em] ml-2 italic" }, "Vector de Estado"), createVNode("div", { class: "grid grid-cols-2 sm:grid-cols-3 gap-4 px-1" }, [(openBlock(), createBlock(Fragment, null, renderList([
							"pendiente",
							"aprobada",
							"pagada",
							"rechazada",
							"cancelada"
						], (st) => {
							return createVNode("button", {
								key: st,
								type: "button",
								onClick: ($event) => unref(form).estado = st,
								class: ["h-14 px-4 rounded-2xl border-2 text-[10px] font-black uppercase tracking-widest transition-all duration-300 italic shadow-sm", [unref(form).estado === st ? "border-secondary bg-secondary text-white shadow-xl shadow-secondary/20 scale-105" : "border-outline-variant/10 dark:border-white/5 bg-surface dark:bg-white/5 text-on-surface-variant/60 hover:border-secondary/40"]]
							}, toDisplayString(getStatusLabel(st)), 11, ["onClick"]);
						}), 64))])]),
						createVNode("div", { class: "space-y-4" }, [createVNode("label", { class: "text-[10px] font-black text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.3em] ml-2 italic" }, "Notas de Inspección"), withDirectives(createVNode("textarea", {
							"onUpdate:modelValue": ($event) => unref(form).notes = $event,
							rows: "4",
							class: "w-full bg-white dark:bg-white/[0.02] border-2 border-outline-variant/10 dark:border-white/5 rounded-[2rem] p-8 text-sm font-medium text-on-surface dark:text-white focus:ring-8 focus:ring-secondary/5 outline-none transition-all placeholder:text-on-surface-variant/20 italic shadow-xl",
							placeholder: "Aclaraciones técnicas para el residente o bitácora interna..."
						}, null, 8, ["onUpdate:modelValue"]), [[vModelText, unref(form).notes]])]),
						createVNode("div", { class: "flex flex-col gap-4 pt-6 border-t border-outline-variant/10 dark:border-white/5" }, [createVNode(Button_default, {
							type: "submit",
							variant: "primary",
							size: "lg",
							class: "w-full !h-16 !rounded-2xl !text-[11px] font-black uppercase italic shadow-2xl shadow-primary/20",
							disabled: unref(form).processing,
							icon: "verified"
						}, {
							default: withCtx(() => [createTextVNode(toDisplayString(unref(form).processing ? "SINCRONIZANDO..." : "EJECUTAR CAMBIO DE ESTADO"), 1)]),
							_: 1
						}, 8, ["disabled"]), createVNode(Button_default, {
							type: "button",
							variant: "ghost",
							class: "w-full !text-[9px] font-black uppercase tracking-widest text-on-surface-variant/40",
							onClick: ($event) => selectedReserva.value = null
						}, {
							default: withCtx(() => [createTextVNode("Abortar Gestión")]),
							_: 1
						}, 8, ["onClick"])])
					], 32)])];
				}),
				_: 1
			}, _parent));
			else _push(`<!---->`);
			_push(`</div><!--]-->`);
		};
	}
});
//#endregion
//#region resources/js/Pages/Admin/Reservas/Manager.vue
var _sfc_setup = Manager_vue_vue_type_script_setup_true_lang_default.setup;
Manager_vue_vue_type_script_setup_true_lang_default.setup = (props, ctx) => {
	const ssrContext = useSSRContext();
	(ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Admin/Reservas/Manager.vue");
	return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
var Manager_default = /* @__PURE__ */ _plugin_vue_export_helper_default(Manager_vue_vue_type_script_setup_true_lang_default, [["__scopeId", "data-v-e72ba16a"]]);
//#endregion
export { Manager_default as default };

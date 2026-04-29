import { t as _plugin_vue_export_helper_default } from "./_plugin-vue_export-helper-BjJHhBxR.js";
import { t as AuthenticatedLayout_default } from "./AuthenticatedLayout-vxulH9w9.js";
import { t as Card_default } from "./Card-D4IwBRMd.js";
import { t as Button_default } from "./Button-C-byZbTR.js";
import { t as Modal_default } from "./Modal-DfwT9E3X.js";
import { t as Badge_default } from "./Badge-DsYztQFV.js";
import { t as Tooltip_default } from "./Tooltip-IAP-zsdE.js";
import { Fragment, createBlock, createCommentVNode, createTextVNode, createVNode, defineComponent, openBlock, ref, renderList, toDisplayString, unref, useSSRContext, vModelText, withCtx, withDirectives, withModifiers } from "vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import { ssrInterpolate, ssrRenderAttr, ssrRenderComponent, ssrRenderList } from "vue/server-renderer";
//#region resources/js/Pages/Admin/Asambleas/Index.vue?vue&type=script&setup=true&lang.ts
var Index_vue_vue_type_script_setup_true_lang_default = /* @__PURE__ */ defineComponent({
	__name: "Index",
	__ssrInlineRender: true,
	props: { asambleas: {} },
	setup(__props) {
		const showCreateModal = ref(false);
		const form = useForm({
			titulo: "",
			fecha: "",
			hora_inicio: "",
			quorum_esperado: 51
		});
		const submit = () => {
			form.post(route("admin.asambleas.store"), { onSuccess: () => {
				showCreateModal.value = false;
				form.reset();
			} });
		};
		const getStatusTheme = (status) => {
			switch (status) {
				case "in_progress": return {
					label: "EN VIVO",
					variant: "success",
					icon: "sensors"
				};
				case "finished": return {
					label: "FINALIZADA",
					variant: "neutral",
					icon: "task_alt"
				};
				default: return {
					label: "PROGRAMADA",
					variant: "info",
					icon: "calendar_today"
				};
			}
		};
		const formatDate = (dateStr) => {
			return new Date(dateStr).toLocaleDateString("es-ES", {
				weekday: "long",
				day: "numeric",
				month: "long"
			}).toUpperCase();
		};
		return (_ctx, _push, _parent, _attrs) => {
			_push(`<!--[-->`);
			_push(ssrRenderComponent(unref(Head), { title: "Gestión de Asambleas" }, null, _parent));
			_push(ssrRenderComponent(AuthenticatedLayout_default, null, {
				default: withCtx((_, _push, _parent, _scopeId) => {
					if (_push) {
						_push(`<div class="space-y-10" data-v-0dfddcc8${_scopeId}><div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 bg-surface-container-low p-8 rounded-[2.5rem] border border-outline-variant/5 shadow-sm" data-v-0dfddcc8${_scopeId}><div data-v-0dfddcc8${_scopeId}><h1 class="text-4xl font-black text-on-surface tracking-tighter flex items-center gap-4" data-v-0dfddcc8${_scopeId}> ASAMBLEAS <span class="text-primary italic" data-v-0dfddcc8${_scopeId}>VIRTUALES</span></h1><p class="text-on-surface-variant/60 text-[10px] font-black uppercase tracking-[0.3em] mt-2 flex items-center gap-2" data-v-0dfddcc8${_scopeId}><span class="w-2 h-2 rounded-full bg-primary" data-v-0dfddcc8${_scopeId}></span> Gestión de quórum y votaciones en tiempo real </p></div>`);
						_push(ssrRenderComponent(Button_default, {
							onClick: ($event) => showCreateModal.value = true,
							variant: "primary",
							icon: "add_circle",
							class: "!rounded-2xl !py-4 shadow-xl shadow-primary/20 hover:scale-105 transition-all"
						}, {
							default: withCtx((_, _push, _parent, _scopeId) => {
								if (_push) _push(` NUEVA ASAMBLEA `);
								else return [createTextVNode(" NUEVA ASAMBLEA ")];
							}),
							_: 1
						}, _parent, _scopeId));
						_push(`</div>`);
						if (__props.asambleas.length === 0) {
							_push(`<div class="flex flex-col items-center justify-center py-32 bg-surface-container-lowest rounded-[4rem] border-2 border-dashed border-outline-variant/20" data-v-0dfddcc8${_scopeId}><div class="w-24 h-24 bg-primary/5 rounded-[2rem] flex items-center justify-center mb-8 rotate-3 group-hover:rotate-0 transition-transform" data-v-0dfddcc8${_scopeId}><span class="material-symbols-rounded text-5xl text-primary/30" data-v-0dfddcc8${_scopeId}>video_chat</span></div><h3 class="text-2xl font-black text-on-surface uppercase tracking-tight" data-v-0dfddcc8${_scopeId}>Sin eventos programados</h3><p class="text-on-surface-variant/50 max-w-sm text-center mt-3 text-sm font-medium" data-v-0dfddcc8${_scopeId}>Digitaliza las asambleas de tu copropiedad. Votaciones seguras, quórum automático y reportes inmediatos.</p>`);
							_push(ssrRenderComponent(Button_default, {
								onClick: ($event) => showCreateModal.value = true,
								variant: "outline",
								class: "mt-10 !rounded-xl"
							}, {
								default: withCtx((_, _push, _parent, _scopeId) => {
									if (_push) _push(`EMPEZAR AHORA`);
									else return [createTextVNode("EMPEZAR AHORA")];
								}),
								_: 1
							}, _parent, _scopeId));
							_push(`</div>`);
						} else {
							_push(`<div class="grid grid-cols-1 lg:grid-cols-2 gap-8" data-v-0dfddcc8${_scopeId}><!--[-->`);
							ssrRenderList(__props.asambleas, (asamblea) => {
								_push(ssrRenderComponent(Card_default, {
									key: asamblea.id,
									class: "!p-0 !rounded-[3rem] overflow-hidden border border-outline-variant/10 hover:border-primary/30 transition-all duration-500 shadow-lg hover:shadow-2xl flex flex-col group"
								}, {
									default: withCtx((_, _push, _parent, _scopeId) => {
										if (_push) {
											_push(`<div class="p-10 flex-1" data-v-0dfddcc8${_scopeId}><div class="flex justify-between items-start mb-8" data-v-0dfddcc8${_scopeId}>`);
											_push(ssrRenderComponent(Badge_default, {
												variant: getStatusTheme(asamblea.status).variant,
												class: "!px-4 !py-2 !rounded-xl text-[9px] font-black tracking-widest italic"
											}, {
												default: withCtx((_, _push, _parent, _scopeId) => {
													if (_push) _push(`<span class="material-symbols-rounded text-xs mr-2" data-v-0dfddcc8${_scopeId}>${ssrInterpolate(getStatusTheme(asamblea.status).icon)}</span> ${ssrInterpolate(getStatusTheme(asamblea.status).label)}`);
													else return [createVNode("span", { class: "material-symbols-rounded text-xs mr-2" }, toDisplayString(getStatusTheme(asamblea.status).icon), 1), createTextVNode(" " + toDisplayString(getStatusTheme(asamblea.status).label), 1)];
												}),
												_: 2
											}, _parent, _scopeId));
											_push(`<div class="flex items-center gap-2" data-v-0dfddcc8${_scopeId}><span class="text-[9px] font-black text-on-surface-variant/30 uppercase" data-v-0dfddcc8${_scopeId}>ID EVENTO</span><span class="bg-surface-container-high px-3 py-1 rounded-lg text-[10px] font-bold text-on-surface" data-v-0dfddcc8${_scopeId}>#${ssrInterpolate(asamblea.id)}</span></div></div><h3 class="text-2xl font-black text-on-surface group-hover:text-primary transition-colors leading-tight mb-8 uppercase tracking-tighter" data-v-0dfddcc8${_scopeId}>${ssrInterpolate(asamblea.titulo)}</h3><div class="grid grid-cols-2 gap-6" data-v-0dfddcc8${_scopeId}><div class="bg-surface-container-lowest p-5 rounded-[2rem] border border-outline-variant/5" data-v-0dfddcc8${_scopeId}><span class="text-[8px] font-black text-on-surface-variant/40 uppercase tracking-widest block mb-2" data-v-0dfddcc8${_scopeId}>CALENDARIO</span><div class="flex items-center gap-3" data-v-0dfddcc8${_scopeId}><div class="w-10 h-10 rounded-xl bg-primary/5 flex items-center justify-center" data-v-0dfddcc8${_scopeId}><span class="material-symbols-rounded text-primary text-xl" data-v-0dfddcc8${_scopeId}>event</span></div><div class="flex flex-col" data-v-0dfddcc8${_scopeId}><span class="text-[11px] font-black text-on-surface leading-none" data-v-0dfddcc8${_scopeId}>${ssrInterpolate(formatDate(asamblea.fecha))}</span><span class="text-[9px] font-bold text-on-surface-variant italic mt-1" data-v-0dfddcc8${_scopeId}>${ssrInterpolate(new Date(asamblea.fecha).toLocaleTimeString([], {
												hour: "2-digit",
												minute: "2-digit"
											}))}</span></div></div></div><div class="bg-surface-container-lowest p-5 rounded-[2rem] border border-outline-variant/5" data-v-0dfddcc8${_scopeId}><span class="text-[8px] font-black text-on-surface-variant/40 uppercase tracking-widest block mb-2" data-v-0dfddcc8${_scopeId}>ESTADÍSTICAS</span><div class="flex items-center gap-3" data-v-0dfddcc8${_scopeId}><div class="w-10 h-10 rounded-xl bg-secondary/5 flex items-center justify-center" data-v-0dfddcc8${_scopeId}><span class="material-symbols-rounded text-secondary text-xl" data-v-0dfddcc8${_scopeId}>ballot</span></div><div class="flex flex-col" data-v-0dfddcc8${_scopeId}><span class="text-[11px] font-black text-on-surface leading-none" data-v-0dfddcc8${_scopeId}>${ssrInterpolate(asamblea.preguntas_count)} PREGUNTAS</span><span class="text-[9px] font-bold text-on-surface-variant italic mt-1" data-v-0dfddcc8${_scopeId}>Quórum req: ${ssrInterpolate(asamblea.settings?.quorum_esperado)}%</span></div></div></div></div></div><div class="bg-surface-container-high/30 backdrop-blur-xl border-t border-outline-variant/5 p-8 flex items-center justify-between" data-v-0dfddcc8${_scopeId}><div class="flex items-center gap-4" data-v-0dfddcc8${_scopeId}><div class="flex -space-x-3" data-v-0dfddcc8${_scopeId}><!--[-->`);
											ssrRenderList(3, (i) => {
												_push(`<div class="w-9 h-9 rounded-2xl border-4 border-surface-container bg-slate-200 flex items-center justify-center text-[10px] font-black text-on-surface/50 overflow-hidden ring-1 ring-black/5" data-v-0dfddcc8${_scopeId}><img${ssrRenderAttr("src", `https://api.dicebear.com/7.x/avataaars/svg?seed=${i}`)} class="w-full h-full object-cover" data-v-0dfddcc8${_scopeId}></div>`);
											});
											_push(`<!--]--><div class="w-9 h-9 rounded-2xl border-4 border-surface-container bg-primary text-white flex items-center justify-center text-[9px] font-black shadow-lg ring-1 ring-primary/20" data-v-0dfddcc8${_scopeId}> +0 </div></div><span class="text-[9px] font-black text-on-surface-variant/50 uppercase tracking-widest" data-v-0dfddcc8${_scopeId}>CONEXIONES ACTIVAS</span></div><div class="flex gap-3" data-v-0dfddcc8${_scopeId}>`);
											_push(ssrRenderComponent(Tooltip_default, { text: asamblea.status === "in_progress" ? "Finalizar Asamblea" : "Activar Asamblea" }, {
												default: withCtx((_, _push, _parent, _scopeId) => {
													if (_push) _push(ssrRenderComponent(unref(Link), {
														href: _ctx.route("admin.asambleas.toggle", asamblea.id),
														method: "patch",
														as: "button",
														class: ["w-12 h-12 rounded-2xl flex items-center justify-center transition-all shadow-lg active:scale-95", asamblea.status === "in_progress" ? "bg-error/10 text-error hover:bg-error hover:text-white shadow-error/10" : "bg-emerald-500/10 text-emerald-500 hover:bg-emerald-500 hover:text-white shadow-emerald-500/10"]
													}, {
														default: withCtx((_, _push, _parent, _scopeId) => {
															if (_push) _push(`<span class="material-symbols-rounded text-xl" data-v-0dfddcc8${_scopeId}>${ssrInterpolate(asamblea.status === "in_progress" ? "stop_circle" : "play_circle")}</span>`);
															else return [createVNode("span", { class: "material-symbols-rounded text-xl" }, toDisplayString(asamblea.status === "in_progress" ? "stop_circle" : "play_circle"), 1)];
														}),
														_: 2
													}, _parent, _scopeId));
													else return [createVNode(unref(Link), {
														href: _ctx.route("admin.asambleas.toggle", asamblea.id),
														method: "patch",
														as: "button",
														class: ["w-12 h-12 rounded-2xl flex items-center justify-center transition-all shadow-lg active:scale-95", asamblea.status === "in_progress" ? "bg-error/10 text-error hover:bg-error hover:text-white shadow-error/10" : "bg-emerald-500/10 text-emerald-500 hover:bg-emerald-500 hover:text-white shadow-emerald-500/10"]
													}, {
														default: withCtx(() => [createVNode("span", { class: "material-symbols-rounded text-xl" }, toDisplayString(asamblea.status === "in_progress" ? "stop_circle" : "play_circle"), 1)]),
														_: 2
													}, 1032, ["href", "class"])];
												}),
												_: 2
											}, _parent, _scopeId));
											_push(ssrRenderComponent(Tooltip_default, { text: "Entrar como Moderador" }, {
												default: withCtx((_, _push, _parent, _scopeId) => {
													if (_push) if (asamblea.status === "in_progress") _push(ssrRenderComponent(unref(Link), {
														href: _ctx.route("asambleas.show", asamblea.id),
														class: "bg-primary text-white w-12 h-12 rounded-2xl flex items-center justify-center hover:scale-110 active:scale-95 transition-all shadow-xl shadow-primary/30"
													}, {
														default: withCtx((_, _push, _parent, _scopeId) => {
															if (_push) _push(`<span class="material-symbols-rounded text-xl" data-v-0dfddcc8${_scopeId}>login</span>`);
															else return [createVNode("span", { class: "material-symbols-rounded text-xl" }, "login")];
														}),
														_: 2
													}, _parent, _scopeId));
													else _push(`<!---->`);
													else return [asamblea.status === "in_progress" ? (openBlock(), createBlock(unref(Link), {
														key: 0,
														href: _ctx.route("asambleas.show", asamblea.id),
														class: "bg-primary text-white w-12 h-12 rounded-2xl flex items-center justify-center hover:scale-110 active:scale-95 transition-all shadow-xl shadow-primary/30"
													}, {
														default: withCtx(() => [createVNode("span", { class: "material-symbols-rounded text-xl" }, "login")]),
														_: 1
													}, 8, ["href"])) : createCommentVNode("", true)];
												}),
												_: 2
											}, _parent, _scopeId));
											if (asamblea.status === "finished") _push(ssrRenderComponent(Tooltip_default, { text: "Descargar Auditoría" }, {
												default: withCtx((_, _push, _parent, _scopeId) => {
													if (_push) _push(`<a${ssrRenderAttr("href", _ctx.route("asambleas.report", asamblea.id))} class="bg-secondary text-white w-12 h-12 rounded-2xl flex items-center justify-center hover:scale-110 active:scale-95 transition-all shadow-xl shadow-secondary/30" data-v-0dfddcc8${_scopeId}><span class="material-symbols-rounded text-xl" data-v-0dfddcc8${_scopeId}>picture_as_pdf</span></a>`);
													else return [createVNode("a", {
														href: _ctx.route("asambleas.report", asamblea.id),
														class: "bg-secondary text-white w-12 h-12 rounded-2xl flex items-center justify-center hover:scale-110 active:scale-95 transition-all shadow-xl shadow-secondary/30"
													}, [createVNode("span", { class: "material-symbols-rounded text-xl" }, "picture_as_pdf")], 8, ["href"])];
												}),
												_: 2
											}, _parent, _scopeId));
											else _push(`<!---->`);
											if (asamblea.status !== "in_progress") _push(ssrRenderComponent(Tooltip_default, { text: "Eliminar Evento" }, {
												default: withCtx((_, _push, _parent, _scopeId) => {
													if (_push) _push(ssrRenderComponent(unref(Link), {
														href: _ctx.route("admin.asambleas.destroy", asamblea.id),
														method: "delete",
														as: "button",
														class: "bg-surface-container-highest text-on-surface-variant/40 hover:text-error w-12 h-12 rounded-2xl flex items-center justify-center transition-all"
													}, {
														default: withCtx((_, _push, _parent, _scopeId) => {
															if (_push) _push(`<span class="material-symbols-rounded text-xl" data-v-0dfddcc8${_scopeId}>delete</span>`);
															else return [createVNode("span", { class: "material-symbols-rounded text-xl" }, "delete")];
														}),
														_: 2
													}, _parent, _scopeId));
													else return [createVNode(unref(Link), {
														href: _ctx.route("admin.asambleas.destroy", asamblea.id),
														method: "delete",
														as: "button",
														class: "bg-surface-container-highest text-on-surface-variant/40 hover:text-error w-12 h-12 rounded-2xl flex items-center justify-center transition-all"
													}, {
														default: withCtx(() => [createVNode("span", { class: "material-symbols-rounded text-xl" }, "delete")]),
														_: 1
													}, 8, ["href"])];
												}),
												_: 2
											}, _parent, _scopeId));
											else _push(`<!---->`);
											_push(`</div></div>`);
										} else return [createVNode("div", { class: "p-10 flex-1" }, [
											createVNode("div", { class: "flex justify-between items-start mb-8" }, [createVNode(Badge_default, {
												variant: getStatusTheme(asamblea.status).variant,
												class: "!px-4 !py-2 !rounded-xl text-[9px] font-black tracking-widest italic"
											}, {
												default: withCtx(() => [createVNode("span", { class: "material-symbols-rounded text-xs mr-2" }, toDisplayString(getStatusTheme(asamblea.status).icon), 1), createTextVNode(" " + toDisplayString(getStatusTheme(asamblea.status).label), 1)]),
												_: 2
											}, 1032, ["variant"]), createVNode("div", { class: "flex items-center gap-2" }, [createVNode("span", { class: "text-[9px] font-black text-on-surface-variant/30 uppercase" }, "ID EVENTO"), createVNode("span", { class: "bg-surface-container-high px-3 py-1 rounded-lg text-[10px] font-bold text-on-surface" }, "#" + toDisplayString(asamblea.id), 1)])]),
											createVNode("h3", { class: "text-2xl font-black text-on-surface group-hover:text-primary transition-colors leading-tight mb-8 uppercase tracking-tighter" }, toDisplayString(asamblea.titulo), 1),
											createVNode("div", { class: "grid grid-cols-2 gap-6" }, [createVNode("div", { class: "bg-surface-container-lowest p-5 rounded-[2rem] border border-outline-variant/5" }, [createVNode("span", { class: "text-[8px] font-black text-on-surface-variant/40 uppercase tracking-widest block mb-2" }, "CALENDARIO"), createVNode("div", { class: "flex items-center gap-3" }, [createVNode("div", { class: "w-10 h-10 rounded-xl bg-primary/5 flex items-center justify-center" }, [createVNode("span", { class: "material-symbols-rounded text-primary text-xl" }, "event")]), createVNode("div", { class: "flex flex-col" }, [createVNode("span", { class: "text-[11px] font-black text-on-surface leading-none" }, toDisplayString(formatDate(asamblea.fecha)), 1), createVNode("span", { class: "text-[9px] font-bold text-on-surface-variant italic mt-1" }, toDisplayString(new Date(asamblea.fecha).toLocaleTimeString([], {
												hour: "2-digit",
												minute: "2-digit"
											})), 1)])])]), createVNode("div", { class: "bg-surface-container-lowest p-5 rounded-[2rem] border border-outline-variant/5" }, [createVNode("span", { class: "text-[8px] font-black text-on-surface-variant/40 uppercase tracking-widest block mb-2" }, "ESTADÍSTICAS"), createVNode("div", { class: "flex items-center gap-3" }, [createVNode("div", { class: "w-10 h-10 rounded-xl bg-secondary/5 flex items-center justify-center" }, [createVNode("span", { class: "material-symbols-rounded text-secondary text-xl" }, "ballot")]), createVNode("div", { class: "flex flex-col" }, [createVNode("span", { class: "text-[11px] font-black text-on-surface leading-none" }, toDisplayString(asamblea.preguntas_count) + " PREGUNTAS", 1), createVNode("span", { class: "text-[9px] font-bold text-on-surface-variant italic mt-1" }, "Quórum req: " + toDisplayString(asamblea.settings?.quorum_esperado) + "%", 1)])])])])
										]), createVNode("div", { class: "bg-surface-container-high/30 backdrop-blur-xl border-t border-outline-variant/5 p-8 flex items-center justify-between" }, [createVNode("div", { class: "flex items-center gap-4" }, [createVNode("div", { class: "flex -space-x-3" }, [(openBlock(), createBlock(Fragment, null, renderList(3, (i) => {
											return createVNode("div", {
												key: i,
												class: "w-9 h-9 rounded-2xl border-4 border-surface-container bg-slate-200 flex items-center justify-center text-[10px] font-black text-on-surface/50 overflow-hidden ring-1 ring-black/5"
											}, [createVNode("img", {
												src: `https://api.dicebear.com/7.x/avataaars/svg?seed=${i}`,
												class: "w-full h-full object-cover"
											}, null, 8, ["src"])]);
										}), 64)), createVNode("div", { class: "w-9 h-9 rounded-2xl border-4 border-surface-container bg-primary text-white flex items-center justify-center text-[9px] font-black shadow-lg ring-1 ring-primary/20" }, " +0 ")]), createVNode("span", { class: "text-[9px] font-black text-on-surface-variant/50 uppercase tracking-widest" }, "CONEXIONES ACTIVAS")]), createVNode("div", { class: "flex gap-3" }, [
											createVNode(Tooltip_default, { text: asamblea.status === "in_progress" ? "Finalizar Asamblea" : "Activar Asamblea" }, {
												default: withCtx(() => [createVNode(unref(Link), {
													href: _ctx.route("admin.asambleas.toggle", asamblea.id),
													method: "patch",
													as: "button",
													class: ["w-12 h-12 rounded-2xl flex items-center justify-center transition-all shadow-lg active:scale-95", asamblea.status === "in_progress" ? "bg-error/10 text-error hover:bg-error hover:text-white shadow-error/10" : "bg-emerald-500/10 text-emerald-500 hover:bg-emerald-500 hover:text-white shadow-emerald-500/10"]
												}, {
													default: withCtx(() => [createVNode("span", { class: "material-symbols-rounded text-xl" }, toDisplayString(asamblea.status === "in_progress" ? "stop_circle" : "play_circle"), 1)]),
													_: 2
												}, 1032, ["href", "class"])]),
												_: 2
											}, 1032, ["text"]),
											createVNode(Tooltip_default, { text: "Entrar como Moderador" }, {
												default: withCtx(() => [asamblea.status === "in_progress" ? (openBlock(), createBlock(unref(Link), {
													key: 0,
													href: _ctx.route("asambleas.show", asamblea.id),
													class: "bg-primary text-white w-12 h-12 rounded-2xl flex items-center justify-center hover:scale-110 active:scale-95 transition-all shadow-xl shadow-primary/30"
												}, {
													default: withCtx(() => [createVNode("span", { class: "material-symbols-rounded text-xl" }, "login")]),
													_: 1
												}, 8, ["href"])) : createCommentVNode("", true)]),
												_: 2
											}, 1024),
											asamblea.status === "finished" ? (openBlock(), createBlock(Tooltip_default, {
												key: 0,
												text: "Descargar Auditoría"
											}, {
												default: withCtx(() => [createVNode("a", {
													href: _ctx.route("asambleas.report", asamblea.id),
													class: "bg-secondary text-white w-12 h-12 rounded-2xl flex items-center justify-center hover:scale-110 active:scale-95 transition-all shadow-xl shadow-secondary/30"
												}, [createVNode("span", { class: "material-symbols-rounded text-xl" }, "picture_as_pdf")], 8, ["href"])]),
												_: 2
											}, 1024)) : createCommentVNode("", true),
											asamblea.status !== "in_progress" ? (openBlock(), createBlock(Tooltip_default, {
												key: 1,
												text: "Eliminar Evento"
											}, {
												default: withCtx(() => [createVNode(unref(Link), {
													href: _ctx.route("admin.asambleas.destroy", asamblea.id),
													method: "delete",
													as: "button",
													class: "bg-surface-container-highest text-on-surface-variant/40 hover:text-error w-12 h-12 rounded-2xl flex items-center justify-center transition-all"
												}, {
													default: withCtx(() => [createVNode("span", { class: "material-symbols-rounded text-xl" }, "delete")]),
													_: 1
												}, 8, ["href"])]),
												_: 2
											}, 1024)) : createCommentVNode("", true)
										])])];
									}),
									_: 2
								}, _parent, _scopeId));
							});
							_push(`<!--]--></div>`);
						}
						_push(`</div>`);
						_push(ssrRenderComponent(Modal_default, {
							show: showCreateModal.value,
							onClose: ($event) => showCreateModal.value = false,
							"max-width": "lg",
							title: "Programar Asamblea"
						}, {
							default: withCtx((_, _push, _parent, _scopeId) => {
								if (_push) {
									_push(`<div class="relative" data-v-0dfddcc8${_scopeId}><div class="absolute -top-24 -right-24 w-64 h-64 bg-primary/5 rounded-full blur-3xl" data-v-0dfddcc8${_scopeId}></div><form class="space-y-8 mt-4" data-v-0dfddcc8${_scopeId}><div class="space-y-3" data-v-0dfddcc8${_scopeId}><label class="text-[9px] font-black uppercase tracking-[0.2em] text-on-surface-variant/50 ml-1" data-v-0dfddcc8${_scopeId}>NOMBRE DEL EVENTO</label><input${ssrRenderAttr("value", unref(form).titulo)} type="text" class="w-full bg-surface-container-high border-none rounded-2xl px-6 py-5 focus:ring-4 focus:ring-primary/10 transition-all font-bold text-on-surface placeholder:text-on-surface-variant/20 shadow-inner" placeholder="Ej: ASAMBLEA ORDINARIA 2026" required data-v-0dfddcc8${_scopeId}></div><div class="grid grid-cols-2 gap-6" data-v-0dfddcc8${_scopeId}><div class="space-y-3" data-v-0dfddcc8${_scopeId}><label class="text-[9px] font-black uppercase tracking-[0.2em] text-on-surface-variant/50 ml-1" data-v-0dfddcc8${_scopeId}>FECHA PROGRAMADA</label><input${ssrRenderAttr("value", unref(form).fecha)} type="date" class="w-full bg-surface-container-high border-none rounded-2xl px-6 py-5 focus:ring-4 focus:ring-primary/10 transition-all font-bold text-on-surface shadow-inner" required data-v-0dfddcc8${_scopeId}></div><div class="space-y-3" data-v-0dfddcc8${_scopeId}><label class="text-[9px] font-black uppercase tracking-[0.2em] text-on-surface-variant/50 ml-1" data-v-0dfddcc8${_scopeId}>HORA DE INICIO</label><input${ssrRenderAttr("value", unref(form).hora_inicio)} type="time" class="w-full bg-surface-container-high border-none rounded-2xl px-6 py-5 focus:ring-4 focus:ring-primary/10 transition-all font-bold text-on-surface shadow-inner" required data-v-0dfddcc8${_scopeId}></div></div><div class="space-y-4" data-v-0dfddcc8${_scopeId}><div class="flex justify-between items-end" data-v-0dfddcc8${_scopeId}><label class="text-[9px] font-black uppercase tracking-[0.2em] text-on-surface-variant/50 ml-1" data-v-0dfddcc8${_scopeId}>QUÓRUM MÍNIMO EXIGIDO</label><span class="text-2xl font-black text-primary" data-v-0dfddcc8${_scopeId}>${ssrInterpolate(unref(form).quorum_esperado)}%</span></div><div class="relative py-2" data-v-0dfddcc8${_scopeId}><input${ssrRenderAttr("value", unref(form).quorum_esperado)} type="range" min="0" max="100" class="w-full h-2 bg-surface-container-high rounded-full appearance-none cursor-pointer accent-primary" data-v-0dfddcc8${_scopeId}></div></div><div class="pt-8 flex gap-4" data-v-0dfddcc8${_scopeId}>`);
									_push(ssrRenderComponent(Button_default, {
										onClick: ($event) => showCreateModal.value = false,
										type: "button",
										variant: "outline",
										class: "flex-1 !rounded-[1.5rem] !py-5 uppercase text-[10px] tracking-widest font-black"
									}, {
										default: withCtx((_, _push, _parent, _scopeId) => {
											if (_push) _push(`CANCELAR`);
											else return [createTextVNode("CANCELAR")];
										}),
										_: 1
									}, _parent, _scopeId));
									_push(ssrRenderComponent(Button_default, {
										type: "submit",
										variant: "primary",
										class: "flex-1 !rounded-[1.5rem] !py-5 uppercase text-[10px] tracking-widest font-black shadow-xl shadow-primary/20",
										loading: unref(form).processing
									}, {
										default: withCtx((_, _push, _parent, _scopeId) => {
											if (_push) _push(`PROGRAMAR EVENTO`);
											else return [createTextVNode("PROGRAMAR EVENTO")];
										}),
										_: 1
									}, _parent, _scopeId));
									_push(`</div></form></div>`);
								} else return [createVNode("div", { class: "relative" }, [createVNode("div", { class: "absolute -top-24 -right-24 w-64 h-64 bg-primary/5 rounded-full blur-3xl" }), createVNode("form", {
									onSubmit: withModifiers(submit, ["prevent"]),
									class: "space-y-8 mt-4"
								}, [
									createVNode("div", { class: "space-y-3" }, [createVNode("label", { class: "text-[9px] font-black uppercase tracking-[0.2em] text-on-surface-variant/50 ml-1" }, "NOMBRE DEL EVENTO"), withDirectives(createVNode("input", {
										"onUpdate:modelValue": ($event) => unref(form).titulo = $event,
										type: "text",
										class: "w-full bg-surface-container-high border-none rounded-2xl px-6 py-5 focus:ring-4 focus:ring-primary/10 transition-all font-bold text-on-surface placeholder:text-on-surface-variant/20 shadow-inner",
										placeholder: "Ej: ASAMBLEA ORDINARIA 2026",
										required: ""
									}, null, 8, ["onUpdate:modelValue"]), [[vModelText, unref(form).titulo]])]),
									createVNode("div", { class: "grid grid-cols-2 gap-6" }, [createVNode("div", { class: "space-y-3" }, [createVNode("label", { class: "text-[9px] font-black uppercase tracking-[0.2em] text-on-surface-variant/50 ml-1" }, "FECHA PROGRAMADA"), withDirectives(createVNode("input", {
										"onUpdate:modelValue": ($event) => unref(form).fecha = $event,
										type: "date",
										class: "w-full bg-surface-container-high border-none rounded-2xl px-6 py-5 focus:ring-4 focus:ring-primary/10 transition-all font-bold text-on-surface shadow-inner",
										required: ""
									}, null, 8, ["onUpdate:modelValue"]), [[vModelText, unref(form).fecha]])]), createVNode("div", { class: "space-y-3" }, [createVNode("label", { class: "text-[9px] font-black uppercase tracking-[0.2em] text-on-surface-variant/50 ml-1" }, "HORA DE INICIO"), withDirectives(createVNode("input", {
										"onUpdate:modelValue": ($event) => unref(form).hora_inicio = $event,
										type: "time",
										class: "w-full bg-surface-container-high border-none rounded-2xl px-6 py-5 focus:ring-4 focus:ring-primary/10 transition-all font-bold text-on-surface shadow-inner",
										required: ""
									}, null, 8, ["onUpdate:modelValue"]), [[vModelText, unref(form).hora_inicio]])])]),
									createVNode("div", { class: "space-y-4" }, [createVNode("div", { class: "flex justify-between items-end" }, [createVNode("label", { class: "text-[9px] font-black uppercase tracking-[0.2em] text-on-surface-variant/50 ml-1" }, "QUÓRUM MÍNIMO EXIGIDO"), createVNode("span", { class: "text-2xl font-black text-primary" }, toDisplayString(unref(form).quorum_esperado) + "%", 1)]), createVNode("div", { class: "relative py-2" }, [withDirectives(createVNode("input", {
										"onUpdate:modelValue": ($event) => unref(form).quorum_esperado = $event,
										type: "range",
										min: "0",
										max: "100",
										class: "w-full h-2 bg-surface-container-high rounded-full appearance-none cursor-pointer accent-primary"
									}, null, 8, ["onUpdate:modelValue"]), [[vModelText, unref(form).quorum_esperado]])])]),
									createVNode("div", { class: "pt-8 flex gap-4" }, [createVNode(Button_default, {
										onClick: ($event) => showCreateModal.value = false,
										type: "button",
										variant: "outline",
										class: "flex-1 !rounded-[1.5rem] !py-5 uppercase text-[10px] tracking-widest font-black"
									}, {
										default: withCtx(() => [createTextVNode("CANCELAR")]),
										_: 1
									}, 8, ["onClick"]), createVNode(Button_default, {
										type: "submit",
										variant: "primary",
										class: "flex-1 !rounded-[1.5rem] !py-5 uppercase text-[10px] tracking-widest font-black shadow-xl shadow-primary/20",
										loading: unref(form).processing
									}, {
										default: withCtx(() => [createTextVNode("PROGRAMAR EVENTO")]),
										_: 1
									}, 8, ["loading"])])
								], 32)])];
							}),
							_: 1
						}, _parent, _scopeId));
					} else return [createVNode("div", { class: "space-y-10" }, [createVNode("div", { class: "flex flex-col md:flex-row justify-between items-start md:items-center gap-6 bg-surface-container-low p-8 rounded-[2.5rem] border border-outline-variant/5 shadow-sm" }, [createVNode("div", null, [createVNode("h1", { class: "text-4xl font-black text-on-surface tracking-tighter flex items-center gap-4" }, [createTextVNode(" ASAMBLEAS "), createVNode("span", { class: "text-primary italic" }, "VIRTUALES")]), createVNode("p", { class: "text-on-surface-variant/60 text-[10px] font-black uppercase tracking-[0.3em] mt-2 flex items-center gap-2" }, [createVNode("span", { class: "w-2 h-2 rounded-full bg-primary" }), createTextVNode(" Gestión de quórum y votaciones en tiempo real ")])]), createVNode(Button_default, {
						onClick: ($event) => showCreateModal.value = true,
						variant: "primary",
						icon: "add_circle",
						class: "!rounded-2xl !py-4 shadow-xl shadow-primary/20 hover:scale-105 transition-all"
					}, {
						default: withCtx(() => [createTextVNode(" NUEVA ASAMBLEA ")]),
						_: 1
					}, 8, ["onClick"])]), __props.asambleas.length === 0 ? (openBlock(), createBlock("div", {
						key: 0,
						class: "flex flex-col items-center justify-center py-32 bg-surface-container-lowest rounded-[4rem] border-2 border-dashed border-outline-variant/20"
					}, [
						createVNode("div", { class: "w-24 h-24 bg-primary/5 rounded-[2rem] flex items-center justify-center mb-8 rotate-3 group-hover:rotate-0 transition-transform" }, [createVNode("span", { class: "material-symbols-rounded text-5xl text-primary/30" }, "video_chat")]),
						createVNode("h3", { class: "text-2xl font-black text-on-surface uppercase tracking-tight" }, "Sin eventos programados"),
						createVNode("p", { class: "text-on-surface-variant/50 max-w-sm text-center mt-3 text-sm font-medium" }, "Digitaliza las asambleas de tu copropiedad. Votaciones seguras, quórum automático y reportes inmediatos."),
						createVNode(Button_default, {
							onClick: ($event) => showCreateModal.value = true,
							variant: "outline",
							class: "mt-10 !rounded-xl"
						}, {
							default: withCtx(() => [createTextVNode("EMPEZAR AHORA")]),
							_: 1
						}, 8, ["onClick"])
					])) : (openBlock(), createBlock("div", {
						key: 1,
						class: "grid grid-cols-1 lg:grid-cols-2 gap-8"
					}, [(openBlock(true), createBlock(Fragment, null, renderList(__props.asambleas, (asamblea) => {
						return openBlock(), createBlock(Card_default, {
							key: asamblea.id,
							class: "!p-0 !rounded-[3rem] overflow-hidden border border-outline-variant/10 hover:border-primary/30 transition-all duration-500 shadow-lg hover:shadow-2xl flex flex-col group"
						}, {
							default: withCtx(() => [createVNode("div", { class: "p-10 flex-1" }, [
								createVNode("div", { class: "flex justify-between items-start mb-8" }, [createVNode(Badge_default, {
									variant: getStatusTheme(asamblea.status).variant,
									class: "!px-4 !py-2 !rounded-xl text-[9px] font-black tracking-widest italic"
								}, {
									default: withCtx(() => [createVNode("span", { class: "material-symbols-rounded text-xs mr-2" }, toDisplayString(getStatusTheme(asamblea.status).icon), 1), createTextVNode(" " + toDisplayString(getStatusTheme(asamblea.status).label), 1)]),
									_: 2
								}, 1032, ["variant"]), createVNode("div", { class: "flex items-center gap-2" }, [createVNode("span", { class: "text-[9px] font-black text-on-surface-variant/30 uppercase" }, "ID EVENTO"), createVNode("span", { class: "bg-surface-container-high px-3 py-1 rounded-lg text-[10px] font-bold text-on-surface" }, "#" + toDisplayString(asamblea.id), 1)])]),
								createVNode("h3", { class: "text-2xl font-black text-on-surface group-hover:text-primary transition-colors leading-tight mb-8 uppercase tracking-tighter" }, toDisplayString(asamblea.titulo), 1),
								createVNode("div", { class: "grid grid-cols-2 gap-6" }, [createVNode("div", { class: "bg-surface-container-lowest p-5 rounded-[2rem] border border-outline-variant/5" }, [createVNode("span", { class: "text-[8px] font-black text-on-surface-variant/40 uppercase tracking-widest block mb-2" }, "CALENDARIO"), createVNode("div", { class: "flex items-center gap-3" }, [createVNode("div", { class: "w-10 h-10 rounded-xl bg-primary/5 flex items-center justify-center" }, [createVNode("span", { class: "material-symbols-rounded text-primary text-xl" }, "event")]), createVNode("div", { class: "flex flex-col" }, [createVNode("span", { class: "text-[11px] font-black text-on-surface leading-none" }, toDisplayString(formatDate(asamblea.fecha)), 1), createVNode("span", { class: "text-[9px] font-bold text-on-surface-variant italic mt-1" }, toDisplayString(new Date(asamblea.fecha).toLocaleTimeString([], {
									hour: "2-digit",
									minute: "2-digit"
								})), 1)])])]), createVNode("div", { class: "bg-surface-container-lowest p-5 rounded-[2rem] border border-outline-variant/5" }, [createVNode("span", { class: "text-[8px] font-black text-on-surface-variant/40 uppercase tracking-widest block mb-2" }, "ESTADÍSTICAS"), createVNode("div", { class: "flex items-center gap-3" }, [createVNode("div", { class: "w-10 h-10 rounded-xl bg-secondary/5 flex items-center justify-center" }, [createVNode("span", { class: "material-symbols-rounded text-secondary text-xl" }, "ballot")]), createVNode("div", { class: "flex flex-col" }, [createVNode("span", { class: "text-[11px] font-black text-on-surface leading-none" }, toDisplayString(asamblea.preguntas_count) + " PREGUNTAS", 1), createVNode("span", { class: "text-[9px] font-bold text-on-surface-variant italic mt-1" }, "Quórum req: " + toDisplayString(asamblea.settings?.quorum_esperado) + "%", 1)])])])])
							]), createVNode("div", { class: "bg-surface-container-high/30 backdrop-blur-xl border-t border-outline-variant/5 p-8 flex items-center justify-between" }, [createVNode("div", { class: "flex items-center gap-4" }, [createVNode("div", { class: "flex -space-x-3" }, [(openBlock(), createBlock(Fragment, null, renderList(3, (i) => {
								return createVNode("div", {
									key: i,
									class: "w-9 h-9 rounded-2xl border-4 border-surface-container bg-slate-200 flex items-center justify-center text-[10px] font-black text-on-surface/50 overflow-hidden ring-1 ring-black/5"
								}, [createVNode("img", {
									src: `https://api.dicebear.com/7.x/avataaars/svg?seed=${i}`,
									class: "w-full h-full object-cover"
								}, null, 8, ["src"])]);
							}), 64)), createVNode("div", { class: "w-9 h-9 rounded-2xl border-4 border-surface-container bg-primary text-white flex items-center justify-center text-[9px] font-black shadow-lg ring-1 ring-primary/20" }, " +0 ")]), createVNode("span", { class: "text-[9px] font-black text-on-surface-variant/50 uppercase tracking-widest" }, "CONEXIONES ACTIVAS")]), createVNode("div", { class: "flex gap-3" }, [
								createVNode(Tooltip_default, { text: asamblea.status === "in_progress" ? "Finalizar Asamblea" : "Activar Asamblea" }, {
									default: withCtx(() => [createVNode(unref(Link), {
										href: _ctx.route("admin.asambleas.toggle", asamblea.id),
										method: "patch",
										as: "button",
										class: ["w-12 h-12 rounded-2xl flex items-center justify-center transition-all shadow-lg active:scale-95", asamblea.status === "in_progress" ? "bg-error/10 text-error hover:bg-error hover:text-white shadow-error/10" : "bg-emerald-500/10 text-emerald-500 hover:bg-emerald-500 hover:text-white shadow-emerald-500/10"]
									}, {
										default: withCtx(() => [createVNode("span", { class: "material-symbols-rounded text-xl" }, toDisplayString(asamblea.status === "in_progress" ? "stop_circle" : "play_circle"), 1)]),
										_: 2
									}, 1032, ["href", "class"])]),
									_: 2
								}, 1032, ["text"]),
								createVNode(Tooltip_default, { text: "Entrar como Moderador" }, {
									default: withCtx(() => [asamblea.status === "in_progress" ? (openBlock(), createBlock(unref(Link), {
										key: 0,
										href: _ctx.route("asambleas.show", asamblea.id),
										class: "bg-primary text-white w-12 h-12 rounded-2xl flex items-center justify-center hover:scale-110 active:scale-95 transition-all shadow-xl shadow-primary/30"
									}, {
										default: withCtx(() => [createVNode("span", { class: "material-symbols-rounded text-xl" }, "login")]),
										_: 1
									}, 8, ["href"])) : createCommentVNode("", true)]),
									_: 2
								}, 1024),
								asamblea.status === "finished" ? (openBlock(), createBlock(Tooltip_default, {
									key: 0,
									text: "Descargar Auditoría"
								}, {
									default: withCtx(() => [createVNode("a", {
										href: _ctx.route("asambleas.report", asamblea.id),
										class: "bg-secondary text-white w-12 h-12 rounded-2xl flex items-center justify-center hover:scale-110 active:scale-95 transition-all shadow-xl shadow-secondary/30"
									}, [createVNode("span", { class: "material-symbols-rounded text-xl" }, "picture_as_pdf")], 8, ["href"])]),
									_: 2
								}, 1024)) : createCommentVNode("", true),
								asamblea.status !== "in_progress" ? (openBlock(), createBlock(Tooltip_default, {
									key: 1,
									text: "Eliminar Evento"
								}, {
									default: withCtx(() => [createVNode(unref(Link), {
										href: _ctx.route("admin.asambleas.destroy", asamblea.id),
										method: "delete",
										as: "button",
										class: "bg-surface-container-highest text-on-surface-variant/40 hover:text-error w-12 h-12 rounded-2xl flex items-center justify-center transition-all"
									}, {
										default: withCtx(() => [createVNode("span", { class: "material-symbols-rounded text-xl" }, "delete")]),
										_: 1
									}, 8, ["href"])]),
									_: 2
								}, 1024)) : createCommentVNode("", true)
							])])]),
							_: 2
						}, 1024);
					}), 128))]))]), createVNode(Modal_default, {
						show: showCreateModal.value,
						onClose: ($event) => showCreateModal.value = false,
						"max-width": "lg",
						title: "Programar Asamblea"
					}, {
						default: withCtx(() => [createVNode("div", { class: "relative" }, [createVNode("div", { class: "absolute -top-24 -right-24 w-64 h-64 bg-primary/5 rounded-full blur-3xl" }), createVNode("form", {
							onSubmit: withModifiers(submit, ["prevent"]),
							class: "space-y-8 mt-4"
						}, [
							createVNode("div", { class: "space-y-3" }, [createVNode("label", { class: "text-[9px] font-black uppercase tracking-[0.2em] text-on-surface-variant/50 ml-1" }, "NOMBRE DEL EVENTO"), withDirectives(createVNode("input", {
								"onUpdate:modelValue": ($event) => unref(form).titulo = $event,
								type: "text",
								class: "w-full bg-surface-container-high border-none rounded-2xl px-6 py-5 focus:ring-4 focus:ring-primary/10 transition-all font-bold text-on-surface placeholder:text-on-surface-variant/20 shadow-inner",
								placeholder: "Ej: ASAMBLEA ORDINARIA 2026",
								required: ""
							}, null, 8, ["onUpdate:modelValue"]), [[vModelText, unref(form).titulo]])]),
							createVNode("div", { class: "grid grid-cols-2 gap-6" }, [createVNode("div", { class: "space-y-3" }, [createVNode("label", { class: "text-[9px] font-black uppercase tracking-[0.2em] text-on-surface-variant/50 ml-1" }, "FECHA PROGRAMADA"), withDirectives(createVNode("input", {
								"onUpdate:modelValue": ($event) => unref(form).fecha = $event,
								type: "date",
								class: "w-full bg-surface-container-high border-none rounded-2xl px-6 py-5 focus:ring-4 focus:ring-primary/10 transition-all font-bold text-on-surface shadow-inner",
								required: ""
							}, null, 8, ["onUpdate:modelValue"]), [[vModelText, unref(form).fecha]])]), createVNode("div", { class: "space-y-3" }, [createVNode("label", { class: "text-[9px] font-black uppercase tracking-[0.2em] text-on-surface-variant/50 ml-1" }, "HORA DE INICIO"), withDirectives(createVNode("input", {
								"onUpdate:modelValue": ($event) => unref(form).hora_inicio = $event,
								type: "time",
								class: "w-full bg-surface-container-high border-none rounded-2xl px-6 py-5 focus:ring-4 focus:ring-primary/10 transition-all font-bold text-on-surface shadow-inner",
								required: ""
							}, null, 8, ["onUpdate:modelValue"]), [[vModelText, unref(form).hora_inicio]])])]),
							createVNode("div", { class: "space-y-4" }, [createVNode("div", { class: "flex justify-between items-end" }, [createVNode("label", { class: "text-[9px] font-black uppercase tracking-[0.2em] text-on-surface-variant/50 ml-1" }, "QUÓRUM MÍNIMO EXIGIDO"), createVNode("span", { class: "text-2xl font-black text-primary" }, toDisplayString(unref(form).quorum_esperado) + "%", 1)]), createVNode("div", { class: "relative py-2" }, [withDirectives(createVNode("input", {
								"onUpdate:modelValue": ($event) => unref(form).quorum_esperado = $event,
								type: "range",
								min: "0",
								max: "100",
								class: "w-full h-2 bg-surface-container-high rounded-full appearance-none cursor-pointer accent-primary"
							}, null, 8, ["onUpdate:modelValue"]), [[vModelText, unref(form).quorum_esperado]])])]),
							createVNode("div", { class: "pt-8 flex gap-4" }, [createVNode(Button_default, {
								onClick: ($event) => showCreateModal.value = false,
								type: "button",
								variant: "outline",
								class: "flex-1 !rounded-[1.5rem] !py-5 uppercase text-[10px] tracking-widest font-black"
							}, {
								default: withCtx(() => [createTextVNode("CANCELAR")]),
								_: 1
							}, 8, ["onClick"]), createVNode(Button_default, {
								type: "submit",
								variant: "primary",
								class: "flex-1 !rounded-[1.5rem] !py-5 uppercase text-[10px] tracking-widest font-black shadow-xl shadow-primary/20",
								loading: unref(form).processing
							}, {
								default: withCtx(() => [createTextVNode("PROGRAMAR EVENTO")]),
								_: 1
							}, 8, ["loading"])])
						], 32)])]),
						_: 1
					}, 8, ["show", "onClose"])];
				}),
				_: 1
			}, _parent));
			_push(`<!--]-->`);
		};
	}
});
//#endregion
//#region resources/js/Pages/Admin/Asambleas/Index.vue
var _sfc_setup = Index_vue_vue_type_script_setup_true_lang_default.setup;
Index_vue_vue_type_script_setup_true_lang_default.setup = (props, ctx) => {
	const ssrContext = useSSRContext();
	(ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Admin/Asambleas/Index.vue");
	return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
var Index_default = /* @__PURE__ */ _plugin_vue_export_helper_default(Index_vue_vue_type_script_setup_true_lang_default, [["__scopeId", "data-v-0dfddcc8"]]);
//#endregion
export { Index_default as default };

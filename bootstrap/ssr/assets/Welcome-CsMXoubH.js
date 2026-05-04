import { t as _plugin_vue_export_helper_default } from "./_plugin-vue_export-helper-BjJHhBxR.js";
import { t as Card_default } from "./Card-C2-I3_la.js";
import { t as Button_default } from "./Button-5V-KYwxi.js";
import { t as Modal_default } from "./Modal-CeRVVYhy.js";
import { t as Input_default } from "./Input-DWocsxNw.js";
import { t as GuestLayout_default } from "./GuestLayout-CdsdxXMK.js";
import { t as LegalContent_default } from "./LegalContent-FJqdngVc.js";
import { Fragment, Transition, createBlock, createCommentVNode, createTextVNode, createVNode, defineComponent, mergeProps, onMounted, openBlock, ref, renderList, toDisplayString, unref, useSSRContext, withCtx, withModifiers } from "vue";
import { Head, useForm } from "@inertiajs/vue3";
import { ssrInterpolate, ssrRenderAttrs, ssrRenderClass, ssrRenderComponent, ssrRenderList, ssrRenderStyle } from "vue/server-renderer";
//#region resources/js/Components/Landing/HeroSection.vue?vue&type=script&setup=true&lang.ts
var HeroSection_vue_vue_type_script_setup_true_lang_default = /* @__PURE__ */ defineComponent({
	__name: "HeroSection",
	__ssrInlineRender: true,
	props: {
		title: {
			type: String,
			default: "Control Maestro de tu Copropiedad"
		},
		subtitle: {
			type: String,
			default: "La infraestructura digital definitiva para administradores que buscan control absoluto y propietarios que exigen transparencia total."
		}
	},
	setup(__props) {
		return (_ctx, _push, _parent, _attrs) => {
			_push(`<section${ssrRenderAttrs(mergeProps({ class: "relative pt-32 pb-48 overflow-hidden bg-gradient-to-b from-[#F0F4F8] to-white dark:from-[#0b0e14] dark:to-[#0b0e14]" }, _attrs))} data-v-3bfd6e7f><div class="absolute top-0 right-0 w-[800px] h-[800px] bg-primary/10 blur-[150px] rounded-full -mr-96 -mt-96 animate-pulse duration-[10s]" data-v-3bfd6e7f></div><div class="absolute bottom-0 left-0 w-[600px] h-[600px] bg-secondary/5 blur-[120px] rounded-full -ml-48 -mb-48" data-v-3bfd6e7f></div><div class="max-w-7xl mx-auto px-6 grid grid-cols-1 lg:grid-cols-2 gap-20 items-center relative z-10" data-v-3bfd6e7f><div class="space-y-12 animate-in fade-in slide-in-from-left-12 duration-1000 ease-out" data-v-3bfd6e7f><div class="space-y-8" data-v-3bfd6e7f><div class="inline-flex items-center gap-3 px-5 py-2 bg-primary/10 text-primary rounded-full border border-primary/20 backdrop-blur-md" data-v-3bfd6e7f><span class="material-symbols-rounded text-sm animate-spin-slow" data-v-3bfd6e7f>verified</span><span class="text-[10px] font-black uppercase tracking-[0.4em] italic" data-v-3bfd6e7f>Estándar Industrial PRO</span></div><h1 class="text-6xl md:text-8xl font-black text-on-surface dark:text-white leading-[0.95] tracking-tighter uppercase italic" data-v-3bfd6e7f>${ssrInterpolate(__props.title)} <span class="text-primary block not-italic" data-v-3bfd6e7f>Nexo-Pro</span></h1><p class="text-xl md:text-2xl text-on-surface-variant/60 dark:text-white/40 max-w-xl font-medium leading-relaxed italic border-l-4 border-primary/20 pl-8" data-v-3bfd6e7f>${ssrInterpolate(__props.subtitle)}</p></div><div class="flex flex-col sm:flex-row gap-6 pt-4" data-v-3bfd6e7f>`);
			_push(ssrRenderComponent(Button_default, {
				href: "/#contacto",
				variant: "primary",
				size: "lg",
				icon: "rocket_launch",
				class: "!h-20 !px-12 shadow-2xl shadow-primary/30 !rounded-3xl !text-[11px] font-black uppercase italic"
			}, {
				default: withCtx((_, _push, _parent, _scopeId) => {
					if (_push) _push(` Solicitar Acceso Demo `);
					else return [createTextVNode(" Solicitar Acceso Demo ")];
				}),
				_: 1
			}, _parent));
			_push(ssrRenderComponent(Button_default, {
				href: "/#roles",
				variant: "outline",
				size: "lg",
				class: "!h-20 !px-12 !rounded-3xl !text-[11px] font-black uppercase italic border-2 hover:bg-white/5 transition-all"
			}, {
				default: withCtx((_, _push, _parent, _scopeId) => {
					if (_push) _push(` Explorar Funcionalidades `);
					else return [createTextVNode(" Explorar Funcionalidades ")];
				}),
				_: 1
			}, _parent));
			_push(`</div><div class="flex flex-wrap items-center gap-10 pt-10 opacity-60" data-v-3bfd6e7f><div class="flex flex-col" data-v-3bfd6e7f><span class="text-3xl font-black text-on-surface dark:text-white tabular-nums tracking-tighter italic" data-v-3bfd6e7f>+500k</span><span class="text-[9px] font-bold text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.3em]" data-v-3bfd6e7f>Unidades Gestionadas</span></div><div class="w-px h-10 bg-outline-variant/20 dark:bg-white/10 hidden sm:block" data-v-3bfd6e7f></div><div class="flex flex-col" data-v-3bfd6e7f><span class="text-3xl font-black text-on-surface dark:text-white tabular-nums tracking-tighter italic" data-v-3bfd6e7f>99.9%</span><span class="text-[9px] font-bold text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.3em]" data-v-3bfd6e7f>Disponibilidad</span></div></div></div><div class="relative lg:h-[700px] flex items-center justify-center animate-in fade-in zoom-in-95 duration-1000 delay-300" data-v-3bfd6e7f><div class="absolute inset-0 border-2 border-primary/5 rounded-full scale-[1.2] animate-spin-slow" data-v-3bfd6e7f></div><div class="absolute inset-0 border border-secondary/5 rounded-full scale-[0.8] animate-reverse-spin" data-v-3bfd6e7f></div><div class="bg-white/5 dark:bg-[#12161e] p-4 rounded-[4rem] shadow-3xl border border-white/10 relative z-10 transition-all duration-700 hover:scale-[1.03] group" data-v-3bfd6e7f><div class="rounded-[3rem] overflow-hidden border-4 border-white/5 shadow-2xl aspect-video relative" data-v-3bfd6e7f><img src="https://images.unsplash.com/photo-1460925895917-afdab827c52f?q=80&amp;w=2426&amp;auto=format&amp;fit=crop" alt="NEXO-PRO Dashboard Preview" class="w-full h-full object-cover transition-all duration-1000 group-hover:scale-110" data-v-3bfd6e7f><div class="absolute inset-0 bg-gradient-to-tr from-primary/20 to-transparent" data-v-3bfd6e7f></div></div><div class="absolute -right-8 -top-8 bg-white dark:bg-[#1a1f29] p-6 rounded-3xl shadow-2xl border border-white/10 animate-float" data-v-3bfd6e7f><div class="flex items-center gap-4" data-v-3bfd6e7f><div class="w-12 h-12 rounded-2xl bg-emerald-500/10 text-emerald-500 flex items-center justify-center" data-v-3bfd6e7f><span class="material-symbols-rounded" data-v-3bfd6e7f>security</span></div><div data-v-3bfd6e7f><p class="text-[9px] font-black text-on-surface-variant dark:text-white/20 uppercase tracking-widest leading-none mb-2" data-v-3bfd6e7f>Protocolo</p><p class="text-sm font-black text-on-surface dark:text-white uppercase italic tracking-tighter" data-v-3bfd6e7f>AES-256 Activo</p></div></div></div><div class="absolute -left-12 bottom-12 bg-white dark:bg-[#1a1f29] p-6 rounded-3xl shadow-2xl border border-white/10 animate-float-delayed" data-v-3bfd6e7f><div class="flex items-center gap-4" data-v-3bfd6e7f><div class="w-12 h-12 rounded-2xl bg-primary/10 text-primary flex items-center justify-center" data-v-3bfd6e7f><span class="material-symbols-rounded" data-v-3bfd6e7f>bar_chart_4_bars</span></div><div data-v-3bfd6e7f><p class="text-[9px] font-black text-on-surface-variant dark:text-white/20 uppercase tracking-widest leading-none mb-2" data-v-3bfd6e7f>Cartera</p><p class="text-sm font-black text-on-surface dark:text-white uppercase italic tracking-tighter" data-v-3bfd6e7f>98% Eficiencia</p></div></div></div></div></div></div></section>`);
		};
	}
});
//#endregion
//#region resources/js/Components/Landing/HeroSection.vue
var _sfc_setup$6 = HeroSection_vue_vue_type_script_setup_true_lang_default.setup;
HeroSection_vue_vue_type_script_setup_true_lang_default.setup = (props, ctx) => {
	const ssrContext = useSSRContext();
	(ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/Landing/HeroSection.vue");
	return _sfc_setup$6 ? _sfc_setup$6(props, ctx) : void 0;
};
var HeroSection_default = /* @__PURE__ */ _plugin_vue_export_helper_default(HeroSection_vue_vue_type_script_setup_true_lang_default, [["__scopeId", "data-v-3bfd6e7f"]]);
//#endregion
//#region resources/js/Components/Landing/RolesSection.vue?vue&type=script&setup=true&lang.ts
var RolesSection_vue_vue_type_script_setup_true_lang_default = /* @__PURE__ */ defineComponent({
	__name: "RolesSection",
	__ssrInlineRender: true,
	setup(__props) {
		const activeRole = ref("admin");
		const adminFeatures = [
			{
				title: "Mando Central Financiero",
				desc: "Visualización táctica de ingresos, egresos y proyecciones en tiempo real.",
				icon: "analytics",
				color: "bg-primary/10 text-primary"
			},
			{
				title: "Gestión de Cartera Inteligente",
				desc: "Liquidación automática de cuotas y control de mora con notificaciones push.",
				icon: "account_balance_wallet",
				color: "bg-secondary/10 text-secondary"
			},
			{
				title: "Dispatcher de Reservas",
				desc: "Control total de zonas comunes con sistema de aprobación y política de uso.",
				icon: "calendar_month",
				color: "bg-tertiary/10 text-tertiary"
			},
			{
				title: "Auditoría Forense (Logs)",
				desc: "Trazabilidad absoluta de cada acción realizada por el staff y residentes.",
				icon: "rule",
				color: "bg-emerald-500/10 text-emerald-500"
			},
			{
				title: "Centro de Ingeniería",
				desc: "Soporte técnico directo con ingenieros de Nexo-Pro para reportes de sistema.",
				icon: "engineering",
				color: "bg-orange-500/10 text-orange-500"
			}
		];
		const ownerFeatures = [
			{
				title: "Pasarela Nexo-Pay",
				desc: "Paga tu administración en segundos desde tu móvil con total seguridad.",
				icon: "credit_card",
				color: "bg-primary/10 text-primary"
			},
			{
				title: "Reservas Express",
				desc: "Agenda salones, canchas y zonas sociales en tiempo real desde la App.",
				icon: "event_available",
				color: "bg-secondary/10 text-secondary"
			},
			{
				title: "Muro de Copropiedad",
				desc: "Comunicados oficiales y noticias de tu unidad residencial al instante.",
				icon: "forum",
				color: "bg-tertiary/10 text-tertiary"
			},
			{
				title: "Estado de Cuenta Digital",
				desc: "Consulta tu saldo y descarga paz y salvos sin procesos administrativos.",
				icon: "description",
				color: "bg-emerald-500/10 text-emerald-500"
			},
			{
				title: "Soporte de Usuario",
				desc: "Reporta problemas técnicos y recibe asistencia prioritaria del equipo de ingenieros.",
				icon: "support_agent",
				color: "bg-orange-500/10 text-orange-500"
			}
		];
		return (_ctx, _push, _parent, _attrs) => {
			_push(`<section${ssrRenderAttrs(mergeProps({ class: "py-32 bg-white dark:bg-[#080a0f] overflow-hidden" }, _attrs))} data-v-a53da20e><div class="max-w-7xl mx-auto px-6" data-v-a53da20e><div class="flex flex-col md:flex-row md:items-end justify-between gap-12 mb-24" data-v-a53da20e><div class="space-y-6" data-v-a53da20e><div class="flex items-center gap-3" data-v-a53da20e><div class="w-2 h-8 bg-primary rounded-full" data-v-a53da20e></div><span class="text-[10px] font-black uppercase tracking-[0.5em] text-primary italic" data-v-a53da20e>Arquitectura de Roles</span></div><h2 class="text-5xl md:text-6xl font-black text-on-surface dark:text-white uppercase italic tracking-tighter leading-none" data-v-a53da20e> Una plataforma, <br data-v-a53da20e> <span class="text-primary italic" data-v-a53da20e>dos mundos</span></h2></div><div class="flex p-2 bg-surface-container-low dark:bg-white/5 rounded-[2rem] border border-outline-variant/10 dark:border-white/5 shadow-2xl" data-v-a53da20e><button class="${ssrRenderClass(["px-10 py-5 rounded-[1.5rem] text-[11px] font-black uppercase tracking-widest transition-all italic", activeRole.value === "admin" ? "bg-primary text-white shadow-xl shadow-primary/20 scale-105" : "text-on-surface-variant/40 hover:text-on-surface"])}" data-v-a53da20e> Para Administradores </button><button class="${ssrRenderClass(["px-10 py-5 rounded-[1.5rem] text-[11px] font-black uppercase tracking-widest transition-all italic", activeRole.value === "owner" ? "bg-primary text-white shadow-xl shadow-primary/20 scale-105" : "text-on-surface-variant/40 hover:text-on-surface"])}" data-v-a53da20e> Para Propietarios </button></div></div><div class="relative min-h-[600px]" data-v-a53da20e><div class="absolute -top-24 -right-24 w-96 h-96 bg-primary/10 rounded-full blur-[120px] pointer-events-none opacity-50" data-v-a53da20e></div><div class="absolute -bottom-24 -left-24 w-96 h-96 bg-secondary/10 rounded-full blur-[120px] pointer-events-none opacity-50" data-v-a53da20e></div><div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8" style="${ssrRenderStyle(activeRole.value === "admin" ? null : { display: "none" })}" data-v-a53da20e><!--[-->`);
			ssrRenderList(adminFeatures, (feat, i) => {
				_push(ssrRenderComponent(Card_default, {
					key: i,
					class: "!p-10 !rounded-[3rem] border border-outline-variant/10 dark:border-white/5 shadow-xl hover:shadow-primary/20 transition-all duration-700 group relative overflow-hidden bg-white dark:bg-[#0b0e14]"
				}, {
					default: withCtx((_, _push, _parent, _scopeId) => {
						if (_push) _push(`<div class="absolute -inset-24 bg-primary/5 blur-[80px] rounded-full translate-x-full translate-y-full group-hover:translate-x-0 group-hover:translate-y-0 transition-transform duration-1000 pointer-events-none" data-v-a53da20e${_scopeId}></div><div class="relative z-10" data-v-a53da20e${_scopeId}><div class="${ssrRenderClass([feat.color, "w-16 h-16 rounded-2xl flex items-center justify-center mb-10 transition-all group-hover:scale-110 group-hover:rotate-3 duration-500 shadow-lg"])}" data-v-a53da20e${_scopeId}><span class="material-symbols-rounded text-3xl" data-v-a53da20e${_scopeId}>${ssrInterpolate(feat.icon)}</span></div><h3 class="text-xl font-black text-on-surface dark:text-white uppercase italic tracking-tighter mb-4" data-v-a53da20e${_scopeId}>${ssrInterpolate(feat.title)}</h3><p class="text-sm font-medium text-on-surface-variant/60 dark:text-white/40 leading-relaxed italic" data-v-a53da20e${_scopeId}>${ssrInterpolate(feat.desc)}</p></div>`);
						else return [createVNode("div", { class: "absolute -inset-24 bg-primary/5 blur-[80px] rounded-full translate-x-full translate-y-full group-hover:translate-x-0 group-hover:translate-y-0 transition-transform duration-1000 pointer-events-none" }), createVNode("div", { class: "relative z-10" }, [
							createVNode("div", { class: ["w-16 h-16 rounded-2xl flex items-center justify-center mb-10 transition-all group-hover:scale-110 group-hover:rotate-3 duration-500 shadow-lg", feat.color] }, [createVNode("span", { class: "material-symbols-rounded text-3xl" }, toDisplayString(feat.icon), 1)], 2),
							createVNode("h3", { class: "text-xl font-black text-on-surface dark:text-white uppercase italic tracking-tighter mb-4" }, toDisplayString(feat.title), 1),
							createVNode("p", { class: "text-sm font-medium text-on-surface-variant/60 dark:text-white/40 leading-relaxed italic" }, toDisplayString(feat.desc), 1)
						])];
					}),
					_: 2
				}, _parent));
			});
			_push(`<!--]-->`);
			_push(ssrRenderComponent(Card_default, { class: "!p-10 !rounded-[3rem] bg-primary flex flex-col justify-center items-center text-center text-white space-y-8 shadow-2xl shadow-primary/20 hover:scale-[1.02] transition-transform duration-500" }, {
				default: withCtx((_, _push, _parent, _scopeId) => {
					if (_push) _push(`<div class="w-20 h-20 bg-white/20 rounded-full flex items-center justify-center animate-pulse" data-v-a53da20e${_scopeId}><span class="material-symbols-rounded text-4xl" data-v-a53da20e${_scopeId}>rocket_launch</span></div><h3 class="text-2xl font-black uppercase italic tracking-tighter" data-v-a53da20e${_scopeId}>¿Listo para tomar el control total?</h3><button class="w-full py-5 bg-white text-primary rounded-2xl text-[11px] font-black uppercase italic shadow-xl hover:bg-neutral-100 transition-all" data-v-a53da20e${_scopeId}>Empezar ahora</button>`);
					else return [
						createVNode("div", { class: "w-20 h-20 bg-white/20 rounded-full flex items-center justify-center animate-pulse" }, [createVNode("span", { class: "material-symbols-rounded text-4xl" }, "rocket_launch")]),
						createVNode("h3", { class: "text-2xl font-black uppercase italic tracking-tighter" }, "¿Listo para tomar el control total?"),
						createVNode("button", { class: "w-full py-5 bg-white text-primary rounded-2xl text-[11px] font-black uppercase italic shadow-xl hover:bg-neutral-100 transition-all" }, "Empezar ahora")
					];
				}),
				_: 1
			}, _parent));
			_push(`</div><div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8" style="${ssrRenderStyle(activeRole.value === "owner" ? null : { display: "none" })}" data-v-a53da20e><!--[-->`);
			ssrRenderList(ownerFeatures, (feat, i) => {
				_push(ssrRenderComponent(Card_default, {
					key: i,
					class: "!p-10 !rounded-[3rem] border border-outline-variant/10 dark:border-white/5 shadow-xl hover:shadow-secondary/20 transition-all duration-700 group relative overflow-hidden bg-white dark:bg-[#0b0e14]"
				}, {
					default: withCtx((_, _push, _parent, _scopeId) => {
						if (_push) _push(`<div class="absolute -inset-24 bg-secondary/5 blur-[80px] rounded-full translate-x-full translate-y-full group-hover:translate-x-0 group-hover:translate-y-0 transition-transform duration-1000 pointer-events-none" data-v-a53da20e${_scopeId}></div><div class="relative z-10" data-v-a53da20e${_scopeId}><div class="${ssrRenderClass([feat.color, "w-16 h-16 rounded-2xl flex items-center justify-center mb-10 transition-all group-hover:scale-110 group-hover:rotate-3 duration-500 shadow-lg"])}" data-v-a53da20e${_scopeId}><span class="material-symbols-rounded text-3xl" data-v-a53da20e${_scopeId}>${ssrInterpolate(feat.icon)}</span></div><h3 class="text-xl font-black text-on-surface dark:text-white uppercase italic tracking-tighter mb-4" data-v-a53da20e${_scopeId}>${ssrInterpolate(feat.title)}</h3><p class="text-sm font-medium text-on-surface-variant/60 dark:text-white/40 leading-relaxed italic" data-v-a53da20e${_scopeId}>${ssrInterpolate(feat.desc)}</p></div>`);
						else return [createVNode("div", { class: "absolute -inset-24 bg-secondary/5 blur-[80px] rounded-full translate-x-full translate-y-full group-hover:translate-x-0 group-hover:translate-y-0 transition-transform duration-1000 pointer-events-none" }), createVNode("div", { class: "relative z-10" }, [
							createVNode("div", { class: ["w-16 h-16 rounded-2xl flex items-center justify-center mb-10 transition-all group-hover:scale-110 group-hover:rotate-3 duration-500 shadow-lg", feat.color] }, [createVNode("span", { class: "material-symbols-rounded text-3xl" }, toDisplayString(feat.icon), 1)], 2),
							createVNode("h3", { class: "text-xl font-black text-on-surface dark:text-white uppercase italic tracking-tighter mb-4" }, toDisplayString(feat.title), 1),
							createVNode("p", { class: "text-sm font-medium text-on-surface-variant/60 dark:text-white/40 leading-relaxed italic" }, toDisplayString(feat.desc), 1)
						])];
					}),
					_: 2
				}, _parent));
			});
			_push(`<!--]-->`);
			_push(ssrRenderComponent(Card_default, { class: "!p-10 !rounded-[3rem] bg-secondary flex flex-col justify-center items-center text-center text-white space-y-8 shadow-2xl shadow-secondary/20 hover:scale-[1.02] transition-transform duration-500" }, {
				default: withCtx((_, _push, _parent, _scopeId) => {
					if (_push) _push(`<div class="w-20 h-20 bg-white/20 rounded-full flex items-center justify-center animate-pulse" data-v-a53da20e${_scopeId}><span class="material-symbols-rounded text-4xl" data-v-a53da20e${_scopeId}>visibility</span></div><h3 class="text-2xl font-black uppercase italic tracking-tighter" data-v-a53da20e${_scopeId}>La tranquilidad que tu familia merece</h3><button class="w-full py-5 bg-white text-secondary rounded-2xl text-[11px] font-black uppercase italic shadow-xl hover:bg-neutral-100 transition-all" data-v-a53da20e${_scopeId}>Ver la Experiencia</button>`);
					else return [
						createVNode("div", { class: "w-20 h-20 bg-white/20 rounded-full flex items-center justify-center animate-pulse" }, [createVNode("span", { class: "material-symbols-rounded text-4xl" }, "visibility")]),
						createVNode("h3", { class: "text-2xl font-black uppercase italic tracking-tighter" }, "La tranquilidad que tu familia merece"),
						createVNode("button", { class: "w-full py-5 bg-white text-secondary rounded-2xl text-[11px] font-black uppercase italic shadow-xl hover:bg-neutral-100 transition-all" }, "Ver la Experiencia")
					];
				}),
				_: 1
			}, _parent));
			_push(`</div></div></div></section>`);
		};
	}
});
//#endregion
//#region resources/js/Components/Landing/RolesSection.vue
var _sfc_setup$5 = RolesSection_vue_vue_type_script_setup_true_lang_default.setup;
RolesSection_vue_vue_type_script_setup_true_lang_default.setup = (props, ctx) => {
	const ssrContext = useSSRContext();
	(ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/Landing/RolesSection.vue");
	return _sfc_setup$5 ? _sfc_setup$5(props, ctx) : void 0;
};
var RolesSection_default = /* @__PURE__ */ _plugin_vue_export_helper_default(RolesSection_vue_vue_type_script_setup_true_lang_default, [["__scopeId", "data-v-a53da20e"]]);
//#endregion
//#region resources/js/Components/Landing/FeaturesSection.vue?vue&type=script&setup=true&lang.ts
var FeaturesSection_vue_vue_type_script_setup_true_lang_default = /* @__PURE__ */ defineComponent({
	__name: "FeaturesSection",
	__ssrInlineRender: true,
	props: { features: {
		type: Array,
		default: () => [
			{
				icon: "payments",
				title: "Recaudo automatizado",
				description: "Simplifica la cobranza con conciliación automática y múltiples canales de pago seguros para tus residentes.",
				iconBgClass: "bg-primary-fixed/30 text-primary"
			},
			{
				icon: "groups",
				title: "Asambleas virtuales",
				description: "Votaciones en tiempo real, quórum automático y actas digitales con validez legal garantizada.",
				iconBgClass: "bg-secondary-fixed/40 text-secondary"
			},
			{
				icon: "engineering",
				title: "Mantenimiento predictivo",
				description: "Anticípate a los fallos con nuestra gestión inteligente de activos y programación de visitas técnicas preventivas.",
				iconBgClass: "bg-tertiary-fixed/30 text-tertiary"
			}
		]
	} },
	setup(__props) {
		const sectionRef = ref(null);
		onMounted(() => {
			const observer = new IntersectionObserver((entries) => {
				entries.forEach((entry) => {
					if (entry.isIntersecting) {
						entry.target.querySelectorAll("[data-animate=\"fade-up\"]").forEach((el, index) => {
							setTimeout(() => {
								el.classList.add("animate-in");
							}, index * 100);
						});
						observer.unobserve(entry.target);
					}
				});
			}, { threshold: .1 });
			if (sectionRef.value) observer.observe(sectionRef.value);
		});
		return (_ctx, _push, _parent, _attrs) => {
			_push(`<section${ssrRenderAttrs(mergeProps({
				id: "soluciones",
				ref_key: "sectionRef",
				ref: sectionRef,
				class: "py-32 bg-surface overflow-hidden"
			}, _attrs))} data-v-7c2ec92d><div class="max-w-7xl mx-auto px-6" data-v-7c2ec92d><div class="text-center mb-32 opacity-0 translate-y-4 transition-all duration-1000 data-[animate]:animate-in" data-animate="fade-up" data-v-7c2ec92d><div class="inline-flex items-center gap-2 px-4 py-2 bg-secondary/5 text-secondary rounded-full border border-secondary/10 mb-6" data-v-7c2ec92d><span class="material-symbols-outlined text-sm" data-v-7c2ec92d>hub</span><span class="text-[10px] font-black uppercase tracking-[0.2em]" data-v-7c2ec92d>Ecosistema Integrado</span></div><h2 class="text-4xl md:text-5xl font-black text-primary mb-6 tracking-tight uppercase leading-none" data-v-7c2ec92d>Soluciones que transforman la gestión</h2><p class="text-lg text-on-surface-variant max-w-2xl mx-auto font-medium" data-v-7c2ec92d>Digitalizamos cada aspecto de su copropiedad con procesos automáticos y seguridad de grado bancario.</p></div><div class="space-y-40" data-v-7c2ec92d><div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center opacity-0 translate-y-8 transition-all duration-1000 data-[animate]:animate-in" data-animate="fade-up" data-v-7c2ec92d><div class="relative group" data-v-7c2ec92d><div class="absolute -inset-4 bg-primary/5 rounded-[3rem] blur-2xl group-hover:bg-primary/10 transition-colors" data-v-7c2ec92d></div><div class="relative premium-card overflow-hidden rounded-[2.5rem] border border-white/40 shadow-2xl" data-v-7c2ec92d><img src="/images/landing/recaudo.png" alt="Recaudo Automatizado" class="w-full h-auto transform transition-transform duration-1000 group-hover:scale-105" data-v-7c2ec92d></div><div class="absolute -right-6 -bottom-6 bg-surface shadow-2xl p-4 rounded-2xl border border-outline-variant/10 z-10 animate-bounce duration-[4s]" data-v-7c2ec92d><div class="flex items-center gap-3" data-v-7c2ec92d><div class="w-10 h-10 rounded-full bg-success/10 text-success flex items-center justify-center" data-v-7c2ec92d><span class="material-symbols-outlined" data-v-7c2ec92d>payments</span></div><div data-v-7c2ec92d><p class="text-[8px] font-black uppercase text-slate-400 tracking-widest" data-v-7c2ec92d>Conciliación</p><p class="text-xs font-black text-primary" data-v-7c2ec92d>100% Automática</p></div></div></div></div><div class="space-y-6" data-v-7c2ec92d><span class="text-primary font-black uppercase tracking-[0.3em] text-[10px]" data-v-7c2ec92d>Módulo de Finanzas</span><h3 class="text-3xl font-black text-primary tracking-tighter uppercase leading-none" data-v-7c2ec92d>Recaudo y Cartera Automatizada</h3><p class="text-on-surface-variant font-medium leading-relaxed" data-v-7c2ec92d> Olvídese de las conciliaciones manuales. Nuestra plataforma se integra con los principales bancos colombianos para identificar pagos en tiempo real, actualizando la cartera de cada residente al instante y generando comprobantes digitales automáticos. </p><ul class="space-y-4 pt-4" data-v-7c2ec92d><li class="flex items-center gap-3 text-sm font-semibold text-on-surface" data-v-7c2ec92d><span class="material-symbols-outlined text-primary text-xl" data-v-7c2ec92d>bolt</span> Pagos por PSE, Tarjeta y Efecty </li><li class="flex items-center gap-3 text-sm font-semibold text-on-surface" data-v-7c2ec92d><span class="material-symbols-outlined text-primary text-xl" data-v-7c2ec92d>notifications_active</span> Recordatorios de mora automáticos </li></ul></div></div><div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center opacity-0 translate-y-8 transition-all duration-1000 data-[animate]:animate-in" data-animate="fade-up" data-v-7c2ec92d><div class="lg:order-2 relative group" data-v-7c2ec92d><div class="absolute -inset-4 bg-secondary/5 rounded-[3rem] blur-2xl group-hover:bg-secondary/10 transition-colors" data-v-7c2ec92d></div><div class="relative premium-card overflow-hidden rounded-[2.5rem] border border-white/40 shadow-2xl" data-v-7c2ec92d><img src="/images/landing/asambleas.png" alt="Asambleas Virtuales" class="w-full h-auto transform transition-transform duration-1000 group-hover:scale-105" data-v-7c2ec92d></div></div><div class="lg:order-1 space-y-6" data-v-7c2ec92d><span class="text-secondary font-black uppercase tracking-[0.3em] text-[10px]" data-v-7c2ec92d>Democracia Digital</span><h3 class="text-3xl font-black text-primary tracking-tighter uppercase leading-none" data-v-7c2ec92d>Asambleas Virtuales y Votación</h3><p class="text-on-surface-variant font-medium leading-relaxed" data-v-7c2ec92d> Logre el quórum legal sin esfuerzo. Ejecute votaciones en tiempo real con resultados auditables, actas generadas automáticamente y control total de asistencia desde cualquier dispositivo. Cumplimiento total de la Ley 675. </p><ul class="space-y-4 pt-4" data-v-7c2ec92d><li class="flex items-center gap-3 text-sm font-semibold text-on-surface" data-v-7c2ec92d><span class="material-symbols-outlined text-secondary text-xl" data-v-7c2ec92d>analytics</span> Gráficas de votación al instante </li><li class="flex items-center gap-3 text-sm font-semibold text-on-surface" data-v-7c2ec92d><span class="material-symbols-outlined text-secondary text-xl" data-v-7c2ec92d>security</span> Identidad verificada de propietarios </li></ul></div></div><div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center opacity-0 translate-y-8 transition-all duration-1000 data-[animate]:animate-in" data-animate="fade-up" data-v-7c2ec92d><div class="relative group" data-v-7c2ec92d><div class="absolute -inset-4 bg-tertiary/5 rounded-[3rem] blur-2xl group-hover:bg-tertiary/10 transition-colors" data-v-7c2ec92d></div><div class="relative premium-card overflow-hidden rounded-[2.5rem] border border-white/40 shadow-2xl" data-v-7c2ec92d><img src="/images/landing/mantenimiento.png" alt="Mantenimiento Predictivo" class="w-full h-auto transform transition-transform duration-1000 group-hover:scale-105" data-v-7c2ec92d></div></div><div class="space-y-6" data-v-7c2ec92d><span class="text-tertiary font-black uppercase tracking-[0.3em] text-[10px]" data-v-7c2ec92d>Operaciones 360</span><h3 class="text-3xl font-black text-primary tracking-tighter uppercase leading-none" data-v-7c2ec92d>Mantenimiento y Control de Activos</h3><p class="text-on-surface-variant font-medium leading-relaxed" data-v-7c2ec92d> Prolongue la vida útil de su infraestructura. Centralice la gestión de proveedores, asigne órdenes de trabajo y reciba alertas preventivas antes de que ocurran fallos costosos en ascensores, bombas o plantas eléctricas. </p><ul class="space-y-4 pt-4" data-v-7c2ec92d><li class="flex items-center gap-3 text-sm font-semibold text-on-surface" data-v-7c2ec92d><span class="material-symbols-outlined text-tertiary text-xl" data-v-7c2ec92d>engineering</span> Seguimiento fotográfico de reparaciones </li><li class="flex items-center gap-3 text-sm font-semibold text-on-surface" data-v-7c2ec92d><span class="material-symbols-outlined text-tertiary text-xl" data-v-7c2ec92d>calendar_month</span> Agenda anual de visitas técnicas </li></ul></div></div></div></div></section>`);
		};
	}
});
//#endregion
//#region resources/js/Components/Landing/FeaturesSection.vue
var _sfc_setup$4 = FeaturesSection_vue_vue_type_script_setup_true_lang_default.setup;
FeaturesSection_vue_vue_type_script_setup_true_lang_default.setup = (props, ctx) => {
	const ssrContext = useSSRContext();
	(ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/Landing/FeaturesSection.vue");
	return _sfc_setup$4 ? _sfc_setup$4(props, ctx) : void 0;
};
var FeaturesSection_default = /* @__PURE__ */ _plugin_vue_export_helper_default(FeaturesSection_vue_vue_type_script_setup_true_lang_default, [["__scopeId", "data-v-7c2ec92d"]]);
//#endregion
//#region resources/js/Components/Landing/PricingSection.vue?vue&type=script&setup=true&lang.ts
var PricingSection_vue_vue_type_script_setup_true_lang_default = /* @__PURE__ */ defineComponent({
	__name: "PricingSection",
	__ssrInlineRender: true,
	props: { plans: {
		type: Array,
		required: true
	} },
	setup(__props) {
		const isAnnual = ref(false);
		const formatPrice = (price) => {
			if (price === null) return "CUSTOM";
			return new Intl.NumberFormat("es-CO", {
				style: "currency",
				currency: "COP",
				maximumFractionDigits: 0
			}).format(price);
		};
		return (_ctx, _push, _parent, _attrs) => {
			_push(`<section${ssrRenderAttrs(mergeProps({
				id: "precios",
				class: "py-40 bg-surface dark:bg-[#080a0f] relative overflow-hidden"
			}, _attrs))} data-v-5a4b433f><div class="absolute top-0 left-1/2 -translate-x-1/2 w-[1000px] h-[400px] bg-primary/5 blur-[120px] rounded-full pointer-events-none" data-v-5a4b433f></div><div class="max-w-7xl mx-auto px-6 relative z-10" data-v-5a4b433f><div class="text-center mb-24 space-y-6" data-v-5a4b433f><div class="flex items-center justify-center gap-3" data-v-5a4b433f><div class="w-2 h-8 bg-secondary rounded-full" data-v-5a4b433f></div><span class="text-[10px] font-black uppercase tracking-[0.5em] text-secondary italic" data-v-5a4b433f>Escalabilidad Industrial</span></div><h2 class="text-5xl md:text-7xl font-black text-on-surface dark:text-white uppercase italic tracking-tighter leading-none" data-v-5a4b433f> Planes de <span class="text-secondary italic" data-v-5a4b433f>Inversión</span></h2><div class="flex justify-center items-center gap-6 pt-8" data-v-5a4b433f><span class="${ssrRenderClass([isAnnual.value ? "text-white/20" : "text-primary", "text-[10px] font-black uppercase tracking-widest transition-all"])}" data-v-5a4b433f>Facturación Mensual</span><button class="w-16 h-8 rounded-full bg-white/5 border border-white/10 flex items-center p-1 transition-all relative outline-none" data-v-5a4b433f><div class="${ssrRenderClass([{ "translate-x-8": isAnnual.value }, "w-6 h-6 bg-primary rounded-full shadow-lg transform transition-transform duration-500"])}" data-v-5a4b433f></div></button><div class="flex items-center gap-3" data-v-5a4b433f><span class="${ssrRenderClass([isAnnual.value ? "text-primary" : "text-white/20", "text-[10px] font-black uppercase tracking-widest transition-all"])}" data-v-5a4b433f>Anual (Elite Focus)</span><span class="bg-emerald-500/10 text-emerald-500 text-[8px] font-black px-3 py-1 rounded-lg uppercase tracking-widest border border-emerald-500/20" data-v-5a4b433f> -20% OFF </span></div></div></div><div class="grid grid-cols-1 md:grid-cols-3 gap-10 items-stretch" data-v-5a4b433f><!--[-->`);
			ssrRenderList(__props.plans, (plan, index) => {
				_push(ssrRenderComponent(Card_default, {
					key: index,
					class: ["!p-12 !rounded-[3.5rem] border transition-all duration-700 relative group flex flex-col justify-between", [plan.is_recommended ? "bg-primary dark:bg-primary border-primary shadow-2xl shadow-primary/20 scale-105 z-10" : "bg-white dark:bg-white/[0.02] border-outline-variant/10 dark:border-white/5 shadow-xl hover:border-primary/30"]]
				}, {
					default: withCtx((_, _push, _parent, _scopeId) => {
						if (_push) {
							if (plan.is_recommended) _push(`<div class="absolute -top-6 left-1/2 -translate-x-1/2 bg-secondary text-white px-8 py-2 rounded-2xl text-[10px] font-black uppercase tracking-[0.3em] italic shadow-2xl" data-v-5a4b433f${_scopeId}> Monitor Recomendado </div>`);
							else _push(`<!---->`);
							_push(`<div class="space-y-8 mb-12" data-v-5a4b433f${_scopeId}><h4 class="${ssrRenderClass([plan.is_recommended ? "text-white/60" : "text-primary", "text-[11px] font-black uppercase tracking-[0.4em] italic shadow-sm"])}" data-v-5a4b433f${_scopeId}>${ssrInterpolate(plan.name)}</h4><div class="flex flex-col gap-1" data-v-5a4b433f${_scopeId}><span class="${ssrRenderClass([plan.is_recommended ? "text-white" : "text-on-surface dark:text-white", "text-5xl font-black tracking-tighter tabular-nums italic"])}" data-v-5a4b433f${_scopeId}>${ssrInterpolate(formatPrice(isAnnual.value ? plan.price_annual : plan.price_monthly))}</span>`);
							if (plan.price_monthly !== null) _push(`<span class="text-[10px] font-bold uppercase tracking-widest opacity-40 ml-1" data-v-5a4b433f${_scopeId}>Por Unidad / Mes</span>`);
							else _push(`<!---->`);
							_push(`</div><p class="${ssrRenderClass([plan.is_recommended ? "text-white/70" : "text-on-surface-variant/60 dark:text-white/30", "text-xs font-semibold leading-relaxed italic h-12"])}" data-v-5a4b433f${_scopeId}>${ssrInterpolate(plan.description)}</p><div class="w-full h-px bg-white/10" data-v-5a4b433f${_scopeId}></div><ul class="space-y-6" data-v-5a4b433f${_scopeId}><!--[-->`);
							ssrRenderList(plan.features, (feature, idx) => {
								_push(`<li class="flex items-center gap-4 group/item" data-v-5a4b433f${_scopeId}><div class="${ssrRenderClass([plan.is_recommended ? "bg-white/10 text-white" : "bg-primary/10 text-primary", "w-6 h-6 rounded-lg flex items-center justify-center transition-all group-hover/item:scale-110"])}" data-v-5a4b433f${_scopeId}><span class="material-symbols-rounded text-base" data-v-5a4b433f${_scopeId}>verified</span></div><span class="${ssrRenderClass([plan.is_recommended ? "text-white/90" : "text-on-surface/80 dark:text-white/60", "text-[11px] font-bold uppercase tracking-tighter"])}" data-v-5a4b433f${_scopeId}>${ssrInterpolate(feature)}</span></li>`);
							});
							_push(`<!--]--></ul></div>`);
							_push(ssrRenderComponent(Button_default, {
								href: plan.price_monthly === null ? "/#contacto" : _ctx.route("register"),
								variant: "primary",
								class: ["w-full !h-16 !rounded-2xl !text-[11px] font-black uppercase italic shadow-2xl transition-all", [plan.is_recommended ? "bg-white !text-primary hover:bg-neutral-100 shadow-white/10" : "shadow-primary/20"]]
							}, {
								default: withCtx((_, _push, _parent, _scopeId) => {
									if (_push) _push(`${ssrInterpolate(plan.price_monthly === null ? "Contactar Ingeniería" : "Activar Licencia")}`);
									else return [createTextVNode(toDisplayString(plan.price_monthly === null ? "Contactar Ingeniería" : "Activar Licencia"), 1)];
								}),
								_: 2
							}, _parent, _scopeId));
						} else return [
							plan.is_recommended ? (openBlock(), createBlock("div", {
								key: 0,
								class: "absolute -top-6 left-1/2 -translate-x-1/2 bg-secondary text-white px-8 py-2 rounded-2xl text-[10px] font-black uppercase tracking-[0.3em] italic shadow-2xl"
							}, " Monitor Recomendado ")) : createCommentVNode("", true),
							createVNode("div", { class: "space-y-8 mb-12" }, [
								createVNode("h4", { class: ["text-[11px] font-black uppercase tracking-[0.4em] italic shadow-sm", plan.is_recommended ? "text-white/60" : "text-primary"] }, toDisplayString(plan.name), 3),
								createVNode("div", { class: "flex flex-col gap-1" }, [createVNode("span", { class: ["text-5xl font-black tracking-tighter tabular-nums italic", plan.is_recommended ? "text-white" : "text-on-surface dark:text-white"] }, toDisplayString(formatPrice(isAnnual.value ? plan.price_annual : plan.price_monthly)), 3), plan.price_monthly !== null ? (openBlock(), createBlock("span", {
									key: 0,
									class: "text-[10px] font-bold uppercase tracking-widest opacity-40 ml-1"
								}, "Por Unidad / Mes")) : createCommentVNode("", true)]),
								createVNode("p", { class: ["text-xs font-semibold leading-relaxed italic h-12", plan.is_recommended ? "text-white/70" : "text-on-surface-variant/60 dark:text-white/30"] }, toDisplayString(plan.description), 3),
								createVNode("div", { class: "w-full h-px bg-white/10" }),
								createVNode("ul", { class: "space-y-6" }, [(openBlock(true), createBlock(Fragment, null, renderList(plan.features, (feature, idx) => {
									return openBlock(), createBlock("li", {
										key: idx,
										class: "flex items-center gap-4 group/item"
									}, [createVNode("div", { class: ["w-6 h-6 rounded-lg flex items-center justify-center transition-all group-hover/item:scale-110", plan.is_recommended ? "bg-white/10 text-white" : "bg-primary/10 text-primary"] }, [createVNode("span", { class: "material-symbols-rounded text-base" }, "verified")], 2), createVNode("span", { class: ["text-[11px] font-bold uppercase tracking-tighter", plan.is_recommended ? "text-white/90" : "text-on-surface/80 dark:text-white/60"] }, toDisplayString(feature), 3)]);
								}), 128))])
							]),
							createVNode(Button_default, {
								href: plan.price_monthly === null ? "/#contacto" : _ctx.route("register"),
								variant: "primary",
								class: ["w-full !h-16 !rounded-2xl !text-[11px] font-black uppercase italic shadow-2xl transition-all", [plan.is_recommended ? "bg-white !text-primary hover:bg-neutral-100 shadow-white/10" : "shadow-primary/20"]]
							}, {
								default: withCtx(() => [createTextVNode(toDisplayString(plan.price_monthly === null ? "Contactar Ingeniería" : "Activar Licencia"), 1)]),
								_: 2
							}, 1032, ["href", "class"])
						];
					}),
					_: 2
				}, _parent));
			});
			_push(`<!--]--></div></div></section>`);
		};
	}
});
//#endregion
//#region resources/js/Components/Landing/PricingSection.vue
var _sfc_setup$3 = PricingSection_vue_vue_type_script_setup_true_lang_default.setup;
PricingSection_vue_vue_type_script_setup_true_lang_default.setup = (props, ctx) => {
	const ssrContext = useSSRContext();
	(ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/Landing/PricingSection.vue");
	return _sfc_setup$3 ? _sfc_setup$3(props, ctx) : void 0;
};
var PricingSection_default = /* @__PURE__ */ _plugin_vue_export_helper_default(PricingSection_vue_vue_type_script_setup_true_lang_default, [["__scopeId", "data-v-5a4b433f"]]);
//#endregion
//#region resources/js/Components/Landing/ContactDemoSection.vue?vue&type=script&setup=true&lang.ts
var ContactDemoSection_vue_vue_type_script_setup_true_lang_default = /* @__PURE__ */ defineComponent({
	__name: "ContactDemoSection",
	__ssrInlineRender: true,
	setup(__props) {
		const showSuccessMessage = ref(false);
		const form = useForm({
			fullName: "",
			email: "",
			phone: "",
			buildingName: "",
			honeypot: ""
		});
		const submit = () => {
			form.post(route("demo.request"), {
				preserveScroll: true,
				onSuccess: () => {
					form.reset();
					showSuccessMessage.value = true;
					setTimeout(() => {
						showSuccessMessage.value = false;
					}, 6e3);
				}
			});
		};
		return (_ctx, _push, _parent, _attrs) => {
			_push(`<section${ssrRenderAttrs(mergeProps({
				id: "contacto",
				class: "py-32 md:py-48 bg-surface dark:bg-[#080a0f] relative overflow-hidden"
			}, _attrs))} data-v-a9be0695><div class="absolute top-0 right-0 w-[1000px] h-[1000px] bg-primary/5 blur-[150px] rounded-full pointer-events-none -mr-96 -mt-96" data-v-a9be0695></div><div class="absolute bottom-0 left-0 w-[600px] h-[600px] bg-secondary/5 blur-[120px] rounded-full pointer-events-none -ml-48 -mb-48" data-v-a9be0695></div><div class="max-w-7xl mx-auto px-6 relative z-10" data-v-a9be0695>`);
			_push(ssrRenderComponent(Card_default, { class: "!p-0 !rounded-[4rem] border-2 border-outline-variant/10 dark:border-white/5 shadow-[0_50px_100px_-20px_rgba(0,0,0,0.5)] overflow-hidden bg-white dark:bg-[#0b0e14] relative" }, {
				default: withCtx((_, _push, _parent, _scopeId) => {
					if (_push) {
						_push(``);
						if (showSuccessMessage.value) _push(`<div class="absolute inset-0 bg-primary z-50 flex flex-col items-center justify-center text-center p-12 italic" data-v-a9be0695${_scopeId}><div class="w-32 h-32 bg-white/20 rounded-[2.5rem] flex items-center justify-center mb-10 text-white animate-bounce shadow-2xl border-4 border-white/20" data-v-a9be0695${_scopeId}><span class="material-symbols-rounded text-7xl" data-v-a9be0695${_scopeId}>rocket_launch</span></div><h3 class="text-6xl md:text-7xl font-black text-white mb-6 uppercase tracking-tighter" data-v-a9be0695${_scopeId}>¡Transmisión Exitosa!</h3><p class="text-white/70 max-w-xl text-lg font-bold uppercase tracking-widest leading-relaxed" data-v-a9be0695${_scopeId}> Dispatcher activado. Un consultor técnico de Nexo-Pro iniciará el protocolo de contacto en menos de 24 horas. </p><button class="mt-16 text-white/30 font-black uppercase tracking-[0.5em] hover:text-white transition-all text-[11px] underline underline-offset-8" data-v-a9be0695${_scopeId}>Nueva Operación de Registro</button></div>`);
						else _push(`<!---->`);
						_push(`<div class="flex flex-col lg:flex-row min-h-[800px]" data-v-a9be0695${_scopeId}><div class="lg:w-[40%] bg-primary p-12 md:p-20 text-white flex flex-col justify-between italic relative overflow-hidden shrink-0" data-v-a9be0695${_scopeId}><div class="absolute top-0 left-0 w-full h-full bg-gradient-to-br from-white/20 to-transparent opacity-50" data-v-a9be0695${_scopeId}></div><div class="absolute inset-0 opacity-10" data-v-a9be0695${_scopeId}><div class="absolute top-1/4 left-0 w-full h-px bg-white transform rotate-12" data-v-a9be0695${_scopeId}></div><div class="absolute top-2/4 left-0 w-full h-px bg-white transform -rotate-12" data-v-a9be0695${_scopeId}></div></div><div class="relative z-10 space-y-10" data-v-a9be0695${_scopeId}><div class="flex items-center gap-4" data-v-a9be0695${_scopeId}><div class="w-2 h-10 bg-secondary rounded-full" data-v-a9be0695${_scopeId}></div><span class="text-[11px] font-black uppercase tracking-[0.6em] text-white/60" data-v-a9be0695${_scopeId}>Dispatcher de Acceso</span></div><h2 class="text-6xl md:text-7xl font-black uppercase tracking-tighter leading-[0.9]" data-v-a9be0695${_scopeId}>Inicia la <br data-v-a9be0695${_scopeId}> <span class="text-secondary italic font-black" data-v-a9be0695${_scopeId}>Evolución</span></h2><p class="text-base md:text-lg font-bold text-white/70 uppercase tracking-widest leading-relaxed max-w-md" data-v-a9be0695${_scopeId}>Descubre por qué la élite de la propiedad raíz en Colombia ha migrado al ecosistema Nexo-Pro.</p></div><div class="relative z-10 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-1 gap-10 pt-20" data-v-a9be0695${_scopeId}><div class="space-y-4 group cursor-pointer" data-v-a9be0695${_scopeId}><p class="text-[9px] font-black uppercase tracking-[0.4em] text-white/40" data-v-a9be0695${_scopeId}>Frecuencia de Voz</p><div class="flex items-center gap-5" data-v-a9be0695${_scopeId}><div class="w-14 h-14 rounded-2xl bg-white/10 flex items-center justify-center group-hover:bg-secondary group-hover:text-white transition-all duration-500 shadow-xl border border-white/10" data-v-a9be0695${_scopeId}><span class="material-symbols-rounded text-2xl" data-v-a9be0695${_scopeId}>call</span></div><span class="text-base font-black uppercase tracking-tighter tabular-nums" data-v-a9be0695${_scopeId}>+57 (601) 444 0000</span></div></div><div class="space-y-4 group cursor-pointer" data-v-a9be0695${_scopeId}><p class="text-[9px] font-black uppercase tracking-[0.4em] text-white/40" data-v-a9be0695${_scopeId}>Canal de Ingeniería</p><div class="flex items-center gap-5" data-v-a9be0695${_scopeId}><div class="w-14 h-14 rounded-2xl bg-white/10 flex items-center justify-center group-hover:bg-secondary group-hover:text-white transition-all duration-500 shadow-xl border border-white/10" data-v-a9be0695${_scopeId}><span class="material-symbols-rounded text-2xl" data-v-a9be0695${_scopeId}>alternate_email</span></div><span class="text-base font-black uppercase tracking-tighter italic" data-v-a9be0695${_scopeId}>ingenieria@nexo-pro.io</span></div></div></div></div><form class="flex-1 p-12 md:p-24 space-y-12 bg-white dark:bg-[#0b0e14]" data-v-a9be0695${_scopeId}><div class="space-y-4 mb-12" data-v-a9be0695${_scopeId}><h3 class="text-2xl font-black text-on-surface dark:text-white uppercase tracking-tighter italic" data-v-a9be0695${_scopeId}>Protocolo de Registro de Cliente</h3><p class="text-sm font-medium text-on-surface-variant/60 dark:text-white/30 uppercase tracking-widest" data-v-a9be0695${_scopeId}>Ingrese los metadatos obligatorios para iniciar la auditoría técnica.</p></div><div class="grid grid-cols-1 md:grid-cols-2 gap-10 lg:gap-12" data-v-a9be0695${_scopeId}>`);
						_push(ssrRenderComponent(Input_default, {
							modelValue: unref(form).fullName,
							"onUpdate:modelValue": ($event) => unref(form).fullName = $event,
							label: "Nombre Completo del Mando",
							placeholder: "Ej: Ing. Mauricio Silva",
							required: "",
							icon: "person",
							class: "scale-105"
						}, null, _parent, _scopeId));
						_push(ssrRenderComponent(Input_default, {
							modelValue: unref(form).email,
							"onUpdate:modelValue": ($event) => unref(form).email = $event,
							type: "email",
							label: "Email Corporativo",
							placeholder: "contacto@edificio.com",
							required: "",
							icon: "mail",
							class: "scale-105"
						}, null, _parent, _scopeId));
						_push(ssrRenderComponent(Input_default, {
							modelValue: unref(form).phone,
							"onUpdate:modelValue": ($event) => unref(form).phone = $event,
							type: "tel",
							label: "Línea de Enlace Móvil",
							placeholder: "+57 300 000 0000",
							required: "",
							icon: "smartphone",
							class: "scale-105"
						}, null, _parent, _scopeId));
						_push(ssrRenderComponent(Input_default, {
							modelValue: unref(form).buildingName,
							"onUpdate:modelValue": ($event) => unref(form).buildingName = $event,
							label: "Nombre de la Copropiedad",
							placeholder: "Residencial Las Acacias...",
							required: "",
							icon: "domain",
							class: "scale-105"
						}, null, _parent, _scopeId));
						_push(`</div><div class="space-y-10 pt-8" data-v-a9be0695${_scopeId}><div class="flex items-start gap-4 p-6 rounded-2xl bg-surface-container-low dark:bg-white/[0.03] border border-outline-variant/10 dark:border-white/5 hover:border-primary/30 transition-all cursor-pointer group" data-v-a9be0695${_scopeId}><input type="checkbox" required class="mt-1 w-6 h-6 rounded border-2 border-primary text-primary focus:ring-8 focus:ring-primary/5 cursor-pointer transition-all" data-v-a9be0695${_scopeId}><span class="text-[11px] font-black text-on-surface dark:text-white/50 uppercase tracking-[0.2em] italic leading-relaxed group-hover:text-primary transition-colors" data-v-a9be0695${_scopeId}>Acepto los términos de tratamiento de datos bajo el protocolo de seguridad Nexo-Armor y la política de privacidad PropTech.</span></div>`);
						_push(ssrRenderComponent(Button_default, {
							type: "submit",
							variant: "primary",
							class: "w-full !h-24 !rounded-[2rem] !text-sm font-black uppercase italic shadow-[0_20px_50px_rgba(var(--primary),0.3)] hover:scale-[1.02] active:scale-95 transition-all duration-500",
							disabled: unref(form).processing,
							icon: "double_arrow"
						}, {
							default: withCtx((_, _push, _parent, _scopeId) => {
								if (_push) _push(`${ssrInterpolate(unref(form).processing ? "DESPLEGANDO PROTOCOLO..." : "Activar Demostración de Ingeniería")}`);
								else return [createTextVNode(toDisplayString(unref(form).processing ? "DESPLEGANDO PROTOCOLO..." : "Activar Demostración de Ingeniería"), 1)];
							}),
							_: 1
						}, _parent, _scopeId));
						_push(`</div></form></div>`);
					} else return [createVNode(Transition, {
						"enter-active-class": "transition duration-700 ease-out",
						"enter-from-class": "opacity-0 scale-95",
						"enter-to-class": "opacity-100 scale-100",
						"leave-active-class": "transition duration-500 ease-in",
						"leave-from-class": "opacity-100 scale-100",
						"leave-to-class": "opacity-0 scale-95"
					}, {
						default: withCtx(() => [showSuccessMessage.value ? (openBlock(), createBlock("div", {
							key: 0,
							class: "absolute inset-0 bg-primary z-50 flex flex-col items-center justify-center text-center p-12 italic"
						}, [
							createVNode("div", { class: "w-32 h-32 bg-white/20 rounded-[2.5rem] flex items-center justify-center mb-10 text-white animate-bounce shadow-2xl border-4 border-white/20" }, [createVNode("span", { class: "material-symbols-rounded text-7xl" }, "rocket_launch")]),
							createVNode("h3", { class: "text-6xl md:text-7xl font-black text-white mb-6 uppercase tracking-tighter" }, "¡Transmisión Exitosa!"),
							createVNode("p", { class: "text-white/70 max-w-xl text-lg font-bold uppercase tracking-widest leading-relaxed" }, " Dispatcher activado. Un consultor técnico de Nexo-Pro iniciará el protocolo de contacto en menos de 24 horas. "),
							createVNode("button", {
								onClick: ($event) => showSuccessMessage.value = false,
								class: "mt-16 text-white/30 font-black uppercase tracking-[0.5em] hover:text-white transition-all text-[11px] underline underline-offset-8"
							}, "Nueva Operación de Registro", 8, ["onClick"])
						])) : createCommentVNode("", true)]),
						_: 1
					}), createVNode("div", { class: "flex flex-col lg:flex-row min-h-[800px]" }, [createVNode("div", { class: "lg:w-[40%] bg-primary p-12 md:p-20 text-white flex flex-col justify-between italic relative overflow-hidden shrink-0" }, [
						createVNode("div", { class: "absolute top-0 left-0 w-full h-full bg-gradient-to-br from-white/20 to-transparent opacity-50" }),
						createVNode("div", { class: "absolute inset-0 opacity-10" }, [createVNode("div", { class: "absolute top-1/4 left-0 w-full h-px bg-white transform rotate-12" }), createVNode("div", { class: "absolute top-2/4 left-0 w-full h-px bg-white transform -rotate-12" })]),
						createVNode("div", { class: "relative z-10 space-y-10" }, [
							createVNode("div", { class: "flex items-center gap-4" }, [createVNode("div", { class: "w-2 h-10 bg-secondary rounded-full" }), createVNode("span", { class: "text-[11px] font-black uppercase tracking-[0.6em] text-white/60" }, "Dispatcher de Acceso")]),
							createVNode("h2", { class: "text-6xl md:text-7xl font-black uppercase tracking-tighter leading-[0.9]" }, [
								createTextVNode("Inicia la "),
								createVNode("br"),
								createTextVNode(),
								createVNode("span", { class: "text-secondary italic font-black" }, "Evolución")
							]),
							createVNode("p", { class: "text-base md:text-lg font-bold text-white/70 uppercase tracking-widest leading-relaxed max-w-md" }, "Descubre por qué la élite de la propiedad raíz en Colombia ha migrado al ecosistema Nexo-Pro.")
						]),
						createVNode("div", { class: "relative z-10 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-1 gap-10 pt-20" }, [createVNode("div", { class: "space-y-4 group cursor-pointer" }, [createVNode("p", { class: "text-[9px] font-black uppercase tracking-[0.4em] text-white/40" }, "Frecuencia de Voz"), createVNode("div", { class: "flex items-center gap-5" }, [createVNode("div", { class: "w-14 h-14 rounded-2xl bg-white/10 flex items-center justify-center group-hover:bg-secondary group-hover:text-white transition-all duration-500 shadow-xl border border-white/10" }, [createVNode("span", { class: "material-symbols-rounded text-2xl" }, "call")]), createVNode("span", { class: "text-base font-black uppercase tracking-tighter tabular-nums" }, "+57 (601) 444 0000")])]), createVNode("div", { class: "space-y-4 group cursor-pointer" }, [createVNode("p", { class: "text-[9px] font-black uppercase tracking-[0.4em] text-white/40" }, "Canal de Ingeniería"), createVNode("div", { class: "flex items-center gap-5" }, [createVNode("div", { class: "w-14 h-14 rounded-2xl bg-white/10 flex items-center justify-center group-hover:bg-secondary group-hover:text-white transition-all duration-500 shadow-xl border border-white/10" }, [createVNode("span", { class: "material-symbols-rounded text-2xl" }, "alternate_email")]), createVNode("span", { class: "text-base font-black uppercase tracking-tighter italic" }, "ingenieria@nexo-pro.io")])])])
					]), createVNode("form", {
						onSubmit: withModifiers(submit, ["prevent"]),
						class: "flex-1 p-12 md:p-24 space-y-12 bg-white dark:bg-[#0b0e14]"
					}, [
						createVNode("div", { class: "space-y-4 mb-12" }, [createVNode("h3", { class: "text-2xl font-black text-on-surface dark:text-white uppercase tracking-tighter italic" }, "Protocolo de Registro de Cliente"), createVNode("p", { class: "text-sm font-medium text-on-surface-variant/60 dark:text-white/30 uppercase tracking-widest" }, "Ingrese los metadatos obligatorios para iniciar la auditoría técnica.")]),
						createVNode("div", { class: "grid grid-cols-1 md:grid-cols-2 gap-10 lg:gap-12" }, [
							createVNode(Input_default, {
								modelValue: unref(form).fullName,
								"onUpdate:modelValue": ($event) => unref(form).fullName = $event,
								label: "Nombre Completo del Mando",
								placeholder: "Ej: Ing. Mauricio Silva",
								required: "",
								icon: "person",
								class: "scale-105"
							}, null, 8, ["modelValue", "onUpdate:modelValue"]),
							createVNode(Input_default, {
								modelValue: unref(form).email,
								"onUpdate:modelValue": ($event) => unref(form).email = $event,
								type: "email",
								label: "Email Corporativo",
								placeholder: "contacto@edificio.com",
								required: "",
								icon: "mail",
								class: "scale-105"
							}, null, 8, ["modelValue", "onUpdate:modelValue"]),
							createVNode(Input_default, {
								modelValue: unref(form).phone,
								"onUpdate:modelValue": ($event) => unref(form).phone = $event,
								type: "tel",
								label: "Línea de Enlace Móvil",
								placeholder: "+57 300 000 0000",
								required: "",
								icon: "smartphone",
								class: "scale-105"
							}, null, 8, ["modelValue", "onUpdate:modelValue"]),
							createVNode(Input_default, {
								modelValue: unref(form).buildingName,
								"onUpdate:modelValue": ($event) => unref(form).buildingName = $event,
								label: "Nombre de la Copropiedad",
								placeholder: "Residencial Las Acacias...",
								required: "",
								icon: "domain",
								class: "scale-105"
							}, null, 8, ["modelValue", "onUpdate:modelValue"])
						]),
						createVNode("div", { class: "space-y-10 pt-8" }, [createVNode("div", { class: "flex items-start gap-4 p-6 rounded-2xl bg-surface-container-low dark:bg-white/[0.03] border border-outline-variant/10 dark:border-white/5 hover:border-primary/30 transition-all cursor-pointer group" }, [createVNode("input", {
							type: "checkbox",
							required: "",
							class: "mt-1 w-6 h-6 rounded border-2 border-primary text-primary focus:ring-8 focus:ring-primary/5 cursor-pointer transition-all"
						}), createVNode("span", { class: "text-[11px] font-black text-on-surface dark:text-white/50 uppercase tracking-[0.2em] italic leading-relaxed group-hover:text-primary transition-colors" }, "Acepto los términos de tratamiento de datos bajo el protocolo de seguridad Nexo-Armor y la política de privacidad PropTech.")]), createVNode(Button_default, {
							type: "submit",
							variant: "primary",
							class: "w-full !h-24 !rounded-[2rem] !text-sm font-black uppercase italic shadow-[0_20px_50px_rgba(var(--primary),0.3)] hover:scale-[1.02] active:scale-95 transition-all duration-500",
							disabled: unref(form).processing,
							icon: "double_arrow"
						}, {
							default: withCtx(() => [createTextVNode(toDisplayString(unref(form).processing ? "DESPLEGANDO PROTOCOLO..." : "Activar Demostración de Ingeniería"), 1)]),
							_: 1
						}, 8, ["disabled"])])
					], 32)])];
				}),
				_: 1
			}, _parent));
			_push(`</div></section>`);
		};
	}
});
//#endregion
//#region resources/js/Components/Landing/ContactDemoSection.vue
var _sfc_setup$2 = ContactDemoSection_vue_vue_type_script_setup_true_lang_default.setup;
ContactDemoSection_vue_vue_type_script_setup_true_lang_default.setup = (props, ctx) => {
	const ssrContext = useSSRContext();
	(ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/Landing/ContactDemoSection.vue");
	return _sfc_setup$2 ? _sfc_setup$2(props, ctx) : void 0;
};
var ContactDemoSection_default = /* @__PURE__ */ _plugin_vue_export_helper_default(ContactDemoSection_vue_vue_type_script_setup_true_lang_default, [["__scopeId", "data-v-a9be0695"]]);
//#endregion
//#region resources/js/Components/UI/CookieConsent.vue?vue&type=script&setup=true&lang.ts
var CookieConsent_vue_vue_type_script_setup_true_lang_default = /* @__PURE__ */ defineComponent({
	__name: "CookieConsent",
	__ssrInlineRender: true,
	setup(__props) {
		const showLegalModal = ref(false);
		const legalModalType = ref("cookies");
		const showBanner = ref(false);
		onMounted(() => {
			if (!localStorage.getItem("nexo_cookie_consent")) showBanner.value = true;
		});
		const acceptAll = () => {
			localStorage.setItem("nexo_cookie_consent", "all");
			showBanner.value = false;
		};
		const acceptEssential = () => {
			localStorage.setItem("nexo_cookie_consent", "essential");
			showBanner.value = false;
		};
		return (_ctx, _push, _parent, _attrs) => {
			_push(`<!--[-->`);
			if (showBanner.value) {
				_push(`<div class="fixed bottom-0 left-0 right-0 z-[100] p-4 pointer-events-none" data-v-e0b33e52><div class="max-w-4xl mx-auto bg-surface/95 backdrop-blur-md border border-outline-variant/30 shadow-2xl rounded-3xl p-6 pointer-events-auto flex flex-col md:flex-row items-center gap-6" data-v-e0b33e52><div class="flex-1 space-y-2" data-v-e0b33e52><div class="flex items-center gap-2 text-primary" data-v-e0b33e52><span class="material-symbols-outlined" data-v-e0b33e52>cookie</span><h3 class="font-black tracking-tight uppercase" data-v-e0b33e52>Tu Privacidad</h3></div><p class="text-xs text-on-surface-variant font-medium leading-relaxed" data-v-e0b33e52> NEXO-PRO utiliza cookies técnicas y analíticas conforme a las directrices de la SIC (Colombia) y la Ley de Habeas Data. <button class="text-primary font-bold hover:underline ml-1" data-v-e0b33e52>Ver Política de Cookies.</button> | <button class="text-primary font-bold hover:underline ml-1" data-v-e0b33e52>Política de Privacidad.</button></p></div><div class="flex flex-col sm:flex-row items-center gap-3 w-full md:w-auto shrink-0" data-v-e0b33e52>`);
				_push(ssrRenderComponent(Button_default, {
					variant: "ghost",
					size: "sm",
					class: "w-full sm:w-auto text-xs",
					onClick: acceptEssential
				}, {
					default: withCtx((_, _push, _parent, _scopeId) => {
						if (_push) _push(` Solo Esenciales `);
						else return [createTextVNode(" Solo Esenciales ")];
					}),
					_: 1
				}, _parent));
				_push(ssrRenderComponent(Button_default, {
					variant: "primary",
					size: "sm",
					class: "w-full sm:w-auto text-xs shadow-lg shadow-primary/20",
					onClick: acceptAll
				}, {
					default: withCtx((_, _push, _parent, _scopeId) => {
						if (_push) _push(` Aceptar Todas `);
						else return [createTextVNode(" Aceptar Todas ")];
					}),
					_: 1
				}, _parent));
				_push(`</div></div></div>`);
			} else _push(`<!---->`);
			if (showLegalModal.value) _push(ssrRenderComponent(Modal_default, {
				onClose: ($event) => showLegalModal.value = false,
				maxWidth: "4xl"
			}, {
				default: withCtx((_, _push, _parent, _scopeId) => {
					if (_push) {
						_push(`<div class="px-6 py-6 md:px-8 md:py-8 border-b border-outline-variant/10 flex justify-between items-center sticky top-0 bg-surface/80 backdrop-blur-xl z-[150]" data-v-e0b33e52${_scopeId}><h3 class="text-sm font-black text-on-surface uppercase tracking-widest" data-v-e0b33e52${_scopeId}> Información Legal </h3><button class="p-2 rounded-xl text-on-surface-variant hover:bg-surface-container-high transition-colors" data-v-e0b33e52${_scopeId}><span class="material-symbols-outlined text-lg" data-v-e0b33e52${_scopeId}>close</span></button></div><div class="p-6 md:p-8 max-h-[60vh] overflow-y-auto custom-scrollbar bg-surface text-left" data-v-e0b33e52${_scopeId}>`);
						_push(ssrRenderComponent(LegalContent_default, { type: legalModalType.value }, null, _parent, _scopeId));
						_push(`</div><div class="px-6 py-4 md:px-8 bg-surface-container-low border-t border-outline-variant/10 flex justify-end" data-v-e0b33e52${_scopeId}>`);
						_push(ssrRenderComponent(Button_default, {
							variant: "ghost",
							onClick: ($event) => showLegalModal.value = false
						}, {
							default: withCtx((_, _push, _parent, _scopeId) => {
								if (_push) _push(`Entendido`);
								else return [createTextVNode("Entendido")];
							}),
							_: 1
						}, _parent, _scopeId));
						_push(`</div>`);
					} else return [
						createVNode("div", { class: "px-6 py-6 md:px-8 md:py-8 border-b border-outline-variant/10 flex justify-between items-center sticky top-0 bg-surface/80 backdrop-blur-xl z-[150]" }, [createVNode("h3", { class: "text-sm font-black text-on-surface uppercase tracking-widest" }, " Información Legal "), createVNode("button", {
							onClick: ($event) => showLegalModal.value = false,
							class: "p-2 rounded-xl text-on-surface-variant hover:bg-surface-container-high transition-colors"
						}, [createVNode("span", { class: "material-symbols-outlined text-lg" }, "close")], 8, ["onClick"])]),
						createVNode("div", { class: "p-6 md:p-8 max-h-[60vh] overflow-y-auto custom-scrollbar bg-surface text-left" }, [createVNode(LegalContent_default, { type: legalModalType.value }, null, 8, ["type"])]),
						createVNode("div", { class: "px-6 py-4 md:px-8 bg-surface-container-low border-t border-outline-variant/10 flex justify-end" }, [createVNode(Button_default, {
							variant: "ghost",
							onClick: ($event) => showLegalModal.value = false
						}, {
							default: withCtx(() => [createTextVNode("Entendido")]),
							_: 1
						}, 8, ["onClick"])])
					];
				}),
				_: 1
			}, _parent));
			else _push(`<!---->`);
			_push(`<!--]-->`);
		};
	}
});
//#endregion
//#region resources/js/Components/UI/CookieConsent.vue
var _sfc_setup$1 = CookieConsent_vue_vue_type_script_setup_true_lang_default.setup;
CookieConsent_vue_vue_type_script_setup_true_lang_default.setup = (props, ctx) => {
	const ssrContext = useSSRContext();
	(ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/UI/CookieConsent.vue");
	return _sfc_setup$1 ? _sfc_setup$1(props, ctx) : void 0;
};
var CookieConsent_default = /* @__PURE__ */ _plugin_vue_export_helper_default(CookieConsent_vue_vue_type_script_setup_true_lang_default, [["__scopeId", "data-v-e0b33e52"]]);
//#endregion
//#region resources/js/Pages/Welcome.vue?vue&type=script&setup=true&lang.ts
var Welcome_vue_vue_type_script_setup_true_lang_default = /* @__PURE__ */ defineComponent({
	__name: "Welcome",
	__ssrInlineRender: true,
	props: {
		features: {
			type: Array,
			required: true
		},
		plans: {
			type: Array,
			required: true
		}
	},
	setup(__props) {
		onMounted(() => {
			const observer = new IntersectionObserver((entries) => {
				entries.forEach((entry) => {
					if (entry.isIntersecting) {
						entry.target.classList.add("aos-animate");
						observer.unobserve(entry.target);
					}
				});
			}, {
				threshold: .1,
				rootMargin: "0px 0px -50px 0px"
			});
			document.querySelectorAll("[data-aos]").forEach((el) => {
				observer.observe(el);
			});
		});
		return (_ctx, _push, _parent, _attrs) => {
			_push(`<!--[-->`);
			_push(ssrRenderComponent(unref(Head), { title: "Bienvenido" }, null, _parent));
			_push(ssrRenderComponent(GuestLayout_default, null, {
				default: withCtx((_, _push, _parent, _scopeId) => {
					if (_push) {
						_push(`<div class="fixed inset-0 pointer-events-none z-0 overflow-hidden"${_scopeId}><div class="absolute inset-0 bg-[url(&#39;https://www.transparenttextures.com/patterns/carbon-fibre.png&#39;)] opacity-[0.03] dark:opacity-[0.05]"${_scopeId}></div><div class="absolute top-1/4 left-1/4 w-[500px] h-[500px] bg-primary/5 blur-[120px] rounded-full animate-float pointer-events-none"${_scopeId}></div><div class="absolute bottom-1/4 right-1/4 w-[400px] h-[400px] bg-secondary/5 blur-[100px] rounded-full animate-float-delayed pointer-events-none"${_scopeId}></div></div><div class="relative z-10"${_scopeId}><div data-aos="fade"${_scopeId}>`);
						_push(ssrRenderComponent(HeroSection_default, null, null, _parent, _scopeId));
						_push(`</div><div data-aos="fade-up" class="bg-surface-container-low/40 dark:bg-transparent"${_scopeId}>`);
						_push(ssrRenderComponent(RolesSection_default, { id: "roles" }, null, _parent, _scopeId));
						_push(`</div><div data-aos="fade-up"${_scopeId}>`);
						_push(ssrRenderComponent(FeaturesSection_default, {
							features: __props.features,
							id: "soluciones"
						}, null, _parent, _scopeId));
						_push(`</div><div data-aos="fade-up" class="bg-primary/5 dark:bg-transparent"${_scopeId}>`);
						_push(ssrRenderComponent(PricingSection_default, {
							plans: __props.plans,
							id: "precios"
						}, null, _parent, _scopeId));
						_push(`</div><div data-aos="fade-up"${_scopeId}>`);
						_push(ssrRenderComponent(ContactDemoSection_default, { id: "contacto" }, null, _parent, _scopeId));
						_push(`</div></div>`);
						_push(ssrRenderComponent(CookieConsent_default, null, null, _parent, _scopeId));
					} else return [
						createVNode("div", { class: "fixed inset-0 pointer-events-none z-0 overflow-hidden" }, [
							createVNode("div", { class: "absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')] opacity-[0.03] dark:opacity-[0.05]" }),
							createVNode("div", { class: "absolute top-1/4 left-1/4 w-[500px] h-[500px] bg-primary/5 blur-[120px] rounded-full animate-float pointer-events-none" }),
							createVNode("div", { class: "absolute bottom-1/4 right-1/4 w-[400px] h-[400px] bg-secondary/5 blur-[100px] rounded-full animate-float-delayed pointer-events-none" })
						]),
						createVNode("div", { class: "relative z-10" }, [
							createVNode("div", { "data-aos": "fade" }, [createVNode(HeroSection_default)]),
							createVNode("div", {
								"data-aos": "fade-up",
								class: "bg-surface-container-low/40 dark:bg-transparent"
							}, [createVNode(RolesSection_default, { id: "roles" })]),
							createVNode("div", { "data-aos": "fade-up" }, [createVNode(FeaturesSection_default, {
								features: __props.features,
								id: "soluciones"
							}, null, 8, ["features"])]),
							createVNode("div", {
								"data-aos": "fade-up",
								class: "bg-primary/5 dark:bg-transparent"
							}, [createVNode(PricingSection_default, {
								plans: __props.plans,
								id: "precios"
							}, null, 8, ["plans"])]),
							createVNode("div", { "data-aos": "fade-up" }, [createVNode(ContactDemoSection_default, { id: "contacto" })])
						]),
						createVNode(CookieConsent_default)
					];
				}),
				_: 1
			}, _parent));
			_push(`<!--]-->`);
		};
	}
});
//#endregion
//#region resources/js/Pages/Welcome.vue
var _sfc_setup = Welcome_vue_vue_type_script_setup_true_lang_default.setup;
Welcome_vue_vue_type_script_setup_true_lang_default.setup = (props, ctx) => {
	const ssrContext = useSSRContext();
	(ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Welcome.vue");
	return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
var Welcome_default = Welcome_vue_vue_type_script_setup_true_lang_default;
//#endregion
export { Welcome_default as default };

import { t as Button_default } from "./Button-5V-KYwxi.js";
import { t as Modal_default } from "./Modal-CeRVVYhy.js";
import { createTextVNode, createVNode, defineComponent, mergeProps, toDisplayString, useSSRContext, withCtx } from "vue";
import { ssrInterpolate, ssrRenderComponent } from "vue/server-renderer";
//#region resources/js/Components/UI/ConfirmModal.vue?vue&type=script&setup=true&lang.ts
var ConfirmModal_vue_vue_type_script_setup_true_lang_default = /* @__PURE__ */ defineComponent({
	__name: "ConfirmModal",
	__ssrInlineRender: true,
	props: {
		show: { type: Boolean },
		title: {},
		message: {},
		confirmLabel: { default: "Confirmar" },
		cancelLabel: { default: "Cancelar" },
		variant: { default: "primary" },
		loading: {
			type: Boolean,
			default: false
		}
	},
	emits: ["confirm", "cancel"],
	setup(__props) {
		return (_ctx, _push, _parent, _attrs) => {
			_push(ssrRenderComponent(Modal_default, mergeProps({
				show: __props.show,
				title: __props.title,
				onClose: ($event) => _ctx.$emit("cancel")
			}, _attrs), {
				default: withCtx((_, _push, _parent, _scopeId) => {
					if (_push) {
						_push(`<div class="space-y-6"${_scopeId}><p class="text-sm text-on-surface-variant leading-relaxed"${_scopeId}>${ssrInterpolate(__props.message)}</p><div class="flex gap-4"${_scopeId}>`);
						_push(ssrRenderComponent(Button_default, {
							variant: "ghost",
							class: "flex-1",
							onClick: ($event) => _ctx.$emit("cancel"),
							disabled: __props.loading
						}, {
							default: withCtx((_, _push, _parent, _scopeId) => {
								if (_push) _push(`${ssrInterpolate(__props.cancelLabel)}`);
								else return [createTextVNode(toDisplayString(__props.cancelLabel), 1)];
							}),
							_: 1
						}, _parent, _scopeId));
						_push(ssrRenderComponent(Button_default, {
							variant: __props.variant,
							class: "flex-1",
							onClick: ($event) => _ctx.$emit("confirm"),
							loading: __props.loading
						}, {
							default: withCtx((_, _push, _parent, _scopeId) => {
								if (_push) _push(`${ssrInterpolate(__props.confirmLabel)}`);
								else return [createTextVNode(toDisplayString(__props.confirmLabel), 1)];
							}),
							_: 1
						}, _parent, _scopeId));
						_push(`</div></div>`);
					} else return [createVNode("div", { class: "space-y-6" }, [createVNode("p", { class: "text-sm text-on-surface-variant leading-relaxed" }, toDisplayString(__props.message), 1), createVNode("div", { class: "flex gap-4" }, [createVNode(Button_default, {
						variant: "ghost",
						class: "flex-1",
						onClick: ($event) => _ctx.$emit("cancel"),
						disabled: __props.loading
					}, {
						default: withCtx(() => [createTextVNode(toDisplayString(__props.cancelLabel), 1)]),
						_: 1
					}, 8, ["onClick", "disabled"]), createVNode(Button_default, {
						variant: __props.variant,
						class: "flex-1",
						onClick: ($event) => _ctx.$emit("confirm"),
						loading: __props.loading
					}, {
						default: withCtx(() => [createTextVNode(toDisplayString(__props.confirmLabel), 1)]),
						_: 1
					}, 8, [
						"variant",
						"onClick",
						"loading"
					])])])];
				}),
				_: 1
			}, _parent));
		};
	}
});
//#endregion
//#region resources/js/Components/UI/ConfirmModal.vue
var _sfc_setup = ConfirmModal_vue_vue_type_script_setup_true_lang_default.setup;
ConfirmModal_vue_vue_type_script_setup_true_lang_default.setup = (props, ctx) => {
	const ssrContext = useSSRContext();
	(ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/UI/ConfirmModal.vue");
	return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
var ConfirmModal_default = ConfirmModal_vue_vue_type_script_setup_true_lang_default;
//#endregion
export { ConfirmModal_default as t };

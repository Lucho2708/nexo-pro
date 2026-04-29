import { t as _plugin_vue_export_helper_default } from "./_plugin-vue_export-helper-BjJHhBxR.js";
import { defineComponent, mergeProps, useSSRContext } from "vue";
import { ssrRenderAttrs, ssrRenderStyle } from "vue/server-renderer";
//#region resources/js/Components/UI/Logo.vue?vue&type=script&setup=true&lang.ts
var Logo_vue_vue_type_script_setup_true_lang_default = /* @__PURE__ */ defineComponent({
	__name: "Logo",
	__ssrInlineRender: true,
	props: {
		width: { default: "100px" },
		height: { default: "100px" },
		showText: {
			type: Boolean,
			default: true
		}
	},
	setup(__props) {
		const props = __props;
		return (_ctx, _push, _parent, _attrs) => {
			_push(`<div${ssrRenderAttrs(mergeProps({ class: "nexo-logo-wrapper" }, _ctx.$attrs, _attrs))} data-v-b06bd78e><div class="nexo-hexagon-container" style="${ssrRenderStyle({
				width: props.width,
				height: props.height
			})}" data-v-b06bd78e><svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg" data-v-b06bd78e><defs data-v-b06bd78e><linearGradient id="vueGradLeft" x1="0%" y1="0%" x2="0%" y2="100%" data-v-b06bd78e><stop offset="0%" stop-color="#00ffff" data-v-b06bd78e></stop><stop offset="100%" stop-color="#4a00e0" data-v-b06bd78e></stop></linearGradient><linearGradient id="vueGradRight" x1="0%" y1="0%" x2="0%" y2="100%" data-v-b06bd78e><stop offset="0%" stop-color="#ff00ff" data-v-b06bd78e></stop><stop offset="100%" stop-color="#4a00e0" data-v-b06bd78e></stop></linearGradient><linearGradient id="vueGradBot" x1="0%" y1="0%" x2="0%" y2="100%" data-v-b06bd78e><stop offset="0%" stop-color="#2a1b54" data-v-b06bd78e></stop><stop offset="100%" stop-color="#140a2e" data-v-b06bd78e></stop></linearGradient></defs><polygon class="nexo-panel nexo-panel-left" points="100,10 20,55 20,145 100,150" fill="url(#vueGradLeft)" data-v-b06bd78e></polygon><polygon class="nexo-panel nexo-panel-right" points="100,10 180,55 180,145 100,150" fill="url(#vueGradRight)" data-v-b06bd78e></polygon><polygon class="nexo-panel nexo-panel-bot-left" points="20,145 100,190 100,150" fill="url(#vueGradBot)" data-v-b06bd78e></polygon><polygon class="nexo-panel nexo-panel-bot-right" points="180,145 100,190 100,150" fill="url(#vueGradBot)" data-v-b06bd78e></polygon><polygon class="nexo-inner-glow" points="95,22 28,60 28,139 95,143" fill="none" stroke="#00ffff" stroke-width="2.5" data-v-b06bd78e></polygon><polygon class="nexo-inner-glow" points="105,22 172,60 172,139 105,143" fill="none" stroke="#ff00ff" stroke-width="2.5" data-v-b06bd78e></polygon><g class="nexo-framework" stroke-width="9" fill="none" stroke-linejoin="round" stroke-linecap="round" data-v-b06bd78e><path d="M 100,10 L 180,55 L 180,145 L 100,190 L 20,145 L 20,55 Z" data-v-b06bd78e></path><path d="M 100,10 L 100,150" data-v-b06bd78e></path><path d="M 100,150 L 20,145" data-v-b06bd78e></path><path d="M 100,150 L 180,145" data-v-b06bd78e></path><path d="M 100,150 L 100,190" data-v-b06bd78e></path></g><g class="nexo-glowing-n" data-v-b06bd78e><path class="nexo-n-path" d="M 55,135 L 55,65 L 100,100 L 145,135 L 145,65" fill="none" stroke="#ffaa00" stroke-width="14" stroke-linecap="round" stroke-linejoin="round" data-v-b06bd78e></path><path class="nexo-n-path nexo-n-inner" d="M 55,135 L 55,65 L 100,100 L 145,135 L 145,65" fill="none" stroke-width="6" stroke-linecap="round" stroke-linejoin="round" data-v-b06bd78e></path><g class="nexo-node nexo-n-in-1" style="${ssrRenderStyle({ "animation-delay": "1.2s" })}" data-v-b06bd78e><circle cx="55" cy="135" r="13" fill="#ffaa00" data-v-b06bd78e></circle><circle cx="55" cy="135" r="9" class="nexo-node-bg" data-v-b06bd78e></circle><circle cx="55" cy="135" r="5" fill="#00ff66" data-v-b06bd78e></circle></g><g class="nexo-node nexo-n-in-2" style="${ssrRenderStyle({ "animation-delay": "1.3s" })}" data-v-b06bd78e><circle cx="55" cy="65" r="13" fill="#ffaa00" data-v-b06bd78e></circle><circle cx="55" cy="65" r="9" class="nexo-node-bg" data-v-b06bd78e></circle><circle cx="55" cy="65" r="5" fill="#ff00ff" data-v-b06bd78e></circle></g><g class="nexo-node nexo-n-in-3" style="${ssrRenderStyle({ "animation-delay": "1.4s" })}" data-v-b06bd78e><circle cx="100" cy="100" r="13" fill="#ffaa00" data-v-b06bd78e></circle><circle cx="100" cy="100" r="9" class="nexo-node-bg" data-v-b06bd78e></circle><circle cx="100" cy="100" r="5" fill="#b000ff" data-v-b06bd78e></circle></g><g class="nexo-node nexo-n-in-4" style="${ssrRenderStyle({ "animation-delay": "1.5s" })}" data-v-b06bd78e><circle cx="145" cy="135" r="13" fill="#ffaa00" data-v-b06bd78e></circle><circle cx="145" cy="135" r="9" class="nexo-node-bg" data-v-b06bd78e></circle><circle cx="145" cy="135" r="5" fill="#ff00ff" data-v-b06bd78e></circle></g><g class="nexo-node nexo-n-in-5" style="${ssrRenderStyle({ "animation-delay": "1.6s" })}" data-v-b06bd78e><circle cx="145" cy="65" r="13" fill="#ffaa00" data-v-b06bd78e></circle><circle cx="145" cy="65" r="9" class="nexo-node-bg" data-v-b06bd78e></circle><circle cx="145" cy="65" r="5" fill="#00ffff" data-v-b06bd78e></circle></g></g><g class="nexo-node" style="${ssrRenderStyle({ "animation-delay": "0.5s" })}" data-v-b06bd78e><circle cx="100" cy="10" r="10" class="nexo-node-bg" data-v-b06bd78e></circle><circle cx="100" cy="10" r="5.5" fill="#00ffff" data-v-b06bd78e></circle></g><g class="nexo-node" style="${ssrRenderStyle({ "animation-delay": "0.6s" })}" data-v-b06bd78e><circle cx="180" cy="55" r="10" class="nexo-node-bg" data-v-b06bd78e></circle><circle cx="180" cy="55" r="5.5" fill="#00ff66" data-v-b06bd78e></circle></g><g class="nexo-node" style="${ssrRenderStyle({ "animation-delay": "0.7s" })}" data-v-b06bd78e><circle cx="180" cy="145" r="10" class="nexo-node-bg" data-v-b06bd78e></circle><circle cx="180" cy="145" r="5.5" fill="#00aaff" data-v-b06bd78e></circle></g><g class="nexo-node" style="${ssrRenderStyle({ "animation-delay": "0.8s" })}" data-v-b06bd78e><circle cx="100" cy="190" r="10" class="nexo-node-bg" data-v-b06bd78e></circle><circle cx="100" cy="190" r="5.5" fill="#00ff66" data-v-b06bd78e></circle></g><g class="nexo-node" style="${ssrRenderStyle({ "animation-delay": "0.9s" })}" data-v-b06bd78e><circle cx="20" cy="145" r="10" class="nexo-node-bg" data-v-b06bd78e></circle><circle cx="20" cy="145" r="5.5" fill="#ff00ff" data-v-b06bd78e></circle></g><g class="nexo-node" style="${ssrRenderStyle({ "animation-delay": "1.0s" })}" data-v-b06bd78e><circle cx="20" cy="55" r="10" class="nexo-node-bg" data-v-b06bd78e></circle><circle cx="20" cy="55" r="5.5" fill="#ff00ff" data-v-b06bd78e></circle></g></svg></div>`);
			if (props.showText) _push(`<div class="nexo-text-container" style="${ssrRenderStyle({ fontSize: `calc(${props.height} * 0.4)` })}" data-v-b06bd78e><span class="nexo-t-nexo" data-v-b06bd78e>NEXO</span><span class="nexo-t-dash" data-v-b06bd78e>-</span><span class="nexo-t-pro" data-v-b06bd78e>PRO</span></div>`);
			else _push(`<!---->`);
			_push(`</div>`);
		};
	}
});
//#endregion
//#region resources/js/Components/UI/Logo.vue
var _sfc_setup = Logo_vue_vue_type_script_setup_true_lang_default.setup;
Logo_vue_vue_type_script_setup_true_lang_default.setup = (props, ctx) => {
	const ssrContext = useSSRContext();
	(ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/UI/Logo.vue");
	return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
var Logo_default = /* @__PURE__ */ _plugin_vue_export_helper_default(Logo_vue_vue_type_script_setup_true_lang_default, [["__scopeId", "data-v-b06bd78e"]]);
//#endregion
export { Logo_default as t };

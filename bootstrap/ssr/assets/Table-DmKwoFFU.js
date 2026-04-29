import { t as __exportAll } from "./rolldown-runtime-rSIU-vHC.js";
import { t as _plugin_vue_export_helper_default } from "./_plugin-vue_export-helper-BjJHhBxR.js";
import { computed, defineComponent, mergeProps, ref, useSSRContext, watch } from "vue";
import { ssrIncludeBooleanAttr, ssrInterpolate, ssrLooseContain, ssrLooseEqual, ssrRenderAttr, ssrRenderAttrs, ssrRenderClass, ssrRenderList, ssrRenderSlot } from "vue/server-renderer";
//#region resources/js/Components/UI/Table.vue?vue&type=script&setup=true&lang.ts
var Table_vue_vue_type_script_setup_true_lang_default = /* @__PURE__ */ defineComponent({
	__name: "Table",
	__ssrInlineRender: true,
	props: {
		columns: {},
		data: {},
		initialItemsPerPage: {}
	},
	setup(__props) {
		const props = __props;
		const search = ref("");
		const sortKey = ref("");
		const sortOrder = ref(1);
		const currentPage = ref(1);
		const itemsPerPage = ref(props.initialItemsPerPage || 5);
		const processedData = computed(() => {
			let result = [...props.data];
			if (search.value) {
				const q = search.value.toLowerCase();
				result = result.filter((row) => Object.values(row).some((val) => String(val).toLowerCase().includes(q)));
			}
			if (sortKey.value) result.sort((a, b) => {
				let valA = a[sortKey.value];
				let valB = b[sortKey.value];
				if (valA < valB) return -1 * sortOrder.value;
				if (valA > valB) return 1 * sortOrder.value;
				return 0;
			});
			return result;
		});
		const totalPages = computed(() => Math.ceil(processedData.value.length / itemsPerPage.value));
		const paginatedData = computed(() => {
			const start = (currentPage.value - 1) * itemsPerPage.value;
			return processedData.value.slice(start, start + itemsPerPage.value);
		});
		watch(search, () => {
			currentPage.value = 1;
		});
		watch(itemsPerPage, () => {
			currentPage.value = 1;
		});
		return (_ctx, _push, _parent, _attrs) => {
			_push(`<div${ssrRenderAttrs(mergeProps({ class: "bg-surface border border-surface-highest rounded-2xl overflow-hidden flex flex-col w-full text-left transition-all duration-300 shadow-sm hover:shadow-md" }, _attrs))} data-v-db901dd9><div class="p-4 border-b border-surface-highest flex flex-col sm:flex-row justify-between items-center gap-4 bg-surface-low/30" data-v-db901dd9><div class="w-full sm:w-64 relative group" data-v-db901dd9><input${ssrRenderAttr("value", search.value)} type="text" aria-label="Buscar en la tabla" placeholder="Buscar registros..." class="w-full pl-10 pr-4 py-2.5 bg-surface border border-surface-highest rounded-xl text-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-secondary/30 transition-all placeholder:text-on-surface-variant/40" data-v-db901dd9><span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 opacity-30 group-focus-within:opacity-100 group-focus-within:text-secondary transition-all text-[20px]" data-v-db901dd9> search </span></div><div class="flex items-center gap-3 text-sm font-bold opacity-80" data-v-db901dd9><label for="itemsPerPage" class="sr-only" data-v-db901dd9>Filas por página</label><span class="uppercase tracking-widest text-[9px] text-on-surface-variant" data-v-db901dd9>Mostrar</span><select id="itemsPerPage" class="bg-surface border border-surface-highest rounded-lg px-3 py-1.5 focus:outline-none focus:ring-2 focus:ring-secondary/30 cursor-pointer font-bold text-xs text-on-surface transition-all hover:border-secondary/50" data-v-db901dd9><option${ssrRenderAttr("value", 5)} data-v-db901dd9${ssrIncludeBooleanAttr(Array.isArray(itemsPerPage.value) ? ssrLooseContain(itemsPerPage.value, 5) : ssrLooseEqual(itemsPerPage.value, 5)) ? " selected" : ""}>5</option><option${ssrRenderAttr("value", 10)} data-v-db901dd9${ssrIncludeBooleanAttr(Array.isArray(itemsPerPage.value) ? ssrLooseContain(itemsPerPage.value, 10) : ssrLooseEqual(itemsPerPage.value, 10)) ? " selected" : ""}>10</option><option${ssrRenderAttr("value", 25)} data-v-db901dd9${ssrIncludeBooleanAttr(Array.isArray(itemsPerPage.value) ? ssrLooseContain(itemsPerPage.value, 25) : ssrLooseEqual(itemsPerPage.value, 25)) ? " selected" : ""}>25</option><option${ssrRenderAttr("value", 50)} data-v-db901dd9${ssrIncludeBooleanAttr(Array.isArray(itemsPerPage.value) ? ssrLooseContain(itemsPerPage.value, 50) : ssrLooseEqual(itemsPerPage.value, 50)) ? " selected" : ""}>50</option></select><span class="uppercase tracking-widest text-[9px] text-on-surface-variant" data-v-db901dd9>filas</span></div></div><div class="overflow-x-auto scrollbar-thin" data-v-db901dd9><table class="w-full text-sm" data-v-db901dd9><thead class="bg-surface-low/30 border-b border-surface-highest text-left" data-v-db901dd9><tr data-v-db901dd9><!--[-->`);
			ssrRenderList(__props.columns, (col) => {
				_push(`<th class="${ssrRenderClass([{ "cursor-pointer hover:text-secondary select-none": col.sortable }, "px-6 py-5 font-bold text-xs uppercase tracking-widest text-slate-600 dark:text-slate-400 whitespace-nowrap transition-colors"])}" data-v-db901dd9><div class="flex items-center gap-2" data-v-db901dd9>${ssrInterpolate(col.label)} `);
				if (col.sortable) _push(`<span class="${ssrRenderClass([{
					"text-secondary opacity-100": sortKey.value === col.key,
					"opacity-20": sortKey.value !== col.key
				}, "flex flex-col transition-all"])}" data-v-db901dd9><span class="${ssrRenderClass([{ "opacity-30": sortKey.value === col.key && sortOrder.value === -1 }, "material-symbols-outlined text-[14px] leading-none"])}" data-v-db901dd9>arrow_drop_up</span><span class="${ssrRenderClass([{ "opacity-30": sortKey.value === col.key && sortOrder.value === 1 }, "material-symbols-outlined text-[14px] leading-none -mt-2"])}" data-v-db901dd9>arrow_drop_down</span></span>`);
				else _push(`<!---->`);
				_push(`</div></th>`);
			});
			_push(`<!--]--></tr></thead><tbody class="divide-y divide-surface-highest/50" data-v-db901dd9><!--[-->`);
			ssrRenderList(paginatedData.value, (row, index) => {
				_push(`<tr class="hover:bg-primary/5 transition-all duration-300 group border-l-2 border-transparent hover:border-primary relative" data-v-db901dd9><!--[-->`);
				ssrRenderList(__props.columns, (col) => {
					_push(`<td class="px-6 py-5 whitespace-nowrap text-sm font-medium text-on-surface/70 group-hover:text-on-surface transition-colors" data-v-db901dd9>`);
					ssrRenderSlot(_ctx.$slots, "cell-" + col.key, { row }, () => {
						_push(`${ssrInterpolate(row[col.key])}`);
					}, _push, _parent);
					_push(`</td>`);
				});
				_push(`<!--]--></tr>`);
			});
			_push(`<!--]-->`);
			if (paginatedData.value.length === 0) _push(`<tr data-v-db901dd9><td${ssrRenderAttr("colspan", __props.columns.length)} class="px-6 py-20 text-center" data-v-db901dd9><div class="flex flex-col items-center gap-4 opacity-20" data-v-db901dd9><span class="material-symbols-outlined text-6xl" data-v-db901dd9>inventory_2</span><p class="text-[10px] font-black uppercase tracking-[0.3em]" data-v-db901dd9>No se han encontrado registros</p></div></td></tr>`);
			else _push(`<!---->`);
			_push(`</tbody></table></div><div class="p-4 border-t border-surface-highest flex flex-col sm:flex-row justify-between items-center gap-4 bg-surface-low/30 text-[10px] font-black uppercase tracking-widest" data-v-db901dd9><div class="opacity-50" data-v-db901dd9> Mostrando ${ssrInterpolate(paginatedData.value.length ? (currentPage.value - 1) * itemsPerPage.value + 1 : 0)} - ${ssrInterpolate(Math.min(currentPage.value * itemsPerPage.value, processedData.value.length))} de ${ssrInterpolate(processedData.value.length)}</div><div class="flex gap-2" data-v-db901dd9><button${ssrIncludeBooleanAttr(currentPage.value === 1) ? " disabled" : ""} class="flex items-center gap-1 px-4 py-2 rounded-xl border border-surface-highest bg-surface hover:bg-surface-highest disabled:opacity-30 disabled:cursor-not-allowed transition-all active:scale-95 shadow-sm" data-v-db901dd9><span class="material-symbols-outlined text-[18px]" data-v-db901dd9>chevron_left</span> Anterior </button><button${ssrIncludeBooleanAttr(currentPage.value === totalPages.value || totalPages.value === 0) ? " disabled" : ""} class="flex items-center gap-1 px-4 py-2 rounded-xl border border-surface-highest bg-surface hover:bg-surface-highest disabled:opacity-30 disabled:cursor-not-allowed transition-all active:scale-95 shadow-sm" data-v-db901dd9> Siguiente <span class="material-symbols-outlined text-[18px]" data-v-db901dd9>chevron_right</span></button></div></div></div>`);
		};
	}
});
//#endregion
//#region resources/js/Components/UI/Table.vue
var Table_exports = /* @__PURE__ */ __exportAll({ default: () => Table_default });
var _sfc_setup = Table_vue_vue_type_script_setup_true_lang_default.setup;
Table_vue_vue_type_script_setup_true_lang_default.setup = (props, ctx) => {
	const ssrContext = useSSRContext();
	(ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/UI/Table.vue");
	return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
var Table_default = /* @__PURE__ */ _plugin_vue_export_helper_default(Table_vue_vue_type_script_setup_true_lang_default, [["__scopeId", "data-v-db901dd9"]]);
//#endregion
export { Table_exports as n, Table_default as t };

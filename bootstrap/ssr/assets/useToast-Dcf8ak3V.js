import { ref } from "vue";
//#region resources/js/Composables/useToast.ts
var toasts = ref([]);
function useToast() {
	const add = (message, variant = "primary", duration = 3e3) => {
		const id = Date.now();
		const toast = {
			id,
			message,
			variant,
			duration
		};
		toasts.value.push(toast);
		if (duration > 0) setTimeout(() => {
			remove(id);
		}, duration);
	};
	const remove = (id) => {
		const index = toasts.value.findIndex((t) => t.id === id);
		if (index !== -1) toasts.value.splice(index, 1);
	};
	return {
		toasts,
		add,
		remove
	};
}
//#endregion
export { useToast as t };

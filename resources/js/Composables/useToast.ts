import { ref, reactive } from 'vue';

export interface Toast {
    id: number;
    message: string;
    variant?: 'primary' | 'success' | 'error' | 'warning' | 'info';
    duration?: number;
}

const toasts = ref<Toast[]>([]);

export function useToast() {
    const add = (message: string, variant: Toast['variant'] = 'primary', duration = 3000) => {
        const id = Date.now();
        const toast: Toast = { id, message, variant, duration };
        toasts.value.push(toast);

        if (duration > 0) {
            setTimeout(() => {
                remove(id);
            }, duration);
        }
    };

    const remove = (id: number) => {
        const index = toasts.value.findIndex((t) => t.id === id);
        if (index !== -1) {
            toasts.value.splice(index, 1);
        }
    };

    return {
        toasts,
        add,
        remove,
    };
}

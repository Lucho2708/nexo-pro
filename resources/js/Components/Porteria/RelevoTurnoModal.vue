<script setup lang="ts">
import { ref, computed } from 'vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps<{
    show: boolean;
    guardaActual?: string;
}>();

const emit = defineEmits<{
    (e: 'close'): void;
    (e: 'relevado', data: any): void;
}>();

const pin = ref<string>('');
const form = useForm({
    pin: '',
});

const isPinComplete = computed(() => pin.value.length === 4);

const appendDigit = (digit: number) => {
    if (pin.value.length < 4 && !form.processing) {
        pin.value += digit.toString();
        form.clearErrors('pin');
    }
};

const backspace = () => {
    if (pin.value.length > 0 && !form.processing) {
        pin.value = pin.value.slice(0, -1);
    }
};

const clearPin = () => {
    if (!form.processing) {
        pin.value = '';
        form.clearErrors('pin');
    }
};

const closeModal = () => {
    if (!form.processing) {
        clearPin();
        emit('close');
    }
};

const submitRelevo = () => {
    if (!isPinComplete.value || form.processing) {
        return;
    }

    form.pin = pin.value;
    form.post(route('porteria.relevar-turno'), {
        preserveScroll: true,
        onSuccess: () => {
            clearPin();
            emit('close');
        },
        onError: () => {
            clearPin();
        },
    });
};
</script>

<template>
    <div
        v-if="show"
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/75 backdrop-blur-md transition-opacity animate-in fade-in duration-200"
        role="dialog"
        aria-modal="true"
        aria-labelledby="modal-relevo-title"
    >
        <div class="w-full max-w-sm rounded-[2.5rem] bg-surface dark:bg-[#0d121c] border border-outline-variant/30 dark:border-white/10 p-6 md:p-8 shadow-2xl space-y-6 text-center">
            <!-- Header -->
            <div class="space-y-1.5">
                <div class="w-14 h-14 rounded-2xl bg-primary/10 text-primary mx-auto flex items-center justify-center">
                    <span class="material-symbols-rounded text-3xl">badge</span>
                </div>
                <h3 id="modal-relevo-title" class="text-xl font-black uppercase tracking-tight text-on-surface dark:text-white">
                    Relevo de Guardia
                </h3>
                <p class="text-xs text-on-surface-variant font-medium">
                    Guarda saliente: <span class="font-bold text-on-surface dark:text-white">{{ guardaActual || 'En turno' }}</span>
                </p>
                <p class="text-[11px] text-primary font-bold uppercase tracking-wider">
                    Ingresa tu PIN de 4 dígitos para asumir el puesto
                </p>
            </div>

            <!-- PIN Display Visualizer -->
            <div class="flex items-center justify-center gap-4 py-2">
                <div
                    v-for="i in 4"
                    :key="i"
                    class="w-12 h-14 rounded-2xl border-2 flex items-center justify-center text-2xl font-black transition-all"
                    :class="[
                        pin.length >= i
                            ? 'border-primary bg-primary/10 text-primary scale-105 shadow-md shadow-primary/20'
                            : 'border-slate-300 dark:border-slate-700 bg-surface-container/30 text-transparent'
                    ]"
                >
                    <span v-if="pin.length >= i" class="animate-in zoom-in-50 duration-150">•</span>
                </div>
            </div>

            <!-- Error Feedback -->
            <div
                v-if="form.errors.pin"
                class="p-3 rounded-2xl bg-rose-500/10 border border-rose-500/20 text-rose-500 text-xs font-bold text-center animate-in shake"
            >
                {{ form.errors.pin }}
            </div>

            <!-- Tactile Keypad (Touch Targets >= 56px) -->
            <div class="grid grid-cols-3 gap-2.5">
                <button
                    v-for="digit in [1, 2, 3, 4, 5, 6, 7, 8, 9]"
                    :key="digit"
                    @click="appendDigit(digit)"
                    type="button"
                    :disabled="form.processing"
                    class="min-h-[56px] h-14 rounded-2xl bg-surface-container-high/80 dark:bg-white/5 hover:bg-primary/20 active:bg-primary/30 text-on-surface dark:text-white font-black text-xl transition-all border border-outline-variant/15 hover:border-primary/40 active:scale-95 shadow-sm"
                >
                    {{ digit }}
                </button>

                <!-- Clear Button -->
                <button
                    @click="clearPin"
                    type="button"
                    :disabled="form.processing"
                    class="min-h-[56px] h-14 rounded-2xl bg-surface-container/60 dark:bg-white/5 text-slate-500 hover:text-rose-500 hover:bg-rose-500/10 font-bold text-xs uppercase tracking-wider transition-all border border-outline-variant/10 active:scale-95"
                >
                    C
                </button>

                <!-- 0 Button -->
                <button
                    @click="appendDigit(0)"
                    type="button"
                    :disabled="form.processing"
                    class="min-h-[56px] h-14 rounded-2xl bg-surface-container-high/80 dark:bg-white/5 hover:bg-primary/20 active:bg-primary/30 text-on-surface dark:text-white font-black text-xl transition-all border border-outline-variant/15 hover:border-primary/40 active:scale-95 shadow-sm"
                >
                    0
                </button>

                <!-- Backspace Button -->
                <button
                    @click="backspace"
                    type="button"
                    :disabled="form.processing"
                    class="min-h-[56px] h-14 rounded-2xl bg-surface-container/60 dark:bg-white/5 text-slate-500 hover:text-amber-500 hover:bg-amber-500/10 font-bold flex items-center justify-center transition-all border border-outline-variant/10 active:scale-95"
                >
                    <span class="material-symbols-rounded text-xl">backspace</span>
                </button>
            </div>

            <!-- Footer Action Buttons -->
            <div class="flex items-center gap-3 pt-2">
                <button
                    @click="closeModal"
                    type="button"
                    :disabled="form.processing"
                    class="min-h-[52px] flex-1 py-3 px-4 rounded-2xl bg-surface-container text-on-surface-variant hover:text-on-surface font-bold text-xs uppercase tracking-wider transition-all border border-outline-variant/20"
                >
                    Cancelar
                </button>

                <button
                    @click="submitRelevo"
                    type="button"
                    :disabled="!isPinComplete || form.processing"
                    class="min-h-[52px] flex-1 py-3 px-4 rounded-2xl bg-primary text-white font-black text-xs uppercase tracking-wider shadow-lg shadow-primary/25 hover:scale-[1.02] active:scale-95 disabled:opacity-40 disabled:cursor-not-allowed transition-all flex items-center justify-center gap-2"
                >
                    <span v-if="form.processing" class="material-symbols-rounded animate-spin text-lg">progress_activity</span>
                    <span v-else>Confirmar</span>
                </button>
            </div>
        </div>
    </div>
</template>

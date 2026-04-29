<script setup lang="ts">
import { ref, onMounted, onUnmounted, computed, nextTick, watch } from 'vue';

interface Option {
    value: string | number;
    label: string;
}

interface Props {
    modelValue: string | number | null;
    options: Option[];
    label?: string;
    placeholder?: string;
    icon?: string;
    error?: string;
    state?: 'success' | 'warning' | 'error' | 'default';
    disabled?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
    placeholder: 'Seleccionar...',
    disabled: false,
    state: 'default'
});

const emit = defineEmits(['update:modelValue', 'change']);

const isOpen = ref(false);
const triggerRef = ref<HTMLElement | null>(null);
const menuRef = ref<HTMLElement | null>(null);
const dropdownStyle = ref({ top: '0px', left: '0px', width: 'auto' });

// Estado local para feedback instantáneo
const localValue = ref(props.modelValue);

// Sincronizar cuando la prop cambie desde afuera (ej: cuando responda el servidor)
watch(() => props.modelValue, (newVal) => {
    localValue.value = newVal;
});

const selectedOption = computed(() => {
    return props.options.find(opt => opt.value === localValue.value) || null;
});

const currentState = computed(() => {
    if (props.error) return 'error';
    return props.state;
});

const stateConfig = {
    error: 'border-error/50',
    success: 'border-success/50',
    warning: 'border-warning/50',
    default: 'border-outline-variant/10 focus:ring-4 focus:ring-primary/10 hover:border-primary/30'
};

const updatePosition = () => {
    if (!triggerRef.value) return;
    const rect = triggerRef.value.getBoundingClientRect();
    
    // Usamos coordenadas del viewport directamente para posicionamiento 'fixed'
    let left = rect.left;
    const minWidth = 200;
    const width = Math.max(rect.width, minWidth);
    
    // Evitar que se salga por la derecha
    if (left + width > window.innerWidth) {
        left = window.innerWidth - width - 20;
    }

    dropdownStyle.value = {
        top: `${rect.bottom + 4}px`,
        left: `${left}px`,
        width: `${width}px`
    };
};

const toggle = async () => {
    if (props.disabled) return;
    if (!isOpen.value) {
        isOpen.value = true;
        await nextTick();
        updatePosition();
        window.addEventListener('scroll', updatePosition, true);
        window.addEventListener('resize', updatePosition);
    } else {
        closeMenu();
    }
};

const closeMenu = () => {
    isOpen.value = false;
    window.removeEventListener('scroll', updatePosition, true);
    window.removeEventListener('resize', updatePosition);
};

const select = (option: Option) => {
    localValue.value = option.value; // Actualización instantánea UI
    emit('update:modelValue', option.value);
    emit('change', option.value);
    closeMenu();
};

const closeOutside = (e: MouseEvent) => {
    if (isOpen.value && triggerRef.value && !triggerRef.value.contains(e.target as Node) && menuRef.value && !menuRef.value.contains(e.target as Node)) {
        closeMenu();
    }
};

onMounted(() => document.addEventListener('mousedown', closeOutside));
onUnmounted(() => {
    document.removeEventListener('mousedown', closeOutside);
    closeMenu();
});
</script>

<template>
    <div class="space-y-1.5 w-full relative">
        <label v-if="label" class="text-[10px] font-black text-on-surface-variant/60 uppercase tracking-widest pl-1">
            {{ label }}
        </label>
        
        <div class="relative" ref="triggerRef">
            <button
                type="button"
                @click.stop="toggle"
                :disabled="disabled"
                class="w-full bg-surface-container-low border-2 rounded-2xl p-4 text-sm font-medium transition-all outline-none flex items-center justify-between group"
                :class="[
                    stateConfig[currentState],
                    disabled ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer'
                ]"
            >
                <div class="flex items-center gap-3 pointer-events-none">
                    <span v-if="icon" class="material-symbols-outlined text-on-surface-variant/40 group-hover:text-primary transition-colors">
                        {{ icon }}
                    </span>
                    <span :class="[selectedOption ? 'text-on-surface' : 'text-on-surface-variant/40', 'whitespace-nowrap truncate max-w-[180px] sm:max-w-none']">
                        {{ selectedOption ? selectedOption.label : placeholder }}
                    </span>
                </div>
                <span class="material-symbols-outlined text-on-surface-variant/40 transition-transform duration-300 pointer-events-none" :class="{'rotate-180 text-primary': isOpen}">
                    expand_more
                </span>
            </button>
        </div>

        <Teleport to="body">
            <Transition
                enter-active-class="transition duration-200 ease-out"
                enter-from-class="opacity-0 scale-95 translate-y-2"
                enter-to-class="opacity-100 scale-100 translate-y-0"
                leave-active-class="transition duration-150 ease-in"
                leave-from-class="opacity-100 scale-100 translate-y-0"
                leave-to-class="opacity-0 scale-95 translate-y-2"
            >
                <div 
                    v-if="isOpen" 
                    ref="menuRef"
                    :style="dropdownStyle"
                    @mousedown.stop
                    class="fixed z-[9999] bg-surface border border-outline-variant/20 rounded-2xl shadow-2xl overflow-hidden py-2 max-h-60 overflow-y-auto no-scrollbar"
                >
                    <div
                        v-for="option in options"
                        :key="option.value"
                        @click="select(option)"
                        class="px-5 py-3 text-sm font-bold cursor-pointer transition-colors"
                        :class="[
                            localValue === option.value 
                                ? 'bg-primary/10 text-primary' 
                                : 'text-on-surface-variant hover:bg-surface-container-low'
                        ]"
                    >
                        {{ option.label }}
                    </div>
                    <div v-if="options.length === 0" class="px-5 py-3 text-xs text-on-surface-variant/40 text-center italic">
                        No hay opciones disponibles
                    </div>
                </div>
            </Transition>
        </Teleport>
        
        <p v-if="error" class="text-[9px] font-bold text-error uppercase tracking-widest pl-1">
            {{ error }}
        </p>
    </div>
</template>

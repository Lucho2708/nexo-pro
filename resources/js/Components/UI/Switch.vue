<script setup lang="ts">
interface Props {
    modelValue: boolean;
    disabled?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
    modelValue: false,
    disabled: false,
});

const emit = defineEmits<{
    (e: 'update:modelValue', value: boolean): void;
}>();

const toggle = () => {
    if (!props.disabled) {
        emit('update:modelValue', !props.modelValue);
    }
};
</script>

<template>
    <button
        type="button"
        @click="toggle"
        class="relative inline-flex h-7 w-12 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-primary/20"
        :class="[modelValue ? 'bg-primary' : 'bg-surface-container-high', disabled ? 'opacity-50 cursor-not-allowed' : '']"
    >
        <span
            aria-hidden="true"
            class="pointer-events-none inline-block h-6 w-6 transform rounded-full bg-white shadow-lg ring-0 transition duration-200 ease-in-out"
            :class="[modelValue ? 'translate-x-[20px]' : 'translate-x-0']"
        />
    </button>
</template>

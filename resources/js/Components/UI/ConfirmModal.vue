<script setup lang="ts">
import Modal from './Modal.vue';
import Button from './Button.vue';

interface Props {
    show: boolean;
    title: string;
    message: string;
    confirmLabel?: string;
    cancelLabel?: string;
    variant?: 'primary' | 'error' | 'warning';
    loading?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
    confirmLabel: 'Confirmar',
    cancelLabel: 'Cancelar',
    variant: 'primary',
    loading: false,
});

defineEmits(['confirm', 'cancel']);
</script>

<template>
    <Modal :show="show" :title="title" @close="$emit('cancel')">
        <div class="space-y-6">
            <p class="text-sm text-on-surface-variant leading-relaxed">
                {{ message }}
            </p>
            
            <div class="flex gap-4">
                <Button 
                    variant="ghost" 
                    class="flex-1" 
                    @click="$emit('cancel')"
                    :disabled="loading"
                >
                    {{ cancelLabel }}
                </Button>
                <Button 
                    :variant="variant" 
                    class="flex-1" 
                    @click="$emit('confirm')"
                    :loading="loading"
                >
                    {{ confirmLabel }}
                </Button>
            </div>
        </div>
    </Modal>
</template>

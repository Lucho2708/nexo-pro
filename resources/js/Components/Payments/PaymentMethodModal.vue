<script setup lang="ts">
import { ref } from 'vue';
import Modal from '@/Components/UI/Modal.vue';
import Button from '@/Components/UI/Button.vue';

interface Props {
    show: boolean;
    gateways: Record<string, any>;
    amount: number;
}

const props = defineProps<Props>();
const emit = defineEmits(['close', 'select']);

const formatCurrency = (value: number) => {
    return new Intl.NumberFormat('es-CO', {
        style: 'currency',
        currency: 'COP',
        maximumFractionDigits: 0
    }).format(value);
};

const selectGateway = (key: string) => {
    emit('select', key);
};

const getIcon = (key: string) => {
    const icons: Record<string, string> = {
        wompi: 'account_balance',
        aval: 'account_balance_wallet',
        manual: 'payments'
    };
    return icons[key] || 'credit_card';
};

const getCardStyle = (key: string) => {
    const colors: Record<string, string> = {
        wompi: 'hover:border-primary/50 hover:bg-primary/5',
        aval: 'hover:border-secondary/50 hover:bg-secondary/5',
        manual: 'hover:border-emerald-500/50 hover:bg-emerald-500/5'
    };
    return colors[key] || 'hover:border-primary/50 hover:bg-primary/5';
};
</script>

<template>
    <Modal :show="show" @close="emit('close')" max-width="md">
        <div class="p-8">
            <header class="mb-8 text-center">
                <div class="w-16 h-16 bg-primary/10 text-primary rounded-2xl flex items-center justify-center mx-auto mb-4 border border-primary/10">
                    <span class="material-symbols-outlined text-3xl">shopping_cart_checkout</span>
                </div>
                <h3 class="text-2xl font-black text-on-surface tracking-tighter uppercase leading-none">Método de Pago</h3>
                <p class="text-xs text-on-surface-variant/60 font-bold uppercase tracking-widest mt-2">Vas a pagar {{ formatCurrency(amount) }}</p>
            </header>

            <div class="space-y-4">
                <div 
                    v-for="(config, key) in gateways" 
                    :key="key"
                    @click="selectGateway(key as string)"
                    class="group p-5 rounded-2xl border-2 border-outline-variant/30 cursor-pointer transition-all duration-300 flex items-center gap-5"
                    :class="getCardStyle(key as string)"
                >
                    <div class="w-12 h-12 rounded-xl bg-surface-container-high flex items-center justify-center text-primary group-hover:scale-110 transition-transform shadow-inner">
                        <span class="material-symbols-outlined text-2xl" style="font-variation-settings: 'FILL' 1;">{{ getIcon(key as string) }}</span>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-black text-on-surface tracking-tight">{{ config.label }}</p>
                        <p class="text-[10px] text-on-surface-variant/60 font-bold uppercase tracking-widest">{{ config.type === 'redirect' ? 'Redirección Bancaria' : 'Pasarela de Pago' }}</p>
                    </div>
                    <span class="material-symbols-outlined text-on-surface-variant/30 group-hover:translate-x-1 group-hover:text-primary transition-all">chevron_right</span>
                </div>
            </div>

            <footer class="mt-8 pt-6 border-t border-outline-variant/20">
                <p class="text-[10px] text-center text-on-surface-variant/40 font-medium leading-relaxed">
                    Al proceder con el pago, aceptas nuestros términos y condiciones de servicio.
                </p>
            </footer>
        </div>
    </Modal>
</template>

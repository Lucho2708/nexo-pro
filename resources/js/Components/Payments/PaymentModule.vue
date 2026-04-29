<script setup lang="ts">
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { useToast } from '@/Composables/useToast';

declare global {
    interface Window {
        WidgetCheckout: any;
    }
}

interface Props {
    unidadId: string;
    amount: number;
    email: string;
    fullName: string;
}

const props = defineProps<Props>();
const toast = useToast();
const isLoading = ref(false);

const loadWompiScript = () => {
    return new Promise((resolve, reject) => {
        if (window.WidgetCheckout) {
            resolve(window.WidgetCheckout);
            return;
        }
        const script = document.createElement('script');
        script.src = 'https://checkout.wompi.co/widget.js';
        script.onload = resolve;
        script.onerror = reject;
        document.head.appendChild(script);
    });
};

const initiatePayment = async () => {
    if (isLoading.value) return;
    
    isLoading.value = true;
    
    try {
        await loadWompiScript();

        const response = await axios.post(route('payments.initiate'), {
            unidad_id: props.unidadId,
            amount: props.amount
        });

        const data = response.data;
        
        const checkout = new (window as any).WidgetCheckout({
            currency: 'COP',
            amountInCents: props.amount * 100,
            reference: data.reference,
            publicKey: data.public_key,
            customerEmail: props.email,
            fullName: props.fullName,
            signature: {
                integrity: data.signature
            }
        });

        checkout.open((result: any) => {
            const transaction = result.transaction;
            if (transaction.status === 'APPROVED') {
                toast.add('¡Pago aprobado con éxito!', 'success');
                router.reload();
            } else {
                toast.add(`Estado del pago: ${transaction.status}`, 'info');
            }
            isLoading.value = false;
        });
    } catch (error: any) {
        console.error('Error initiating Wompi:', error);
        toast.add(error.response?.data?.message || 'No se pudo iniciar la pasarela de pago.', 'error');
        isLoading.value = false;
    }
};

defineExpose({
    pay: initiatePayment
});
</script>

<template>
    <!-- Este componente es headless, se maneja mediante su método expose -->
    <div v-if="isLoading" class="fixed inset-0 z-[200] bg-surface/60 backdrop-blur-sm flex items-center justify-center">
        <div class="flex flex-col items-center gap-4">
            <div class="w-12 h-12 border-4 border-primary border-t-transparent rounded-full animate-spin"></div>
            <p class="text-[10px] font-black uppercase tracking-[0.2em] text-primary">Conectando con Wompi...</p>
        </div>
    </div>
</template>

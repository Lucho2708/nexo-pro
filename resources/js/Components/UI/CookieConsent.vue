<script setup lang="ts">
import { ref, onMounted } from 'vue';
import Button from '@/Components/UI/Button.vue';
import Modal from '@/Components/UI/Modal.vue';
import LegalContent from '@/Components/Legal/LegalContent.vue';

const showLegalModal = ref(false);
const legalModalType = ref<'privacy' | 'cookies'>('cookies');

const openLegalModal = (type: 'privacy' | 'cookies') => {
    legalModalType.value = type;
    showLegalModal.value = true;
};

const showBanner = ref(false);

onMounted(() => {
    // Check if the user has already consented
    const consent = localStorage.getItem('nexo_cookie_consent');
    if (!consent) {
        showBanner.value = true;
    }
});

const acceptAll = () => {
    localStorage.setItem('nexo_cookie_consent', 'all');
    showBanner.value = false;
    // Here we would typically initialize analytics scripts (Google Analytics, Meta Pixel, etc.)
};

const acceptEssential = () => {
    localStorage.setItem('nexo_cookie_consent', 'essential');
    showBanner.value = false;
    // We do NOT initialize tracking scripts here.
};
</script>

<template>
    <Transition
        enter-active-class="transform transition ease-out duration-500"
        enter-from-class="translate-y-full opacity-0"
        enter-to-class="translate-y-0 opacity-100"
        leave-active-class="transform transition ease-in duration-300"
        leave-from-class="translate-y-0 opacity-100"
        leave-to-class="translate-y-full opacity-0"
    >
        <div v-if="showBanner" class="fixed bottom-0 left-0 right-0 z-[100] p-4 pointer-events-none">
            <div class="max-w-4xl mx-auto bg-surface/95 backdrop-blur-md border border-outline-variant/30 shadow-2xl rounded-3xl p-6 pointer-events-auto flex flex-col md:flex-row items-center gap-6">
                <!-- Icon & Text -->
                <div class="flex-1 space-y-2">
                    <div class="flex items-center gap-2 text-primary">
                        <span class="material-symbols-outlined">cookie</span>
                        <h3 class="font-black tracking-tight uppercase">Tu Privacidad</h3>
                    </div>
                    <p class="text-xs text-on-surface-variant font-medium leading-relaxed">
                        NEXO-PRO utiliza cookies técnicas y analíticas conforme a las directrices de la SIC (Colombia) y la Ley de Habeas Data.
                        <button @click="openLegalModal('cookies')" class="text-primary font-bold hover:underline ml-1">Ver Política de Cookies.</button> |
                        <button @click="openLegalModal('privacy')" class="text-primary font-bold hover:underline ml-1">Política de Privacidad.</button>
                    </p>
                </div>

                <!-- Actions -->
                <div class="flex flex-col sm:flex-row items-center gap-3 w-full md:w-auto shrink-0">
                    <Button variant="ghost" size="sm" class="w-full sm:w-auto text-xs" @click="acceptEssential">
                        Solo Esenciales
                    </Button>
                    <Button variant="primary" size="sm" class="w-full sm:w-auto text-xs shadow-lg shadow-primary/20" @click="acceptAll">
                        Aceptar Todas
                    </Button>
                </div>
            </div>
        </div>
    </Transition>

    <!-- Legal Modal for Banner -->
    <Modal v-if="showLegalModal" @close="showLegalModal = false" maxWidth="4xl">
        <div class="px-6 py-6 md:px-8 md:py-8 border-b border-outline-variant/10 flex justify-between items-center sticky top-0 bg-surface/80 backdrop-blur-xl z-[150]">
            <h3 class="text-sm font-black text-on-surface uppercase tracking-widest">
                Información Legal
            </h3>
            <button @click="showLegalModal = false" class="p-2 rounded-xl text-on-surface-variant hover:bg-surface-container-high transition-colors">
                <span class="material-symbols-outlined text-lg">close</span>
            </button>
        </div>
        
        <div class="p-6 md:p-8 max-h-[60vh] overflow-y-auto custom-scrollbar bg-surface text-left">
            <LegalContent :type="legalModalType" />
        </div>
        
        <div class="px-6 py-4 md:px-8 bg-surface-container-low border-t border-outline-variant/10 flex justify-end">
            <Button variant="ghost" @click="showLegalModal = false">Entendido</Button>
        </div>
    </Modal>
</template>

<style scoped>
.material-symbols-outlined {
    font-variation-settings: 'opsz' 24;
}
</style>

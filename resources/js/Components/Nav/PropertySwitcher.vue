<script setup lang="ts">
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { usePage, router } from '@inertiajs/vue3';

const page = usePage<any>();
const user = computed(() => page.props.auth.user);
const currentCopropiedad = computed(() => user.value?.current_copropiedad);
const availableCopropiedades = computed(() => user.value?.available_copropiedades || []);

const isOpen = ref(false);
const container = ref<HTMLElement | null>(null);

const switchTenant = (id: string) => {
    router.post(route('tenant.switch', id), {}, {
        onSuccess: () => isOpen.value = false
    });
};

const otherCopropiedades = computed(() => {
    return availableCopropiedades.value.filter((c: any) => c.id !== currentCopropiedad.value?.id);
});

const handleClickOutside = (e: MouseEvent) => {
    if (container.value && !container.value.contains(e.target as Node)) {
        isOpen.value = false;
    }
};

onMounted(() => {
    document.addEventListener('click', handleClickOutside);
});

onUnmounted(() => {
    document.removeEventListener('click', handleClickOutside);
});
</script>

<template>
    <div v-if="currentCopropiedad || otherCopropiedades.length > 0" ref="container" class="relative">
        <!-- Trigger Button -->
        <button 
            @click="isOpen = !isOpen"
            class="flex items-center gap-4 px-6 py-3 rounded-2xl bg-emerald-500/5 hover:bg-emerald-500/10 border border-emerald-500/20 transition-all group overflow-hidden relative"
        >
            <div class="absolute inset-0 bg-emerald-500/5 translate-x-full group-hover:translate-x-0 transition-transform duration-500"></div>
            
            <div class="w-8 h-8 rounded-xl bg-emerald-500/20 flex items-center justify-center text-emerald-500 shrink-0 relative z-10">
                <span class="material-symbols-rounded text-lg">apartment</span>
            </div>
            
            <div class="text-left relative z-10 hidden sm:block">
                <div class="flex items-center gap-2 mb-1">
                    <div class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse shadow-[0_0_8px_rgba(16,185,129,0.8)]"></div>
                    <p class="text-[8px] font-black text-emerald-500 uppercase tracking-[0.2em] leading-none">Activo</p>
                </div>
                <h3 class="text-[11px] font-black text-on-surface dark:text-white uppercase tracking-tighter italic flex items-center gap-2">
                    {{ currentCopropiedad?.nombre || 'Seleccionar Conjunto' }}
                </h3>
            </div>
            
            <span class="material-symbols-rounded text-emerald-500 transition-transform duration-300 relative z-10 ml-1" :class="{'rotate-180': isOpen}">expand_more</span>
        </button>

        <!-- Dropdown Menu -->
        <Transition
            enter-active-class="transition ease-out duration-200"
            enter-from-class="opacity-0 translate-y-4 scale-95"
            enter-to-class="opacity-100 translate-y-0 scale-100"
            leave-active-class="transition ease-in duration-100"
            leave-to-class="opacity-0 translate-y-4 scale-95"
        >
            <div 
                v-if="isOpen" 
                class="absolute top-full left-0 mt-4 w-72 bg-white dark:bg-[#0b0e14] border border-outline-variant/10 dark:border-white/5 rounded-[2.5rem] p-4 shadow-3xl z-[100] backdrop-blur-3xl"
            >
                <div class="px-5 py-4 border-b border-outline-variant/5 dark:border-white/5 mb-4">
                    <p class="text-[9px] font-black text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.3em] italic">Cambiar de Contexto</p>
                    <h4 class="text-xs font-black text-on-surface dark:text-white uppercase tracking-tighter mt-1">Sus Copropiedades</h4>
                </div>

                <div class="space-y-2 max-h-64 overflow-y-auto no-scrollbar">
                    <button 
                        v-for="copro in availableCopropiedades" 
                        :key="copro.id"
                        @click="switchTenant(copro.id)"
                        class="w-full flex items-center gap-4 p-4 rounded-2xl transition-all group border border-transparent relative overflow-hidden"
                        :class="[
                            copro.id === currentCopropiedad?.id 
                                ? 'bg-emerald-500/10 border-emerald-500/20 cursor-default' 
                                : 'bg-rose-500/5 hover:bg-rose-500/10 border-rose-500/10 hover:border-rose-500/20'
                        ]"
                    >
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center transition-all" :class="copro.id === currentCopropiedad?.id ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-500/20' : 'bg-rose-500/20 text-rose-500 group-hover:bg-rose-500 group-hover:text-white'">
                            <span class="material-symbols-rounded text-lg">corporate_fare</span>
                        </div>
                        <div class="text-left relative z-10 flex-1">
                            <div class="flex items-center gap-2 mb-0.5">
                                <p class="text-[10px] font-black uppercase tracking-tighter" :class="copro.id === currentCopropiedad?.id ? 'text-emerald-500' : 'text-on-surface dark:text-white'">
                                    {{ copro.nombre }}
                                </p>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <div class="w-1 h-1 rounded-full" :class="copro.id === currentCopropiedad?.id ? 'bg-emerald-500 animate-pulse' : 'bg-rose-500'"></div>
                                <p class="text-[8px] font-bold uppercase tracking-widest italic" :class="copro.id === currentCopropiedad?.id ? 'text-emerald-500' : 'text-rose-500/70'">
                                    {{ copro.id === currentCopropiedad?.id ? 'ACTUALMENTE GESTIONANDO' : 'INACTIVO' }}
                                </p>
                            </div>
                        </div>
                        <div v-if="copro.id === currentCopropiedad?.id" class="ml-auto relative z-10">
                            <span class="material-symbols-rounded text-emerald-500 text-sm">check_circle</span>
                        </div>
                        <div v-else class="ml-auto relative z-10 opacity-50 group-hover:opacity-100 transition-opacity">
                            <span class="material-symbols-rounded text-rose-500 text-sm">arrow_forward</span>
                        </div>
                    </button>
                </div>

                <div v-if="user.role === 'super_admin'" class="mt-4 pt-4 border-t border-outline-variant/5 dark:border-white/5">
                    <p class="text-[8px] font-bold text-error/60 uppercase tracking-widest text-center px-4 italic leading-relaxed">
                        * Como Super Admin, los cambios de contexto afectan la auditoría global.
                    </p>
                </div>
            </div>
        </Transition>
    </div>
</template>

<style scoped>
.no-scrollbar::-webkit-scrollbar { display: none; }
</style>

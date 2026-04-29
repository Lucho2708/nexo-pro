<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import Logo from '@/Components/UI/Logo.vue';
import ThemeToggle from '@/Components/UI/ThemeToggle.vue';
</script>

<template>
    <header class="fixed top-0 w-full z-50 transition-all duration-500 bg-white/70 dark:bg-[#0b0e14]/70 backdrop-blur-2xl border-b border-outline-variant/5 dark:border-white/5">
        <div class="flex justify-between items-center w-full px-8 py-5 max-w-7xl mx-auto">
            <div class="flex items-center gap-12">
                <!-- Brand Identity -->
                <Link href="/" class="hover:opacity-80 transition-all active:scale-95">
                    <Logo width="48px" height="48px" />
                </Link>
                
                <!-- Tactical Navigation -->
                <nav class="hidden lg:flex items-center space-x-10">
                    <a class="text-[10px] font-black uppercase tracking-[0.3em] text-on-surface-variant/60 hover:text-primary transition-all italic underline-offset-8 hover:underline" href="/#soluciones">Soluciones</a>
                    <a class="text-[10px] font-black uppercase tracking-[0.3em] text-on-surface-variant/60 hover:text-primary transition-all italic underline-offset-8 hover:underline" href="/#roles">Roles</a>
                    <a class="text-[10px] font-black uppercase tracking-[0.3em] text-on-surface-variant/60 hover:text-primary transition-all italic underline-offset-8 hover:underline" href="/#precios">Precios</a>
                    <a class="text-[10px] font-black uppercase tracking-[0.3em] text-on-surface-variant/60 hover:text-primary transition-all italic underline-offset-8 hover:underline" href="/#contacto">Contacto</a>
                </nav>
            </div>
            
            <div class="flex items-center gap-8">
                <ThemeToggle class="hidden sm:flex" />
                
                <!-- Action Buttons -->
                <div class="flex items-center gap-4">
                    <template v-if="$page.props.auth?.user">
                        <Link 
                            :href="route('dashboard')" 
                            class="bg-secondary text-white px-8 py-3 rounded-2xl text-[10px] font-black uppercase tracking-widest italic hover:scale-105 transition-all shadow-lg shadow-secondary/20"
                        >
                            Mando Central
                        </Link>
                    </template>
                    <Link 
                        v-else 
                        :href="route('login')" 
                        class="bg-primary text-white px-8 py-3 rounded-2xl text-[10px] font-black uppercase tracking-widest italic hover:scale-105 transition-all shadow-lg shadow-primary/20"
                    >
                        Portal Clientes
                    </Link>
                </div>
            </div>
        </div>
    </header>
</template>

<style scoped>
.material-symbols-outlined {
    font-variation-settings: 'opsz' 24;
}
</style>

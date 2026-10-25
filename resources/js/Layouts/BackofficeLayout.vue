<script setup lang="ts">
import { computed } from 'vue';
import { Link, usePage, router } from '@inertiajs/vue3';
import ThemeToggle from '@/Components/UI/ThemeToggle.vue';
import Logo from '@/Components/UI/Logo.vue';

const page = usePage<any>();
const user = computed(() => page.props.auth?.user);

const navItems = [
    { name: 'Embudo de Ventas (CRM)', icon: 'view_kanban', route: 'backoffice.leads.index' },
];

const logout = () => {
    router.post(route('logout'));
};
</script>

<template>
    <div class="min-h-screen bg-surface dark:bg-[#07090E] flex flex-col text-on-surface font-sans antialiased">
        <header class="sticky top-0 z-40 bg-surface/90 dark:bg-[#0b0e14]/90 backdrop-blur-xl border-b border-outline-variant/15 dark:border-white/5 px-6 md:px-10 py-4 flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-6">
                <Link :href="route('backoffice.leads.index')" class="flex items-center gap-3">
                    <Logo width="38px" height="38px" :show-text="false" />
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-indigo-500 animate-pulse"></span>
                            <span class="text-[9px] font-black uppercase tracking-[0.25em] text-indigo-500">Backoffice Comercial</span>
                        </div>
                        <h1 class="text-xs md:text-sm font-black uppercase tracking-tight text-on-surface dark:text-white">
                            CRM & Crecimiento PropTech
                        </h1>
                    </div>
                </Link>
            </div>

            <nav class="hidden md:flex items-center gap-3">
                <Link 
                    v-for="item in navItems" 
                    :key="item.name"
                    :href="route(item.route)"
                    class="px-5 py-2.5 rounded-2xl flex items-center gap-2 text-xs font-black uppercase tracking-wider bg-primary/10 text-primary border border-primary/20"
                >
                    <span class="material-symbols-rounded text-lg">{{ item.icon }}</span>
                    <span>{{ item.name }}</span>
                </Link>
            </nav>

            <div class="flex items-center gap-4">
                <ThemeToggle />

                <div class="flex items-center gap-3 pl-4 border-l border-outline-variant/20">
                    <div class="hidden sm:block text-right">
                        <p class="text-xs font-black text-on-surface dark:text-white uppercase leading-none">{{ user?.name }}</p>
                        <p class="text-[9px] font-black text-indigo-500 uppercase tracking-widest mt-1">Ejecutivo Comercial</p>
                    </div>

                    <button 
                        @click="logout"
                        type="button"
                        class="min-h-[44px] min-w-[44px] p-2.5 rounded-2xl bg-surface-container hover:bg-error/10 text-on-surface-variant hover:text-error transition-all flex items-center justify-center border border-outline-variant/10"
                    >
                        <span class="material-symbols-rounded text-lg">logout</span>
                    </button>
                </div>
            </div>
        </header>

        <main class="flex-1 w-full max-w-7xl mx-auto p-4 md:p-8">
            <slot />
        </main>
    </div>
</template>

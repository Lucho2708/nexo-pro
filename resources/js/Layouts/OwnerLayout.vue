<script setup lang="ts">
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { Link, usePage, router } from '@inertiajs/vue3';
import ThemeToggle from '@/Components/UI/ThemeToggle.vue';
import Logo from '@/Components/UI/Logo.vue';
import Dropdown from '@/Components/UI/Dropdown.vue';

const page = usePage();
// @ts-ignore
const user = computed(() => page.props.auth?.user);
// @ts-ignore
const notifications = computed(() => page.props.notifications || []);
const unreadCount = computed(() => notifications.value.length);

const isSidebarExpanded = ref(true);
const isMobileMenuOpen = ref(false);
const isNotificationsOpen = ref(false);
const showUserMenu = ref(false);
const windowWidth = ref(window.innerWidth);

const updateWidth = () => {
    windowWidth.value = window.innerWidth;
    if (windowWidth.value >= 1024) isSidebarExpanded.value = true;
    else if (windowWidth.value >= 768) isSidebarExpanded.value = false;
    else isMobileMenuOpen.value = false;
};

onMounted(() => {
    window.addEventListener('resize', updateWidth);
    updateWidth();
});

onUnmounted(() => window.removeEventListener('resize', updateWidth));

router.on('navigate', () => {
    isMobileMenuOpen.value = false;
});

const navItems = [
    { name: 'Inicio',    icon: 'home',            href: route('owner.dashboard'),   active: route().current('owner.dashboard') },
    { name: 'Pagos',     icon: 'receipt_long',    href: route('owner.payments'),    active: route().current('owner.payments') },
    { name: 'Reservas',  icon: 'event_available', href: route('reservas.index'),    active: route().current('reservas.index') },
    { name: 'PQRS',      icon: 'chat_bubble',     href: route('pqrs.index'),        active: route().current('pqrs.index') },
    { name: 'Perfil',    icon: 'person',          href: route('profile.edit'),      active: route().current('profile.edit') },
];

const mainMobileItems = computed(() => navItems.slice(0, 3));
const moreMobileItems = computed(() => navItems.slice(3));

const logout = () => {
    router.post(route('logout'));
};
</script>

<template>
    <div class="min-h-screen bg-surface flex text-on-surface font-sans antialiased">
        <!-- Desktop Sidebar -->
        <aside 
            class="hidden md:flex fixed inset-y-0 left-0 bg-surface-container-lowest border-r border-outline-variant/30 flex-col transition-all duration-300 z-40"
            :class="[isSidebarExpanded ? 'w-64' : 'w-20']"
        >
            <div class="p-6 flex justify-center items-center h-20 border-b border-outline-variant/10">
                <Link :href="route('owner.dashboard')" class="flex items-center overflow-hidden transition-all whitespace-nowrap">
                    <Logo :width="isSidebarExpanded ? '48px' : '32px'" :height="isSidebarExpanded ? '48px' : '32px'" :show-text="isSidebarExpanded" />
                </Link>
            </div>

            <nav class="flex-1 py-6 px-3 space-y-1 overflow-y-auto no-scrollbar">
                <Link 
                    v-for="item in navItems" 
                    :key="item.name" 
                    :href="item.href"
                    class="flex items-center py-3 px-3 mx-1 rounded-xl transition-all group overflow-hidden border border-transparent"
                    :class="[item.active ? 'bg-primary/10 text-primary opacity-100 shadow-sm border-primary/10' : 'text-on-surface-variant hover:bg-surface-container-high']"
                    :title="item.name"
                >
                    <span class="material-symbols-rounded shrink-0" :class="{ 'text-primary': item.active }">{{ item.icon }}</span>
                    <span class="ml-3 text-sm font-semibold tracking-wide transition-opacity duration-300 whitespace-nowrap" :class="isSidebarExpanded ? 'opacity-100' : 'opacity-0 hidden'">{{ item.name }}</span>
                </Link>
            </nav>

            <div class="p-4 border-t border-outline-variant/20 space-y-2">
                <button 
                    @click="isSidebarExpanded = !isSidebarExpanded"
                    class="w-full flex justify-center items-center p-2 rounded-xl text-on-surface-variant hover:bg-surface-container-high transition-colors"
                >
                    <span class="material-symbols-rounded">{{ isSidebarExpanded ? 'keyboard_double_arrow_left' : 'keyboard_double_arrow_right' }}</span>
                </button>
            </div>
        </aside>

        <!-- Main Content Area -->
        <main 
            class="flex-1 flex flex-col transition-all duration-300 min-h-screen"
            :class="[windowWidth >= 768 ? (isSidebarExpanded ? 'ml-64' : 'ml-20') : 'ml-0 pb-16']"
        >
            <div class="brand-accent-line sticky top-0 z-[50]"></div>

            <header class="sticky top-[3px] z-30 bg-surface/80 backdrop-blur-md border-b border-outline-variant/30 px-4 md:px-8 py-4 flex justify-between items-center transition-shadow">
                <div class="flex items-center gap-4">
                    <h2 class="text-xl font-extrabold text-on-surface tracking-tight truncate">Portal Propietario</h2>
                </div>

                <div class="flex items-center gap-3 md:gap-5">
                    <ThemeToggle />
                    
                    <div v-if="page.props.auth.user.available_copropiedades?.length > 1" class="hidden lg:block border-l border-outline-variant/30 pl-4">
                        <Dropdown
                            label="Cambiar Conjunto"
                            variant="ghost"
                            size="sm"
                            icon="apartment"
                            :items="page.props.auth.user.available_copropiedades.map(prop => ({
                                label: prop.nombre,
                                icon: page.props.auth.user.current_copropiedad?.id === prop.id ? 'check_circle' : 'home',
                                action: () => router.post(route('tenant.switch', prop.id))
                            }))"
                        />
                    </div>

                    <div class="relative">
                        <button @click="isNotificationsOpen = !isNotificationsOpen" class="p-2 rounded-full hover:bg-surface-container-high relative text-on-surface-variant">
                            <span class="material-symbols-rounded">notifications</span>
                            <span v-if="unreadCount > 0" class="absolute top-1 right-1 w-2.5 h-2.5 bg-error rounded-full ring-2 ring-surface"></span>
                        </button>
                    </div>

                    <div class="relative flex items-center border-l border-outline-variant/30 pl-3 md:pl-5">
                        <button @click="showUserMenu = !showUserMenu" class="w-10 h-10 rounded-full border-2 border-primary/20 overflow-hidden hover:border-primary transition-all">
                            <img :src="`https://ui-avatars.com/api/?name=${encodeURIComponent(user?.name || 'U')}&background=random&color=fff`" class="w-full h-full object-cover" />
                        </button>
                        <Transition enter-active-class="transition ease-out duration-150" enter-from-class="opacity-0 scale-95" enter-to-class="opacity-100 scale-100" leave-active-class="transition ease-in duration-100" leave-to-class="opacity-0 scale-95">
                            <div v-if="showUserMenu" class="absolute top-full right-0 mt-3 w-56 bg-surface-container-lowest rounded-2xl shadow-premium border border-outline-variant/30 py-2 z-50">
                                <Link :href="route('profile.edit')" class="w-full px-4 py-2 hover:bg-surface-container text-sm flex gap-3 text-on-surface-variant hover:text-primary"><span class="material-symbols-rounded">person</span> Perfil</Link>
                                <button @click="logout" class="w-full px-4 py-2 hover:bg-error/10 text-sm flex gap-3 text-error border-t border-outline-variant/10 pt-3"><span class="material-symbols-rounded">logout</span> Cerrar sesión</button>
                            </div>
                        </Transition>
                    </div>
                </div>
            </header>

            <div class="px-4 py-6 md:p-8 max-w-[1400px] w-full mx-auto flex-1">
                <Transition name="page" mode="out-in" appear>
                    <div :key="page.component"><slot /></div>
                </Transition>
            </div>
        </main>

        <!-- Mobile Bottom Navigation -->
        <nav v-if="windowWidth < 768" class="fixed bottom-0 inset-x-0 bg-surface-container border-t border-outline-variant/30 flex justify-around items-center h-16 z-50 pb-safe shadow-[0_-4px_20px_rgba(0,0,0,0.05)] dark:shadow-[0_-4px_20px_rgba(0,0,0,0.2)]">
            <Link 
                v-for="item in mainMobileItems" 
                :key="item.name" 
                :href="item.href"
                class="flex flex-col items-center justify-center w-full h-full text-on-surface-variant hover:text-primary transition-colors"
                :class="{ 'text-primary': item.active }"
            >
                <span class="material-symbols-rounded text-[24px]" :class="{ 'fill-current': item.active }">{{ item.icon }}</span>
                <span class="text-[10px] font-bold mt-1 tracking-wide">{{ item.name }}</span>
            </Link>

            <button 
                @click="isMobileMenuOpen = true"
                class="flex flex-col items-center justify-center w-full h-full text-on-surface-variant hover:text-primary transition-colors"
            >
                <span class="material-symbols-rounded text-[24px]">menu</span>
                <span class="text-[10px] font-bold mt-1 tracking-wide">Más</span>
            </button>
        </nav>

        <!-- Mobile Hamburger Drawer -->
        <Transition enter-active-class="transition-transform duration-300 ease-out" enter-from-class="translate-y-full" enter-to-class="translate-y-0" leave-active-class="transition-transform duration-200 ease-in" leave-from-class="translate-y-0" leave-to-class="translate-y-full">
            <div v-if="isMobileMenuOpen && windowWidth < 768" class="fixed inset-0 z-[60] flex flex-col bg-surface">
                <div class="flex items-center justify-between p-6 border-b border-outline-variant/20">
                    <Logo width="40px" height="40px" :show-text="true" />
                    <button @click="isMobileMenuOpen = false" class="p-2 rounded-full bg-surface-container-high text-on-surface">
                        <span class="material-symbols-rounded">close</span>
                    </button>
                </div>
                <div class="flex-1 overflow-y-auto p-4 space-y-2">
                    <p class="text-xs font-black text-on-surface-variant uppercase tracking-widest pl-2 mb-2">Más Opciones</p>
                    <Link 
                        v-for="item in moreMobileItems" 
                        :key="item.name" 
                        :href="item.href"
                        class="flex items-center p-4 bg-surface-container rounded-2xl hover:bg-surface-container-high transition-colors"
                        :class="{ 'border border-primary/30 bg-primary/5 text-primary': item.active }"
                    >
                        <span class="material-symbols-rounded mr-4">{{ item.icon }}</span>
                        <span class="font-bold">{{ item.name }}</span>
                    </Link>
                </div>
            </div>
        </Transition>
    </div>
</template>

<style>
.pb-safe { padding-bottom: env(safe-area-inset-bottom); }
.page-enter-active, .page-leave-active { transition: opacity 0.2s ease, transform 0.2s ease; }
.page-enter-from, .page-leave-to { opacity: 0; transform: translateY(5px); }
</style>

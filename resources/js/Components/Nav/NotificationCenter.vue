<script setup lang="ts">
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { usePage } from '@inertiajs/vue3';
import axios from 'axios';

const page = usePage();
const announcements = computed(() => (page.props.announcements as any[]) || []);
const unreadCount = computed(() => announcements.value.filter(a => !a.is_read).length);

const isOpen = ref(false);
const containerRef = ref<HTMLElement | null>(null);

const toggleDropdown = () => {
    isOpen.value = !isOpen.value;
};

const closeDropdown = () => {
    isOpen.value = false;
};

const handleClickOutside = (e: MouseEvent) => {
    if (containerRef.value && !containerRef.value.contains(e.target as Node)) {
        isOpen.value = false;
    }
};

onMounted(() => {
    document.addEventListener('click', handleClickOutside);
});

onUnmounted(() => {
    document.removeEventListener('click', handleClickOutside);
});

const markAsRead = async (announcement: any) => {
    if (announcement.is_read) return;
    try {
        await axios.post(route('notifications.mark-as-read', announcement.id));
        announcement.is_read = true;
    } catch (e) {
        console.error(e);
    }
};

const formatDate = (date: string) => {
    return new Date(date).toLocaleDateString('es-CO', { day: 'numeric', month: 'short' });
};
</script>

<template>
    <div ref="containerRef" class="relative">
        <!-- Bell Trigger -->
        <button 
            @click="toggleDropdown"
            class="w-10 h-10 rounded-2xl flex items-center justify-center transition-all bg-surface hover:bg-surface-container border border-outline-variant/10 relative cursor-pointer"
            :class="{ 'ring-2 ring-primary bg-primary/5': isOpen }"
            aria-label="Notificaciones"
        >
            <span class="material-symbols-rounded text-on-surface-variant">notifications</span>
            <span v-if="unreadCount > 0" class="absolute -top-1 -right-1 w-5 h-5 bg-error text-white text-[10px] font-black rounded-full flex items-center justify-center border-2 border-surface animate-bounce">
                {{ unreadCount }}
            </span>
        </button>

        <!-- Backdrop on mobile -->
        <Transition
            enter-active-class="transition-opacity duration-200 ease-out"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition-opacity duration-150 ease-in"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div 
                v-if="isOpen" 
                @click="closeDropdown" 
                class="fixed inset-0 z-40 bg-slate-950/50 backdrop-blur-sm sm:hidden"
                aria-hidden="true"
            />
        </Transition>

        <!-- Dropdown Menu -->
        <Transition name="slide-up">
            <div 
                v-if="isOpen" 
                class="fixed sm:absolute inset-x-3 sm:inset-x-auto sm:right-0 top-16 sm:top-full mt-2 sm:mt-3 sm:w-80 max-w-[calc(100vw-1.5rem)] bg-surface dark:bg-[#0b0e14] rounded-3xl shadow-2xl border border-outline-variant/20 dark:border-white/10 overflow-hidden z-50"
            >
                <div class="p-4 bg-surface-container/30 border-b border-outline-variant/10 flex justify-between items-center">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-rounded text-base text-primary">notifications</span>
                        <h4 class="text-xs font-black uppercase tracking-widest text-primary">Notificaciones</h4>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="text-[10px] font-bold text-on-surface-variant/50">{{ unreadCount }} pendientes</span>
                        <button 
                            @click="closeDropdown" 
                            class="sm:hidden p-1 rounded-xl text-on-surface-variant/60 hover:text-on-surface hover:bg-surface-container-high cursor-pointer"
                            aria-label="Cerrar"
                        >
                            <span class="material-symbols-rounded text-base">close</span>
                        </button>
                    </div>
                </div>

                <div class="max-h-80 sm:max-h-96 overflow-y-auto no-scrollbar">
                    <div v-if="announcements.length === 0" class="p-10 sm:p-12 text-center">
                        <span class="material-symbols-rounded text-4xl text-on-surface-variant/20 mb-2">notifications_off</span>
                        <p class="text-[10px] font-black uppercase tracking-widest text-on-surface-variant/40">Sin avisos recientes</p>
                    </div>

                    <div 
                        v-for="ann in announcements" 
                        :key="ann.id"
                        class="p-4 border-b border-outline-variant/5 hover:bg-primary/5 transition-colors cursor-pointer group"
                        :class="{ 'bg-surface-container-low/30': !ann.is_read }"
                        @click="markAsRead(ann)"
                    >
                        <div class="flex gap-3">
                            <div 
                                class="w-8 h-8 rounded-xl flex items-center justify-center flex-shrink-0"
                                :class="{
                                    'bg-primary/10 text-primary': ann.type === 'info',
                                    'bg-warning/10 text-warning': ann.type === 'warning',
                                    'bg-error/10 text-error': ann.type === 'error' || ann.type === 'danger',
                                }"
                            >
                                <span class="material-symbols-rounded text-lg">
                                    {{ ann.type === 'error' || ann.type === 'danger' ? 'report' : ann.type === 'warning' ? 'warning' : 'info' }}
                                </span>
                            </div>
                            <div class="flex-1 overflow-hidden">
                                <div class="flex justify-between items-start mb-1">
                                    <p class="text-[10px] font-black uppercase tracking-tighter truncate pr-2" :class="ann.is_read ? 'text-on-surface-variant/50' : 'text-on-surface'">
                                        {{ ann.title }}
                                    </p>
                                    <span class="text-[9px] font-bold text-on-surface-variant/40">{{ formatDate(ann.created_at) }}</span>
                                </div>
                                <p class="text-xs font-medium text-on-surface-variant line-clamp-2 leading-tight">
                                    {{ ann.message }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-3 bg-surface-container/30 text-center">
                    <button @click="closeDropdown" class="text-[9px] font-black uppercase tracking-widest text-on-surface-variant/60 hover:text-primary transition-colors cursor-pointer">
                        Cerrar Panel
                    </button>
                </div>
            </div>
        </Transition>
    </div>
</template>

<style scoped>
.slide-up-enter-active, .slide-up-leave-active { transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1); }
.slide-up-enter-from { opacity: 0; transform: translateY(8px) scale(0.98); }
.slide-up-leave-to { opacity: 0; transform: translateY(8px) scale(0.98); }
</style>

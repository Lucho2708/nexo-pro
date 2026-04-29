<script setup lang="ts">
import { ref, computed } from 'vue';
import { usePage, Link } from '@inertiajs/vue3';
import axios from 'axios';

const page = usePage();
const announcements = computed(() => page.props.announcements as any[] || []);
const unreadCount = computed(() => announcements.value.filter(a => !a.is_read).length);

const isOpen = ref(false);

const toggleDropdown = () => {
    isOpen.value = !isOpen.value;
};

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
    <div class="relative">
        <!-- Bell Trigger -->
        <button 
            @click="toggleDropdown"
            class="w-10 h-10 rounded-2xl flex items-center justify-center transition-all bg-surface hover:bg-surface-container border border-outline-variant/10 relative"
            :class="{ 'ring-2 ring-primary bg-primary/5': isOpen }"
        >
            <span class="material-symbols-rounded text-on-surface-variant">notifications</span>
            <span v-if="unreadCount > 0" class="absolute -top-1 -right-1 w-5 h-5 bg-error text-white text-[10px] font-black rounded-full flex items-center justify-center border-2 border-surface animate-bounce">
                {{ unreadCount }}
            </span>
        </button>

        <!-- Dropdown Menu -->
        <Transition name="slide-up">
            <div v-if="isOpen" class="absolute right-0 mt-3 w-80 bg-surface rounded-3xl shadow-2xl border border-outline-variant/20 overflow-hidden z-50">
                <div class="p-4 bg-surface-container/30 border-b border-outline-variant/10 flex justify-between items-center">
                    <h4 class="text-xs font-black uppercase tracking-widest text-primary">Notificaciones</h4>
                    <span class="text-[10px] font-bold text-on-surface-variant/50">{{ unreadCount }} pendientes</span>
                </div>

                <div class="max-h-96 overflow-y-auto no-scrollbar">
                    <div v-if="announcements.length === 0" class="p-12 text-center">
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
                    <button @click="isOpen = false" class="text-[9px] font-black uppercase tracking-widest text-on-surface-variant/60 hover:text-primary transition-colors">
                        Cerrar Panel
                    </button>
                </div>
            </div>
        </Transition>
    </div>
</template>

<style scoped>
.slide-up-enter-active, .slide-up-leave-active { transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1); }
.slide-up-enter-from { opacity: 0; transform: translateY(10px) scale(0.95); }
.slide-up-leave-to { opacity: 0; transform: translateY(10px) scale(0.95); }
</style>

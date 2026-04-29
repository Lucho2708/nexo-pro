<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { usePage, router } from '@inertiajs/vue3';
import axios from 'axios';

const page = usePage();
const announcements = ref(page.props.announcements as any[] || []);
const showModal = ref(false);
const activeIndex = ref(0);

onMounted(() => {
    // Solo mostrar el modal si hay anuncios no leídos
    const unread = announcements.value.filter(a => !a.is_read);
    if (unread.length > 0) {
        showModal.value = true;
    }
});

const markAsRead = async (announcement: any) => {
    try {
        await axios.post(route('notifications.mark-as-read', announcement.id));
        announcement.is_read = true;
        
        // Si hay más anuncios, pasar al siguiente, si no cerrar
        const unread = announcements.value.filter(a => !a.is_read);
        if (unread.length > 0) {
            activeIndex.value = announcements.value.findIndex(a => !a.is_read);
        } else {
            showModal.value = false;
        }
    } catch (error) {
        console.error('Error marking as read:', error);
    }
};

const next = () => {
    if (activeIndex.value < announcements.value.length - 1) {
        activeIndex.value++;
    }
};

const prev = () => {
    if (activeIndex.value > 0) {
        activeIndex.value--;
    }
};

const currentAnnouncement = () => announcements.value[activeIndex.value];
</script>

<template>
    <Transition name="fade">
        <div v-if="showModal && currentAnnouncement()" class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-on-surface/40 backdrop-blur-sm">
            <div class="bg-surface w-full max-w-lg rounded-3xl shadow-2xl overflow-hidden border border-outline-variant/30 animate-in zoom-in-95 duration-300">
                <!-- Header Accent -->
                <div 
                    class="h-2 w-full"
                    :class="{
                        'bg-primary': currentAnnouncement().type === 'info',
                        'bg-warning': currentAnnouncement().type === 'warning',
                        'bg-error': currentAnnouncement().type === 'danger' || currentAnnouncement().type === 'error',
                    }"
                ></div>

                <div class="p-8">
                    <div class="flex items-start justify-between mb-6">
                        <div class="flex items-center gap-3">
                            <div 
                                class="w-12 h-12 rounded-2xl flex items-center justify-center"
                                :class="{
                                    'bg-primary/10 text-primary': currentAnnouncement().type === 'info',
                                    'bg-warning/10 text-warning': currentAnnouncement().type === 'warning',
                                    'bg-error/10 text-error': currentAnnouncement().type === 'danger' || currentAnnouncement().type === 'error',
                                }"
                            >
                                <span class="material-symbols-rounded text-2xl">
                                    {{ currentAnnouncement().type === 'danger' || currentAnnouncement().type === 'error' ? 'report' : currentAnnouncement().type === 'warning' ? 'warning' : 'campaign' }}
                                </span>
                            </div>
                            <div>
                                <p class="text-[10px] font-black uppercase tracking-widest text-on-surface-variant/50 leading-none mb-1">Notificación Importante</p>
                                <h3 class="text-xl font-black text-on-surface leading-tight tracking-tight">{{ currentAnnouncement().title }}</h3>
                            </div>
                        </div>
                        <button @click="showModal = false" class="text-on-surface-variant/40 hover:text-on-surface transition-colors">
                            <span class="material-symbols-rounded">close</span>
                        </button>
                    </div>

                    <div class="bg-surface-container/50 p-6 rounded-2xl mb-8 border border-outline-variant/10">
                        <p class="text-sm text-on-surface-variant font-medium leading-relaxed whitespace-pre-wrap">
                            {{ currentAnnouncement().message }}
                        </p>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex gap-2">
                            <div v-if="announcements.length > 1" class="flex items-center gap-1">
                                <button @click="prev" :disabled="activeIndex === 0" class="w-8 h-8 rounded-full flex items-center justify-center border border-outline-variant/30 disabled:opacity-20">
                                    <span class="material-symbols-rounded">chevron_left</span>
                                </button>
                                <span class="text-[10px] font-black uppercase tracking-widest text-on-surface-variant/40">
                                    {{ activeIndex + 1 }} / {{ announcements.length }}
                                </span>
                                <button @click="next" :disabled="activeIndex === announcements.length - 1" class="w-8 h-8 rounded-full flex items-center justify-center border border-outline-variant/30 disabled:opacity-20">
                                    <span class="material-symbols-rounded">chevron_right</span>
                                </button>
                            </div>
                        </div>

                        <button 
                            @click="markAsRead(currentAnnouncement())"
                            class="bg-on-surface text-surface px-6 py-3 rounded-2xl font-black text-xs uppercase tracking-widest hover:scale-105 active:scale-95 transition-all shadow-lg"
                        >
                            Entendido
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </Transition>
</template>

<style scoped>
.fade-enter-active, .fade-leave-active { transition: opacity 0.3s ease; }
.fade-enter-from, .fade-leave-to { opacity: 0; }
</style>

<script setup lang="ts">
import { useToast } from '@/Composables/useToast';

const { toasts, remove } = useToast();
</script>

<template>
    <div class="fixed bottom-6 right-6 z-[100] flex flex-col gap-4 pointer-events-none">
        <transition-group 
            enter-active-class="transform transition duration-500 cubic-bezier(0.175, 0.885, 0.32, 1.275)"
            enter-from-class="translate-y-4 opacity-0 scale-90 sm:translate-y-0 sm:translate-x-10"
            enter-to-class="translate-y-0 opacity-100 scale-100 sm:translate-x-0"
            leave-active-class="transition duration-300 ease-in"
            leave-from-class="opacity-100 scale-100"
            leave-to-class="opacity-0 scale-95"
        >
            <div 
                v-for="toast in toasts" 
                :key="toast.id" 
                class="pointer-events-auto w-[320px] md:w-[400px] overflow-hidden rounded-[1.5rem] border shadow-[0_25px_60px_rgba(0,0,0,0.2)] backdrop-blur-2xl transition-all duration-300"
                :class="{
                    'bg-white/95 dark:bg-slate-900/90 border-primary/20 dark:border-primary/30': !toast.variant || toast.variant === 'primary' || toast.variant === 'info',
                    'bg-emerald-50/95 dark:bg-emerald-950/40 border-emerald-500/30': toast.variant === 'success',
                    'bg-red-50/95 dark:bg-red-950/40 border-red-500/30': toast.variant === 'danger' || toast.variant === 'error',
                    'bg-amber-50/95 dark:bg-amber-950/40 border-amber-500/30': toast.variant === 'warning',
                }"
            >
                <div class="p-5">
                    <div class="flex items-center gap-4">
                        <!-- Icon Container with Dynamic Variants -->
                        <div class="flex-shrink-0">
                            <div 
                                class="flex h-11 w-11 items-center justify-center rounded-2xl shadow-sm transition-all duration-500"
                                :class="{
                                    'bg-primary text-on-primary': !toast.variant || toast.variant === 'primary' || toast.variant === 'info',
                                    'bg-emerald-500 text-white shadow-emerald-500/20': toast.variant === 'success',
                                    'bg-red-500 text-white shadow-red-500/20': toast.variant === 'danger' || toast.variant === 'error',
                                    'bg-amber-500 text-white shadow-amber-500/20': toast.variant === 'warning',
                                }"
                            >
                                <svg v-if="toast.variant === 'success'" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                </svg>
                                <svg v-else-if="toast.variant === 'danger' || toast.variant === 'error'" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                <svg v-else-if="toast.variant === 'warning'" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                                </svg>
                                <svg v-else class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12v-.008z" />
                                </svg>
                            </div>
                        </div>

                        <!-- Text Content -->
                        <div class="flex-1 min-w-0">
                            <p 
                                class="text-[10px] font-black uppercase tracking-[0.15em] mb-0.5 opacity-50"
                                :class="{
                                    'text-primary dark:text-primary-light': !toast.variant || toast.variant === 'primary' || toast.variant === 'info',
                                    'text-emerald-700 dark:text-emerald-400': toast.variant === 'success',
                                    'text-red-700 dark:text-red-400': toast.variant === 'danger' || toast.variant === 'error',
                                    'text-amber-700 dark:text-amber-400': toast.variant === 'warning',
                                }"
                            >
                                {{ toast.variant === 'danger' || toast.variant === 'error' ? 'Atención' : toast.variant || 'Notificación' }}
                            </p>
                            <p class="text-sm font-bold text-on-surface dark:text-white leading-tight">
                                {{ toast.message }}
                            </p>
                        </div>

                        <!-- Close Button -->
                        <div class="flex-shrink-0 self-start">
                            <button 
                                type="button" 
                                @click="remove(toast.id)"
                                class="inline-flex rounded-xl p-1.5 transition-all active:scale-90 hover:bg-black/5 dark:hover:bg-white/10 text-on-surface-variant/40 hover:text-on-surface"
                            >
                                <span class="sr-only">Cerrar</span>
                                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Bottom Decorative Bar -->
                <div 
                    class="h-1 w-full opacity-40 transition-all duration-300"
                    :class="{
                        'bg-primary': !toast.variant || toast.variant === 'primary' || toast.variant === 'info',
                        'bg-emerald-500': toast.variant === 'success',
                        'bg-red-500': toast.variant === 'danger' || toast.variant === 'error',
                        'bg-amber-500': toast.variant === 'warning',
                    }"
                ></div>
            </div>
        </transition-group>
    </div>
</template>

<style scoped>
.no-scrollbar::-webkit-scrollbar { display: none; }
</style>

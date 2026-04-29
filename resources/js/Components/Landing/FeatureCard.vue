<script setup lang="ts">
defineProps({
    icon: {
        type: String,
        required: true
    },
    title: {
        type: String,
        required: true
    },
    description: {
        type: String,
        required: true
    },
    iconBgClass: {
        type: String,
        default: 'bg-primary-fixed/30 text-primary'
    },
    delay: {
        type: Number,
        default: 0
    }
});
</script>

<template>
    <div 
        class="bg-surface-container-lowest p-10 rounded-3xl group hover:translate-y-[-8px] transition-all duration-300 shadow-sm hover:shadow-xl opacity-0 translate-y-8"
        :style="{ transitionDelay: `${delay}ms` }"
        data-animate="fade-up"
    >
        <div :class="['w-14 h-14 rounded-2xl flex items-center justify-center mb-8', iconBgClass]">
            <span class="material-symbols-outlined text-3xl">{{ icon }}</span>
        </div>
        <h3 class="text-xl font-bold mb-4 text-primary">{{ title }}</h3>
        <p class="text-on-surface-variant leading-relaxed">
            {{ description }}
        </p>
    </div>
</template>

<style scoped>
/* Note: Animation classes will be controlled by the parent FeaturesSection using Intersection Observer */
.animate-in {
    opacity: 1 !important;
    transform: translateY(0) !important;
}
</style>

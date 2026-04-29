<script setup lang="ts">
import Header from '@/Components/Landing/Header.vue';
import Footer from '@/Components/Landing/Footer.vue';
</script>

<template>
    <div class="min-h-screen bg-surface flex flex-col font-sans text-on-surface antialiased">
        <Header />
        
        <main class="flex-grow flex flex-col">
            <slot />
        </main>
        
        <Footer />
    </div>
</template>

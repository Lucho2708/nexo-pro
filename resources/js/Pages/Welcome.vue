<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { onMounted, PropType } from 'vue';
import type { Feature, Plan } from '@/types';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import HeroSection from '@/Components/Landing/HeroSection.vue';
import RolesSection from '@/Components/Landing/RolesSection.vue';
import FeaturesSection from '@/Components/Landing/FeaturesSection.vue';
import PricingSection from '@/Components/Landing/PricingSection.vue';
import ContactDemoSection from '@/Components/Landing/ContactDemoSection.vue';
import CookieConsent from '@/Components/UI/CookieConsent.vue';

const props = defineProps({
    features: {
        type: Array as PropType<Feature[]>,
        required: true
    },
    plans: {
        type: Array as PropType<Plan[]>,
        required: true
    }
});

onMounted(() => {
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('aos-animate');
                // No dejamos de observar para permitir re-animar si se desea, 
                // o desobservamos para rendimiento (mejor desobservar)
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    document.querySelectorAll('[data-aos]').forEach(el => {
        observer.observe(el);
    });
});
</script>

<template>
    <Head title="Bienvenido" />
    
    <GuestLayout>
        <!-- Background Tactical Infrastructure -->
        <div class="fixed inset-0 pointer-events-none z-0 overflow-hidden">
            <!-- Tactical Grid -->
            <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')] opacity-[0.03] dark:opacity-[0.05]"></div>
            
            <!-- Orbital Particles -->
            <div class="absolute top-1/4 left-1/4 w-[500px] h-[500px] bg-primary/5 blur-[120px] rounded-full animate-float pointer-events-none"></div>
            <div class="absolute bottom-1/4 right-1/4 w-[400px] h-[400px] bg-secondary/5 blur-[100px] rounded-full animate-float-delayed pointer-events-none"></div>
        </div>

        <div class="relative z-10">
            <div data-aos="fade">
                <HeroSection />
            </div>
            
            <div data-aos="fade-up" class="bg-surface-container-low/40 dark:bg-transparent">
                <RolesSection id="roles" />
            </div>
            
            <div data-aos="fade-up">
                <FeaturesSection :features="features" id="soluciones" />
            </div>
            
            <div data-aos="fade-up" class="bg-primary/5 dark:bg-transparent">
                <PricingSection :plans="plans" id="precios" />
            </div>
            
            <div data-aos="fade-up">
                <ContactDemoSection id="contacto" />
            </div>
        </div>
        
        <CookieConsent />
    </GuestLayout>
</template>

<style>
/* Animaciones AOS Custom */
[data-aos] {
    opacity: 0;
    transform: translateY(30px);
    transition: all 1s cubic-bezier(0.22, 1, 0.36, 1);
    will-change: transform, opacity;
}

[data-aos="fade"] {
    transform: translateY(0);
}

[data-aos].aos-animate {
    opacity: 1;
    transform: translateY(0);
}

/* Efecto de Vidrio Esmerilado Global */
.glass-effect {
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
}

@keyframes float {
    0%, 100% { transform: translate(0, 0) scale(1); }
    33% { transform: translate(30px, -50px) scale(1.1); }
    66% { transform: translate(-20px, 20px) scale(0.9); }
}

@keyframes float-delayed {
    0%, 100% { transform: translate(0, 0) scale(1.1); }
    33% { transform: translate(-40px, 30px) scale(0.9); }
    66% { transform: translate(30px, -20px) scale(1.05); }
}

.animate-float {
    animation: float 20s infinite ease-in-out;
}

.animate-float-delayed {
    animation: float-delayed 25s infinite ease-in-out;
}
</style>

<script setup lang="ts">
/**
 * NexoSkeleton.vue
 *
 * [LÓGICA DE NEGOCIO / UX]
 * Componente de marcador de posición (Skeleton Loader) con animación de brillo (shimmer).
 * - Mejora la percepción de velocidad y reduce el estrés del usuario frente a conexiones lentas.
 * - Erradica el molesto desplazamiento acumulado del diseño (Cumulative Layout Shift - CLS)
 *   reservando el espacio geométrico exacto de los datos antes de que la API responda.
 *
 * [ASPECTO TÉCNICO / UI]
 * - Animación CSS fluida @keyframes shimmer acelerada por hardware (transform: translateX).
 * - Soporte nativo para Modo Claro (zinc-200 / slate-200) y Modo Oscuro (slate-800 / zinc-800).
 * - Variantes semánticas: 'text', 'card', 'circle' (avatares) y 'button'.
 */

type SkeletonVariant = 'text' | 'card' | 'circle' | 'button';

const props = withDefaults(
    defineProps<{
        variant?: SkeletonVariant;
        width?: string;
        height?: string;
        rounded?: string;
    }>(),
    {
        variant: 'text',
        width: '100%',
        height: undefined,
        rounded: undefined,
    }
);
</script>

<template>
    <div
        class="relative overflow-hidden bg-slate-200/80 dark:bg-slate-800/80 animate-pulse select-none"
        :class="[
            rounded ? rounded : (variant === 'circle' ? 'rounded-full' : (variant === 'button' ? 'rounded-xl' : 'rounded-lg')),
            variant === 'text' && !height ? 'h-4 my-1' : '',
            variant === 'circle' && !height ? 'h-10 w-10 shrink-0' : '',
            variant === 'button' && !height ? 'h-12' : '',
            variant === 'card' && !height ? 'h-28' : '',
        ]"
        :style="{
            width: width,
            height: height ? height : undefined,
        }"
        aria-hidden="true"
    >
        <!-- Capa de brillo translúcido Shimmer -->
        <div
            class="absolute inset-0 -translate-x-full bg-gradient-to-r from-transparent via-white/20 dark:via-white/5 to-transparent animate-[shimmer_1.5s_infinite]"
        ></div>
    </div>
</template>

<style scoped>
@keyframes shimmer {
    100% {
        transform: translateX(100%);
    }
}
</style>

# Animaciones y Microinteracciones Premium

## Principios de motion design

1. **Propósito:** Cada animación comunica algo (estado, jerarquía, relación)
2. **Velocidad:** Respuestas táctiles 100–150ms, transiciones 200–300ms, entradas 300–400ms
3. **Curvas:** Usar spring/ease-out para entradas, ease-in para salidas
4. **Respeto:** Siempre envolver en `@media (prefers-reduced-motion: reduce)`

---

## Transiciones de Vue.js — Configuración global

```javascript
// main.js — registrar transiciones globales
// Las usamos como <Transition name="fade">, <Transition name="slide-up">, etc.
```

```css
/* src/assets/styles/animations.css */

/* Fade simple */
.fade-enter-active, .fade-leave-active { transition: opacity 0.2s ease; }
.fade-enter-from, .fade-leave-to { opacity: 0; }

/* Slide up (para modales, dropdowns que salen desde abajo) */
.slide-up-enter-active { transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1); }
.slide-up-leave-active { transition: all 0.2s ease-in; }
.slide-up-enter-from   { opacity: 0; transform: translateY(12px); }
.slide-up-leave-to     { opacity: 0; transform: translateY(8px); }

/* Slide down (para mensajes de error, helpers) */
.slide-down-enter-active { transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1); }
.slide-down-leave-active { transition: all 0.15s ease-in; }
.slide-down-enter-from   { opacity: 0; transform: translateY(-6px); }
.slide-down-leave-to     { opacity: 0; transform: translateY(-4px); }

/* Scale in (para tooltips, popovers) */
.scale-in-enter-active { transition: all 0.2s cubic-bezier(0.34, 1.56, 0.64, 1); }
.scale-in-leave-active { transition: all 0.15s ease-in; }
.scale-in-enter-from   { opacity: 0; transform: scale(0.92); }
.scale-in-leave-to     { opacity: 0; transform: scale(0.95); }

/* Toast (entra desde la derecha) */
.toast-enter-active { transition: all 0.35s cubic-bezier(0.16, 1, 0.3, 1); }
.toast-leave-active { transition: all 0.2s ease-in; }
.toast-enter-from   { opacity: 0; transform: translateX(100%) scale(0.95); }
.toast-leave-to     { opacity: 0; transform: translateX(8px) scale(0.95); }

/* Page transition (router) */
.page-enter-active { transition: all 0.25s ease-out; }
.page-leave-active { transition: all 0.15s ease-in; }
.page-enter-from   { opacity: 0; transform: translateY(4px); }
.page-leave-to     { opacity: 0; }
```

---

## Microinteracción: botón con feedback de éxito

```vue
<script setup>
import { ref } from 'vue'

const status = ref('idle') // idle | loading | success | error

async function handleClick() {
  status.value = 'loading'
  try {
    await saveData()
    status.value = 'success'
    setTimeout(() => status.value = 'idle', 2500)
  } catch {
    status.value = 'error'
    setTimeout(() => status.value = 'idle', 3000)
  }
}
</script>

<template>
  <button
    @click="handleClick"
    :disabled="status === 'loading'"
    :class="[
      'relative inline-flex items-center gap-2 rounded-lg px-5 py-2.5 text-sm font-medium',
      'transition-all duration-300 ease-spring focus-visible:ring-2 focus-visible:ring-offset-2',
      status === 'idle'    && 'bg-brand-600 text-white hover:bg-brand-700 focus-visible:ring-brand-500',
      status === 'loading' && 'bg-brand-600 text-white opacity-80 cursor-wait',
      status === 'success' && 'bg-success text-white scale-105 focus-visible:ring-success',
      status === 'error'   && 'bg-danger text-white focus-visible:ring-danger',
    ]"
  >
    <!-- Contenido animado -->
    <Transition name="scale-in" mode="out-in">
      <span v-if="status === 'idle'" key="idle" class="flex items-center gap-2">
        <CloudArrowUpIcon class="h-4 w-4" /> Guardar cambios
      </span>
      <span v-else-if="status === 'loading'" key="loading" class="flex items-center gap-2">
        <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
        </svg>
        Guardando...
      </span>
      <span v-else-if="status === 'success'" key="success" class="flex items-center gap-2">
        <CheckIcon class="h-4 w-4" /> ¡Guardado!
      </span>
      <span v-else key="error" class="flex items-center gap-2">
        <XMarkIcon class="h-4 w-4" /> Error al guardar
      </span>
    </Transition>
  </button>
</template>
```

---

## Composable useScrollAnimation (aparición al hacer scroll)

```javascript
// composables/useScrollAnimation.js
import { ref, onMounted, onUnmounted } from 'vue'

export function useScrollAnimation(threshold = 0.1) {
  const elementRef = ref(null)
  const isVisible  = ref(false)

  let observer

  onMounted(() => {
    observer = new IntersectionObserver(
      ([entry]) => {
        if (entry.isIntersecting) {
          isVisible.value = true
          observer.unobserve(entry.target) // Solo animar una vez
        }
      },
      { threshold }
    )
    if (elementRef.value) observer.observe(elementRef.value)
  })

  onUnmounted(() => observer?.disconnect())

  return { elementRef, isVisible }
}

// Uso en componente
const { elementRef, isVisible } = useScrollAnimation()
// <div ref="elementRef" :class="isVisible ? 'animate-slide-up' : 'opacity-0'" />
```

---

## Staggered list animation (listas con entrada escalonada)

```vue
<script setup>
import { TransitionGroup } from 'vue'
</script>

<template>
  <!-- Cada card entra con 50ms de delay escalonado -->
  <TransitionGroup
    tag="div"
    class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3"
    name="list"
  >
    <div
      v-for="(item, index) in items"
      :key="item.id"
      :style="{ transitionDelay: `${index * 50}ms` }"
    >
      <StatsCard v-bind="item" />
    </div>
  </TransitionGroup>
</template>

<style scoped>
.list-enter-active { transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1); }
.list-leave-active { transition: all 0.2s ease-in; position: absolute; }
.list-enter-from   { opacity: 0; transform: translateY(16px); }
.list-leave-to     { opacity: 0; transform: scale(0.95); }
.list-move         { transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1); }
</style>
```

---

## Contador animado de números (KPIs)

```vue
<script setup>
import { ref, watch } from 'vue'

const props = defineProps({ value: Number, duration: { type: Number, default: 1000 } })
const displayed = ref(0)

watch(() => props.value, (newVal) => {
  const start = displayed.value
  const diff  = newVal - start
  const startTime = performance.now()

  function step(currentTime) {
    const elapsed  = currentTime - startTime
    const progress = Math.min(elapsed / props.duration, 1)
    // Easing out cubic
    const eased = 1 - Math.pow(1 - progress, 3)
    displayed.value = Math.round(start + diff * eased)
    if (progress < 1) requestAnimationFrame(step)
  }

  requestAnimationFrame(step)
}, { immediate: true })
</script>

<template>
  <span>{{ displayed.toLocaleString('es-CO') }}</span>
</template>
```

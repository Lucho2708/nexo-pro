# Mobile-First — Breakpoints, Grillas y Patrones Táctiles

## Breakpoints del sistema (Tailwind)

```
default (base) → 0px+     → Móvil portrait (320–767px)   ← DISEÑAR AQUÍ PRIMERO
sm             → 640px+   → Móvil landscape / Tablet pequeña
md             → 768px+   → Tablet portrait
lg             → 1024px+  → Tablet landscape / Laptop
xl             → 1280px+  → Desktop
2xl            → 1536px+  → Desktop grande / Ultrawide
```

## Regla de oro mobile-first

```html
<!-- ❌ MAL: desktop-first (ocultar en móvil) -->
<div class="flex max-md:hidden"> ... </div>

<!-- ✅ BIEN: mobile-first (mostrar en desktop) -->
<div class="hidden md:flex"> ... </div>
```

```css
/* ❌ MAL: desktop-first en CSS */
.sidebar { width: 260px; }
@media (max-width: 768px) { .sidebar { display: none; } }

/* ✅ BIEN: mobile-first */
.sidebar { display: none; }
@media (min-width: 768px) { .sidebar { display: block; width: 260px; } }
```

---

## Sistema de grillas responsive

### Grid de contenido (artículos, cards)

```html
<!-- Cards adaptativas: 1 col → 2 col → 3 col → 4 col -->
<div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
  <StatsCard v-for="stat in stats" :key="stat.id" v-bind="stat" />
</div>
```

### Layout principal de la app

```html
<!-- Layout con sidebar colapsable -->
<div class="flex min-h-screen bg-surface-50 dark:bg-surface-950">

  <!-- Sidebar: oculto en móvil, fijo en desktop -->
  <aside
    class="fixed inset-y-0 left-0 z-50 w-[260px] transform transition-transform duration-300 ease-spring
           bg-white dark:bg-surface-900 border-r border-surface-200 dark:border-white/8
           -translate-x-full md:translate-x-0 md:static md:flex-shrink-0"
    :class="{ 'translate-x-0': sidebarOpen }"
  >
    <AppSidebar />
  </aside>

  <!-- Overlay móvil -->
  <Transition name="fade">
    <div
      v-if="sidebarOpen"
      class="fixed inset-0 z-40 bg-black/40 backdrop-blur-sm md:hidden"
      @click="sidebarOpen = false"
    />
  </Transition>

  <!-- Contenido principal -->
  <div class="flex flex-1 flex-col min-w-0">
    <AppHeader @toggle-sidebar="sidebarOpen = !sidebarOpen" />
    <main class="flex-1 p-4 sm:p-6 lg:p-8">
      <slot />
    </main>
  </div>
</div>
```

---

## Touch targets y gestos táctiles

### Regla de los 44px

```html
<!-- ❌ Botón demasiado pequeño para el pulgar -->
<button class="p-1 text-sm"> <XMarkIcon class="h-4 w-4" /> </button>

<!-- ✅ Touch target adecuado (mínimo 44×44px) -->
<button class="flex h-11 w-11 items-center justify-center rounded-lg hover:bg-surface-100">
  <XMarkIcon class="h-5 w-5" aria-label="Cerrar" />
</button>
```

### Swipe para acciones en listas (patrón mobile nativo)

```vue
<script setup>
import { useSwipe } from '@vueuse/core'
import { ref } from 'vue'

const rowRef = ref(null)
const swipeOffset = ref(0)
const actionVisible = ref(false)

const { direction, distanceX } = useSwipe(rowRef, {
  onSwipe() {
    if (direction.value === 'left') {
      swipeOffset.value = Math.min(Math.abs(distanceX.value), 80)
      actionVisible.value = swipeOffset.value > 40
    }
  },
  onSwipeEnd() {
    if (!actionVisible.value) swipeOffset.value = 0
  },
})
</script>

<template>
  <div class="relative overflow-hidden">
    <!-- Acción oculta detrás -->
    <div class="absolute right-0 top-0 flex h-full w-20 items-center justify-center bg-danger">
      <TrashIcon class="h-5 w-5 text-white" />
    </div>

    <!-- Fila deslizable -->
    <div
      ref="rowRef"
      class="relative bg-white dark:bg-surface-900 transition-transform"
      :style="{ transform: `translateX(-${swipeOffset}px)` }"
    >
      <slot />
    </div>
  </div>
</template>
```

---

## Composable useBreakpoint

```javascript
// composables/useBreakpoint.js
import { useMediaQuery } from '@vueuse/core'
import { computed } from 'vue'

export function useBreakpoint() {
  const isSm  = useMediaQuery('(min-width: 640px)')
  const isMd  = useMediaQuery('(min-width: 768px)')
  const isLg  = useMediaQuery('(min-width: 1024px)')
  const isXl  = useMediaQuery('(min-width: 1280px)')

  const isMobile  = computed(() => !isMd.value)
  const isTablet  = computed(() => isMd.value && !isLg.value)
  const isDesktop = computed(() => isLg.value)

  return { isSm, isMd, isLg, isXl, isMobile, isTablet, isDesktop }
}

// Uso en componente
const { isMobile, isDesktop } = useBreakpoint()
// <DataTable v-if="isDesktop" /> <CardList v-else />
```

---

## Patrones de navegación por dispositivo

### Mobile: Bottom Navigation

```vue
<template>
  <!-- Solo visible en móvil -->
  <nav class="fixed bottom-0 left-0 right-0 z-50 md:hidden
              bg-white/90 dark:bg-surface-900/90 backdrop-blur-md
              border-t border-surface-200 dark:border-white/8
              pb-safe"> <!-- pb-safe = padding para iPhone notch -->
    <div class="flex items-center justify-around px-2 py-2">
      <NavItem
        v-for="item in navItems"
        :key="item.to"
        :to="item.to"
        :icon="item.icon"
        :label="item.label"
        :badge="item.badge"
      />
    </div>
  </nav>
</template>

<style>
/* Safe area para iPhone X+ */
.pb-safe { padding-bottom: env(safe-area-inset-bottom, 8px); }
</style>
```

### Desktop: Sidebar

```vue
<!-- Solo visible en md+ — manejado con hidden md:flex en el layout principal -->
```

---

## Tipografía responsive (escala fluida)

```html
<!-- Hero section: escala fluida sin breakpoints hardcoded -->
<h1 class="font-display font-bold tracking-tight text-gray-900 dark:text-white
           text-3xl sm:text-4xl lg:text-5xl xl:text-6xl">
  Administra tu copropiedad con inteligencia
</h1>

<!-- Subtítulo -->
<p class="mt-4 text-base sm:text-lg text-gray-600 dark:text-gray-400 max-w-2xl">
  NEXO-PRO centraliza todo: recaudo, mantenimiento y comunicación.
</p>
```

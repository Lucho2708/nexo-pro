# Dark Mode, Theming y Accesibilidad (a11y)

## useTheme composable

```javascript
// composables/useTheme.js
import { ref, watch, onMounted } from 'vue'
import { usePreferredDark, useStorage } from '@vueuse/core'

export function useTheme() {
  const prefersDark  = usePreferredDark()
  const storedTheme  = useStorage('ph360-theme', 'system') // 'light' | 'dark' | 'system'

  const isDark = ref(false)

  function applyTheme() {
    const shouldBeDark =
      storedTheme.value === 'dark' ||
      (storedTheme.value === 'system' && prefersDark.value)

    isDark.value = shouldBeDark
    document.documentElement.classList.toggle('dark', shouldBeDark)
  }

  watch([storedTheme, prefersDark], applyTheme)
  onMounted(applyTheme)

  function setTheme(theme) { storedTheme.value = theme }
  function toggle() { setTheme(isDark.value ? 'light' : 'dark') }

  return { isDark, storedTheme, setTheme, toggle }
}
```

## ThemeToggle.vue

```vue
<script setup>
import { useTheme } from '@/composables/useTheme'
const { isDark, storedTheme, setTheme } = useTheme()

const options = [
  { value: 'light',  label: 'Claro',    icon: SunIcon },
  { value: 'dark',   label: 'Oscuro',   icon: MoonIcon },
  { value: 'system', label: 'Sistema',  icon: ComputerDesktopIcon },
]
</script>

<template>
  <div class="flex items-center gap-1 rounded-lg bg-surface-100 dark:bg-surface-800 p-1">
    <button
      v-for="opt in options"
      :key="opt.value"
      @click="setTheme(opt.value)"
      :title="opt.label"
      :aria-pressed="storedTheme === opt.value"
      :class="[
        'flex h-7 w-7 items-center justify-center rounded-md transition-all duration-150',
        storedTheme === opt.value
          ? 'bg-white dark:bg-surface-700 shadow-xs text-gray-900 dark:text-gray-100'
          : 'text-gray-400 hover:text-gray-600 dark:hover:text-gray-300',
      ]"
    >
      <component :is="opt.icon" class="h-4 w-4" />
    </button>
  </div>
</template>
```

---

## Accesibilidad (WCAG 2.1 AA) — Reglas por componente

### Contraste mínimo

```
Texto normal  (< 18px regular / < 14px bold): mínimo 4.5:1
Texto grande  (≥ 18px regular / ≥ 14px bold): mínimo 3:1
Componentes UI (borders de input, íconos informativos): mínimo 3:1
```

```javascript
// Pares de color aprobados para el sistema
const contrastAprobado = {
  // Texto sobre blanco
  'gray-900 / white': 19.1,   // ✅ texto principal
  'gray-600 / white': 5.74,   // ✅ texto secundario
  'gray-400 / white': 2.8,    // ❌ solo decorativo (placeholder)
  // Texto sobre brand-600
  'white / brand-600': 4.7,   // ✅ botón primario
  // Texto sobre surface-50
  'gray-700 / surface-50': 9.8, // ✅
}
```

### Focus visible obligatorio

```css
/* Nunca hacer esto: */
*:focus { outline: none; } /* ❌ rompe accesibilidad de teclado */

/* Siempre usar focus-visible: */
:focus-visible {
  outline: 2px solid theme('colors.brand.500');
  outline-offset: 2px;
}
/* Tailwind: class="focus-visible:ring-2 focus-visible:ring-brand-500 focus-visible:ring-offset-2" */
```

### Íconos y botones sin texto

```html
<!-- ❌ MAL: ícono sin descripción -->
<button @click="eliminar">
  <TrashIcon class="h-5 w-5" />
</button>

<!-- ✅ BIEN: aria-label descriptivo -->
<button @click="eliminar" aria-label="Eliminar liquidación del apartamento 501">
  <TrashIcon class="h-5 w-5" aria-hidden="true" />
</button>
```

### Roles ARIA para componentes personalizados

```html
<!-- Tabs accesibles -->
<div role="tablist" aria-label="Secciones del perfil">
  <button
    v-for="tab in tabs"
    :key="tab.id"
    role="tab"
    :aria-selected="activeTab === tab.id"
    :tabindex="activeTab === tab.id ? 0 : -1"
    @click="activeTab = tab.id"
    @keydown.right="nextTab"
    @keydown.left="prevTab"
  >
    {{ tab.label }}
  </button>
</div>

<div
  v-for="tab in tabs"
  :key="tab.id"
  role="tabpanel"
  :aria-labelledby="`tab-${tab.id}`"
  :hidden="activeTab !== tab.id"
>
  <component :is="tab.component" />
</div>
```

### Mensajes de estado accesibles (live regions)

```html
<!-- Anunciar cambios de estado a lectores de pantalla -->
<div aria-live="polite" aria-atomic="true" class="sr-only">
  {{ statusMessage }}
  <!-- Ejemplo: "Liquidación guardada correctamente" -->
</div>

<div aria-live="assertive" class="sr-only">
  {{ errorMessage }}
  <!-- Errores críticos se anuncian inmediatamente -->
</div>
```

### Orden de navegación con teclado

```html
<!-- Trampas de foco en modales (Headless UI lo maneja automáticamente) -->
<!-- Para modales custom: usar la librería 'focus-trap-vue' -->

<!-- Skip navigation para usuarios de teclado -->
<a
  href="#main-content"
  class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4
         focus:z-[9999] focus:rounded-lg focus:bg-brand-600 focus:px-4 focus:py-2
         focus:text-sm focus:font-medium focus:text-white focus:shadow-lg"
>
  Ir al contenido principal
</a>
```

### Formularios accesibles

```html
<!-- Asociar label e input SIEMPRE -->
<label for="nombre-unidad">Número de unidad</label>
<input
  id="nombre-unidad"
  name="nombre_unidad"
  type="text"
  autocomplete="off"
  aria-required="true"
  aria-invalid="true"
  aria-describedby="nombre-error nombre-hint"
/>
<p id="nombre-error" role="alert">El número de unidad es requerido</p>
<p id="nombre-hint">Ejemplo: 301-A</p>
```

---

## Checklist de accesibilidad antes de PR

- [ ] Contraste de texto verificado con Axe DevTools o Colour Contrast Analyser
- [ ] Navegación completa con solo teclado (Tab, Shift+Tab, Enter, Space, Escape)
- [ ] Todos los íconos solos tienen aria-label
- [ ] Los mensajes de error están asociados al input con aria-describedby
- [ ] Los modales tienen foco atrapado y se cierran con Escape
- [ ] Las imágenes decorativas tienen alt="" (vacío, no omitido)
- [ ] Las listas de ítems usan `<ul>/<li>` o role="list"
- [ ] La jerarquía de headings es lógica (h1 → h2 → h3, sin saltos)
- [ ] El color no es el único indicador de estado (acompañar con ícono o texto)

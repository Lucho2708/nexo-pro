# Patrones UX Premium — Estados, Feedback y Flujos

## Regla fundamental: nunca dejes al usuario en el vacío

Cada operación asíncrona necesita 4 estados diseñados:
1. **Loading** — el usuario sabe que está pasando algo
2. **Success** — confirmación clara de que funcionó
3. **Error** — qué salió mal y cómo resolverlo
4. **Empty** — qué hacer cuando no hay datos

---

## Skeleton Loaders (no usar spinners genéricos en listas)

```vue
<!-- SkeletonCard.vue -->
<template>
  <div class="animate-pulse rounded-xl bg-white dark:bg-surface-900
              border border-surface-200 dark:border-white/8 p-5">
    <div class="flex items-start gap-3">
      <div class="h-10 w-10 rounded-lg bg-surface-200 dark:bg-white/8 flex-shrink-0" />
      <div class="flex-1 space-y-2">
        <div class="h-4 w-3/4 rounded-md bg-surface-200 dark:bg-white/8" />
        <div class="h-3 w-1/2 rounded-md bg-surface-100 dark:bg-white/5" />
      </div>
    </div>
    <div class="mt-4 space-y-2">
      <div class="h-7 w-2/5 rounded-md bg-surface-200 dark:bg-white/8" />
      <div class="h-3 w-1/4 rounded-md bg-surface-100 dark:bg-white/5" />
    </div>
  </div>
</template>

<!-- Uso: mostrar N skeletons mientras carga -->
<template>
  <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
    <template v-if="loading">
      <SkeletonCard v-for="i in 4" :key="i" />
    </template>
    <template v-else>
      <StatsCard v-for="stat in stats" :key="stat.id" v-bind="stat" />
    </template>
  </div>
</template>
```

---

## Empty States con propósito

```vue
<!-- EmptyState.vue — con ilustración SVG inline y CTA -->
<script setup>
defineProps({
  icon:        { type: Object, default: null },
  title:       { type: String, required: true },
  description: { type: String, default: '' },
  action:      { type: String, default: '' },    // Texto del botón CTA
  actionIcon:  { type: Object, default: null },
  size:        { type: String, default: 'md' },  // sm | md | lg
})
</script>

<template>
  <div :class="[
    'flex flex-col items-center justify-center text-center',
    size === 'sm' && 'py-8 px-4',
    size === 'md' && 'py-16 px-6',
    size === 'lg' && 'py-24 px-8',
  ]">
    <!-- Ícono con fondo decorativo -->
    <div class="relative mb-5">
      <div class="absolute inset-0 rounded-full bg-brand-50 dark:bg-brand-500/10 blur-xl scale-150 opacity-60" />
      <div class="relative flex h-14 w-14 items-center justify-center rounded-2xl
                  bg-brand-50 dark:bg-brand-500/15">
        <component :is="icon" class="h-7 w-7 text-brand-500 dark:text-brand-400" />
      </div>
    </div>

    <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">{{ title }}</h3>
    <p v-if="description" class="mt-1.5 max-w-sm text-sm text-gray-500 dark:text-gray-400 leading-relaxed">
      {{ description }}
    </p>

    <BaseButton v-if="action" class="mt-6" :icon-left="actionIcon" @click="$emit('action')">
      {{ action }}
    </BaseButton>
  </div>
</template>
```

---

## Toast / Notifications System

```vue
<!-- useToast.js composable -->
<script>
import { ref } from 'vue'

const toasts = ref([])

export function useToast() {
  function show({ message, type = 'info', duration = 4000, action = null }) {
    const id = Date.now()
    toasts.value.push({ id, message, type, action })

    if (duration > 0) {
      setTimeout(() => dismiss(id), duration)
    }
    return id
  }

  const dismiss = (id) => {
    toasts.value = toasts.value.filter(t => t.id !== id)
  }

  return {
    toasts,
    success: (msg, opts = {}) => show({ message: msg, type: 'success', ...opts }),
    error:   (msg, opts = {}) => show({ message: msg, type: 'error', duration: 6000, ...opts }),
    warning: (msg, opts = {}) => show({ message: msg, type: 'warning', ...opts }),
    info:    (msg, opts = {}) => show({ message: msg, type: 'info', ...opts }),
    dismiss,
  }
}
</script>

<!-- ToastContainer.vue -->
<template>
  <Teleport to="body">
    <div class="fixed bottom-0 right-0 z-[9999] flex flex-col gap-2
                p-4 sm:p-6 max-w-sm w-full pointer-events-none
                bottom-safe"> <!-- bottom-safe para mobile -->
      <TransitionGroup name="toast">
        <div
          v-for="toast in toasts"
          :key="toast.id"
          :class="[
            'pointer-events-auto flex items-start gap-3 rounded-xl p-4',
            'border shadow-modal backdrop-blur-sm',
            'dark:bg-surface-800/95',
            toast.type === 'success' && 'bg-white border-success/20',
            toast.type === 'error'   && 'bg-white border-danger/20',
            toast.type === 'warning' && 'bg-white border-warning/20',
            toast.type === 'info'    && 'bg-white border-brand-200 dark:border-brand-500/20',
          ]"
          role="alert"
        >
          <!-- Ícono semántico -->
          <div :class="[
            'flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-lg',
            toast.type === 'success' && 'bg-success/10',
            toast.type === 'error'   && 'bg-danger/10',
            toast.type === 'warning' && 'bg-warning/10',
            toast.type === 'info'    && 'bg-brand-50 dark:bg-brand-500/15',
          ]">
            <CheckCircleIcon  v-if="toast.type === 'success'" class="h-4 w-4 text-success" />
            <XCircleIcon      v-if="toast.type === 'error'"   class="h-4 w-4 text-danger" />
            <ExclamationIcon  v-if="toast.type === 'warning'" class="h-4 w-4 text-warning" />
            <InformationCircleIcon v-if="toast.type === 'info'" class="h-4 w-4 text-brand-500" />
          </div>

          <p class="flex-1 text-sm text-gray-700 dark:text-gray-200 leading-relaxed">
            {{ toast.message }}
          </p>

          <button
            @click="dismiss(toast.id)"
            class="flex-shrink-0 rounded-md p-1 text-gray-400 hover:text-gray-600
                   hover:bg-surface-100 transition-colors"
            aria-label="Cerrar notificación"
          >
            <XMarkIcon class="h-4 w-4" />
          </button>
        </div>
      </TransitionGroup>
    </div>
  </Teleport>
</template>

<style scoped>
.toast-enter-active { @apply transition-all duration-300 ease-spring; }
.toast-leave-active { @apply transition-all duration-200 ease-in; }
.toast-enter-from   { @apply opacity-0 translate-y-2 scale-95; }
.toast-leave-to     { @apply opacity-0 translate-x-4 scale-95; }
.bottom-safe        { padding-bottom: max(1rem, env(safe-area-inset-bottom)); }
</style>
```

---

## Feedback de formulario en tiempo real

```vue
<!-- Validación con feedback visual inmediato -->
<script setup>
import { ref, computed } from 'vue'

const email = ref('')
const touched = ref(false)

const emailError = computed(() => {
  if (!touched.value) return ''
  if (!email.value) return 'El correo electrónico es requerido'
  if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value)) return 'Ingresa un correo válido'
  return ''
})

const emailStatus = computed(() => {
  if (!touched.value || !email.value) return 'default'
  return emailError.value ? 'error' : 'success'
})
</script>

<template>
  <div class="space-y-1.5">
    <label class="label-text" for="email">
      Correo electrónico
      <span class="text-danger" aria-hidden>*</span>
    </label>

    <div class="relative">
      <input
        id="email"
        v-model="email"
        type="email"
        placeholder="tu@correo.com"
        :class="[
          'block w-full rounded-lg border px-3.5 py-2.5 text-sm transition-all duration-150',
          'bg-white dark:bg-surface-800 text-gray-900 dark:text-gray-100',
          'placeholder:text-gray-400',
          'focus:outline-none focus:ring-2',
          emailStatus === 'error'   && 'border-danger focus:border-danger focus:ring-danger/20',
          emailStatus === 'success' && 'border-success focus:border-success focus:ring-success/20',
          emailStatus === 'default' && 'border-surface-200 dark:border-white/10 focus:border-brand-500 focus:ring-brand-500/20',
        ]"
        @blur="touched = true"
      />

      <!-- Ícono de estado -->
      <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
        <Transition name="scale-in">
          <CheckCircleIcon v-if="emailStatus === 'success'" class="h-4 w-4 text-success" />
          <ExclamationCircleIcon v-else-if="emailStatus === 'error'" class="h-4 w-4 text-danger" />
        </Transition>
      </div>
    </div>

    <Transition name="slide-down">
      <p v-if="emailError" class="flex items-center gap-1 text-xs text-danger" role="alert">
        <ExclamationCircleIcon class="h-3 w-3" />
        {{ emailError }}
      </p>
    </Transition>
  </div>
</template>
```

---

## Confirmación de acciones destructivas (Modal)

```vue
<!-- Patrón: no confirmar con window.confirm(), usar modal premium -->
<script setup>
import { ref } from 'vue'
import { Dialog, DialogPanel, DialogTitle } from '@headlessui/vue'

const props = defineProps({ isOpen: Boolean, title: String, description: String })
const emit = defineEmits(['confirm', 'cancel'])
const loading = ref(false)

async function handleConfirm() {
  loading.value = true
  await emit('confirm')
  loading.value = false
}
</script>

<template>
  <Dialog :open="isOpen" class="relative z-50" @close="emit('cancel')">
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-black/40 backdrop-blur-sm" aria-hidden />

    <div class="fixed inset-0 flex items-end sm:items-center justify-center p-4">
      <DialogPanel class="w-full max-w-md transform overflow-hidden rounded-2xl sm:rounded-xl
                          bg-white dark:bg-surface-900 p-6 shadow-modal
                          transition-all duration-300 ease-spring
                          animate-slide-up">

        <!-- Ícono de advertencia -->
        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-danger/10 mx-auto mb-4">
          <ExclamationTriangleIcon class="h-6 w-6 text-danger" />
        </div>

        <DialogTitle class="text-center text-base font-semibold text-gray-900 dark:text-gray-100">
          {{ title }}
        </DialogTitle>
        <p class="mt-2 text-center text-sm text-gray-500 dark:text-gray-400">
          {{ description }}
        </p>

        <div class="mt-6 flex flex-col-reverse sm:flex-row gap-3">
          <BaseButton variant="secondary" full @click="emit('cancel')">
            Cancelar
          </BaseButton>
          <BaseButton variant="danger" full :loading="loading" @click="handleConfirm">
            Confirmar eliminación
          </BaseButton>
        </div>
      </DialogPanel>
    </div>
  </Dialog>
</template>
```

# Componentes Premium — Vue.js 3 + Tailwind CSS

## BaseButton.vue

```vue
<script setup>
defineProps({
  variant: { type: String, default: 'primary' }, // primary | secondary | ghost | danger | success
  size:     { type: String, default: 'md' },      // xs | sm | md | lg | xl
  loading:  { type: Boolean, default: false },
  disabled: { type: Boolean, default: false },
  iconLeft: { type: Object, default: null },       // Componente de ícono
  iconRight:{ type: Object, default: null },
  full:     { type: Boolean, default: false },
})

const variants = {
  primary:   `bg-brand-600 text-white shadow-sm
              hover:bg-brand-700 active:bg-brand-800
              focus-visible:ring-brand-500
              disabled:bg-brand-300`,
  secondary: `bg-white text-gray-700 border border-surface-200 shadow-xs
              hover:bg-surface-50 active:bg-surface-100
              dark:bg-surface-800 dark:text-gray-200 dark:border-white/10
              dark:hover:bg-surface-700`,
  ghost:     `text-gray-600 dark:text-gray-400
              hover:bg-surface-100 dark:hover:bg-white/5
              active:bg-surface-200 dark:active:bg-white/10`,
  danger:    `bg-danger text-white shadow-sm
              hover:bg-red-600 active:bg-red-700
              focus-visible:ring-danger`,
  success:   `bg-success text-white shadow-sm
              hover:bg-emerald-600 active:bg-emerald-700`,
}

const sizes = {
  xs: 'h-7  px-2.5 text-xs  gap-1.5 rounded-md',
  sm: 'h-8  px-3   text-sm  gap-1.5 rounded-md',
  md: 'h-10 px-4   text-sm  gap-2   rounded-lg',
  lg: 'h-11 px-5   text-base gap-2  rounded-lg',
  xl: 'h-12 px-6   text-base gap-2.5 rounded-xl',
}
</script>

<template>
  <button
    :disabled="disabled || loading"
    :class="[
      'inline-flex items-center justify-center font-medium',
      'transition-all duration-150 ease-out',
      'focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-offset-2',
      'disabled:opacity-50 disabled:cursor-not-allowed disabled:pointer-events-none',
      'select-none',
      variants[variant],
      sizes[size],
      full && 'w-full',
    ]"
  >
    <!-- Loading spinner -->
    <svg v-if="loading" class="animate-spin -ml-0.5 h-4 w-4" fill="none" viewBox="0 0 24 24">
      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
    </svg>

    <component v-else-if="iconLeft" :is="iconLeft" class="h-4 w-4 flex-shrink-0" />

    <span :class="{ 'opacity-0': loading && !iconLeft }">
      <slot />
    </span>

    <component v-if="iconRight && !loading" :is="iconRight" class="h-4 w-4 flex-shrink-0" />
  </button>
</template>
```

---

## BaseInput.vue

```vue
<script setup>
import { computed, ref } from 'vue'

const props = defineProps({
  modelValue: { type: [String, Number], default: '' },
  label:      { type: String, default: '' },
  placeholder:{ type: String, default: '' },
  type:       { type: String, default: 'text' },
  error:      { type: String, default: '' },
  hint:       { type: String, default: '' },
  prefix:     { type: String, default: '' },   // Ícono o texto antes del input
  suffix:     { type: String, default: '' },
  disabled:   { type: Boolean, default: false },
  required:   { type: Boolean, default: false },
  id:         { type: String, default: () => `input-${Math.random().toString(36).slice(2, 7)}` },
})

const emit = defineEmits(['update:modelValue'])
const isFocused = ref(false)

const inputClass = computed(() => [
  'block w-full rounded-lg border bg-white text-sm text-gray-900',
  'placeholder:text-gray-400',
  'dark:bg-surface-800 dark:text-gray-100 dark:placeholder:text-gray-500',
  'transition-all duration-150',
  props.error
    ? 'border-danger focus:border-danger focus:ring-danger/25'
    : 'border-surface-200 dark:border-white/10 focus:border-brand-500 focus:ring-brand-500/20',
  'focus:outline-none focus:ring-2',
  props.prefix ? 'pl-10' : 'pl-3.5',
  props.suffix ? 'pr-10' : 'pr-3.5',
  'py-2.5',
  props.disabled && 'opacity-50 cursor-not-allowed bg-surface-50',
])
</script>

<template>
  <div class="space-y-1.5">
    <!-- Label -->
    <label v-if="label" :for="id" class="label-text block">
      {{ label }}
      <span v-if="required" class="text-danger ml-0.5" aria-hidden>*</span>
    </label>

    <!-- Input wrapper -->
    <div class="relative">
      <!-- Prefix icon/text -->
      <div v-if="prefix" class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
        <span class="text-gray-400 text-sm">{{ prefix }}</span>
      </div>

      <input
        :id="id"
        :type="type"
        :value="modelValue"
        :placeholder="placeholder"
        :disabled="disabled"
        :required="required"
        :class="inputClass"
        :aria-invalid="!!error"
        :aria-describedby="error ? `${id}-error` : hint ? `${id}-hint` : undefined"
        @input="emit('update:modelValue', $event.target.value)"
        @focus="isFocused = true"
        @blur="isFocused = false"
      />

      <!-- Suffix -->
      <div v-if="suffix" class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
        <span class="text-gray-400 text-sm">{{ suffix }}</span>
      </div>

      <!-- Error icon -->
      <div v-if="error" class="absolute inset-y-0 right-0 flex items-center pr-3">
        <ExclamationCircleIcon class="h-4 w-4 text-danger" aria-hidden />
      </div>
    </div>

    <!-- Error message -->
    <Transition name="slide-down">
      <p v-if="error" :id="`${id}-error`" class="flex items-center gap-1 text-xs text-danger" role="alert">
        <ExclamationCircleIcon class="h-3.5 w-3.5 flex-shrink-0" />
        {{ error }}
      </p>
    </Transition>

    <!-- Hint -->
    <p v-if="hint && !error" :id="`${id}-hint`" class="helper-text">{{ hint }}</p>
  </div>
</template>
```

---

## StatsCard.vue (Dashboard KPI)

```vue
<script setup>
defineProps({
  title:  { type: String, required: true },
  value:  { type: [String, Number], required: true },
  change: { type: Number, default: null },       // % de cambio vs período anterior
  trend:  { type: String, default: 'neutral' },  // up | down | neutral
  icon:   { type: Object, required: true },
  color:  { type: String, default: 'brand' },    // brand | success | warning | danger
  loading:{ type: Boolean, default: false },
})

const colorMap = {
  brand:   { bg: 'bg-brand-50 dark:bg-brand-500/10',   icon: 'text-brand-600 dark:text-brand-400' },
  success: { bg: 'bg-success/10',                       icon: 'text-success' },
  warning: { bg: 'bg-warning/10',                       icon: 'text-warning dark:text-yellow-400' },
  danger:  { bg: 'bg-danger/10',                        icon: 'text-danger' },
}
</script>

<template>
  <div class="group relative overflow-hidden rounded-xl bg-white dark:bg-surface-900
              border border-surface-200 dark:border-white/8
              p-5 shadow-card hover:shadow-card-hover
              transition-all duration-200 ease-spring">

    <!-- Skeleton loading -->
    <template v-if="loading">
      <div class="animate-shimmer space-y-3"
           style="background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
                  background-size: 200% 100%;">
        <div class="h-4 w-24 rounded bg-surface-200" />
        <div class="h-8 w-32 rounded bg-surface-200" />
        <div class="h-3 w-20 rounded bg-surface-200" />
      </div>
    </template>

    <template v-else>
      <div class="flex items-start justify-between">
        <!-- Ícono -->
        <div :class="['flex h-10 w-10 items-center justify-center rounded-lg', colorMap[color].bg]">
          <component :is="icon" :class="['h-5 w-5', colorMap[color].icon]" />
        </div>

        <!-- Badge de cambio -->
        <span v-if="change !== null" :class="[
          'inline-flex items-center gap-0.5 rounded-full px-2 py-0.5 text-xs font-medium',
          trend === 'up'   && 'bg-success/10 text-success',
          trend === 'down' && 'bg-danger/10 text-danger',
          trend === 'neutral' && 'bg-surface-100 text-gray-500 dark:bg-white/5',
        ]">
          <ArrowUpIcon   v-if="trend === 'up'"   class="h-3 w-3" />
          <ArrowDownIcon v-if="trend === 'down'" class="h-3 w-3" />
          {{ Math.abs(change) }}%
        </span>
      </div>

      <div class="mt-4">
        <p class="text-2xl font-semibold font-display text-gray-900 dark:text-white tracking-tight">
          {{ value }}
        </p>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ title }}</p>
      </div>
    </template>
  </div>
</template>
```

---

## BaseBadge.vue

```vue
<script setup>
defineProps({
  variant: { type: String, default: 'default' }, // default | success | warning | danger | info | brand
  size:    { type: String, default: 'md' },       // sm | md
  dot:     { type: Boolean, default: false },     // punto de estado pulsante
})

const variants = {
  default: 'bg-surface-100 text-gray-600 dark:bg-white/8 dark:text-gray-400',
  brand:   'bg-brand-50 text-brand-700 dark:bg-brand-500/15 dark:text-brand-300',
  success: 'bg-success/10 text-emerald-700 dark:text-emerald-400',
  warning: 'bg-warning/10 text-amber-700 dark:text-amber-400',
  danger:  'bg-danger/10 text-red-700 dark:text-red-400',
  info:    'bg-info/10 text-blue-700 dark:text-blue-400',
}
const dotColors = {
  default: 'bg-gray-400', brand: 'bg-brand-500',
  success: 'bg-success',  warning: 'bg-warning',
  danger:  'bg-danger',   info: 'bg-info',
}
const sizes = { sm: 'px-2 py-0.5 text-xs', md: 'px-2.5 py-1 text-xs' }
</script>

<template>
  <span :class="['inline-flex items-center gap-1.5 rounded-full font-medium', variants[variant], sizes[size]]">
    <span v-if="dot" class="relative flex h-1.5 w-1.5">
      <span :class="['animate-ping absolute inline-flex h-full w-full rounded-full opacity-75', dotColors[variant]]" />
      <span :class="['relative inline-flex rounded-full h-1.5 w-1.5', dotColors[variant]]" />
    </span>
    <slot />
  </span>
</template>
```

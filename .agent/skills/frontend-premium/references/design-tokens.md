# Design Tokens — Sistema de Diseño NEXO-PRO

## tailwind.config.js completo

```javascript
import { fontFamily } from 'tailwindcss/defaultTheme'

export default {
  darkMode: 'class',
  content: ['./index.html', './src/**/*.{vue,js,ts}'],

  theme: {
    extend: {
      fontFamily: {
        sans:    ['Plus Jakarta Sans', ...fontFamily.sans],
        display: ['Bricolage Grotesque', ...fontFamily.sans],
        mono:    ['JetBrains Mono', ...fontFamily.mono],
      },

      fontSize: {
        '2xs': ['0.625rem', { lineHeight: '1rem' }],           // 10px
        'xs':  ['0.75rem',  { lineHeight: '1.125rem' }],       // 12px
        'sm':  ['0.875rem', { lineHeight: '1.375rem' }],       // 14px
        'base':['1rem',     { lineHeight: '1.625rem' }],       // 16px
        'lg':  ['1.125rem', { lineHeight: '1.75rem' }],        // 18px
        'xl':  ['1.25rem',  { lineHeight: '1.875rem' }],       // 20px
        '2xl': ['1.5rem',   { lineHeight: '2rem' }],           // 24px
        '3xl': ['1.875rem', { lineHeight: '2.375rem' }],       // 30px
        '4xl': ['2.25rem',  { lineHeight: '2.75rem', letterSpacing: '-0.02em' }],
        '5xl': ['3rem',     { lineHeight: '1.15',    letterSpacing: '-0.03em' }],
        '6xl': ['3.75rem',  { lineHeight: '1.1',     letterSpacing: '-0.04em' }],
      },

      colors: {
        brand: {
          50: '#EEF2FF', 100: '#E0E7FF', 200: '#C7D2FE', 300: '#A5B4FC',
          400: '#818CF8', 500: '#6366F1', 600: '#4F46E5', 700: '#4338CA',
          800: '#3730A3', 900: '#312E81', 950: '#1E1B4B',
        },
        surface: {
          0:   '#FFFFFF',
          50:  '#F9FAFB',
          100: '#F3F4F6',
          200: '#E5E7EB',
          800: '#1F2937',
          900: '#111827',
          950: '#030712',
        },
        success: { light: '#ECFDF5', DEFAULT: '#10B981', dark: '#065F46' },
        warning: { light: '#FFFBEB', DEFAULT: '#F59E0B', dark: '#92400E' },
        danger:  { light: '#FEF2F2', DEFAULT: '#EF4444', dark: '#991B1B' },
        info:    { light: '#EFF6FF', DEFAULT: '#3B82F6', dark: '#1E3A8A' },
      },

      boxShadow: {
        'xs':         '0 1px 2px 0 rgb(0 0 0 / 0.05)',
        'sm':         '0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1)',
        'card':       '0 4px 6px -1px rgb(0 0 0 / 0.07), 0 2px 4px -2px rgb(0 0 0 / 0.05)',
        'card-hover': '0 10px 15px -3px rgb(0 0 0 / 0.08), 0 4px 6px -4px rgb(0 0 0 / 0.05)',
        'modal':      '0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.08)',
        'inner':      'inset 0 2px 4px 0 rgb(0 0 0 / 0.06)',
        'glow-brand': '0 0 0 3px rgb(99 102 241 / 0.25)',
        'glow-success':'0 0 0 3px rgb(16 185 129 / 0.25)',
        'glow-danger': '0 0 0 3px rgb(239 68 68 / 0.25)',
      },

      borderRadius: {
        'sm':   '4px',
        'md':   '8px',
        'lg':   '12px',
        'xl':   '16px',
        '2xl':  '24px',
        '3xl':  '32px',
      },

      spacing: {
        '4.5': '1.125rem',
        '5.5': '1.375rem',
        '18':  '4.5rem',
        '22':  '5.5rem',
      },

      animation: {
        'fade-in':      'fadeIn 0.2s ease-out',
        'slide-up':     'slideUp 0.3s cubic-bezier(0.16, 1, 0.3, 1)',
        'slide-down':   'slideDown 0.3s cubic-bezier(0.16, 1, 0.3, 1)',
        'scale-in':     'scaleIn 0.2s cubic-bezier(0.16, 1, 0.3, 1)',
        'spin-slow':    'spin 2s linear infinite',
        'pulse-brand':  'pulseBrand 2s cubic-bezier(0.4, 0, 0.6, 1) infinite',
        'shimmer':      'shimmer 1.5s infinite',
        'bounce-soft':  'bounceSoft 0.5s cubic-bezier(0.36, 0.07, 0.19, 0.97)',
      },

      keyframes: {
        fadeIn:     { from: { opacity: '0' }, to: { opacity: '1' } },
        slideUp:    { from: { opacity: '0', transform: 'translateY(8px)' }, to: { opacity: '1', transform: 'translateY(0)' } },
        slideDown:  { from: { opacity: '0', transform: 'translateY(-8px)' }, to: { opacity: '1', transform: 'translateY(0)' } },
        scaleIn:    { from: { opacity: '0', transform: 'scale(0.95)' }, to: { opacity: '1', transform: 'scale(1)' } },
        pulseBrand: { '0%, 100%': { opacity: '1' }, '50%': { opacity: '0.5' } },
        shimmer:    { '0%': { backgroundPosition: '-200% 0' }, '100%': { backgroundPosition: '200% 0' } },
        bounceSoft: { '0%, 100%': { transform: 'translateY(0)' }, '50%': { transform: 'translateY(-4px)' } },
      },

      transitionTimingFunction: {
        'spring':     'cubic-bezier(0.16, 1, 0.3, 1)',
        'in-back':    'cubic-bezier(0.36, 0, 0.66, -0.56)',
        'out-back':   'cubic-bezier(0.34, 1.56, 0.64, 1)',
      },
    },
  },

  plugins: [
    require('@tailwindcss/forms')({ strategy: 'class' }),
    require('@tailwindcss/typography'),
  ],
}
```

---

## Variables CSS personalizadas (base.css)

```css
/* src/assets/styles/base.css */
@import url('https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,400;12..96,500;12..96,600;12..96,700&family=Plus+Jakarta+Sans:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap');

@tailwind base;
@tailwind components;
@tailwind utilities;

@layer base {
  :root {
    /* Superficie */
    --color-bg:          theme('colors.surface.0');
    --color-bg-subtle:   theme('colors.surface.50');
    --color-bg-muted:    theme('colors.surface.100');
    --color-border:      theme('colors.surface.200');

    /* Texto */
    --color-text-primary:   theme('colors.gray.900');
    --color-text-secondary: theme('colors.gray.600');
    --color-text-tertiary:  theme('colors.gray.400');

    /* Brand */
    --color-brand:       theme('colors.brand.600');
    --color-brand-hover: theme('colors.brand.700');
    --color-brand-light: theme('colors.brand.50');

    /* Sidebar */
    --sidebar-width: 260px;
    --sidebar-collapsed: 64px;

    /* Header */
    --header-height: 64px;
    --header-height-mobile: 56px;
  }

  .dark {
    --color-bg:          theme('colors.surface.950');
    --color-bg-subtle:   theme('colors.surface.900');
    --color-bg-muted:    theme('colors.surface.800');
    --color-border:      rgb(255 255 255 / 0.08);

    --color-text-primary:   theme('colors.gray.50');
    --color-text-secondary: theme('colors.gray.400');
    --color-text-tertiary:  theme('colors.gray.600');

    --color-brand:       theme('colors.brand.400');
    --color-brand-hover: theme('colors.brand.300');
    --color-brand-light: rgb(99 102 241 / 0.12);
  }

  * { @apply border-surface-200 dark:border-white/8; }

  html {
    -webkit-tap-highlight-color: transparent;
    text-rendering: optimizeLegibility;
    -webkit-font-smoothing: antialiased;
    scroll-behavior: smooth;
  }

  /* Scrollbar premium */
  ::-webkit-scrollbar { width: 6px; height: 6px; }
  ::-webkit-scrollbar-track { @apply bg-transparent; }
  ::-webkit-scrollbar-thumb { @apply bg-gray-300 dark:bg-gray-700 rounded-full; }
  ::-webkit-scrollbar-thumb:hover { @apply bg-gray-400 dark:bg-gray-600; }

  /* Focus ring global */
  :focus-visible {
    @apply outline-none ring-2 ring-brand-500 ring-offset-2 dark:ring-offset-surface-950;
  }

  /* Reduced motion */
  @media (prefers-reduced-motion: reduce) {
    *, *::before, *::after {
      animation-duration: 0.01ms !important;
      transition-duration: 0.01ms !important;
    }
  }
}

@layer components {
  .container-app {
    @apply mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8;
  }

  .page-title {
    @apply font-display text-2xl font-semibold tracking-tight text-gray-900 dark:text-gray-50
           sm:text-3xl;
  }

  .section-title {
    @apply font-sans text-lg font-semibold text-gray-800 dark:text-gray-100;
  }

  .label-text {
    @apply text-sm font-medium text-gray-700 dark:text-gray-300;
  }

  .helper-text {
    @apply text-xs text-gray-500 dark:text-gray-400;
  }
}
```

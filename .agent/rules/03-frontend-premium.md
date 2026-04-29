---
trigger: always_on
---

# Estándares de desarrollo frontend

Eres un desarrollador frontend senior. Todo el código de cliente (React, Vue, Angular, Svelte, o vanilla) debe cumplir con:

## Arquitectura y organización
- **Estructura por características** (feature-based), no por tipo técnico.
- Cada componente debe tener una sola responsabilidad (ya aplicamos SOLID).
- Separa la lógica de estado de la presentación: usa hooks/composables/controllers.
- Gestiona el estado global con un patrón consistente (Context API, Zustand, Redux Toolkit, Pinia). Evita prop drilling profundo.

## Calidad de código
- **TypeScript estricto** (`strict: true`) – no se permite `any` sin justificación.
- Linting y formateo automático: ESLint + Prettier configurados con reglas recomendadas.
- Nombres de componentes en **PascalCase**, archivos en kebab-case o camelCase según convención del framework.
- Comentarios JSDoc/TSDoc en funciones públicas y componentes reutilizables.

## Rendimiento (Core Web Vitals)
- Lazy loading de rutas y componentes pesados.
- Uso de `React.memo`, `useMemo`, `useCallback` solo cuando haya beneficios medibles (evita optimizaciones prematuras).
- Optimización de imágenes: formatos modernos (WebP, AVIF), lazy loading, dimensiones correctas.
- Minimizar el bundle: análisis con `webpack-bundle-analyzer` o `vite-bundle-visualizer`.

## Accesibilidad (WCAG 2.1 nivel AA)
- Estructura HTML semántica (`<header>`, `<nav>`, `<main>`, etc.).
- Atributos `alt` descriptivos en imágenes.
- Navegación por teclado (focus visible, orden lógico con tabindex).
- Contraste de color mínimo 4.5:1 para texto normal.
- Uso de ARIA solo cuando sea necesario y correctamente.

## Pruebas en frontend
- Pruebas unitarias para hooks y utilidades (Jest/Vitest).
- Pruebas de integración/componente (React Testing Library / Vue Test Utils).
- Opcional pero recomendado: pruebas end-to-end con Playwright o Cypress.

## Entregable del agente
Cada componente o feature que generes debe incluir:
1. El código fuente.
2. Un breve checklist indicando cómo cumples con rendimiento, accesibilidad y TypeScript.
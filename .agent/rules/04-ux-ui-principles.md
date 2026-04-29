---
trigger: always_on
---

# Directrices de Experiencia de Usuario (UX) e Interfaz (UI)

El agente debe priorizar la usabilidad y la experiencia del usuario final. Toda interfaz que construyas debe seguir:

## Flujos de usuario
- **Minimizar la carga cognitiva**: menos de 7 elementos en un menú, procesos divididos en pasos lógicos.
- **Feedback inmediato** para cada acción del usuario (carga, éxito, error, hover, focus).
- **Mensajes de error claros y accionables** (no solo "Error 500", sino "No se pudo guardar. Verifica tu conexión e inténtalo de nuevo").
- **Confirmación para acciones destructivas** (eliminar, sobrescribir).

## Consistencia visual
- Usa un **sistema de diseño** (design tokens: colores, tipografía, espaciados, sombras) definido en un archivo central (`theme.css`, `tailwind.config.js`, etc.).
- Mantén una cuadrícula consistente (múltiplos de 4 u 8 píxeles).
- Los componentes reutilizables (botones, inputs, modales) deben tener variantes claras (primario, secundario, peligro, etc.).

## Responsividad y adaptabilidad
- Diseño **mobile-first** o al menos completamente responsive (breakpoints comunes: 640px, 768px, 1024px, 1280px).
- Los elementos táctiles deben tener al menos 44x44 píxeles.
- Las fuentes deben usar unidades relativas (rem, em) para respetar el zoom del navegador.

## Rendimiento percibido
- Los esqueletos (skeleton screens) o indicadores de carga mejoran la percepción de velocidad.
- Evita el "layout shift" (CLS): reserva espacio para imágenes, anuncios, contenido dinámico.

## Evaluación UX
Antes de dar por terminada una vista o flujo, el agente debe listar:
- ¿Qué feedback visual recibe el usuario?
- ¿Cómo se maneja el error?
- ¿Es navegable solo con teclado?
- ¿El tamaño de los elementos táctiles es adecuado?
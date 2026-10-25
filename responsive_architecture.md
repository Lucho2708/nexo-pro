# Especificación de Arquitectura Responsiva y Maquetación Multiplataforma
**Proyecto:** NEXO-PRO • Solución Enterprise Multi-Inquilino de Propiedad Horizontal  
**Escuadrón Responsable:** `Market Researcher`, `Product Owner`, `Architect`, `SOLID Validator`, `DB Dev`, `Backend Dev`, `Frontend Dev`, `Senior Reviewer`  
**Fecha:** 25 de Octubre de 2026  
**Documentación:** Bifurcada (`[LÓGICA DE NEGOCIO / UX]` & `[ASPECTO TÉCNICO / UI]`)

---

## 1. Diagnóstico Heurístico y Análisis de Defectos Visuales Reportados

A partir de las capturas del usuario en producción (`uploaded_media_0` y `uploaded_media_1`), se diagnosticaron dos fallos críticos de maquetación:

```
+-------------------------------------------------------------------------------+
|                            DEFECTOS DETECTADOS                                |
+-------------------------------------------------------------------------------+
| 1. Desbordamiento y Truncamiento Tipográfico (Horizontal Text Overflow):      |
|    - Causa: Ancho fijo con doble padding (!p-12 + px-6 = 144px de padding)    |
|      y tipografía fija text-5xl en rejilla de 3 columnas a 1024-1280px.       |
|    - Impacto: Los precios "$ 189.000", "$ 389.000" y "CUSTOM" se cortaban      |
|      en el borde derecho de las tarjetas en resoluciones Desktop intermedias. |
|                                                                               |
| 2. Colapso de Contraste en Modo Claro (White-on-White Contrast Collapse):     |
|    - Causa: En la tarjeta Recomendada, la regla bg-slate-900 fue anulada      |
|      por .premium-card (bg-surface = #FFFFFF en light mode), mientras el texto|
|      mantenía clases text-white.                                              |
|    - Impacto: Rótulos, precio y lista de características totalmente           |
|      invisibles (ratio de contraste 1:1, violación crítica WCAG 2.1 AA).       |
+-------------------------------------------------------------------------------+
```

---

## 2. Estrategia de Maquetación Multiplataforma (Mobile, Tablet, Desktop, UltraWide)

### 2.1 Metodología Estricta Mobile-First
Se establece que todo componente debe estructurarse desde la vista móvil ($320\text{px} - 639\text{px}$), escalando progresivamente hacia Tablet ($640\text{px} - 1023\text{px}$), Desktop ($1024\text{px} - 1535\text{px}$) y UltraWide ($1536\text{px}+$):

```css
/* Mobile Base (320px+) -> 1 Columna apilada, Touch targets >= 48px */
grid-cols-1 gap-6 p-5

/* Tablet (sm: 640px+ / md: 768px+) -> 2 Columnas balanceadas */
sm:grid-cols-2 md:gap-6 sm:p-6

/* Desktop (lg: 1024px+ / xl: 1280px+) -> 3 Columnas fluidas con escala visual */
lg:grid-cols-3 lg:gap-6 xl:gap-8 lg:p-7 xl:p-8

/* UltraWide (2xl: 1536px+) -> Contenedor max-w-7xl centrado con padding generoso */
2xl:p-10
```

### 2.2 Tipografía Fluida y Prevención de Desbordamiento (`clamp()` y `tabular-nums`)
Para erradicar el recorte de cifras monetarias colombianas (ej. `$ 389.000 COP`):
1. **Escala tipográfica adaptativa:**
   `text-3xl sm:text-4xl lg:text-3xl xl:text-4xl 2xl:text-5xl font-black tracking-tight tabular-nums`
2. **Contención elástica:** Uso de `min-w-0`, `truncate` y `break-keep` para que el texto nunca desborde el contenedor hijo.
3. **Optimización de padding horizontal:** Se reemplazó el padding rígido `!p-12` ($48\text{px}$ en cada borde $= 96\text{px}$) por una progresión elástica `p-6 sm:p-7 xl:p-8`, recuperando más de $40\text{px}$ de ancho útil por tarjeta.

### 2.3 Solución de Contraste Incondicional para Tarjetas Destacadas
Para la tarjeta de plan **Recomendado**:
- Se fija un fondo oscuro premium autónomo `!bg-slate-950` con borde `border-indigo-500/50` y sombra `shadow-indigo-500/20` tanto en **Modo Claro** como en **Modo Oscuro**.
- Todos los elementos textuales interiores se definen explícitamente en tonos claros (`text-white`, `text-slate-300`, `text-indigo-400`), asegurando un ratio de contraste superior a **$16:1$ (Nivel AAA)** en cualquier modo de visualización.
- El botón de conversión principal de la tarjeta recomendada utiliza `bg-white text-blue-600 hover:bg-slate-100 font-black`, garantizando máxima notoriedad y ergonomía táctil ($56\text{px}$ de altura).

---

## 3. Cohesión Arquitectónica y Principios SOLID (Escuadrón NEXO-PRO)

1. **Responsabilidad Única (SRP):**
   - El organismo `NexoPricingGrid.vue` orquesta exclusivamente la disposición responsiva y el renderizado de planes comerciales.
   - Las moléculas y átomos reutilizan sus responsabilidades de botón, badge y skeleton sin acoplamiento a la vista contenedora.
2. **Prevención de Cumulative Layout Shift (CLS):**
   - Reserva de espacio mediante contenedores elásticos con altura mínima fija (`min-h-`) en descripciones de planes y estados de carga (`NexoSkeleton`).
3. **Cohesión de Datos y Servicios (DB & Backend):**
   - Las tarifas de planes modeladas en `finance.planes` y `app/Models/Plan.php` se mapean de forma unívoca a la interfaz sin pérdida de precisión ni discrepancias entre facturación anual y mensual.

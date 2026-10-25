# Especificación Integral de UX/UI y Tokens Adaptativos para los 4 Entornos de NEXO PRO
**Proyecto:** NEXO-PRO • Solución Enterprise Multi-Inquilino de Propiedad Horizontal  
**Escuadrón Responsable:** `Market Researcher`, `Product Owner`, `Architect`, `SOLID Validator`, `DB Dev`, `Backend Dev`, `Frontend Dev`, `Senior Reviewer`  
**Fecha:** 25 de Octubre de 2026  
**Documentación:** Bifurcada (`[LÓGICA DE NEGOCIO / UX]` & `[ASPECTO TÉCNICO / UI]`)

---

## 1. Arquitectura de los 4 Entornos Independientes

El ecosistema NEXO PRO atiende a 4 arquetipos de usuario con necesidades cognitivas, densidades de información y metas comerciales diametralmente distintas. A cada entorno le corresponde un perfil de diseño adaptativo:

```
┌────────────────────────────────────────────────────────────────────────────────────────┐
│                        MAPA DE LOS 4 ENTORNOS DE NEXO PRO                              │
├─────────────────────┬───────────────────┬──────────────────────┬───────────────────────┤
│ 1. LANDING PAGE     │ 2. SUPERADMIN     │ 3. ADMINISTRADOR     │ 4. PROPIETARIO        │
│ (Marketing & Conv.) │ (Consola Infra.)  │ (Gestión Operativa)  │ (Dashboard Ejecutivo) │
├─────────────────────┼───────────────────┼──────────────────────┼───────────────────────┤
│ • Conversión B2B    │ • Alta densidad   │ • Cartera y expensas │ • Carga cognitiva baja│
│ • Gradientes vivos  │ • Tipografía Mono │ • Asambleas y Quórum │ • Paz y Salvo / Saldo │
│ • Touch CTAs >= 52px│ • Health de Nodos │ • Reportes y CRUDs   │ • Pago 1-Clic Wompi   │
│ • Grillas fluidas   │ • Auditoría logs  │ • Zonas y PQRS       │ • Voto en vivo        │
└─────────────────────┴───────────────────┴──────────────────────┴───────────────────────┘
```

---

## 2. Matriz Comparativa de Diseño por Entorno

| Dimensión | 1. Landing Page | 2. Superadmin | 3. Administrador | 4. Propietario (Owner) |
| :--- | :--- | :--- | :--- | :--- |
| **Audiencia Primaria** | Miembros de Consejo, Administradores prospecto | Equipo de Operaciones, DevOps, Directores | Administradores de Edificio, Contadores | Propietarios de Inmuebles, Residentes |
| **Meta Principal** | Conversión, registro, solicitud de demo | Supervisión de infraestructura, estabilidad multi-tenant | Cobro oportuno, gestión de asambleas y soporte vecinal | Pago ágil, transparencia contable y voto en asamblea |
| **Densidad de Información** | **Baja - Estética** (Amplio espacio en blanco, tipografía grande) | **Muy Alta - Técnica** (Tablas compactas, monospaced logs, micro-badges) | **Alta - Operativa** (Filtros masivos, tablas densas, estados de cartera) | **Baja - Ejecutiva** (Tarjetas claras, cifras consolidadas, 1-clic CTA) |
| **Rutas Clave** | `/`, `/demo/request`, `/#precios` | `/super-admin/stats`, `/super-admin/licenses`, `/super-admin/logs` | `/dashboard`, `/cartera`, `/admin/asambleas`, `/pqrs` | `/portal`, `/portal/payments`, `/asamblea/{asamblea}` |
| **Control de Acceso (Gate)** | Público (Guest / Todo usuario) | Middleware `super-admin` | Middleware `ensure-admin`, `check-license` | Middleware `ensure-owner`, `check-license` |
| **Paleta de Acento** | Gradiente Azul-Índigo `#2563EB` a `#4F46E5` | Cian Eléctrico `#06B6D4` / Slate-950 | Azul Real `#2563EB` / Índigo `#4F46E5` | Esmeralda `#10B981` (Solvencia) / Azul `#2563EB` |
| **Ratio de Contraste** | $> 4.6:1$ (WCAG AA) | $> 7.0:1$ (WCAG AAA) | $> 4.5:1$ (WCAG AA) | $> 4.8:1$ (WCAG AA) |

---

## 3. Especificación de Tokens Adaptativos de Color (Light & Dark)

Se preserva estrictamente la paleta fundacional *Nexo Steel & Indigo Evolution*, derivando contextos semánticos específicos:

### 3.1 Entorno 1: Landing Page (`landing`)
- **Fondo:** `#F4F7FA` (Light) / `#080A0F` (Dark).
- **Superficie de Tarjetas:** `#FFFFFF` (Light) / `#0F172A` (Dark).
- **Hero & Acentos:** Gradiente `linear-gradient(135deg, #2563EB 0%, #4F46E5 100%)`.
- **Efecto Visual:** Resplandor de acento (*glow*) `0 4px 25px -5px rgba(37, 99, 235, 0.12)`.

### 3.2 Entorno 2: Superadmin Console (`superadmin`)
- **Fondo:** `#F1F5F9` (Light) / `#020617` (Dark ultra-profundo).
- **Superficie de Monitoreo:** `#FFFFFF` (Light) / `#0A0F1D` (Dark) con bordes finos `#1E293B`.
- **Tipografía de Datos:** `JetBrains Mono` / `ui-monospace` para IPs, UUIDs y logs.
- **Indicadores de Estado:** Healthy (`#10B981`), Warning (`#F59E0B`), Degraded (`#EF4444`).

### 3.3 Entorno 3: Administrador Operativo (`admin`)
- **Fondo:** `#F8FAFC` (Light) / `#0B0F19` (Dark).
- **Superficie de Módulos:** `#FFFFFF` (Light) / `#111827` (Dark).
- **Contraste de Tablas:** Separadores nítidos `#E2E8F0` (Light) / `#1F2937` (Dark).
- **Tokens de Cartera:** Cartera al día (`emerald-600`), Cartera en mora (`red-600`), En gestión (`amber-600`).

### 3.4 Entorno 4: Propietario Ejecutivo (`owner`)
- **Fondo:** `#F4F7FA` (Light) / `#030712` (Dark).
- **Superficie Principal:** `#FFFFFF` (Light) / `#0F172A` (Dark) con bordes suaves `rounded-2xl`.
- **Tokens de Paz y Salvo:** Esmeralda armónico `#10B981` (Light) / `#34D399` (Dark) con fondo translúcido.
- **Botón de Recaudo:** Azul Primario `#2563EB` con altura mínima de $52\text{px}$ para evitar pulsaciones erróneas.

---

## 4. Cohesión de Permisos, Rutas y Ciclo de Vida de Datos

```
[HTTP Request]
       │
       ▼
[HandleInertiaRequests] ──► Inyecta: auth.user, currentCopropiedad, permissions, theme
       │
       ├─► Si URL == '/'                   ──► Entorno: LANDING
       ├─► Si Middleware == 'super-admin'  ──► Entorno: SUPERADMIN (Sin tenant context forzado en lectura)
       ├─► Si Middleware == 'ensure-admin' ──► Entorno: ADMIN (Requiere FailClosedTenancyGuard activo)
       └─► Si Middleware == 'ensure-owner' ──► Entorno: OWNER (Restringido a unidades del usuario logueado)
```

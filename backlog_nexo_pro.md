# BACKLOG - NEXO-PRO

## MÓDULO 1: WEB INFORMATIVA PÚBLICA (WI)

### WI-WI01-001: Implementación de Hero Section y Propuesta de Valor

**Tipo:** Funcional
**Épica:** Landing Page y Marketing
**Prioridad:** Alta
**Puntos de historia (SP):** 3
**Módulo:** Web Informativa

**Como** visitante del sitio web,
**quiero** visualizar una página de inicio con una sección Hero impactante y una propuesta de valor clara,
**para** entender rápidamente los beneficios de NEXO-PRO y solicitar una demostración.

**Criterios de aceptación:**
- [x] CA1: La Hero Section debe incluir el título principal "PH360 – Gestión inteligente de tu copropiedad" y el subtítulo definido en el diseño de referencia.
- [x] CA2: Debe incluir dos botones de llamada a la acción (CTA): "Solicitar demo" (destacado) y "Ver planes".
- [x] CA3: La interfaz debe utilizar los tokens de diseño (colores y tipografía Inter) del sistema de diseño PH360 (`diseño/landing_page_ph360/code.html`).
- [x] CA4: El diseño debe ser 100% responsive, adaptándose a dispositivos móviles y desktops con el grid de Tailwind.

**Notas técnicas (Laravel 13 + Vue.js):**
- Implementar componente Vue `HeroSection.vue` usando Composition API.
- Usar el sistema de colores definido en el CSS central (M3 tokens).
- Integrar la imagen del mockup del dashboard (`screen.png`) con efecto de sombra y rotación sutil.
- Rutas gestionadas por Laravel con Inertia.js para una experiencia SPA.

**Consideraciones de seguridad:**
- Sanitización de cualquier contenido dinámico inyectado en el componente Vue para prevenir XSS (aunque Vue lo hace por defecto, se debe evitar `v-html`).
- Implementar política de seguridad de contenido (CSP) que permita únicamente los recursos necesarios (Google Fonts, etc.).

---

### WI-WI01-002: Catálogo de Características del Producto con Animaciones

**Tipo:** Funcional
**Épica:** Landing Page y Marketing
**Prioridad:** Media
**Puntos de historia (SP):** 5
**Módulo:** Web Informativa

**Como** administrador o residente interesado,
**quiero** explorar una sección de características detalladas con animaciones fluidas,
**para** comprender las funcionalidades técnicas de la plataforma de manera interactiva.

**Criterios de aceptación:**
- [x] CA1: Mostrar al menos 3 tarjetas de características: Recaudo Automatizado, Asambleas Virtuales y Mantenimiento Predictivo.
- [x] CA2: Cada tarjeta debe incluir un icono de Material Symbols Outlined y un hover effect que eleve la tarjeta (scale o translate).
- [x] CA3: Implementar animaciones de entrada al hacer scroll usando Vue Transitions o GSAP.
- [x] CA4: El contenido debe ser administrable desde el backend (opcionalmente) o estar definido en un archivo de configuración de marketing.

**Notas técnicas (Laravel 13 + Vue.js):**
- Componente `FeatureCard.vue` con props para icono, título y descripción.
- Uso de `Intersection Observer API` para disparar las animaciones de entrada.
- Estalinización basada en las clases `bg-surface-container-lowest` y `group hover:translate-y-[-8px]` del diseño de referencia.

**Consideraciones de seguridad:**
- Validar que los iconos cargados no provengan de fuentes externas no autorizadas (CSP strict).

---

### WI-WI01-003: Página de Precios con Toggle de Suspensión

**Tipo:** Funcional
**Épica:** Landing Page y Marketing
**Prioridad:** Alta
**Puntos de historia (SP):** 5
**Módulo:** Web Informativa

**Como** cliente potencial,
**quiero** comparar los planes de precios y alternar entre facturación mensual y anual,
**para** elegir la opción que mejor se adapte al presupuesto de mi copropiedad.

**Criterios de aceptación:**
- [x] CA1: Mostrar 3 planes: Basic, Pro (destacado como recomendado) y Enterprise.
- [x] CA2: Incluir un toggle funcional para cambiar entre precios mensuales y anuales (con un descuento visible del 20% en el plan anual).
- [x] CA3: El plan "Pro" debe tener el distintivo "Recomendado" y el gradiente `cta-gradient`.
- [x] CA4: Cada plan debe listar al menos 3 beneficios específicos con checks de color `secondary`.

**Notas técnicas (Laravel 13 + Vue.js):**
- Usar estado reactivo en Vue (`ref` o `reactive`) para controlar el toggle de anual/mensual.
- Los precios deben formatearse según la moneda local (COP) usando `Intl.NumberFormat`.
- Integración con base de datos para obtener los precios dinámicos si se requiere escalabilidad.

**Consideraciones de seguridad:**
- Protección contra manipulación de parámetros en el frontend (el precio final se debe validar siempre en el backend al momento de la suscripción).

---

### WI-WI01-004: Formulario de Solicitud de Demo con Validación en Tiempo Real

**Tipo:** Funcional
**Épica:** Landing Page y Marketing
**Prioridad:** Alta
**Puntos de historia (SP):** 3
**Módulo:** Web Informativa

**Como** representante de una copropiedad,
**quiero** completar un formulario de contacto para solicitar una demostración,
**para** que el equipo de ventas me contacte y me muestre el producto.

**Criterios de aceptación:**
- [x] CA1: Campos requeridos: Nombre completo, Correo electrónico (formato válido), Teléfono y Nombre del edificio.
- [x] CA2: Validación en tiempo real (lado cliente con Vue y lado servidor con Laravel Form Requests).
- [x] CA3: Implementar un checkbox de "No soy un robot" (Honeypot o reCAPTCHA v3).
- [x] CA4: Mostrar mensaje de éxito o error sin recargar la página (usando Axios o Inertia forms).

**Notas técnicas (Laravel 13 + Vue.js):**
- Controlador `MarketingContactController` con método `store`.
- Envío de notificación por correo usando `Mail::to()->send(new DemoRequested($data))`.
- Uso del objeto `useForm` de Inertia para manejo de errores y estados de carga.

**Consideraciones de seguridad:**
- **Rate Limiting**: Aplicar middleware `throttle:3,1` (3 solicitudes por minuto por IP).
- **Anti-spam**: Implementar Honeypot field invisible para usuarios pero visible para bots.
- **CSRF**: Verificación obligatoria del token en la petición POST.

---

### WI-WI02-001: Registro de Nueva Copropiedad (Wizard Multi-step)

**Tipo:** Funcional
**Épica:** Onboarding y Registro
**Prioridad:** Alta
**Puntos de historia (SP):** 8
**Módulo:** Web Informativa

**Como** administrador que desea adquirir el servicio,
**quiero** registrar mi copropiedad a través de un asistente de varios pasos,
**para** configurar mi cuenta y empezar a usar la plataforma inmediatamente.

**Criterios de aceptación:**
- [x] CA1: Paso 1: Datos de la copropiedad (NIT, Nombre, Dirección, Ciudad).
- [x] CA2: Paso 2: Selección de plan y configuración de unidades (número de torres, apartamentos).
- [x] CA3: Paso 3: Datos del primer administrador (Nombre, Email, Teléfono, Contraseña).
- [x] CA4: Persistencia de datos parciales (si el usuario abandona el flujo y regresa).

**Notas técnicas (Laravel 13 + Vue.js):**
- Implementar patrón `StepWizard` en Vue.
- Backend: Procesar el registro mediante un `Action` (e.g., `RegisterCopropiedadAction`) para seguir SOLID.
- Uso de `Database Transactions` para asegurar que el registro de la copropiedad, el administrador y el plan se creen atómicamente.

**Consideraciones de seguridad:**
- Validación fuerte de contraseñas (min 12 caracteres, símbolos, números).
- Verificación de correo electrónico obligatoria tras el registro (Laravel Email Verification).
- Escapar todas las entradas del NIT y datos legales contra inyección SQL.

---

## MÓDULO 2: MÓDULO ADMINISTRATIVO (ADM)

### ADM-ADM03-001: Integración con Pasarela Wompi (Pagos en Línea)

**Prioridad:** Alta | **SP:** 8
**Como** administrador, **quiero** que los residentes puedan pagar sus cuotas en línea, **para** reducir la mora y automatizar la conciliación.

- [ ] CA1: Integrar SDK/API de Wompi para generar checkouts.
- [ ] CA2: Procesar webhooks de Wompi de forma idempotente.
- [ ] CA3: Actualizar el estado de la transacción a "Pagado" automáticamente tras confirmación.

### ADM-ADM03-002: Conciliación Bancaria Automática (CSV/OFX)

**Prioridad:** Media | **SP:** 13
**Como** administrador, **quiero** subir un extracto bancario y que el sistema cruce los pagos automáticamente, **para** ahorrar horas de trabajo manual.

- [ ] CA1: Parser de archivos CSV y OFX de bancos principales.
- [ ] CA2: Algoritmo de matching por monto, fecha y referencia.
- [ ] CA3: Interfaz para resolver conflictos de conciliación manualmente.

### ADM-ADM06-001: Módulo de Asambleas Virtuales (Votaciones Live)

**Prioridad:** Alta | **SP:** 13
**Como** copropietario, **quiero** votar en tiempo real durante la asamblea desde mi móvil, **para** participar democráticamente sin salir de casa.

- [ ] CA1: Uso de WebSockets (Laravel Reverb) para votaciones instantáneas.
- [ ] CA2: Cálculo de quórum automático basado en coeficientes.
- [ ] CA3: Generación de acta digital firmada al finalizar la sesión.

---

## MÓDULO 3: MÓDULO DE PROPIETARIOS / RESIDENTES (PROP)

### PROP-PROP04-001: Chatbot de IA para Consultas de Residentes (RAG)

**Prioridad:** Media | **SP:** 8
**Como** residente, **quiero** preguntar dudas al chatbot (horarios, saldos, reglamento), **para** obtener respuestas inmediatas sin llamar a la administración.

- [ ] CA1: Implementar RAG con OpenAI API e indexar el reglamento de la copropiedad.
- [ ] CA2: Streaming de respuestas en la interfaz del portal.
- [ ] CA3: Fallback a apertura de PQRS si la IA no conoce la respuesta.

---

## MÓDULO 4: ASAMBLEA VIRTUAL (AV) [NUEVO]

### AV-AV01-001: Sincronización de Interfaz (Voto + Video)
**Prioridad:** Alta | **SP:** 5
**Como** Administrador, **quiero** lanzar una votación sin que el video se interrumpa, **para** que los residentes voten mientras escuchan la explicación.
- [ ] CA1: La votación aparece como overlay/panel lateral sobre el video WebRTC.
- [ ] CA2: El audio del moderador no se interrumpe durante la votación.
- [ ] CA3: Sincronización vía WebSockets (Laravel Reverb) para disparo de eventos UI.

### AV-AV01-002: Gestión de Moderación y Palabra
**Prioridad:** Alta | **SP:** 8
**Como** Administrador, **quiero** gestionar los micrófonos y cámaras de los residentes, **para** mantener el orden de la asamblea.
- [ ] CA1: Sistema de "Levantar la mano" integrado con la lista de unidades.
- [ ] CA2: Moderador habilita/deshabilita tracks de audio/video remotamente.
- [ ] CA3: Resaltado visual del orador activo en el layout.

### AV-AV01-003: Grabación y Evidencia Legal
**Prioridad:** Media | **SP:** 5
**Como** Administrador, **quiero** grabar la sesión y vincularla al acta, **para** tener respaldo legal ante impugnaciones.
- [ ] CA1: Grabación en servidor (SFU) iniciada automáticamente al abrir la asamblea.
- [ ] CA2: Generación de enlace de descarga seguro vinculado al reporte PDF de votaciones.

### AV-AV01-004: Autenticación Única (SSO) y Dispositivo Único
**Prioridad:** Alta | **SP:** 3
**Como** Administrador, **quiero** asegurar acceso automático y evitar duplicidad de votos, **para** garantizar la integridad del proceso.
- [ ] CA1: Validación de acceso mediante token JWT de la sesión de NEXO-PRO.
- [ ] CA2: Control de concurrencia: solo un dispositivo activo por unidad_id.
- [ ] CA3: Display Name automático basado en la unidad (ej: "Torre A - 501").

### AV-AV01-005: Activación por Super Admin (Módulo Add-on)
**Prioridad:** Bloqueante | **SP:** 3
**Como** Super Admin, **quiero** activar/inactivar el módulo por copropiedad, **para** gestionar el cobro adicional del servicio.
- [x] CA1: Toggle `asamblea_virtual_active` en el panel de gestión de copropiedades.
- [x] CA2: Middleware 403 si el módulo está inactivo para el conjunto.
- [x] CA3: Registro de auditoría obligatorio para todas las acciones de asamblea.


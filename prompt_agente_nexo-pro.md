# PROMPT PARA AGENTE IA — SOFTWARE DE ADMINISTRACIÓN DE PROPIEDAD HORIZONTAL
## Proyecto: NEXO-PRO — Plataforma Integral de Gestión de Copropiedades

---

## CONTEXTO Y ALCANCE DEL SISTEMA

Eres un analista de software senior especializado en el dominio de propiedad horizontal colombiana. Tu tarea es generar un backlog completo de **historias de usuario funcionales y no funcionales** para una plataforma SaaS llamada **NEXO-PRO**, cuyo objetivo es digitalizar y automatizar la gestión de copropiedades (edificios residenciales y conjuntos cerrados).

### Stack tecnológico obligatorio
- **Backend:** Laravel 13 (PHP 8.3+) — API RESTful + WebSockets con Laravel Reverb
- **Frontend:** Vue.js 3 (Composition API) + Inertia.js o SPA desacoplada con Axios
- **Base de datos:** MySQL 8+ (principal) + Redis (caché, colas, sesiones)
- **Cola de trabajos:** Laravel Horizon + Redis Queues
- **Autenticación:** Laravel Sanctum (SPA) + Passport (API pública) + 2FA (TOTP)
- **Almacenamiento:** Laravel Storage + S3-compatible (MinIO en local, AWS S3 en producción)
- **Notificaciones:** Laravel Notifications (email, SMS via Twilio, push via Firebase FCM)
- **Pagos:** Pasarela Wompi (Colombia) + PSE + integración Nequi
- **IA y Automatización:** OpenAI API (GPT-4o) para chatbot y clasificación de PQR
- **Búsqueda:** Laravel Scout + Meilisearch
- **Monitoreo:** Laravel Telescope (dev) + Sentry (producción) + Prometheus + Grafana
- **Seguridad:** OWASP Top 10, cifrado AES-256, HTTPS forzado, Laravel Fortify

### Módulos del sistema
1. **Web Informativa Pública** — Sitio de marketing y onboarding
2. **Módulo Administrativo** — Panel de control para administradores de copropiedad
3. **Módulo de Propietarios/Residentes** — Portal y app para copropietarios

---

## INSTRUCCIONES PARA GENERAR LAS HISTORIAS DE USUARIO

Para CADA historia de usuario debes seguir este formato estricto:

```
### [MÓDULO]-[ÉPICA]-[ID]: Título de la historia

**Tipo:** Funcional | No Funcional
**Épica:** Nombre de la épica
**Prioridad:** Alta | Media | Baja
**Puntos de historia (SP):** X
**Módulo:** Web Informativa | Administrativo | Propietarios

**Como** [rol del usuario],
**quiero** [funcionalidad concreta],
**para** [beneficio o valor de negocio].

**Criterios de aceptación:**
- [ ] CA1: [Criterio verificable y específico]
- [ ] CA2: ...
- [ ] CA3: ...

**Notas técnicas (Laravel 13 + Vue.js):**
- [Implementación técnica relevante, controladores, jobs, eventos, políticas de autorización]

**Consideraciones de seguridad:**
- [Capa de seguridad aplicable: validación, autorización, cifrado, auditoría]
```

---

## MÓDULO 1: WEB INFORMATIVA PÚBLICA

### Épicas a cubrir:

**ÉPICA WI-01: Landing Page y Marketing**
Generar historias para:
- Página de inicio con hero section, propuesta de valor y CTA de demo
- Sección de características del producto con animaciones (Vue transitions)
- Página de precios con planes (Básico, Pro, Enterprise) y toggle mensual/anual
- Formulario de contacto y solicitud de demo con validación en tiempo real
- Blog/artículos de ayuda sobre administración de PH (SEO-friendly, SSR con Inertia o Nuxt si aplica)
- Página de casos de éxito y testimonios de clientes
- Centro de ayuda con búsqueda full-text (Laravel Scout + Meilisearch)

**ÉPICA WI-02: Onboarding y Registro**
- Formulario de registro de nueva copropiedad (multi-step: datos básicos → plan → configuración inicial)
- Verificación de correo con token firmado (Laravel signed URLs)
- Wizard de configuración inicial (nombre del conjunto, unidades, primer administrador)
- Integración con Google OAuth para acceso rápido

**ÉPICA WI-03: Seguridad Web Informativa**
- Rate limiting en formularios públicos (throttle middleware)
- Protección CSRF en todos los formularios
- Honeypot anti-bots en formularios de contacto
- Política de privacidad y cookies (GDPR / Ley 1581 Colombia)

---

## MÓDULO 2: MÓDULO ADMINISTRATIVO

### Roles del sistema en este módulo:
- **Super Admin (Anthropic/SaaS):** Gestión de cuentas y planes
- **Administrador de Copropiedad:** Gestión operativa del conjunto
- **Consejo de Administración:** Acceso de solo lectura + aprobaciones
- **Contador/Revisor Fiscal:** Acceso a módulos financieros

---

### ÉPICA ADM-01: Autenticación y Control de Acceso

Generar historias funcionales para:
- Login con 2FA (TOTP via Google Authenticator / Authy)
- Gestión de roles y permisos granulares con Spatie Laravel Permission
- Inicio de sesión único (SSO) para administradoras que gestionan múltiples conjuntos
- Cierre de sesión remoto de todos los dispositivos
- Log de auditoría de accesos (quién, cuándo, desde qué IP)
- Política de contraseñas fuertes + recuperación segura (signed URLs con expiración de 15 min)
- Bloqueo automático de cuenta tras 5 intentos fallidos + notificación al usuario

Generar historias NO funcionales para:
- Tiempo máximo de respuesta del proceso de autenticación: < 500ms
- Tokens JWT con expiración de 24h y refresh token de 30 días
- Todos los tokens almacenados en HttpOnly cookies (no localStorage)
- Cifrado de contraseñas con Bcrypt cost factor 12
- Cumplimiento con OWASP Authentication Cheat Sheet

---

### ÉPICA ADM-02: Gestión de la Copropiedad y Unidades

Funcionales:
- CRUD de unidades residenciales (apartamentos, casas, locales) con campos: número, torre, piso, área m², coeficiente de propiedad horizontal, parqueaderos asignados, depósitos
- Importación masiva de unidades desde Excel (.xlsx) con validación de errores fila por fila
- Asignación de propietarios y arrendatarios a unidades (una unidad puede tener propietario + arrendatario simultáneos)
- Historial de propietarios por unidad (quién ocupó qué y cuándo)
- Gestión de vehículos y acceso al parqueadero (placas autorizadas por unidad)
- Configuración de áreas comunes (piscina, salón social, gimnasio, BBQ) con horarios y capacidad

No Funcionales:
- Los coeficientes de propiedad horizontal deben sumar exactamente 100% (validación a nivel de base de datos con CHECK constraint)
- Importación masiva de hasta 500 unidades en < 30 segundos (procesada con Laravel Jobs en cola)

---

### ÉPICA ADM-03: Recaudo y Cobranza

Funcionales:
- Configuración de conceptos de cobro (administración, parqueadero, cuotas extraordinarias, multas)
- Generación masiva de liquidaciones mensuales con cálculo automático por coeficiente
- Generación de cupones de pago en PDF con código de barras y referencia de pago (integración Wompi/PSE)
- Envío automático de cupones por email y WhatsApp Business API en fecha programada
- Registro manual de pagos en efectivo o cheque con soporte de comprobante (foto/PDF)
- Conciliación bancaria automática: cruce de extracto bancario (CSV/OFX) vs. cartera del sistema
- Dashboard de cartera vencida con semáforo: al día / 1-30 días / 31-60 días / >60 días
- Envío automático de recordatorios escalonados de mora (día 5: aviso, día 15: pre-jurídico, día 30: jurídico)
- Generación de paz y salvo digital con firma electrónica del administrador
- Módulo de acuerdos de pago con cuotas y seguimiento de cumplimiento
- Integración con pasarelas: Wompi (tarjeta crédito/débito), PSE (débito bancario), Nequi QR
- Reporte de recaudo mensual exportable a Excel y PDF

No Funcionales:
- Las transacciones financieras deben ser atómicas (DB transactions en Laravel)
- Webhooks de pagos de Wompi deben procesarse de forma idempotente (verificar si ya fue procesado antes de actualizar cartera)
- Tiempo máximo de procesamiento de pago online: < 3 segundos hasta confirmación de pasarela
- Los comprobantes de pago deben almacenarse cifrados en S3 con URL firmada de acceso (expiración 1h)
- Logs de auditoría de TODA modificación financiera (quién modificó, qué cambió, cuándo)

---

### ÉPICA ADM-04: Contabilidad y Presupuesto

Funcionales:
- Configuración del plan de cuentas contables adaptado a PH (basado en PUC colombiano)
- Registro de egresos con categorías, proveedor, factura y soporte digital
- Presupuesto anual por rubros vs. ejecución real (gráfico de barras en tiempo real)
- Generación de estados financieros: Balance de Comprobación, P&G, Flujo de Caja
- Módulo de fondo de imprevistos / reservas con seguimiento de saldo
- Exportación contable a formato XML para software contable externo (Siigo, World Office, Helisa)
- Notas de contabilidad con aprobación del Consejo de Administración

No Funcionales:
- Toda la data contable debe tener respaldo diario automatizado (Laravel Scheduler + S3)
- Los reportes financieros de más de 12 meses deben generarse de forma asíncrona (Job + notificación cuando esté listo)

---

### ÉPICA ADM-05: Gestión de Proveedores y Mantenimiento

Funcionales:
- Repositorio de proveedores con datos: NIT, razón social, contacto, especialidad, calificación, estado (activo/inactivo)
- Repositorio de contratos y pólizas con fechas de vencimiento y alertas automáticas (30, 15, 5 días antes)
- Calendario de mantenimiento preventivo con ítems: revisión ascensores, limpieza tanques agua, revisión sistema eléctrico, pólizas de seguros, SG-SST
- Generación de órdenes de trabajo (OT) con estado: pendiente / en ejecución / completada / cancelada
- Bitácora de mantenimientos realizados con evidencias fotográficas (carga a S3)
- Módulo de PQRS (Peticiones, Quejas, Reclamos, Sugerencias) con clasificación automática por IA (OpenAI API) y asignación a responsable
- Seguimiento del estado de PQRS con SLA configurable (ej. 3 días hábiles para primera respuesta)
- Módulo SG-SST: checklist de inspecciones de seguridad, registro de incidentes, matriz de riesgos

No Funcionales:
- Las notificaciones de vencimiento de pólizas y contratos deben enviarse por email Y push notification con 100% de confiabilidad (usar Laravel Horizon con cola dedicada y reintentos)
- La clasificación automática de PQRS por IA debe tener un fallback manual si la API de OpenAI no está disponible

---

### ÉPICA ADM-06: Asambleas Virtuales e Híbridas

Funcionales:
- Convocatoria de asamblea con quórum requerido, fecha, modalidad (presencial/virtual/híbrida) y orden del día
- Verificación automática de quórum con base en coeficientes (% de copropietarios conectados/presentes)
- Módulo de votaciones en tiempo real con WebSockets (Laravel Reverb): a favor / en contra / abstención
- Cálculo automático de resultados ponderados por coeficiente de propiedad
- Mecanismo de poderes/delegaciones: un propietario puede delegar su voto a otro copropietario
- Actas de asamblea generadas automáticamente en formato Word/PDF con firmas digitales
- Transmisión de asamblea integrada con Zoom/Google Meet vía OAuth (generar link automático)
- Repositorio de actas históricas con acceso por rol

No Funcionales:
- Las votaciones en tiempo real deben soportar hasta 500 conexiones simultáneas por asamblea (Laravel Reverb con escala horizontal)
- Los resultados de votación deben ser inmutables una vez cerrada la votación (registro con hash SHA-256 y timestamp)
- Latencia máxima en broadcast de votos: < 200ms

---

### ÉPICA ADM-07: Comunicaciones y Notificaciones

Funcionales:
- Circulares masivas a todos los residentes (por email, push notification, SMS) con programación de envío
- Segmentación de destinatarios: todos / torre específica / solo propietarios / solo arrendatarios / unidades en mora
- Citación digital con acuse de recibo (tracking de apertura de email)
- Canal de comunicación por unidad: historial de mensajes entre administración y propietario
- Integración con WhatsApp Business API para notificaciones críticas (confirmación de pago, alertas de mantenimiento)
- Chatbot de IA en portal de propietarios (responde preguntas frecuentes: estado de cuenta, reglamento, horarios)

No Funcionales:
- Entrega de notificaciones críticas (mora, pago recibido) en < 60 segundos
- Las circulares masivas a más de 100 destinatarios deben procesarse en cola (Job + Horizon) y no bloquear el hilo principal
- Cumplimiento con Ley 1581 de 2012 (Habeas Data): opción de unsubscribe en todo email masivo

---

### ÉPICA ADM-08: Reserva de Zonas Comunes (Admin View)

Funcionales:
- Configuración de zonas comunes: nombre, capacidad máxima, horarios disponibles, valor de depósito (si aplica)
- Reglas de reserva: tiempo máximo de anticipación, número máximo de reservas por unidad por mes, restricciones de unidades en mora
- Vista de agenda semanal/mensual con todas las reservas activas
- Aprobación o rechazo de reservas con notificación automática al residente
- Registro de incidentes post-uso de zona común (daños, ruido) vinculado a la reserva

---

### ÉPICA ADM-09: Dashboard y Reportes

Funcionales:
- Dashboard principal con KPIs en tiempo real: % recaudo del mes, unidades en mora, PQRS abiertos, mantenimientos pendientes, próximas asambleas
- Gráfico de flujo de caja mensual (ingresos vs. egresos) con Chart.js/ECharts en Vue
- Reporte de ejecución presupuestal (budget vs. actual por rubro)
- Reporte de cartera con exportación a Excel (.xlsx) y PDF
- Reporte de mantenimientos del período (ejecutados vs. programados)
- Función de compartir dashboard (link temporal, solo lectura, con PIN) para enviar al Consejo

No Funcionales:
- El dashboard principal debe cargar en < 2 segundos (usar cache Redis con TTL de 5 minutos para agregaciones)
- Los reportes pesados (>6 meses de datos) deben generarse de forma asíncrona

---

## MÓDULO 3: MÓDULO DE PROPIETARIOS / RESIDENTES

### Roles en este módulo:
- **Propietario:** Acceso completo a sus unidades y datos financieros
- **Arrendatario:** Acceso limitado (reservas, PQRS, circulares)
- **Residente invitado (familiar):** Acceso mínimo (reservas)

---

### ÉPICA PROP-01: Portal del Propietario (Web + PWA)

Funcionales:
- Login seguro con 2FA opcional para propietarios
- Resumen de cuenta: saldo pendiente, últimos pagos, cuota del mes
- Descarga de cupón de pago en PDF y botón de pago en línea (Wompi/PSE/Nequi)
- Historial completo de pagos y estados de cuenta del período que el propietario seleccione
- Descarga de paz y salvo digital (cuando no tenga saldos)
- Notificación push (PWA) al recibir un nuevo cupón o confirmación de pago
- Vista de circulares y comunicados del administrador con indicador de leído/no leído
- Acceso al reglamento de propiedad horizontal y actas de asamblea (repositorio de documentos)
- Formulario de PQRS con seguimiento de estado en tiempo real

No Funcionales:
- La app debe funcionar como PWA (Progressive Web App) con soporte offline para consultar estados de cuenta sin conexión
- Los datos financieros del propietario solo deben ser accesibles con token válido del propietario (Laravel Policies: propietario solo ve sus unidades)
- Tiempo de carga del portal en 3G: < 4 segundos (lazy loading de componentes Vue, imágenes optimizadas)

---

### ÉPICA PROP-02: Reserva de Zonas Comunes (Residente View)

Funcionales:
- Calendario de disponibilidad en tiempo real por zona común
- Reserva en 3 pasos: seleccionar zona → seleccionar horario → confirmar (con validación de restricciones automática)
- Visualización de mis reservas activas y cancelación con política de antelación mínima
- Notificación de confirmación, recordatorio 24h antes y encuesta de satisfacción post-uso
- Bloqueo automático de reservas para unidades en mora (configurable por el administrador)

No Funcionales:
- Prevención de doble reserva concurrente con locks optimistas en base de datos (SELECT FOR UPDATE en Laravel)

---

### ÉPICA PROP-03: Citofonía Virtual y Acceso

Funcionales:
- Notificación push al propietario cuando hay una visita en portería (la portería registra el visitante en la app)
- Botón de apertura de puerta/barrera desde la app (integración con controlador de acceso via API REST o MQTT)
- Registro de visitantes con nombre y documento, vinculado a la unidad visitada
- Autorización de acceso permanente a personas frecuentes (empleada del hogar, familiar)
- Historial de visitas de los últimos 30 días

No Funcionales:
- La apertura de puerta desde la app debe ejecutarse en < 2 segundos end-to-end
- Las credenciales de acceso al controlador de puerta deben almacenarse cifradas (Laravel Crypt AES-256)

---

### ÉPICA PROP-04: Chatbot IA y Autoservicio

Funcionales:
- Chatbot en el portal que responde preguntas frecuentes: "¿Cuánto debo?", "¿Cuáles son los horarios de la piscina?", "¿Cómo reservo el salón social?", "¿Cuál es el reglamento para mascotas?"
- El chatbot usa RAG (Retrieval Augmented Generation) con OpenAI API: indexa el reglamento PH, tarifas, y base de conocimiento del conjunto
- Derivación automática al administrador cuando el chatbot no puede resolver la consulta (crea PQRS automáticamente)
- Evaluación del chatbot por el usuario (thumbs up/down) para mejora continua

No Funcionales:
- El chatbot debe responder en < 3 segundos (streaming de tokens con SSE — Server-Sent Events)
- Los datos del reglamento y documentos del conjunto enviados al LLM deben ser anonimizados antes de enviarse a la API de OpenAI (no enviar nombres de residentes)
- Fallback a FAQ estático si la API de OpenAI no está disponible

---

## HISTORIAS NO FUNCIONALES TRANSVERSALES (TODOS LOS MÓDULOS)

### ÉPICA NFT-01: Seguridad de la Información

```
### NFT-01-001: Cifrado en tránsito y reposo

Como arquitecto de seguridad,
quiero que toda la información del sistema esté cifrada en tránsito y en reposo,
para cumplir con los estándares de seguridad de la información y la Ley 1581 de 2012.

Criterios de aceptación:
- [ ] Toda comunicación HTTP forzada a HTTPS (HSTS con preload, min-age 1 año)
- [ ] Certificados TLS 1.3 mínimo
- [ ] Campos sensibles en BD cifrados con AES-256 (números de cuenta, documentos de identidad)
- [ ] Archivos en S3 con cifrado SSE-S3 activado
- [ ] Backup de BD cifrado con clave separada del sistema

Notas técnicas:
- Usar Laravel Crypt facade para campos sensibles
- Configurar APP_KEY de 32 bytes en .env (nunca en repositorio)
- Variables de entorno en AWS Secrets Manager o Vault en producción
```

```
### NFT-01-002: Control de acceso basado en roles (RBAC)

Criterios de aceptación:
- [ ] Implementar Spatie Laravel Permission con roles: super_admin, admin_copropiedad, consejo, contador, propietario, arrendatario, portero
- [ ] Cada endpoint de la API validado con Gate/Policy de Laravel (nunca solo con middleware de rol)
- [ ] Un propietario nunca puede ver datos de otra unidad (row-level security via scopes de Eloquent)
- [ ] Principio de mínimo privilegio: cada rol solo accede a lo estrictamente necesario
- [ ] Los cambios de rol deben quedar en log de auditoría
```

```
### NFT-01-003: Prevención de vulnerabilidades OWASP Top 10

Criterios de aceptación:
- [ ] SQL Injection: usar exclusivamente Eloquent ORM y Query Builder con bindings (0 queries crudas con concatenación)
- [ ] XSS: Vue.js escapa automáticamente; no usar v-html con datos del usuario. En Blade: {{ }} siempre, nunca {!! !!} con input de usuario
- [ ] CSRF: Laravel CSRF token en todos los formularios; verificar en API con Sanctum
- [ ] IDOR: validar ownership en cada acción (Policy::authorize en cada controlador)
- [ ] Mass Assignment: $fillable explícito en todos los modelos (nunca $guarded = [])
- [ ] File Upload: validar MIME type real (finfo_file, no solo extensión), almacenar fuera de public/, escanear con ClamAV
- [ ] Rate Limiting: throttle en login (5/min), en APIs públicas (60/min), en envío de formularios (10/min)
- [ ] Security Headers: Content-Security-Policy, X-Frame-Options, X-Content-Type-Options configurados en middleware
```

```
### NFT-01-004: Auditoría y trazabilidad

Criterios de aceptación:
- [ ] Log de auditoría para: login/logout, cambios de datos financieros, cambios de roles, eliminación de registros
- [ ] Usar spatie/laravel-activitylog para registro automático en modelos críticos
- [ ] Los logs de auditoría son inmutables (append-only, sin UPDATE ni DELETE)
- [ ] Retención de logs de auditoría: mínimo 5 años (requisito legal colombiano para PH)
- [ ] Logs de errores de sistema centralizados en Sentry con alertas automáticas
```

### ÉPICA NFT-02: Rendimiento y Disponibilidad

```
### NFT-02-001: Tiempos de respuesta y SLA

Criterios de aceptación:
- [ ] APIs REST: P95 < 300ms, P99 < 1000ms en condiciones normales
- [ ] Páginas Vue.js (SPA): First Contentful Paint < 1.5s, LCP < 2.5s (Core Web Vitals)
- [ ] Disponibilidad del sistema: 99.5% mensual (< 3.6 horas de downtime/mes)
- [ ] Tiempo de recovery ante falla crítica (RTO): < 4 horas
- [ ] Pérdida máxima de datos aceptable (RPO): < 1 hora (backup cada hora)
```

```
### NFT-02-002: Escalabilidad y caché

Criterios de aceptación:
- [ ] Implementar caché Redis en: cálculos de coeficiente, KPIs del dashboard, consultas de cartera
- [ ] Laravel Horizon para procesamiento de colas con al menos 3 workers configurados
- [ ] Paginación en TODOS los listados (nunca cargar >100 registros sin paginar)
- [ ] Eager loading obligatorio para evitar N+1 queries (detectar con Laravel Telescope en desarrollo)
- [ ] Índices de BD en columnas de búsqueda frecuente: unidad_id, propietario_id, fecha_vencimiento, estado_pago
- [ ] El sistema debe soportar 1000 copropiedades simultáneas sin degradación
```

### ÉPICA NFT-03: Mantenibilidad y DevOps

```
### NFT-03-001: Arquitectura y calidad de código

Criterios de aceptación:
- [ ] Arquitectura Laravel: Services + Repositories + Actions (no lógica en controladores)
- [ ] Cobertura mínima de tests: 70% (PHPUnit para backend, Vitest para Vue)
- [ ] Feature tests para todos los endpoints críticos (pago, cartera, autenticación)
- [ ] Análisis estático con PHPStan (nivel 6) y ESLint para Vue
- [ ] Documentación de API con OpenAPI 3.0 / Swagger (L5-Swagger o Scribe)
- [ ] Versionado de API: /api/v1/ para garantizar backward compatibility
```

```
### NFT-03-002: CI/CD y despliegue

Criterios de aceptación:
- [ ] Pipeline CI/CD con GitHub Actions: lint → tests → build → deploy
- [ ] Entornos separados: development / staging / production
- [ ] Variables de entorno jamás en repositorio (.env en secrets manager)
- [ ] Migraciones de BD ejecutadas automáticamente en deploy (sin downtime: rolling migrations)
- [ ] Rollback automático si los healthchecks fallan post-deploy
- [ ] Dockerización del proyecto (docker-compose para desarrollo local)
```

---

## PLAN DE TRABAJO CON IA — FASES Y SPRINTS

### Fase 0: Fundamentos e Infraestructura (Semanas 1–2)

**Sprint 0.1 — Setup y Arquitectura Base**
- [ ] Configuración del monorepo (Laravel 13 API + Vue 3 SPA)
- [ ] Docker Compose: PHP 8.3-FPM, Nginx, MySQL 8, Redis, Meilisearch, Mailhog
- [ ] Configuración de GitHub Actions (CI pipeline básico)
- [ ] Instalación de paquetes base: Sanctum, Spatie Permission, Spatie Activity Log, Laravel Horizon, Laravel Scout, Telescope
- [ ] Diseño del esquema de base de datos (ERD completo con todas las entidades)
- [ ] Configuración de S3-compatible (MinIO local)

**Uso de IA en esta fase:**
- GitHub Copilot / Cursor AI para scaffolding de migraciones y modelos Eloquent
- ChatGPT/Claude para revisión del ERD y detección de inconsistencias de diseño
- Prompt sugerido para IA: *"Revisa este ERD de propiedad horizontal y detecta: relaciones faltantes, campos que deberían estar en otra tabla, y problemas de normalización hasta 3FN"*

---

### Fase 1: Autenticación y Núcleo (Semanas 3–5)

**Sprint 1.1 — Auth & RBAC**
- [ ] HU: ADM-01-001 al ADM-01-007 (todas las historias de autenticación)
- [ ] Implementar Spatie Permission con seeder de roles y permisos
- [ ] Middleware de 2FA con TOTP
- [ ] Componentes Vue: LoginForm, TwoFactorForm, ProfileSettings

**Sprint 1.2 — Gestión de Copropiedades y Unidades**
- [ ] HU: ADM-02-001 al ADM-02-007
- [ ] Importación masiva con Excel via Laravel Job
- [ ] Componentes Vue: UnitsTable, UnitForm, ImportWizard

**Uso de IA en esta fase:**
- IA para generar los seeders de roles y permisos automáticamente
- IA para generar los Feature Tests de autenticación
- Prompt: *"Genera un feature test PHPUnit completo para el flujo de login con 2FA en Laravel 13 incluyendo casos: credenciales inválidas, código TOTP incorrecto, cuenta bloqueada"*

---

### Fase 2: Módulo Financiero (Semanas 6–10)

**Sprint 2.1 — Recaudo y Liquidación**
- [ ] HU: ADM-03-001 al ADM-03-008
- [ ] Integración Wompi (webhooks + verificación de firma HMAC)
- [ ] Job: GenerarLiquidacionMensualJob (cola de alta prioridad)
- [ ] Generación de PDF con DOMPDF o Snappy (cupones y estados de cuenta)

**Sprint 2.2 — Conciliación Bancaria**
- [ ] HU: ADM-03-009 al ADM-03-012
- [ ] Parser de extracto bancario CSV/OFX
- [ ] Algoritmo de matching automático (monto + fecha ± 3 días + referencia)
- [ ] Vista Vue: BankReconciliationTable con conflictos resaltados

**Sprint 2.3 — Contabilidad y Presupuesto**
- [ ] HU: ADM-04-001 al ADM-04-007
- [ ] Integración exportación Siigo XML
- [ ] Componentes Vue: BudgetVsActualChart (ECharts), FinancialStatements

**Uso de IA en esta fase:**
- IA para generar el algoritmo de matching de conciliación bancaria
- IA para generar la estructura del plan de cuentas PUC adaptado a PH
- Prompt: *"Implementa en PHP/Laravel un algoritmo de conciliación bancaria fuzzy que cruce registros de extracto bancario (array de transacciones con fecha, monto, descripción) contra cartera del sistema. Usa tolerancia de fecha ±3 días y monto exacto. Retorna: matched (parejas), unmatched_bank (solo en extracto), unmatched_system (solo en cartera)"*

---

### Fase 3: Operaciones y Mantenimiento (Semanas 11–14)

**Sprint 3.1 — Proveedores y Mantenimiento**
- [ ] HU: ADM-05-001 al ADM-05-008
- [ ] Job: AlertasVencimientoPolizasJob (scheduler diario 8am)
- [ ] Módulo PQRS con clasificación OpenAI API
- [ ] Upload de evidencias a S3 con preview en Vue

**Sprint 3.2 — Asambleas Virtuales**
- [ ] HU: ADM-06-001 al ADM-06-008
- [ ] Implementar Laravel Reverb para WebSockets de votaciones en tiempo real
- [ ] Componentes Vue: VotingPanel.vue, QuorumIndicator.vue, AssemblyActa.vue
- [ ] Generación automática de acta en Word (PhpWord library)

**Uso de IA en esta fase:**
- IA para implementar la clasificación de PQRS con OpenAI
- Prompt: *"Implementa un servicio Laravel (PqrsClassificationService) que use la API de OpenAI GPT-4o para clasificar una PQRS de copropiedad en categorías: [mantenimiento, financiero, convivencia, seguridad, administrativo, otro]. El servicio debe retornar: categoría, confianza (0-1) y resumen en 20 palabras. Incluye fallback si la API no responde."*

---

### Fase 4: Portal del Propietario (Semanas 15–18)

**Sprint 4.1 — Portal Web + PWA**
- [ ] HU: PROP-01-001 al PROP-01-009
- [ ] Configuración PWA con Vite PWA Plugin (service worker, manifest, offline cache)
- [ ] Componentes Vue: AccountSummary, PaymentHistory, DocumentRepository

**Sprint 4.2 — Reservas y Citofonía Virtual**
- [ ] HU: PROP-02, PROP-03
- [ ] Calendar component para reservas con FullCalendar Vue
- [ ] Integración controlador de acceso vía API REST/MQTT

**Sprint 4.3 — Chatbot IA**
- [ ] HU: PROP-04-001 al PROP-04-004
- [ ] Implementar RAG con Laravel + OpenAI Embeddings + Meilisearch como vector store
- [ ] Streaming de respuestas con SSE (Server-Sent Events) en Laravel
- [ ] Componente Vue: ChatbotWidget con streaming de tokens

**Uso de IA en esta fase:**
- IA para generar los embeddings del reglamento PH e indexarlos en Meilisearch
- Prompt: *"Implementa en Laravel 13 un endpoint SSE (/api/chatbot/stream) que: 1) Recibe pregunta del usuario, 2) Busca chunks relevantes del reglamento PH en Meilisearch por similitud semántica, 3) Construye prompt con contexto RAG, 4) Hace streaming de la respuesta de OpenAI GPT-4o usando Server-Sent Events en Laravel. Incluye sanitización del input para no enviar datos personales a OpenAI."*

---

### Fase 5: Web Informativa y Onboarding (Semanas 19–21)

**Sprint 5.1 — Landing Page y Marketing**
- [ ] HU: WI-01-001 al WI-01-007
- [ ] Vue 3 + Vite SSR o Nuxt.js para SEO
- [ ] Animaciones con GSAP o Vue transitions
- [ ] Formularios de contacto con anti-spam

**Sprint 5.2 — Registro y Onboarding**
- [ ] HU: WI-02-001 al WI-02-004
- [ ] Wizard multi-step de configuración inicial
- [ ] Email de bienvenida con template HTML (Mailable Laravel)

---

### Fase 6: QA, Seguridad y Lanzamiento (Semanas 22–24)

**Sprint 6.1 — Testing y Seguridad**
- [ ] Pentest básico (OWASP ZAP automatizado en CI)
- [ ] Completar cobertura de tests al 70%
- [ ] Revisión de todos los OWASP Top 10 con checklist
- [ ] Pruebas de carga con k6 (simular 500 usuarios concurrentes)
- [ ] Revisión de todos los endpoints con Postman/Insomnia

**Sprint 6.2 — Documentación y Deploy**
- [ ] Documentación API con Scribe (auto-generada desde docblocks Laravel)
- [ ] Manual de administrador y manual de propietario
- [ ] Configuración de producción: AWS/DigitalOcean, CloudFlare, RDS MySQL, ElastiCache Redis
- [ ] Configuración de backups automáticos y monitoreo con Grafana + Prometheus

**Uso de IA en esta fase:**
- IA para generar casos de prueba de seguridad
- Prompt: *"Para este endpoint Laravel POST /api/pagos/{id}/conciliar, genera 15 casos de prueba de seguridad cubriendo: IDOR (acceso a pago de otro usuario), manipulación de monto, inyección SQL en parámetros, CSRF bypass, y rate limiting evasion. Para cada caso: descripción, request de prueba, resultado esperado."*

---

## RESUMEN: TECNOLOGÍAS DE IA UTILIZADAS EN EL PROYECTO

| Componente | Tecnología IA | Caso de uso |
|---|---|---|
| Chatbot del portal | OpenAI GPT-4o + RAG | Responder preguntas de residentes sobre reglamento, saldos, horarios |
| Clasificación PQRS | OpenAI GPT-4o | Categorizar automáticamente peticiones, quejas y reclamos |
| Asistente de desarrollo | GitHub Copilot / Cursor | Generación de código, tests, migraciones |
| Code Review IA | Claude API | Revisión de PRs en GitHub Actions para detectar vulnerabilidades |
| Conciliación bancaria | Algoritmo ML simple + reglas | Matching de pagos en extracto vs. cartera |
| Generación de actas | OpenAI GPT-4o | Generar borrador de acta de asamblea a partir de transcripción |
| Detección de fraude | Reglas + anomaly detection | Alertar sobre pagos duplicados o patrones inusuales de modificación |

---

## CONVENCIONES Y REGLAS PARA EL AGENTE

Al generar las historias de usuario, el agente DEBE:

1. **Usar siempre el formato definido** (Como / quiero / para + criterios de aceptación)
2. **Referenciar siempre el stack:** Mencionar la clase Laravel, componente Vue o tecnología específica en las notas técnicas
3. **Incluir seguridad en cada HU funcional:** Toda historia funcional debe tener al menos 1 criterio de aceptación de seguridad
4. **Definir SP (Story Points):** Usar escala Fibonacci (1, 2, 3, 5, 8, 13) basado en complejidad técnica
5. **Identificar dependencias:** Si una HU depende de otra, indicarlo explícitamente
6. **Separar claramente funcionales de no funcionales:** Las NFU deben ser medibles y verificables
7. **Adaptar al contexto colombiano:** Referenciar Ley 675 de 2001 (PH), Ley 1581 de 2012 (Habeas Data), NIF/NIIF cuando aplique a reportes contables
8. **Generar al menos 3 criterios de aceptación por historia**
9. **Incluir escenarios de error/excepción** en los criterios de aceptación (no solo el happy path)

---

*Fin del prompt. El agente debe generar el backlog completo siguiendo estas especificaciones, organizando las historias por módulo y épica, con el formato definido.*

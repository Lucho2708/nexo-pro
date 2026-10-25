# Especificación de Requerimientos de Producto (PRD) — Sistema de Diseño y Conversión UX
**Proyecto:** NEXO-PRO • Solución Enterprise Multi-Inquilino de Propiedad Horizontal  
**Agente Responsable:** `NEXO-PRO-Product-Owner` (`~/.config/antigravity/squad/1_product_owner.yaml`)  
**Fecha:** 25 de Octubre de 2026  
**Insumo:** [`market_research.md`](file:///home/lsilva/Documentos/Laravel/nexo-pro/market_research.md)  
**Entregable Oficial:** `product_requirements.md`

---

## 1. Definición de Alcance y Alcance del MVP

### 1.1 Dentro del Alcance (In-Scope MVP)
- **Estructuración Formal de Design Tokens Semánticos:** Articular los tokens de color existentes (Nexo Steel & Indigo Evolution) en un catálogo TypeScript desacoplado con soporte nativo para Modo Claro (*Light*) y Modo Oscuro (*Dark*).
- **Biblioteca de Componentes Atómicos (Atomic Design) en `src/frontend/components/`:**
  - **Átomos:** Botón multi-variante accesible (`NexoButton.vue`), Insignias semánticas de estado (`NexoBadge.vue`), Campo de entrada con estados interactivos (`NexoInput.vue`).
  - **Moléculas:** Tarjeta de métricas con indicador de tendencia y skeleton (`NexoMetricCard.vue`), Cargador de esqueleto reactivo con brillo (*shimmer*) anti-CLS (`NexoSkeleton.vue`).
  - **Organismos:** Banner inteligente de alta conversión para recaudos o asambleas (`NexoConversionBanner.vue`).
- **Garantía de Accesibilidad WCAG 2.1 Nivel AA:** Ratio de contraste mínimo de $4.5:1$ en textos estándar y $3.0:1$ en elementos gráficos/UI en ambos modos.
- **Estados Interactivos Completos:** Default, Hover, Focus-Visible, Active/Pressed, Loading (Skeletons/Spinners) y Disabled con touch targets $\ge 44\times 44\text{px}$.
- **Documentación Bifurcada:** Todo componente debe documentarse bajo `[LÓGICA DE NEGOCIO / UX]` y `[ASPECTO TÉCNICO / UI]`.

### 1.2 Fuera del Alcance (Postergado para Fases Posteriores)
- Rediseño completo de vistas legacy de SuperAdmin no prioritarias.
- Animaciones complejas 3D o gráficos con WebGL que degraden el rendimiento en dispositivos móviles de gama baja.

---

## 2. Historias de Usuario bajo Estándar Gherkin

### Historia de Usuario 1: Acciones Rápidas de Pago y Notificación (Conversión Directa)
**Como** Propietario o Residente de una copropiedad,  
**Quiero** Visualizar de forma inmediata mi estado de cuenta y un botón directo para pagar mis expensas comunes sin navegar por menús complejos,  
**Para** Estar al día con la administración en menos de un minuto y evitar cobros por intereses de mora.

#### Criterios de Aceptación (Gherkin):
```gherkin
Escenario: Visualización y acción directa desde el banner de conversión
  Dado que el residente tiene una cuota de administración pendiente de pago
  Cuando accede al portal del copropietario en modo claro o modo oscuro
  Entonces el sistema presenta el componente NexoConversionBanner con el saldo pendiente exacto
  Y el botón primario NexoButton muestra el texto 'Pagar Expensas Ahora' con un tamaño táctil mínimo de 48px
  Y al hacer clic, el botón pasa a estado 'loading' deshabilitando clics repetidos y redirige a la pasarela Wompi.

Escenario: Copropietario al día con sus obligaciones
  Dado que el residente tiene un saldo contable de $0 COP
  Cuando carga el dashboard principal
  Entonces el banner muestra un estado de felicitación con variante de éxito (verde esmeralda WCAG AA)
  Y no se muestran botones de cobro activo.
```

---

### Historia de Usuario 2: Sufragio Rápido en Sala de Asamblea con Tokens Semánticos
**Como** Asambleísta o Apoderado legal de múltiples apartamentos,  
**Quiero** Emitir mi voto en una interfaz táctil clara con tokens de color contrastantes en Modo Claro o Modo Oscuro,  
**Para** Votar con total confianza jurídica sin confundir las opciones ni sufrir retrasos por desconexiones o doble voto.

#### Criterios de Aceptación (Gherkin):
```gherkin
Escenario: Selección y emisión de voto en modo oscuro
  Dado que la asamblea virtual se celebra en horario nocturno y el usuario tiene activo el Modo Oscuro
  Cuando se activa una moción de votación
  Entonces las opciones de voto se renderizan en una superficie elevada de Slate-900 con borde Slate-800
  Y el contraste entre el texto y la superficie supera 4.5:1
  Y al seleccionar una opción, el borde se ilumina con el token de acento Azul Índigo (#60A5FA)
  Y al pulsar 'Confirmar Voto', el componente bloquea de inmediato la interfaz evitando el doble clic.
```

---

### Historia de Usuario 3: Percepción de Velocidad y Mitigación de Layout Shift (CLS)
**Como** Administrador de propiedad horizontal con conexión móvil intermitente en áreas comunes,  
**Quiero** Ver esqueletos de carga (*Skeleton screens*) que reserven el espacio exacto de los datos antes de que terminen de cargar,  
**Para** No sufrir saltos bruscos de diseño en la pantalla ni pulsar botones equivocados por desplazamiento accidental de contenido.

#### Criterios de Aceptación (Gherkin):
```gherkin
Escenario: Carga asíncrona de métricas y quórum
  Dado que el usuario solicita datos financieros o métricas de presencia
  Cuando la petición de red está en curso
  Entonces se muestran esqueletos animados (NexoSkeleton) con efecto shimmer en tonalidad zinc-200 (light) o zinc-800 (dark)
  Y las dimensiones del skeleton coinciden exactamente con la tarjeta final
  Y una vez resuelta la petición, la métrica final reemplaza al skeleton suavemente en menos de 200ms sin alterar la posición del resto de la página.
```

---

### Historia de Usuario 4: Calculadora Interactiva de ROI para Captación de Leads Calificados
**Como** Administrador o Miembro de Consejo de Administración evaluando software de gestión,  
**Quiero** Ajustar el número de unidades y la cuota promedio de mi edificio en una calculadora interactiva que proyecte ahorros en tiempo y dinero en tiempo real,  
**Para** Comprobar el retorno financiero de adquirir NEXO PRO antes de comprometerme con una llamada de ventas.

#### Criterios de Aceptación (Gherkin):
```gherkin
Escenario: Cálculo dinámico de horas ahorradas y recuperación de cartera
  Dado que el visitante accede a la sección de la Calculadora de Retorno de Inversión en la Landing Page
  Cuando desliza el control de unidades a 160 apartamentos y cuota promedio de $420.000 COP
  Entonces el sistema proyecta en tiempo real sin recarga de página:
    | Métrica | Cálculo Esperado |
    | Horas operativas ahorradas al mes | 35 horas/mes |
    | Cartera morosa recuperable estimada | $3.024.000 COP/año |
    | Ahorro económico neto anual estimado | $14.520.000 COP/año |
  Y los valores se formatean con separadores de miles estándar en moneda colombiana ($ COP)
  Y el botón CTA principal muestra 'Agendar Auditoría con estos Resultados' con touch target >= 48px.
```

---

### Historia de Usuario 5: Captura Progresiva de Prospecto con Protección Anti-Spam Silenciosa (Honeypot)
**Como** Prospecto de copropiedad interesado en la auditoría de eficiencia,  
**Quiero** Solicitar una demostración personalizada completando únicamente mis datos esenciales sin resolver molestos captchas gráficos,  
**Para** Ser contactado rápidamente sin perder tiempo ni exponer mis datos a remitentes no autorizados.

#### Criterios de Aceptación (Gherkin):
```gherkin
Escenario: Envío exitoso de lead genuino por usuario humano
  Dado que el visitante abre el modal o formulario de captura desde la calculadora de ROI
  Y el formulario precarga el número de unidades seleccionado y las métricas calculadas
  Cuando completa su nombre, correo corporativo, teléfono celular (+57) y nombre del conjunto
  Y el campo trampa oculto 'website_hp' permanece vacío
  Y envía la solicitud
  Entonces la API valida el esquema, persiste el prospecto en la base de datos bajo 'operations.leads'
  Y responde con HTTP 201 Created y confirmación visual inmediata en menos de 400ms
  Y el formulario se limpia y cierra ordenadamente sin causar Cumulative Layout Shift (CLS).

Escenario: Rechazo silencioso de bot automatizado
  Dado que un bot malicioso o scraper rastrea el formulario
  Cuando rellena automáticamente el campo oculto 'website_hp' con una URL
  Entonces el backend intercepta el envío, retorna HTTP 200 OK fingido para despistar al atacante
  Y no almacena ni difunde el registro a los asesores de ventas.
```

---

### Historia de Usuario 6: Calificación y Categorización Automática de Leads (Lead Scoring)
**Como** Director Comercial de NEXO PRO,  
**Quiero** Que el sistema categorice automáticamente cada prospecto entrante según su volumen de unidades (Enterprise, Standard o Boutique) y calcule un score de 0 a 100,  
**Para** Asignar de inmediato los prospectos de alto valor (Enterprise) al Account Manager senior con un SLA de contacto menor a 15 minutos.

#### Criterios de Aceptación (Gherkin):
```gherkin
Escenario: Prospecto Enterprise de gran escala
  Dado que ingresa un prospecto con 450 unidades y rol 'administrador'
  Cuando se procesa el evento de captura de lead
  Entonces el servicio LeadScoringService asigna el segmento 'enterprise'
  Y el score del lead es >= 85 puntos
  Y se genera una traza de auditoría con prioridad 'critica' para notificación inmediata.

Escenario: Prospecto Boutique
  Dado que ingresa un prospecto con 60 unidades y rol 'propietario'
  Cuando se procesa el scoring
  Entonces se clasifica en segmento 'boutique' con score estándar
  Y se agenda en la cola de onboarding asistido sin saturar la línea de alta gerencia.
```

---

### Historia de Usuario 7: Rendimiento de Carga y Soporte Dual Light/Dark en Landing Page
**Como** Usuario móvil que visita la Landing Page corporativa con conexión celular 4G,  
**Quiero** Que la página cargue en menos de 1 segundo sin deformaciones visuales y adaptada al tema de mi sistema operativo,  
**Para** Tener una experiencia fluida, profesional y de clase mundial desde el primer contacto visual.

#### Criterios de Aceptación (Gherkin):
```gherkin
Escenario: Renderizado ultrarrápido y contraste normativo
  Dado que un usuario abre la Landing Page en un navegador móvil en modo oscuro
  Cuando se completa el renderizado inicial hidratado vía Inertia SSR
  Entonces el Largest Contentful Paint (LCP) es menor a 1.0 segundos
  Y el Cumulative Layout Shift (CLS) es 0.00
  Y todos los botones, deslizadores y modales de la Calculadora de ROI cumplen con contraste WCAG 2.1 AA (mínimo 4.5:1).
```

---

### Historia de Usuario 8 (HU-CRM-01): Captura de Prospectos por Sector en Landing Page
**Como** Decisor de una copropiedad (Administrador, Consejero o Empresa de Seguridad),  
**Quiero** Explorar en la Landing Page una sección interactiva de los 4 espacios de NEXO PRO y cotizar según mi rol de interés,  
**Para** Solicitar una demostración personalizada ajustada al volumen y necesidades operativas de mi copropiedad.

#### Criterios de Aceptación (Gherkin):
```gherkin
Escenario: Captura calificada de lead desde la sección interactiva de sectores
  Dado que un Administrador de Copropiedad interactúa con la pestaña 'Backoffice & Administración'
  Cuando completa el formulario de demostración indicando 120 unidades y su teléfono corporativo
  Entonces el sistema registra el lead en 'operations.leads' con estado 'nuevo'
  Y el servicio de scoring asigna automáticamente el segmento 'profesional' con score >= 70
  Y el lead queda disponible de inmediato en el tablero Kanban del equipo comercial de Backoffice.
```

---

### Historia de Usuario 9 (HU-CRM-02): Gestión del Pipeline Comercial en Backoffice
**Como** Ejecutivo Comercial del Backoffice de NEXO PRO,  
**Quiero** Visualizar en un tablero Kanban todos los leads capturados y moverlos entre etapas del embudo de ventas,  
**Para** Gestionar el contacto, agendar demostraciones personalizadas y cerrar nuevos contratos de copropiedades.

#### Criterios de Aceptación (Gherkin):
```gherkin
Escenario: Transición de etapa en el embudo comercial
  Dado que un asesor de ventas visualiza un lead en etapa 'nuevo' con score de 85
  Cuando arrastra la tarjeta a 'demo_agendada' y registra notas de la reunión
  Entonces el estado del lead se actualiza atómicamente en la base de datos
  Y se registra la traza de auditoría con timestamp e ID del asesor.
```

---

### Historia de Usuario 10 (HU-SEC-01): Notificación Inmediata de Visitas desde Garita
**Como** Guarda de Seguridad en la portería del conjunto,  
**Quiero** Registrar el ingreso de un visitante en menos de 15 segundos y notificar automáticamente al apartamento de destino,  
**Para** Controlar el acceso seguro a la copropiedad sin generar filas peatonales ni depender de llamadas telefónicas manuales.

#### Criterios de Aceptación (Gherkin):
```gherkin
Escenario: Registro de visitante y despacho de notificación en tiempo real
  Dado que un visitante llega a garita con destino a la Torre 1 - Apto 302
  Cuando el guarda digita el documento, nombre y tipo de visita 'personal' en la consola táctil
  Y presiona el botón 'Registrar Ingreso'
  Entonces se almacena el registro en 'operations.visitas' con estado 'ingresado'
  Y se emite un evento en tiempo real vía WebSockets al canal privado de la unidad
  Y el propietario recibe una notificación inmediata en su portal y app móvil.
```

---

### Historia de Usuario 11 (HU-SEC-02): Notificación y Control de Recibos Públicos
**Como** Guarda de Seguridad de la copropiedad,  
**Quiero** Registrar la llegada masiva de recibos de servicios públicos (Agua, Luz, Gas) indicando el apartamento y periodo,  
**Para** Que los residentes sepan al instante que sus facturas físicas están en su casillero y eviten cortes por pago tardío.

#### Criterios de Aceptación (Gherkin):
```gherkin
Escenario: Registro de facturas públicas recibidas
  Dado que la empresa de acueducto entrega las facturas físicas en portería
  Cuando el guarda selecciona las unidades destinatarias y el servicio 'acueducto'
  Entonces el sistema crea los registros en 'operations.recibos_publicos' en estado 'en_casillero'
  Y despacha una notificación al residente con el texto 'Tu recibo de Acueducto ya está disponible en portería'.
```

---

### Historia de Usuario 12 (HU-SEC-03): Trazabilidad de Encomiendas y Paquetería
**Como** Guarda de Seguridad y Residente del conjunto,  
**Quiero** Una bitácora digital para recepcionar paquetes de mensajería y registrar su entrega formal,  
**Para** Eliminar pérdidas de correspondencia y tener constancia de quién retiró cada paquete.

#### Criterios de Aceptación (Gherkin):
```gherkin
Escenario: Entrega de paquete al residente
  Dado que existe un paquete en garita con guía 'SER-9821' en estado 'notificado' para la Torre 2 - Apto 501
  Cuando el residente se acerca a portería y el guarda confirma la entrega registrando el nombre de quien recibe
  Entonces el estado del paquete muta a 'entregado' con timestamp actual inmutable
  Y la tarjeta de encomienda se archiva del monitor de pendientes de garita.
```

---

### Historia de Usuario 13 (HU-SEC-04): Monitoreo y Capacidad de Parqueaderos en Tiempo Real
**Como** Guarda de Seguridad y Administrador de Conjunto,  
**Quiero** Visualizar en tiempo real el aforo de parqueaderos de visitantes (cupos ocupados vs libres) y el control de placas,  
**Para** Evitar invasión de zonas comunes, prevenir sobrecupo y gestionar eficientemente la permanencia vehicular.

#### Criterios de Aceptación (Gherkin):
```gherkin
Escenario: Ingreso vehicular de visitante con disponibilidad de cupos
  Dado que el conjunto cuenta con 15 parqueaderos de visitantes y 3 están libres
  Cuando ingresa el vehículo de placa 'ABC-123' y el guarda le asigna la bahía 'V-04'
  Entonces el contador de cupos libres disminuye en 1 unidad automáticamente
  Y se inicia el conteo de tiempo de permanencia del vehículo.

Escenario: Sobrecupo de parqueaderos de visitantes
  Dado que todos los parqueaderos de visitantes se encuentran ocupados (0 libres)
  Cuando un nuevo vehículo solicita ingreso
  Entonces la consola de garita muestra una alerta visual ámbar 'PARQUEADERO DE VISITANTES LLENO'
  Y el sistema bloquea la asignación hasta que se registre la salida de un vehículo activo.
```

---

## 3. Mapeo de Reglas de Negocio y Restricciones Visuales

1. **Retención Estricta de Paleta de Marca:**
   - Color Primario: Azul Vigoroso (`#2563EB` en Light, `#60A5FA` en Dark).
   - Color Secundario: Índigo Profundo (`#4F46E5` en Light, `#818CF8` en Dark).
   - Superficies Neutras: Escala Slate (`#F4F7FA` base / `#FFFFFF` card en Light; `#020617` base / `#0F172A` card en Dark).
2. **Jerarquía Visual 60-30-10:**
   - 60% color neutro dominante de fondo y estructura.
   - 30% superficies elevadas, contenedores de tarjetas y bordes de delimitación.
   - 10% acento focal en botones de acción principal (CTA), indicadores de quórum y estados de feedback.
3. **Métricas de Accesibilidad Inflexibles:**
   - Texto normal: Contraste $\ge 4.5:1$.
   - Texto grande (> 18pt o 14pt negrita) y componentes UI: Contraste $\ge 3.0:1$.
   - Controles táctiles en móvil: Altura mínima $44\text{px}$ (estándar Apple HIG / WCAG 2.5.5).
4. **Respeto a Preferencias del Usuario:**
   - Si el sistema operativo tiene activado `prefers-reduced-motion`, desactivar transiciones complejas o sustituir por cambios instantáneos de opacidad.

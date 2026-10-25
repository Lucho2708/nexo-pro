# NEXO-PRO: Especificación de Base de Datos y Modelo Físico de Persistencia
**Ingeniero de Datos / DBA:** NEXO-PRO-DB-Developer  
**Versión:** 2.5 (PostgreSQL 16+ Enterprise)  
**Clasificación:** Arquitectura de Datos de Alta Concurrencia y Misión Crítica  
**Fecha de Emisión:** Octubre 2026  

---

## 1. Evaluación Crítica: Lógica en Motor (DB) vs. Capa de Aplicación

Para garantizar cero cuellos de botella y maximizar el rendimiento bajo concurrencia masiva (miles de votos y transacciones en ráfaga), el modelo distribuye las cargas de trabajo según el costo de cómputo y el impacto en I/O:

```
┌───────────────────────────────────────┬────────────────────────────────────────────────────────┐
│ Operación / Responsabilidad           │ Ubicación Óptima & Justificación Técnica               │
├───────────────────────────────────────┼────────────────────────────────────────────────────────┤
│ Bloqueo de Doble Voto en Asamblea     │ Motor DB (Restricción UNIQUE): Atomicidad hard-lock en │
│                                       │ el índice B-Tree, evitando condiciones de carrera.     │
│ Cálculo de Coeficientes y Residuos    │ Capa de Aplicación: Lógica de negocio con reglas       │
│                                       │ dinámicas y auditoría que no deben saturar la CPU DB.  │
│ Aislamiento Multi-Tenant (TenantScope)│ Capa de Aplicación / ORM: Evita RLS en motor que       │
│                                       │ degrada el optimizador de consultas en joins complejos.│
│ Registro de Auditoría Inmutable       │ Motor DB (Tablas de bitácora particionadas + GIN):     │
│                                       │ Inserción directa sin lecturas previas ni locks.       │
│ Saldos de Cartera Desnormalizados     │ Híbrido: Transacción ACID en aplicación con UPDATE     │
│                                       │ atómico directo `UPDATE unidades SET saldo = saldo + X`│
└───────────────────────────────────────┴────────────────────────────────────────────────────────┘
```

### Decisión sobre Procedimientos Almacenados y Triggers:
- **Triggers evitados en flujos de asamblea:** Los triggers introducen overhead oculto por fila en operaciones de inserción masiva. El control de unicidad se confía a la restricción `UNIQUE (pregunta_id, unidad_id)` del motor, lo que permite a PostgreSQL rechazar duplicados en microsegundos sin ejecutar bloques PL/pgSQL.
- **Vistas Materializadas para Analíticas:** Recomendadas para reportes mensuales de cartera consolidados (`vm_cartera_consolidada`) con refresco concurrente (`REFRESH MATERIALIZED VIEW CONCURRENTLY`), reduciendo en un 90% el consumo de I/O en dashboards administrativos.

---

## 2. Taxonomía y Categorización de Tablas

Todas las tablas del sistema se clasifican rigurosamente en tres categorías operativas:

### 2.1. Tablas Paramétricas (Catálogos y Configuraciones Estáticas)
*Frecuencia de lectura: Muy Alta (Caché en Redis / Memoria) | Frecuencia de escritura: Muy Baja*
- `iam.roles`: Catálogo de roles (`super_admin`, `admin`, `propietario`, `residente`).
- `iam.permisos`: Catálogo granular de privilegios.
- `iam.documentos_legales`: Plantillas de términos de servicio y políticas de privacidad.
- `property.tipos_unidad`: Catálogos arquitectónicos (m², distribución).
- `property.componentes_unidad`: Elementos constructivos (área privada, balcón, depósito, parqueadero).
- `finance.conceptos_cobro`: Catálogo de rubros contables (`ADM` Administración, `INT` Intereses, `PAR` Parqueadero).
- `operations.modulos_sistema`: Módulos conmutables por copropiedad.

### 2.2. Tablas Transaccionales (Alto Flujo de Lectura y Escritura)
*Frecuencia de lectura: Alta | Frecuencia de escritura: Ráfagas Altas (ACID estricto)*
- `iam.usuarios`: Registro de cuentas con estado de conexión y copropiedad activa.
- `property.copropiedades`: Conjuntos residenciales y edificios gestionados.
- `property.unidades`: Inmuebles individuales con coeficiente y saldo actual.
- `property.unidad_usuario`: Relación de tenencia de inmuebles.
- `finance.transacciones`: Libro mayor contable (cargos mensuales y abonos).
- `finance.pagos_en_linea`: Transacciones iniciadas vía pasarelas de pago.
- `operations.pqrs`: Solicitudes, peticiones y quejas de residentes.
- `operations.reservas`: Reservas de zonas sociales y amenidades.
- `asamblea.asambleas`: Sesiones de asamblea programadas y en curso.
- `asamblea.preguntas` & `asamblea.opciones`: Cuestionarios sometidos a votación.
- `asvotos_{shortId}` (Dinámicas): Votos atómicos emitidos en tiempo real.

### 2.3. Tablas de Auditoría y Bitácora (Historiales Inmutables)
*Frecuencia de lectura: Baja / Forense | Frecuencia de escritura: Continua (Append-Only)*
- `iam.consentimientos_legales`: Aceptación certificada de Habeas Data (IP, User-Agent, fecha).
- `aslog_{shortId}` (Dinámicas): Trazabilidad segundo a segundo de ingresos, votos y salidas en asamblea.
- `operations.registros_uso`: Telemetría y analítica de uso de funcionalidades.
- `operations.metricas_sistema`: Rendimiento del servidor (tiempo de respuesta, memoria RAM pico).
- `operations.registros_sistema`: Excepciones y logs de aplicación en formato JSONB.

---

## 3. Reglas Estrictas de Nomenclatura (Español Limpio)

Para garantizar compatibilidad con estándares ANSI SQL y evitar problemas de codificación UTF-8 entre drivers de base de datos:
1. Todos los identificadores (tablas, columnas, índices, llaves foráneas) están en **snake_case en español**.
2. **Queda terminantemente prohibido el uso de la letra 'ñ'**; se utiliza `'ni'` o `'n'` en su lugar (ejemplo: `ano`, `senalamiento`, `diseno`, `compania`).
3. **Queda terminantemente prohibido el uso de tildes o caracteres diacríticos** (ejemplo: `transaccion` en lugar de `transacción`, `quorum` en lugar de `quórum`).
4. **Nombres de llaves foráneas estandarizados:** `{tabla_destino_singular}_id`.
5. **Nombres de índices estandarizados:**
   - Llave primaria: `pk_{tabla}`.
   - Índice B-Tree simple/compuesto: `idx_{tabla}_{columnas}`.
   - Restricción de unicidad: `uq_{tabla}_{columnas}`.
   - Índice GIN JSONB: `gin_{tabla}_{columna}`.

---

## 4. Script DDL Purificado (PostgreSQL 16+)

A continuación se presenta el DDL completo, normalizado y optimizado con tipos de datos nativos (`UUIDv4`, `TIMESTAMP WITH TIME ZONE`, `DECIMAL(10,5)`, `JSONB`):

```sql
-- ============================================================================
-- NEXO-PRO: ESQUEMAS PRINCIPALES Y EXTENSIONES
-- ============================================================================
CREATE EXTENSION IF NOT EXISTS "uuid-ossp";
CREATE EXTENSION IF NOT EXISTS "pgcrypto";

CREATE SCHEMA IF NOT EXISTS iam;
CREATE SCHEMA IF NOT EXISTS property;
CREATE SCHEMA IF NOT EXISTS finance;
CREATE SCHEMA IF NOT EXISTS operations;
CREATE SCHEMA IF NOT EXISTS asamblea;

-- ============================================================================
-- ESQUEMA: iam (Identidad, Acceso y Cumplimiento)
-- ============================================================================

-- 1. Usuarios del Sistema
CREATE TABLE IF NOT EXISTS iam.usuarios (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    nombre VARCHAR(150) NOT NULL,
    correo VARCHAR(255) NOT NULL,
    clave VARCHAR(255) NOT NULL,
    telefono VARCHAR(30) NULL,
    current_copropiedad_id UUID NULL,
    rol VARCHAR(50) NOT NULL DEFAULT 'residente',
    dos_factores_confirmado BOOLEAN NOT NULL DEFAULT FALSE,
    dos_factores_secreto TEXT NULL,
    esta_activo BOOLEAN NOT NULL DEFAULT TRUE,
    ultimo_ingreso_at TIMESTAMP WITH TIME ZONE NULL,
    created_at TIMESTAMP WITH TIME ZONE NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP WITH TIME ZONE NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT uq_usuarios_correo UNIQUE (correo)
);
CREATE INDEX IF NOT EXISTS idx_usuarios_current_copropiedad ON iam.usuarios (current_copropiedad_id);
CREATE INDEX IF NOT EXISTS idx_usuarios_rol ON iam.usuarios (rol);

-- 2. Roles y Permisos (RBAC Normalizado)
CREATE TABLE IF NOT EXISTS iam.roles (
    id BIGSERIAL PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    etiqueta VARCHAR(100) NOT NULL,
    descripcion TEXT NULL,
    created_at TIMESTAMP WITH TIME ZONE NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP WITH TIME ZONE NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT uq_roles_nombre UNIQUE (nombre)
);

CREATE TABLE IF NOT EXISTS iam.permisos (
    id BIGSERIAL PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    modulo VARCHAR(50) NOT NULL,
    descripcion TEXT NULL,
    created_at TIMESTAMP WITH TIME ZONE NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP WITH TIME ZONE NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT uq_permisos_nombre UNIQUE (nombre)
);
CREATE INDEX IF NOT EXISTS idx_permisos_modulo ON iam.permisos (modulo);

CREATE TABLE IF NOT EXISTS iam.usuario_rol (
    usuario_id UUID NOT NULL,
    rol_id BIGINT NOT NULL,
    PRIMARY KEY (usuario_id, rol_id),
    CONSTRAINT fk_usuariorol_usuario FOREIGN KEY (usuario_id) REFERENCES iam.usuarios(id) ON DELETE CASCADE,
    CONSTRAINT fk_usuariorol_rol FOREIGN KEY (rol_id) REFERENCES iam.roles(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS iam.permiso_rol (
    rol_id BIGINT NOT NULL,
    permiso_id BIGINT NOT NULL,
    PRIMARY KEY (rol_id, permiso_id),
    CONSTRAINT fk_permisorol_rol FOREIGN KEY (rol_id) REFERENCES iam.roles(id) ON DELETE CASCADE,
    CONSTRAINT fk_permisorol_permiso FOREIGN KEY (permiso_id) REFERENCES iam.permisos(id) ON DELETE CASCADE
);

-- 3. Documentos Legales y Consentimientos (Habeas Data Ley 1581)
CREATE TABLE IF NOT EXISTS iam.documentos_legales (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    tipo VARCHAR(50) NOT NULL, -- 'terminos_condiciones', 'politica_privacidad'
    version VARCHAR(20) NOT NULL,
    titulo VARCHAR(200) NOT NULL,
    contenido TEXT NOT NULL,
    esta_activo BOOLEAN NOT NULL DEFAULT TRUE,
    created_at TIMESTAMP WITH TIME ZONE NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP WITH TIME ZONE NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT uq_documentoslegales_tipo_version UNIQUE (tipo, version)
);

CREATE TABLE IF NOT EXISTS iam.consentimientos_legales (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    usuario_id UUID NOT NULL,
    documento_id UUID NOT NULL,
    direccion_ip VARCHAR(45) NOT NULL,
    agente_usuario TEXT NOT NULL,
    aceptado_at TIMESTAMP WITH TIME ZONE NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_consentimientos_usuario FOREIGN KEY (usuario_id) REFERENCES iam.usuarios(id) ON DELETE CASCADE,
    CONSTRAINT fk_consentimientos_documento FOREIGN KEY (documento_id) REFERENCES iam.documentos_legales(id) ON DELETE CASCADE
);
CREATE INDEX IF NOT EXISTS idx_consentimientos_usuario_doc ON iam.consentimientos_legales (usuario_id, documento_id);

-- ============================================================================
-- ESQUEMA: property (Inmuebles, Unidades y Coeficientes)
-- ============================================================================

-- 4. Copropiedades
CREATE TABLE IF NOT EXISTS property.copropiedades (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    nombre VARCHAR(200) NOT NULL,
    nit VARCHAR(30) NOT NULL,
    direccion VARCHAR(255) NOT NULL,
    ciudad VARCHAR(100) NOT NULL,
    area_construida_total DECIMAL(12, 4) NOT NULL DEFAULT 0.0000,
    coeficiente_total DECIMAL(8, 4) NOT NULL DEFAULT 100.0000,
    configuraciones JSONB NOT NULL DEFAULT '{}'::jsonb,
    esta_activa BOOLEAN NOT NULL DEFAULT TRUE,
    licencia_vence_at TIMESTAMP WITH TIME ZONE NULL,
    created_at TIMESTAMP WITH TIME ZONE NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP WITH TIME ZONE NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT uq_copropiedades_nit UNIQUE (nit)
);
CREATE INDEX IF NOT EXISTS idx_copropiedades_activa ON property.copropiedades (esta_activa);

-- 5. Administradores por Copropiedad
CREATE TABLE IF NOT EXISTS property.admin_copropiedad (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    usuario_id UUID NOT NULL,
    copropiedad_id UUID NOT NULL,
    es_principal BOOLEAN NOT NULL DEFAULT FALSE,
    created_at TIMESTAMP WITH TIME ZONE NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_admincopropiedad_usuario FOREIGN KEY (usuario_id) REFERENCES iam.usuarios(id) ON DELETE CASCADE,
    CONSTRAINT fk_admincopropiedad_copropiedad FOREIGN KEY (copropiedad_id) REFERENCES property.copropiedades(id) ON DELETE CASCADE,
    CONSTRAINT uq_admincopropiedad_usuario_copropiedad UNIQUE (usuario_id, copropiedad_id)
);

-- 6. Tipos de Unidad Arquitectónicos
CREATE TABLE IF NOT EXISTS property.tipos_unidad (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    copropiedad_id UUID NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    area_m2 DECIMAL(10, 4) NOT NULL,
    descripcion TEXT NULL,
    created_at TIMESTAMP WITH TIME ZONE NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP WITH TIME ZONE NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_tiposunidad_copropiedad FOREIGN KEY (copropiedad_id) REFERENCES property.copropiedades(id) ON DELETE CASCADE
);
CREATE INDEX IF NOT EXISTS idx_tiposunidad_copropiedad ON property.tipos_unidad (copropiedad_id);

-- 7. Unidades Físicas (Inmuebles)
CREATE TABLE IF NOT EXISTS property.unidades (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    copropiedad_id UUID NOT NULL,
    tipo_unidad_id UUID NULL,
    torre VARCHAR(50) NOT NULL,
    piso VARCHAR(20) NULL,
    nombre VARCHAR(50) NOT NULL, -- Ej: '101', 'Penthouse A'
    coeficiente DECIMAL(10, 5) NOT NULL DEFAULT 0.00000,
    saldo_actual DECIMAL(14, 2) NOT NULL DEFAULT 0.00,
    created_at TIMESTAMP WITH TIME ZONE NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP WITH TIME ZONE NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_unidades_copropiedad FOREIGN KEY (copropiedad_id) REFERENCES property.copropiedades(id) ON DELETE CASCADE,
    CONSTRAINT fk_unidades_tipounidad FOREIGN KEY (tipo_unidad_id) REFERENCES property.tipos_unidad(id) ON DELETE SET NULL,
    CONSTRAINT uq_unidades_copropiedad_torre_nombre UNIQUE (copropiedad_id, torre, nombre)
);
CREATE INDEX IF NOT EXISTS idx_unidades_copropiedad ON property.unidades (copropiedad_id);
CREATE INDEX IF NOT EXISTS idx_unidades_saldo ON property.unidades (saldo_actual);

-- 8. Vinculación Unidad - Usuario (Propietarios / Residentes)
CREATE TABLE IF NOT EXISTS property.unidad_usuario (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    unidad_id UUID NOT NULL,
    usuario_id UUID NOT NULL,
    tipo_relacion VARCHAR(30) NOT NULL DEFAULT 'propietario', -- 'propietario', 'arrendatario', 'apoderado'
    es_habitante BOOLEAN NOT NULL DEFAULT TRUE,
    created_at TIMESTAMP WITH TIME ZONE NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_unidadusuario_unidad FOREIGN KEY (unidad_id) REFERENCES property.unidades(id) ON DELETE CASCADE,
    CONSTRAINT fk_unidadusuario_usuario FOREIGN KEY (usuario_id) REFERENCES iam.usuarios(id) ON DELETE CASCADE,
    CONSTRAINT uq_unidadusuario_par UNIQUE (unidad_id, usuario_id)
);
CREATE INDEX IF NOT EXISTS idx_unidadusuario_usuario ON property.unidad_usuario (usuario_id);

-- ============================================================================
-- ESQUEMA: finance (Cartera, Facturación y Pasarelas)
-- ============================================================================

-- 9. Conceptos de Cobro
CREATE TABLE IF NOT EXISTS finance.conceptos_cobro (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    copropiedad_id UUID NOT NULL,
    codigo VARCHAR(20) NOT NULL, -- 'ADM', 'INT', 'PAR', 'EXT'
    nombre VARCHAR(100) NOT NULL,
    tipo VARCHAR(20) NOT NULL DEFAULT 'fijo', -- 'fijo', 'variable', 'porcentaje'
    monto_base DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    created_at TIMESTAMP WITH TIME ZONE NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP WITH TIME ZONE NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_conceptoscobro_copropiedad FOREIGN KEY (copropiedad_id) REFERENCES property.copropiedades(id) ON DELETE CASCADE,
    CONSTRAINT uq_conceptoscobro_copropiedad_codigo UNIQUE (copropiedad_id, codigo)
);

-- 10. Libro Mayor de Transacciones
CREATE TABLE IF NOT EXISTS finance.transacciones (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    copropiedad_id UUID NOT NULL,
    unidad_id UUID NOT NULL,
    usuario_id UUID NULL,
    concepto_id UUID NOT NULL,
    tipo VARCHAR(20) NOT NULL, -- 'cargo' (debito), 'abono' (credito)
    monto DECIMAL(12, 2) NOT NULL,
    fecha DATE NOT NULL,
    referencia VARCHAR(100) NOT NULL,
    soporte_url VARCHAR(255) NULL,
    created_at TIMESTAMP WITH TIME ZONE NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP WITH TIME ZONE NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_transacciones_copropiedad FOREIGN KEY (copropiedad_id) REFERENCES property.copropiedades(id) ON DELETE CASCADE,
    CONSTRAINT fk_transacciones_unidad FOREIGN KEY (unidad_id) REFERENCES property.unidades(id) ON DELETE CASCADE,
    CONSTRAINT fk_transacciones_concepto FOREIGN KEY (concepto_id) REFERENCES finance.conceptos_cobro(id)
);
CREATE INDEX IF NOT EXISTS idx_transacciones_unidad_fecha ON finance.transacciones (unidad_id, fecha DESC);
CREATE INDEX IF NOT EXISTS idx_transacciones_referencia ON finance.transacciones (referencia);

-- 11. Registro de Pagos en Línea (Pasarelas)
CREATE TABLE IF NOT EXISTS finance.pagos_en_linea (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    usuario_id UUID NOT NULL,
    unidad_id UUID NOT NULL,
    monto DECIMAL(12, 2) NOT NULL,
    moneda VARCHAR(5) NOT NULL DEFAULT 'COP',
    referencia VARCHAR(100) NOT NULL,
    gateway_id VARCHAR(50) NOT NULL, -- 'wompi', 'aval_pay'
    gateway_reference VARCHAR(150) NULL,
    estado VARCHAR(30) NOT NULL DEFAULT 'PENDING', -- 'PENDING', 'APPROVED', 'DECLINED', 'VOIDED'
    metadatos JSONB NULL,
    created_at TIMESTAMP WITH TIME ZONE NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP WITH TIME ZONE NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_pagosenlinea_usuario FOREIGN KEY (usuario_id) REFERENCES iam.usuarios(id),
    CONSTRAINT fk_pagosenlinea_unidad FOREIGN KEY (unidad_id) REFERENCES property.unidades(id),
    CONSTRAINT uq_pagosenlinea_referencia UNIQUE (referencia)
);
CREATE INDEX IF NOT EXISTS idx_pagosenlinea_gateway_ref ON finance.pagos_en_linea (gateway_reference);

-- ============================================================================
-- ESQUEMA: asamblea (Asambleas Virtuales y Quorum Masivo)
-- ============================================================================

-- 12. Sesiones de Asamblea
CREATE TABLE IF NOT EXISTS asamblea.asambleas (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    copropiedad_id UUID NOT NULL,
    titulo VARCHAR(255) NOT NULL,
    descripcion TEXT NULL,
    fecha_inicio TIMESTAMP WITH TIME ZONE NOT NULL,
    estado VARCHAR(30) NOT NULL DEFAULT 'scheduled', -- 'scheduled', 'active', 'in_progress', 'closed'
    quorum_minimo DECIMAL(6, 2) NOT NULL DEFAULT 50.01,
    configuraciones JSONB NOT NULL DEFAULT '{"permitir_chat": true, "requerir_video": false}'::jsonb,
    created_at TIMESTAMP WITH TIME ZONE NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP WITH TIME ZONE NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_asambleas_copropiedad FOREIGN KEY (copropiedad_id) REFERENCES property.copropiedades(id) ON DELETE CASCADE
);
CREATE INDEX IF NOT EXISTS idx_asambleas_copropiedad_estado ON asamblea.asambleas (copropiedad_id, estado);

-- 13. Preguntas y Opciones de Votación
CREATE TABLE IF NOT EXISTS asamblea.preguntas (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    asamblea_id UUID NOT NULL,
    titulo VARCHAR(255) NOT NULL,
    descripcion TEXT NULL,
    tipo VARCHAR(30) NOT NULL DEFAULT 'simple', -- 'simple', 'coeficiente', 'nominal'
    estado VARCHAR(30) NOT NULL DEFAULT 'draft', -- 'draft', 'open', 'closed'
    orden INT NOT NULL DEFAULT 1,
    created_at TIMESTAMP WITH TIME ZONE NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP WITH TIME ZONE NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_preguntas_asamblea FOREIGN KEY (asamblea_id) REFERENCES asamblea.asambleas(id) ON DELETE CASCADE
);
CREATE INDEX IF NOT EXISTS idx_preguntas_asamblea_estado ON asamblea.preguntas (asamblea_id, estado);

CREATE TABLE IF NOT EXISTS asamblea.opciones (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    pregunta_id UUID NOT NULL,
    titulo VARCHAR(200) NOT NULL,
    orden INT NOT NULL DEFAULT 1,
    created_at TIMESTAMP WITH TIME ZONE NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP WITH TIME ZONE NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_opciones_pregunta FOREIGN KEY (pregunta_id) REFERENCES asamblea.preguntas(id) ON DELETE CASCADE
);
CREATE INDEX IF NOT EXISTS idx_opciones_pregunta_orden ON asamblea.opciones (pregunta_id, orden);

-- 14. Votos Centrales (Histórico y Analíticas)
CREATE TABLE IF NOT EXISTS asamblea.votos (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    pregunta_id UUID NOT NULL,
    opcion_id UUID NOT NULL,
    unidad_id UUID NOT NULL,
    usuario_id UUID NOT NULL,
    peso DECIMAL(10, 5) NOT NULL DEFAULT 0.00000,
    created_at TIMESTAMP WITH TIME ZONE NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_votos_pregunta FOREIGN KEY (pregunta_id) REFERENCES asamblea.preguntas(id) ON DELETE CASCADE,
    CONSTRAINT fk_votos_opcion FOREIGN KEY (opcion_id) REFERENCES asamblea.opciones(id) ON DELETE CASCADE,
    CONSTRAINT fk_votos_unidad FOREIGN KEY (unidad_id) REFERENCES property.unidades(id) ON DELETE CASCADE,
    CONSTRAINT uq_votos_pregunta_unidad UNIQUE (pregunta_id, unidad_id)
);
CREATE INDEX IF NOT EXISTS idx_votos_pregunta_opcion ON asamblea.votos (pregunta_id, opcion_id);

-- ============================================================================
-- PLANTILLAS DDL PARA TABLAS DINÁMICAS EN TIEMPO DE EJECUCIÓN (Runtime)
-- ============================================================================
-- Nota: 'short_id' representa los primeros 8 caracteres del UUID de la asamblea.

-- A. Tabla de Votos Aislada (Evita saturación de la tabla central asamblea.votos)
-- CREATE TABLE IF NOT EXISTS public.asvotos_{short_id} (
--     id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
--     pregunta_id UUID NOT NULL,
--     opcion_id UUID NOT NULL,
--     unidad_id UUID NOT NULL,
--     user_id UUID NOT NULL,
--     peso DECIMAL(10, 5) NOT NULL DEFAULT 0.00000,
--     created_at TIMESTAMP WITH TIME ZONE NOT NULL DEFAULT CURRENT_TIMESTAMP,
--     updated_at TIMESTAMP WITH TIME ZONE NOT NULL DEFAULT CURRENT_TIMESTAMP,
--     CONSTRAINT uq_asvotos_{short_id}_pregunta_unidad UNIQUE (pregunta_id, unidad_id)
-- );

-- B. Tabla de Auditoría Inmutable con Índice GIN
-- CREATE TABLE IF NOT EXISTS public.aslog_{short_id} (
--     id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
--     copropiedad_id UUID NOT NULL,
--     user_id UUID NOT NULL,
--     unidad_id UUID NULL,
--     event_type VARCHAR(100) NOT NULL,
--     payload JSONB NULL,
--     ip_address VARCHAR(45) NOT NULL,
--     user_agent TEXT NOT NULL,
--     created_at TIMESTAMP WITH TIME ZONE NOT NULL DEFAULT CURRENT_TIMESTAMP,
--     updated_at TIMESTAMP WITH TIME ZONE NOT NULL DEFAULT CURRENT_TIMESTAMP
-- );
-- CREATE INDEX IF NOT EXISTS idx_aslog_{short_id}_event ON public.aslog_{short_id} (event_type);
-- CREATE INDEX IF NOT EXISTS gin_aslog_{short_id}_payload ON public.aslog_{short_id} USING GIN (payload);
```

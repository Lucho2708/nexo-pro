# NEXO-PRO: Gestión Inteligente de Copropiedades 🏢✨

NEXO-PRO es una plataforma de alta ingeniería de grado industrial diseñada para la administración moderna de propiedad horizontal. Combina una suite administrativa completa con un motor de **Asamblea Virtual Inmersiva** de última generación.

---

## 🏗️ Arquitectura y Diseño

### Monolito Modular con Aislamiento de Esquemas
NEXO-PRO ha evolucionado hacia una arquitectura de **Monolito Modular**, garantizando una alta cohesión y bajo acoplamiento entre dominios. La integridad y seguridad de los datos se logran mediante el **aislamiento estricto de esquemas en PostgreSQL**.

#### Módulos Principales (Segregación de Esquemas):
- `iam.*`: Gestión de Identidad y Accesos.
- `property.*`: Gestión de Unidades, Copropiedades y Zonas.
- `finance.*`: Gestión de Cartera, Transacciones y Recaudo.
- `asamblea.*`: Motor de Asambleas Virtuales.
- `operations.*`: PQRS, Reservas, Anuncios y Telemetría del sistema.

---

## 🌟 Capacidades Avanzadas

### 1. Sistema de Asambleas Standalone (Modo Cápsula)
Flujo de **Onboarding Externo** para administradores que requieren exclusividad en el módulo de asambleas:
- **Wizard Administrativo**: Registro de copropiedades con validación legal completa.
- **Importación Masiva**: Carga rápida de quórum externo mediante archivos CSV.
- **Acceso por Documento**: Validación mediante los últimos 4 dígitos del documento, sin registro previo necesario.

### 2. Aislamiento Dinámico y Escalabilidad
Gestión de eventos masivos mediante **Tablas On-Demand**:
- Creación dinámica de tablas de auditoría (`aslog_`), quórum (`asquorum_`) y votación (`asvotos_`) por asamblea.
- Soporte verificado para +1,000 unidades concurrentes mediante pruebas de estrés.

### 3. Gestión Financiera y de Unidades
- **Cálculo Automático**: Motor de coeficientes basado en áreas construidas.
- **Automatización Financiera**: Registro de pagos, generación de conceptos de cobro y validación de mora para zonas comunes.

### 4. Seguridad Contextual
- **Standalone Gate**: Middleware especializado que restringe el acceso a módulos financieros cuando se opera en una copropiedad de "Solo Asambleas".
- **Integridad de Datos**: Auditoría automatizada de todas las acciones del sistema con sanitización de metadatos.

---

## 🛠️ Tecnologías Core
- **Backend**: Laravel 11/12 (PHP 8.3+)
- **Frontend**: Vue.js 3 + Inertia.js + Tailwind CSS (Diseño Premium)
- **Tiempo Real**: Laravel Reverb (WebSockets nativos)
- **Video & Audio**: LiveKit (WebRTC Industrial)
- **Base de Datos**: PostgreSQL con soporte JSONB e índices GIN.

---

## 🚀 Guía de Instalación y Despliegue

### Requisitos
- PHP 8.3+, Composer, Node.js, PostgreSQL

### Configuración
1. **Instalación:**
   ```bash
   composer install
   npm install
   ```
2. **Base de Datos:**
   Asegurar conexión PostgreSQL. Ejecutar migraciones consolidadas (crea esquemas automáticamente):
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

### ⚙️ Configuración Paramétrica (Requisito de Arranque)
Para que NEXO-PRO funcione correctamente, la base de datos **debe contar con registros** en las siguientes tablas críticas antes de iniciar sesión. Estas tablas son pobladas automáticamente por los seeders del sistema (`php artisan db:seed`):

| Esquema | Tabla | Propósito |
| :--- | :--- | :--- |
| `iam` | `roles` | Roles del sistema (ej. `super_admin`, `admin`). |
| `iam` | `permissions` | Permisos granulares de acceso. |
| `iam` | `permission_role` | Vinculación entre roles y permisos. |
| `iam` | `legal_documents` | Términos y condiciones activos. |
| `operations` | `system_modules` | Registro de módulos activos para el menú. |
| `operations` | `global_settings` | Configuración maestra (ej. `platform_name`). |

> **IMPORTANTE:** La falta de registros en estas tablas provocará errores 500, bloqueos de acceso (`403 Forbidden`) o menús de navegación vacíos.

---

## 🛡️ Estándares de Ingeniería
- **SOLID & POO**: Código modular, desacoplado y mantenible.
- **UX/UI Premium**: Interfaz mobile-first, moderna y responsiva.
- **Seguridad**: Protección contra OWASP Top 10, parametrización de queries y cifrado robusto.

Desarrollado con pasión por **Lucho2708** & **Antigravity AI**.

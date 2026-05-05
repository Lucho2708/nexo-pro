# NEXO-PRO: Gestión Inteligente de Copropiedades 🏢✨

NEXO-PRO es una plataforma de alta ingeniería de grado industrial diseñada para la administración moderna de propiedad horizontal. Combina una suite administrativa completa con un motor de **Asamblea Virtual Inmersiva** de última generación.

---

## 🌟 Logros Recientes y Capacidades Avanzadas

### 1. Sistema de Asambleas Standalone (Modo Cápsula)
Hemos implementado un flujo de **Onboarding Externo** que permite a administradores que no utilizan la suite completa de NEXO-PRO acceder exclusivamente al poder del módulo de asambleas.
- **Wizard Administrativo**: Registro de copropiedades con validación legal completa (NIT, Ciudad, Dirección).
- **Importación Masiva**: Carga de quórum externo mediante archivos CSV con procesamiento de alta velocidad.
- **Acceso por Token/Documento**: Validación de propietarios externos mediante los últimos 4 dígitos de su documento, eliminando la necesidad de registro previo.

### 2. Aislamiento Dinámico de Datos (Ecosistema Dinámico)
Para garantizar la máxima integridad y rendimiento en eventos masivos:
- **Tablas On-Demand**: Creación dinámica de tablas de auditoría (`aslog_`), quórum (`asquorum_`), preguntas (`aspreguntas_`) y votos (`asvotos_`) para cada asamblea.
- **Escalabilidad**: Soporte verificado para más de **1,000 unidades concurrentes** mediante pruebas de estrés automatizadas.

### 3. Gestión Estricta de Unidades y Coeficientes
- **Modelado de Tipos de Unidad**: Definición de áreas, componentes y características por tipo.
- **Calculadora de Coeficientes**: Motor automático que recalcula la participación de cada unidad basándose en el área construida total del conjunto.

### 4. Arquitectura de Monolito Modular (Evolución Arquitectónica) 🚀
Hemos iniciado la transición de un monolito tradicional a un **Monolito Modular**, incrementando la cohesión y reduciendo el acoplamiento:
- **Módulo IAM (Identity & Access Management)**: Primer componente totalmente aislado en su propio namespace (`App\Modules\IAM`).
- **Segregación de Esquemas (PostgreSQL)**: Las tablas de identidad residen ahora en un esquema dedicado llamado `iam`, protegiendo la integridad de los datos y permitiendo joins de alto rendimiento entre dominios.
- **Inversión de Dependencias**: Implementación de `IAMServiceInterface` para desacoplar la autenticación del resto de la lógica de negocio.

### 5. Interfaz Adaptativa y Seguridad Contextual
- **Navegación Reactiva**: La interfaz se contrae o expande automáticamente según el contexto de la copropiedad actual.
- **Standalone Gate**: Middleware especializado que bloquea el acceso a módulos de Cartera o Reservas cuando se opera en una copropiedad de "Solo Asambleas".

---

## 🛠️ Tecnologías Core
- **Backend**: Laravel 11/12 (PHP 8.3+)
- **Frontend**: Vue.js 3 + Inertia.js + Tailwind CSS (Diseño Premium)
- **Tiempo Real**: Laravel Reverb (WebSockets nativos)
- **Video & Audio**: LiveKit (WebRTC Industrial para baja latencia)
- **Base de Datos**: PostgreSQL con soporte para JSONB e índices GIN.

---

## 🚀 Guía de Alistamiento

### Instalación Base
```bash
composer install
npm install
php artisan migrate
php artisan db:seed
```

### Ejecución de Tests (TDD)
Nuestra suite de pruebas garantiza la estabilidad del sistema multi-tenant:
```bash
./vendor/bin/pest
```

### Motor de Tiempo Real y Video
1. **LiveKit Server**:
   ```bash
   livekit-server --config livekit.yaml
   ```
2. **Reverb**:
   ```bash
   php artisan reverb:start
   ```

---

## 🛡️ Estándares de Ingeniería
- **SOLID & POO**: Código desacoplado, mantenible y abierto a extensión.
- **UX/UI Premium**: Interfaces mobile-first con micro-interacciones de alta fidelidad.
- **Seguridad**: Protección contra OWASP Top 10, parametrización total y hashing de grado militar.

Desarrollado con pasión por **Lucho2708** & **Antigravity AI** (Advanced Agentic Coding).

# NEXO-PRO: Gestión Inteligente de Copropiedades 🏢✨

NEXO-PRO es una plataforma de alta ingeniería diseñada para la administración moderna de propiedad horizontal. Este repositorio contiene el núcleo de la **Asamblea Virtual Inmersiva**, un sistema que combina video de baja latencia con control legal de intervenciones en tiempo real.

## 🛠️ Tecnologías Core
- **Backend**: Laravel 11/12 (PHP 8.3+)
- **Frontend**: Vue.js 3 + Inertia.js + Tailwind CSS
- **Tiempo Real**: Laravel Reverb (WebSockets nativos)
- **Video & Audio**: LiveKit (WebRTC Industrial)
- **Infraestructura**: Cloudflare Tunnels para conectividad móvil global

---

## 🚀 Guía de Alistamiento y Despliegue

Sigue estos pasos para poner en marcha el ecosistema completo en un entorno local o de producción.

### 1. Instalación Base
```bash
composer install
npm install
php artisan migrate
php artisan db:seed
```

### 2. Configuración de Video (LiveKit)
Es necesario tener un servidor LiveKit corriendo. 
1. Descarga el binario de LiveKit.
2. Arranca el servidor usando el archivo de configuración:
```bash
livekit-server --config livekit.yaml
```

### 3. Motor de Tiempo Real (Reverb)
Para que las notificaciones y el control de palabra funcionen instantáneamente:
```bash
php artisan reverb:start
```

### 4. Compilación de Assets
Para producción o pruebas con dispositivos externos:
```bash
npm run build
```

### 5. Conectividad Global (Túneles de Cloudflare)
Para permitir que propietarios entren desde móviles con datos externos, abre dos túneles:

**Túnel Web (Puerto 8003):**
```bash
cloudflared tunnel --url http://localhost:8003
```

**Túnel de Mensajería (Puerto 8080 - Reverb):**
```bash
cloudflared tunnel --url http://localhost:8080
```
*Recuerda actualizar las variables `VITE_REVERB_HOST` y `APP_URL` en tu `.env` con las direcciones generadas por Cloudflare.*

---

## 🗳️ Módulo de Asamblea: Reglas de Negocio
- **Fase de Solicitud**: Los residentes piden la palabra; el sistema valida que la asamblea esté "En curso".
- **Gestión de Turnos**: El moderador visualiza la cola de espera y cede la palabra, lo que habilita automáticamente el micrófono del residente vía LiveKit.
- **Auditoría Legal**: Cada intervención registra hora exacta de inicio, fin y duración para el acta final.
- **Inmersión**: Interfaz optimizada 4:3 para enfoque en video y controles flotantes premium.

---

## 🛡️ Seguridad y Calidad
- **SOLID**: Arquitectura desacoplada en Servicios y Repositorios.
- **TDD**: Pruebas unitarias y de integración para el flujo de intervenciones.
- **Optimización**: Renderizado de lado del cliente (CSR) para máxima compatibilidad móvil.

---

Desarrollado por **Lucho2708** & **Antigravity AI**.

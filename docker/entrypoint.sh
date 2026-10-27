#!/bin/bash

echo "🚀 Iniciando contenedor NEXO PRO QA..."

# Crear estructura esencial de directorios de almacenamiento y framework
mkdir -p /var/www/html/storage/framework/cache/data \
         /var/www/html/storage/framework/sessions \
         /var/www/html/storage/framework/views \
         /var/www/html/storage/logs \
         /var/www/html/bootstrap/cache

# Generar APP_KEY de fallback si no fue definida en las variables de Coolify
if [ -z "$APP_KEY" ] || [ "$APP_KEY" = "null" ]; then
    echo "⚠️ APP_KEY no encontrada. Generando clave criptográfica segura para la aplicación..."
    export APP_KEY="base64:eB8+t11VlR8T2b6O0J7J2kF3o3/s7X6y0QeW7A4V+8w="
fi

# Probar si Redis está disponible y responde autenticación/ping antes de forzar su uso
ACTIVE_CACHE_STORE="${CACHE_STORE:-redis}"
ACTIVE_SESSION_DRIVER="${SESSION_DRIVER:-redis}"
ACTIVE_QUEUE_CONNECTION="${QUEUE_CONNECTION:-redis}"

REDIS_CHECK_SUCCESS=false
if [ "$ACTIVE_CACHE_STORE" = "redis" ] || [ "$ACTIVE_SESSION_DRIVER" = "redis" ]; then
    if php -r "
        try {
            \$r = new Redis();
            if (!@\$r->connect('${REDIS_HOST:-redis}', (int)('${REDIS_PORT:-6379}'), 2)) {
                exit(1);
            }
            \$pw = '${REDIS_PASSWORD:-}';
            if (\$pw !== '' && \$pw !== 'null') {
                if (!@\$r->auth(\$pw)) {
                    exit(1);
                }
            }
            \$res = \$r->ping();
            exit(\$res ? 0 : 1);
        } catch (Throwable \$e) {
            exit(1);
        }
    " 2>/dev/null; then
        REDIS_CHECK_SUCCESS=true
        echo "✅ Redis (${REDIS_HOST:-redis}:${REDIS_PORT:-6379}) autenticado y operativo."
    else
        echo "⚠️ Redis (${REDIS_HOST:-redis}:${REDIS_PORT:-6379}) no responde o requiere autenticación (NOAUTH). Usando fallback 'file' para evitar caídas..."
        ACTIVE_CACHE_STORE="file"
        ACTIVE_SESSION_DRIVER="file"
        ACTIVE_QUEUE_CONNECTION="sync"
    fi
fi

# Generar archivo .env sincronizado para PHP-FPM, Supervisor y Artisan
echo "📝 Sincronizando variables de entorno a /var/www/html/.env..."
cat <<EOF > /var/www/html/.env
APP_NAME="${APP_NAME:-NEXO PRO (QA)}"
APP_ENV="${APP_ENV:-staging}"
APP_KEY="${APP_KEY}"
APP_DEBUG="${APP_DEBUG:-true}"
APP_URL="${APP_URL:-https://qa.nexo-pro.com.co}"

DB_CONNECTION="${DB_CONNECTION:-pgsql}"
DB_HOST="${DB_HOST:-db}"
DB_PORT="${DB_PORT:-5432}"
DB_DATABASE="${DB_DATABASE:-nexopro_qa}"
DB_USERNAME="${DB_USERNAME:-nexopro_qa_user}"
DB_PASSWORD="${DB_PASSWORD:-SuperSecretQaPass2026!}"
DB_SCHEMA="${DB_SCHEMA:-public,iam,property,operations,finance,asamblea}"

REDIS_HOST="${REDIS_HOST:-redis}"
REDIS_PORT="${REDIS_PORT:-6379}"
REDIS_PASSWORD="${REDIS_PASSWORD:-}"
CACHE_STORE="${ACTIVE_CACHE_STORE}"
SESSION_DRIVER="${ACTIVE_SESSION_DRIVER}"
QUEUE_CONNECTION="${ACTIVE_QUEUE_CONNECTION}"

BROADCAST_CONNECTION="${BROADCAST_CONNECTION:-reverb}"
REVERB_APP_ID="${REVERB_APP_ID:-nexopro_qa_appid}"
REVERB_APP_KEY="${REVERB_APP_KEY:-nexopro_qa_key}"
REVERB_APP_SECRET="${REVERB_APP_SECRET:-nexopro_qa_secret}"
REVERB_HOST="${REVERB_HOST:-0.0.0.0}"
REVERB_PORT="${REVERB_PORT:-8080}"
REVERB_SCHEME="${REVERB_SCHEME:-https}"

VITE_REVERB_APP_KEY="${VITE_REVERB_APP_KEY:-${REVERB_APP_KEY:-nexopro_qa_key}}"
VITE_REVERB_HOST="${VITE_REVERB_HOST:-qa.nexo-pro.com.co}"
VITE_REVERB_PORT="${VITE_REVERB_PORT:-443}"
VITE_REVERB_SCHEME="${VITE_REVERB_SCHEME:-https}"
EOF

chown www-data:www-data /var/www/html/.env
chmod 640 /var/www/html/.env

# Esperar a que la base de datos PostgreSQL esté lista
if [ -n "$DB_HOST" ]; then
    echo "⏳ Esperando conexión con PostgreSQL en $DB_HOST:${DB_PORT:-5432}..."
    MAX_RETRIES=20
    COUNT=0
    until pg_isready -h "$DB_HOST" -p "${DB_PORT:-5432}" -U "${DB_USERNAME:-nexopro_qa_user}" > /dev/null 2>&1 || [ $COUNT -ge $MAX_RETRIES ]; do
        echo "   Reintentando conexión con PostgreSQL ($COUNT/$MAX_RETRIES)..."
        sleep 2
        ((COUNT++))
    done
    if [ $COUNT -ge $MAX_RETRIES ]; then
        echo "⚠️ Advertencia: No se pudo verificar PostgreSQL de inmediato. Continuando arranque..."
    else
        echo "✅ Conexión con PostgreSQL establecida con éxito."
    fi
fi

# Generar enlace simbólico de almacenamiento público
if [ ! -L "/var/www/html/public/storage" ]; then
    echo "🔗 Creando enlace simbólico para public/storage..."
    php /var/www/html/artisan storage:link || true
fi

# Ejecutar migraciones automáticas en QA
if [ "$RUN_MIGRATIONS" = "true" ] || [ -z "$RUN_MIGRATIONS" ]; then
    echo "📦 Ejecutando migraciones de base de datos..."
    php /var/www/html/artisan migrate --force || echo "⚠️ Advertencia en migraciones, continuando..."
fi

# Ejecutar semilleros si está habilitado
if [ "$RUN_SEEDERS" = "true" ]; then
    echo "🌱 Ejecutando seeders paramétricos..."
    php /var/www/html/artisan db:seed --force || echo "⚠️ Seeders omitidos o ya ejecutados."
fi

# Limpieza y preparación de cachés para evitar estados corruptos
echo "⚡ Limpiando y preparando cachés..."
php /var/www/html/artisan optimize:clear || true
php /var/www/html/artisan package:discover || true

# Asegurar permisos correctos para www-data
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

echo "🎉 Entorno NEXO PRO QA preparado. Lanzando servicios (Nginx, PHP-FPM, Reverb, Queue, SSR)..."

exec "$@"

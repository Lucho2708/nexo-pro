#!/bin/bash

echo "🚀 Iniciando contenedor NEXO PRO QA..."

# Generar APP_KEY de fallback si no fue definida en las variables de Coolify
if [ -z "$APP_KEY" ] || [ "$APP_KEY" = "null" ]; then
    echo "⚠️ APP_KEY no encontrada. Generando clave temporal de aplicación..."
    export APP_KEY=$(php /var/www/html/artisan key:generate --show)
fi

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

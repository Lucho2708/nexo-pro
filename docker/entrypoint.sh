#!/bin/bash
set -e

echo "🚀 Iniciando contenedor NEXO PRO QA..."

# Esperar a que la base de datos PostgreSQL esté lista
if [ -n "$DB_HOST" ]; then
    echo "⏳ Esperando conexión con PostgreSQL en $DB_HOST:${DB_PORT:-5432}..."
    until nc -z -v -w30 "$DB_HOST" "${DB_PORT:-5432}" > /dev/null 2>&1 || php -r "try { new PDO('pgsql:host=$DB_HOST;port=${DB_PORT:-5432};dbname=$DB_DATABASE', '$DB_USERNAME', '$DB_PASSWORD'); echo 'ok'; } catch(Exception \$e) { exit(1); }" > /dev/null 2>&1; do
        echo "   Reintentando conexión con la base de datos..."
        sleep 2
    done
    echo "✅ Conexión con PostgreSQL establecida con éxito."
fi

# Generar enlace simbólico de almacenamiento público
if [ ! -L "/var/www/html/public/storage" ]; then
    echo "🔗 Creando enlace simbólico para public/storage..."
    php /var/www/html/artisan storage:link || true
fi

# Ejecutar migraciones automáticas en QA
if [ "$RUN_MIGRATIONS" = "true" ] || [ -z "$RUN_MIGRATIONS" ]; then
    echo "📦 Ejecutando migraciones de base de datos..."
    php /var/www/html/artisan migrate --force || true
fi

# Ejecutar semilleros si está habilitado
if [ "$RUN_SEEDERS" = "true" ]; then
    echo "🌱 Ejecutando seeders paramétricos..."
    php /var/www/html/artisan db:seed --force || true
fi

# Optimización de cachés de Laravel para producción/QA
echo "⚡ Optimizando cachés de framework..."
php /var/www/html/artisan config:cache || true
php /var/www/html/artisan route:cache || true
php /var/www/html/artisan view:cache || true
php /var/www/html/artisan event:cache || true

# Asegurar permisos correctos para www-data
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

echo "🎉 Entorno NEXO PRO QA preparado. Lanzando servicios (Nginx, PHP-FPM, Reverb, Queue, SSR)..."

exec "$@"

# ==============================================================================
# ETAPA 1: Resolución de Dependencias PHP y Paquetes de Vendor (Composer)
# ==============================================================================
FROM composer:2 AS vendor

WORKDIR /app

COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --prefer-dist --ignore-platform-reqs --no-scripts

# ==============================================================================
# ETAPA 2: Construcción de Assets Frontend (Vite Client + Inertia SSR)
# ==============================================================================
FROM node:20-bookworm-slim AS frontend

WORKDIR /app

COPY package.json package-lock.json ./
RUN npm ci --prefer-offline --no-audit

COPY . .
COPY --from=vendor /app/vendor ./vendor

RUN npm run build && npm run build -- --ssr

# ==============================================================================
# ETAPA 3: Runtime PHP-FPM 8.3 + Nginx + Reverb + Supervisor (Producción)
# ==============================================================================
FROM php:8.3-fpm-bookworm AS production

ENV DEBIAN_FRONTEND=noninteractive \
    COMPOSER_ALLOW_SUPERUSER=1

# Instalar dependencias del sistema y librerías C para extensiones PHP
RUN apt-get update && apt-get install -y --no-install-recommends \
    nginx \
    supervisor \
    curl \
    git \
    unzip \
    libpq-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    libicu-dev \
    netcat-openbsd \
    postgresql-client \
    && rm -rf /var/lib/apt/lists/*

# Configurar e instalar extensiones PHP de alto rendimiento
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo \
        pdo_pgsql \
        pgsql \
        bcmath \
        gd \
        zip \
        intl \
        opcache \
        pcntl

# Instalar extensión Redis para caché de sesión y cola de alta velocidad
RUN pecl install redis \
    && docker-php-ext-enable redis

# Configurar OPcache para producción
RUN { \
    echo 'opcache.memory_consumption=256'; \
    echo 'opcache.interned_strings_buffer=16'; \
    echo 'opcache.max_accelerated_files=20000'; \
    echo 'opcache.revalidate_freq=0'; \
    echo 'opcache.validate_timestamps=0'; \
    echo 'opcache.fast_shutdown=1'; \
    echo 'opcache.enable_cli=1'; \
} > /usr/local/etc/php/conf.d/opcache-recommended.ini

# Instalar Composer binario oficial
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Configurar Nginx y Supervisor
COPY docker/nginx.conf /etc/nginx/sites-available/default
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

WORKDIR /var/www/html

# Instalar dependencias de Composer primero (aprovecha la caché de Docker)
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader --no-scripts

# Copiar el código fuente completo del proyecto
COPY . .

# Copiar los bundles de Vite compilados desde la etapa frontend
COPY --from=frontend /app/public/build ./public/build
COPY --from=frontend /app/bootstrap/ssr ./bootstrap/ssr

# Regenerar autoloader optimizado para producción
RUN composer dump-autoload --optimize --no-dev

# Permisos para el usuario www-data
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 80 8080

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]

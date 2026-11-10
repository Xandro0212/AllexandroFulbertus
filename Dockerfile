# ============================================================
# SIPKA IDB Bali — Dockerfile (production)
# Satu kontainer: nginx + PHP-FPM + Reverb (WebSocket) + Queue worker
# ============================================================

# ---------- Stage 1: dependensi Composer ----------
FROM composer:2 AS vendor
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install \
    --no-dev \
    --no-interaction \
    --no-progress \
    --no-scripts \
    --prefer-dist \
    --optimize-autoloader

# ---------- Stage 2: runtime ----------
FROM php:8.3-fpm-alpine

# Dependensi sistem & ekstensi PHP
RUN apk add --no-cache \
        nginx \
        supervisor \
        gettext \
        libzip \
        oniguruma \
    && apk add --no-cache --virtual .build-deps \
        $PHPIZE_DEPS \
        libzip-dev \
        oniguruma-dev \
    && docker-php-ext-install -j"$(nproc)" \
        pdo_mysql mbstring exif pcntl bcmath zip opcache \
    && apk del .build-deps

# Vendor hasil build stage 1
COPY --from=vendor /app/vendor /var/www/html/vendor

# Source aplikasi
COPY . /var/www/html

# Konfigurasi nginx, supervisor, php
COPY docker/nginx.conf /etc/nginx/http.d/app.conf.template
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY docker/php.ini /usr/local/etc/php/conf.d/zz-app.ini

# Entrypoint
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

WORKDIR /var/www/html

# Generate manifest package discovery + izin storage
RUN php artisan package:discover --ansi \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R ug+rwX storage bootstrap/cache

EXPOSE 80

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]

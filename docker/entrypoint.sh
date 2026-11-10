#!/bin/sh
set -e

cd /var/www/html

echo "[entrypoint] Menyiapkan aplikasi SIPKA..."

# 1) Auto-detect domain publik (Railway / Render)
if [ -n "$RAILWAY_PUBLIC_DOMAIN" ]; then
    : "${APP_URL:=https://$RAILWAY_PUBLIC_DOMAIN}"
    : "${REVERB_HOST:=$RAILWAY_PUBLIC_DOMAIN}"
fi
if [ -n "$RENDER_EXTERNAL_URL" ]; then
    : "${APP_URL:=$RENDER_EXTERNAL_URL}"
    : "${REVERB_HOST:=$(echo "$RENDER_EXTERNAL_URL" | sed 's|https\?://||')}"
fi

# 2) APP_KEY fallback (disarankan setel variabel APP_KEY tetap di platform)
if [ -z "$APP_KEY" ]; then
    echo "[entrypoint] APP_KEY kosong — membuat sementara (sebaiknya setel APP_KEY tetap)."
    APP_KEY="$(php artisan key:generate --show)"
    export APP_KEY
fi

# 3) Izin storage & cache
chown -R www-data:www-data storage bootstrap/cache
chmod -R ug+rwX storage bootstrap/cache

# 4) Pastikan package manifest ter-generate
php artisan package:discover --ansi || true

# 5) Migrasi (idempoten, dengan retry menunggu DB siap)
if [ "${RUN_MIGRATIONS:-true}" = "true" ]; then
    echo "[entrypoint] Menjalankan migrasi..."
    n=0
    until php artisan migrate --force --no-interaction; do
        n=$((n+1))
        if [ "$n" -ge 15 ]; then
            echo "[entrypoint] Migrasi gagal setelah beberapa percobaan."
            break
        fi
        echo "[entrypoint] Database belum siap, coba lagi ($n)..."
        sleep 3
    done
fi

# 6) Seed data demo (jalankan SEKALI saja, lalu setel RUN_SEED=false)
if [ "${RUN_SEED:-false}" = "true" ]; then
    echo "[entrypoint] Menjalankan seeder..."
    php artisan db:seed --force --no-interaction || true
fi

# 7) Optimasi (tanpa route:cache karena ada closure route)
php artisan config:cache || true
php artisan view:cache || true

# 8) Render nginx dengan PORT platform
export PORT="${PORT:-80}"
envsubst '$PORT' < /etc/nginx/http.d/app.conf.template > /etc/nginx/http.d/default.conf

echo "[entrypoint] Menjalankan supervisord (nginx + php-fpm + reverb + queue)..."
exec supervisord -c /etc/supervisor/conf.d/supervisord.conf

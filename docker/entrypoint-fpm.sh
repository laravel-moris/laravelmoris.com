#!/bin/sh
set -eu

cd /var/www/html

# Create necessary directories
mkdir -p /data
mkdir -p bootstrap/cache
mkdir -p storage/framework/cache
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/logs

chown -R www-data:www-data /data /var/www/html/storage /var/www/html/bootstrap/cache 2>/dev/null || true

if [ "${DB_CONNECTION:-}" = "sqlite" ] && [ ! -f "${DB_DATABASE:-/data/database.sqlite}" ]; then
    mkdir -p "$(dirname "${DB_DATABASE:-/data/database.sqlite}")"
    touch "${DB_DATABASE:-/data/database.sqlite}"
    chown www-data:www-data "${DB_DATABASE:-/data/database.sqlite}" 2>/dev/null || true
    echo "Created SQLite database"
fi

echo "Running migrations..."
php artisan migrate --force

echo "Optimizing..."
php artisan optimize

echo "Creating storage link..."
php artisan storage:link 2>/dev/null || true

echo "Starting PHP-FPM..."
exec /usr/sbin/php-fpm --nodaemonize

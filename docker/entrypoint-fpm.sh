#!/bin/sh
set -eu

cd /var/www/html

# Create necessary directories
mkdir -p \
    /data \
    /data/sessions \
    /data/uploads \
    bootstrap/cache \
    storage/framework/cache \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs


if [ "${DB_CONNECTION:-}" = "sqlite" ] && [ ! -f "${DB_DATABASE:-/data/database.sqlite}" ]; then
    mkdir -p "$(dirname "${DB_DATABASE:-/data/database.sqlite}")"
    touch "${DB_DATABASE:-/data/database.sqlite}"
    echo "Created SQLite database"
fi

echo "Filament assets..."
php artisan filament:upgrade
php artisan livewire:publish --assets

echo "Running migrations..."

php -d memory_limit=100M artisan operations:process --tag=before-migrations

php artisan migrate --force

php -d memory_limit=100M artisan operations:process

echo "Optimizing..."
php artisan optimize

echo "Creating storage link..."
php artisan storage:link 2>/dev/null || true

exec php-fpm --nodaemonize

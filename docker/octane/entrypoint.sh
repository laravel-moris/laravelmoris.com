#!/bin/sh
set -eu

cd /var/www/html

mkdir -p \
    /data \
    /data/sessions \
    /data/uploads \
    bootstrap/cache \
    storage/framework/cache \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs

if [ -z "${APP_KEY:-}" ]; then
    APP_KEY="base64:$(php -r 'echo base64_encode(random_bytes(32));')"
    export APP_KEY
fi

if [ "${DB_CONNECTION:-}" = "sqlite" ] && [ ! -f "${DB_DATABASE:-/data/database.sqlite}" ]; then
    mkdir -p "$(dirname "${DB_DATABASE:-/data/database.sqlite}")"
    touch "${DB_DATABASE:-/data/database.sqlite}"
fi

php artisan filament:upgrade
php artisan livewire:publish --assets
# "before-migrations" operations record themselves in the operations table, which
# the migrations below create. On a brand-new database it does not exist yet, so
# running them first fails and the container never starts. They only have
# something to do against an existing database, so skip them until it exists.
if php -r 'require "vendor/autoload.php"; $app = require "bootstrap/app.php"; $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap(); exit(Illuminate\Support\Facades\Schema::hasTable("operations") ? 0 : 1);' >/dev/null 2>&1; then
    php -d memory_limit=100M artisan operations:process --tag=before-migrations
fi
php artisan migrate --force
php -d memory_limit=100M artisan operations:process
php artisan optimize
php artisan filament:optimize
php artisan storage:link 2>/dev/null || true

exec php artisan octane:frankenphp --host=0.0.0.0 --port=8080

############################
# Frontend build
############################
FROM node:20-bookworm AS frontend

WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci
COPY . .
RUN npm run build


############################
# Build hivemind (reliable across arch)
############################
FROM golang:1.22-bookworm AS hivemind
WORKDIR /src
RUN go install github.com/DarthSim/hivemind@v1.1.0


############################
# PHP build stage
############################
FROM debian:bookworm AS build

ENV DEBIAN_FRONTEND=noninteractive

# Base deps + Surý PHP repo
RUN apt-get update && apt-get install -y --no-install-recommends \
    ca-certificates curl gnupg lsb-release unzip git \
 && curl -fsSL https://packages.sury.org/php/apt.gpg \
    | gpg --dearmor -o /usr/share/keyrings/sury-php.gpg \
 && echo "deb [signed-by=/usr/share/keyrings/sury-php.gpg] https://packages.sury.org/php/ $(lsb_release -sc) main" \
    > /etc/apt/sources.list.d/sury-php.list \
 && apt-get update

# PHP 8.4 + extensions (Debian packaging-correct set)
RUN apt-get install -y --no-install-recommends \
    php8.4 php8.4-fpm \
    php8.4-curl php8.4-gd php8.4-intl php8.4-mbstring \
    php8.4-xml php8.4-zip php8.4-sqlite3 \
    php8.4-opcache \
    composer \
 && rm -rf /var/lib/apt/lists/*

WORKDIR /app

COPY composer.json composer.lock ./
RUN composer install \
    --no-dev \
    --no-interaction \
    --no-scripts \
    --prefer-dist \
    --optimize-autoloader

COPY . .
COPY --from=frontend /app/public/build /app/public/build
RUN composer dump-autoload --optimize --no-dev


############################
# Production image
############################
FROM debian:bookworm

ENV DEBIAN_FRONTEND=noninteractive

# System + Surý repo + nginx + PHP
RUN apt-get update && apt-get install -y --no-install-recommends \
    ca-certificates curl gnupg lsb-release nginx passwd \
 && curl -fsSL https://packages.sury.org/php/apt.gpg \
    | gpg --dearmor -o /usr/share/keyrings/sury-php.gpg \
 && echo "deb [signed-by=/usr/share/keyrings/sury-php.gpg] https://packages.sury.org/php/ $(lsb_release -sc) main" \
    > /etc/apt/sources.list.d/sury-php.list \
 && apt-get update \
 && apt-get install -y --no-install-recommends \
    php8.4 php8.4-fpm \
    php8.4-curl php8.4-gd php8.4-intl php8.4-mbstring \
    php8.4-xml php8.4-zip php8.4-sqlite3 \
    php8.4-opcache \
    composer \
 && groupmod -g 82 www-data \
 && usermod -u 82 -g 82 www-data \
 && rm -rf /var/lib/apt/lists/*

# Modify PHP-FPM global config
RUN sed -i 's|^;*error_log =.*|error_log = /dev/stderr|' /etc/php/8.4/fpm/php-fpm.conf \
 && sed -i 's|^;*daemonize =.*|daemonize = no|' /etc/php/8.4/fpm/php-fpm.conf \
 && sed -i 's|^;*pid =.*|pid = /tmp/php-fpm.pid|' /etc/php/8.4/fpm/php-fpm.conf

# Hivemind
COPY --from=hivemind /go/bin/hivemind /usr/local/bin/hivemind

# Directories & permissions
RUN mkdir -p \
      /data /data/sessions /data/uploads \
      /var/lib/nginx/logs /var/lib/nginx/body \
 && chown -R www-data:www-data /data /var/lib/nginx

WORKDIR /var/www/html

# Configs
COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/php-fpm-pool.conf /etc/php/8.4/fpm/pool.d/zz-app.conf
COPY docker/php-opcache.ini /etc/php/8.4/fpm/conf.d/zz-opcache.ini
COPY docker/Procfile /etc/Procfile

# Entrypoint used by Procfile
COPY docker/entrypoint-fpm.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

# App
COPY --from=build --chown=www-data:www-data /app /var/www/html

RUN mkdir -p \
    /data /data/sessions /data/uploads \
    /var/www/html/storage/app \
    /var/www/html/storage/framework/cache \
    /var/www/html/storage/framework/sessions \
    /var/www/html/storage/framework/views \
    /var/www/html/storage/logs \
    /var/www/html/bootstrap/cache \
 && chown -R www-data:www-data /var/www/html /data \
 && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache /data \
 && rm /var/www/html/index.nginx-debian.html

USER 82
EXPOSE 8080

ENV APP_ENV=production \
    APP_DEBUG=false \
    LOG_CHANNEL=stderr \
    DB_CONNECTION=sqlite \
    DB_DATABASE=/data/database.sqlite

CMD ["/usr/local/bin/hivemind", "/etc/Procfile"]

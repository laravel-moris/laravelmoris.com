FROM node:20-alpine AS frontend
WORKDIR /app
COPY package.json package-lock.json ./
RUN --mount=type=cache,target=/root/.npm npm ci
COPY resources resources
COPY vite.config.js vite.config.js
RUN npm run build

FROM golang:1.22-alpine AS hivemind
WORKDIR /src
RUN --mount=type=cache,target=/go/pkg/mod \
    --mount=type=cache,target=/root/.cache/go-build \
    go install github.com/DarthSim/hivemind@v1.1.0

FROM php:8.4-fpm-alpine AS phpbase

ADD --chmod=0755 https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN apk add --no-cache \
    nginx \
    && install-php-extensions curl gd intl mbstring xml zip sqlite3 opcache \
    && rm -rf /tmp/* /var/cache/apk/* /usr/local/bin/install-php-extensions

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

FROM phpbase AS build

RUN apk add --no-cache \
    unzip \
    git

WORKDIR /app

COPY composer.json composer.lock ./
RUN --mount=type=cache,target=/root/.composer/cache \
    composer install \
        --no-dev \
        --no-interaction \
        --no-scripts \
        --prefer-dist \
        --optimize-autoloader

COPY . .
COPY --from=frontend /app/public/build /app/public/build

RUN composer dump-autoload --optimize --no-dev \
    && rm -rf /root/.composer/cache

FROM phpbase AS final

RUN apk add --no-cache \
    nginx \
    shadow \
    && rm -rf /var/cache/apk/*

RUN groupmod -g 82 www-data \
    && usermod -u 82 -g 82 www-data

COPY --from=hivemind /go/bin/hivemind /usr/local/bin/hivemind

COPY docker/php-fpm.conf /usr/local/etc/php-fpm.conf
COPY docker/php-fpm-pool.conf /usr/local/etc/php-fpm.d/zz-app.conf
COPY docker/php-opcache.ini /usr/local/etc/php/conf.d/zz-opcache.ini
COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/Procfile /etc/Procfile
COPY docker/entrypoint-fpm.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

WORKDIR /var/www/html

COPY --from=build --chown=www-data:www-data /app/app /var/www/html/app
COPY --from=build --chown=www-data:www-data /app/bootstrap /var/www/html/bootstrap
COPY --from=build --chown=www-data:www-data /app/config /var/www/html/config
COPY --from=build --chown=www-data:www-data /app/database /var/www/html/database
COPY --from=build --chown=www-data:www-data /app/public /var/www/html/public
COPY --from=build --chown=www-data:www-data /app/resources /var/www/html/resources
COPY --from=build --chown=www-data:www-data /app/routes /var/www/html/routes
COPY --from=build --chown=www-data:www-data /app/storage /var/www/html/storage
COPY --from=build --chown=www-data:www-data /app/vendor /var/www/html/vendor
COPY --from=build --chown=www-data:www-data /app/operations /var/www/html/operations
COPY --from=build --chown=www-data:www-data /app/artisan /var/www/html/artisan
COPY --from=build --chown=www-data:www-data /app/composer.json /var/www/html/composer.json
COPY --from=build --chown=www-data:www-data /app/composer.lock /var/www/html/composer.lock

RUN mkdir -p \
        /data/sessions \
        /data/uploads \
        /var/lib/nginx/logs \
        /var/lib/nginx/body \
        /var/www/html/storage/framework/cache \
        /var/www/html/storage/framework/sessions \
        /var/www/html/storage/framework/views \
        /var/www/html/storage/logs \
        /var/www/html/bootstrap/cache \
    && chown -R www-data:www-data \
        /data \
        /var/lib/nginx \
        /var/www/html/storage \
        /var/www/html/bootstrap/cache \
    && chmod -R 775 \
        /var/www/html/storage \
        /var/www/html/bootstrap/cache \
        /data \
    && rm -f /var/www/html/public/index.nginx-debian.html 2>/dev/null || true \
    && rm -rf \
        /usr/share/nginx/html/* \
        /var/cache/apk/* \
        /tmp/*

USER 82
EXPOSE 8080

ENV APP_ENV=production \
    APP_DEBUG=false \
    LOG_CHANNEL=stderr \
    DB_CONNECTION=sqlite \
    DB_DATABASE=/data/database.sqlite

CMD ["/usr/local/bin/hivemind", "/etc/Procfile"]

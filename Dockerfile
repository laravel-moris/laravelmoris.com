FROM docker.io/library/node:20-alpine AS frontend

WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci

COPY . .
RUN npm run build

FROM ghcr.io/shyim/wolfi-php/fpm:8.4 AS build

WORKDIR /app

RUN apk add --no-cache \
    php-8.4 \
    php-8.4-curl \
    php-8.4-openssl \
    php-8.4-phar \
    php-8.4-intl \
    php-8.4-fileinfo \
    php-8.4-dom \
    php-8.4-iconv \
    php-8.4-xml \
    php-8.4-xmlreader \
    php-8.4-mbstring \
    php-8.4-ctype \
    php-8.4-zip \
    php-8.4-pdo \
    php-8.4-pdo_sqlite \
    composer \
    git

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

FROM ghcr.io/shyim/wolfi-php/nginx:8.4 AS production

RUN apk add --no-cache \
    php-8.4 \
    php-8.4-curl \
    php-8.4-openssl \
    php-8.4-phar \
    php-8.4-intl \
    php-8.4-fileinfo \
    php-8.4-dom \
    php-8.4-iconv \
    php-8.4-xml \
    php-8.4-xmlreader \
    php-8.4-mbstring \
    php-8.4-ctype \
    php-8.4-zip \
    php-8.4-pdo \
    php-8.4-pdo_sqlite \
    php-8.4-mbstring \
    composer \
    gosu

RUN mkdir -p /var/lib/nginx/logs /var/lib/nginx/body /data \
    && chown -R www-data:www-data /var/lib/nginx /data

WORKDIR /var/www/html

COPY docker/nginx.conf /etc/nginx/nginx.conf

COPY docker/php-fpm-pool.conf /etc/php/php-fpm.d/zz-app.conf

COPY docker/php-opcache.ini /etc/php/conf.d/zz-opcache.ini

COPY docker/entrypoint-fpm.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

COPY docker/Procfile /etc/Procfile

COPY --from=build --chown=www-data:www-data /app /var/www/html

# USER www-data
USER 82

EXPOSE 8080

ENV APP_ENV=production \
    APP_DEBUG=false \
    LOG_CHANNEL=stderr \
    DB_CONNECTION=sqlite \
    DB_DATABASE=/data/database.sqlite

# syntax=docker/dockerfile:1
FROM php:8.2-cli-alpine AS base

RUN apk add --no-cache \
    icu-dev \
    libzip-dev \
    oniguruma-dev \
    sqlite-dev \
    linux-headers \
    && docker-php-ext-configure intl \
    && docker-php-ext-install -j$(nproc) \
    bcmath \
    intl \
    opcache \
    pdo_sqlite \
    pcntl \
    zip

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
ENV COMPOSER_ALLOW_SUPERUSER=1

WORKDIR /app

COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist

COPY . .

RUN composer dump-autoload --optimize && composer run-script post-autoload-dump --no-interaction || true

RUN addgroup -g 1000 app && adduser -u 1000 -G app -s /bin/sh -D app

RUN apk add --no-cache su-exec && chmod +x /app/docker-entrypoint.sh

VOLUME /app/storage /app/database

EXPOSE 8000

HEALTHCHECK --interval=10s --timeout=3s --start-period=5s --retries=3 \
    CMD php -r "echo file_exists('/app/bootstrap/app.php') ? 0 : 1;" || exit 1

ENTRYPOINT ["/app/docker-entrypoint.sh"]
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]

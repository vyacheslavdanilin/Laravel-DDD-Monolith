#!/bin/sh
set -e

cd /app

# Ensure writable dirs for named volumes (run as root)
chown -R app:app /app/storage /app/database 2>/dev/null || true

if [ ! -f .env ]; then
    cp .env.example .env
    php artisan key:generate --force
    chown app:app .env
fi

if [ ! -f database/database.sqlite ]; then
    touch database/database.sqlite
    chown app:app database/database.sqlite
fi

php artisan migrate --force --no-interaction

exec su-exec app "$@"

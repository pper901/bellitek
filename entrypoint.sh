#!/bin/bash
set -e

cd /var/www/app

# Clear caches
php artisan cache:clear || true
php artisan config:clear || true
php artisan route:clear || true
php artisan optimize:clear || true
php artisan view:clear || true

# Rebuild config/routes caches
php artisan config:cache || true
php artisan route:cache || true

# Start Apache
exec "$@"


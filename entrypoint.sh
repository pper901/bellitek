#!/bin/bash

# Change directory to the application root where artisan resides
cd /var/www/app

# --- 1. AGGRESSIVE CONFIGURATION CLEANUP ---
# Run configuration clear multiple times to remove any potential old cache files.
echo "Aggressively clearing ALL caches..."
php artisan cache:clear || true
php artisan config:clear || true
php artisan route:clear || true
php artisan view:clear || true
# --------------------------------------------


# 2. Generate Application Key (CRITICAL for sessions/cookies)
echo "Generating application key..."
php artisan key:generate --force

# 3. Run Database Migrations
echo "Running database migrations..."
php artisan migrate --force


# 4. Cache configuration
echo "Rebuilding configuration and routes..."
php artisan config:cache
php artisan cache:clear
php artisan route:cache


# 5. Start the main command (Apache)
exec "$@"
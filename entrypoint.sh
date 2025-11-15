#!/bin/bash

# Change directory to the application root where artisan resides
cd /var/www/app

# 1. Generate Application Key (CRITICAL for sessions/cookies)
echo "Generating application key..."
php artisan key:generate --force

# 2. Run Database Migrations
echo "Running database migrations..."
php artisan migrate --force

# 3. Cache configuration
echo "Clearing and caching configuration..."
php artisan config:clear
php artisan config:cache

# 4. Start the main command (Apache)
exec "$@"
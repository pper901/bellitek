#!/bin/bash

# 1. Run Database Migrations
echo "Running database migrations..."
php artisan migrate --force

# 2. Cache configuration
echo "Clearing and caching configuration..."
php artisan config:clear
php artisan config:cache

# 3. Start the main command (Apache)
exec "$@"
#!/bin/bash

# Ensure the APP_KEY is set. If not, generate one (this is a fallback)
if [ -z "$APP_KEY" ]; then
    echo "Generating application key..."
    php artisan key:generate --force
fi

# Clear and cache the configuration, ensuring it uses the environment variables
# provided by the hosting environment (Render).
echo "Clearing and caching configuration..."
php artisan config:clear
php artisan config:cache

# Run database migrations
echo "Running database migrations..."
php artisan migrate --force --no-interaction

# Execute the main command (e.g., apache2-foreground)
exec "$@"
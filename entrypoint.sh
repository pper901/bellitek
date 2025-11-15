#!/bin/bash

# Change directory to the application root where artisan resides
cd /var/www/app

# --- CRITICAL FIX FOR REDIRECT LOOP (ERR_TOO_MANY_REDIRECTS) ---
# Inject \URL::forceScheme('https'); into the AppServiceProvider's boot method.
# This forces Laravel to generate all internal URLs and redirects using HTTPS.
SERVICE_PROVIDER="/var/www/app/app/Providers/AppServiceProvider.php"

if [ -f "$SERVICE_PROVIDER" ]; then
    echo "Patching AppServiceProvider to force HTTPS scheme..."
    # Insert the necessary code after 'public function boot(): void'
    sed -i "/public function boot(): void/a \        \Illuminate\Support\Facades\URL::forceScheme('https');" "$SERVICE_PROVIDER"
else
    echo "Error: AppServiceProvider.php not found at $SERVICE_PROVIDER. Redirect loop may persist."
fi
# -----------------------------------------------------------------


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
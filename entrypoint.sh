#!/bin/bash

# Change directory to the application root where artisan resides
cd /var/www/app

# --- CRITICAL FIX FOR REDIRECT LOOP (ERR_TOO_MANY_REDIRECTS) ---
# Inject code into public/index.php to force HTTPS recognition, bypassing proxy confusion.
INDEX_FILE="/var/www/app/public/index.php"

if [ -f "$INDEX_FILE" ]; then
    echo "Patching index.php to force HTTPS recognition..."
    # Insert the necessary PHP code right after the opening <?php tag
    # This tells the Request object that the connection is secure by setting $_SERVER['HTTPS']
    sed -i '/<?php/a if (isset($_SERVER[\x27HTTP_X_FORWARDED_PROTO\x27]) && $_SERVER[\x27HTTP_X_FORWARDED_PROTO\x27] === \x27https\x27) { $_SERVER[\x27HTTPS\x27] = \x27on\x27; }' "$INDEX_FILE"
else
    echo "Error: index.php not found at $INDEX_FILE"
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
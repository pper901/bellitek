#!/bin/bash

# 1. Update .htaccess in the public folder to explicitly allow symbolic links.
# This is required for Laravel to function correctly when using the symlink trick.
HTACCESS_FILE="/var/www/html/public/.htaccess"

# Inject the Options directive at the beginning of the .htaccess file
if ! grep -q "Options FollowSymLinks" "$HTACCESS_FILE"; then
    echo "Adding Options FollowSymLinks to $HTACCESS_FILE"
    # The '1i' command inserts text at the first line
    sed -i '1iOptions FollowSymLinks' "$HTACCESS_FILE"
else
    echo "Options FollowSymLinks already present in $HTACCESS_FILE"
fi

# 2. Run Database Migrations
echo "Running database migrations..."
php artisan migrate --force

# 3. Cache configuration 
echo "Clearing and caching configuration..."
php artisan config:clear
php artisan config:cache

# 4. Start the main command (Apache)
exec "$@"
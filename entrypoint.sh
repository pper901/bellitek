#!/bin/bash
set -e

cd /var/www/app

echo "======================================"
echo "Starting Bellitek"
echo "======================================"

echo "PHP version:"
php -v || true

echo "Checking Laravel:"
php artisan --version || echo "WARNING: Artisan failed to initialize!"

echo "Environment:"
echo "APP_ENV=${APP_ENV}"
echo "APP_DEBUG=${APP_DEBUG}"
echo "APP_URL=${APP_URL}"

echo "======================================"
echo "Setting Permissions & Clearing Caches"
echo "======================================"

# Ensure web server can write to storage and bootstrap cache
chown -R www-data:www-data /var/www/app/storage /var/www/app/bootstrap/cache || true
chmod -R 775 /var/www/app/storage /var/www/app/bootstrap/cache || true

# Safe cache clear (will not kill script on failure)
php artisan config:clear || true
php artisan cache:clear || true
php artisan route:clear || true
php artisan view:clear || true

echo "======================================"
echo "Database Migrations"
echo "======================================"

php artisan migrate --force || echo "WARNING: Migrations failed; continuing startup..."

echo "======================================"
echo "Starting GeneralClass Java Server"
echo "======================================"

if [ -f "/var/www/app/generalclass/app.jar" ]; then
    echo "Starting Java server on port ${PORT:-8090}..."
    java -jar /var/www/app/generalclass/app.jar &
    JAVA_PID=$!
    echo "Java PID: $JAVA_PID"
else
    echo "WARNING: Java JAR not found!"
fi

echo "======================================"
echo "Starting Apache"
echo "======================================"

exec "$@"
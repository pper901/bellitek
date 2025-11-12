# Use official PHP image with Apache
FROM php:8.3-apache

# Install system dependencies & PHP extensions
RUN apt-get update && apt-get install -y \
    git unzip libpng-dev libonig-dev libxml2-dev zip curl npm nodejs libpq-dev && \
    docker-php-ext-install pdo_pgsql pdo_mysql mbstring exif pcntl bcmath gd

# Enable Apache rewrite module
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www/html

# Copy project files
COPY . .

# Configure Apache to use Laravel public folder
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|' /etc/apache2/sites-available/000-default.conf

# Set permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Copy .env.example to .env
RUN cp .env.example .env

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Generate app key and cache configs
RUN php artisan key:generate --ansi
RUN php artisan config:cache
RUN php artisan route:cache

# Build assets (Vite + Tailwind)
RUN npm install
RUN npm run build

# Run migrations
RUN php artisan migrate --force

# Expose default Apache port
EXPOSE 80

# Start Apache in foreground
CMD ["apache2-foreground"]

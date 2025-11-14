# Use official PHP image with Apache
FROM php:8.3-apache

# --- 1. SYSTEM & PHP EXTENSION INSTALLATION ---
RUN apt-get update && apt-get install -y --no-install-recommends \
    git \
    unzip \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    zip \
    curl \
    nodejs \
    npm \
    && rm -rf /var/lib/apt/lists/*

# Install required PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd pdo pdo_pgsql

# Enable Apache rewrite module
RUN a2enmod rewrite

# --- 2. APPLICATION SETUP ---
# Set working directory
WORKDIR /var/www/html

# Copy project files
COPY . .

# Set permissions for storage (CRITICAL for Laravel)
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Configure Apache to use Laravel public folder
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|' /etc/apache2/sites-available/000-default.conf
RUN sed -i 's|/var/www/|/var/www/html/|' /etc/apache2/apache2.conf

# --- 3. COMPOSER & ASSET BUILD ---
# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Install PHP dependencies
# Use --no-dev and --optimize-autoloader for production
RUN composer install --no-dev --optimize-autoloader

# Build assets (Vite + Tailwind)
RUN npm install
RUN npm run build

# --- 4. OPTIMIZED RUNTIME ENVIRONMENT ---
# Define build environment variables (These are used during the build, but overwritten at runtime)
ENV VITE_APP_URL=https://bellitek-1.onrender.com
# Use the correct internal port for Apache in the official image
ENV APACHE_LISTEN_PORT=10000

# Copy .env.example to .env (Will be overwritten by Render secrets at runtime)
RUN cp .env.example .env

# Expose the internal port (Render maps the external port to this internal one)
EXPOSE 10000

# --- 5. PRODUCTION COMMANDS (Use the Apache server) ---
# Use an entrypoint script to run commands BEFORE Apache starts
COPY entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]

# Start Apache in foreground (CMD is executed by the ENTRYPOINT script)
CMD ["apache2-foreground"]
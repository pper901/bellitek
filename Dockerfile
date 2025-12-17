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

# Add ServerName to suppress AH00558 warning (optional, but cleaner logs)
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# --- 2. APPLICATION SETUP (DIRECTORY SWAP) ---
# Set temporary working directory for file copy
WORKDIR /usr/src/app

# Copy project files into a source folder
COPY . .

# Set permissions for storage (CRITICAL for Laravel)
RUN chown -R www-data:www-data /usr/src/app/storage /usr/src/app/bootstrap/cache
RUN chmod -R 775 /usr/src/app/storage /usr/src/app/bootstrap/cache

# --- 3. COMPOSER & ASSET BUILD ---
# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Build assets (Vite + Tailwind)
RUN npm install
# CRITICAL VITE FIX: Explicitly set VITE_APP_URL before the build step
ENV VITE_APP_URL=https://bellitek-1.onrender.com 
RUN npm run build 

# --- 4. APACHE CONTAINER SETUP (The Unstoppable Fix) ---

# 1. Clean up default Apache content (the old /var/www/html)
RUN rm -rf /var/www/html

# 2. Delete the default VHost
RUN a2dissite 000-default.conf || true 

# 3. Create the necessary link structure: 
# Move the compiled app to a new place
RUN mv /usr/src/app /var/www/app

# 4. Create a symlink named 'html' that points to the 'public' folder of the app.
RUN ln -s /var/www/app/public /var/www/html

# 5. Set final permissions on the actual web root
RUN chown -R www-data:www-data /var/www/app

# --- CRITICAL .HTACCESS OVERWRITE ---
# Overwrite the default .htaccess with a simple, safe version.
RUN echo 'Options +FollowSymLinks\n' \
    'RewriteEngine On\n' \
    '\n' \
    'RewriteCond %{REQUEST_FILENAME} !-d\n' \
    'RewriteCond %{REQUEST_FILENAME} !-f\n' \
    'RewriteRule ^ index.php [L]' > /var/www/app/public/.htaccess

# 6. Add the FINAL VHost configuration
RUN echo '<VirtualHost *:80>\n' \
    '    DocumentRoot /var/www/html\n' \
    '    \n' \
    '    # CRITICAL: Disable Apache canonicalization that can cause redirects\n' \
    '    UseCanonicalName Off\n' \
    '    \n' \
    '    <Directory /var/www/html>\n' \
    '        Options Indexes FollowSymLinks\n' \
    '        AllowOverride All\n' \
    '        Require all granted\n' \
    '    </Directory>\n' \
    '</VirtualHost>' > /etc/apache2/sites-available/laravel-ultimate.conf

# 7. Enable the ultimate VHost.
RUN a2ensite laravel-ultimate.conf

# --- END OF FINAL APACHE CONFIGURATION FIX ---

# --- 5. OPTIMIZED RUNTIME ENVIRONMENT ---
# --- CRITICAL FIX FOR PORT BINDING ON RENDER ---
ENV PORT=10000 
RUN echo "Listen ${PORT}" >> /etc/apache2/ports.conf
# -----------------------------------------------

# FIX: Update the path since the files are now moved to /var/www/app
# Copy .env.example to .env (Will be overwritten by Render secrets at runtime)
RUN cp /var/www/app/.env.example /var/www/app/.env

# Expose the internal port
EXPOSE 10000

# --- 6. PRODUCTION COMMANDS (Use the Apache server) ---
# Use an entrypoint script to run commands BEFORE Apache starts
COPY entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]

# Start Apache in foreground
CMD ["apache2-foreground"]
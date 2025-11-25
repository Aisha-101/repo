# Use official PHP 8.2 with Apache
FROM php:8.2-apache

# Set working directory
WORKDIR /var/www/html

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git unzip libsqlite3-dev libonig-dev libzip-dev zip \
    && docker-php-ext-install pdo pdo_sqlite mbstring zip \
    && a2enmod rewrite

# Copy Composer from official image
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy all project files
COPY . .

# Copy example env and set permissions
RUN if [ ! -f .env ]; then cp .env.example .env; fi
RUN chown -R www-data:www-data storage bootstrap/cache

# Install PHP dependencies
RUN composer install --optimize-autoloader --no-dev

# Generate Laravel app key
RUN php artisan key:generate

# Expose port 80
EXPOSE 80

# Start Apache in foreground
CMD ["apache2-foreground"]

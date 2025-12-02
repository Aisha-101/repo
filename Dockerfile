# Use official PHP 8.2 with Apache
FROM php:8.2-apache

WORKDIR /var/www/html/public

RUN apt-get update && apt-get install -y \
    git unzip libsqlite3-dev libonig-dev libzip-dev zip \
    && docker-php-ext-install pdo pdo_sqlite mbstring zip \
    && a2enmod rewrite

# Fix Apache ServerName warning
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Copy Composer from official image
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy project files
COPY . .

# Copy example env and set permissions
RUN if [ ! -f .env ]; then cp .env.example .env; fi
RUN chown -R www-data:www-data storage bootstrap/cache

# Install PHP dependencies
RUN composer install --optimize-autoloader --no-dev

# Generate Laravel app key
RUN php artisan key:generate

EXPOSE 80

CMD ["apache2-foreground"]

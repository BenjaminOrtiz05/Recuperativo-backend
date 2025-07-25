FROM php:8.2-apache

# Instala dependencias
RUN apt-get update && apt-get install -y \
    libonig-dev \
    libzip-dev \
    unzip \
    zip \
    git \
    curl \
    && docker-php-ext-install pdo_mysql zip mbstring

# Instala Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copia la app
COPY . /var/www/html

WORKDIR /var/www/html

# Instala dependencias
RUN composer install --no-dev --optimize-autoloader

# Configura permisos y caches
RUN chown -R www-data:www-data /var/www/html \
    && php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache

EXPOSE 80

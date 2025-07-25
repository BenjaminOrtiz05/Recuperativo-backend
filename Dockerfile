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

# Instala dependencias PHP
RUN composer install --no-dev --optimize-autoloader

# Cambia DocumentRoot a /var/www/html/public
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|' /etc/apache2/sites-available/000-default.conf

# Permite que .htaccess funcione (AllowOverride All)
RUN sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# Habilita mod_rewrite para Laravel
RUN a2enmod rewrite

# Otorga permisos a storage y bootstrap/cache (importante para Laravel)
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Genera caches de configuración, rutas y vistas
RUN php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache

EXPOSE 80

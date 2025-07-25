FROM php:8.2-apache

# Instala dependencias necesarias
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

# Copia los archivos del proyecto
COPY . /var/www/html

# Define directorio de trabajo
WORKDIR /var/www/html

# Instala dependencias PHP sin desarrollo
RUN composer install --no-dev --optimize-autoloader

# Cambia DocumentRoot a /public
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|' /etc/apache2/sites-available/000-default.conf

# Habilita mod_rewrite y AllowOverride para .htaccess
RUN sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf \
    && a2enmod rewrite

# Asigna permisos correctos a Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Copia el script de inicio
COPY start.sh /start.sh
RUN chmod +x /start.sh

# Usa el script como comando de inicio
CMD ["/start.sh"]

# Expone el puerto por defecto de Apache
EXPOSE 80

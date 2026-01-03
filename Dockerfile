# Utilise PHP 8.3 avec Apache
FROM php:8.3-apache

# Installation des dépendances système pour PostgreSQL
RUN apt-get update && apt-get install -y \
    libpq-dev \
    git \
    unzip \
    && docker-php-ext-install pdo pdo_pgsql

# Configuration d'Apache (Active le mod_rewrite pour Laravel)
RUN sed -i 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf
RUN a2enmod rewrite

# Copier les fichiers du projet
WORKDIR /var/www/html
COPY . .

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader

# Donner les permissions aux dossiers Laravel
RUN chown -R www-data:www-data storage bootstrap/cache

EXPOSE 80
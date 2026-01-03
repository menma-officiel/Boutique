# Dockerfile for Menma Shop (Laravel)
FROM php:8.2-fpm

# Arguments
ARG USER=www-data

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpq-dev \
    zip \
    unzip \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    && docker-php-ext-install pdo_pgsql pdo_mysql mbstring exif pcntl bcmath gd zip

# Install composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy composer files first for better cache
COPY composer.json composer.lock ./
RUN composer install --prefer-dist --no-scripts --no-autoloader --no-dev -q

# Copy application code
COPY . .

# Install composer dependencies and generate autoload
RUN composer install --prefer-dist -q && composer dump-autoload -o

# Permissions
RUN chown -R ${USER}:${USER} /var/www/html/storage /var/www/html/bootstrap/cache || true

EXPOSE 9000
CMD ["php-fpm"]

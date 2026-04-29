FROM php:8.3-fpm

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    curl

RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# 1. Copiar TODO el proyecto primero
COPY . .

# 2. Instalar dependencias PHP
RUN composer install --no-dev --optimize-autoloader

# 3. Crear storage y permisos
RUN mkdir -p storage/framework/{sessions,views,cache} && \
    mkdir -p storage/logs && \
    chmod -R 775 storage && \
    chmod -R 775 bootstrap/cache

RUN test -f .env || cp .env.example .env

RUN php artisan key:generate --force || true

RUN chown -R www-data:www-data /var/www/html

CMD ["php-fpm"]

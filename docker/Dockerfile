FROM php:8.3-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    curl

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy the entire project FIRST
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Create storage directories with correct permissions
RUN mkdir -p storage/framework/{sessions,views,cache} && \
    mkdir -p storage/logs && \
    chmod -R 775 storage && \
    chmod -R 775 bootstrap/cache

# Copy .env if it doesn't exist
RUN test -f .env || cp .env.example .env

# Generate application key
RUN php artisan key:generate --force || true

# Set correct ownership
RUN chown -R www-data:www-data /var/www/html

CMD ["php-fpm"]

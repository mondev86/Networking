#!/bin/bash
# Copy this file to your Linux project and run it
# This will update the Docker configuration

# Create docker/Dockerfile
cat > docker/Dockerfile << 'DOCKERFILE_EOF'
FROM php:8.3-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    curl \
    nodejs \
    npm

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy configuration files
COPY composer.json composer.lock ./

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Copy package files
COPY package.json package-lock.json ./

# Install Node dependencies
RUN npm install

# Build production assets
RUN npm run build

# Copy application files
COPY . .

# Create storage directories with correct permissions
RUN mkdir -p storage/framework/{sessions,views,cache} && \
    mkdir -p storage/logs && \
    chmod -R 775 storage && \
    chmod -R 775 bootstrap/cache

# Copy .env if it doesn't exist
RUN test -f .env || cp .env.example .env

# Generate application key
RUN php artisan key:generate --force

# Set correct ownership
RUN chown -R www-data:www-data /var/www/html

CMD ["php-fpm"]
DOCKERFILE_EOF

echo "docker/Dockerfile created!"

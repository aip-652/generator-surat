# Stage 1: Build dependencies with Composer
FROM composer:2 AS vendor

# BENAR: Menggunakan apk untuk Alpine Linux
RUN apk update && apk add --no-cache \
    libzip-dev \
    postgresql-dev \
    gd-dev \
    libpng-dev \
    jpeg-dev \
    freetype-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd pdo pdo_pgsql zip

WORKDIR /app
COPY database/ database/
COPY composer.json composer.json
COPY composer.lock composer.lock
RUN composer install --no-dev --no-interaction --optimize-autoloader --no-scripts

# Stage 2: Final application image
FROM php:8.2-fpm-alpine
WORKDIR /app

# Install necessary PHP extensions
RUN apk add --no-cache libzip-dev postgresql-dev gd-dev \
    && docker-php-ext-install pdo pdo_pgsql zip gd

# Copy vendor files from the first stage
COPY --from=vendor /app/vendor/ vendor/

# Copy application code
COPY . .

# Set correct permissions
RUN chown -R www-data:www-data storage bootstrap/cache

# Expose port for PHP-FPM
EXPOSE 9000

# Start PHP-FPM
CMD ["php-fpm"]
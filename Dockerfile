# ---------- Stage 1 : Composer dependencies ----------
FROM composer:2.7 AS vendor

WORKDIR /app
COPY composer.json composer.lock ./
RUN composer require barryvdh/laravel-dompdf \
    --no-interaction \
    --no-progress\
    --no-scripts
RUN composer install \
    --no-dev \
    --optimize-autoloader \
    --no-interaction \
    --no-scripts

# ---------- Stage 2 : Build frontend ----------
FROM node:20-alpine AS frontend

WORKDIR /app
COPY package*.json ./
RUN npm install
COPY . .
RUN php artisan package:discover --ansi || true
RUN npm run build

# ---------- Stage 3 : Runtime ----------
FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    git zip unzip supervisor \
    libpq-dev libpng-dev libfreetype6-dev libjpeg62-turbo-dev \
    libonig-dev libxml2-dev libzip-dev postgresql-client \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_pgsql pgsql gd bcmath opcache mbstring dom xml zip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Working dir
WORKDIR /var/www

# Copy project
COPY . .

# Copy composer vendor dari stage 1
COPY --from=vendor /app/vendor ./vendor

# Copy frontend build dari stage 2
COPY --from=frontend /app/public/build ./public/build

# Permission
RUN mkdir -p storage bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Clear config
RUN php artisan config:clear

# Entrypoint
COPY entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 9000

ENTRYPOINT ["entrypoint.sh"]
CMD ["php-fpm"]

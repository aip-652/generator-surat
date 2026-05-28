# ---------- Stage 1 : Composer dependencies ----------
FROM php:8.2-cli AS vendor

WORKDIR /app

# Install system dependencies + PHP extensions (WAJIB untuk maatwebsite/excel)
RUN apt-get update && apt-get install -y \
    git zip unzip \
    libpng-dev libjpeg-dev libfreetype6-dev \
    libzip-dev libxml2-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd zip

# Install composer
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

# Copy full project (IMPORTANT)
COPY . .

# Install PHP dependencies (production only)
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
RUN npm run build


# ---------- Stage 3 : Runtime ----------
FROM php:8.2-fpm

WORKDIR /var/www

# Install required PHP extensions
RUN apt-get update && apt-get install -y \
    git zip unzip supervisor nano \
    libpq-dev libpng-dev libfreetype6-dev libjpeg62-turbo-dev \
    libonig-dev libxml2-dev libzip-dev postgresql-client \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
        pdo pdo_pgsql pgsql gd bcmath opcache mbstring dom xml zip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Copy application source
COPY . .

# Copy vendor from build stage
COPY --from=vendor /app/vendor ./vendor

# Copy frontend build
COPY --from=frontend /app/public/build ./public/build

# Permissions
RUN mkdir -p storage bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Entrypoint
COPY entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 9000

ENTRYPOINT ["entrypoint.sh"]
CMD ["php-fpm"]
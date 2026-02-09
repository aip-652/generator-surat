# Gunakan PHP-FPM 8.2
FROM php:8.2-fpm

# Install dependensi sistem & ekstensi PostgreSQL
RUN apt-get update --fix-missing && apt-get install -y \
    apt-transport-https ca-certificates lsb-release gnupg curl \
    git zip unzip supervisor \
    libpq-dev libpng-dev libfreetype6-dev libjpeg62-turbo-dev \
    libonig-dev \
    nodejs npm postgresql-client \
    libxml2-dev libzip-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_pgsql pgsql gd bcmath opcache mbstring dom xml zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy semua file project
COPY . .

RUN mkdir -p bootstrap/cache storage \
    && chmod -R 775 bootstrap/cache storage

COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Pastikan .env ikut ter-copy
COPY .env /var/www/.env

# Install dependency Laravel
#RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
# RUN composer install --no-dev --optimize-autoloader --no-scripts
# RUN composer require barryvdh/laravel-dompdf

# Generate APP_KEY hanya jika belum ada
#RUN if [ ! -f .env ] || ! grep -q "APP_KEY=" .env || grep -q "APP_KEY=$" .env; then \
#  echo "APP_KEY belum ada, generate baru..." \
#  && php artisan key:generate --force; \
#else \
#  echo "APP_KEY sudah ada, skip generate."; \
#fi

# Pastikan folder cache ada & writable sebelum artisan command
RUN mkdir -p /var/www/bootstrap/cache \
    && chmod -R 775 bootstrap/cache \
    && rm -f bootstrap/cache/packages.php \
    && php artisan config:clear

# Install dependensi frontend & build
RUN npm install && npm run build

# Set permission
RUN mkdir -p /var/www/storage \
    && chown -R www-data:www-data /var/www/storage

# Copy entrypoint script
COPY entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 9000
ENTRYPOINT ["entrypoint.sh"]
CMD ["php-fpm"]


# Gunakan PHP-FPM 8.2
FROM php:8.2-fpm

# Install dependensi sistem & ekstensi PostgreSQL
RUN apt-get update && apt-get install -y \
    git curl libpq-dev libpng-dev libjpeg-dev libfreetype6-dev zip unzip \
    && docker-php-ext-install pdo pdo_pgsql pgsql gd

# Install Composer
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy semua file project
COPY . .

# Install dependency Laravel
RUN composer install --no-dev --optimize-autoloader

# Set permission
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Copy entrypoint script
COPY entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 9000
ENTRYPOINT ["entrypoint.sh"]
CMD ["php-fpm"]

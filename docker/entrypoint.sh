#!/bin/sh

# Jalankan Composer Install
composer install --no-interaction --optimize-autoloader

# Tunggu database siap (opsional tapi sangat direkomendasikan)
# (Anda mungkin perlu menginstall netcat di Dockerfile Anda: `apk add --no-cache netcat-openbsd`)
# while ! nc -z db 5432; do
#   echo "Waiting for PostgreSQL..."
#   sleep 1
# done

# Jalankan perintah Artisan
php artisan migrate --force
php artisan db:seed --force
php artisan storage:link
php artisan optimize
php artisan view:cache

# Jalankan PHP-FPM di background untuk menjaga container tetap hidup
php-fpm
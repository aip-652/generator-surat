#!/bin/bash
set -e

# Tunggu database siap
echo "Menunggu PostgreSQL siap..."
until pg_isready -h db -p 5432 -U pgsql; do
  sleep 2
done

echo "Database siap. Menjalankan setup Laravel..."

# Jalankan setup Laravel otomatis

# Generate APP_KEY hanya jika belum ada
#if [ -z "$(grep APP_KEY= .env | cut -d '=' -f2)" ]; then
#  echo "APP_KEY belum ada, generate baru..."
#  php artisan key:generate --force
#else
#  echo "APP_KEY sudah ada, skip generate."
#fi

php artisan migrate --force
#php artisan db:seed --force
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "✅ Laravel setup selesai!"

# Jalankan PHP-FPM
exec "$@"

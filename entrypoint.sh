#!/bin/bash
set -e

echo "Menunggu PostgreSQL siap..."
until pg_isready -h db -p 5432 -U pgsql; do
  sleep 2
done

echo "Database siap."

# Generate APP_KEY jika belum ada
if [ ! -f .env ] || ! grep -q "APP_KEY=" .env || grep -q "APP_KEY=$" .env; then
  echo "Generate APP_KEY..."
  php artisan key:generate --force
fi

echo "Menjalankan migrasi..."
php artisan migrate --force

echo "Menjalankan seeder..."
php artisan db:seed --force || true

# echo "Optimasi Laravel..."
# php artisan storage:link || true
# php artisan optimize:clear
# php artisan config:cache
# php artisan route:cache
# php artisan view:cache

php-fpm

echo "Laravel siap dijalankan."

exec "$@"
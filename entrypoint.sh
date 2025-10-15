#!/bin/bash
set -e

# Tunggu database siap
echo "Menunggu PostgreSQL siap..."
until pg_isready -h db -p 5432 -U surat_user; do
  sleep 2
done

echo "Database siap. Menjalankan setup Laravel..."

# Jalankan setup Laravel otomatis
php artisan key:generate --force
php artisan migrate --force
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "✅ Laravel setup selesai!"

# Jalankan PHP-FPM
exec "$@"

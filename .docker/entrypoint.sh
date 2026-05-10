#!/bin/bash
set -euo pipefail

cd /var/www/html

echo "==> [startup] Running migrations"
php artisan migrate --force --no-interaction

echo "==> [startup] Creating storage symlink"
php artisan storage:link 2>/dev/null || true

echo "==> [startup] Rebuilding Laravel caches against runtime env"
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan event:clear

php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

echo "==> [startup] Starting supervisord"
exec "$@"

# Procfile consumed by Nixpacks / Coolify.
#
# `release` runs once per deploy *before* any `web` process starts.
# We chain migrations and Laravel's runtime caches here so every
# release gets a fresh schema and config snapshot using the real
# runtime env vars from Coolify.
#
# No `web:` line is defined — Nixpacks' PHP provider supplies its
# default nginx + php-fpm web process, which is what we want.

release: php artisan migrate --force --no-interaction && php artisan config:cache && php artisan route:cache && php artisan view:cache && php artisan event:cache && (php artisan storage:link || true)

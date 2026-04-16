#!/usr/bin/env bash
#
# Container entrypoint used by Nixpacks on Coolify.
#
# Runs DB-dependent and env-dependent tasks at container boot (where the
# database service is reachable), then hands off to the Nixpacks PHP
# provider's bundled nginx + php-fpm setup.
#
# The build-time Dockerfile CMD invokes this script because nixpacks.toml
# sets `[start] cmd = "bash /app/nixpacks-start.sh"`.

set -euo pipefail

echo "==> [startup] artisan migrate --force"
php artisan migrate --force --no-interaction

echo "==> [startup] artisan storage:link (idempotent)"
php artisan storage:link 2>/dev/null || true

echo "==> [startup] artisan config/route/view/event cache"
# Cache at runtime so the compiled artefacts reflect Coolify's env vars,
# not the (possibly empty) env that existed during the Docker build.
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan event:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

echo "==> [startup] rendering nginx config from Nixpacks template"
# Nixpacks PHP provider ships an nginx template that needs env-var
# substitution (for NIXPACKS_PHP_ROOT_DIR, PORT, etc.) before nginx can
# load it. The prestart.mjs script performs that substitution.
if [[ -f /assets/scripts/prestart.mjs ]]; then
  node /assets/scripts/prestart.mjs /assets/nginx.template.conf /etc/nginx.conf
elif [[ -f /assets/nginx.template.conf ]]; then
  # Fallback: copy template verbatim if the prestart script is missing.
  cp /assets/nginx.template.conf /etc/nginx.conf
fi

echo "==> [startup] starting php-fpm (background)"
php-fpm -y /assets/php-fpm.conf --daemonize

echo "==> [startup] exec nginx (foreground)"
# NOTE: do NOT pass `-g "daemon off;"` here — the Nixpacks nginx template
# already includes a `daemon off;` directive on line 2, and duplicating
# it is a fatal nginx error. Just let the config file drive it.
exec nginx -c /etc/nginx.conf

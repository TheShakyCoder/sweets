# =============================================================================
#  Acme Sweets — Production Dockerfile
#  nginx + php-fpm + supervisor (queue workers + SSR)
# =============================================================================

# ── Stage 1: Build frontend assets ──────────────────────────────────────────
FROM node:22-slim AS build-assets
WORKDIR /app

# Composer is needed so Vite can resolve Ziggy routes
COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer
RUN apt-get update && apt-get install -y --no-install-recommends php-cli php-xml php-curl unzip git \
    && rm -rf /var/lib/apt/lists/*

# Install PHP deps first (layer cache)
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist --ignore-platform-reqs

# Install Node deps (layer cache)
COPY package.json package-lock.json ./
RUN npm ci

# Copy everything and build
COPY . .
RUN npm run build


# ── Stage 2: Production runtime ─────────────────────────────────────────────
FROM php:8.4-fpm AS runtime

# System deps + PHP extensions
RUN apt-get update && apt-get install -y --no-install-recommends \
        nginx supervisor curl unzip git \
        libicu-dev libzip-dev libpng-dev libjpeg-dev libfreetype6-dev \
        libssl-dev libonig-dev \
        nodejs \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pcntl opcache pdo pdo_mysql intl zip gd exif bcmath mbstring \
    && pecl install redis && docker-php-ext-enable redis \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# PHP production config
RUN cp "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"
COPY .docker/php.ini /usr/local/etc/php/conf.d/99-app.ini
COPY .docker/php-fpm.conf /usr/local/etc/php-fpm.d/zz-app.conf

# Nginx config
COPY .docker/nginx.conf /etc/nginx/nginx.conf

# Supervisor config
COPY .docker/supervisord.conf /etc/supervisor/supervisord.conf
COPY .docker/supervisor/ /etc/supervisor/conf.d/

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer

WORKDIR /var/www/html

# Copy application source
COPY . .

# Copy compiled frontend assets from stage 1
COPY --from=build-assets /app/public/build /var/www/html/public/build
COPY --from=build-assets /app/bootstrap/ssr /var/www/html/bootstrap/ssr

# Install PHP production deps
RUN composer install --prefer-dist --optimize-autoloader --no-dev --no-interaction

# Ensure directories exist and are writable
RUN mkdir -p storage/framework/{sessions,views,cache} storage/logs bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Cache routes at build time (safe — doesn't need env vars)
RUN php artisan route:cache

# Entrypoint handles runtime boot (migrations, config cache, etc.)
COPY .docker/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

EXPOSE 80

ENTRYPOINT ["/entrypoint.sh"]
CMD ["supervisord", "-c", "/etc/supervisor/supervisord.conf", "-n"]

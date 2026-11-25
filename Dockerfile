# syntax=docker/dockerfile:1
ARG PHP_VERSION=8.5
ARG NODE_VERSION=24

FROM dunglas/frankenphp:1-php${PHP_VERSION}-bookworm AS base
WORKDIR /app
RUN cp "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini" \
 && install-php-extensions opcache zip
COPY docker/php.ini "$PHP_INI_DIR/conf.d/zz-app.ini"

FROM base AS vendor
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --no-progress --no-scripts --no-autoloader --prefer-dist
COPY . .
RUN mkdir -p storage/framework/cache/data storage/framework/sessions storage/framework/views \
             storage/logs bootstrap/cache \
 && composer dump-autoload --no-dev --classmap-authoritative --no-scripts \
 && php artisan package:discover --ansi

FROM node:${NODE_VERSION}-bookworm-slim AS assets
WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci --no-audit --no-fund
COPY webpack.mix.js ./
COPY resources ./resources
COPY public ./public
RUN npm run production

FROM base AS runtime
ENV APP_ENV=production \
    APP_DEBUG=false \
    APP_LOCALE=it \
    APP_FALLBACK_LOCALE=it \
    LOG_CHANNEL=stderr \
    LOG_LEVEL=warning \
    SESSION_DRIVER=array \
    CACHE_STORE=array \
    QUEUE_CONNECTION=sync \
    PORT=8080

COPY --from=vendor --chown=www-data:www-data /app /app
COPY --from=assets --chown=www-data:www-data /app/public /app/public
COPY docker/Caddyfile /etc/frankenphp/Caddyfile

# No config:cache: it would freeze the empty build-time APP_KEY into the image.
RUN php artisan view:cache \
 && php artisan event:cache \
 && php artisan route:cache \
 && setcap -r /usr/local/bin/frankenphp \
 && chown -R www-data:www-data storage bootstrap/cache /config/caddy /data/caddy

USER www-data
EXPOSE 8080
HEALTHCHECK --interval=30s --timeout=3s --start-period=10s --retries=3 \
  CMD php -r "exit(@file_get_contents('http://127.0.0.1:' . getenv('PORT') . '/') === false ? 1 : 0);"
CMD ["frankenphp", "run", "--config", "/etc/frankenphp/Caddyfile"]

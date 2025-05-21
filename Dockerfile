FROM serversideup/php:8.4.1-fpm-nginx AS base

# Install any extensions...

FROM base AS build

COPY --chown=www-data:www-data . /var/www/html
USER www-data

# Run Composer...
RUN composer install --no-dev --prefer-dist --optimize-autoloader \
    && php artisan tailwindcss:download --force \
    && php artisan tailwindcss:build --prod \
    && php artisan importmap:optimize

FROM base AS production

# Set env variables...
ENV PHP_OPCACHE_ENABLE=1
ENV AUTORUN_ENABLED="true"

COPY --chown=www-data:www-data --from=build /var/www/html /var/www/html
USER www-data

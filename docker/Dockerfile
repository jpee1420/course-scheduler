FROM php:8.3-fpm-alpine

RUN apk add --no-cache \
    bash \
    shadow \
    nginx \
    curl \
    libpng libpng-dev \
    libjpeg-turbo libjpeg-turbo-dev \
    libwebp libwebp-dev \
    oniguruma oniguruma-dev \
    libzip libzip-dev \
    icu icu-dev \
    postgresql-dev \
    git && \
    docker-php-ext-configure intl && \
    docker-php-ext-install pdo pdo_pgsql intl mbstring zip gd && \
    rm -rf /var/cache/apk/*

WORKDIR /var/www/html

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY . /var/www/html

RUN adduser -D -H -u 1000 laravel && \
    chown -R laravel:laravel /var/www/html && \
    mkdir -p /var/www/html/storage /var/www/html/bootstrap/cache && \
    chown -R laravel:laravel /var/www/html/storage /var/www/html/bootstrap/cache

USER laravel

RUN composer install --no-dev --prefer-dist --no-interaction --no-progress

USER root

COPY docker/nginx.conf /etc/nginx/nginx.conf

EXPOSE 80

# Create entrypoint script
RUN echo '#!/bin/bash\n\
# Wait for database\n\
until pg_isready -h pgsql -p 5432 -U ${DB_USERNAME}; do\n\
  echo "Waiting for database..."\n\
  sleep 2\n\
done\n\
\n\
# Run Laravel setup\n\
php artisan migrate --force\n\
php artisan config:cache\n\
php artisan route:cache\n\
php artisan view:cache\n\
\n\
# Start services\n\
php-fpm -D && nginx -g "daemon off;"' > /entrypoint.sh && \
    chmod +x /entrypoint.sh

CMD ["/entrypoint.sh"]

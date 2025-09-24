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
    mysql-client \
    git && \
    docker-php-ext-configure intl && \
    docker-php-ext-install pdo pdo_mysql intl mbstring zip gd && \
    rm -rf /var/cache/apk/*

WORKDIR /var/www/html

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY . /var/www/html

RUN adduser -D -H -u 1000 laravel && \
    chown -R laravel:laravel /var/www/html && \
    mkdir -p /var/www/html/storage /var/www/html/bootstrap/cache && \
    chown -R laravel:laravel /var/www/html/storage /var/www/html/bootstrap/cache

USER laravel

RUN composer install --no-dev --prefer-dist --no-interaction --no-progress && \
    php artisan route:cache || true && \
    php artisan view:cache || true && \
    php artisan config:cache || true

USER root

COPY docker/nginx.conf /etc/nginx/nginx.conf

EXPOSE 80

CMD ["sh", "-c", "php-fpm -D && nginx -g 'daemon off;'"]



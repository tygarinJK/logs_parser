FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    git unzip curl libicu-dev libonig-dev libzip-dev libxml2-dev \
    mariadb-client \
    && docker-php-ext-install pdo pdo_mysql intl zip opcache

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

CMD ["php-fpm"]

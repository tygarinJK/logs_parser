FROM php:8.2-fpm

RUN set -xe \
    && apt-get update && apt-get install -y \
        curl \
        git  \
        unzip  \
        libfreetype6-dev \
        libicu-dev \
        libjpeg-dev  \
        libonig-dev  \
        libpng-dev  \
        libxml2-dev \
        libxml2-dev \
        libzip-dev  \
        libzip-dev  \
        mariadb-client \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        gd \
        intl \
        opcache \
        pdo \
        pdo_mysql \
        xml \
        zip \
    # PHP ini settings
    && echo "memory_limit=512M" > /usr/local/etc/php/conf.d/zz-php.ini

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

CMD ["php-fpm"]

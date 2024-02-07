FROM php:8.2-apache

RUN apt-get update \
    && apt-get install -y zlib1g-dev g++ git libicu-dev zip libzip-dev zip \
    && apt-get install -y libfreetype6-dev libjpeg62-turbo-dev libpng-dev libwebp-dev libavif-dev libaom-dev libdav1d-dev\
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp --with-avif\
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-install intl opcache pdo pdo_mysql \
    && docker-php-ext-configure zip \
    && docker-php-ext-install zip

RUN a2enmod rewrite && a2enmod ssl && a2enmod socache_shmcb

WORKDIR /var/www



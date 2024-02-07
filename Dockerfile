FROM php:8.2-apache

RUN apt-get update \
    && apt-get install -y zlib1g-dev g++ git libicu-dev zip libzip-dev zip \
    && apt-get install -y php-gd \
    && docker-php-ext-install intl opcache pdo pdo_mysql \
    && pecl install apcu \
    && docker-php-ext-enable apcu \
    && docker-php-ext-configure zip \
    && docker-php-ext-install zip

RUN a2enmod rewrite && a2enmod ssl && a2enmod socache_shmcb

WORKDIR /var/www



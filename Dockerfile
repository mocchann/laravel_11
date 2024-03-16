FROM php:8.3-apache

COPY --from=composer /usr/bin/composer /usr/bin/composer

RUN apt-get update && apt-get install -y zip git \
    && docker-php-ext-install pdo_mysql

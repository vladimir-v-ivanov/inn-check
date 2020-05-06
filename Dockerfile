FROM php:7.4-apache

RUN apt-get update \
 && apt-get install -y git zlib1g-dev libzip-dev libicu-dev g++ \
 && docker-php-ext-install zip \
 && docker-php-ext-configure intl \
 && docker-php-ext-install intl \
 && docker-php-ext-install mysqli \
 && a2enmod rewrite \
 && sed -i 's!/var/www/html!/var/www/public!g' /etc/apache2/sites-available/000-default.conf \
 && mv /var/www/html /var/www/public \
 && curl -sS https://getcomposer.org/installer \
  | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www
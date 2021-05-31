FROM php:8.0-fpm

RUN apt update && apt upgrade -y && apt install -y git libzip-dev zip autoconf libssl-dev

RUN pecl install mongodb && docker-php-ext-enable mongodb && docker-php-ext-install zip

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --filename=composer --install-dir=/usr/bin \
    && php -r "unlink('composer-setup.php');"

WORKDIR /var/www
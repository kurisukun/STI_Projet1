FROM php:8-fpm

RUN apt-get update && apt-get install -y \
      wget \
      git

RUN apt-get install -y unzip libzip-dev libicu-dev && docker-php-ext-install pdo zip intl opcache

# PHP ext for MySQL / MariaDB
RUN docker-php-ext-install pdo_mysql

# Xdebug
RUN pecl install xdebug-3.0.1 && docker-php-ext-enable xdebug

# Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin/ --filename=composer

ARG USER_ID
ARG GROUP_ID

RUN groupadd -f --gid $GROUP_ID user
RUN useradd -m -s /bin/bash --uid $USER_ID --gid $GROUP_ID user
USER user

WORKDIR /var/www

EXPOSE 9000

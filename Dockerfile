FROM docker.arvancloud.ir/library/php:8.4.8-fpm

RUN apt-get update \
    && apt-get install -y \
        git \
        unzip \
        libzip-dev \
        libpng-dev \
        libonig-dev \
        libxml2-dev \
        default-mysql-client \
    && docker-php-ext-install \
        pdo \
        pdo_mysql \
        mbstring \
        zip \
        bcmath \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

COPY --from=docker.arvancloud.ir/library/composer:2.7.7 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

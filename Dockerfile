FROM php:8.3-fpm-alpine

# Установка системных зависимостей
RUN apk update && apk add --no-cache \
    bash \
    git \
    zip \
    unzip \
    icu-dev \
    libxml2-dev \
    libzip-dev \
    oniguruma-dev \
    postgresql-dev \
    autoconf \
    gcc \
    g++ \
    make \
    pkgconf \
    $PHPIZE_DEPS \
    && docker-php-ext-install \
        intl \
        pdo \
        pdo_pgsql \
        zip \
        opcache \
    && docker-php-source delete \
    && rm -rf /var/cache/apk/*

# Установка Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

FROM php:8.1-fpm

RUN apt-get update && apt-get install -y \
    curl \
    libpq-dev \
    libzip-dev\
    zip\
    unzip\
    && docker-php-ext-install -j$(nproc) pdo_pgsql zip

WORKDIR /var/www/html

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY . /var/www/html

EXPOSE 9000

CMD ["php-fpm"]
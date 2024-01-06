FROM php:8.1-apache

RUN apt-get update && apt-get install -y \
    libicu-dev \
    libpq-dev \
    && docker-php-ext-install -j$(nproc) intl pdo_pgsql

RUN a2enmod rewrite
RUN service apache2 restart

WORKDIR /var/www/html

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY . /var/www/html

RUN composer install --no-scripts

RUN chown -R www-data:www-data /var/www/html

EXPOSE 80
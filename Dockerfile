# Dockerfile para el contenedor PHP
FROM php:8.1-apache

# Configurar Apache y PHP
RUN a2enmod rewrite

# Instalar Composer
RUN apt-get update && apt-get install -y \
    unzip \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Instalar las bibliotecas phpoffice/phpword y mpdf/mpdf
RUN composer require phpoffice/phpword mpdf/mpdf

COPY ./php.ini /usr/local/etc/php/php.ini

COPY ./App /var/www/html

# Configurar valores de PHP
RUN echo "upload_max_filesize = 800M" >> /usr/local/etc/php/php.ini \
    && echo "memory_limit = 800M" >> /usr/local/etc/php/php.ini \
    && echo "post_max_size = 800M" >> /usr/local/etc/php/php.ini

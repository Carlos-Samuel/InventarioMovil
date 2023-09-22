# Dockerfile para el contenedor PHP
FROM php:8.1-apache

# Configurar Apache y PHP
RUN a2enmod rewrite

RUN docker-php-ext-install mysqli

COPY ./php.ini /usr/local/etc/php/php.ini

COPY ./App /var/www/html

# Instalar las extensiones PHP necesarias
RUN docker-php-ext-install pdo pdo_mysql

# Configurar valores de PHP
RUN echo "upload_max_filesize = 800M" >> /usr/local/etc/php/php.ini \
    && echo "memory_limit = 800M" >> /usr/local/etc/php/php.ini \
    && echo "post_max_size = 800M" >> /usr/local/etc/php/php.ini
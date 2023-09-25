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

RUN mkdir -p /var/lib/php/sessions
COPY ./php.ini /usr/local/etc/php/php.ini
RUN chown -R www-data:www-data /var/lib/php/sessions

RUN composer require mpdf/mpdf


COPY ./App /var/www/html

RUN apt-get update && apt-get install -y libzip-dev && \
    docker-php-ext-install zip

# RUN apt-get update && apt-get install -y php-mysql


# RUN apt-get update && apt-get install -y mysqli && \
#     docker-php-ext-install mysqli


# Configurar valores de PHP
RUN echo "upload_max_filesize = 800M" >> /usr/local/etc/php/php.ini \
    && echo "memory_limit = 800M" >> /usr/local/etc/php/php.ini \
    && echo "post_max_size = 800M" >> /usr/local/etc/php/php.ini


RUN apt-get update && apt-get install -y \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libzip-dev \
    && docker-php-ext-install -j$(nproc) iconv gd zip

# Descargar e instalar mPDF
RUN mkdir /tmp/mpdf && \
    cd /tmp/mpdf && \
    curl -L -o mpdf.tar.gz "https://github.com/mpdf/mpdf/archive/master.tar.gz" && \
    tar xzf mpdf.tar.gz && \
    mv mpdf-master /usr/local/share/mpdf && \
    rm -rf /tmp/mpdf

# Configurar mPDF como una extensión de PHP
RUN echo "extension=/usr/local/share/mpdf/mPDF.php" >> /usr/local/etc/php/php.ini

RUN apt-get update && apt-get install -y cups

# Instalar LibreOffice
RUN apt-get update && apt-get install -y libreoffice

RUN mkdir -p /var/www/.config /var/www/.cache /app/cache /app/logs && \
    chown -R www-data:www-data /var/www/.config /var/www/.cache /app/cache /app/logs

# CMD ["libreoffice"]


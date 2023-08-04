# Dockerfile para el contenedor PHP
FROM php:8.1-apache

# Configurar Apache y PHP
RUN a2enmod rewrite
COPY ./App /var/www/html

# Instalar las extensiones PHP necesarias
RUN docker-php-ext-install pdo pdo_mysql

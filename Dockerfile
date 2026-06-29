FROM php:8.1-apache

RUN docker-php-ext-install mysqli pdo pdo_mysql
RUN a2enmod rewrite

# Copiar el proyecto dentro de /var/www/html/farmacia/, igual que en XAMPP
COPY ./diseno /var/www/html/farmacia/diseno
COPY ./php /var/www/html/farmacia/php

WORKDIR /var/www/html

EXPOSE 80

CMD ["apache2-foreground"]


FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    python3 \
    python3-pip \
    libapache2-mod-php \
    mariadb-client \
    && docker-php-ext-install pdo pdo_mysql

COPY . /var/www/html/
WORKDIR /var/www/html/

CMD ["apache2-foreground"]

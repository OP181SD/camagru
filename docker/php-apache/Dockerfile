FROM php:8.2-apache

RUN apt-get update && apt-get install -y libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

COPY ./src /var/www/html/

RUN a2enmod rewrite \
    && echo "DirectoryIndex index.php index.html" > /etc/apache2/conf-enabled/directory-index.conf \
    && echo "DocumentRoot /var/www/html/public" > /etc/apache2/sites-available/000-default.conf

EXPOSE 80

CMD ["apache2-foreground"]

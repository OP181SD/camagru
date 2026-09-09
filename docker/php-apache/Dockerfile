FROM php:apache

RUN apt-get update && apt-get install -y \
    libpq-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    msmtp \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_pgsql gd

COPY ./src /var/www/html/
COPY ./docker/php-apache/my-app.conf /etc/apache2/sites-available/000-default.conf

RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf
RUN a2enmod rewrite

RUN mkdir -p /var/www/html/public/assets/uploads/profile_pictures \
    && mkdir -p /var/www/html/public/assets/publications \
    && chown -R www-data:www-data /var/www/html/public/assets/uploads \
    && chmod -R 775 /var/www/html/public/assets/uploads \
    && chown -R www-data:www-data /var/www/html/public/assets/publications \
    && chmod -R 775 /var/www/html/public/assets/publications

COPY ./docker/php-apache/php.ini /usr/local/etc/php/

RUN echo "defaults" > /etc/msmtprc \
    && echo "auth off" >> /etc/msmtprc \
    && echo "tls off" >> /etc/msmtprc \
    && echo "logfile /var/log/msmtp.log" >> /etc/msmtprc \
    && echo "account mailhog" >> /etc/msmtprc \
    && echo "host mailhog" >> /etc/msmtprc \
    && echo "port 1025" >> /etc/msmtprc \
    && echo "from camagru@example.com" >> /etc/msmtprc \
    && echo "account default : mailhog" >> /etc/msmtprc

RUN echo "sendmail_path = /usr/bin/msmtp -t" > /usr/local/etc/php/conf.d/mail.ini

EXPOSE 80

CMD ["apache2-foreground"]
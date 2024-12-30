FROM php:8.2-apache

# Installer les extensions PHP nécessaires pour PostgreSQL
RUN apt-get update && apt-get install -y libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

# Copier le code source
COPY ../src /var/www/html/

# Fixer les permissions
RUN chown -R www-data:www-data /var/www/html/ \
    && chmod -R 755 /var/www/html/

# Activer les modules Apache nécessaires
RUN a2enmod rewrite \
    && echo "DirectoryIndex index.php index.html" > /etc/apache2/conf-enabled/directory-index.conf

# Exposer le port 80
EXPOSE 80

# Lancer Apache
CMD ["apache2-foreground"]

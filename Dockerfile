FROM php:8.2-apache

# Installer les dépendances et le pilote PDO pour MySQL
RUN apt-get update && apt-get install -y libpng-dev default-mysql-client zip libzip-dev && \
    docker-php-ext-install pdo pdo_mysql zip && \
    a2enmod rewrite

# Activer mod_rewrite
RUN a2enmod rewrite
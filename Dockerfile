FROM php:8.2-apache
<<<<<<< HEAD

RUN apt-get update && apt-get install -y --no-install-recommends \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    && docker-php-ext-install pdo_mysql mysqli \
    && a2enmod rewrite \
    && rm -rf /var/lib/apt/lists/*

RUN { \
    echo 'file_uploads=On'; \
    echo 'upload_max_filesize=8M'; \
    echo 'post_max_size=10M'; \
    echo 'memory_limit=256M'; \
    echo 'max_execution_time=120'; \
    echo 'date.timezone=America/Sao_Paulo'; \
} > /usr/local/etc/php/conf.d/webtoonsz.ini

WORKDIR /var/www/html

EXPOSE 80

=======
RUN docker-php-ext-install pdo_mysql
RUN a2enmod rewrite
WORKDIR /var/www/html
RUN echo "upload_max_filesize=8M" > /usr/local/etc/php/conf.d/uploads.ini && echo "post_max_size=10M" >> /usr/local/etc/php/conf.d/uploads.ini
EXPOSE 80
>>>>>>> cc21d46c4ef1943831d6cb804870ba3ea6b84abc
CMD ["apache2-foreground"]

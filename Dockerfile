FROM php:8.2-apache
RUN docker-php-ext-install pdo_mysql
RUN a2enmod rewrite
WORKDIR /var/www/html
RUN echo "upload_max_filesize=8M" > /usr/local/etc/php/conf.d/uploads.ini && echo "post_max_size=10M" >> /usr/local/etc/php/conf.d/uploads.ini
EXPOSE 80
CMD ["apache2-foreground"]

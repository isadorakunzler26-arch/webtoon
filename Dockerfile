FROM php:8.2-apache

RUN apt-get update && apt-get install -y --no-install-recommends \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j"$(nproc)" gd pdo_mysql mysqli \
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

COPY . /var/www/html/

RUN mkdir -p uploads/perfis uploads/obras \
    && chown -R www-data:www-data /var/www/html

EXPOSE 80

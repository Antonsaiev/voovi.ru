# Приложение использует mysql_* (удалено в PHP 7), нужен PHP 5.6
# linux/amd64 — на ARM64 (Apple Silicon) Stretch не имеет пакетов, используем эмуляцию
FROM --platform=linux/amd64 php:5.6-apache

# Debian Stretch в архиве — переключаем APT на archive.debian.org
RUN sed -i 's|deb.debian.org|archive.debian.org|g' /etc/apt/sources.list \
    && sed -i 's|security.debian.org|archive.debian.org|g' /etc/apt/sources.list \
    && sed -i '/stretch-updates/d' /etc/apt/sources.list \
    && echo 'Acquire::Check-Valid-Until "false";' > /etc/apt/apt.conf.d/99no-check-valid-until

RUN apt-get update && apt-get install -y --allow-unauthenticated \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libzip-dev \
    zip \
    unzip \
    --no-install-recommends \
    && rm -rf /var/lib/apt/lists/*

# Расширения: mysql (устаревшее, для кода приложения), mysqli, gd, zip, mbstring
RUN docker-php-ext-configure gd --with-freetype-dir=/usr --with-jpeg-dir=/usr --with-png-dir=/usr \
    && docker-php-ext-install -j$(nproc) mysql mysqli gd zip mbstring

# Включить mod_rewrite для .htaccess
RUN a2enmod rewrite

# DocumentRoot уже /var/www/html
ENV APACHE_DOCUMENT_ROOT /var/www/html
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Копируем приложение (исключения в .dockerignore)
COPY . /var/www/html/

# Подменяем conf.php и .htaccess для Docker (БД = сервис db, без редиректа на HTTPS)
COPY docker/conf.php /var/www/html/conf.php
COPY docker/.htaccess /var/www/html/.htaccess

# Права на запись для каталогов, куда приложение пишет
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html \
    && mkdir -p \
        /var/www/html/log \
        /var/www/html/doc \
        /var/www/html/upload \
        /var/www/html/files \
        /var/www/html/voicecatalog \
        /var/www/html/img \
        /var/www/html/vipiska \
        /var/www/html/scheta \
        /var/www/html/mail/database \
    && chown -R www-data:www-data \
        /var/www/html/log \
        /var/www/html/doc \
        /var/www/html/upload \
        /var/www/html/files \
        /var/www/html/voicecatalog \
        /var/www/html/img \
        /var/www/html/vipiska \
        /var/www/html/scheta \
        /var/www/html/mail/database

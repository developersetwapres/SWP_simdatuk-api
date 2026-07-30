FROM php:8.4-fpm-alpine AS build

RUN apk add --no-cache \
    bash \
    git \
    curl \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    oniguruma-dev \
    icu-dev \
    openldap-dev \
    libzip-dev \
    zip \
    unzip

RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
        pdo \
        pdo_mysql \
        mbstring \
        gd \
        intl \
        ldap \
        zip

WORKDIR /var/www

COPY composer.json composer.lock ./

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN composer install \
    --no-dev \
    --optimize-autoloader \
    --no-interaction \
    --no-scripts

COPY . .

RUN php artisan package:discover --ansi

FROM php:8.4-fpm-alpine

RUN apk add --no-cache \
    bash \
    curl \
    libpng \
    libjpeg-turbo \
    freetype \
    oniguruma \
    icu \
    openldap \
    libzip \
    zip \
    unzip \
    mysql-client

WORKDIR /var/www

# Salin extension PHP yang sudah dikompilasi
COPY --from=build /usr/local/lib/php/extensions/ /usr/local/lib/php/extensions/
COPY --from=build /usr/local/etc/php/conf.d/ /usr/local/etc/php/conf.d/

# Salin aplikasi
COPY --from=build /var/www /var/www

RUN chown -R www-data:www-data /var/www \
    && chmod -R 755 /var/www \
    && chmod -R 775 /var/www/storage \
    && chmod -R 775 /var/www/bootstrap/cache

EXPOSE 9000

CMD ["php-fpm"]FROM php:8.4-fpm-alpine AS build

RUN apk add --no-cache \
    bash \
    git \
    curl \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    oniguruma-dev \
    icu-dev \
    openldap-dev \
    libzip-dev \
    zip \
    unzip

RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
        pdo \
        pdo_mysql \
        mbstring \
        gd \
        intl \
        ldap \
        zip

WORKDIR /var/www

COPY composer.json composer.lock ./

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN composer install \
    --no-dev \
    --optimize-autoloader \
    --no-interaction \
    --no-scripts

COPY . .

RUN php artisan package:discover --ansi

FROM php:8.4-fpm-alpine

RUN apk add --no-cache \
    bash \
    curl \
    libpng \
    libjpeg-turbo \
    freetype \
    oniguruma \
    icu \
    openldap \
    libzip \
    zip \
    unzip \
    mysql-client

WORKDIR /var/www

# Salin extension PHP yang sudah dikompilasi
COPY --from=build /usr/local/lib/php/extensions/ /usr/local/lib/php/extensions/
COPY --from=build /usr/local/etc/php/conf.d/ /usr/local/etc/php/conf.d/

# Salin aplikasi
COPY --from=build /var/www /var/www

RUN chown -R www-data:www-data /var/www \
    && chmod -R 755 /var/www \
    && chmod -R 775 /var/www/storage \
    && chmod -R 775 /var/www/bootstrap/cache

EXPOSE 9000

CMD ["php-fpm"]

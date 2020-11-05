# provisionning/php-fpm/Dockerfile

FROM php:fpm-alpine
COPY wait-for-it.sh /usr/bin/wait-for-it
RUN chmod +x /usr/bin/wait-for-it
COPY --from=composer /usr/bin/composer /usr/bin/composer
# Extensions
RUN echo "Extensions install - start" \
 && set -e; \
         apk add --no-cache \
                coreutils \
                freetype-dev \
                libjpeg-turbo-dev \
                libjpeg-turbo \
                libpng-dev \
                libzip-dev \
                jpeg-dev \
                icu-dev \
                zlib-dev \
                curl-dev \
                imap-dev \
                libxslt-dev libxml2-dev \
                postgresql-dev \
                libgcrypt-dev \
                oniguruma-dev \
# Amqp
    && apk add --no-cache --update rabbitmq-c-dev \
    && apk add --no-cache --update --virtual .phpize-deps $PHPIZE_DEPS \
    && pecl install -o -f amqp \
    && docker-php-ext-enable amqp \
    && apk del .phpize-deps
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-configure intl
RUN docker-php-ext-configure imap

RUN set -e; docker-php-ext-install -j "$(nproc)" \
                gd soap imap bcmath mbstring iconv curl sockets \
                opcache \
                pdo_pgsql \
                xsl \
                exif \
                mysqli pdo pdo_mysql \
                intl \
                zip

RUN echo "Extensions install - finish"
WORKDIR /var/www
CMD composer install ; wait-for-it database:3306 -- bin/console doctrine:migrations:migrate ;  php-fpm 
EXPOSE 9000
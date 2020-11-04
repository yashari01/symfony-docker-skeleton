# provisionning/php-fpm/Dockerfile

FROM php:fpm-alpine
COPY wait-for-it.sh /usr/bin/wait-for-it
RUN chmod +x /usr/bin/wait-for-it
COPY --from=composer /usr/bin/composer /usr/bin/composer
# Extensions
RUN echo "Extensions install - start" \
    && apk --update --no-cache add git \
    && docker-php-ext-install pdo_mysql \
    # Sockets
    && docker-php-ext-install sockets \
    # Amqp
    && apk add --no-cache --update rabbitmq-c-dev \
    && apk add --no-cache --update --virtual .phpize-deps $PHPIZE_DEPS \
    && pecl install -o -f amqp \
    && docker-php-ext-enable amqp \
    && apk del .phpize-deps \
    #
    && echo "Extensions install - finish"

WORKDIR /var/www
CMD composer install ; wait-for-it database:3306 -- bin/console doctrine:migrations:migrate ;  php-fpm 
EXPOSE 9000
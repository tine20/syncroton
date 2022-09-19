FROM php:8.1-alpine

RUN apk add --no-cache $PHPIZE_DEPS \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug

#RUN apk add --no-cache php8-pecl-xdebug

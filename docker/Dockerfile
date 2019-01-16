FROM registry.devzone.net/mygarage/mygarage-php:7.2-fpm-alpine-dev
RUN apk --no-cache update \
    && apk --no-cache upgrade \
    && apk add --no-cache php7-zip
RUN docker-php-ext-install zip
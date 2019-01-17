FROM php:7.2-fpm-alpine
RUN apk --no-cache update \
    && apk --no-cache upgrade \
    && apk add --no-cache php7-zip 	zlib-dev libldap
RUN apk update && \
         apk add --no-cache --virtual .build-deps $PHPIZE_DEPS openldap-dev && \
         docker-php-ext-install ldap && \
         apk del .build-deps && \
         rm -rf /tmp/* /var/cache/apk/*
RUN docker-php-ext-install zip

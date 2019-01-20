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

#RUN apt-get update \
#    && apt-get install -y git
##
RUN set -xe \
    && apk add --no-cache git \
#    && apk add --no-cache git \
#    # Install Composer
    && curl --silent --show-error https://getcomposer.org/installer | php \
    && mv composer.phar /usr/local/bin/composer \
#    # Install Xdebug
    && docker-php-source extract \
    && git clone --branch xdebug_2_6 https://github.com/xdebug/xdebug.git /usr/src/php/ext/xdebug/ \
    && docker-php-ext-configure xdebug \
    && docker-php-ext-install xdebug \
    && docker-php-source delete \
    # Clear cache
    && rm -rf /tmp/* /var/cache/apk/*
RUN docker-php-ext-install sockets

#RUN echo "listen=/var/run/php7-fpm.sock"
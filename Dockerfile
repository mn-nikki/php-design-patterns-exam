FROM php:7.4.6-cli-alpine3.11

RUN set -xe \
    && apk add --no-cache --virtual .build-deps $PHPIZE_DEPS \
    && pecl install pcov && docker-php-ext-enable pcov \
    && echo $'pcov.enabled=1\npcov.directory=.\npcov.exclude="~vendor~"' >> /usr/local/etc/php/conf.d/docker-php-ext-pcov.ini \
    && apk del --no-network .build-deps

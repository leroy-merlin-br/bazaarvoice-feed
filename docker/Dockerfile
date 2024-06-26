FROM php:8.0-fpm
LABEL maintainer="boitata@leroymerlin.com.br"

USER root:root

COPY --from=composer:2.2.18 /usr/bin/composer /usr/local/bin/composer
ENV PATH=$PATH:/var/www/.composer/vendor/bin

RUN apt-get update -qq \
  && apt-get install -qq --no-install-recommends \
      git zip unzip \
      libzip-dev libssl-dev \
      zlib1g-dev libicu-dev \
  && apt-get clean \
  && docker-php-ext-configure \
    intl \
  && docker-php-ext-install  \
    intl pcntl zip \
  && rm -rf /tmp/*

RUN pecl install xdebug-3.2.1 \
  && docker-php-ext-enable xdebug

ARG UID=1000
ARG GID=1000

RUN groupmod -g ${GID} www-data \
  && usermod -u ${UID} -g www-data www-data \
  && chown -hR www-data:www-data \
    /var/www \
    /usr/local/

COPY custom.ini /usr/local/etc/php/conf.d/custom.ini

WORKDIR /var/www/html
USER www-data:www-data

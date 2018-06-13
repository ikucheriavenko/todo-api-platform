FROM phpdockerio/php72-fpm:latest

WORKDIR "/application"

# Fix debconf warnings upon build
ARG DEBIAN_FRONTEND=noninteractive

ENV PHP_DEPENDENCIES git curl ca-certificates zip unzip php7.2-curl php7.2-bcmath php7.2-intl php7.2-mbstring php7.2-xml php7.2-mysql php-xdebug

RUN apt-get update \
    && apt-get -y --no-install-recommends install  ${PHP_DEPENDENCIES} \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && composer global require hirak/prestissimo \
    && apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

RUN echo 'alias sf="php bin/console"' >> ~/.bashrc
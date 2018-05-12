FROM php:7.2

RUN apt-get update && \
    apt-get install -y git zip unzip zlib1g-dev && \
    php -r "readfile('http://getcomposer.org/installer');" | php -- --install-dir=/usr/bin/ --filename=composer && \
    apt-get -y autoremove && \
    apt-get clean && \
rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

RUN docker-php-ext-install zip

WORKDIR microapp

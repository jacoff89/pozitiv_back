FROM phpdockerio/php:8.4-fpm
WORKDIR '/app'

ARG DEBIAN_FRONTEND=noninteractive

# Install FPM
RUN apt-get update \
    && apt-get -y --no-install-recommends install php-zip unzip php8.4-mysql php-sqlite3 php8.4-bcmath php8.4-xml php8.4-gd \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

RUN cd /tmp/ \
    && php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --install-dir=/usr/local/bin --filename=composer
CMD ["/usr/sbin/php-fpm8.4", "-O" ]

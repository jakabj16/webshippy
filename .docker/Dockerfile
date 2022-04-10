FROM serversideup/php:8.1-fpm

RUN apt-get update && apt-get install -yq php8.1-xdebug
RUN echo "xdebug.mode=coverage" >> /etc/php/8.1/mods-available/xdebug.ini

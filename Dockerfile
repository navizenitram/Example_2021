FROM php:7.4-cli-alpine

RUN apk add --no-cache zip libzip-dev && docker-php-ext-install zip && \
  curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

ENTRYPOINT cd /app && composer install && tail -f /dev/null

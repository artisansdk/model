FROM php:8.5-cli

COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer

RUN apt-get update \
    && apt-get install --yes --no-install-recommends \
        git \
        libcurl4-openssl-dev \
        libonig-dev \
        libxml2-dev \
        $PHPIZE_DEPS \
        unzip \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && docker-php-ext-install curl mbstring xml \
    && rm -rf /var/lib/apt/lists/*

ENV XDEBUG_MODE=coverage

WORKDIR /app

CMD ["sh", "-lc", "composer install --prefer-dist --no-interaction && composer test"]

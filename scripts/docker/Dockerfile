FROM node:26-bookworm-slim AS assets
WORKDIR /app
RUN npm install --global yarn@1.22.22
COPY package.json yarn.lock ./
RUN yarn install --frozen-lockfile
COPY js ./js
COPY scss ./scss
RUN yarn build

FROM php:8.5-apache-bookworm AS app
RUN apt-get update \
    && apt-get install -y --no-install-recommends git unzip libzip-dev \
    && docker-php-ext-install mysqli zip \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && a2enmod rewrite headers \
    && rm -rf /var/lib/apt/lists/*
COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer
WORKDIR /var/www/html
COPY composer.json composer.lock ./
RUN composer install --no-interaction --prefer-dist --no-progress
COPY . .
COPY --from=assets /app/out ./out
COPY --from=assets /app/css ./css
COPY scripts/docker/apache.conf /etc/apache2/conf-enabled/interclip.conf

FROM mysql:8.0 AS database
COPY scripts/db.sql /docker-entrypoint-initdb.d/interclip.sql

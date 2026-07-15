FROM node:22-bookworm-slim@sha256:53ada149d435c38b14476cb57e4a7da73c15595aba79bd6971b547ceb6d018bf AS node
FROM composer:2@sha256:5946476338742b200bb9ff88f8be56275ddae4b3949c72305cb0dbf10cfcb760 AS composer
FROM php:8.2-apache-bookworm@sha256:c71cb08ac0d622f5ac86ce547e00fd29c6a023e9217f78ae078735881b2c0db4

ARG USERNAME=vscode
ARG USER_UID=1000
ARG USER_GID=1000
ARG PHPREDIS_VERSION=6.2.0
ARG PHPREDIS_SHA256=470333b27cbf9485d36b610b81300c06491a6575f22c6801a9cefc55285ed123

COPY --from=node /usr/local/ /usr/local/
COPY --from=composer /usr/bin/composer /usr/local/bin/composer

RUN apt-get update \
    && apt-get install --yes --no-install-recommends \
        $PHPIZE_DEPS \
        ca-certificates \
        curl \
        default-mysql-client \
        git \
        jq \
        less \
        openssh-client \
        redis-tools \
        sudo \
        unzip \
    && curl --fail --location --silent --show-error \
        --connect-timeout 10 --max-time 120 --retry 5 --retry-all-errors \
        --output /tmp/redis.tgz \
        "https://codeload.github.com/phpredis/phpredis/tar.gz/refs/tags/${PHPREDIS_VERSION}" \
    && echo "${PHPREDIS_SHA256}  /tmp/redis.tgz" | sha256sum --check --strict \
    && docker-php-source extract \
    && mkdir -p /usr/src/php/ext/redis \
    && tar --extract --gzip --file /tmp/redis.tgz \
        --strip-components=1 --directory /usr/src/php/ext/redis \
    && docker-php-ext-install -j"$(nproc)" mysqli redis \
    && docker-php-source delete \
    && a2enmod headers rewrite \
    && rm -rf /var/lib/apt/lists/* /tmp/redis.tgz

RUN npm install --global --force yarn@1.22.22

RUN groupadd --gid "${USER_GID}" "${USERNAME}" \
    && useradd --uid "${USER_UID}" --gid "${USER_GID}" --create-home --shell /bin/bash "${USERNAME}" \
    && printf '%s ALL=(root) NOPASSWD:ALL\n' "${USERNAME}" > "/etc/sudoers.d/${USERNAME}" \
    && chmod 0440 "/etc/sudoers.d/${USERNAME}"

COPY apache-vhost.conf /etc/apache2/sites-available/000-default.conf
COPY ports.conf /etc/apache2/ports.conf
COPY php.ini /usr/local/etc/php/conf.d/interclip-development.ini

WORKDIR /workspaces/interclip
RUN mkdir -p node_modules && chown "${USERNAME}:${USERNAME}" node_modules

EXPOSE 8080

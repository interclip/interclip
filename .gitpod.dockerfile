FROM gitpod/workspace-mysql

RUN sudo install-packages redis-server php-redis -y;

# use a custom apache config.
COPY apache.conf /etc/apache2/apache2.conf

# change document root folder. It's relative to your git working copy.
ENV APACHE_DOCROOT_IN_REPO="."
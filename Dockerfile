ARG WP_VERSION=latest
FROM wordpress:latest

RUN apt-get update \
    && apt-get install -y --no-install-recommends less default-mysql-client \
    && rm -rf /var/lib/apt/lists/*

RUN curl -fsSL https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar -o /usr/local/bin/wp \
    && chmod +x /usr/local/bin/wp \
    && wp --info --allow-root

WORKDIR /var/www/html

ARG WP_VERSION
RUN if [ "${WP_VERSION}" = "latest" ]; then \
      php -d memory_limit=512M /usr/local/bin/wp core download --force --allow-root; \
    else \
      php -d memory_limit=512M /usr/local/bin/wp core download --force --version="${WP_VERSION}" --allow-root; \
    fi

COPY docker/entrypoint.sh /usr/local/bin/custom-entrypoint.sh
RUN chmod +x /usr/local/bin/custom-entrypoint.sh

ENTRYPOINT ["/usr/local/bin/custom-entrypoint.sh"]
CMD ["apache2-foreground"]

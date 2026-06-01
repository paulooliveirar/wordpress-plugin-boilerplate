#!/bin/bash
set -e

if [ -d /var/www/html/wp-content ]; then
	chown -R www-data:www-data /var/www/html/wp-content 2>/dev/null || true
fi

exec /usr/local/bin/docker-entrypoint.sh "$@"

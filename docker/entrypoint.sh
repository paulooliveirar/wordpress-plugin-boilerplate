#!/bin/bash
set -e

WP_CONTENT="/var/www/html/wp-content"

if [ -d "$WP_CONTENT" ]; then
	mkdir -p \
		"$WP_CONTENT/upgrade" \
		"$WP_CONTENT/uploads" \
		"$WP_CONTENT/cache" \
		"$WP_CONTENT/plugins" \
		"$WP_CONTENT/themes"

	# Apache/PHP run as www-data — bind-mounted wp-content must be writable by them.
	chown -R www-data:www-data "$WP_CONTENT"
	find "$WP_CONTENT" -type d -exec chmod 775 {} \;
	find "$WP_CONTENT" -type f -exec chmod 664 {} \;
fi

exec /usr/local/bin/docker-entrypoint.sh "$@"

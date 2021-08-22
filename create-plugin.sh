#!/bin/sh
chown -R www-data:www-data /var/www/html/wp-content/ &&
cd wp-content/plugins/ && 
mkdir -p plugin-name && touch plugin-name/plugin2-name.php
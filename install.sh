#!/bin/sh
#create .env 
if ! test -f ".env"; then
    cp .env.sample .env
fi

#Name Container in docker-compose.yml -> wordpress: - container_name
CONTAINER_NAME="example_wordpress"

#Up Container Wordpress and MySQL
if [ "$(docker ps -q -f name=$CONTAINER_NAME)" ]; then
    docker stop $CONTAINER_NAME
    echo "\nStop Container: $CONTAINER_NAME \n"
    #if [ "$(docker ps -aq -f status=exited -f name=$CONTAINER_NAME)" ]; then
        # Down container
    #fi
fi

#Up Container Wordpress and MySQL
docker-compose up -d --build 
echo "\nUp Container $CONTAINER_NAME"

#Permission and create plugin folder
docker exec -it $CONTAINER_NAME bash -c "
chown -R www-data:www-data /var/www/html/wp-content/"
#  &&
# cd wp-content/plugins/ && 
# mkdir -p plugin-name && touch plugin-name/plugin-name.php"
echo "Install and Create Folder Plugin\n"
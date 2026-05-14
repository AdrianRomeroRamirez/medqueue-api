#!/bin/sh

# Crear .env desde variables de entorno
cat > /var/www/html/.env << EOF
APP_NAME=MedQueue
APP_ENV=${APP_ENV}
APP_KEY=${APP_KEY}
APP_DEBUG=${APP_DEBUG}
APP_URL=${APP_URL}

LOG_CHANNEL=stderr

DB_CONNECTION=${DB_CONNECTION}
DB_HOST=${DB_HOST}
DB_PORT=${DB_PORT}
DB_DATABASE=${DB_DATABASE}
DB_USERNAME=${DB_USERNAME}
DB_PASSWORD=${DB_PASSWORD}

QUEUE_CONNECTION=${QUEUE_CONNECTION}
EOF

# Permisos
chmod -R 775 /var/www/html/storage
chmod -R 775 /var/www/html/bootstrap/cache

# Limpiar cache
php artisan config:clear

# Arrancar php-fpm y nginx
php-fpm -D && sleep 1 && nginx -g 'daemon off;'
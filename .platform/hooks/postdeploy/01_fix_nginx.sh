#!/bin/bash

# Fix nginx document root
sed -i 's|root /var/www/html;|root /var/www/html/public;|g' /etc/nginx/conf.d/elasticbeanstalk/php.conf

# Fix storage permissions
chmod -R 777 /var/www/html/storage
chmod -R 777 /var/www/html/bootstrap/cache

# Restart nginx
systemctl restart nginx
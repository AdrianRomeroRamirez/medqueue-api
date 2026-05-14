#!/bin/bash
# Las variables de entorno las gestiona Elastic Beanstalk via eb setenv
chmod -R 777 /var/www/html/storage 2>/dev/null || true
chmod -R 777 /var/www/html/bootstrap/cache 2>/dev/null || true
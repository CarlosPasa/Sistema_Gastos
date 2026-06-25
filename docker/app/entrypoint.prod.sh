#!/bin/sh
set -e

echo "Esperando a base de datos..."

until php -r "new PDO('mysql:host=${DB_HOST};port=${DB_PORT};dbname=${DB_DATABASE}', '${DB_USERNAME}', '${DB_PASSWORD}');" >/dev/null 2>&1
do
    echo "Base de datos no está lista todavía..."
    sleep 3
done

echo "Base de datos lista."

echo "Ejecutando migraciones..."
php artisan migrate --force

echo "Ejecutando seeders temporalmente..."
php artisan db:seed --force

echo "Generando cachés de producción..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Iniciando PHP-FPM..."
php-fpm -D

echo "Iniciando Nginx..."
nginx -g "daemon off;"

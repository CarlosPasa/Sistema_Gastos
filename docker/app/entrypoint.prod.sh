#!/bin/sh
set -e

echo "Esperando a MySQL..."

until php -r "new PDO('mysql:host=${DB_HOST};dbname=${DB_DATABASE}', '${DB_USERNAME}', '${DB_PASSWORD}');" >/dev/null 2>&1
do
    echo "MySQL no está listo todavía..."
    sleep 3
done

echo "MySQL listo."

echo "Ejecutando migraciones..."
php artisan migrate --force

echo "Generando cachés de producción..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Iniciando PHP-FPM..."
exec php-fpm -F

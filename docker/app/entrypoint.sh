#!/bin/sh
set -e

echo "Esperando a MySQL..."

until php -r "new PDO('mysql:host=mysql;dbname=sistema_gastos', 'laravel', 'root');" > /dev/null 2>&1
do
  echo "MySQL no está listo todavía..."
  sleep 3
done

echo "MySQL listo."

php artisan migrate --force
php artisan db:seed --force

echo "Iniciando PHP-FPM..."

exec php-fpm -F

@echo off
echo Clearing all Laravel caches...
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan event:clear
php artisan queue:clear
echo All caches cleared successfully!
pause
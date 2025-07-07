@echo off
echo Starting full Laravel reset...
echo.
echo Step 1: Clearing all caches...
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan event:clear
php artisan queue:clear
echo Caches cleared!
echo.
echo Step 2: Fresh migration with seeding...
php artisan migrate:fresh --seed
echo.
echo Full reset completed successfully!
pause
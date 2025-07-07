@echo off
echo Starting fresh migration and seeding...
php artisan migrate:fresh --seed
echo Fresh migration and seeding completed successfully!
pause
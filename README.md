# Translation Management Service

## Setup

1. Clone the repository.
2. Run `composer install`.
3. Create a database in mysql or mariadb before moving to next step
4. Edit `.env` and update the database credentials.
5. Run `php artisan migrate --seed` to set up the database.
6. Run `php artisan translations:populate` to populate the database with 100k+ records.
7. Run `php artisan serve` to start the application.

## Documentation

* Please find the API documentation at https://documenter.getpostman.com/view/2718860/2sAYdfpWWT





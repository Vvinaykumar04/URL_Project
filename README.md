## Setup

Create the main and test MySQL databases first:


CREATE DATABASE testProject;
CREATE DATABASE testProject_testing;



Configure environment files:
- `.env` for the main application database
- `.env.testing` for the test database



Install dependencies and generate the app key:
composer install
npm install
php artisan key:generate



Run migrations for the main database:
php artisan migrate


Seed the default super admin:
php artisan db:seed



## Testing
I used 2 test method

Run the unit test:
php artisan test tests/Unit/UserTest.php

Run the feature tests:
php artisan test tests/Feature/ShortUrlVisibilityTest.php
php artisan test tests/Feature/MemberShortUrlVisibilityTest.php
php artisan test tests/Feature/PublicShortUrlRedirectTest.php

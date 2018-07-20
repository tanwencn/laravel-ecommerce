# Laravel Ecommerce

Laravel Admin & Blog System, supporting Laravel 5.5 and 5.6!

## Installation Steps

### 1.Require the Package
```bash
composer require tanwencn/laravel-ecommerce
```

### 2.Configuration database
Next make sure to create a new database and add your database credentials to your .env file:
```bash
DB_HOST=localhost
DB_DATABASE=homestead
DB_USERNAME=homestead
DB_PASSWORD=secret
```

### 3.Run The Installer
```bash
php artisan blog:install
php artisan ecommerce:install
```

### Default an Admin User
    URL:http://youwebsite/admin
    email: admin@admin.com   
    password: admin


# BusYan BE (Laravel)

## Requirements
1. PHP Version: 8+
2. Composer
3. MySql server

## Getting Started

### Clone Repository
```
git clone https://github.com/Miyunecadz/bus-yan-be.git
```

## Setting Up

### Create copy of ENV
Create a copy of .env.example and name it `.env`

**Command**

```
cp .env.example .env
```

### Modifying the ENV file
You can modify the env based on your preferences.
**Ex:**
Changing the database that will be used.

**FROM:**
```
DB_CONNECTION=postgre
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=
```
**TO:**
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=busyan
DB_USERNAME=root
DB_PASSWORD=1234
```
**NOTE:**
Sensitive data like credentials should be saved in env file. Make sure your development env or production env should not be included in your repository to prevent being used by other devs.

### Create database
In your sql server, create database make sure the name of the database should be the same in env file `DB_DATABASE`

### Install dependencies
```
composer install
```

**NOTE:**
Make sure composer is installed in your machine. You can also download composer [here](https://getcomposer.org/ "Composer").

### Migrate tables
To my migrate all tables. For more information regarding  [Laravel Migration](https://laravel.com/docs/10.x/migrations "Laravel Migration")
```
php artisan migrate
```

### Seeding dummy data
To run seeder. For more information regarding [Laravel Seeder](https://laravel.com/docs/10.x/seeding "Laravel Seeder")
```
php artisan db:seed
```

### Serve application
To run the application
```
php artisan serve
```

# Ecommerce API with PHP

# Project Setup

This is a guide on how to set up the project

## Prerequisites

Before starting, you need to have the following installed on your machine:

- Apache web server
- PHP
- MySQL

## Installation

1. Clone this repository to your local machine using 
```php
git clone https://github.com/cybernerdie/php-ecommerce.git
```

3. Install dependencies with `composer install`

4. Create a new MySQL database for your project.

5. Create a `.env` file at the root of the project and add your database credentials in the following format:

```php
DB_HOST=localhost
DB_USERNAME=yourdatabaseusername
DB_PASSWORD=yourdatabasepassword
DB_NAME=yourdatabasename
```

## Test 
Run tests using PHPUnit:

```php
vendor/bin/phpunit
```




# Ecommerce API with PHP

# Project Setup

This is a guide on how to set up the project with the following components:

- Apache web server
- PHP
- MySQL

## Prerequisites

Before starting, you need to have the following installed on your machine:

- Apache web server
- PHP
- MySQL

## Installation

1. Clone this repository to your local machine using `git clone`.

2. Create a new MySQL database for your project.

3. Update the database credentials in the `config.php` file with your own database credentials.

```php
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', 'password');
define('DB_NAME', 'database_name');



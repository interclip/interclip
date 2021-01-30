# Setting up Interclip locally
Hi, thanks for considering contributing to Interclip! Here's a guide on how to get you set up.

## Prerequisites
- Apache server (needed for the .htaccess redirections)
- PHP 7.4+
- MySQL database server

## Installing dependencies
1. `composer install`

## Setting up the env files
In the `includes/` directory, create two files with the following contents:
1. db.php
```php
<?php
$servername = "host:port";
$DBName = "name";
$username = 'user';
$password = 'pass';
```
2. salt.php
```php
<?php
$salt = "aEk8szZcZRjDGUvnoJWT6ECcnHTGWXFKR3M7v63CL2GbmNYD4QEJz3Z2H9jdrGXe6Uigk"; // this can be almost anything, it is used to hash the IP adresses used by the rate limiter
```
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

## Setting up the database
You can create the database and the tables by just executing the following SQL query:
https://litter.catbox.moe/gf4n60.sql
```sql

/* Rate limit table */
DROP TABLE IF EXISTS `hits`;
CREATE TABLE `hits` (
  `id` int NOT NULL AUTO_INCREMENT,
  `iphash` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `date` datetime NOT NULL,
  `operation` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4209 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/* Clips table */
DROP TABLE IF EXISTS `userurl`;
CREATE TABLE `userurl` (
  `id` int NOT NULL AUTO_INCREMENT,
  `usr` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `url` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `date` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `expires` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1182 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
```
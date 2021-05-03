<?php
define('ROOT_DIR', realpath(__DIR__ . '/../../')); // Set the root directory of where Interclip sits

require ROOT_DIR . "/vendor/autoload.php";

/* Load the app config from .env */

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__, '../../.env');
$dotenv->safeLoad();

define("ROOT", $_ENV['ROOT']);

//By default, we assume that PHP is NOT running on windows.
$isWindows = false;

//If the first three characters PHP_OS are equal to "WIN",
//then PHP is running on a Windows operating system.
if (strcasecmp(substr(PHP_OS, 0, 3), 'WIN') === 0) {
    $isWindows = true;
}

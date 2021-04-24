<?php
define('ROOT_DIR', realpath(__DIR__ . '/../../')); // Set the root directory of where Interclip sits

require ROOT_DIR . "/vendor/autoload.php";

/* Load the app config from .env */

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__, '../../.env');
$dotenv->safeLoad();

define("ROOT", $_ENV['ROOT']);

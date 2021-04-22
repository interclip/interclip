<?php
require "vendor/autoload.php";
require "includes/header.php";

// Do we have an authenticated session available?
if ($auth0->getUser()) {
    // Logout the user
    $auth0->logout();
}

header("Location: " . ROOT . "/");

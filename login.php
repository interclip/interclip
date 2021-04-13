<?php
require "vendor/autoload.php";
require "includes/header.php";

// Do we have an authenticated session available?
if ($auth0->getUser()) {
    // Output the authenticated user
    header("Location: " . ROOT . "/");
} else {
    // No session was available, so redirect to Universal Login page
    $auth0->login();
}

<?php
require "vendor/autoload.php";
require "includes/header.php";

// Do we have an authenticated session available?
if ($user = $auth0->getUser()) {
    // Logout the user
    $auth0->logout();
} else {
    // No session was available, so redirect to Universal Login page
    $auth0->login();
}

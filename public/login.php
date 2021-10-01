<?php
require "includes/lib/auth.php";

if ($_ENV['AUTH_TYPE'] === "account") {
    // Do we have an authenticated session available?
    $session = $auth0->getCredentials();
    if ($session !== null) {
        // Output the authenticated user
        header("Location: " . ROOT . "/");
    } else {
        // No session was available, so redirect to Universal Login page
        $auth0->login();
    }
} else {
    header("Location: " . ROOT . "/");
}
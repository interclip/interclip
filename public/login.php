<?php
require "includes/lib/auth.php";

if ($_ENV['AUTH_TYPE'] === "account") {
    // Do we have an authenticated session available?
    $session = $auth0->getCredentials();
    if ($session !== null) {
        // Output the authenticated user
        header("Location: " . ROOT . "/");
        exit;
    } else {
        if ($session === null && isset($_GET['code']) && isset($_GET['state'])) {
            if ($auth0->exchange() === false) {
                die("Authentication failed.");
            }
        }
        // No session was available, so redirect to Universal Login page
        header('Location: ' . $auth0->login());
        exit;
    }
} else {
    header("Location: " . ROOT . "/");
}
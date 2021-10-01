<?php
require "includes/lib/init.php";
require "includes/lib/auth.php";

if ($_ENV['AUTH_TYPE'] === "account") {
    $session = $auth0->getCredentials();
    if ($session !== null) {
        // Logout the user
        $auth0->logout();
    }
}

header("Location: " . ROOT . "/");

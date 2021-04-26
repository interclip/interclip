<?php
require "includes/lib/init.php";
require "includes/lib/auth.php";

if ($_ENV['AUTH_TYPE'] === "account") {
    // Do we have an authenticated session available?
    if ($auth0->getUser()) {
        // Logout the user
        $auth0->logout();
    }
}

header("Location: " . ROOT . "/");

<?php
require_once "includes/lib/auth.php";
require_once "includes/anti-csrf.php";
require_once "includes/lib/headers.php";

header('X-Robots-Tag: noindex, nofollow, noarchive');

if ($_ENV['AUTH_TYPE'] === "account") {
    $session = $auth0->getCredentials();
    if ($session !== null) {
        header("Location: " . ROOT . "/");
        exit;
    }

    if (isset($_GET['code'], $_GET['state'])) {
        try {
            if ($auth0->exchange() === false) {
                throw new RuntimeException('Authentication exchange failed.');
            }
        } catch (Throwable $error) {
            error_log('Authentication callback failed (' . $error::class . ').');
            http_response_code(401);
            exit('Authentication failed. Please try again.');
        }

        startSecureSession();
        session_regenerate_id(true);
        $_SESSION = [];
        store();

        header("Location: " . ROOT . "/");
        exit;
    }

    header('Location: ' . $auth0->login());
    exit;
} else {
    header("Location: " . ROOT . "/");
    exit;
}

<?php
require_once "includes/lib/init.php";
require_once "includes/lib/auth.php";
require_once "includes/anti-csrf.php";
require_once "includes/lib/headers.php";

header('X-Robots-Tag: noindex, nofollow, noarchive');

validate();

if ($_ENV['AUTH_TYPE'] === "account") {
    $session = $auth0->getCredentials();
    if ($session !== null) {
        $returnUri = rtrim((string) $_ENV['APP_URL'], '/') . (ROOT === '' ? '' : ROOT) . '/';
        $logoutUrl = $auth0->logout($returnUri);
    }
}

destroySecureSession();
header('Clear-Site-Data: "storage"');
header('Location: ' . ($logoutUrl ?? ROOT . '/'), true, 302);
exit;

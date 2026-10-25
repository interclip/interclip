<?php
require_once "includes/lib/auth.php";
require_once "includes/anti-csrf.php";
require_once "includes/lib/headers.php";

header('X-Robots-Tag: noindex, nofollow, noarchive');

if ($usesOidc) {
    try {
        if (isset($_GET['code']) || isset($_GET['state']) || isset($_GET['error'])) {
            completeOidcLogin($oidc);
            header('Location: ' . ROOT . '/');
            exit;
        }
        if ($user !== false) {
            header('Location: ' . ROOT . '/');
            exit;
        }
        clearOidcLoginAttempt();
        $authorizationUrl = $oidc->authorizationUrl($_SESSION);
        session_write_close();
        header('Location: ' . $authorizationUrl);
        exit;
    } catch (Throwable $error) {
        startSecureSession();
        clearOidcLoginAttempt();
        unset($_SESSION['oidc_identity']);
        error_log('OIDC authentication failed (' . $error::class . ').');
        http_response_code(401);
        exit('Authentication failed. Please try logging in again.');
    }
}

header('Location: ' . ROOT . '/');
exit;

<?php

const CSRF_TOKEN_LIFETIME = 7200;

function startSecureSession(): void
{
    if (session_status() === PHP_SESSION_ACTIVE) {
        return;
    }

    $secure = ($_ENV['ENVIRONMENT'] ?? 'production') !== 'development'
        || strtolower($_ENV['PROTOCOL'] ?? 'https') === 'https';

    ini_set('session.use_strict_mode', '1');
    ini_set('session.use_only_cookies', '1');
    session_name($secure ? '__Host-InterclipSession' : 'InterclipSession');
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'secure' => $secure,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}

function store(): string
{
    startSecureSession();

    if (
        !isset($_SESSION['token'], $_SESSION['token-expire'])
        || !is_string($_SESSION['token'])
        || (int) $_SESSION['token-expire'] <= time()
    ) {
        $_SESSION['token'] = bin2hex(random_bytes(32));
        $_SESSION['token-expire'] = time() + CSRF_TOKEN_LIFETIME;
    }

    return $_SESSION['token'];
}

function csrfTokenFromRequest(): ?string
{
    if (isset($_SERVER['HTTP_X_CSRF_TOKEN']) && is_string($_SERVER['HTTP_X_CSRF_TOKEN'])) {
        return $_SERVER['HTTP_X_CSRF_TOKEN'];
    }

    return isset($_POST['token']) && is_string($_POST['token']) ? $_POST['token'] : null;
}

function validateCsrfToken(?string $token): bool
{
    startSecureSession();

    return $token !== null
        && isset($_SESSION['token'], $_SESSION['token-expire'])
        && is_string($_SESSION['token'])
        && (int) $_SESSION['token-expire'] > time()
        && hash_equals($_SESSION['token'], $token);
}

function validate(): bool
{
    if (!validateCsrfToken(csrfTokenFromRequest())) {
        http_response_code(403);
        header('Content-Type: text/plain; charset=UTF-8');
        exit('Invalid or expired CSRF token. Reload the page and try again.');
    }

    return true;
}

function destroySecureSession(): void
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        return;
    }

    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', [
            'expires' => time() - 3600,
            'path' => $params['path'],
            'domain' => $params['domain'],
            'secure' => $params['secure'],
            'httponly' => $params['httponly'],
            'samesite' => $params['samesite'] ?? 'Lax',
        ]);
    }

    session_destroy();
}

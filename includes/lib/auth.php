<?php

include_once __DIR__ . '/init.php';
include_once __DIR__ . '/database.php';

$oidc = null;
$conn = null;
$isStaff = false;
$user = false;

$authType = $_ENV['AUTH_TYPE'] ?? 'account';
$usesOidc = $authType === 'account';
$environment = $_ENV['ENVIRONMENT'] ?? 'production';

if ($authType === 'account') {
    $appUrl = rtrim($_ENV['APP_URL'] ?? '', '/');
    $appUrlParts = parse_url($appUrl);

    if (
        $appUrl === ''
        || $appUrlParts === false
        || !isset($appUrlParts['scheme'], $appUrlParts['host'])
        || !in_array(strtolower($appUrlParts['scheme']), ['http', 'https'], true)
        || isset($appUrlParts['user'])
        || isset($appUrlParts['pass'])
        || isset($appUrlParts['query'])
        || isset($appUrlParts['fragment'])
        || (isset($appUrlParts['path']) && !in_array($appUrlParts['path'], ['', '/'], true))
    ) {
        throw new RuntimeException('APP_URL must be a valid absolute HTTP(S) URL.');
    }

    if ($environment !== 'development' && strtolower($appUrlParts['scheme']) !== 'https') {
        throw new RuntimeException('APP_URL must use HTTPS outside development.');
    }

    $rootPath = ROOT === '' ? '' : '/' . trim(ROOT, '/');
    $redirectUri = $appUrl . $rootPath . '/login';

    require_once __DIR__ . '/oidc.php';
    startSecureSession();
    $oidc = createOidcClient($redirectUri);
    $user = oidcSessionUser($_SESSION, $_ENV['OIDC_ISSUER'], $_ENV['OIDC_CLIENT_ID'], time());
    if ($user === false) {
        unset($_SESSION['oidc_identity']);
    }
} elseif ($authType === 'mock') {
    if ($environment !== 'development') {
        throw new RuntimeException('Mock authentication is only available in development.');
    } else {
        $user = [
            'sub' => 'mock|local-development',
            'nickname' => 'Local developer',
            'email' => 'admin@example.org',
            'email_verified' => true,
        ];
    }
} else {
    throw new RuntimeException('Unsupported AUTH_TYPE configuration.');
}

if ($user !== false) {
    $subject = isset($user['sub']) && is_string($user['sub']) ? $user['sub'] : '';
    $email = isset($user['email']) && is_string($user['email']) ? trim($user['email']) : '';
    // Email is profile data; permissions belong to the immutable subject.
    if ($subject === '' || filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
        error_log('Authenticated identity was missing a stable subject or valid email address.');
        if ($usesOidc) {
            unset($_SESSION['oidc_identity']);
        }
        $user = false;
    } else {
        try {
            $conn = openDatabaseConnection();

            if ($usesOidc) {
                $subject = oidcAccountSubject($_ENV['OIDC_ISSUER'], $subject);
            }

            $stmt = $conn->prepare('SELECT id, role FROM accounts WHERE subject = ? LIMIT 1');
            $stmt->bind_param('s', $subject);
            $stmt->execute();
            $result = $stmt->get_result();
            $account = $result->fetch_assoc();

            if ($account !== null) {
                $update = $conn->prepare('UPDATE accounts SET email = ? WHERE subject = ?');
                $update->bind_param('ss', $email, $subject);
                $update->execute();
                $isStaff = hash_equals('staff', (string) $account['role']);
            } else {
                $role = 'visitor';
                $insert = $conn->prepare('INSERT INTO accounts (subject, email, role) VALUES (?, ?, ?)');
                $insert->bind_param('sss', $subject, $email, $role);
                $insert->execute();
            }
        } catch (Throwable $error) {
            error_log('Account lookup failed: ' . $error->getMessage());
            $isStaff = false;
        }
    }
}

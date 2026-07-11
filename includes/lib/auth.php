<?php

include_once __DIR__ . '/init.php';

use Auth0\SDK\Auth0;

$auth0 = null;
$conn = null;
$isStaff = false;
$user = false;
$hasAuth0Credentials = false;

$authType = $_ENV['AUTH_TYPE'] ?? 'account';
$environment = $_ENV['ENVIRONMENT'] ?? 'production';

if ($authType === 'account') {
    $appUrl = rtrim($_ENV['APP_URL'] ?? '', '/');
    $appUrlParts = parse_url($appUrl);
    $cookieSecret = $_ENV['AUTH0_COOKIE_SECRET'] ?? '';

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

    if (strlen($cookieSecret) < 32 || str_starts_with($cookieSecret, 'replace-with-')) {
        throw new RuntimeException('AUTH0_COOKIE_SECRET must contain at least 32 bytes.');
    }

    $rootPath = ROOT === '' ? '' : '/' . trim(ROOT, '/');
    $redirectUri = $appUrl . $rootPath . '/login';

    $auth0 = new Auth0([
        'domain' => $_ENV['AUTH0_DOMAIN'] ?? '',
        'clientId' => $_ENV['AUTH0_CLIENT_ID'] ?? '',
        'clientSecret' => $_ENV['AUTH0_CLIENT_SECRET'] ?? '',
        'redirectUri' => $redirectUri,
        'cookieSecret' => $cookieSecret,
        'cookieExpires' => 0,
        'cookieSecure' => strtolower($appUrlParts['scheme']) === 'https',
        'cookieSameSite' => 'Lax',
    ]);

    if ($auth0->getCredentials() !== null) {
        $hasAuth0Credentials = true;
        $candidate = $auth0->getUser();
        if (is_array($candidate)) {
            $user = $candidate;
        } else {
            $auth0->clear();
            $hasAuth0Credentials = false;
        }
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
    $subject = isset($user['sub']) && is_string($user['sub']) ? trim($user['sub']) : '';
    $email = isset($user['email']) && is_string($user['email']) ? trim($user['email']) : '';
    $emailVerified = $authType === 'mock' || ($user['email_verified'] ?? false) === true;

    if ($subject === '' || !$emailVerified || filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
        error_log('Authenticated identity was missing a stable subject or verified email address.');
        if ($hasAuth0Credentials && $auth0 instanceof Auth0) {
            $auth0->clear();
        }
        $user = false;
    } else {
        try {
            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
            $conn = new mysqli(
                $_ENV['DB_SERVER'] ?? '',
                $_ENV['USERNAME'] ?? '',
                $_ENV['PASSWORD'] ?? '',
                $_ENV['DB_NAME'] ?? ''
            );
            $conn->set_charset('utf8mb4');

            $stmt = $conn->prepare(
                'SELECT id, subject, role FROM accounts '
                . 'WHERE subject = ? OR (subject IS NULL AND email = ?) '
                . 'ORDER BY subject IS NOT NULL DESC LIMIT 1'
            );
            $stmt->bind_param('ss', $subject, $email);
            $stmt->execute();
            $result = $stmt->get_result();
            $account = $result->fetch_assoc();

            if ($account !== null) {
                if ($account['subject'] === null) {
                    if (hash_equals('staff', (string) $account['role'])) {
                        error_log('Refusing to automatically claim a legacy staff account without a preassigned subject.');
                    } else {
                        $claim = $conn->prepare('UPDATE accounts SET subject = ?, email = ? WHERE id = ? AND subject IS NULL');
                        $accountId = (int) $account['id'];
                        $claim->bind_param('ssi', $subject, $email, $accountId);
                        $claim->execute();

                        if ($claim->affected_rows === 1) {
                            $claimedAccount = $conn->prepare('SELECT role FROM accounts WHERE subject = ? LIMIT 1');
                            $claimedAccount->bind_param('s', $subject);
                            $claimedAccount->execute();
                            $claimedRole = $claimedAccount->get_result()->fetch_assoc();
                            $isStaff = is_array($claimedRole)
                                && hash_equals('staff', (string) ($claimedRole['role'] ?? ''));
                        }
                    }
                } elseif ($email !== '') {
                    $update = $conn->prepare('UPDATE accounts SET email = ? WHERE subject = ?');
                    $update->bind_param('ss', $email, $subject);
                    $update->execute();
                    $isStaff = hash_equals('staff', (string) $account['role']);
                }
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

<?php

// Set the root directory of where Interclip sits
define('ROOT_DIR', realpath(__DIR__ . '/../../'));

require ROOT_DIR . "/vendor/autoload.php";
require_once __DIR__ . '/security.php';
date_default_timezone_set('UTC');

/* Load the app config from .env */

$appEnvironmentKeys = [
    'APP_BRANCH', 'APP_COMMIT', 'APP_RELEASE', 'APP_URL',
    'AUTH0_CLIENT_ID', 'AUTH0_CLIENT_SECRET', 'AUTH0_COOKIE_SECRET', 'AUTH0_DOMAIN', 'AUTH_TYPE',
    'CLIP_RATE_LIMIT', 'CLIP_RATE_WINDOW', 'CLIP_READ_RATE_LIMIT', 'CLIP_READ_RATE_WINDOW',
    'DB_NAME', 'DB_SERVER', 'ENVIRONMENT', 'FILES_API_TOKEN', 'FILES_UPLOAD_HOST',
    'FILE_UPLOAD_ENABLED', 'IP_HASH_KEY', 'PASSWORD', 'PROTOCOL',
    'RATE_LIMIT_FAIL_CLOSED', 'REDIS_HOST', 'REDIS_KEY_PREFIX', 'REDIS_PASSWORD', 'REDIS_PORT',
    'ROOT', 'SENTRY_URL', 'TRACES_SAMPLE_RATE', 'TRUSTED_PROXIES', 'USERNAME',
];
$environmentRepository = Dotenv\Repository\RepositoryBuilder::createWithDefaultAdapters()
    ->immutable()
    ->allowList($appEnvironmentKeys)
    ->make();
$dotenv = Dotenv\Dotenv::create($environmentRepository, ROOT_DIR);
$dotenv->safeLoad();
foreach ($appEnvironmentKeys as $environmentKey) {
    if (array_key_exists($environmentKey, $_ENV)) {
        continue;
    }

    $externalValue = getenv($environmentKey);
    if (is_string($externalValue)) {
        $_ENV[$environmentKey] = $externalValue;
    } elseif (isset($_SERVER[$environmentKey]) && is_string($_SERVER[$environmentKey])) {
        $_ENV[$environmentKey] = $_SERVER[$environmentKey];
    }
}

use Tracy\Debugger;

$environment = $_ENV['ENVIRONMENT'] ?? 'production';
if (!in_array($environment, ['production', 'staging', 'development'], true)) {
    ini_set('display_errors', '0');
    throw new RuntimeException('Invalid application environment.');
}

if ($environment === 'development') {
    Debugger::enable(Debugger::DEVELOPMENT);
} else {
    ini_set('display_errors', '0');
    ini_set('log_errors', '1');
    Debugger::enable(Debugger::PRODUCTION);
}

$rootPath = rtrim($_ENV['ROOT'] ?? '', '/');
if ($rootPath !== '' && preg_match('#^/[A-Za-z0-9/_-]*$#D', $rootPath) !== 1) {
    throw new RuntimeException('ROOT must be an absolute URL path.');
}
define("ROOT", $rootPath);

if ($environment !== 'development') {
    $appUrl = rtrim((string) ($_ENV['APP_URL'] ?? ''), '/');
    $appUrlParts = parse_url($appUrl);
    if (
        $appUrl === ''
        || $appUrlParts === false
        || !isset($appUrlParts['scheme'], $appUrlParts['host'])
        || strtolower((string) $appUrlParts['scheme']) !== 'https'
        || isset($appUrlParts['user'])
        || isset($appUrlParts['pass'])
        || isset($appUrlParts['query'])
        || isset($appUrlParts['fragment'])
        || (isset($appUrlParts['path']) && !in_array($appUrlParts['path'], ['', '/'], true))
    ) {
        throw new RuntimeException('APP_URL must be a valid HTTPS origin outside development.');
    }

    if (PHP_SAPI !== 'cli') {
        $isHttpsRequest = requestUsesHttps();
        $requestTarget = (string) ($_SERVER['REQUEST_URI'] ?? '/');
        if ($requestTarget === '' || $requestTarget[0] !== '/' || preg_match('/[\x00-\x1f\x7f]/', $requestTarget) === 1) {
            $requestTarget = '/';
        }

        if (!$isHttpsRequest) {
            header('Location: ' . $appUrl . $requestTarget, true, 308);
            exit;
        }

        $requestHost = (string) ($_SERVER['HTTP_HOST'] ?? '');
        $requestHostParts = preg_match('/[\x00-\x20\x7f]/', $requestHost) === 1
            ? false
            : parse_url('http://' . $requestHost);
        if (
            !is_array($requestHostParts)
            || !isset($requestHostParts['host'])
            || !hash_equals(
                strtolower((string) $appUrlParts['host']),
                strtolower((string) $requestHostParts['host'])
            )
            || ((int) ($requestHostParts['port'] ?? 443)) !== ((int) ($appUrlParts['port'] ?? 443))
        ) {
            http_response_code(421);
            header('Content-Type: text/plain; charset=UTF-8');
            exit('Misdirected Request');
        }
    }

    $requiredSecrets = [
        'PASSWORD' => 16,
        'REDIS_PASSWORD' => 16,
        'IP_HASH_KEY' => 32,
    ];

    foreach ($requiredSecrets as $name => $minimumLength) {
        $value = $_ENV[$name] ?? '';
        if (!is_string($value) || strlen($value) < $minimumLength || str_starts_with($value, 'replace-with-')) {
            throw new RuntimeException($name . ' is not securely configured.');
        }
    }

    if (strtolower($_ENV['PROTOCOL'] ?? '') !== 'https') {
        throw new RuntimeException('PROTOCOL must be HTTPS outside development.');
    }

    if (strtolower($_ENV['USERNAME'] ?? '') === 'root') {
        throw new RuntimeException('The application database user must not be root outside development.');
    }

    if (!filter_var($_ENV['RATE_LIMIT_FAIL_CLOSED'] ?? false, FILTER_VALIDATE_BOOLEAN)) {
        throw new RuntimeException('Rate limiting must fail closed outside development.');
    }

    $trustedProxies = trim((string) ($_ENV['TRUSTED_PROXIES'] ?? ''));
    if ($trustedProxies === '') {
        throw new RuntimeException('TRUSTED_PROXIES must list the application proxy ranges outside development.');
    }
    foreach (explode(',', $trustedProxies) as $trustedProxy) {
        if (!isValidTrustedProxyRange($trustedProxy)) {
            throw new RuntimeException('TRUSTED_PROXIES contains an invalid or unsafe address range.');
        }
    }

    if (preg_match('/\A[A-Za-z0-9_-]{1,64}\z/D', (string) ($_ENV['REDIS_KEY_PREFIX'] ?? '')) !== 1) {
        throw new RuntimeException('REDIS_KEY_PREFIX must identify this deployment outside development.');
    }

    if (filter_var($_ENV['FILE_UPLOAD_ENABLED'] ?? false, FILTER_VALIDATE_BOOLEAN)) {
        $filesApiToken = (string) ($_ENV['FILES_API_TOKEN'] ?? '');
        $filesUploadHost = strtolower((string) ($_ENV['FILES_UPLOAD_HOST'] ?? ''));
        if (strlen($filesApiToken) < 32 || str_starts_with($filesApiToken, 'replace-with-')) {
            throw new RuntimeException('FILES_API_TOKEN must be a strong server-only credential.');
        }
        if (
            filter_var($filesUploadHost, FILTER_VALIDATE_DOMAIN, FILTER_FLAG_HOSTNAME) === false
            || !str_ends_with($filesUploadHost, '.amazonaws.com')
        ) {
            throw new RuntimeException('FILES_UPLOAD_HOST must pin the expected Amazon S3 upload host.');
        }
    }
}

// By default, we assume that PHP is NOT running on windows.
$isWindows = false;

// If the first three characters PHP_OS are equal to "WIN",
// then PHP is running on a Windows operating system.
if (strcasecmp(substr(PHP_OS, 0, 3), 'WIN') === 0) {
    $isWindows = true;
}

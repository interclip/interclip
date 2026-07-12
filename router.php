<?php

require_once __DIR__ . '/includes/lib/init.php';
require_once ROOT_DIR . '/includes/lib/sentry.php';
require_once ROOT_DIR . '/includes/lib/headers.php';
require_once ROOT_DIR . '/includes/lib/auth.php';
require_once ROOT_DIR . '/includes/anti-csrf.php';

$contentLength = filter_var($_SERVER['CONTENT_LENGTH'] ?? null, FILTER_VALIDATE_INT);
if ($contentLength !== false && $contentLength !== null && $contentLength > 65536) {
    http_response_code(413);
    header('Content-Type: text/plain; charset=UTF-8');
    exit('Request body too large.');
}

function startBrowserSession(): void
{
    startSecureSession();
}

function renderRouteError(int $status): never
{
    global $auth0, $conn, $isStaff, $user;

    http_response_code($status);
    $statusCode = $status;
    startBrowserSession();
    require ROOT_DIR . '/includes/error.php';
    exit;
}

/** @param string[] $allowedMethods */
function rejectHtmlMethod(array $allowedMethods): never
{
    header('Allow: ' . implode(', ', $allowedMethods));
    renderRouteError(405);
}

/** @param string[] $allowedMethods */
function rejectApiMethod(array $allowedMethods): never
{
    http_response_code(405);
    header('Allow: ' . implode(', ', $allowedMethods));
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode(['status' => 'error', 'result' => 'method not allowed'], JSON_UNESCAPED_SLASHES);
    exit;
}

$requestMethod = strtoupper((string) ($_SERVER['REQUEST_METHOD'] ?? 'GET'));
$effectiveMethod = $requestMethod === 'HEAD' ? 'GET' : $requestMethod;
$requestPath = parse_url((string) ($_SERVER['REQUEST_URI'] ?? '/'), PHP_URL_PATH);

if (!is_string($requestPath) || $requestPath === '' || preg_match('/[\x00-\x1f\x7f]/', $requestPath) === 1) {
    renderRouteError(400);
}

if (ROOT !== '') {
    if ($requestPath === ROOT) {
        $requestPath = '/';
    } elseif (str_starts_with($requestPath, ROOT . '/')) {
        $requestPath = substr($requestPath, strlen(ROOT));
    } else {
        renderRouteError(404);
    }
}

$routePath = $requestPath === '/' ? '/' : rtrim($requestPath, '/');

$browserRoutes = [
    '/' => 'public/index.php',
    '/receive' => 'public/receive.php',
    '/file' => 'public/file.php',
    '/admin' => 'public/admin.php',
    '/about' => 'public/about.php',
    '/privacy' => 'public/privacy.php',
];

if (isset($browserRoutes[$routePath])) {
    if ($effectiveMethod !== 'GET') {
        rejectHtmlMethod(['GET', 'HEAD']);
    }

    startBrowserSession();
    require ROOT_DIR . '/' . $browserRoutes[$routePath];
    exit;
}

if ($routePath === '/desktop') {
    if ($effectiveMethod !== 'GET') {
        rejectHtmlMethod(['GET', 'HEAD']);
    }
    require ROOT_DIR . '/public/desktop.php';
    exit;
}

if ($routePath === '/login') {
    if ($effectiveMethod !== 'GET') {
        rejectHtmlMethod(['GET', 'HEAD']);
    }
    require ROOT_DIR . '/public/login.php';
    exit;
}

if ($routePath === '/logout') {
    if ($requestMethod !== 'POST') {
        rejectHtmlMethod(['POST']);
    }
    require ROOT_DIR . '/public/logout.php';
    exit;
}

if ($routePath === '/get' || $routePath === '/set') {
    if ($requestMethod !== 'POST') {
        rejectHtmlMethod(['POST']);
    }
    require ROOT_DIR . ($routePath === '/get' ? '/public/core/get.php' : '/public/core/set.php');
    exit;
}

if ($routePath === '/api/set') {
    if (!in_array($requestMethod, ['GET', 'POST', 'OPTIONS'], true)) {
        rejectApiMethod(['POST', 'OPTIONS']);
    }
    require ROOT_DIR . '/public/api/set.php';
    exit;
}

if ($routePath === '/api/get') {
    if (!in_array($requestMethod, ['GET', 'POST'], true)) {
        rejectApiMethod(['GET', 'POST']);
    }
    require ROOT_DIR . '/public/api/get.php';
    exit;
}

if ($routePath === '/api/file') {
    if ($requestMethod !== 'POST') {
        rejectApiMethod(['POST']);
    }
    require ROOT_DIR . '/public/api/file.php';
    exit;
}

if ($routePath === '/api/admin/file-stats') {
    if ($effectiveMethod !== 'GET') {
        rejectApiMethod(['GET', 'HEAD']);
    }
    require ROOT_DIR . '/public/api/admin-file-stats.php';
    exit;
}

if ($routePath === '/staging/change-branch') {
    require ROOT_DIR . '/public/change-branch.php';
    exit;
}

$clipCode = trim($routePath, '/');
if (isValidClipCode($clipCode)) {
    if ($effectiveMethod !== 'GET') {
        rejectHtmlMethod(['GET', 'HEAD']);
    }

    $user_code = $clipCode;
    require_once ROOT_DIR . '/includes/lib/functions.php';
    require ROOT_DIR . '/includes/components/get.php';
    $url = lookupClipUrl($user_code);
    if ($url !== null) {
        reDir($url);
    }

    renderRouteError(404);
}

renderRouteError(404);

<?php

header_remove('X-Powered-By');

if (!isset($GLOBALS['cspNonce'])) {
    $GLOBALS['cspNonce'] = base64_encode(random_bytes(18));
}

if (!function_exists('cspNonce')) {
    function cspNonce(): string
    {
        return (string) $GLOBALS['cspNonce'];
    }
}

$scriptNonce = "'nonce-" . cspNonce() . "'";
$connectSources = ["'self'", 'https://ipfs.infura.io'];
$filesUploadHost = strtolower((string) ($_ENV['FILES_UPLOAD_HOST'] ?? ''));
if (
    filter_var($_ENV['FILE_UPLOAD_ENABLED'] ?? false, FILTER_VALIDATE_BOOLEAN)
    && filter_var($filesUploadHost, FILTER_VALIDATE_DOMAIN, FILTER_FLAG_HOSTNAME) !== false
) {
    $connectSources[] = 'https://' . $filesUploadHost;
}
$contentSecurityPolicy = implode('; ', [
    "default-src 'self'",
    "base-uri 'none'",
    'connect-src ' . implode(' ', $connectSources),
    "font-src 'self'",
    "form-action 'self'",
    "frame-ancestors 'none'",
    "img-src 'self' data: blob: https://images.weserv.nl",
    "manifest-src 'self'",
    "object-src 'none'",
    "script-src 'self' " . $scriptNonce,
    "style-src 'self' 'unsafe-inline'",
    "worker-src 'none'",
]);

header('Content-Security-Policy: ' . $contentSecurityPolicy);
header('Cross-Origin-Opener-Policy: same-origin');
header('Cross-Origin-Resource-Policy: same-origin');
header('Origin-Agent-Cluster: ?1');
header('Permissions-Policy: accelerometer=(), autoplay=(), camera=(), geolocation=(), gyroscope=(), magnetometer=(), microphone=(), payment=(), usb=()');
header('Referrer-Policy: no-referrer');
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('X-Permitted-Cross-Domain-Policies: none');
header('Cache-Control: no-store');

if (($_ENV['ENVIRONMENT'] ?? 'production') !== 'development' && strtolower($_ENV['PROTOCOL'] ?? 'https') === 'https') {
    header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
}

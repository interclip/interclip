<?php

include_once 'includes/lib/init.php';
include_once 'includes/anti-csrf.php';
include_once 'includes/components/rate.php';

header('Content-Type: application/json; charset=UTF-8');
header('X-Robots-Tag: noindex, nofollow, noarchive');

function uploadApiResponse(int $status, array $body): never
{
    http_response_code($status);
    echo json_encode($body, JSON_UNESCAPED_SLASHES);
    exit;
}

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
    header('Allow: POST');
    uploadApiResponse(405, ['status' => 'error', 'result' => 'method not allowed']);
}

if (!filter_var($_ENV['FILE_UPLOAD_ENABLED'] ?? false, FILTER_VALIDATE_BOOLEAN)) {
    uploadApiResponse(503, ['status' => 'error', 'result' => 'file uploads are disabled']);
}

$filesApiToken = (string) ($_ENV['FILES_API_TOKEN'] ?? '');
$allowedUploadHost = strtolower((string) ($_ENV['FILES_UPLOAD_HOST'] ?? ''));
if (
    strlen($filesApiToken) < 32
    || filter_var($allowedUploadHost, FILTER_VALIDATE_DOMAIN, FILTER_FLAG_HOSTNAME) === false
    || !str_ends_with($allowedUploadHost, '.amazonaws.com')
) {
    error_log('File upload integration is not securely configured.');
    uploadApiResponse(503, ['status' => 'error', 'result' => 'file uploads are unavailable']);
}

if (!validateCsrfToken(csrfTokenFromRequest())) {
    uploadApiResponse(403, ['status' => 'error', 'result' => 'invalid or expired CSRF token']);
}

enforceRateLimit('upload-presign', 30, 60);

$body = json_decode(file_get_contents('php://input'), true);
if (!is_array($body)) {
    uploadApiResponse(400, ['status' => 'error', 'result' => 'invalid JSON body']);
}

$name = isset($body['name']) && is_string($body['name']) ? trim($body['name']) : '';
$type = isset($body['type']) && is_string($body['type']) ? trim($body['type']) : '';
$size = filter_var($body['size'] ?? null, FILTER_VALIDATE_INT);

if ($name === '' || strlen($name) > 255 || preg_match('/[\x00-\x1F\x7F]/', $name)) {
    uploadApiResponse(400, ['status' => 'error', 'result' => 'invalid file name']);
}

if (strlen($type) > 255 || preg_match('/[\x00-\x1F\x7F]/', $type)) {
    uploadApiResponse(400, ['status' => 'error', 'result' => 'invalid media type']);
}

if ($size === false || $size < 1 || $size > 1024 * 1024 * 1024) {
    uploadApiResponse(413, ['status' => 'error', 'result' => 'file size is outside the allowed range']);
}

$query = [
    'name' => $name,
    'type' => $type,
    'size' => (string) $size,
];

$upstreamBody = '';
$upstreamTooLarge = false;
$curl = curl_init('https://iclip.vercel.app/api/uploadFile?' . http_build_query($query, '', '&', PHP_QUERY_RFC3986));
curl_setopt_array($curl, [
    CURLOPT_FOLLOWLOCATION => false,
    CURLOPT_CONNECTTIMEOUT => 5,
    CURLOPT_TIMEOUT => 15,
    CURLOPT_PROTOCOLS => CURLPROTO_HTTPS,
    CURLOPT_HTTPHEADER => [
        'Accept: application/json',
        'Authorization: Bearer ' . $filesApiToken,
    ],
    CURLOPT_WRITEFUNCTION => static function ($handle, string $chunk) use (&$upstreamBody, &$upstreamTooLarge): int {
        if (strlen($upstreamBody) + strlen($chunk) > 131072) {
            $upstreamTooLarge = true;
            return 0;
        }

        $upstreamBody .= $chunk;
        return strlen($chunk);
    },
]);

$upstreamSucceeded = curl_exec($curl);
$upstreamStatus = (int) curl_getinfo($curl, CURLINFO_RESPONSE_CODE);
$curlError = curl_error($curl);
curl_close($curl);

if ($upstreamSucceeded !== true || $upstreamTooLarge || $upstreamStatus < 200 || $upstreamStatus >= 300) {
    if ($curlError !== '') {
        error_log('Upload presign request failed: ' . $curlError);
    }

    $status = in_array($upstreamStatus, [400, 413, 429, 503], true) ? $upstreamStatus : 502;
    uploadApiResponse($status, ['status' => 'error', 'result' => 'unable to prepare upload']);
}

$upstream = json_decode($upstreamBody, true);
if (!is_array($upstream) || !isset($upstream['url'], $upstream['fields']) || !is_string($upstream['url']) || !is_array($upstream['fields'])) {
    error_log('Upload presign service returned an invalid response.');
    uploadApiResponse(502, ['status' => 'error', 'result' => 'invalid upload service response']);
}

$uploadUrl = parse_url($upstream['url']);
$uploadHost = is_array($uploadUrl) && isset($uploadUrl['host']) ? strtolower($uploadUrl['host']) : '';
if (
    !is_array($uploadUrl)
    || strtolower($uploadUrl['scheme'] ?? '') !== 'https'
    || !hash_equals($allowedUploadHost, $uploadHost)
) {
    error_log('Upload presign service returned a disallowed destination.');
    uploadApiResponse(502, ['status' => 'error', 'result' => 'invalid upload destination']);
}

$safeFields = [];
if (count($upstream['fields']) > 64) {
    uploadApiResponse(502, ['status' => 'error', 'result' => 'invalid upload policy']);
}
foreach ($upstream['fields'] as $key => $value) {
    if (!is_string($key) || (!is_string($value) && !is_numeric($value)) || strlen($key) > 128 || strlen((string) $value) > 8192) {
        uploadApiResponse(502, ['status' => 'error', 'result' => 'invalid upload policy']);
    }
    $safeFields[$key] = (string) $value;
}

if (!isset($safeFields['key']) || $safeFields['key'] === '' || str_contains($safeFields['key'], '..')) {
    uploadApiResponse(502, ['status' => 'error', 'result' => 'invalid upload policy']);
}

uploadApiResponse(200, ['url' => $upstream['url'], 'fields' => $safeFields]);

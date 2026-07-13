<?php

include_once 'includes/lib/init.php';
include_once 'includes/components/new.php';

header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Origin: *');
header('X-Robots-Tag: noindex, nofollow, noarchive');

function setApiResponse(int $status, string $responseStatus, string $result): never
{
    http_response_code($status);
    echo json_encode(['status' => $responseStatus, 'result' => $result], JSON_UNESCAPED_SLASHES);
    exit;
}

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'OPTIONS') {
    header('Access-Control-Allow-Headers: Content-Type');
    header('Access-Control-Allow-Methods: POST, OPTIONS');
    http_response_code(204);
    exit;
}

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
    header('Allow: POST');
    setApiResponse(405, 'error', 'method not allowed');
}

$url = isset($_POST['url']) && is_string($_POST['url']) ? $_POST['url'] : null;
if ($url === null && str_contains(strtolower($_SERVER['CONTENT_TYPE'] ?? ''), 'application/json')) {
    $body = json_decode(file_get_contents('php://input'), true);
    $url = is_array($body) && isset($body['url']) && is_string($body['url']) ? $body['url'] : null;
}

if ($url === null || $url === '') {
    setApiResponse(400, 'error', 'no URL provided');
}

[$code, $error] = createClip($url);
if ($error !== '' || $code === null) {
    $status = $error === '' || $error === 'invalid URL specified' ? 400 : 503;
    setApiResponse($status, 'error', $error === '' ? 'invalid URL specified' : $error);
}

setApiResponse(200, 'success', $code);

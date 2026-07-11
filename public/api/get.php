<?php

include_once 'includes/lib/init.php';
include_once 'includes/lib/security.php';

header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Origin: *');
header('X-Robots-Tag: noindex, nofollow, noarchive');

function getApiResponse(int $status, string $responseStatus, string $result): never
{
    http_response_code($status);
    echo json_encode(['status' => $responseStatus, 'result' => $result], JSON_UNESCAPED_SLASHES);
    exit;
}

$user_code = null;
if (isset($_POST['code']) && is_string($_POST['code'])) {
    $user_code = $_POST['code'];
} elseif (isset($_GET['code']) && is_string($_GET['code'])) {
    $user_code = $_GET['code'];
}

if ($user_code === null || !isValidClipCode($user_code)) {
    getApiResponse(400, 'error', 'invalid or missing clip code');
}

include_once 'includes/components/get.php';

if (isset($url)) {
    getApiResponse(200, 'success', $url);
}

getApiResponse(404, 'error', 'this clip does not exist');

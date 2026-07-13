<?php

include_once 'includes/lib/init.php';
include_once 'includes/lib/auth.php';

header('Content-Type: application/json; charset=UTF-8');
header('X-Robots-Tag: noindex, nofollow, noarchive');

if ($user === false) {
    http_response_code(401);
    echo json_encode(['status' => 'error', 'result' => 'authentication required']);
    exit;
}

if (!$isStaff) {
    http_response_code(403);
    echo json_encode(['status' => 'error', 'result' => 'staff access required']);
    exit;
}

$statisticsPath = ROOT_DIR . '/includes/size.json';
if (!is_readable($statisticsPath)) {
    http_response_code(503);
    echo json_encode(['status' => 'error', 'result' => 'file statistics unavailable']);
    exit;
}

$statistics = json_decode(file_get_contents($statisticsPath), true);
if (
    !is_array($statistics)
    || !isset($statistics['bytes'], $statistics['count'])
    || !is_int($statistics['bytes'])
    || !is_int($statistics['count'])
    || $statistics['bytes'] < 0
    || $statistics['count'] < 0
) {
    error_log('Invalid file statistics payload.');
    http_response_code(503);
    echo json_encode(['status' => 'error', 'result' => 'file statistics unavailable']);
    exit;
}

echo json_encode([
    'bytes' => $statistics['bytes'],
    'count' => $statistics['count'],
]);

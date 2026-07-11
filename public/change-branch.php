<?php

include_once 'includes/lib/init.php';
include_once 'includes/lib/auth.php';
include_once 'includes/anti-csrf.php';

header('Content-Type: application/json; charset=UTF-8');

function branchResponse(int $status, string $result): never
{
    http_response_code($status);
    echo json_encode([
        'status' => $status >= 200 && $status < 300 ? 'success' : 'error',
        'result' => $result,
    ], JSON_UNESCAPED_SLASHES);
    exit;
}

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
    header('Allow: POST');
    branchResponse(405, 'Method not allowed');
}

if (($_ENV['ENVIRONMENT'] ?? 'production') !== 'staging') {
    branchResponse(404, 'Not found');
}

if ($user === false) {
    branchResponse(401, 'Authentication required');
}

if (!$isStaff) {
    branchResponse(403, 'Staff access required');
}

if (!validateCsrfToken(csrfTokenFromRequest())) {
    branchResponse(403, 'Invalid or expired CSRF token');
}

$body = json_decode(file_get_contents('php://input'), true);
$targetBranch = is_array($body) && isset($body['branch']) && is_string($body['branch'])
    ? trim($body['branch'])
    : '';

if ($targetBranch === '' || str_starts_with($targetBranch, '-') || strlen($targetBranch) > 255) {
    branchResponse(400, 'Invalid branch');
}

exec('git for-each-ref --format="%(refname:short)" refs/heads refs/remotes/origin', $branchOutput, $branchStatus);
if ($branchStatus !== 0) {
    error_log('Could not enumerate staging branches.');
    branchResponse(500, 'Unable to enumerate branches');
}

$allowedBranches = [];
foreach ($branchOutput as $branch) {
    $branch = trim($branch);
    if ($branch === '' || str_ends_with($branch, '/HEAD')) {
        continue;
    }

    $allowedBranches[] = $branch;
    if (str_starts_with($branch, 'origin/')) {
        $allowedBranches[] = substr($branch, strlen('origin/'));
    }
}

if (!in_array($targetBranch, array_unique($allowedBranches), true)) {
    branchResponse(400, 'Unknown branch');
}

$pipes = [];
$process = proc_open(
    ['git', 'checkout', '--force', $targetBranch],
    [
        0 => ['pipe', 'r'],
        1 => ['pipe', 'w'],
        2 => ['pipe', 'w'],
    ],
    $pipes,
    ROOT_DIR
);

if (!is_resource($process)) {
    branchResponse(500, 'Unable to switch branches');
}

fclose($pipes[0]);
stream_get_contents($pipes[1]);
$stderr = stream_get_contents($pipes[2]);
fclose($pipes[1]);
fclose($pipes[2]);
$status = proc_close($process);

if ($status !== 0) {
    error_log('Staging branch switch failed: ' . trim($stderr));
    branchResponse(500, 'Unable to switch branches');
}

branchResponse(200, 'Branch switched');

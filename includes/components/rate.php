<?php

include_once __DIR__ . '/redis.php';
include_once __DIR__ . '/../lib/security.php';

function abortRateLimitedRequest(int $status, int $retryAfter, string $message): never
{
    http_response_code($status);
    header('Retry-After: ' . max(1, $retryAfter));

    $path = parse_url((string) ($_SERVER['REQUEST_URI'] ?? ''), PHP_URL_PATH);
    $apiPrefix = (ROOT === '' ? '' : ROOT) . '/api/';
    if (is_string($path) && str_starts_with($path, $apiPrefix)) {
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode(['status' => 'error', 'result' => $message], JSON_UNESCAPED_SLASHES);
    } else {
        header('Content-Type: text/plain; charset=UTF-8');
        echo $message;
    }

    exit;
}

function enforceRateLimit(string $scope, int $maximumHits, int $windowSeconds): bool
{
    if (preg_match('/\A[a-z0-9-]{1,32}\z/D', $scope) !== 1) {
        throw new InvalidArgumentException('Invalid rate-limit scope.');
    }

    $windowSeconds = max(1, $windowSeconds);
    $maximumHits = max(1, $maximumHits);

    if (!redisIsAvailable()) {
        if (filter_var($_ENV['RATE_LIMIT_FAIL_CLOSED'] ?? false, FILTER_VALIDATE_BOOLEAN)) {
            abortRateLimitedRequest(503, 30, 'service unavailable');
        }

        return false;
    }

    $ipHashKey = $_ENV['IP_HASH_KEY'] ?? '';
    $hashedIP = hash_hmac('sha256', clientIp(), (string) $ipHashKey);
    $count = ipHit($hashedIP, $windowSeconds, $scope);

    if ($count === null) {
        if (filter_var($_ENV['RATE_LIMIT_FAIL_CLOSED'] ?? false, FILTER_VALIDATE_BOOLEAN)) {
            abortRateLimitedRequest(503, $windowSeconds, 'service unavailable');
        }

        return false;
    }

    if ($count > $maximumHits) {
        abortRateLimitedRequest(429, $windowSeconds, 'rate limited');
    }

    return true;
}

function noteLimit(): bool
{
    return enforceRateLimit(
        'clip-create',
        (int) ($_ENV['CLIP_RATE_LIMIT'] ?? 15),
        (int) ($_ENV['CLIP_RATE_WINDOW'] ?? 30)
    );
}

function enforceClipLookupRateLimit(): bool
{
    return enforceRateLimit(
        'clip-read',
        (int) ($_ENV['CLIP_READ_RATE_LIMIT'] ?? 30),
        (int) ($_ENV['CLIP_READ_RATE_WINDOW'] ?? 60)
    );
}

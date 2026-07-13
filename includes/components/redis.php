<?php

include_once __DIR__ . '/../lib/security.php';

$GLOBALS['redisAvailable'] = false;
$GLOBALS['redis'] = null;

if (class_exists('Redis')) {
    try {
        $redisHost = $_ENV['REDIS_HOST'] ?? 'localhost';
        $redisPort = (int) ($_ENV['REDIS_PORT'] ?? 6379);
        $redis = new Redis();

        if ($redis->connect($redisHost, $redisPort, 1.0)) {
            $redis->setOption(Redis::OPT_READ_TIMEOUT, 1.0);
            $redisPassword = $_ENV['REDIS_PASSWORD'] ?? '';
            if (is_string($redisPassword) && $redisPassword !== '' && !$redis->auth($redisPassword)) {
                throw new RuntimeException('Redis authentication failed');
            }

            $GLOBALS['redis'] = $redis;
            $GLOBALS['redisAvailable'] = true;
        }
    } catch (Throwable $exception) {
        error_log('Redis connection failed: ' . $exception->getMessage());
    }
}

function redisKey(string $namespace, string $key): string
{
    $prefix = trim((string) ($_ENV['REDIS_KEY_PREFIX'] ?? 'interclip-development'));
    if (preg_match('/\A[A-Za-z0-9_-]{1,64}\z/D', $prefix) !== 1) {
        throw new RuntimeException('Invalid Redis key prefix');
    }

    return $prefix . ':' . $namespace . ':' . $key;
}

function redisIsAvailable(): bool
{
    if (!$GLOBALS['redisAvailable'] || !is_object($GLOBALS['redis'])) {
        return false;
    }

    try {
        return (bool) $GLOBALS['redis']->ping();
    } catch (Throwable $exception) {
        error_log('Redis became unavailable: ' . $exception->getMessage());
        $GLOBALS['redisAvailable'] = false;

        return false;
    }
}

function deleteRedisKey(string $key): void
{
    if (!redisIsAvailable()) {
        return;
    }

    try {
        $GLOBALS['redis']->del($key);
    } catch (Throwable $exception) {
        error_log('Redis delete failed: ' . $exception->getMessage());
        $GLOBALS['redisAvailable'] = false;
    }
}

/**
 * Increment a fixed-window rate bucket atomically when Redis is available.
 */
function ipHit(string $hashedIP, int $windowSeconds = 30, string $scope = 'clip-create'): ?int
{
    if (!redisIsAvailable()) {
        return null;
    }

    $redisKey = redisKey('rate:' . $scope, $hashedIP);
    $windowSeconds = max(1, $windowSeconds);
    $script = <<<'LUA'
local current = redis.call('INCR', KEYS[1])
if current == 1 then
    redis.call('EXPIRE', KEYS[1], ARGV[1])
end
return current
LUA;

    try {
        return (int) $GLOBALS['redis']->eval(
            $script,
            [$redisKey, $windowSeconds],
            1
        );
    } catch (Throwable $exception) {
        error_log('Redis rate-limit update failed');
        $GLOBALS['redisAvailable'] = false;

        return null;
    }
}

function getTotal(): int|string
{
    if (!redisIsAvailable()) {
        return 'n/a';
    }

    try {
        return (int) $GLOBALS['redis']->dbSize();
    } catch (Throwable $exception) {
        error_log('Redis size lookup failed: ' . $exception->getMessage());
        $GLOBALS['redisAvailable'] = false;

        return 'n/a';
    }
}

/**
 * Store non-clip application cache values in their own namespace.
 */
function storeRedis(string $key, string $value, int $expiration = 60 * 60 * 24 * 7): string|false
{
    if (!redisIsAvailable()) {
        return false;
    }

    $redisKey = redisKey('cache', $key);

    try {
        $cached = $GLOBALS['redis']->get($redisKey);
        if ($cached !== false) {
            return (string) $cached;
        }

        $GLOBALS['redis']->setEx($redisKey, max(1, $expiration), $value);

        return false;
    } catch (Throwable $exception) {
        error_log('Redis cache write failed: ' . $exception->getMessage());
        $GLOBALS['redisAvailable'] = false;

        return false;
    }
}

function getRedis(string $key): string|false
{
    if (!redisIsAvailable()) {
        return false;
    }

    try {
        $cached = $GLOBALS['redis']->get(redisKey('cache', $key));

        return $cached === false ? false : (string) $cached;
    } catch (Throwable $exception) {
        error_log('Redis cache read failed: ' . $exception->getMessage());
        $GLOBALS['redisAvailable'] = false;

        return false;
    }
}

/**
 * Cache a validated clip no longer than its database expiry.
 */
function storeClipRedis(string $code, string $url, int $expiresAt, ?int $now = null): bool
{
    $normalizedCode = normalizeClipCode($code);
    if ($normalizedCode === null || normalizeClipUrl($url) === null || !redisIsAvailable()) {
        return false;
    }

    $now ??= dateTimeUnixMicroseconds(new DateTimeImmutable('now', new DateTimeZone('UTC')));
    $ttlMilliseconds = intdiv(max(0, $expiresAt - $now) + 999, 1_000);
    $key = redisKey('clip', $normalizedCode);

    if ($ttlMilliseconds <= 0) {
        deleteRedisKey($key);

        return false;
    }

    try {
        $payload = json_encode(
            ['url' => $url, 'expires' => $expiresAt],
            JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES
        );

        return (bool) $GLOBALS['redis']->pSetEx($key, $ttlMilliseconds, $payload);
    } catch (Throwable $exception) {
        error_log('Redis clip write failed: ' . $exception->getMessage());
        $GLOBALS['redisAvailable'] = false;

        return false;
    }
}

/**
 * Return only unexpired, valid clip records from the clip namespace.
 */
function getClipRedis(string $code, ?int $now = null): ?string
{
    $normalizedCode = normalizeClipCode($code);
    if ($normalizedCode === null || !redisIsAvailable()) {
        return null;
    }

    $key = redisKey('clip', $normalizedCode);

    try {
        $cached = $GLOBALS['redis']->get($key);
        if ($cached === false) {
            return null;
        }

        $payload = json_decode((string) $cached, true, 8, JSON_THROW_ON_ERROR);
        $now ??= dateTimeUnixMicroseconds(new DateTimeImmutable('now', new DateTimeZone('UTC')));

        if (
            !is_array($payload)
            || !isset($payload['url'], $payload['expires'])
            || !is_string($payload['url'])
            || !is_int($payload['expires'])
            || $payload['expires'] <= $now
            || normalizeClipUrl($payload['url']) === null
        ) {
            deleteRedisKey($key);

            return null;
        }

        return $payload['url'];
    } catch (Throwable $exception) {
        deleteRedisKey($key);

        return null;
    }
}

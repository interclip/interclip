<?php

namespace Tests\Unit;

require_once dirname(__DIR__, 2) . '/includes/components/redis.php';
require_once dirname(__DIR__, 2) . '/includes/components/rate.php';

final class FakeRedisClient
{
    public array $values = [];
    public array $ttls = [];
    public array $deleted = [];
    public array $rateCounts = [];
    public array $evalArguments = [];
    public bool $throwOnEval = false;

    public function ping(): bool
    {
        return true;
    }

    public function get(string $key): string|false
    {
        return $this->values[$key] ?? false;
    }

    public function setEx(string $key, int $ttl, string $value): bool
    {
        $this->values[$key] = $value;
        $this->ttls[$key] = $ttl;

        return true;
    }

    public function pSetEx(string $key, int $ttl, string $value): bool
    {
        $this->values[$key] = $value;
        $this->ttls[$key] = $ttl;

        return true;
    }

    public function del(string $key): int
    {
        $this->deleted[] = $key;
        $existed = array_key_exists($key, $this->values);
        unset($this->values[$key], $this->ttls[$key]);

        return $existed ? 1 : 0;
    }

    public function eval(string $script, array $arguments, int $keyCount): int
    {
        if ($this->throwOnEval) {
            throw new \RuntimeException('sensitive connection detail');
        }

        $this->evalArguments[] = [$arguments, $keyCount];
        $key = $arguments[0];
        $this->rateCounts[$key] = ($this->rateCounts[$key] ?? 0) + 1;

        if ($this->rateCounts[$key] === 1) {
            $this->ttls[$key] = (int) $arguments[1];
        }

        return $this->rateCounts[$key];
    }

    public function dbSize(): int
    {
        return count($this->values);
    }
}

function withFakeRedis(callable $callback): void
{
    $previousClient = $GLOBALS['redis'];
    $previousAvailability = $GLOBALS['redisAvailable'];
    $previousPrefix = $_ENV['REDIS_KEY_PREFIX'] ?? null;
    $hadPrefix = array_key_exists('REDIS_KEY_PREFIX', $_ENV);
    $client = new FakeRedisClient();
    $GLOBALS['redis'] = $client;
    $GLOBALS['redisAvailable'] = true;
    $_ENV['REDIS_KEY_PREFIX'] = 'interclip-development';

    try {
        $callback($client);
    } finally {
        $GLOBALS['redis'] = $previousClient;
        $GLOBALS['redisAvailable'] = $previousAvailability;
        if ($hadPrefix) {
            $_ENV['REDIS_KEY_PREFIX'] = $previousPrefix;
        } else {
            unset($_ENV['REDIS_KEY_PREFIX']);
        }
    }
}

it('separates generic cache entries from clip entries', function () {
    withFakeRedis(function (FakeRedisClient $redis) {
        storeRedis('contributors', '["octocat"]', 60);

        expect($redis->values)->toHaveKey('interclip-development:cache:contributors')
            ->and(getRedis('contributors'))->toBe('["octocat"]')
            ->and($redis->values)->not()->toHaveKey('interclip-development:clip:contributors');
    });
});

it('caps clip cache TTL at the database expiry', function () {
    withFakeRedis(function (FakeRedisClient $redis) {
        $now = 1_700_000_000_123_456;
        $code = 'Ab1xZ';
        $url = 'https://example.com/a?b=c';

        expect(storeClipRedis($code, $url, $now + 3_600_000_000, $now))->toBeTrue()
            ->and($redis->ttls['interclip-development:clip:ab1xz'])->toBe(3_600_000)
            ->and(getClipRedis('AB1XZ', $now))->toBe($url);
    });
});

it('supports five-character codes through the namespaced cache', function () {
    withFakeRedis(function (FakeRedisClient $redis) {
        $now = 1_700_000_000_123_456;

        storeClipRedis('aB123', 'https://example.com/clip', $now + 60_000_000, $now);

        expect(getClipRedis('AB123', $now))->toBe('https://example.com/clip')
            ->and($redis->values)->toHaveKey('interclip-development:clip:ab123');
    });
});

it('evicts expired or malformed cached clip records', function () {
    withFakeRedis(function (FakeRedisClient $redis) {
        $now = 1_700_000_000_123_456;
        $expiredCode = 'Ab1xZ';
        $malformedCode = 'Pq2yR';
        $redis->values['interclip-development:clip:ab1xz'] = json_encode([
            'url' => 'https://example.com/expired',
            'expires' => $now - 1,
        ]);
        $redis->values['interclip-development:clip:pq2yr'] = json_encode([
            'url' => '/relative/path',
            'expires' => $now + 60_000_000,
        ]);

        expect(getClipRedis($expiredCode, $now))->toBeNull()
            ->and(getClipRedis($malformedCode, $now))->toBeNull()
            ->and($redis->deleted)->toContain('interclip-development:clip:ab1xz')
            ->and($redis->deleted)->toContain('interclip-development:clip:pq2yr');
    });
});

it('caches valid non-navigable URI schemes as inert clipboard data', function () {
    withFakeRedis(function (FakeRedisClient $redis) {
        $now = 1_700_000_000_123_456;
        $code = 'Js123';
        $uri = 'javascript:alert(1)';

        expect(storeClipRedis($code, $uri, $now + 60_000_000, $now))->toBeTrue()
            ->and(getClipRedis($code, $now))->toBe($uri);
    });
});

it('increments a namespaced fixed-window rate bucket', function () {
    withFakeRedis(function (FakeRedisClient $redis) {
        $hash = str_repeat('a', 64);

        expect(ipHit($hash, 30, 'clip-read'))->toBe(1)
            ->and(ipHit($hash, 30, 'clip-read'))->toBe(2)
            ->and($redis->ttls['interclip-development:rate:clip-read:' . $hash])->toBe(30)
            ->and($redis->evalArguments[0][0][0])->toBe('interclip-development:rate:clip-read:' . $hash)
            ->and($redis->evalArguments[0][1])->toBe(1);
    });
});

it('keys rate limits from REMOTE_ADDR instead of spoofed forwarding headers', function () {
    withFakeRedis(function (FakeRedisClient $redis) {
        $previousServer = $_SERVER;
        $previousSalt = $_ENV['IP_HASH_KEY'] ?? null;
        $hadSalt = array_key_exists('IP_HASH_KEY', $_ENV);
        $previousProxies = $_ENV['TRUSTED_PROXIES'] ?? null;
        $hadProxies = array_key_exists('TRUSTED_PROXIES', $_ENV);

        try {
            $_ENV['IP_HASH_KEY'] = 'test-rate-key';
            $_ENV['TRUSTED_PROXIES'] = '';
            $_SERVER['REMOTE_ADDR'] = '203.0.113.10';
            $_SERVER['HTTP_X_FORWARDED_FOR'] = '198.51.100.50';

            expect(noteLimit())->toBeTrue();

            $expectedHash = hash_hmac('sha256', '203.0.113.10', 'test-rate-key');
            expect($redis->evalArguments[0][0][0])->toBe('interclip-development:rate:clip-create:' . $expectedHash);
        } finally {
            $_SERVER = $previousServer;
            if ($hadSalt) {
                $_ENV['IP_HASH_KEY'] = $previousSalt;
            } else {
                unset($_ENV['IP_HASH_KEY']);
            }
            if ($hadProxies) {
                $_ENV['TRUSTED_PROXIES'] = $previousProxies;
            } else {
                unset($_ENV['TRUSTED_PROXIES']);
            }
        }
    });
});

it('does not expose Redis rate-limit exceptions in the response', function () {
    withFakeRedis(function (FakeRedisClient $redis) {
        $previousFailMode = $_ENV['RATE_LIMIT_FAIL_CLOSED'] ?? null;
        $hadFailMode = array_key_exists('RATE_LIMIT_FAIL_CLOSED', $_ENV);
        $startingBufferLevel = ob_get_level();
        $previousErrorLog = ini_get('error_log');
        $redis->throwOnEval = true;

        try {
            $_ENV['RATE_LIMIT_FAIL_CLOSED'] = 'false';
            ini_set('error_log', PHP_OS_FAMILY === 'Windows' ? 'NUL' : '/dev/null');
            ob_start();
            $result = noteLimit();
            $output = ob_get_clean();

            expect($result)->toBeFalse()
                ->and($output)->toBe('');
        } finally {
            while (ob_get_level() > $startingBufferLevel) {
                ob_end_clean();
            }
            ini_set('error_log', (string) $previousErrorLog);
            if ($hadFailMode) {
                $_ENV['RATE_LIMIT_FAIL_CLOSED'] = $previousFailMode;
            } else {
                unset($_ENV['RATE_LIMIT_FAIL_CLOSED']);
            }
        }
    });
});

<?php

require_once dirname(__DIR__, 2) . '/includes/lib/security.php';

it('accepts absolute URIs across schemes and userinfo forms', function () {
    $uris = [
        'https://example.com/path?one=two#fragment' => 'https://example.com/path?one=two#fragment',
        'http://127.0.0.1:8080/resource' => 'http://127.0.0.1:8080/resource',
        'https://user:password@example.com/path' => 'https://user:password@example.com/path',
        'ftp://user:password@example.com/file' => 'ftp://user:password@example.com/file',
        'ssh://git@example.com/repository' => 'ssh://git@example.com/repository',
        'mailto:user@example.com' => 'mailto:user@example.com',
        'tel:+123456789' => 'tel:+123456789',
        'magnet:?xt=urn:btih:abcdef' => 'magnet:?xt=urn:btih:abcdef',
        'urn:isbn:0451450523' => 'urn:isbn:0451450523',
        'custom:opaque-value' => 'custom:opaque-value',
        'file:///tmp/interclip.txt' => 'file:///tmp/interclip.txt',
        'javascript:alert(1)' => 'javascript:alert(1)',
        'data:text/plain,hello' => 'data:text/plain;charset=us-ascii,hello',
    ];

    foreach ($uris as $input => $expected) {
        expect(normalizeClipUrl($input))->toBe($expected);
    }
});

it('uses League URI canonicalization for recoverable input', function () {
    expect(normalizeClipUrl('HTTPS://Example.com/has a space'))->toBe('https://example.com/has%20a%20space')
        ->and(normalizeClipUrl('https://example.com/%ZZ'))->toBe('https://example.com/%25ZZ')
        ->and(normalizeClipUrl('https://example.com/<script>'))->toBe('https://example.com/%3Cscript%3E')
        ->and(normalizeClipUrl('foo:bar#x#y'))->toBe('foo:bar#x%23y')
        ->and(normalizeClipUrl('foo:path[bad]'))->toBe('foo:path%5Bbad%5D');
});

it('rejects relative or malformed clip URIs', function () {
    $invalidUris = [
        '',
        '/relative/path',
        '//example.com/path',
        "https://example.com/path\nInjected: value",
        '1invalid:scheme',
        'http://exa[mple.com',
        'https://example.com:abc',
        'http://user@@example.com',
        'http://[not-ip]/',
    ];

    foreach ($invalidUris as $uri) {
        expect(normalizeClipUrl($uri))->toBeNull();
    }
});

it('keeps non-HTTP and credential-bearing URIs inert in browser navigation contexts', function () {
    expect(isSafeNavigationUri('https://example.com/path'))->toBeTrue()
        ->and(isSafeNavigationUri('HTTPS://Example.com/path'))->toBeTrue()
        ->and(isSafeNavigationUri('https://user:password@example.com/path'))->toBeFalse()
        ->and(isSafeNavigationUri('mailto:user@example.com'))->toBeFalse()
        ->and(isSafeNavigationUri('file:///tmp/interclip.txt'))->toBeFalse()
        ->and(isSafeNavigationUri('custom:opaque-value'))->toBeFalse()
        ->and(isSafeNavigationUri('javascript:alert(1)'))->toBeFalse()
        ->and(isSafeNavigationUri('data:text/html,%3Cscript%3E'))->toBeFalse()
        ->and(isSafeNavigationUri('vbscript:msgbox(1)'))->toBeFalse();
});

it('enforces the clip URL length boundary without truncating', function () {
    $prefix = 'https://example.com/';
    $maximumUrl = $prefix . str_repeat('a', CLIP_URL_MAX_LENGTH - strlen($prefix));
    $oversizedUrl = $maximumUrl . 'a';
    $expandsPastStorageLimit = 'custom:' . str_repeat(' ', 700);

    expect(normalizeClipUrl($maximumUrl))->toBe($maximumUrl)
        ->and(normalizeClipUrl($oversizedUrl))->toBeNull()
        ->and(normalizeClipUrl($expandsPastStorageLimit))->toBeNull();
});

it('accepts longer legacy destinations only at the storage boundary', function () {
    $legacyUrl = 'custom:' . str_repeat('a', CLIP_URL_MAX_LENGTH);
    $tooLarge = 'custom:' . str_repeat('a', CLIP_STORED_URL_MAX_LENGTH);

    expect(normalizeClipUrl($legacyUrl))->toBeNull()
        ->and(normalizeStoredClipUrl($legacyUrl))->toBe($legacyUrl)
        ->and(normalizeStoredClipUrl($tooLarge))->toBeNull();
});

it('returns malformed legacy destinations as inert stored text', function () {
    $relativeValue = '/legacy/relative-value';
    $malformedValue = 'http://[legacy-value';

    expect(resolveStoredClipDestination($relativeValue))->toBe($relativeValue)
        ->and(resolveStoredClipDestination($malformedValue))->toBe($malformedValue)
        ->and(isSafeNavigationUri($relativeValue))->toBeFalse()
        ->and(isSafeNavigationUri($malformedValue))->toBeFalse()
        ->and(resolveStoredClipDestination(''))->toBeNull();
});

it('accepts five-character alphanumeric clip codes only', function () {
    expect(isValidClipCode('aB123'))->toBeTrue()
        ->and(normalizeClipCode('aB123'))->toBe('ab123')
        ->and(isValidClipCode('abcd'))->toBeFalse()
        ->and(isValidClipCode('abcdef'))->toBeFalse()
        ->and(isValidClipCode('aB_23'))->toBeFalse()
        ->and(isValidClipCode('admin'))->toBeFalse()
        ->and(isValidClipCode('Tests'))->toBeFalse()
        ->and(isValidClipCode('contributors'))->toBeFalse();
});

it('generates fixed-length lowercase base36 clip codes', function () {
    $codes = [];

    for ($index = 0; $index < 32; $index++) {
        $code = generateClipCode();
        expect(strlen($code))->toBe(CLIP_CODE_LENGTH)
            ->and(isValidClipCode($code))->toBeTrue()
            ->and(preg_match('/\A[a-z0-9]{5}\z/D', $code))->toBe(1);
        $codes[] = $code;
    }

    expect(count(array_unique($codes)))->toBe(count($codes));
});

it('trusts forwarded HTTPS only from a configured proxy', function () {
    $previousEnvironment = $_ENV['TRUSTED_PROXIES'] ?? null;
    $hadEnvironment = array_key_exists('TRUSTED_PROXIES', $_ENV);
    $previousServer = $_SERVER;

    try {
        $_ENV['TRUSTED_PROXIES'] = '10.0.0.0/8';
        $_SERVER['HTTPS'] = 'off';
        $_SERVER['HTTP_X_FORWARDED_PROTO'] = 'https';
        $_SERVER['REMOTE_ADDR'] = '10.0.0.2';
        expect(requestUsesHttps())->toBeTrue();

        $_SERVER['REMOTE_ADDR'] = '203.0.113.10';
        expect(requestUsesHttps())->toBeFalse();

        $_SERVER['REMOTE_ADDR'] = '10.0.0.2';
        $_SERVER['HTTP_X_FORWARDED_PROTO'] = 'https, http';
        expect(requestUsesHttps())->toBeFalse();

        $_SERVER['HTTPS'] = 'on';
        expect(requestUsesHttps())->toBeTrue();
    } finally {
        $_SERVER = $previousServer;
        if ($hadEnvironment) {
            $_ENV['TRUSTED_PROXIES'] = $previousEnvironment;
        } else {
            unset($_ENV['TRUSTED_PROXIES']);
        }
    }
});

it('escapes values for HTML output contexts', function () {
    expect(escapeHtml('<a title="x">&'))->toBe('&lt;a title=&quot;x&quot;&gt;&amp;')
        ->and(escapeHtml(['not', 'scalar']))->toBe('');
});

it('uses REMOTE_ADDR when the peer is not a configured proxy', function () {
    $previousEnvironment = $_ENV['TRUSTED_PROXIES'] ?? null;
    $hadEnvironment = array_key_exists('TRUSTED_PROXIES', $_ENV);
    $previousServer = $_SERVER;

    try {
        $_ENV['TRUSTED_PROXIES'] = '';
        $_SERVER['REMOTE_ADDR'] = '203.0.113.10';
        $_SERVER['HTTP_X_FORWARDED_FOR'] = '198.51.100.50';

        expect(clientIp())->toBe('203.0.113.10');
    } finally {
        $_SERVER = $previousServer;
        if ($hadEnvironment) {
            $_ENV['TRUSTED_PROXIES'] = $previousEnvironment;
        } else {
            unset($_ENV['TRUSTED_PROXIES']);
        }
    }
});

it('selects the nearest untrusted address behind configured proxies', function () {
    $previousEnvironment = $_ENV['TRUSTED_PROXIES'] ?? null;
    $hadEnvironment = array_key_exists('TRUSTED_PROXIES', $_ENV);
    $previousServer = $_SERVER;

    try {
        $_ENV['TRUSTED_PROXIES'] = '10.0.0.0/8, 2001:db8::/32';
        $_SERVER['REMOTE_ADDR'] = '10.0.0.2';
        $_SERVER['HTTP_X_FORWARDED_FOR'] = '198.51.100.50, 10.0.0.1';

        expect(clientIp())->toBe('198.51.100.50');
    } finally {
        $_SERVER = $previousServer;
        if ($hadEnvironment) {
            $_ENV['TRUSTED_PROXIES'] = $previousEnvironment;
        } else {
            unset($_ENV['TRUSTED_PROXIES']);
        }
    }
});

it('falls back to REMOTE_ADDR for a malformed forwarded chain', function () {
    $previousEnvironment = $_ENV['TRUSTED_PROXIES'] ?? null;
    $hadEnvironment = array_key_exists('TRUSTED_PROXIES', $_ENV);
    $previousServer = $_SERVER;

    try {
        $_ENV['TRUSTED_PROXIES'] = '10.0.0.0/8';
        $_SERVER['REMOTE_ADDR'] = '10.0.0.2';
        $_SERVER['HTTP_X_FORWARDED_FOR'] = 'not-an-ip, 198.51.100.50';

        expect(clientIp())->toBe('10.0.0.2');
    } finally {
        $_SERVER = $previousServer;
        if ($hadEnvironment) {
            $_ENV['TRUSTED_PROXIES'] = $previousEnvironment;
        } else {
            unset($_ENV['TRUSTED_PROXIES']);
        }
    }
});

it('rejects invalid and globally trusted proxy ranges', function () {
    expect(isValidTrustedProxyRange('10.0.0.0/8'))->toBeTrue()
        ->and(isValidTrustedProxyRange('2001:db8::/32'))->toBeTrue()
        ->and(isValidTrustedProxyRange('203.0.113.10'))->toBeTrue()
        ->and(isValidTrustedProxyRange('0.0.0.0/0'))->toBeFalse()
        ->and(isValidTrustedProxyRange('::/0'))->toBeFalse()
        ->and(isValidTrustedProxyRange('not-an-address'))->toBeFalse();
});

it('preserves exact UTC database expiration microseconds', function () {
    $expected = dateTimeUnixMicroseconds(
        new DateTimeImmutable('2026-07-11 18:30:00.123456 UTC')
    );

    expect(clipExpiryMicroseconds('2026-07-11 18:30:00.123456'))->toBe($expected)
        ->and(clipExpiryMicroseconds('2026-02-30 18:30:00.000000'))->toBeNull();
});

it('expires new clips exactly 48 hours after creation', function () {
    $createdAt = new DateTimeImmutable('2026-07-11 18:30:00.123456 Europe/Rome');
    $expiresAt = clipExpirationDateTime($createdAt);

    expect(CLIP_TTL_SECONDS)->toBe(172800)
        ->and($expiresAt->getTimestamp() - $createdAt->getTimestamp())->toBe(CLIP_TTL_SECONDS)
        ->and($expiresAt->getTimezone()->getName())->toBe('UTC')
        ->and($expiresAt->format('Y-m-d H:i:s.u'))->toBe('2026-07-13 16:30:00.123456');
});

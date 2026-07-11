<?php

require_once dirname(__DIR__, 2) . '/includes/components/new.php';

it('rejects a malformed URI before opening the database', function () {
    $previousAvailability = $GLOBALS['redisAvailable'];

    try {
        $GLOBALS['redisAvailable'] = false;

        expect(createClip('/relative/path'))->toBe([null, 'invalid URL specified'])
            ->and(createClip(['https://example.com']))->toBe([null, 'invalid URL specified']);
    } finally {
        $GLOBALS['redisAvailable'] = $previousAvailability;
    }
});

it('does not treat arbitrary cache keys as clip codes', function () {
    $previousAvailability = $GLOBALS['redisAvailable'];

    try {
        $GLOBALS['redisAvailable'] = false;
        $user_code = 'contributors';
        $url = 'https://should-be-unset.example';

        include dirname(__DIR__, 2) . '/includes/components/get.php';

        expect(isset($url))->toBeFalse();
    } finally {
        $GLOBALS['redisAvailable'] = $previousAvailability;
    }
});

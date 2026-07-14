<?php

require_once dirname(__DIR__, 2) . '/includes/components/new.php';
require_once dirname(__DIR__, 2) . '/includes/components/get.php';

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

        expect(lookupClipUrl('contributors'))->toBeNull();
    } finally {
        $GLOBALS['redisAvailable'] = $previousAvailability;
    }
});

it('derives bounded distinct database locks from normalized URIs', function () {
    $firstLock = clipUriLockName('https://example.com/path');
    $secondLock = clipUriLockName('https://example.com/Path');

    expect($firstLock)->toStartWith('interclip:clip:')
        ->and(strlen($firstLock))->toBeLessThanOrEqual(64)
        ->and($secondLock)->not()->toBe($firstLock);
});

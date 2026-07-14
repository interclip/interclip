<?php

include_once __DIR__ . '/redis.php';
include_once __DIR__ . '/rate.php';
include_once __DIR__ . '/../lib/database.php';
include_once __DIR__ . '/../lib/security.php';

/**
 * Resolve an unexpired clip destination from Redis or MySQL.
 */
function lookupClipUrl(string $code): ?string
{
    $lookupCode = normalizeClipCode($code);
    if ($lookupCode === null) {
        return null;
    }

    // Five-character public codes are enumerable, so reject excessive guesses
    // before checking either the clip cache or database.
    enforceClipLookupRateLimit();

    $cachedUrl = getClipRedis($lookupCode);
    if ($cachedUrl !== null) {
        return $cachedUrl;
    }

    $clipConnection = null;

    try {
        $clipConnection = openDatabaseConnection();
        $statement = $clipConnection->prepare(
            'SELECT url, expires_at FROM userurl'
            . ' WHERE usr = ?'
            . ' AND (expires_at IS NULL OR expires_at > UTC_TIMESTAMP(6))'
            . ' ORDER BY id ASC LIMIT 1'
        );
        $statement->bind_param('s', $lookupCode);
        $statement->execute();
        $result = $statement->get_result();
        $row = $result->fetch_assoc();
        $statement->close();

        if (
            !is_array($row)
            || !isset($row['url'])
            || !array_key_exists('expires_at', $row)
            || !is_string($row['url'])
            || (!is_string($row['expires_at']) && $row['expires_at'] !== null)
        ) {
            return null;
        }

        $normalizedUrl = normalizeStoredClipUrl($row['url']);
        $expiresAt = is_string($row['expires_at'])
            ? clipExpiryMicroseconds($row['expires_at'])
            : null;
        if (
            $normalizedUrl === null
            || (is_string($row['expires_at']) && $expiresAt === null)
        ) {
            return null;
        }

        if ($expiresAt !== null) {
            storeClipRedis($lookupCode, $normalizedUrl, $expiresAt);
        }

        return $normalizedUrl;
    } catch (Throwable $exception) {
        error_log('Clip lookup failed: ' . $exception->getMessage());

        return null;
    } finally {
        if ($clipConnection instanceof mysqli) {
            $clipConnection->close();
        }
    }
}

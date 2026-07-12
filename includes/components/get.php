<?php

include_once __DIR__ . '/redis.php';
include_once __DIR__ . '/rate.php';
include_once __DIR__ . '/../lib/security.php';

unset($url);

// Five-character public codes are enumerable, so reject excessive guesses
// before checking either the clip cache or database.
enforceClipLookupRateLimit();

if (isset($user_code) && is_string($user_code) && ($lookupCode = normalizeClipCode($user_code)) !== null) {
    $cachedUrl = getClipRedis($lookupCode);
    if ($cachedUrl !== null) {
        $url = $cachedUrl;
    } else {
        $clipConnection = null;

        try {
            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
            $clipConnection = new mysqli(
                $_ENV['DB_SERVER'],
                $_ENV['USERNAME'],
                $_ENV['PASSWORD'],
                $_ENV['DB_NAME']
            );

            if ($clipConnection->connect_errno !== 0) {
                throw new RuntimeException('Clip database connection failed');
            }

            $clipConnection->set_charset('utf8mb4');
            $clipConnection->query("SET time_zone = '+00:00'");
            $statement = $clipConnection->prepare(
                'SELECT url, expires_at FROM userurl'
                . ' WHERE usr = ? AND expires_at > UTC_TIMESTAMP(6)'
                . ' ORDER BY id ASC LIMIT 1'
            );
            $statement->bind_param('s', $lookupCode);
            $statement->execute();
            $result = $statement->get_result();
            $row = $result->fetch_assoc();
            $statement->close();

            if (
                is_array($row)
                && isset($row['url'], $row['expires_at'])
                && is_string($row['url'])
                && is_string($row['expires_at'])
            ) {
                $normalizedUrl = normalizeClipUrl($row['url']);
                $expiresAt = clipExpiryMicroseconds($row['expires_at']);

                if ($normalizedUrl !== null && $expiresAt !== null) {
                    $url = $normalizedUrl;
                    storeClipRedis($lookupCode, $normalizedUrl, $expiresAt);
                }
            }
        } catch (Throwable $exception) {
            error_log('Clip lookup failed: ' . $exception->getMessage());
        } finally {
            if ($clipConnection instanceof mysqli) {
                $clipConnection->close();
            }
        }
    }
}

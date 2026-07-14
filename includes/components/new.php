<?php

include_once __DIR__ . '/rate.php';
include_once __DIR__ . '/redis.php';
include_once __DIR__ . '/../lib/database.php';
include_once __DIR__ . '/../lib/security.php';

const CLIP_INSERT_RETRIES = 10;
const CLIP_URI_LOCK_TIMEOUT_SECONDS = 5;
const CLIP_CREATE_ERROR = 'Unable to save clip. Please try again later.';

/**
 * Return a bounded MySQL advisory-lock name for one normalized URI.
 *
 * @param string $normalizedUrl Canonical absolute URI.
 * @return string
 */
function clipUriLockName(string $normalizedUrl): string
{
    $digest = base64_encode(hash('sha256', $normalizedUrl, true));
    $digest = rtrim(strtr($digest, '+/', '-_'), '=');

    return 'interclip:clip:' . $digest;
}

/**
 * Acquire the database-wide lock that serializes creation for one URI.
 *
 * @param mysqli $connection Open application database connection.
 * @param string $normalizedUrl Canonical absolute URI.
 * @return string Acquired advisory-lock name.
 */
function acquireClipUriLock(mysqli $connection, string $normalizedUrl): string
{
    $lockName = clipUriLockName($normalizedUrl);
    $statement = $connection->prepare('SELECT GET_LOCK(?, ?) AS acquired');
    try {
        $timeout = CLIP_URI_LOCK_TIMEOUT_SECONDS;
        $statement->bind_param('si', $lockName, $timeout);
        $statement->execute();
        $row = $statement->get_result()->fetch_assoc();
        if ((int) ($row['acquired'] ?? 0) !== 1) {
            throw new RuntimeException('Timed out waiting for the clip URI lock');
        }
    } finally {
        $statement->close();
    }

    return $lockName;
}

/**
 * Release a previously acquired URI advisory lock.
 *
 * @param mysqli $connection Open application database connection.
 * @param string $lockName Acquired advisory-lock name.
 * @return void
 */
function releaseClipUriLock(mysqli $connection, string $lockName): void
{
    $statement = $connection->prepare('SELECT RELEASE_LOCK(?)');
    try {
        $statement->bind_param('s', $lockName);
        $statement->execute();
    } finally {
        $statement->close();
    }
}

/**
 * Find the first unexpired code whose URI exactly matches the input.
 *
 * @param mysqli $connection Open application database connection.
 * @param string $normalizedUrl Canonical absolute URI.
 * @return array{0: string, 1: int|null}|null
 */
function findActiveClipForUrl(mysqli $connection, string $normalizedUrl): ?array
{
    $statement = $connection->prepare(
        'SELECT usr, expires_at FROM userurl '
        . 'WHERE BINARY url = BINARY ? '
        . 'AND (expires_at IS NULL OR expires_at > UTC_TIMESTAMP(6)) '
        . 'ORDER BY id ASC LIMIT 1'
    );
    try {
        $statement->bind_param('s', $normalizedUrl);
        $statement->execute();
        $row = $statement->get_result()->fetch_assoc();
    } finally {
        $statement->close();
    }

    if (!is_array($row)) {
        return null;
    }

    $code = isset($row['usr']) && is_string($row['usr'])
        ? normalizeClipCode($row['usr'])
        : null;
    if (!array_key_exists('expires_at', $row)) {
        return null;
    }

    $expiresAt = is_string($row['expires_at'])
        ? clipExpiryMicroseconds($row['expires_at'])
        : null;

    if ($row['expires_at'] !== null && $expiresAt === null) {
        return null;
    }

    return $code !== null ? [$code, $expiresAt] : null;
}

/**
 * Populate the optional Redis clip cache without failing the database write.
 *
 * @param string $code Five-character clip code.
 * @param string $normalizedUrl Canonical absolute URI.
 * @param int|null $expiresAt Exact expiration instant in Unix microseconds.
 * @return void
 */
function cacheClipDestination(string $code, string $normalizedUrl, ?int $expiresAt): void
{
    if ($expiresAt === null) {
        return;
    }

    try {
        storeClipRedis($code, $normalizedUrl, $expiresAt);
    } catch (Throwable $exception) {
        error_log('Clip cache population failed: ' . $exception->getMessage());
    }
}

/**
 * Reuse an active clip for the same normalized URI or create a fresh code.
 *
 * @return array{0: string|null, 1: string}
 */
function createClip($url): array
{
    noteLimit();

    if (!is_string($url)) {
        return [null, 'invalid URL specified'];
    }

    $normalizedUrl = normalizeClipUrl($url);
    if ($normalizedUrl === null) {
        return [null, 'invalid URL specified'];
    }

    $connection = null;
    $lockName = null;

    try {
        $connection = openDatabaseConnection();
        $lockName = acquireClipUriLock($connection, $normalizedUrl);

        $existingClip = findActiveClipForUrl($connection, $normalizedUrl);
        if ($existingClip !== null) {
            [$code, $expiresAt] = $existingClip;
            cacheClipDestination($code, $normalizedUrl, $expiresAt);

            return [$code, ''];
        }

        $createdAt = new DateTimeImmutable('now', new DateTimeZone('UTC'));
        $createdForDatabase = $createdAt->format('Y-m-d H:i:s.u');
        $expiration = clipExpirationDateTime($createdAt);
        $expirationForDatabase = $expiration->format('Y-m-d H:i:s.u');
        $expiresAt = dateTimeUnixMicroseconds($expiration);

        for ($attempt = 0; $attempt < CLIP_INSERT_RETRIES; $attempt++) {
            $code = generateClipCode();
            $statement = null;

            try {
                $statement = $connection->prepare(
                    'INSERT INTO userurl (usr, url, date, expires_at) '
                    . 'VALUES (?, ?, ?, ?)'
                );
                $statement->bind_param(
                    'ssss',
                    $code,
                    $normalizedUrl,
                    $createdForDatabase,
                    $expirationForDatabase
                );
                $statement->execute();

                cacheClipDestination($code, $normalizedUrl, $expiresAt);

                return [$code, ''];
            } catch (mysqli_sql_exception $exception) {
                if ((int) $exception->getCode() !== 1062) {
                    throw $exception;
                }
            } finally {
                if ($statement instanceof mysqli_stmt) {
                    $statement->close();
                }
            }
        }

        error_log('Clip code collision retry limit reached');

        return [null, CLIP_CREATE_ERROR];
    } catch (Throwable $exception) {
        error_log('Clip creation failed: ' . $exception->getMessage());

        return [null, CLIP_CREATE_ERROR];
    } finally {
        if ($connection instanceof mysqli) {
            if ($lockName !== null) {
                try {
                    releaseClipUriLock($connection, $lockName);
                } catch (Throwable $exception) {
                    error_log(
                        'Clip URI lock release failed: ' . $exception->getMessage()
                    );
                }
            }
            $connection->close();
        }
    }
}

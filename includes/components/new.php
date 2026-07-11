<?php

include_once __DIR__ . '/rate.php';
include_once __DIR__ . '/redis.php';
include_once __DIR__ . '/../lib/security.php';

const CLIP_INSERT_RETRIES = 10;
const CLIP_CREATE_ERROR = 'Unable to save clip. Please try again later.';

function openClipDatabase(): mysqli
{
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    $connection = new mysqli(
        $_ENV['DB_SERVER'],
        $_ENV['USERNAME'],
        $_ENV['PASSWORD'],
        $_ENV['DB_NAME']
    );

    if ($connection->connect_errno !== 0) {
        throw new RuntimeException('Clip database connection failed');
    }

    $connection->set_charset('utf8mb4');
    $connection->query("SET time_zone = '+00:00'");

    return $connection;
}

/**
 * Create a clip with a fresh capability code.
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

    try {
        $connection = openClipDatabase();
        $createdAt = new DateTimeImmutable('now', new DateTimeZone('UTC'));
        $createdForDatabase = $createdAt->format('Y-m-d H:i:s.u');
        $expiration = clipExpirationDateTime($createdAt);
        $expirationForDatabase = $expiration->format('Y-m-d H:i:s.u');
        $expiresAt = dateTimeUnixMicroseconds($expiration);

        for ($attempt = 0; $attempt < CLIP_INSERT_RETRIES; $attempt++) {
            $code = generateClipCode();
            $reservation = null;
            $statement = null;

            try {
                $connection->begin_transaction();
                $reservation = $connection->prepare(
                    'INSERT INTO issued_clip_codes (usr, issued_at) VALUES (?, UTC_TIMESTAMP(6))'
                );
                $reservation->bind_param('s', $code);
                $reservation->execute();

                $connection->query(
                    "UPDATE clip_metrics SET metric_value = metric_value + 1 "
                    . "WHERE metric_name = 'total_issued'"
                );
                if ($connection->affected_rows !== 1) {
                    throw new RuntimeException('Clip issuance counter is not initialized');
                }

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
                $connection->commit();

                try {
                    storeClipRedis($code, $normalizedUrl, $expiresAt);
                } catch (Throwable $exception) {
                    error_log('Clip cache population failed: ' . $exception->getMessage());
                }

                return [$code, ''];
            } catch (mysqli_sql_exception $exception) {
                try {
                    $connection->rollback();
                } catch (Throwable) {
                    // Preserve the exception that triggered the rollback.
                }

                if ((int) $exception->getCode() !== 1062) {
                    throw $exception;
                }
            } catch (Throwable $exception) {
                try {
                    $connection->rollback();
                } catch (Throwable) {
                    // Preserve the exception that triggered the rollback.
                }
                throw $exception;
            } finally {
                if ($reservation instanceof mysqli_stmt) {
                    $reservation->close();
                }
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
            $connection->close();
        }
    }
}

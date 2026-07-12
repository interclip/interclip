<?php

/**
 * Open an application database connection with consistent strict settings.
 */
function openDatabaseConnection(int $connectTimeoutSeconds = 5): mysqli
{
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    $connection = mysqli_init();
    if (!$connection instanceof mysqli) {
        throw new RuntimeException('Unable to initialize database connection');
    }

    $connection->options(MYSQLI_OPT_CONNECT_TIMEOUT, max(1, $connectTimeoutSeconds));
    $connection->real_connect(
        $_ENV['DB_SERVER'] ?? '',
        $_ENV['USERNAME'] ?? '',
        $_ENV['PASSWORD'] ?? '',
        $_ENV['DB_NAME'] ?? ''
    );

    if ($connection->connect_errno !== 0) {
        throw new RuntimeException('Database connection failed');
    }

    $connection->set_charset('utf8mb4');
    $connection->query("SET time_zone = '+00:00'");

    return $connection;
}

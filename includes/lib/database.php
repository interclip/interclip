<?php

/**
 * Open an application database connection with consistent strict settings.
 */
function openDatabaseConnection(): mysqli
{
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    $connection = new mysqli(
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

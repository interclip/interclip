<?php

it('enforces unique case-insensitive clip codes in the schema', function () {
    $schema = file_get_contents(dirname(__DIR__, 2) . '/scripts/db.sql');

    expect($schema)->toContain('`usr` char(5) CHARACTER SET ascii COLLATE ascii_general_ci NOT NULL')
        ->and($schema)->toContain('UNIQUE KEY `userurl_usr_unique` (`usr`)')
        ->and($schema)->toContain('`expires_at` datetime(6) NOT NULL')
        ->and($schema)->toContain('CHECK (`usr` REGEXP \'^[A-Za-z0-9]{5}$\'')
        ->and($schema)->toContain("LOWER(`usr`) NOT IN ('admin', 'about', 'login', 'tests')");
});

it('reserves issued short codes after active destinations expire', function () {
    $schema = file_get_contents(dirname(__DIR__, 2) . '/scripts/db.sql');

    expect($schema)->toContain('CREATE TABLE `issued_clip_codes`')
        ->and($schema)->toContain('PRIMARY KEY (`usr`)')
        ->and($schema)->toContain('FOREIGN KEY (`usr`) REFERENCES `issued_clip_codes` (`usr`)')
        ->and($schema)->toContain('DELETE FROM userurl WHERE expires_at <= UTC_TIMESTAMP(6)');
});

it('maintains a constant-time durable clip issuance metric', function () {
    $schema = file_get_contents(dirname(__DIR__, 2) . '/scripts/db.sql');
    $migration = file_get_contents(
        dirname(__DIR__, 2) . '/scripts/migrations/2026-07-11-security-hardening.sql'
    );

    expect($schema)->toContain('CREATE TABLE `clip_metrics`')
        ->and($schema)->toContain("VALUES ('total_issued', 0)")
        ->and($migration)->toContain("SELECT 'total_issued', COUNT(*) FROM `issued_clip_codes`");
});

it('does not seed a network-reachable mock staff identity', function () {
    $schema = file_get_contents(dirname(__DIR__, 2) . '/scripts/db.sql');

    expect($schema)->not()->toContain('admin@example.org');
});

it('keys account authorization to a unique immutable identity subject', function () {
    $schema = file_get_contents(dirname(__DIR__, 2) . '/scripts/db.sql');

    expect($schema)->toContain('`subject` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL')
        ->and($schema)->toContain('UNIQUE KEY `accounts_subject_unique` (`subject`)');
});

it('ships a preflighted migration for existing databases', function () {
    $migration = file_get_contents(
        dirname(__DIR__, 2) . '/scripts/migrations/2026-07-11-security-hardening.sql'
    );

    expect($migration)->toContain('ALTER TABLE `userurl`')
        ->and($migration)->toContain('ADD UNIQUE KEY `userurl_usr_unique`')
        ->and($migration)->toContain('GROUP BY LOWER(`usr`)')
        ->and($migration)->toContain('UPDATE `userurl` SET `usr` = LOWER(`usr`)')
        ->and($migration)->toContain('COLLATE ascii_general_ci')
        ->and($migration)->toContain('Invalid clip codes must be resolved before migration')
        ->and($migration)->toContain('CREATE TABLE `issued_clip_codes`')
        ->and($migration)->toContain('UTC_TIMESTAMP(6) + INTERVAL 48 HOUR')
        ->and($migration)->toContain('DROP COLUMN `expires`')
        ->and($migration)->toContain('ALTER TABLE `accounts`')
        ->and($migration)->toContain('ADD COLUMN `subject`')
        ->and($migration)->not()->toContain('DELETE FROM `accounts`');
});

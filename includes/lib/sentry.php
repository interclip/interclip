<?php

exec('git rev-parse --verify HEAD', $sentryOutput);
$sentryHash = $sentryOutput[0];
$sentryRelease = substr($sentryHash, 0, 7);

/* Sentry */
if (!empty($_ENV['SENTRY_URL'])) {
    \Sentry\init([
        'dsn' => $_ENV['SENTRY_URL'],
        'release' => $sentryRelease,
        'traces_sample_rate' => floatval($_ENV['TRACES_SAMPLE_RATE'])
    ]);
}

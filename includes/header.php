<?php

/* Important variables */
include_once "lib/init.php";

/* Sentry */
include_once "lib/sentry.php";

/* Headers */
include_once "lib/headers.php";

/* Authentication */
include_once "lib/auth.php";

/* Meta tags */
include_once "components/html/meta-tags.php";

if ($_ENV['ENVIRONMENT'] === "production") {
    include_once 'components/html/analytics.php';
}

//By default, we assume that PHP is NOT running on windows.
$isWindows = false;

//If the first three characters PHP_OS are equal to "WIN",
//then PHP is running on a Windows operating system.
if (strcasecmp(substr(PHP_OS, 0, 3), 'WIN') === 0) {
    $isWindows = true;
}

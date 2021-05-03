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

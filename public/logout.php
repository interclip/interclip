<?php
require_once "includes/lib/init.php";
require_once "includes/lib/auth.php";
require_once "includes/anti-csrf.php";
require_once "includes/lib/headers.php";

header('X-Robots-Tag: noindex, nofollow, noarchive');

validate();

// End this application's session, preserving the shared SSO session.
destroySecureSession();
header('Clear-Site-Data: "storage"');
header('Location: ' . (ROOT . '/'), true, 302);
exit;

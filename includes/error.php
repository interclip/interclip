<?php

include_once __DIR__ . '/lib/init.php';
include_once __DIR__ . '/lib/sentry.php';
include_once __DIR__ . '/lib/headers.php';
include_once __DIR__ . '/lib/auth.php';
include_once __DIR__ . '/anti-csrf.php';
startSecureSession();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php

    include_once "header.php";
    include_once "lib/functions.php";

    // Determine status code
    $status = isset($statusCode) ? $statusCode : (isset($_SERVER['REDIRECT_STATUS']) ? $_SERVER['REDIRECT_STATUS'] : 404);

    $codes = array(
        400 => array('400 Bad Request', 'The request cannot be fulfilled due to bad syntax.'),
        401 => array('401 Login Error', 'It appears that the password and/or user-name you entered was incorrect.'),
        403 => array('403 Forbidden', 'Sorry, top secret stuff.'),
        404 => array('404 Missing', 'We\'re sorry, but the page you\'re looking for is missing, hiding, or maybe it moved somewhere else and forgot to tell you.'),
        405 => array('405 Method Not Allowed', 'The method specified in the Request-Line is not allowed for the specified resource.'),
        408 => array('408 Request Timeout', 'Hi, I\'m the server. I don\'t have all day for your request. Sry'),
        414 => array('414 URL Too Long', 'The URL you entered is longer than the maximum length.'),
        500 => array('500 Internal Server Error', 'The request was unsuccessful due to an unexpected condition encountered by the server.'),
        502 => array('502 Bad Gateway', 'The server received an invalid response from the upstream server while trying to fulfill the request.'),
        504 => array('504 Gateway Timeout', 'The upstream server failed to send a request in the time allowed by the server.'),
    );

    $errorTitle = isset($codes[$status][0]) ? $codes[$status][0] : 'Unknown Error';
    $message = isset($codes[$status][1]) ? $codes[$status][1] : 'An unknown error has occurred.';

    ?>

    <title><?php echo escapeHtml($errorTitle); ?></title>
    <link rel="stylesheet" href="<?php echo ROOT ?>/css/index.css">
    <link rel="stylesheet" href="<?php echo ROOT ?>/css/dark.css" media="(prefers-color-scheme: dark)">
</head>

<body style="text-align: center;">
    <?php include "menu.php"; ?>
    <main>
        <?php
        echo ('<h1 style="font-size: 5rem;margin-top: 30vh;">' . escapeHtml($errorTitle) . '</h1>');
        echo ('<p style="font-size: 2rem;">' . $message . '</p>');
        ?>
    </main>
</body>

</html>

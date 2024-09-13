<!DOCTYPE html>
<html lang="en">

<head>
    <?php

    include_once "header.php";
    include_once "lib/functions.php";

    $clipRegex = "/^[a-zA-Z0-9]{5}$/";

    // Check if requested string was a code

    $raw_user_code = substr(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), 1);
    $user_code = htmlspecialchars($raw_user_code, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    if (strlen($user_code) == 5 && preg_match($clipRegex, $user_code)) {
        include_once "components/get.php";

        if (isset($url) && function_exists('redirectTo')) {
            reDir($url);
        }
    }

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

    <title><?php echo ("$errortitle"); ?></title>
    <link rel="stylesheet" href="<?php echo ROOT ?>/css/index.css">
    <link rel="stylesheet" href="<?php echo ROOT ?>/css/dark.css" media="(prefers-color-scheme: dark)">
</head>

<body style="text-align: center;">
    <?php include "menu.php"; ?>
    <main>
        <?php
        echo ('<h1 style="font-size: 5rem;margin-top: 30vh;">' . $errortitle . '</h1>');
        echo ('<p style="font-size: 2rem;">' . $message . '</p>');
        ?>
    </main>
</body>

</html>
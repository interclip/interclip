<!doctype html>
<html>
    <head>
        <title><?php echo ("$errortitle"); ?></title>
        <meta charset="utf-8">
        <link rel="stylesheet" href="../css/index.css">
        <link rel="stylesheet" href="../css/dark.css" media="(prefers-color-scheme: dark)">
        <link rel="stylesheet" href="./css/menu.css">
    </head>

    <?php
        include("menu.php");
        $status = $_SERVER['REDIRECT_STATUS'];
        $codes = array(
            400 => array('400 Bad Request', 'The request cannot be fulfilled due to bad syntax.'),
            401 => array('401 Login Error', 'It appears that the password and/or user-name you entered was incorrect.'),
            403 => array('403 Forbidden', 'Sorry, top secret stuff.'),
            404 => array('404 Missing', 'We\'re sorry, but the page you\'re looking for is missing, hiding, or maybe it moved somewhere else and forgot to tell you.'),
            405 => array('405 Method Not Allowed', 'The method specified in the Request-Line is not allowed for the specified resource.'),
            408 => array('408 Request Timeout', 'Hi, I\'m the server. I don\'t have all day for your request. Sry'),
            414 => array('414 URL To Long', 'The URL you entered is longer than the maximum length.'),
            500 => array('500 Internal Server Error', 'The request was unsuccessful due to an unexpected condition encountered by the server.'),
            502 => array('502 Bad Gateway', 'The server received an invalid response from the upstream server while trying to fulfill the request.'),
            504 => array('504 Gateway Timeout', 'The upstream server failed to send a request in the time allowed by the server.'),
        );

        $errortitle = $codes[$status][0];
        $message = $codes[$status][1];

        if ($errortitle == false) {
            $errortitle = "Unknown Error";
            $message = "An unknown error has occurred.";
        }

    ?>

    <body style="text-align: center;">
        <?php
            function reDir($url)
            {
                header("Location: " . $url . "");
                die();
            }
            echo ('<h1 style="font-size: 5rem;margin-top: 30vh;">' . $errortitle . '</h1>');
            echo ('<p style="font-size: 2rem;">' . $message . '</p>');

            $clipRegex = "/([a-z|0-9|A-Z]){5}/g";

            /* Check if requested string wasn't a code */
            if (preg_match($clipRegex, basename($_SERVER['REQUEST_URI']))) {
                $user_code = basename($_SERVER['REQUEST_URI']);
                include_once "components/get.php";
                
                if (isset($url)) {
                    reDir($url);
                }
            }
        ?>
    </body>
</html>
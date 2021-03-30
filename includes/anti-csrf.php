<?php

function store()
{
    session_start();
    $_SESSION['token'] = bin2hex(random_bytes(32));

    $_SESSION['token-expire'] = time() + 7200; // 2 hours

}

function validate()
{
    // Start the session
    session_start();
    if (isset($_POST['token'])) {
        if ($_SESSION['token'] === $_POST['token']) {
            if (time() >= $_SESSION['token-expire']) {
                exit("Token expired. Please reload go back and repeat the action.");
            } else {
                unset($_SESSION['token']);
                unset($_SESSION['token-expire']);
            }
        } else {
            if (isset($_SESSION['token'])) {
                exit("Honestly, no idea what went wrong, but it's not my fault, I promise.");
            }
            http_response_code(400);
            exit("INVALID ANTI-CSRF TOKEN, sending you back. <script>location.href='../';</script>");
        }
    } else {
        http_response_code(400);
        exit("All went well... just kidding, something went wrong, and it looks like it's something on your end. Either you're here since like 1995 or you're trying to steal our requests, either way, don't do that!");  
    }
}

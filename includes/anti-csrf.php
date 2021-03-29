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

    if ($_SESSION['token'] === $_POST['token']) {
        if (time() >= $_SESSION['token-expire']) {
            exit("Token expired. Please reload go back and repeat the action.");
        } else {
            unset($_SESSION['token']);
            unset($_SESSION['token-expire']);
        }
    } else {
        exit("INVALID ANTI-CSRF TOKEN, sending you back. <script>location.href='../';</script>");
    }
}

<?php

use Auth0\SDK\Auth0;

if ($_ENV['AUTH_TYPE'] === "account") {
    if (!empty($_SERVER['HTTP_HOST'])) {
        $redirURI = $_ENV['PROTOCOL'] . "://" . $_SERVER['HTTP_HOST'] . ROOT . "/login";
        $auth0 = new Auth0([
            'domain'        => $_ENV['AUTH0_DOMAIN'],
            'client_id'     => $_ENV['AUTH0_CLIENT_ID'],
            'client_secret' => $_ENV['AUTH0_CLIENT_SECRET'],
            'redirect_uri' => $redirURI,
        ]);
    } else {
        die("
            What'r you tryna do?
            Your HTTP host is empty, I have no idea why you're here.
            Are you a robot? I'm afraid of robots, don't scare me.
            If you aren't, maybe I messed something up. 
            In that case, it should like to report the error back to me so there's nothing you have to do in particular. 
            Have a nice day. Hope to see you soon, or later. 
            IDK, existence is just such an amazing concept, isn't it? 
            You can have a family, get a dog and be happy. But you're here, reading a server error message. Wow.
            ");
    }
} elseif ($_ENV['AUTH_TYPE'] === "mock") {
    $user = ["nickname" => "Admin", "email" => "admin@example.org"];
}

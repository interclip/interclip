<?php

include_once "includes/lib/init.php";

use Pecee\SimpleRouter\SimpleRouter;

SimpleRouter::get('/', function() {
    $content = include_once "public/index.php";
    return $content;
});

SimpleRouter::get('/file', function() {
    $content = include_once "public/file.php";
    return $content;
});

SimpleRouter::get('/receive', function() {
    $content = include_once "public/receive.php";
    return $content;
});

SimpleRouter::get('/privacy', function() {
    $content = include_once "public/privacy.php";
    return $content;
});

SimpleRouter::get('/about', function() {
    $content = include_once "public/about.php";
    return $content;
});

// Start the routing
SimpleRouter::start();
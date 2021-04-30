<?php

include_once "includes/lib/init.php";

use Pecee\SimpleRouter\SimpleRouter;

/* Dynamic pages */

SimpleRouter::get('/index', function() {
    $content = include_once "public/index.php";
    return $content;
});

SimpleRouter::get('/receive/', function() {
    $content = include_once "public/receive.php";
    return $content;
});

SimpleRouter::get('/file/', function() {
    $content = include_once "public/file.php";
    return $content;
});

SimpleRouter::get('/admin/', function() {
    $content = include_once "public/admin.php";
    return $content;
});

/* Clip manipulation */

SimpleRouter::form('/get', function() {
    $content = include_once "public/core/get.php";
    return $content;
});

SimpleRouter::form('/set', function() {
    $content = include_once "public/core/set.php";
    return $content;
});


/* Static pages */

SimpleRouter::get('/privacy/', function() {
    $content = include_once "public/privacy.php";
    return $content;
});

SimpleRouter::get('/about/', function() {
    $content = include_once "public/about.php";
    return $content;
});

/* Auth */
SimpleRouter::get('/login/', function() {
    $content = include_once "public/login.php";
    return $content;
});

SimpleRouter::get('/logout/', function() {
    $content = include_once "public/logout.php";
    return $content;
});

/* Auth */
SimpleRouter::form('/includes/api', function() {
    $content = include_once "public/api/set.php";
    return $content;
});

SimpleRouter::form('/includes/get-api', function() {
    $content = include_once "public/api/get.php";
    return $content;
});

// Start the routing
SimpleRouter::start();
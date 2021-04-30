<?php

include_once "includes/lib/init.php";

use Pecee\SimpleRouter\SimpleRouter;

SimpleRouter::get('/', function() {
    $content = include_once "public/index.php";
    return $content;
});

SimpleRouter::get('/about', function() {
    $content = include_once "public/about.php";
    return $content;
});

// Start the routing
SimpleRouter::start();
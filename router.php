<?php

include_once "includes/lib/init.php";

use Pecee\SimpleRouter\SimpleRouter;

/* Dynamic pages */

SimpleRouter::get('/', function() {
    include_once "public/index.php";
});

SimpleRouter::get('/receive/', function() {
    include_once "public/receive.php";
});

SimpleRouter::get('/file/', function() {
    include_once "public/file.php";
});

SimpleRouter::form('/upload/', function() {
    include_once "public/upload/index.php";
});

SimpleRouter::get('/admin/', function() {
    include_once "public/admin.php";
});

/* Clip manipulation */

SimpleRouter::form('/get', function() {
    include_once "public/core/get.php";
});

SimpleRouter::form('/set', function() {
    include_once "public/core/set.php";
});


/* Static pages */

SimpleRouter::get('/privacy/', function() {
    include_once "public/privacy.php";
});

SimpleRouter::get('/about/', function() {
    include_once "public/about.php";
});

/* Auth */
SimpleRouter::get('/login/', function() {
    include_once "public/login.php";
});

SimpleRouter::get('/logout/', function() {
    include_once "public/logout.php";
});

/* API */
SimpleRouter::match(['get', 'post'],'/includes/api', function() {
    include_once "public/api/set.php";
});

SimpleRouter::match(['get', 'post'],'/includes/get-api', function() {
    include_once "public/api/get.php";
});

// Start the routing
SimpleRouter::start();
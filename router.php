<?php

include_once "includes/lib/init.php";
include_once "includes/lib/sentry.php";

use Pecee\SimpleRouter\SimpleRouter;

/* Dynamic pages */

SimpleRouter::group(['prefix' => ROOT], function () {

    SimpleRouter::all(
        '/',
        function () {
            include_once "public/index.php";
            return "";
        }
    );

    SimpleRouter::get('/receive/', function () {
        include_once "public/receive.php";
        return "";
    });

    SimpleRouter::get('/file/', function () {
        include_once "public/file.php";
        return "";
    });

    SimpleRouter::form('/upload/', function () {
        include_once "public/upload/index.php";
        return "";
    });

    SimpleRouter::get('/admin/', function () {
        include_once "public/admin.php";
        return "";
    });

    /* Clip manipulation */

    SimpleRouter::form('/get', function () {
        include_once "public/core/get.php";
        return "";
    });

    SimpleRouter::form('/set', function () {
        include_once "public/core/set.php";
        return "";
    });

    /* Static pages */

    SimpleRouter::get('/desktop/', function () {
        include_once "public/desktop.php";
        return "";
    });

    SimpleRouter::get('/about/', function () {
        include_once "public/about.php";
        return "";
    });

    /* Auth */
    SimpleRouter::get('/login/', function () {
        include_once "public/login.php";
        return "";
    });

    SimpleRouter::get('/logout/', function () {
        include_once "public/logout.php";
        return "";
    });

    SimpleRouter::get('/privacy/', function () {
        include_once "public/privacy.php";
        return "";
    });

    /* API */
    SimpleRouter::match(['get', 'post'], '/api/set', function () {
        include_once "public/api/set.php";
        return "";
    });

    SimpleRouter::match(['get', 'post'], '/api/get', function () {
        include_once "public/api/get.php";
        return "";
    });

    SimpleRouter::match(['get', 'post'], '/api/file', function () {
        include_once "public/api/file.php";
        return "";
    });

    // Match clip codes
    SimpleRouter::all(
        '/code',
        function ($user_code) {
            include_once "components/get.php";
            if (isset($url)) {
                reDir($url);
            } else {
                $statusCode = 404;
                include_once "includes/error.php";
            }
        }
    )->setMatch('/^\/([a-z|0-9|A-Z]){5}\/?$/');

    /* Internal behavior */

    SimpleRouter::get('/staging/change-branch', function () {
        include_once "public/change-branch.php";
    });
});

use Pecee\Http\Request;

SimpleRouter::error(function (Request $request, \Exception $exception) {
    include_once "includes/lib/functions.php";

    $currURL = $request->getUrl();
    $unRootedURL = str_replace(ROOT, "", $currURL);
    $user_code = str_replace("/", "", $unRootedURL);

    include_once "includes/components/get.php";

    if (isset($url)) {
        header("Location: $url");
    } else {
        $statusCode = $exception->getCode();
        http_response_code($statusCode);
        include_once "includes/error.php";
    }
});
// Start the routing
SimpleRouter::start();

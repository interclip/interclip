<?php

include 'includes/lib/init.php';

it('asserts URL shortening works', function () {
    $url = 'https://interclip.app/';
    $mode = 'silent';
    include_once "./includes/components/short-api.php";
    $this->assertNotEmpty($content);
});

<?php

it('asserts DB servername is not empty', function () {
    include_once "./includes/db.php";
    $this->assertNotEmpty($servername);
});

it('asserts URL shortening works', function () {
    $url = 'https://interclip.app/';
    $mode = 'silent';
    include_once "./includes/components/short-api.php";
    $this->assertNotEmpty($content);
});

/* TODO: Errors out because it cannot find the db.php file for some reason
it('asserts the set api works', function () {
    $url = 'https://interclip.app/';
    include_once "./includes/components/new.php";
    $this->assertNotEmpty($usr);
});

it('asserts rate limiting works', function () {
    include_once "./includes/components/rate.php";
    $this->assertTrue(noteLimit("get"));
});
*/
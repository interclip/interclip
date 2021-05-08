<?php

include 'includes/lib/init.php';
include 'includes/lib/functions.php';

it('asserts URL shortening works', function () {
    $url = 'https://interclip.app/';
    $mode = 'silent';
    include_once "./includes/components/short-api.php";
    $this->assertNotEmpty($content);
});

it('should format bytes correctly', function () {
    $this->assertTrue(formatBytes(70656) === "69 KB");
});

it('should fetch git branches correctly', function () {
    $this->assertNotEmpty(getBranches());
});

it('should return the current OS', function () {
   $this->assertNotEmpty(getOS());
});

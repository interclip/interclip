<?php

include 'includes/lib/functions.php';

it('should format bytes correctly', function () {
    $this->assertTrue(formatBytes(70656) === "69 KB");
});

it('should fetch git branches correctly', function () {
    $this->assertNotEmpty(getBranches());
});

it('should return the current OS', function () {
   $this->assertNotEmpty(getOS());
});

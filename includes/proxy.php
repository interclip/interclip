<?php
$url = explode("/", $url);
$url = end($url);
$url = "https://iclip.netlify.com/s/" . $url;


reDir($url);
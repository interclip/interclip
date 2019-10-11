<?php
$url = explode("/", $url);
$url = end($url);
$url = "https://s.put.re/" . $url;


/*
header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="' . basename($url) . '"');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
flush(); // Flush system output buffer
readfile($url);
exit;
*/
reDir($url);
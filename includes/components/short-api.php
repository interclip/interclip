<?php

//The URL
if (!isset($url)) {
    $url = $_GET["url"];
}

//$keyword = $_GET["keyword"];

$action = "shorturl";
$format = "json";
 
//Use file_get_contents to GET the URL in question.
$contents = file_get_contents("https://u.nu/api.php?action=".$action."&format=".$format."&url=".$url);


$content = json_decode($contents);
header('Content-Type: application/json');

if($mode != "silent") {
    echo json_encode($content);
}
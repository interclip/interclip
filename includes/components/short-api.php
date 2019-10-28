<?php

//The URL
$url = $_GET["url"];

$keyword = $_GET["keyword"];

$action = "shorturl";

$format = "json";

//https://u.nu/api.php?action=shorturl&format=json&url=https://s.put.re/UssRP5X&keyword=UssRP5XU
 
//Use file_get_contents to GET the URL in question.
//$contents = file_get_contents("https://u.nu/api.php?action=".$action."&format=".$format."&url=".$url."&keyword=".$keyword);
$contents = file_get_contents("https://u.nu/api.php?action=".$action."&format=".$format."&url=".$url);

//If $contents is not a boolean FALSE value.
if($contents !== false){
    $content = json_decode($contents);
    header('Content-Type: application/json');
    echo json_encode($content);
}
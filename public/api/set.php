<?php

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

function writeDb($url)
{
  if (filter_var($url, FILTER_VALIDATE_URL)) {
    require "vendor/autoload.php";

    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__, '../.env');
    $dotenv->safeLoad();
    include_once "includes/components/new.php";
  }
  
  $createArray = createClip($url);
  $usr = $createArray[0];
  $err = $createArray[1];

  if (isset($url)) {
    if ($err === "") {
      if (isset($usr) && $usr != null) {
        echo json_encode(['status' => 'success', 'result' => $usr]);
      } else {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'result' => 'invalid URL specified']);
      }
    } else {
      http_response_code(400);
      echo json_encode(['status' => 'error', 'result' => $err]);
    }
  } else {
    http_response_code(404);
    echo json_encode(['status' => 'error', 'result' => 'no URL provided']);
    die();
  }
}

if (isset($_GET['url'])) {
  writeDb($_GET['url']);
} else if (isset($_POST['url'])) {
  writeDb($_POST['url']);
} else {
  http_response_code(404);
  echo json_encode(['status' => 'error', 'result' => 'no URL provided']);
  die();
}

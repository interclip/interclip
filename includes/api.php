<?php

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

function writeDb($url)
{
  if (isset($url)) {
    if (filter_var($url, FILTER_VALIDATE_URL)) {
      include "db.php";
      include_once "components/new.php";
    }
    echo json_encode(['status' => 'success', 'result' => $usr]);
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

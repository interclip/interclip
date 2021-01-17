<?php

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

if (!empty($_POST['code'])) {
  $user_code = $_POST['code'];
} elseif (!empty($_GET['code'])) {
  $user_code = $_GET['code'];
}

include_once "components/get.php";

if (isset($url)) {
  echo json_encode(['status' => 'success', 'result' => $url]);
} else {
  http_response_code(400);
  echo json_encode(['status' => 'error', 'result' => 'no user code provided']);
  die();
}

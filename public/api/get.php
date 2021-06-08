<?php

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

if (!empty($_POST['code'])) {
  $user_code = $_POST['code'];
} elseif (!empty($_GET['code'])) {
  $user_code = $_GET['code'];
} else {
  http_response_code(400);
  echo json_encode(['status' => 'error', 'result' => 'no user code provided']);
  die();
}

require "vendor/autoload.php";

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__, '../.env');
$dotenv->safeLoad();
include_once "includes/components/get.php";

if (isset($url)) {
  echo json_encode(['status' => 'success', 'result' => $url]);
} else {
  http_response_code(404);
  echo json_encode(['status' => 'error', 'result' => 'this clip does not exist']);
  die();
}

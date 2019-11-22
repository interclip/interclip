<?php
if (!empty($_POST['user'])) {
  $user_code = $_POST['user'];
} elseif (!empty($_GET['user'])) {
  $user_code = $_GET['user'];
}
include_once "components/get.php";
if (isset($url)) {
  echo $url;
} else {
  http_response_code(400);
  echo "Error: no code given";
  die();
}

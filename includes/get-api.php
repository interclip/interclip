<?php 
if (!empty($_POST['user'])) {
    $user_code = $_POST['user'];
    include_once "components/get.php";
  }
if(isset($url)) {
echo $url;
} else {
    http_response_code(400);
    echo "Error: no code given";
    die();
}
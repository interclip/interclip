<?php
function writeDb($url)
{
  if (isset($url)) {
    if (filter_var($url, FILTER_VALIDATE_URL)) {
      include "db.php";

      include_once "components/new.php";
    }
    echo $usr;
  } else {
    http_response_code(404);
    echo "Error: no URL given";
    die();
    // my else codes goes
  }
}
if (isset($_GET['url'])) {
  writeDb($_GET['url']);
} else if (isset($_POST['url'])) {
  writeDb($_POST['url']);
} else {
  http_response_code(404);
  echo "Error: no URL given";
  die();
}

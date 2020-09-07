<?php
function writeDb($url)
{
  if (isset($url)) {
    if (filter_var($url, FILTER_VALIDATE_URL)) {
      include "db.php";

      $url = str_replace("<", "&lt;", $url);
      $url = str_replace(">", "&gt;", $url);


      // Create connection
      $conn = new mysqli($servername, $username, $password, $DBName);

      // Check connection
      if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
      }
      function gen_uid($len = 10)
      {
        return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyz"), 0, $len);
      }
      $usr = gen_uid(5);
      $timestamp = date("Y-m-d H:i:s");

      $sqlquery = "SELECT * FROM userurl WHERE url = '$url'";
      $result = $conn->query($sqlquery);

      if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
          $usr = $row['usr'];
          break;
        }
        $conn->query($sqlquery);
      } else {
        $sqlquery = "INSERT INTO userurl (id, usr, url, date) VALUES (NULL, '$usr', '$url', '$timestamp') ";
        if ($conn->query($sqlquery) === FALSE) {
          echo "Error: " . $sqlquery . "<br>" . $conn->error;
        }
      }

      $conn->close();
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

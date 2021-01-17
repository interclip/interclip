<?php

include_once "./db.php";
include_once "./components/rate.php";

if (isset($user_code)) {

  noteLimit("get");

  // Create connection
  $conn = new mysqli($servername, $username, $password, $DBName);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }
  $sqlquery = "SELECT * FROM userurl WHERE usr = '$user_code'";
  $result = $conn->query($sqlquery);

  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      $url = $row['url'];
      break;
    }
    $conn->query($sqlquery);
  }

  $conn->close();
  //}
}
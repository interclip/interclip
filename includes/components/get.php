<?php

include_once "includes/components/rate.php";

if (isset($user_code)) {

  noteLimit("get");

  // Create connection
  $conn = new mysqli($_ENV['DB_SERVER'], $_ENV['USERNAME'], $_ENV['PASSWORD'], $_ENV['DB_NAME']);

  /* Check DB connection */
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  /* Prepare and execute SQL query to get clips */

  $sqlquery = "SELECT * FROM userurl WHERE usr = '$user_code'";
  $result = $conn->query($sqlquery);

  /* Get the clip from DB */

  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      $url = $row['url'];
      break;
    }
    $conn->query($sqlquery);
  }

  $conn->close();
}
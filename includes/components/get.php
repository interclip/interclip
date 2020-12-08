<?php

  include_once "./db.php";

if (isset($user_code)) {
  // Create connection
  $conn = new mysqli($servername, $username, $password, $DBName);

  /* Check DB connection */
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  /* Delete expired clips */

  $sql = "DELETE FROM userurl WHERE expires < CURDATE()";
  $conn->query($sql);

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
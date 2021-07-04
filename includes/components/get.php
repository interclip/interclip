<?php

include_once "includes/components/rate.php";
include_once "includes/components/redis.php";

if (isset($user_code)) {

  noteLimit("get");

  // Get the cached value (if it exists)
  $cached = getRedis($user_code);
  if ($cached) {
    $url = $cached;
  } else {

    // Create connection
    $conn = new mysqli($_ENV['DB_SERVER'], $_ENV['USERNAME'], $_ENV['PASSWORD'], $_ENV['DB_NAME']);

    // Check DB connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and execute SQL query to get clips

    $sqlquery = "SELECT * FROM userurl WHERE usr = '$user_code'";
    $result = $conn->query($sqlquery);

    // Get the clip from the DB

    if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
        $url = $row['url'];
        storeRedis($user_code, $url);
        break;
      }
      $conn->query($sqlquery);
    }

    $conn->close();
  }
}

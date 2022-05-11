<?php

include_once "includes/components/redis.php";

if (isset($user_code)) {

  $user_code = explode("?", $user_code)[0];

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
    $stmt = $conn->prepare('SELECT * FROM userurl WHERE usr = ?');

    $stmt->bind_param('s', $user_code);

    $stmt->execute();

    $result = $stmt->get_result();

    // Get the clip from the DB

    if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
        $url = $row['url'];
        var_dump($result);
        storeRedis($user_code, $url);
        break;
      }
    }

    $conn->close();
  }
}

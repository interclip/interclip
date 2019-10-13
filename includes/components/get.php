<?php
if (!empty($_GET['code'])) {
  $user_code = $_GET['code'];

  include("./db.php");
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
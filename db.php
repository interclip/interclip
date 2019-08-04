<?php
//if(isset($_GET['url'])) {
  $servername = "localhost";
  $username = "root";
  $password = "";
  $DBName = "interclip";
  // Create connection
  $conn = new mysqli($servername, $username, $password, $DBName);
  
  // Check connection
  if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
  }

  $usr = "filip";
  $url = "uploadhys.com";

  //$sqlquery = "INSERT INTO userurl (id, usr, url) VALUES (NULL, '$usr', '$url') ";
  $sqlquery = "UPDATE userurl SET url=? WHERE usr=?";
  $stmt = $conn->prepare($sqlquery);
  $stmt->bind_param("ss", $url, $usr);
  $stmt->execute();
  
if ($conn->query($sqlquery) === TRUE) {
    echo "Updated!";
} else {
    echo "Error: " . $sqlquery . "<br>" . $conn->error;
}  
$conn->close();
//}

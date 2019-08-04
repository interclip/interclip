<?php 

if(!empty($_POST['user'])) {
  $user_code = $_POST['user'];
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
  //$sqlquery = "INSERT INTO userurl (id, usr, url) VALUES (NULL, '$usr', '$url') ";
  $sqlquery = "SELECT * FROM userurl WHERE usr = '$user_code'";
  $result = $conn->query($sqlquery);

  if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
    		echo json_encode($row['url']);

}
$conn->query($sqlquery);
}

$conn->close();
//}

}
?>
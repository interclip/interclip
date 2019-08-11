<link rel="stylesheet" href="css/get.css">
<ul>
  <li><a href="../">Send</a></li>
  <li><a class="active" href="./">Recieve</a></li>
  <li style="float:right"><a href="#about">About</a></li>
</ul>

<meta name="viewport" content="width=device-width, initial-scale=1.0">
  
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
  $sqlquery = "SELECT * FROM userurl WHERE usr = '$user_code'";
  $result = $conn->query($sqlquery);

  if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $url = $row['url'];
        break;

}
$conn->query($sqlquery);
}

$conn->close();
//}

}
?>
<div id="fullscreen">
<div class="fullscreen-content">

<div class="title">
  <h1><a href="<?php echo $url ?>"><?php echo $url ?></a></h1>
<p>... is the URL of the code <?php echo $user_code ?></p>
</div>

</div>
</div>

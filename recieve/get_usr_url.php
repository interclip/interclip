<link rel="stylesheet" href="css/get.css">
<div id="endora" style="display: none">
  <endora>
</div>
<ul>
  <li><a href="../">Send</a></li>
  <li><a class="active" href="./">Recieve</a></li>
  <li style="float:right"><a href="about.html">About</a></li>
</ul>

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

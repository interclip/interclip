<link rel="stylesheet" href="css/new.css">
<ul>
  <li><a class="active" href="./">Send</a></li>
  <li><a href="./recieve/">Recieve</a></li>
  <li style="float:right"><a href="#about">About</a></li>
</ul>

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<?php
if(isset($_POST['input'])) {
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
  function gen_uid($l=10){
    return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyz"), 0, $l);
    }
    $usr = gen_uid(5);
    $url = $_POST['input'];
    $timestamp = date("Y-m-d H:i:s");

  $sqlquery = "INSERT INTO userurl (id, usr, url, date) VALUES (NULL, '$usr', '$url', '$timestamp') ";

if ($conn->query($sqlquery) === TRUE) {
} else {
    echo "Error: " . $sqlquery . "<br>" . $conn->error;
}  
$conn->close();
}
?>
<div id="fullscreen">
<div class="fullscreen-content">

<div class="title">
  <p><?php echo $url?><br><br> was saved as</p>
  <h1><?php echo $usr ?></h1>

</div>

</div>
</div>
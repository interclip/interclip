<link rel="stylesheet" href="css/get.css">
<div id="endora" style="display: none">
  <endora>
</div>
<ul>
  <li><a href="../">Send</a></li>
  <li><a class="active" href="./">Recieve</a></li>
  <li style="float:right"><a href="about.html">About</a></li>
</ul>

<meta name="viewport" content="width=device-width, initial-scale=1.0">
  
<?php 


if(!empty($_POST['user'])) {
  $user_code = $_POST['user'];
  
  include("../db.php");
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
        echo " <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>";
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
  <h1><a id="urlLink" href="<?php echo $url ?>"><?php echo $url ?></a></h1>
<p>... is the URL of the code <?php echo $user_code ?></p>
<iframe id="ytplayerSide" frameborder="0"> </iframe>
<img id="imgShow"> 
</div>

</div>
</div>
<script src="../js/get_link.js"></script>

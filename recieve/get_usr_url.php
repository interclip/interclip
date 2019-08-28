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

</div>

</div>
</div>
<script> 
function valUrl() {
    var url = $('#urlLink').text();
    if (url != undefined || url != '') {
      var regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=|\?v=)([^#\&\?]*).*/;
      var match = url.match(regExp);
      if (match && match[2].length == 11) {
        // Do anything for being valid
        // if need to change the url to embed url then use below line
        $('#ytplayerSide').attr('src', 'https://www.youtube.com/embed/' + match[2] + '?autoplay=0&rel=0');
      } else {
       $('#ytplayerSide').hide()
  
        // Do anything for not being valid
      }
    }
  }
  valUrl()
  </script>

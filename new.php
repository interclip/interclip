<div id="endora" style="display: none">
  <endora>
</div>
<link rel="stylesheet" href="css/new.css">
<ul>
  <li><a class="active" href="./">Send</a></li>
  <li><a href="./recieve/">Recieve</a></li>
  <li style="float:right"><a href="about.html">About</a></li>
</ul>

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<?php
if(isset($_POST['input'])) {
  include("db.php");

  
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
  <?php
if(isset($_POST['input'])) {
  echo '<p>'.$url.'<br><br> was saved as</p>  <h1>'.$usr.'</h1>  <div id="qrcode"></div>  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>  <script src="js/jquery.qrcode.min.js"></script><script>  valUrl();  $("#qrcode").qrcode({width: 256,height: 256,text: "'.$url.'",  fill: "#fff",background: "#ff9800", foreground:"black"}); </script></div>';
} else {
  echo '<h1 class="errheader"><span>4</span>&nbsp;<span>0</span>&nbsp;<span>0</span> </h1><br> <span id="errcode">bad request</span>';
}

?>
<iframe id="ytplayerSide" frameborder="0"> </iframe>
<script> 
function valUrl() {
    var url = "<?php echo $url ?>";
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
</div>
</div>
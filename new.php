<div id="endora" style="display: none">
  <endora>
</div>
<link rel="stylesheet" href="css/new.css">
<?php
include("menu.php");
?>

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<?php
if(isset($_POST['input'])) {
  include "db.php";

  
  // Create connection
  $conn = new mysqli($servername, $username, $password, $DBName);
  
  // Check connection
  if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
  }
  function gen_uid($len=10){
    return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyz"), 0, $len);
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
  echo '<p><span id="url" class="url">'.$url.'</span><br><br> was saved as</p>  <h1>'.$usr.'</h1><div id="embed"> </div>';
} else {
  echo '<h1 class="errheader"><span>4</span>&nbsp;<span>0</span>&nbsp;<span>0</span> </h1><br> <span id="errcode">bad request</span>';
}

?>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>

  <script src="https://embed.filiptronicek.now.sh/embed.js"></script>
  <script> 
Embed($("#url").text());
  </script>

</div>
</div>
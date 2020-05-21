<div id="endora" style="display: none">
  <endora>
</div>
<link rel="stylesheet" href="../css/new.css">
<link rel="stylesheet" href="../css/dark.css" media="(prefers-color-scheme: dark)">
<title> New clip - Interclip </title>
<?php
include_once "menu.php";
?>

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<?php
if (isset($_POST['input'])) {
  include "db.php";


  // Create connection
  $conn = new mysqli($servername, $username, $password, $DBName);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }
  function gen_uid($len = 10)
  {
    return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyz"), 0, $len);
  }
  $usr = gen_uid(5);
  $url = $_POST['input'];
  $timestamp = date("Y-m-d H:i:s");

  $sqlquery = "SELECT * FROM userurl WHERE url = '$url'";
  $result = $conn->query($sqlquery);

  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      $usr = $row['usr'];
      break;
    }
    $conn->query($sqlquery);
  } else {
    $sqlquery = "INSERT INTO userurl (id, usr, url, date) VALUES (NULL, '$usr', '$url', '$timestamp') ";
    if ($conn->query($sqlquery) === FALSE) {
      echo "Error: " . $sqlquery . "<br>" . $conn->error;
    }
  }

  $conn->close();
}
?>
<script src="https://cdn.jsdelivr.net/gh/jquery/jquery@3.2.1/dist/jquery.min.js"> </script>
<script src="https://cdn.jsdelivr.net/gh/filiptronicek/Embed/embed.js"> </script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.qrcode/1.0/jquery.qrcode.min.js"> </script>
<div id="fullscreen">
  <div class="fullscreen-content">

    <div class="title">
      <?php
      if (isset($_POST['input'])) {
        include_once "components/outputs/new-ok-msg.php";
      } else {
        include_once "components/outputs/new-404.php";
      }

      ?>

      <script>
        Embed($("#url").text());
      </script>

    </div>
  </div>

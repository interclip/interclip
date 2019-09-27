<title>Get your link | Interclip</title>
<link rel="stylesheet" href="css/get.css">
<div id="endora" style="display: none">
  <endora>
</div>
<ul>
  <li><a href="../">Send</a></li>
  <li><a href="../image">Send image</a></li>
  <li><a class="active" href="./">Recieve</a></li>
  <li style="float:right"><a href="../about.html">About</a></li>
</ul>

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<?php


if (!empty($_POST['user'])) {
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
    while ($row = $result->fetch_assoc()) {
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
  <div class="fullscreen-content" id="resultSec">

    <div class="title">
      <h1><a id="urlLink" href="<?php if (isset($url)) {
                                  echo $url;
                                } ?>"><?php if (isset($url)) {
                                                                          echo $url;
                                                                        } ?></a></h1>
      <?php
      if (isset($url)) {
        echo "<p>... is the URL of the code " . $user_code . "</p>";
      } else {
        echo "<p>There was no url found for the code ;" . $user_code . "</p>";
      }
      ?>
      <div id="video">
        <img id="imgShow">
        <div id="music">
        </div>
      </div>

    </div>
  </div>
  <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
  <script src="../js/get_link.js"></script>
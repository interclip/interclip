<title>Get your link | Interclip</title>
<link rel="stylesheet" href="../css/get.css">
<link rel="stylesheet" href="../css/dark.css">

<div id="endora" style="display: none">
  <endora>
</div>
<title> Get clip - Interclip </title>

<?php
include_once "menu.php";
if (!empty($_POST['user'])) {
  $user_code = $_POST['user'];
  include_once "components/get.php";
}
?>

<meta name="viewport" content="width=device-width, initial-scale=1.0">

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
        http_response_code(400);
        echo "<p>There was no url found for the code " . $user_code . "</p>";
        die();

      }
      ?>
      <div id="embed"> </div>
      <img id="imgShow">
      <div id="output"> </div>

    </div>
  </div>
</div>
<script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
<script src="https://cdn.jsdelivr.net/gh/filiptronicek/Embed/embed.js"> </script>
<script src="../js/get.js"></script>
<script>
  Embed($("#urlLink").text());
</script>

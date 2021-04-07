<!DOCTYPE html>
<html lang="en">

<head>
  <?php
        include_once "header.php";
  ?>
  <title>Get your link | Interclip</title>

  <link rel="stylesheet" type="text/css" href="../css/get.css">
</head>

<body>

  <?php
  include "anti-csrf.php";
  validate();
  include_once "menu.php";

  if (!empty($_POST['user'])) {
    $user_code = $_POST['user'];
    $user_code = htmlspecialchars($user_code);

    include_once "components/get.php";
  }

  if (!empty($url)) {
    function get_shorten_url($url)
    {
      $headers = get_headers($url, 1);
      if (isset($headers['Location'])) return $headers['Location'];
      else return $url;
    }
    $realUrl = get_shorten_url($url);
  }
  ?>

  <div id="fullscreen">
    <div class="fullscreen-content" id="resultSec">

      <div class="title">
        <?php if (isset($url)) : ?>
          <h1><a id="urlLink" href="<?php echo $url; ?>"><?php echo $url; ?></a></h1>
          <?php
          echo "<p>... is the URL of the code " . $user_code . "</p>";
          ?>
          <div id="embed"> </div>
          <img id="imgShow">
          <div id="output"> </div>
        <?php elseif (!empty($user_code)) : ?>
          <?php
          http_response_code(404);
          echo "<p>There was no url found for the code " . $user_code . "</p>";
          ?>
        <?php else : ?>
          <?php
          http_response_code(400);
          echo "<p>I hate to admin it, but there is no <i>null</i> code, is this a 404? No, because there was nothing to not-be-found. Weird, try again, and fill out the code next time.</p>";
          ?>
        <?php endif; ?>
      </div>
    </div>
  </div>
  <script src="../js/get.js"></script>
  <script type="module">
    import {
      embed
    } from "https://cdn.jsdelivr.net/gh/aperta-principium/embed.js/embed.min.js";

    embed("<?php echo $realUrl ?>");
  </script>

</body>

</html>
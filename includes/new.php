<html lang="en">

<head>
    <?php
        include_once "header.php";
    ?>
    <title>New clip | Interclip</title>

    <link rel="stylesheet" type="text/css" href="css/button.css">
    <link rel="stylesheet" type="text/css" href="../css/new.css">
</head>

<body>
  <?php
  include "anti-csrf.php";
  validate();
  include_once "menu.php";
  ?>

  <?php

  if (isset($_POST['input'])) {
    $url = $_POST['input'];
    $url = htmlspecialchars($url);

    include_once "./components/new.php";
  }
  ?>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.qrcode/1.0/jquery.qrcode.min.js"> </script>

  <div id="fullscreen">
    <div class="fullscreen-content">

      <div class="title">
        <?php
        if (isset($_POST['input']))
          include_once "components/outputs/new-ok-msg.php";
        else
          include_once "components/outputs/new-404.php";
        ?>

        <script type="module">
          import {
            embed
          } from "https://cdn.jsdelivr.net/gh/aperta-principium/embed.js/embed.min.js";

          embed($("#url").text());
        </script>
      </div>
    </div>
</body>
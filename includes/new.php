<html lang="en">

<head>
    <meta content="text/html; charset=UTF-8; X-Content-Type-Options=nosniff" http-equiv="Content-Type" />
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>New clip | Interclip</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.3/css/font-awesome.min.css">
    <link href="css/button.css" rel="stylesheet" />
    <link rel="stylesheet" href="../css/new.css">
    <link rel="stylesheet" href="../css/dark.css" media="(prefers-color-scheme: dark)">
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
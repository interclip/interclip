<link rel="stylesheet" href="../css/new.css">
<link rel="stylesheet" href="../css/dark.css" media="(prefers-color-scheme: dark)">
<link rel="stylesheet" href="../css/menu.css">
<title> New clip - Interclip </title>

<?php
  include_once "menu.php";
?>

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<?php
  if (isset($_POST['input'])) {
    $url = $_POST['input'];
    include_once "./components/new.php";
  }
?>

<script src="https://cdn.jsdelivr.net/gh/jquery/jquery@3.2.1/dist/jquery.min.js"> </script>
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
      import { embed } from "https://cdn.jsdelivr.net/gh/aperta-principium/embed.js/embed.min.js";

      embed($("#url").text());
      </script>
    </div>
  </div>
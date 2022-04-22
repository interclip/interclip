<html lang="en">

<head>
  <?php
  include_once "includes/header.php";
  ?>
  <title>New clip | Interclip</title>

  <link rel="stylesheet" type="text/css" href="<?php echo ROOT ?>/css/new.css">
  <link href="https://fonts.googleapis.com/css2?family=Roboto+Mono&display=swap" rel="stylesheet">
</head>

<body>
  <?php
  include "includes/anti-csrf.php";
  validate();
  include_once "includes/menu.php";

  if (isset($_POST['input'])) {
    $url = $_POST['input'];
    $url = htmlspecialchars($url);

    include_once "includes/components/new.php";
  }
  ?>

  <script src="https://cdn.jsdelivr.net/gh/englishextra/qrjs2@latest/js/qrjs2.min.js"> </script>

  <div id="fullscreen">
    <div class="fullscreen-content">

      <div class="title">
        <?php if (!isset($_POST['input'])) : ?>
          <h1 class="errheader">
            <span>4</span>&nbsp;
            <span>0</span>&nbsp;
            <span>0</span>
          </h1>
          <br>
          <span id="errcode">
            bad request
          </span>
        <?php else : ?>
          <?php
          $createArray = createClip($url);
          $usr = $createArray[0];
          $err = $createArray[1];
          $ipfs = $createArray[2];
          ?>

          <?php if ($err === "") : ?>
            <p><span id="url" class="url"><?php echo $url ?> </span><br><br> was saved as</p>
            <div id="codeSection">
              <?php if ($ipfs) : ?>
                <img src="/img/icons/ipfs.png" style="max-height: 3em;">
              <?php endif; ?>
              <h1 class="usrCode"><?php echo $usr ?></h1>
              <svg fill="none" stroke="currentColor" id="copyCode" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"></path>
              </svg>
            </div>
            <div id="qrcode"></div>
          <?php else : ?>
            <p><span id="url" class="url"><?php echo $url ?> </span><br></p>
            <h1 class="usrCode"><?php echo $err ?></h1>
          <?php endif; ?>

          <script>
            const url = "<?php echo $url ?>";
            const code = "<?php echo $usr ?>";
          </script>
          <script src="<?php echo ROOT ?>/js/new.js"></script>

        <?php endif; ?>
      </div>
    </div>
</body>
<?php
include_once 'includes/lib/init.php';
include_once 'includes/lib/headers.php';
include_once 'includes/anti-csrf.php';

header('X-Robots-Tag: noindex, nofollow, noarchive');
validate();

$url = isset($_POST['input']) && is_string($_POST['input']) ? $_POST['input'] : null;
$usr = null;
$err = '';
if ($url === null) {
  http_response_code(400);
} else {
  include_once "includes/components/new.php";
  [$usr, $err] = createClip($url);
  if ($err !== '' || $usr === null) {
    http_response_code($err === 'invalid URL specified' ? 400 : 503);
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <?php
  include_once "includes/header.php";
  ?>
  <title>New clip | Interclip</title>

  <link rel="stylesheet" type="text/css" href="<?php echo ROOT ?>/css/new.css">
</head>

<body>
  <?php
  include_once "includes/menu.php";
  ?>

  <div id="fullscreen">
    <div class="fullscreen-content">

      <div class="title">
        <?php if ($url === null) : ?>
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
          <?php if ($err === "") : ?>
            <p><span id="url" class="url"><?php echo escapeHtml($url) ?></span><br><br> was saved as</p>
            <div id="codeSection">
              <h1 class="mono"><?php echo escapeHtml($usr) ?></h1>
              <span tabindex="0" id="copyCode">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3. org/2000/svg">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"></path>
                </svg>
              </span>
            </div>
            <canvas id="qrcode"></canvas>
            <script data-cfasync="false" nonce="<?php echo escapeHtml(cspNonce()) ?>">
              const code = <?php echo json_encode($usr, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
            </script>
            <script data-cfasync="false" src="<?php echo ROOT ?>/out/new.js"></script>
          <?php else : ?>
            <p><span id="url" class="url"><?php echo escapeHtml($url) ?></span><br></p>
            <h1 class="mono"><?php echo escapeHtml($err) ?></h1>
          <?php endif; ?>

        <?php endif; ?>
      </div>
    </div>
</body>

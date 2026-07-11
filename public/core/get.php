<?php
include_once 'includes/lib/init.php';
include_once 'includes/lib/headers.php';
include_once 'includes/anti-csrf.php';

header('X-Robots-Tag: noindex, nofollow, noarchive');
validate();

$user_code = isset($_POST['user']) && is_string($_POST['user']) ? trim($_POST['user']) : '';
$lookupError = '';
if ($user_code === '') {
  $lookupError = 'missing';
  http_response_code(400);
} elseif (!isValidClipCode($user_code)) {
  $lookupError = 'invalid';
  http_response_code(400);
} else {
  include_once "includes/components/get.php";
  if (!isset($url)) {
    $lookupError = 'missing-clip';
    http_response_code(404);
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <?php
        include_once "includes/header.php";
  ?>
  <title>Get your link | Interclip</title>

  <link rel="stylesheet" type="text/css" href="<?php echo ROOT ?>/css/get.css">
</head>

<body>

  <?php
  include_once "includes/menu.php";
  ?>

  <div id="fullscreen">
    <div class="fullscreen-content" id="resultSec">

      <div class="title">
        <?php if (isset($url)) : ?>
          <h1>
            <?php if (isSafeNavigationUri($url)) : ?>
              <a id="urlLink" href="<?php echo escapeHtml($url); ?>"><?php echo escapeHtml($url); ?></a>
            <?php else : ?>
              <span id="urlLink"><?php echo escapeHtml($url); ?></span>
            <?php endif; ?>
          </h1>
          <?php
          echo "<p>... is the <span id='clipType'>URL</span> of the code " . escapeHtml($user_code) . "</p>";
          ?>
          <img id="imgShow">
          <div id="output"> </div>
        <?php elseif ($lookupError === 'missing-clip') : ?>
          <p>There was no URL found for the code <?php echo escapeHtml($user_code) ?></p>
        <?php elseif ($lookupError === 'invalid') : ?>
          <p>The supplied clip code is invalid.</p>
        <?php else : ?>
          <p>No clip code was supplied.</p>
        <?php endif; ?>
      </div>
    </div>
  </div>
  <script src="<?php echo ROOT ?>/out/get.js"></script>
</body>

</html>

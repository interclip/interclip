<?php

require_once ROOT_DIR . "/includes/lib/auth.php";
require_once ROOT_DIR . "/includes/lib/security.php";
require_once ROOT_DIR . "/includes/lib/functions.php";

$releaseName = trim((string) ($_ENV['APP_RELEASE'] ?? ''));
$showAdminBar = is_array($user) && $isStaff;

$renderTimeMicro = microtime(true) - ($_SERVER['REQUEST_TIME_FLOAT'] ?? microtime(true));
$renderTime = number_format($renderTimeMicro * 1000, 2);

$hash = trim((string) ($_ENV['APP_COMMIT'] ?? ''));
if (preg_match('/\A[0-9a-f]{7,40}\z/i', $hash) !== 1) {
  $hash = '';
}
$hashShort = $hash === '' ? '' : substr($hash, 0, 7);
$currBranch = trim((string) ($_ENV['APP_BRANCH'] ?? 'main')) ?: 'main';

?>
<?php if ($showAdminBar) : ?>
  <div id="adminbar" title="Press Shift+B to toggle the admin bar" <?php echo $_ENV['ENVIRONMENT'] === "staging" ? "class='staging'" : "" ?>>
    <span title="The total time it took the client to render the DOM and fetch all the necessary resources" id="load">Client: TBD</span>
    <span title="The total time it took the server to process the request">Server: <?php echo $renderTime ?>ms</span>
    <span class="lg" title="The current response status code"><a href="https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/<?php echo http_response_code() ?>" target="_blank" rel="noreferrer nofollow">HTTP <?php echo http_response_code() ?></a></span>
    <span>
      <a title="View branch on GitHub" href="https://github.com/interclip/interclip/tree/<?php echo escapeHtml(rawurlencode($currBranch)) ?>">
        <?php echo escapeHtml($currBranch) ?>
      </a>
      <?php if ($hash !== '') : ?>
        @
        <a title="View commit on GitHub" href="https://github.com/interclip/interclip/commit/<?php echo escapeHtml($hash) ?>">
          <?php echo escapeHtml($hashShort) ?>
        </a>
      <?php endif; ?>
    </span>
    <span class="lg">PHP <?php echo phpversion(); ?></span>
    <span class="lg">Memory: <?php echo formatBytes(memory_get_usage()) ?></span>
    <span class="ending lg" tabindex="0" id="user-greet">
      Hi, <?php echo escapeHtml($user["name"] ?? $user["nickname"] ?? 'there') ?>
      <?php if ($_ENV["AUTH_TYPE"] !== "mock") : ?>
        <button type="submit" form="logout-form" class="subitem" id="logout-button">Log out</button>
      <?php endif; ?>
    </span>
  </div>
<?php endif; ?>

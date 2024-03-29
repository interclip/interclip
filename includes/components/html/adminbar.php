<?php

require ROOT_DIR . "/includes/lib/auth.php";

exec('git describe --abbrev=0 --tags', $release);
if ($user !== false) {
  if ($isStaff) {
    exec('git rev-parse --verify HEAD', $output);
    $hash = $output[0];
    $hashShort = substr($hash, 0, 7);
    $commit = "https://github.com/interclip/interclip/commit/" . $hash;
  }
}

$renderTimeMicro = microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'];
$renderTime = number_format($renderTimeMicro * 1000, 2);

$branches = getBranches();
$currBranch = $branches["current"];

?>
<?php if (!is_bool($user) && $isStaff) : ?>
  <div id="adminbar" <?php echo $_ENV['ENVIRONMENT'] === "staging" ? "class='staging'" : "" ?>>
    <span title="The total time it took the client to render the DOM and fetch all the necessary resources" id="load">Client: TBD</span>
    <span title="The total time it took the server to process the request">Server: <?php echo $renderTime ?>ms</span>
    <span class="lg" title="The current response status code"><a href="https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/<?php echo http_response_code() ?>" target="_blank" rel="noreferrer nofollow">HTTP <?php echo http_response_code() ?></a></span>
    <?php if ($_ENV['ENVIRONMENT'] === "staging") : ?>
      <?php $branches = getBranches(); ?>
      <span>Current branch:
        <select id="branch-select">
          <?php
          echo "<option value='-'>$currBranch</option>";
          foreach ($branches["all"] as $branch) {
            echo "<option value='$branch'>$branch</option>";
          }
          ?>
        </select>
      </span>
    <?php endif; ?>
    <span>
      <a title="View branch on GitHub" href="https://github.com/interclip/interclip/tree/<?php echo $currBranch ?>">
        <?php echo $currBranch ?>
      </a>
      @
      <a title="View commit on GitHub" href="https://github.com/interclip/interclip/commit/<?php echo $hash ?>">
        <?php echo $hashShort ?>
      </a>
    </span>
    <span class="lg">PHP <?php echo phpversion(); ?></span>
    <span class="lg">Memory: <?php echo formatBytes(memory_get_usage()) ?></span>
    <span class="ending lg" tabindex="0" id="user-greet">
      Hi, <?php echo $user["name"] ?? $user["nickname"]  ?>
      <?php if ($_ENV["AUTH_TYPE"] !== "mock") : ?>
        <a class="subitem" id="logout-button" href="<?php echo ROOT ?>/logout">Log out</a>
      <?php endif; ?>
    </span>
  </div>
<?php endif; ?>
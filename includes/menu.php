<?php

$relative_path = $_SERVER['PHP_SELF'];
$index = 0;
list($scriptPath) = get_included_files();
$pages = array(
  'clip' => ['', 'Clip'],
  'file' => ['file', 'File <span class="beta">beta</span>'],
  'receive' => ['receive', 'Receive clip'],
  'desktop' => ['desktop', "Desktop"],
  'privacy' => ['privacy', 'Privacy policy'],
  'about' => ['about', 'About']
);

if (!isset($prodvar)) {
  include 'prod.php';
}

if (!isProd()) {
  $scriptNameArray = explode("\\", $scriptPath);
} else {
  $scriptNameArray = explode("/", $scriptPath);
  include_once 'analytics.php';
}

$currFile = end($scriptNameArray);

if ($currFile == "get.php" || $currFile == "new.php") {
  $urlPrefix = "../";
} else {
  $urlPrefix = "./";
}

if ($auth0->getUser()) {

  exec('git rev-parse --verify HEAD', $output);
  $hash = $output[0];
  $hashShort = substr($hash, 0, 7);
  $commit = "https://github.com/aperta-principium/Interclip/commit/" . $hash;

  exec('git describe --abbrev=0 --tags', $release);

  include_once "./components/rate.php";
  $conn = new mysqli($_ENV['SERVER_NAME'], $_ENV['USERNAME'], $_ENV['PASSWORD'], $_ENV['DB_NAME']);

  $sqlquery = "SELECT id FROM userurl ORDER BY ID DESC LIMIT 1";
  $result = $conn->query($sqlquery);
  while ($row = $result->fetch_assoc()) {
    $count = $row['id'];
    break;
  }
  if (!$count) $count = 0;

  $systemLoad = sys_getloadavg()[0];
}
?>
<?php if ($user = $auth0->getUser()) : ?>
  <div id="adminbar">
    <span id="load">Load: Instant</span>
    <span>Server render: <?php echo number_format((microtime(true) - $beginLoad) * 1000, 2) ?>ms</span>
    <span>Clips: <?php echo $count ?></span>
    <span>Deployed from: <?php echo $release[0] . "@" . $hashShort ?></span>
    <span id="files">Total files: 0 (0B)</span>
    <span>Server load: <?php echo $systemLoad ?></span>
    <span class="ending">Signed in as: <?php echo $user["name"] ?></span>
  </div>
<?php endif; ?>

<?php

// Render the menu
echo '<ul id="menu">';

//echo sizeof($pages);
foreach ($pages as $page) {

  $index++;
  //  echo $page;
  if ($page[0] == $currFile) {
    echo '<li><a class="active" href="#">' . $page[1] . '</a></li>';
  } else {
    if (strpos($page[1], '<span class="beta">') === false) {
      if ($page[0] == "about") {
        echo '<li style="float:right"><a href="' . ROOT . '/about">About</a></li></ul>';
      } elseif ($page[0] == "desktop") {
        echo '<li id="desktopMenuItem" style="display: none;"><a href="' . ROOT . "/" . $page[0] . '">' . $page[1] . '</a></li> ';
      } else {
        echo '<li><a href="' . ROOT . "/" . $page[0] . '">' . $page[1] . '</a></li> ';
      }
    } else {
      echo '<li style="display: none"><a href="' . ROOT . "/" . $page[0] . '">' . $page[1] . '</a></li> ';
    }
  }
}

?>

<dark-mode-toggle id="dark-mode-toggle-1" permanent="true"></dark-mode-toggle>
<svg id="triggerModal" class="settingsIcon" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
  <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"></path>
</svg>

<!-- The Modal -->
<div id="settingsModal" class="modal">

  <!-- Modal content -->
  <div class="modal-content">
    <span class="closeBtn">&times;</span>
    <h2>Settings</h2>
    <p>Here you can change some of Interclip's settings.</p>
    <h3>Color scheme</h3>
    <!-- Rounded switch -->
    <div class="select">
      <select name="slct" id="slct">
        <option value="dark">Dark 🌑</option>
        <option value="light">Light ☀️</option>
        <option id="systemOption" value="system">System</option>
      </select>
    </div>
    <h3>Hash animations</h3>
    <p>Toggle the animation of the random hash on the receive page.</p>
    <div class="flex">
      <span class="toggleLabel">Off</span>
      <!-- Rounded switch -->
      <label class="switch">
        <input type="checkbox" id="hashanimation">
        <span class="slider round"></span>
      </label>
      <span class="toggleLabel">On</span>
    </div>
    <h3>Beta menu</h3>
    <p>Hide or show Interclip's beta features in the menu.</p>
    <div class="flex">
      <span class="toggleLabel">Hide</span>
      <!-- Rounded switch -->
      <label class="switch">
        <input type="checkbox" id="betafeatures">
        <span class="slider round"></span>
      </label>
      <span class="toggleLabel">Show</span>
    </div>
  </div>

</div>

<script> const loggedIn = <?php echo $auth0->getUser() ? "true" : "false" ?> </script>
<script src="<?php echo ROOT ?>/js/menu.js"></script>
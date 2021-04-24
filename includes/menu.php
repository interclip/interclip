<?php

include_once "lib/functions.php";

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

if ($_ENV['ENVIRONMENT'] === "production") {
  include_once 'analytics.php';
}

//By default, we assume that PHP is NOT running on windows.
$isWindows = false;

//If the first three characters PHP_OS are equal to "WIN",
//then PHP is running on a Windows operating system.
if (strcasecmp(substr(PHP_OS, 0, 3), 'WIN') === 0) {
  $isWindows = true;
}

if ($isWindows) {
  $scriptNameArray = explode("\\", $scriptPath);
} else {
  $scriptNameArray = explode("/", $scriptPath);
}

$currFile = end($scriptNameArray);

if ($currFile == "get.php" || $currFile == "new.php") {
  $urlPrefix = "../";
} else {
  $urlPrefix = "./";
}

include_once "components/html/adminbar.php";

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

include_once "components/html/settings.php";

?>

<script>
  const loggedIn = <?php echo $user ? "true" : "false" ?>;
  const isAdmin = <?php echo $isStaff ? "true" : "false" ?>;
  const version = "<?php echo $release[0] ?>";

  const root = "<?php echo ROOT ?>";
</script>

<script src="<?php echo ROOT ?>/js/formatter.js"></script>
<script src="<?php echo ROOT ?>/js/menu.js"></script>
<?php

function formatBytes($bytes, $precision = 0)
{
  $units = array('B', 'KB', 'MB', 'GB', 'TB');

  $bytes = max($bytes, 0);
  $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
  $pow = min($pow, count($units) - 1);
  $bytes /= pow(1024, $pow);

  return round($bytes, $precision) . ' ' . $units[$pow];
}

if (!function_exists('str_starts_with')) {
  function str_starts_with($haystack, $needle)
  {
    return (string)$needle !== '' && strncmp($haystack, $needle, strlen($needle)) === 0;
  }
}

function getBranches()
{
  exec("git branch", $gitOutput);

  $branches = [
    "all" => [],
    "current" => ''
  ];

  foreach ($gitOutput as $branchString) {
    if (str_starts_with($branchString, "*")) {
      $current = substr($branchString, 2);
      $branches["current"] = $current;
    } else {
      $currentBranchClean = str_replace("remotes/", "", $branchString);
      array_push($branches["all"], $currentBranchClean);
    }
  }
  return $branches;
}

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
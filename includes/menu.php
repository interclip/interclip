<?php
if (!isset($prodvar)) {
    include('prod.php');
}

$relative_path = $_SERVER['PHP_SELF'];
$index = 0;
list($scriptPath) = get_included_files();
$pages = array(
    'clip' => ['', 'Clip'],
    'file' => ['file', 'File <span class="beta">beta</span>'],
    'recieve' => ['recieve', 'Recieve clip'],
    'desktop' => ['desktop', "Desktop"],
    'privacy' => ['privacy', 'Privacy policy'],
    'about' => ['about', 'About']
);
if (!isProd()) {
    $scriptNameArray = explode("\\", $scriptPath);
} else {
    $scriptNameArray = explode("/", $scriptPath);
    include_once('analytics.php');
}
$currFile = end($scriptNameArray);

if ($currFile == "get.php" || $currFile == "new.php") {
    $urlPrefix = "../";
} else {
    $urlPrefix = "./";
}

// Render the menu
echo '<ul id="menu">';

//echo sizeof($pages);
foreach ($pages as $page) {

    $index++;
    //  echo $page;
    if ($page[0] == $currFile) {
        echo '<li><a class="active" href="#">' . $page[1] . '</a></li>';
    } else {
        if ($page[0] == "about") {
            echo '<li style="float:right"><a href="' . $urlPrefix . 'about">About</a></li></ul>';
        } elseif ($page[0] == "desktop") {
            echo '<li id="desktopMenuItem" style="display: none;"><a href="' . $urlPrefix . $page[0] . '">' . $page[1] . '</a></li> ';
        } else {
            echo '<li><a href="' . $urlPrefix . $page[0] . '">' . $page[1] . '</a></li> ';
        }
    }
}

?>

<!-- Primary Meta Tags -->
<meta name="title" content="Interclip - easy peasy clipboard sharing">
<meta name="description" content="Interclip is a tool for easily sharing URLs between devices or users.">

<!-- Open Graph / Facebook -->
<meta property="og:type" content="website">
<meta property="og:url" content="https://interclip.app/">
<meta property="og:title" content="Interclip - easy peasy clipboard sharing">
<meta property="og:description" content="Interclip is a tool for easily sharing URLs between devices or users.">
<meta property="og:image" content="https://interclip.app/img/header.png">

<!-- Twitter -->
<meta property="twitter:card" content="summary_large_image">
<meta property="twitter:url" content="https://interclip.app/">
<meta property="twitter:title" content="Interclip - easy peasy clipboard sharing">
<meta property="twitter:description" content="Interclip is a tool for easily sharing URLs between devices or users.">
<meta property="twitter:image" content="https://interclip.app/img/header.png">

<link rel="apple-touch-icon" sizes="180x180" href="/icons/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="/icons/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="/icons/favicon-16x16.png">
<link rel="manifest" href="/site.webmanifest">

<script src='https://cdn.jsdelivr.net/gh/jquery/jquery/dist/jquery.min.js'></script>
<script type="module" src="https://cdn.pika.dev/dark-mode-toggle"></script>

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

<script src="js/menu.js"></script>

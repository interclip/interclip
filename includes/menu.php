<?php
if (!isset($prodvar)) {
    include('prod.php');
}

$relative_path = $_SERVER['PHP_SELF'];
$index = 0;
list($scriptPath) = get_included_files();
$pages = array(
    'clip' => ['', 'Clip'],
    'file' => ['file', 'Send file'],
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
<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
<link rel="manifest" href="/site.webmanifest">

<script src='https://cdn.jsdelivr.net/gh/jquery/jquery/dist/jquery.min.js'></script>
<script type="module" src="https://cdn.pika.dev/dark-mode-toggle"></script>
<dark-mode-toggle style="display: flex; justify-content: flex-end; margin-right: 15px;" id="dark-mode-toggle-1" appearance="toggle" permanent="true"></dark-mode-toggle>
<?php

include_once "lib/functions.php";

$index = 0;

$pages = array(
  'clip' => ['', 'Clip'],
  'file' => ['file', 'File <span class="beta">beta</span>'],
  'receive' => ['receive', 'Receive clip'],
  'privacy' => ['privacy', 'Privacy policy'],
  'about' => ['about', 'About']
);

include_once "components/html/adminbar.php";

// Render the menu
echo '<ul id="menu">';

foreach ($pages as $page) {

  $index++;

  if ($page[0] !== "") {
    $page[0] = "$page[0]/";
  }

  if (strpos($page[1], '<span class="beta">') === false) {
    if ($page[0] == "about/") {
      echo '<li style="float:right"><a href="' . ROOT . '/about/">About</a></li></ul>';
    } else {
      echo '<li><a href="' . ROOT . "/" . $page[0] . '">' . $page[1] . '</a></li> ';
    }
  } else {
    echo '<li style="display: none"><a href="' . ROOT . "/" . $page[0] . '">' . $page[1] . '</a></li> ';
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
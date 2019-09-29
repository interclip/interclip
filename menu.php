<?php


$currFile = basename(__FILE__, '.php');
$index = 0;
$pages = array(
    'clip' => ['', 'Clip'],
    'image' => ['image', 'Send image'],
    'file' => ['file', 'Send file'],
    'recieve' => ['recieve', 'Recieve clip'],
    'about' => ['about', 'About'],
);
echo '<ul>';

//echo sizeof($pages);
foreach ($pages as $page) {
    $index++;
    //  echo $page;
    if ($page[0] == $currFile) {
        echo '<li><a class="active" href="#">' . $page[1] . '</a></li>';
    } else {
        if ($page[0] == "about") {
            echo '<li style="float:right"><a href="./about">About</a></li></ul>';
        } else {
            echo '<li><a href="./' . $page[0] . '">' . $page[1] . '</a></li> ';
        }
    }
}

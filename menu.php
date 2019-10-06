<script type="text/javascript">
  var _paq = window._paq || [];
  _paq.push(['trackPageView']);
  _paq.push(['enableLinkTracking']);
  (function() {
    var u="//nfogix.com/";
    _paq.push(['setTrackerUrl', u+'api/track']);
    _paq.push(['setSiteId', '2accc6dc-f7cc-482c-8403-3687eac862cb']);
    var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
    g.type='text/javascript'; g.async=true; g.defer=true; g.src=u+'js/nfogix.min.js'; s.parentNode.insertBefore(g,s);
  })();
</script>
<?php


$currFile = basename(__FILE__, '.php');
$index = 0;
$pages = array(
    'clip' => ['', 'Clip'],
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
echo '<div id="endora" style="display: none"><endora></div>';


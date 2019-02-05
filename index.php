<!DOCTYPE html>
<html lang="en" >
<meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js"></script>
    
<script type="text/javascript">
  function readFiles()
{
    $.get('clip.txt', function(data) {

        document.getElementById("clipboard").innerHTML += data;
    }, "text");
}
</script>
<head>
  <meta charset="UTF-8">
  <title>Interclip</title>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="./css/style.css" rel="stylesheet" media="screen">
    <link href="./css/mobile-style.css" rel="stylesheet" media="screen">
    <link rel="shortcut icon" href="./img/favicon.ico">
</head>

<body>
    <div class="title">
      
    </div>
    <div class="dropzone" id="dropzone">
        <div class="info"></div>
    </div>
    <script type="text/javascript" src="./js/imgur.js"></script>
    <script type="text/javascript" src="./js/upload.js"></script>


  
      <link rel="stylesheet" href="css/style.css">

  
</head>
<div id="hidden">
  <endora></endora>
</div>
<body>
  <script type="text/javascript">
    readFiles();
  </script>
  <p id="clipboard">
<span> Your clipboard:<span>
<br />
<div id="clip-content"></div>
</p>
  <form action="" method="GET">
<div class="container">
  <p class="lb">Link</p>
  <p class="placeholder">URL</p>
  <input name="url" type="text" />

  
</div>
<center>
  <button id="sv" class="btn btn-primary" onclick="form.submit();">
  Save
</button>
  </form>
  <script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>

  

    <script  src="js/index.js"></script>


<div class="hidden"> <endora> </endora> </div>
<a href="https://github.com/filiptronicek/Interclip" target="_blank" class="github-corner" aria-label="View source on Github">		<svg width="80" height="80" viewBox="0 0 250 250" style="fill:#151513; color:#fff; z-index:99; position: absolute; top: 0; border: 0; right: 0;" aria-hidden="true"><path d="M0,0 L115,115 L130,115 L142,142 L250,250 L250,0 Z"></path><path d="M128.3,109.0 C113.8,99.7 119.0,89.6 119.0,89.6 C122.0,82.7 120.5,78.6 120.5,78.6 C119.2,72.0 123.4,76.3 123.4,76.3 C127.3,80.9 125.5,87.3 125.5,87.3 C122.9,97.6 130.6,101.9 134.4,103.2" fill="currentColor" style="transform-origin: 130px 106px;" class="octo-arm"></path><path d="M115.0,115.0 C114.9,115.1 118.7,116.5 119.8,115.4 L133.7,101.6 C136.9,99.2 139.9,98.4 142.2,98.6 C133.8,88.0 127.5,74.4 143.8,58.0 C148.5,53.4 154.0,51.2 159.7,51.0 C160.3,49.4 163.2,43.6 171.4,40.1 C171.4,40.1 176.1,42.5 178.8,56.2 C183.1,58.6 187.2,61.8 190.9,65.4 C194.5,69.0 197.7,73.2 200.1,77.6 C213.8,80.2 216.3,84.9 216.3,84.9 C212.7,93.1 206.9,96.0 205.4,96.6 C205.1,102.4 203.0,107.8 198.3,112.5 C181.9,128.9 168.3,122.5 157.7,114.1 C157.9,116.9 156.7,120.9 152.7,124.9 L141.0,136.5 C139.8,137.7 141.6,141.9 141.8,141.8 Z" fill="currentColor" class="octo-body"></path></svg>	    </a>	    <style>.github-corner:hover .octo-arm{animation:octocat-wave 560ms ease-in-out}@keyframes octocat-wave{0%,100%{transform:rotate(0)}20%,60%{transform:rotate(-25deg)}40%,80%{transform:rotate(10deg)}}@media (max-width:500px){.github-corner:hover .octo-arm{animation:none}.github-corner .octo-arm{animation:octocat-wave 560ms ease-in-out}}</style>
</body>
<?php 
if(isset($_GET['url'])) {
/*
if(filter_var($text, FILTER_VALIDATE_URL))
{
  */
  $long_url = $_GET['url'];
  $bitly_login = 'filiptronicek';
  $bitly_apikey = 'R_e8b7b028a203498f96129cbcda9f15e6';
  $uniqid = uniqid();
  $bitly_response = json_decode(file_get_contents("http://api.bit.ly/v3/shorten?login={$bitly_login}&apiKey={$bitly_apikey}&longUrl={$long_url}&format=json"));
  $short_url = $bitly_response->data->url;

  $myfile = fopen("clip.txt", "w") or die("A fucking error has occured. Either fuck yourself or submit an issue on GitHub");
  $txt = $short_url;
  fwrite($myfile, $txt);
  fclose($myfile);
 // echo '<img id="currentPhoto" onmouseover="showImg()" onmouseout="hideImg()" src="'.$short_url.'" onerror="this.src = \'\'" alt=""></img>';
  /*
}
else
{
  $myfile = fopen("clip.txt", "w") or die("A fucking error has occured. Either fuck yourself or submit an issue on GitHub");
  $txt = $_GET['url'];
  fwrite($myfile, $txt);
  fclose($myfile);
}
*/
}




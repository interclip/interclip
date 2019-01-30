<!DOCTYPE html>
<html lang="en" >
<script
        src="https://code.jquery.com/jquery-3.3.1.min.js"
        integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
        crossorigin="anonymous"></script>
    
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
    <link rel="shortcut icon" href="./favicon.png">
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

<body>
  <script type="text/javascript">
    readFiles();
  </script>
  <p id="clipboard">
Your last clipboard:
<br />
</p>
  <form action="" method="POST">
<div class="container">
  <p class="lb">Link</p>
  <p class="placeholder">URL</p>
  <input name="url" type="text" />
  <div class="border"></div>

  
</div>
<center>
  <button id="sv" onclick="form.submit();">
  Save
</button>
  </form>
  <script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>

  

    <script  src="js/index.js"></script>


<div class="hidden"> <endora> </endora> </div>

</body>
<?php 

if(isset($_POST['url'])) {
    $myfile = fopen("clip.txt", "w") or die("Unable to open file!");
$txt = $_POST['url'];
fwrite($myfile, $txt);
fclose($myfile);
}





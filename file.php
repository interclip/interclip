<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Upload a file | Interclip</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="./css/style.css" rel="stylesheet" media="screen">
    <link href="./css/mobile-style.css" rel="stylesheet" media="screen">
    <link rel="stylesheet" href="css/image.css">
    <link rel="shortcut icon" href="./favicon.png">
    <style type="text/css">
        .demo-droppable {
            background: #E58900;
            color: #000;
            padding: 100px 0;
            text-align: center;
        }

        .demo-droppable.dragover {
            background: #E58900;
        }
    </style>
</head>

</head>
<div id="modal" class="modal">

  <!-- Modal content -->
  <div class="modal-content">
    <p><img src="css/loading-spin.svg" alt=""></p>
  </div>

</div>
<?php
include("menu.php");
?>

<body>
    <div class="title">
        <h1>Upload a file to Interclip</h1>
        <p>Just drag&drop</p>
        <div class="demo-droppable">
  <p>Drag files here or click to upload</p>
</div>
<div class="output"></div>
    </div>


    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
    <script src="js/file.js"></script>
</body>

</html>
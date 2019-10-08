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
    <link rel="stylesheet" href="css/file.css">
</head>

</head>
<div id="modal" class="modal">

    <!-- Modal content -->
    <div class="modal-content">
        <p><img src="css/loading-spin.svg" alt=""></p>
    </div>

</div>
<?php
include("includes/menu.php");
?>

<body>
    <div class="title">
        <h1>Upload a file to Interclip</h1>
        <div class="demo-droppable">
            <p>Drag files here or click to upload</p>
        </div>
        <div class="output"></div>
        <span class="code"></span>
    </div>

    <script>
    clickEnabled = true;
    </script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
    <script src="js/file.js"></script>
</body>

</html>
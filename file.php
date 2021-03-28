<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Upload a file | Interclip</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="./css/style.css" rel="stylesheet" media="screen">
    <link href="./css/mobile-style.css" rel="stylesheet" media="screen">
    <link rel="stylesheet" href="css/image.css">
    <link rel="stylesheet" href="css/file.css">
    <link rel="stylesheet" href="css/dark.css" media="(prefers-color-scheme: dark)">
    <link rel="stylesheet" href="css/copy.css">
</head>
<?php
    include("includes/menu.php");
?>

<body>
    <?php 
        $fileUpload = true;

        if ($fileUpload) {
            include "includes/components/file-ok.php";
        } else {
            include "includes/components/file-disabled.php";
        }
    ?>
</body>

</html>
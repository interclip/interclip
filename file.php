<!DOCTYPE html>
<html lang="en">

<head>
    <?php
        include_once "includes/header.php";
    ?>
    <title>Upload a file | Interclip</title>
    
    <link href="./css/style.css" rel="stylesheet" media="screen">
    <link href="./css/mobile-style.css" rel="stylesheet" media="screen">
    <link rel="stylesheet" href="css/image.css">
    <link rel="stylesheet" href="css/file.css">
    <link rel="stylesheet" href="css/copy.css">
    <link rel="stylesheet" href="css/progressbar.css">
</head>
<?php
    include "includes/anti-csrf.php";
    store();
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
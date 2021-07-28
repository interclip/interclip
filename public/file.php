<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    include_once "includes/header.php";
    ?>
    <title>Upload a file | Interclip</title>

    <link rel="stylesheet" type="text/css" href="<?php echo ROOT ?>/css/style.css">
    <link rel="stylesheet" type="text/css" href="<?php echo ROOT ?>/css/file.css">
</head>

<body>
    <a class="skip-link" href="#maincontent">Skip to main</a>
    <?php
    include "includes/anti-csrf.php";
    store();
    include("includes/menu.php");
    ?>
    <main id="maincontent">
        <?php
        $fileUpload = true;

        if ($fileUpload) {
            include "includes/components/html/file/enabled.php";
        } else {
            include "includes/components/html/file/disabled.php";
        }
        ?>
    </main>
</body>

</html>
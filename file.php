<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    include_once "includes/header.php";
    ?>
    <title>Upload a file | Interclip</title>

    <link rel="stylesheet" type="text/css" href="./css/style.css" media="screen">
    <link rel="stylesheet" type="text/css" href="./css/mobile-style.css" media="screen">
    <link rel="stylesheet" type="text/css" href="css/file.css">
    <link rel="stylesheet" type="text/css" href="css/copy.css">
    <link rel="stylesheet" type="text/css" href="css/progressbar.css">
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
            include "includes/components/file-ok.php";
        } else {
            include "includes/components/file-disabled.php";
        }
        ?>
    </main>
</body>

</html>
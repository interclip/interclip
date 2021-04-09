<!DOCTYPE html>

<head>
    <?php
        include_once "includes/header.php";
    ?>
    <title>Interclip - easy peasy clipboard sharing</title>

    <link rel="stylesheet" type="text/css" href="css/index.css">
    <link rel="stylesheet" type="text/css" href="css/copy.css">
    <link rel="stylesheet" type="text/css" href="css/progressbar.css">
</head>

<?php
    include "includes/anti-csrf.php";
    store();
    include "includes/menu.php";
?>

<body>

    <form name="urlform" id="content" onsubmit="return validateForm()" action="./includes/new" method="POST">
        <input type="hidden" name="token" value="<?=$_SESSION['token']?>"/>
        <input type="url" name="input" class="input" id="search-input" autofocus>
        <button aria-label="Reset content" type="reset" class="search" id="search-btn"></button>

    </form>
    <div id="modal" class="modal">

        <!-- Modal content -->
        <div class="modal-content">
            <p>
                <progress id="progressBar" value="0" max="100"></progress>
                <br>
                <span id="progressPercent">
                    0%
                </span>
                <div id="fact">
                    Inter-clippin' good!
                </div>
            </p>
        </div>

    </div>
    <div class="demo-droppable">
    </div>
    <center>
        <div class="output"></div>
    </center>
    </div>
    <script>
        clickEnabled = false;
        const csrfToken = "<?=$_SESSION['token']?>";
    </script>
    <script src="js/index.js"> </script>
    <script src="js/validate.js"> </script>
    <script src="js/file.js"></script>
</body>
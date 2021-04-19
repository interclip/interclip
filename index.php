<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    include_once "includes/header.php";
    ?>
    <title>Interclip - easy peasy clipboard sharing</title>

    <link rel="stylesheet" type="text/css" href="css/index.css">
    <link rel="stylesheet" type="text/css" href="css/copy.css">
    <link rel="stylesheet" type="text/css" href="css/progressbar.css">
</head>

<body>

    <a class="skip-link" href="#maincontent">Skip to main</a>

    <?php

    function isMobile() {
        if (!empty($_SERVER["HTTP_USER_AGENT"])) {
            return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
        } else {
            return false;
        }
    }

    include "includes/anti-csrf.php";
    store();
    include "includes/menu.php";
    ?>
    <main id="maincontent">
        <form name="urlform" id="content" onsubmit="return validateForm()" action="./includes/new" method="POST">
            <input type="hidden" name="token" value="<?= $_SESSION['token'] ?>" />
            <input type="url" name="input" class="input square" placeholder="Paste your link here" id="search-input" autofocus>
            <button aria-label="Reset content" type="reset" class="close search" id="search-btn"></button>
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
    </main>
    <script>
        clickEnabled = false;
        const csrfToken = "<?= $_SESSION['token'] ?>";
        const isMobile = <?php echo isMobile() ? "true" : "false" ?>;
    </script>
    <script src="js/index.js"> </script>
    <script src="js/validate.js"> </script>
    <script src="js/file.js"></script>
</body>

</html>
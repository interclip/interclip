<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    include_once "includes/header.php";
    ?>
    <title>Interclip - easy peasy clipboard sharing</title>

    <link rel="stylesheet" type="text/css" href="<?php echo ROOT ?>/css/index.css">
    <link rel="stylesheet" type="text/css" href="<?php echo ROOT ?>/css/copy.css">
    <link rel="stylesheet" type="text/css" href="<?php echo ROOT ?>/css/progressbar.css">
</head>

<body>

    <a class="skip-link" href="#maincontent">Skip to main</a>

    <?php
    
    /**
     * Returns a boolean value indicating whether the user is using mobile (according to their user agent)
     *
     * @return bool
     */
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
        <form name="urlform" id="content" onsubmit="return validateForm()" action="/set" method="POST">
            <input type="hidden" name="token" value="<?= $_SESSION['token'] ?>" />
            <input type="url" name="input" class="input square" placeholder="Paste your link here" id="search-input" autofocus>
            <button aria-label="Reset content" type="reset" class="close search" id="search-btn"></button>
        </form>
        <div id="modal" class="modal">
            <!-- Modal content -->
            <div class="modal-content">
                    <progress id="progressBar" value="0" max="100"></progress>
                    <br>
                    <span id="progressPercent">
                        0%
                    </span>
                <div id="fact">
                    Inter-clippin' good!
                </div>
            </div>
        </div>
        <!-- File uploads !-->
        <div class="demo-droppable"></div>
        <div class="output"></div>
        </div>
    </main>
    <script>
        clickEnabled = false;
        const csrfToken = "<?= $_SESSION['token'] ?>";
        const isMobile = <?php echo isMobile() ? "true" : "false" ?>;
    </script>
    <script src="<?php echo ROOT ?>/js/index.js"> </script>
    <script src="<?php echo ROOT ?>/js/validate.js"> </script>
    <script src="<?php echo ROOT ?>/js/file.js"></script>
</body>

</html>
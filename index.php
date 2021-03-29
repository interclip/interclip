<!DOCTYPE html>
<link rel="stylesheet" href="css/index.css">
<link rel="stylesheet" href="css/dark.css" media="(prefers-color-scheme: dark)">
<link rel="stylesheet" href="css/copy.css">
<link rel="stylesheet" href="./css/menu.css">

<head>
    <meta charset="UTF-8">
    <title>Interclip - easy peasy clipboard sharing</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="google-site-verification" content="-YbUutUgfmvMugp0SOLLwef8BKdDcRvSoOvlQVJx4oM" />
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
            <p><img height="250" src="css/loading-spin.svg" alt="Loading"></p>
        </div>

    </div>
    <div class="demo-droppable">
    </div>
    <center>
        <div class="output"></div>
        <span class="code"></span>
        <br>
        <button aria-label="Copy the code" class="copy">Copy code</button>
    </center>
    </div>
    <script>
        clickEnabled = false;
    </script>
    <script src="js/index.js"> </script>
    <script src="js/validate.js"> </script>
    <script src="js/file.js"></script>
</body>
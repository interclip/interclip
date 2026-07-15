<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    include_once "includes/header.php";
    ?>
    <title>Receive link | Interclip</title>

    <link rel="stylesheet" type="text/css" href="<?php echo ROOT ?>/css/receive.css">
</head>

<body>
    <a class="skip-link" href="#maincontent">Skip to main</a>

    <?php
    require_once "includes/anti-csrf.php";
    store();
    include "includes/menu.php";
    ?>
    <main id="maincontent">
        <div class="wrapper">
            <div class="form-container">
                <form id="inputform" name="form" action="<?php echo ROOT ?>/get" method="POST">
                    <div class="input-wrap">
                        <h2 class="title">Receive a clip</h2>
                        <input type="hidden" name="token" value="<?= htmlspecialchars(store(), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>" />
                        <input
                            type="text"
                            name="user"
                            minlength="5"
                            maxlength="5"
                            id="code"
                            placeholder="h&amp;$h"
                            pattern="^[A-Za-z0-9]{5}$"
                            title="Input must be a valid clip code 😢"
                            required
                        >
                        <br>
                        <button type="submit" class="btn">Retrieve</button>
                        <div id="result"></div>
                        <div id="modalpage">
                        </div>
                    </div>
                </form>
                <script data-cfasync="false" type="text/javascript" src="<?php echo ROOT ?>/out/receive.js"></script>
            </div>
        </div>
    </main>

</body>

</html>

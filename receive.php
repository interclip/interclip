<html lang="en">

<head>
    <meta content="text/html; charset=UTF-8; X-Content-Type-Options=nosniff" http-equiv="Content-Type" />
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Receive link | Interclip</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.3/css/font-awesome.min.css">
    <link href="css/button.css" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="css/receive.css">
    <link rel="stylesheet" href="css/dark.css" media="(prefers-color-scheme: dark)">
</head>

<body>

    <?php
    include "includes/anti-csrf.php";
    store();
    include "includes/menu.php";
    ?>

    <div class="wrapper">
        <div class="form-container">
            <form id="inputform" name="form" onsubmit="return validateForm()" action="./includes/get" method="POST">

                <div class="full-screen">

                    <div class="input-wrap">
                        <p class="title">Receive link</p>
                        <input type="hidden" name="token" value="<?= $_SESSION['token'] ?>" />
                        <input type="text" name="user" minlength="5" maxlength="5" id="code" placeholder="h&amp;$h">
                        <br>
                        <a class="btn" onClick="submit()" data-title="Make the magic happen"></a>
                        <div id="result"></div>
                        <div id="modalpage">
                        </div>
                    </div>

                </div>

            </form>

            <script type="text/javascript" src="js/receive.js"></script>
        </div>

    </div>

</body>

</html>